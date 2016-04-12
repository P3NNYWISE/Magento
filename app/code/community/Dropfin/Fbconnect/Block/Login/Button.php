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

class Dropfin_Fbconnect_Block_Login_Button extends Mage_Core_Block_Template
{
	protected $fbconnect = null;
    protected $userInfo = null;
    protected $redirectUri = null;

    protected function _construct() {
        parent::_construct();

        $this->fbconnect = Mage::getSingleton('fbconnect/facebook_connect');

        if(!Mage::helper('dropfin_fbconnect')->isLoginEnable()){
            return;
        }

        // CSRF protection
        Mage::getSingleton('core/session')->setFacebookCsrf($csrf = md5(uniqid(rand(), TRUE)));
        $this->fbconnect->setState($csrf);
        
        if(!($redirect = Mage::getSingleton('customer/session')->getBeforeAuthUrl())) {
            $redirect = Mage::helper('core/url')->getCurrentUrl();      
        }
        
        // Redirect uri
        Mage::getSingleton('core/session')->setFacebookRedirect($redirect);
    }

    protected function _getButtonUrl()
    {
        if(!Mage::getSingleton('customer/session')->isLoggedIn()){
            return $this->fbconnect->createAuthUrl();
        } else {
            return $this->getUrl('customer/account');
        }
    }

}