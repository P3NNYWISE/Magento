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
 * Articulo tab on product edit form
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_Block_Adminhtml_Catalog_Product_Edit_Tab_Articulo extends MyExtension_Mymodule_Block_Adminhtml_Articulo_Tree
{
    protected $_articuloIds = null;
    protected $_selectedNodes = null;

    /**
     * constructor
     * Specify template to use
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('myextension_mymodule/catalog/product/edit/tab/articulo.phtml');
    }

    /**
     * Retrieve currently edited product
     *
     * @access public
     * @return Mage_Catalog_Model_Product
     * @author Ultimate Module Creator
     */
    public function getProduct()
    {
        return Mage::registry('current_product');
    }

    /**
     * Return array with articulo IDs which the product is assigned to
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getArticuloIds()
    {
        if (is_null($this->_articuloIds)) {
            $selectedArticulos = Mage::helper('myextension_mymodule/product')->getSelectedArticulos($this->getProduct());
            $ids = array();
            foreach ($selectedArticulos as $articulo) {
                $ids[] = $articulo->getId();
            }
            $this->_articuloIds = $ids;
        }
        return $this->_articuloIds;
    }

    /**
     * Forms string out of getArticuloIds()
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getIdsString()
    {
        return implode(',', $this->getArticuloIds());
    }

    /**
     * Returns root node and sets 'checked' flag (if necessary)
     *
     * @access public
     * @return Varien_Data_Tree_Node
     * @author Ultimate Module Creator
     */
    public function getRootNode()
    {
        $root = $this->getRoot();
        if ($root && in_array($root->getId(), $this->getArticuloIds())) {
            $root->setChecked(true);
        }
        return $root;
    }

    /**
     * Returns root node
     *
     * @param MyExtension_Mymodule_Model_Articulo|null $parentNodeArticulo
     * @param int  $recursionLevel
     * @return Varien_Data_Tree_Node
     * @author Ultimate Module Creator
     */
    public function getRoot($parentNodeArticulo = null, $recursionLevel = 3)
    {
        if (!is_null($parentNodeArticulo) && $parentNodeArticulo->getId()) {
            return $this->getNode($parentNodeArticulo, $recursionLevel);
        }
        $root = Mage::registry('articulo_root');
        if (is_null($root)) {
            $rootId = Mage::helper('myextension_mymodule/articulo')->getRootArticuloId();

            $ids = $this->getSelectedArticuloPathIds($rootId);
            $tree = Mage::getResourceSingleton('myextension_mymodule/articulo_tree')
                ->loadByIds($ids, false, false);
            if ($this->getArticulo()) {
                $tree->loadEnsuredNodes($this->getArticulo(), $tree->getNodeById($rootId));
            }
            $tree->addCollectionData($this->getArticuloCollection());
            $root = $tree->getNodeById($rootId);
            Mage::register('articulo_root', $root);
        }
        return $root;
    }

    /**
     * Returns array with configuration of current node
     *
     * @access protected
     * @param Varien_Data_Tree_Node $node
     * @param int $level How deep is the node in the tree
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _getNodeJson($node, $level = 1)
    {
        $item = parent::_getNodeJson($node, $level);
        if ($this->_isParentSelectedArticulo($node)) {
            $item['expanded'] = true;
        }
        if (in_array($node->getId(), $this->getArticuloIds())) {
            $item['checked'] = true;
        }
        return $item;
    }

    /**
     * Returns whether $node is a parent (not exactly direct) of a selected node
     *
     * @access protected
     * @param Varien_Data_Tree_Node $node
     * @return bool
     * @author Ultimate Module Creator
     */
    protected function _isParentSelectedArticulo($node)
    {
        $result = false;
        // Contains string with all articulo IDs of children (not exactly direct) of the node
        $allChildren = $node->getAllChildren();
        if ($allChildren) {
            $selectedArticuloIds = $this->getArticuloIds();
            $allChildrenArr = explode(',', $allChildren);
            for ($i = 0, $cnt = count($selectedArticuloIds); $i < $cnt; $i++) {
                $isSelf = $node->getId() == $selectedArticuloIds[$i];
                if (!$isSelf && in_array($selectedArticuloIds[$i], $allChildrenArr)) {
                    $result = true;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * Returns array with nodes those are selected (contain current product)
     *
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _getSelectedNodes()
    {
        if ($this->_selectedNodes === null) {
            $this->_selectedNodes = array();
            $root = $this->getRoot();
            foreach ($this->getArticuloIds() as $articuloId) {
                if ($root) {
                    $this->_selectedNodes[] = $root->getTree()->getNodeById($articuloId);
                }
            }
        }
        return $this->_selectedNodes;
    }

    /**
     * Returns JSON-encoded array of articulo children
     *
     * @access public
     * @param int $articuloId
     * @return string
     * @author Ultimate Module Creator
     */
    public function getArticuloChildrenJson($articuloId)
    {
        $articulo = Mage::getModel('myextension_mymodule/articulo')->load($articuloId);
        $node = $this->getRoot($articulo, 1)->getTree()->getNodeById($articuloId);
        if (!$node || !$node->hasChildren()) {
            return '[]';
        }

        $children = array();
        foreach ($node->getChildren() as $child) {
            $children[] = $this->_getNodeJson($child);
        }
        return Mage::helper('core')->jsonEncode($children);
    }

    /**
     * Returns URL for loading tree
     *
     * @access public
     * @param null $expanded
     * @return string
     * @author Ultimate Module Creator
     */
    public function getLoadTreeUrl($expanded = null)
    {
        return $this->getUrl('*/*/articulosJson', array('_current' => true));
    }

    /**
     * Return distinct path ids of selected articulos
     *
     * @access public
     * @param mixed $rootId Root articulo Id for context
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedArticuloPathIds($rootId = false)
    {
        $ids = array();
        $articuloIds = $this->getArticuloIds();
        if (empty($articuloIds)) {
            return array();
        }
        $collection = Mage::getResourceModel('myextension_mymodule/articulo_collection');

        if ($rootId) {
            $collection->addFieldToFilter('parent_id', $rootId);
        } else {
            $collection->addFieldToFilter('entity_id', array('in'=>$articuloIds));
        }

        foreach ($collection as $item) {
            if ($rootId && !in_array($rootId, $item->getPathIds())) {
                continue;
            }
            foreach ($item->getPathIds() as $id) {
                if (!in_array($id, $ids)) {
                    $ids[] = $id;
                }
            }
        }
        return $ids;
    }
}
