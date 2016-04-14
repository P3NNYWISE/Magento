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
 * Adminhtml articulo attribute edit page tabs
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_Block_Adminhtml_Articulo_Attribute_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('articulo_attribute_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('myextension_mymodule')->__('Attribute Information'));
    }

    /**
     * add attribute tabs
     *
     * @access protected
     * @return MyExtension_Mymodule_Adminhtml_Articulo_Attribute_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'main',
            array(
                'label'     => Mage::helper('myextension_mymodule')->__('Properties'),
                'title'     => Mage::helper('myextension_mymodule')->__('Properties'),
                'content'   => $this->getLayout()->createBlock(
                    'myextension_mymodule/adminhtml_articulo_attribute_edit_tab_main'
                )
                ->toHtml(),
                'active'    => true
            )
        );
        $this->addTab(
            'labels',
            array(
                'label'     => Mage::helper('myextension_mymodule')->__('Manage Label / Options'),
                'title'     => Mage::helper('myextension_mymodule')->__('Manage Label / Options'),
                'content'   => $this->getLayout()->createBlock(
                    'myextension_mymodule/adminhtml_articulo_attribute_edit_tab_options'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }
}
