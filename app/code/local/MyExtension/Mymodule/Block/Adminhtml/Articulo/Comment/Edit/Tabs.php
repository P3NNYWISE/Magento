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
 * Articulo comment admin edit tabs
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_Block_Adminhtml_Articulo_Comment_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('articulo_comment_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('myextension_mymodule')->__('Articulo Comment'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return MyExtension_Mymodule_Block_Adminhtml_Articulo_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_articulo_comment',
            array(
                'label'   => Mage::helper('myextension_mymodule')->__('Articulo comment'),
                'title'   => Mage::helper('myextension_mymodule')->__('Articulo comment'),
                'content' => $this->getLayout()->createBlock(
                    'myextension_mymodule/adminhtml_articulo_comment_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_articulo_comment',
                array(
                    'label'   => Mage::helper('myextension_mymodule')->__('Store views'),
                    'title'   => Mage::helper('myextension_mymodule')->__('Store views'),
                    'content' => $this->getLayout()->createBlock(
                        'myextension_mymodule/adminhtml_articulo_comment_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve comment
     *
     * @access public
     * @return MyExtension_Mymodule_Model_Articulo_Comment
     * @author Ultimate Module Creator
     */
    public function getComment()
    {
        return Mage::registry('current_comment');
    }
}
