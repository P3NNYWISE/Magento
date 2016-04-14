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
 * Articulo product model
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_Model_Articulo_Product extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource
     *
     * @access protected
     * @return void
     * @author Ultimate Module Creator
     */
    protected function _construct()
    {
        $this->_init('myextension_mymodule/articulo_product');
    }

    /**
     * Save data for articulo-product relation
     * @access public
     * @param  MyExtension_Mymodule_Model_Articulo $articulo
     * @return MyExtension_Mymodule_Model_Articulo_Product
     * @author Ultimate Module Creator
     */
    public function saveArticuloRelation($articulo)
    {
        $data = $articulo->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveArticuloRelation($articulo, $data);
        }
        return $this;
    }

    /**
     * get products for articulo
     *
     * @access public
     * @param MyExtension_Mymodule_Model_Articulo $articulo
     * @return MyExtension_Mymodule_Model_Resource_Articulo_Product_Collection
     * @author Ultimate Module Creator
     */
    public function getProductCollection($articulo)
    {
        $collection = Mage::getResourceModel('myextension_mymodule/articulo_product_collection')
            ->addArticuloFilter($articulo);
        return $collection;
    }
}
