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
 * Articulo admin edit tabs
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_Block_Adminhtml_Articulo_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('articulo_info_tabs');
        $this->setDestElementId('articulo_tab_content');
        $this->setTitle(Mage::helper('myextension_mymodule')->__('Articulo Information'));
        $this->setTemplate('widget/tabshoriz.phtml');
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return MyExtension_Mymodule_Block_Adminhtml_Articulo_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        $articulo = $this->getArticulo();
        $entity = Mage::getModel('eav/entity_type')
            ->load('myextension_mymodule_articulo', 'entity_type_code');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($entity->getEntityTypeId());
        $attributes->addFieldToFilter(
            'attribute_code',
            array(
                'nin' => array('meta_title', 'meta_description', 'meta_keywords')
            )
        );
        $attributes->getSelect()->order('additional_table.position', 'ASC');

        $this->addTab(
            'info',
            array(
                'label'   => Mage::helper('myextension_mymodule')->__('Articulo Information'),
                'content' => $this->getLayout()->createBlock(
                    'myextension_mymodule/adminhtml_articulo_edit_tab_attributes'
                )
                ->setAttributes($attributes)
                ->setAddHiddenFields(true)
                ->toHtml(),
            )
        );
        $seoAttributes = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter($entity->getEntityTypeId())
            ->addFieldToFilter(
                'attribute_code',
                array(
                    'in' => array('meta_title', 'meta_description', 'meta_keywords')
                )
            );
        $seoAttributes->getSelect()->order('additional_table.position', 'ASC');

        $this->addTab(
            'meta',
            array(
                'label'   => Mage::helper('myextension_mymodule')->__('Meta'),
                'title'   => Mage::helper('myextension_mymodule')->__('Meta'),
                'content' => $this->getLayout()->createBlock(
                    'myextension_mymodule/adminhtml_articulo_edit_tab_attributes'
                )
                ->setAttributes($seoAttributes)
                ->toHtml(),
            )
        );
        $this->addTab(
            'products',
            array(
                'label'   => Mage::helper('myextension_mymodule')->__('Associated Products'),
                'content' => $this->getLayout()->createBlock(
                    'myextension_mymodule/adminhtml_articulo_edit_tab_product',
                    'articulo.product.grid'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'categories',
            array(
                'label'   => Mage::helper('myextension_mymodule')->__('Associated Categories'),
                'content' => $this->getLayout()->createBlock(
                    'myextension_mymodule/adminhtml_articulo_edit_tab_categories',
                    'articulo.category.tree'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve articulo entity
     *
     * @access public
     * @return MyExtension_Mymodule_Model_Articulo
     * @author Ultimate Module Creator
     */
    public function getArticulo()
    {
        return Mage::registry('current_articulo');
    }
}
