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

class Dropfin_Fbconnect_Model_Resource_Setup extends Mage_Eav_Model_Entity_Setup
{
    protected $_customerAttributes = array();

    public function setCustomerAttributes($customerAttributes)
    {
        $this->_customerAttributes = $customerAttributes;
        return $this;
    }
    
   /**
     * Add our custom attributes
     *
     * @return Mage_Eav_Model_Entity_Setup
     */
    public function installCustomerAttributes()
    {        
        foreach ($this->_customerAttributes as $code => $attr) {
            $this->addAttribute('customer', $code, $attr);
        }

        return $this;
    }

    /**
     * Remove custom attributes
     *
     * @return Mage_Eav_Model_Entity_Setup
     */
    public function removeCustomerAttributes()
    {
        foreach ($this->_customerAttributes as $code => $attr) {
            $this->removeAttribute('customer', $code);
        }

        return $this;
    }  
}
