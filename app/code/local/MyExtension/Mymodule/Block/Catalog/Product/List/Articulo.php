<?php
/**
 * MyExtension_Mymodule extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       MyExtension
 * @package        MyExtension_Mymodule
 * @copyright      Copyright (c) 2016
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Articulo list on product page block
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_Block_Catalog_Product_List_Articulo extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * get the list of articulos
     *
     * @access protected
     * @return MyExtension_Mymodule_Model_Resource_Articulo_Collection
     * @author Ultimate Module Creator
     */
    public function getArticuloCollection()
    {
        if (!$this->hasData('articulo_collection')) {
            $product = Mage::registry('product');
            $collection = Mage::getResourceSingleton('myextension_mymodule/articulo_collection')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->addAttributeToSelect('titulo', 1)
                ->addAttributeToFilter('status', 1)
                ->addProductFilter($product);
            $collection->getSelect()->order('related_product.position', 'ASC');
            $this->setData('articulo_collection', $collection);
        }
        return $this->getData('articulo_collection');
    }
}
