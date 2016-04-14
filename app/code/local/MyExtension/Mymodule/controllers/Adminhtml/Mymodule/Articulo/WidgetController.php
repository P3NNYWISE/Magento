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
 * Articulo admin widget controller
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_Adminhtml_Mymodule_Articulo_WidgetController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Chooser Source action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function chooserAction()
    {
        $uniqId = $this->getRequest()->getParam('uniq_id');
        $grid = $this->getLayout()->createBlock(
            'myextension_mymodule/adminhtml_articulo_widget_chooser',
            '',
            array(
                'id' => $uniqId,
            )
        );
        $this->getResponse()->setBody($grid->toHtml());
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
        if ($articuloId = (int) $this->getRequest()->getPost('id')) {
            $articulo = Mage::getModel('myextension_mymodule/articulo')->load($articuloId);
            if ($articulo->getId()) {
                Mage::register('articulo', $articulo);
                Mage::register('current_articulo', $articulo);
            }
            $this->getResponse()->setBody(
                $this->_getArticuloTreeBlock()->getTreeJson($articulo)
            );
        }
    }

    /**
     * get articulo tree block
     *
     * @access protected
     * @return MyExtension_Mymodule_Block_Adminhtml_Articulo_Widget_Chooser
     * @author Ultimate Module Creator
     */
    protected function _getArticuloTreeBlock()
    {
        return $this->getLayout()->createBlock(
            'myextension_mymodule/adminhtml_articulo_widget_chooser',
            '',
            array(
                'id' => $this->getRequest()->getParam('uniq_id'),
                'use_massaction' => $this->getRequest()->getParam('use_massaction', false)
            )
        );
    }
}
