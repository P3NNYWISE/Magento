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
 * Articulo model
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_Model_Articulo extends Mage_Catalog_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'myextension_mymodule_articulo';
    const CACHE_TAG = 'myextension_mymodule_articulo';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'myextension_mymodule_articulo';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'articulo';
    protected $_productInstance = null;
    protected $_categoryInstance = null;

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('myextension_mymodule/articulo');
    }

    /**
     * before save articulo
     *
     * @access protected
     * @return MyExtension_Mymodule_Model_Articulo
     * @author Ultimate Module Creator
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * get the url to the articulo details page
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getArticuloUrl()
    {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('myextension_mymodule/articulo/url_prefix')) {
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('myextension_mymodule/articulo/url_suffix')) {
                $urlKey .= '.'.$suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('myextension_mymodule/articulo/view', array('id'=>$this->getId()));
    }

    /**
     * check URL key
     *
     * @access public
     * @param string $urlKey
     * @param bool $active
     * @return mixed
     * @author Ultimate Module Creator
     */
    public function checkUrlKey($urlKey, $active = true)
    {
        return $this->_getResource()->checkUrlKey($urlKey, $active);
    }

    /**
     * save articulo relation
     *
     * @access public
     * @return MyExtension_Mymodule_Model_Articulo
     * @author Ultimate Module Creator
     */
    protected function _afterSave()
    {
        $this->getProductInstance()->saveArticuloRelation($this);
        $this->getCategoryInstance()->saveArticuloRelation($this);
        return parent::_afterSave();
    }

    /**
     * get product relation model
     *
     * @access public
     * @return MyExtension_Mymodule_Model_Articulo_Product
     * @author Ultimate Module Creator
     */
    public function getProductInstance()
    {
        if (!$this->_productInstance) {
            $this->_productInstance = Mage::getSingleton('myextension_mymodule/articulo_product');
        }
        return $this->_productInstance;
    }

    /**
     * get selected products array
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedProducts()
    {
        if (!$this->hasSelectedProducts()) {
            $products = array();
            foreach ($this->getSelectedProductsCollection() as $product) {
                $products[] = $product;
            }
            $this->setSelectedProducts($products);
        }
        return $this->getData('selected_products');
    }

    /**
     * Retrieve collection selected products
     *
     * @access public
     * @return MyExtension_Mymodule_Resource_Articulo_Product_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedProductsCollection()
    {
        $collection = $this->getProductInstance()->getProductCollection($this);
        return $collection;
    }

    /**
     * get category relation model
     *
     * @access public
     * @return MyExtension_Mymodule_Model_Articulo_Category
     * @author Ultimate Module Creator
     */
    public function getCategoryInstance()
    {
        if (!$this->_categoryInstance) {
            $this->_categoryInstance = Mage::getSingleton('myextension_mymodule/articulo_category');
        }
        return $this->_categoryInstance;
    }

    /**
     * get selected categories array
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedCategories()
    {
        if (!$this->hasSelectedCategories()) {
            $categories = array();
            foreach ($this->getSelectedCategoriesCollection() as $category) {
                $categories[] = $category;
            }
            $this->setSelectedCategories($categories);
        }
        return $this->getData('selected_categories');
    }

    /**
     * Retrieve collection selected categories
     *
     * @access public
     * @return MyExtension_Mymodule_Resource_Articulo_Category_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedCategoriesCollection()
    {
        $collection = $this->getCategoryInstance()->getCategoryCollection($this);
        return $collection;
    }

    /**
     * get the tree model
     *
     * @access public
     * @return MyExtension_Mymodule_Model_Resource_Articulo_Tree
     * @author Ultimate Module Creator
     */
    public function getTreeModel()
    {
        return Mage::getResourceModel('myextension_mymodule/articulo_tree');
    }

    /**
     * get tree model instance
     *
     * @access public
     * @return MyExtension_Mymodule_Model_Resource_Articulo_Tree
     * @author Ultimate Module Creator
     */
    public function getTreeModelInstance()
    {
        if (is_null($this->_treeModel)) {
            $this->_treeModel = Mage::getResourceSingleton('myextension_mymodule/articulo_tree');
        }
        return $this->_treeModel;
    }

    /**
     * Move articulo
     *
     * @access public
     * @param   int $parentId new parent articulo id
     * @param   int $afterArticuloId articulo id after which we have put current articulo
     * @return  MyExtension_Mymodule_Model_Articulo
     * @author Ultimate Module Creator
     */
    public function move($parentId, $afterArticuloId)
    {
        $parent = Mage::getModel('myextension_mymodule/articulo')->load($parentId);
        if (!$parent->getId()) {
            Mage::throwException(
                Mage::helper('myextension_mymodule')->__(
                    'Articulo move operation is not possible: the new parent articulo was not found.'
                )
            );
        }
        if (!$this->getId()) {
            Mage::throwException(
                Mage::helper('myextension_mymodule')->__(
                    'Articulo move operation is not possible: the current articulo was not found.'
                )
            );
        } elseif ($parent->getId() == $this->getId()) {
            Mage::throwException(
                Mage::helper('myextension_mymodule')->__(
                    'Articulo move operation is not possible: parent articulo is equal to child articulo.'
                )
            );
        }
        $this->setMovedArticuloId($this->getId());
        $eventParams = array(
            $this->_eventObject => $this,
            'parent'            => $parent,
            'articulo_id'     => $this->getId(),
            'prev_parent_id'    => $this->getParentId(),
            'parent_id'         => $parentId
        );
        $moveComplete = false;
        $this->_getResource()->beginTransaction();
        try {
            $this->getResource()->changeParent($this, $parent, $afterArticuloId);
            $this->_getResource()->commit();
            $this->setAffectedArticuloIds(array($this->getId(), $this->getParentId(), $parentId));
            $moveComplete = true;
        } catch (Exception $e) {
            $this->_getResource()->rollBack();
            throw $e;
        }
        if ($moveComplete) {
            Mage::app()->cleanCache(array(self::CACHE_TAG));
        }
        return $this;
    }

    /**
     * Get the parent articulo
     *
     * @access public
     * @return  MyExtension_Mymodule_Model_Articulo
     * @author Ultimate Module Creator
     */
    public function getParentArticulo()
    {
        if (!$this->hasData('parent_articulo')) {
            $this->setData(
                'parent_articulo',
                Mage::getModel('myextension_mymodule/articulo')->load($this->getParentId())
            );
        }
        return $this->_getData('parent_articulo');
    }

    /**
     * Get the parent id
     *
     * @access public
     * @return  int
     * @author Ultimate Module Creator
     */
    public function getParentId()
    {
        $parentIds = $this->getParentIds();
        return intval(array_pop($parentIds));
    }

    /**
     * Get all parent articulos ids
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getParentIds()
    {
        return array_diff($this->getPathIds(), array($this->getId()));
    }

    /**
     * Get all articulos children
     *
     * @access public
     * @param bool $asArray
     * @return mixed (array|string)
     * @author Ultimate Module Creator
     */
    public function getAllChildren($asArray = false)
    {
        $children = $this->getResource()->getAllChildren($this);
        if ($asArray) {
            return $children;
        } else {
            return implode(',', $children);
        }
    }

    /**
     * Get all articulos children
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getChildArticulos()
    {
        return implode(',', $this->getResource()->getChildren($this, false));
    }

    /**
     * check the id
     *
     * @access public
     * @param int $id
     * @return bool
     * @author Ultimate Module Creator
     */
    public function checkId($id)
    {
        return $this->_getResource()->checkId($id);
    }

    /**
     * Get array articulos ids which are part of articulo path
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getPathIds()
    {
        $ids = $this->getData('path_ids');
        if (is_null($ids)) {
            $ids = explode('/', $this->getPath());
            $this->setData('path_ids', $ids);
        }
        return $ids;
    }

    /**
     * Retrieve level
     *
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getLevel()
    {
        if (!$this->hasLevel()) {
            return count(explode('/', $this->getPath())) - 1;
        }
        return $this->getData('level');
    }

    /**
     * Verify articulo ids
     *
     * @access public
     * @param array $ids
     * @return bool
     * @author Ultimate Module Creator
     */
    public function verifyIds(array $ids)
    {
        return $this->getResource()->verifyIds($ids);
    }

    /**
     * check if articulo has children
     *
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function hasChildren()
    {
        return $this->_getResource()->getChildrenAmount($this) > 0;
    }

    /**
     * check if articulo can be deleted
     *
     * @access protected
     * @return MyExtension_Mymodule_Model_Articulo
     * @author Ultimate Module Creator
     */
    protected function _beforeDelete()
    {
        if ($this->getResource()->isForbiddenToDelete($this->getId())) {
            Mage::throwException(Mage::helper('myextension_mymodule')->__("Can't delete root articulo."));
        }
        return parent::_beforeDelete();
    }

    /**
     * get the articulos
     *
     * @access public
     * @param MyExtension_Mymodule_Model_Articulo $parent
     * @param int $recursionLevel
     * @param bool $sorted
     * @param bool $asCollection
     * @param bool $toLoad
     * @author Ultimate Module Creator
     */
    public function getArticulos($parent, $recursionLevel = 0, $sorted=false, $asCollection=false, $toLoad=true)
    {
        return $this->getResource()->getArticulos($parent, $recursionLevel, $sorted, $asCollection, $toLoad);
    }

    /**
     * Return parent articulos of current articulo
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getParentArticulos()
    {
        return $this->getResource()->getParentArticulos($this);
    }

    /**
     * Return children articulos of current articulo
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getChildrenArticulos()
    {
        return $this->getResource()->getChildrenArticulos($this);
    }

    /**
     * check if parents are enabled
     *
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function getStatusPath()
    {
        $parents = $this->getParentArticulos();
        $rootId = Mage::helper('myextension_mymodule/articulo')->getRootArticuloId();
        foreach ($parents as $parent) {
            if ($parent->getId() == $rootId) {
                continue;
            }
            if (!$parent->getStatus()) {
                return false;
            }
        }
        return $this->getStatus();
    }

    /**
     * Retrieve default attribute set id
     *
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getDefaultAttributeSetId()
    {
        return $this->getResource()->getEntityType()->getDefaultAttributeSetId();
    }

    /**
     * get attribute text value
     *
     * @access public
     * @param $attributeCode
     * @return string
     * @author Ultimate Module Creator
     */
    public function getAttributeText($attributeCode)
    {
        $text = $this->getResource()
            ->getAttribute($attributeCode)
            ->getSource()
            ->getOptionText($this->getData($attributeCode));
        if (is_array($text)) {
            return implode(', ', $text);
        }
        return $text;
    }

    /**
     * check if comments are allowed
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getAllowComments()
    {
        if ($this->getData('allow_comment') == MyExtension_Mymodule_Model_Adminhtml_Source_Yesnodefault::NO) {
            return false;
        }
        if ($this->getData('allow_comment') == MyExtension_Mymodule_Model_Adminhtml_Source_Yesnodefault::YES) {
            return true;
        }
        return Mage::getStoreConfigFlag('myextension_mymodule/articulo/allow_comment');
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        $values['in_rss'] = 1;
        $values['allow_comment'] = MyExtension_Mymodule_Model_Adminhtml_Source_Yesnodefault::USE_DEFAULT;
        return $values;
    }
    
}
