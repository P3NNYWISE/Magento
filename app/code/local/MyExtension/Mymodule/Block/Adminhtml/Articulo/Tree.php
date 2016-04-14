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
 * Articulo admin tree block
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_Block_Adminhtml_Articulo_Tree extends MyExtension_Mymodule_Block_Adminhtml_Articulo_Abstract
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('myextension_mymodule/articulo/tree.phtml');
        $this->setUseAjax(true);
        $this->_withProductCount = true;
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return MyExtension_Mymodule_Block_Adminhtml_Articulo_Tree
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        $addUrl = $this->getUrl(
            "*/*/add",
            array(
                '_current'=>true,
                'id'=>null,
                '_query' => false
            )
        );

        $this->setChild(
            'add_sub_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(
                    array(
                        'label'   => Mage::helper('myextension_mymodule')->__('Add Child Articulo'),
                        'onclick' => "addNew('".$addUrl."', false)",
                        'class'   => 'add',
                        'id'      => 'add_child_articulo_button',
                        'style'   => $this->canAddChild() ? '' : 'display: none;'
                    )
                )
        );

        $this->setChild(
            'add_root_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(
                    array(
                        'label'   => Mage::helper('myextension_mymodule')->__('Add Root Articulo'),
                        'onclick' => "addNew('".$addUrl."', true)",
                        'class'   => 'add',
                        'id'      => 'add_root_articulo_button'
                    )
                )
        );
        $this->setChild(
            'store_switcher',
            $this->getLayout()->createBlock('adminhtml/store_switcher')
                ->setSwitchUrl(
                    $this->getUrl(
                        '*/*/*',
                        array(
                            '_current' =>true,
                            '_query'=>false,
                            'store'=>null
                        )
                    )
                )
                ->setTemplate('store/switcher/enhanced.phtml')
        );
        return parent::_prepareLayout();
    }

    /**
     * get the articulo collection
     *
     * @access public
     * @return MyExtension_Mymodule_Model_Resource_Articulo_Collection
     * @author Ultimate Module Creator
     */
    public function getArticuloCollection()
    {
        $collection = $this->getData('articulo_collection');
        if (is_null($collection)) {
            $collection = Mage::getModel('myextension_mymodule/articulo')->getCollection();
            $collection->addAttributeToSelect('titulo')->addAttributeToSelect('status');
            $this->setData('articulo_collection', $collection);
        }
        return $collection;
    }

    /**
     * get html for add root button
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getAddRootButtonHtml()
    {
        return $this->getChildHtml('add_root_button');
    }

    /**
     * get html for add child button
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getAddSubButtonHtml()
    {
        return $this->getChildHtml('add_sub_button');
    }

    /**
     * get html for expand button
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getExpandButtonHtml()
    {
        return $this->getChildHtml('expand_button');
    }

    /**
     * get html for add collapse button
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getCollapseButtonHtml()
    {
        return $this->getChildHtml('collapse_button');
    }

    /**
     * get url for tree load
     *
     * @access public
     * @param mxed $expanded
     * @return string
     * @author Ultimate Module Creator
     */
    public function getLoadTreeUrl($expanded=null)
    {
        $params = array('_current' => true, 'id' => null, 'store' => null);
        if ((is_null($expanded) &&
            Mage::getSingleton('admin/session')->getArticuloIsTreeWasExpanded()) ||
            $expanded == true) {
            $params['expand_all'] = true;
        }
        return $this->getUrl('*/*/articulosJson', $params);
    }

    /**
     * get url for loading nodes
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getNodesUrl()
    {
        return $this->getUrl('*/mymodule_articulos/jsonTree');
    }

    /**
     * check if tree is expanded
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getIsWasExpanded()
    {
        return Mage::getSingleton('admin/session')->getArticuloIsTreeWasExpanded();
    }

    /**
     * get url for moving articulo
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getMoveUrl()
    {
        return $this->getUrl('*/mymodule_articulo/move');
    }

    /**
     * get the tree as json
     *
     * @access public
     * @param mixed $parentNodeArticulo
     * @return string
     * @author Ultimate Module Creator
     */
    public function getTree($parentNodeArticulo = null)
    {
        $rootArray = $this->_getNodeJson($this->getRoot($parentNodeArticulo));
        $tree = isset($rootArray['children']) ? $rootArray['children'] : array();
        return $tree;
    }

    /**
     * get the tree as json
     *
     * @access public
     * @param mixed $parentNodeArticulo
     * @return string
     * @author Ultimate Module Creator
     */
    public function getTreeJson($parentNodeArticulo = null)
    {
        $rootArray = $this->_getNodeJson($this->getRoot($parentNodeArticulo));
        $json = Mage::helper('core')->jsonEncode(isset($rootArray['children']) ? $rootArray['children'] : array());
        return $json;
    }

    /**
     * Get JSON of array of articulos, that are breadcrumbs for specified articulo path
     *
     * @access public
     * @param string $path
     * @param string $javascriptVarName
     * @return string
     * @author Ultimate Module Creator
     */
    public function getBreadcrumbsJavascript($path, $javascriptVarName)
    {
        if (empty($path)) {
            return '';
        }

        $articulos = Mage::getResourceSingleton('myextension_mymodule/articulo_tree')
            ->loadBreadcrumbsArray($path);
        if (empty($articulos)) {
            return '';
        }
        foreach ($articulos as $key => $articulo) {
            $articulos[$key] = $this->_getNodeJson($articulo);
        }
        return
            '<script type="text/javascript">'
            . $javascriptVarName . ' = ' . Mage::helper('core')->jsonEncode($articulos) . ';'
            . ($this->canAddChild() ? '$("add_child_articulo_button").show();' : '$("add_child_articulo_button").hide();')
            . '</script>';
    }

    /**
     * Get JSON of a tree node or an associative array
     *
     * @access protected
     * @param Varien_Data_Tree_Node|array $node
     * @param int $level
     * @return string
     * @author Ultimate Module Creator
     */
    protected function _getNodeJson($node, $level = 0)
    {
        // create a node from data array
        if (is_array($node)) {
            $node = new Varien_Data_Tree_Node($node, 'entity_id', new Varien_Data_Tree);
        }
        $item = array();
        $item['text'] = $this->buildNodeName($node);
        $item['id']   = $node->getId();
        $item['path'] = $node->getData('path');
        $item['cls']  = 'folder';
        if ($node->getStatus()) {
            $item['cls'] .= ' active-category';
        } else {
            $item['cls'] .= ' no-active-category';
        }
        $item['allowDrop'] = true;
        $item['allowDrag'] = true;
        if ((int)$node->getChildrenCount()>0) {
            $item['children'] = array();
        }
        $isParent = $this->_isParentSelectedArticulo($node);
        if ($node->hasChildren()) {
            $item['children'] = array();
            if (!($this->getUseAjax() && $node->getLevel() > 1 && !$isParent)) {
                foreach ($node->getChildren() as $child) {
                    $item['children'][] = $this->_getNodeJson($child, $level+1);
                }
            }
        }
        if ($isParent || $node->getLevel() < 1) {
            $item['expanded'] = true;
        }
        return $item;
    }

    /**
     * Get node label
     *
     * @access public
     * @param Varien_Object $node
     * @return string
     * @author Ultimate Module Creator
     */
    public function buildNodeName($node)
    {
        $result = $this->escapeHtml($node->getTitulo());
        return $result;
    }

    /**
     * check if entity is movable
     *
     * @access protected
     * @param Varien_Object $node
     * @return bool
     * @author Ultimate Module Creator
     */
    protected function _isArticuloMoveable($node)
    {
        return true;
    }

    /**
     * check if parent is selected
     *
     * @access protected
     * @param Varien_Object $node
     * @return bool
     * @author Ultimate Module Creator
     */
    protected function _isParentSelectedArticulo($node)
    {
        if ($node && $this->getArticulo()) {
            $pathIds = $this->getArticulo()->getPathIds();
            if (in_array($node->getId(), $pathIds)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if page loaded by outside link to articulo edit
     *
     * @access public
     * @return boolean
     * @author Ultimate Module Creator
     */
    public function isClearEdit()
    {
        return (bool) $this->getRequest()->getParam('clear');
    }

    /**
     * Check availability of adding root articulo
     *
     * @access public
     * @return boolean
     * @author Ultimate Module Creator
     */
    public function canAddRootArticulo()
    {
        return true;
    }

    /**
     * Check availability of adding child articulo
     *
     * @access public
     * @return boolean
     */
    public function canAddChild()
    {
        return true;
    }

    /**
     * get store switcher html
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getStoreSwitcherHtml()
    {
        return $this->getChildHtml('store_switcher');
    }

    /**
     * get current store
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store');
        return Mage::app()->getStore($storeId);
    }

    /**
     * get switch url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getSwitchTreeUrl()
    {
        return $this->getUrl(
            "*/mymodule_articulo/tree",
            array(
                '_current'=>true,
                'store'=>null,
                '_query'=>false,
                'id'=>null,
                'parent'=>null
            )
        );
    }

}
