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
 * Articulo widget block
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_Block_Articulo_Widget_View extends Mage_Core_Block_Template implements
    Mage_Widget_Block_Interface
{
    protected $_htmlTemplate = 'myextension_mymodule/articulo/widget/view.phtml';

    /**
     * Prepare a for widget
     *
     * @access protected
     * @return MyExtension_Mymodule_Block_Articulo_Widget_View
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
        $articuloId = $this->getData('articulo_id');
        if ($articuloId) {
            $articulo = Mage::getModel('myextension_mymodule/articulo')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($articuloId);
            if ($articulo->getStatusPath()) {
                $this->setCurrentArticulo($articulo);
                $this->setTemplate($this->_htmlTemplate);
            }
        }
        return $this;
    }
}
