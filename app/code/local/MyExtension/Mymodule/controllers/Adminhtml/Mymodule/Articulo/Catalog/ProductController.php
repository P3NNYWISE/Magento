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
 * Articulo - product controller
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class MyExtension_Mymodule_Adminhtml_Mymodule_Articulo_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{

    /**
     * articulos action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function articulosAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * articulos json action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function articulosJsonAction()
    {
        $product = $this->_initProduct();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock(
                'myextension_mymodule/adminhtml_catalog_product_edit_tab_articulo'
            )
            ->getArticuloChildrenJson($this->getRequest()->getParam('articulo'))
        );
    }
}
