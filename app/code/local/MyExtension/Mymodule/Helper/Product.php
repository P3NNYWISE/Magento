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
 * Product helper
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_Helper_Product extends MyExtension_Mymodule_Helper_Data
{

    /**
     * get the selected articulos for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return array()
     * @author Ultimate Module Creator
     */
    public function getSelectedArticulos(Mage_Catalog_Model_Product $product)
    {
        if (!$product->hasSelectedArticulos()) {
            $articulos = array();
            foreach ($this->getSelectedArticulosCollection($product) as $articulo) {
                $articulos[] = $articulo;
            }
            $product->setSelectedArticulos($articulos);
        }
        return $product->getData('selected_articulos');
    }

    /**
     * get articulo collection for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return MyExtension_Mymodule_Model_Resource_Articulo_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedArticulosCollection(Mage_Catalog_Model_Product $product)
    {
        $collection = Mage::getResourceSingleton('myextension_mymodule/articulo_collection')
            ->addProductFilter($product);
        return $collection;
    }
}
