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

class Dropfin_Fbconnect_Helper_Facebook extends Mage_Core_Helper_Abstract {

	public function getUserByFacebookId(Mage_Customer_Model_Customer $customer, $facebookId, $token) {

        $customer->setFbconnectFid($facebookId)
                ->setFbconnectFtoken($token)
                ->save();
        
        Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
    }

    public function createAccount($email, $firstName, $lastName, $facebookId, $token) {

        $customer = Mage::getModel('customer/customer');

        $customer->setWebsiteId(Mage::app()->getWebsite()->getId())
                ->setEmail($email)
                ->setFirstname($firstName)
                ->setLastname($lastName)
                ->setFbconnectFid($facebookId)
                ->setFbconnectFtoken($token)
                ->setPassword($customer->generatePassword(10))
                ->save();

        $customer->setConfirmation(null);
        $customer->save();

        $customer->sendNewAccountEmail('confirmed', '', Mage::app()->getStore()->getId());

        Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);            

    }

    public function loginByCustomer(Mage_Customer_Model_Customer $customer) {

        if($customer->getConfirmation()) {
            $customer->setConfirmation(null);
            $customer->save();
        }

        Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);        
    }

	public function getCustomersByFacebookId($facebookId) {

        $customer = Mage::getModel('customer/customer');

        $collection = $customer->getCollection()
        	->addAttributeToFilter('fbconnect_fid', $facebookId)
            ->setPageSize(1);

        if($customer->getSharingConfig()->isWebsiteScope()) {
            $collection->addAttributeToFilter(
                'website_id',
                Mage::app()->getWebsite()->getId()
            );
        }

        if(Mage::getSingleton('customer/session')->isLoggedIn()) {
            $collection->addFieldToFilter(
                'entity_id',
                array('neq' => Mage::getSingleton('customer/session')->getCustomerId())
            );
        }

        return $collection;
    }

    public function getCustomersByEmail($email) {

        $customer = Mage::getModel('customer/customer');

        $collection = $customer->getCollection()
                ->addFieldToFilter('email', $email)
                ->setPageSize(1);

        if($customer->getSharingConfig()->isWebsiteScope()) {
            $collection->addAttributeToFilter(
                'website_id',
                Mage::app()->getWebsite()->getId()
            );
        }  
        
        if(Mage::getSingleton('customer/session')->isLoggedIn()) {
            $collection->addFieldToFilter(
                'entity_id',
                array('neq' => Mage::getSingleton('customer/session')->getCustomerId())
            );
        }        
        
        return $collection;
    }    

}