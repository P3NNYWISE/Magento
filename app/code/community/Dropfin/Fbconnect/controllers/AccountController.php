<?php

/**
 * Dropfin
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade 
 * this extension to newer versions in the future. 
 *
 * @category    Dropfin
 * @package     Facebook Connect
 * @copyright   Copyright (c) Dropfin (http://www.dropfin.com)
 */

class Dropfin_Fbconnect_AccountController extends Mage_Core_Controller_Front_Action {

	protected function _getSession() {
        return Mage::getSingleton('customer/session');
    }

    protected function _getLanguage() {
        return Mage::helper('dropfin_fbconnect');
    }

    protected function _closeWindow() {
    	if($this->getRequest()->isXmlHttpRequest()) {
    		$this->getResponse()->clearHeaders()->setHeader('Content-type', 'application/json', true);
    		$this->getResponse()->setBody(json_encode(array(
    			'windowClose' => true
    		)));
    	}else{
    		$this->getResponse()->setBody('<script type="text/javascript">window.opener.location.reload(true);window.close();</script>');
    	}
    	return true;
    }

    protected function _connectCallback() {
        $errorCode = $this->getRequest()->getParam('error');
        $code = $this->getRequest()->getParam('code');
        $state = $this->getRequest()->getParam('state');
        if(!($errorCode || $code) && !$state) {
            // Direct route access - deny
            return;
        }

        if(!$state || $state != Mage::getSingleton('core/session')->getFacebookCsrf()) {
            return;
        }

        if($errorCode) {
            // Facebook API read light - abort
            if($errorCode === 'access_denied') {
                Mage::getSingleton('core/session')->addNotice(
                    $this->_getLanguage()->__('Facebook connect process aborted.')
                );
                return;
            }

            throw new Exception(
                sprintf(
                    $this->_getLanguage()->__('Sorry, Please try again.'),
                    $errorCode
                )
            );
            return;
        }

        if ($code) {

            // Facebook API green light - proceed
            $connect = Mage::getSingleton('fbconnect/facebook_connect');
            $facebookHelper = Mage::helper('dropfin_fbconnect/facebook');

            $userInfo = $connect->api('/me');
            $token = $connect->getAccessToken();

            $customersByFacebookId = $facebookHelper->getCustomersByFacebookId($userInfo->id);

            if($this->_getSession()->isLoggedIn()) {

                // Logged in user
                if($customersByFacebookId->count()) {
                    // Facebook account already connected to other account - deny
                    Mage::getSingleton('core/session')->addNotice(
                        $this->_getLanguage()->__('Your facebook account is already connected to the store accounts.')
                    );
                    return;
                }

                // Connect from account dashboard - attach
                $customer = $this->_getSession()->getCustomer();

                $facebookHelper->getUserByFacebookId(
                    $customer,
                    $userInfo->id,
                    $token
                );

                Mage::getSingleton('core/session')->addSuccess(
                    $this->_getLanguage()->__('Your facebook account is now connected to the store accout.')
                );

                return;
            }

            if($customersByFacebookId->count()) {
                // Existing connected user - login
                $customer = $customersByFacebookId->getFirstItem();

                $facebookHelper->loginByCustomer($customer);

                Mage::getSingleton('core/session')->addSuccess(
                    $this->_getLanguage()->__('You have successfully logged in using your facebook account.')
                );

                return;
            }

            $customersByEmail = $facebookHelper->getCustomersByEmail($userInfo->email);

            if($customersByEmail->count()) {

                $customer = $customersByEmail->getFirstItem();
                
                $facebookHelper->getUserByFacebookId(
                    $customer,
                    $userInfo->id,
                    $token
                );

                Mage::getSingleton('core/session')->addSuccess(
                    $this->_getLanguage()->__('We have discovered you already have an account at our store. Your facebook account is now connected to the store account.')
                );

                return;
            }

            // New connection - create, attach, login
            if(empty($userInfo->first_name)) {
                throw new Exception(
                    $this->_getLanguage()->__('Sorry, could not retrieve your facebook first name. Please try again.')
                );
            }

            if(empty($userInfo->last_name)) {
                throw new Exception(
                    $this->_getLanguage()->__('Sorry, could not retrieve your facebook last name. Please try again.')
                );
            }

            $facebookHelper->createAccount(
                $userInfo->email,
                $userInfo->first_name,
                $userInfo->last_name,
                $userInfo->id,
                $token
            );

            Mage::getSingleton('core/session')->addSuccess(
                $this->_getLanguage()->__('Your facebook account is now connected to our store.')
            );
        }
    }

	public function connectAction() {
        
		$session = $this->_getSession();
        if ($session->isLoggedIn()) {
            return $this->_closeWindow();
        }

        try {
            $this->_connectCallback();
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }

        return $this->_closeWindow();
    }
}