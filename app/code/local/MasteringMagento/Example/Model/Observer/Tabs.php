<?php
/**
 * app/code/local/MasteringMagento/Example/Model/Observer/Sales.php
 *
 * This example code is provided for use with the Mastering Magento video
 * series, by Packt Publishing.
 *
 * @author    Franklin P. Strube <franklin.strube@gmail.com>
 * @category  MasteringMagento
 * @package   Example
 * @copyright Copyright (c) 2012 Packt Publishing (http://packtpub.com)
 */
class MasteringMagento_Example_Model_Observer_Tabs
{
    public function RemoveTab($observer)
    {
           $block = $observer->getEvent()->getBlock();
           mage::log($block->getTabsIds());
/*
        #Borra Tab Meta Information
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs) {
                $block->removeTab('group_9');
            }


        #Borra Tab Image 
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs) {
                $block->removeTab('group_10');
            }
        #Borra Tab Recurrir Profile
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs) {
                $block->removeTab('group_11');
            }
    #Borra Tab Gift
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs) {
                $block->removeTab('group_17');
            }


        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs) {
                $block->removeTab('upsell');
            }
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs) {
                $block->removeTab('crosssell');
            }

        */
        return $this;
    }
}
