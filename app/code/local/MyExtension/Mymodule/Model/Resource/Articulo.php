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
 * Articulo resource model
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_Model_Resource_Articulo extends Mage_Catalog_Model_Resource_Abstract
{
    /**
     * Articulo tree object
     * @var Varien_Data_Tree_Db
     */
    protected $_tree;
    protected $_articuloProductTable = null;
    protected $_articuloCategoryTable = null;


    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        $resource = Mage::getSingleton('core/resource');
        $this->setType('myextension_mymodule_articulo')
            ->setConnection(
                $resource->getConnection('articulo_read'),
                $resource->getConnection('articulo_write')
            );
        $this->_articuloProductTable = $this->getTable('myextension_mymodule/articulo_product');
        $this->_articuloCategoryTable = $this->getTable('myextension_mymodule/articulo_category');

    }

    /**
     * wrapper for main table getter
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getMainTable()
    {
        return $this->getEntityTable();
    }

    /**
     * Retrieve articulo tree object
     *
     * @access protected
     * @return Varien_Data_Tree_Db
     * @author Ultimate Module Creator
     */
    protected function _getTree()
    {
        if (!$this->_tree) {
            $this->_tree = Mage::getResourceModel('myextension_mymodule/articulo_tree')->load();
        }
        return $this->_tree;
    }

    /**
     * Process articulo data before delete
     * update children count for parent articulo
     * delete child articulos
     *
     * @access protected
     * @param Varien_Object $object
     * @return MyExtension_Mymodule_Model_Resource_Articulo
     * @author Ultimate Module Creator
     */
    protected function _beforeDelete(Varien_Object $object)
    {
        parent::_beforeDelete($object);
        /**
         * Update children count for all parent articulos
         */
        $parentIds = $object->getParentIds();
        if ($parentIds) {
            $childDecrease = $object->getChildrenCount() + 1; // +1 is itself
            $data = array('children_count' => new Zend_Db_Expr('children_count - ' . $childDecrease));
            $where = array('entity_id IN(?)' => $parentIds);
            $this->_getWriteAdapter()->update($this->getMainTable(), $data, $where);
        }
        $this->deleteChildren($object);
        return $this;
    }

    /**
     * Delete children articulos of specific articulo
     *
     * @access public
     * @param Varien_Object $object
     * @return MyExtension_Mymodule_Model_Resource_Articulo
     * @author Ultimate Module Creator
     */
    public function deleteChildren(Varien_Object $object)
    {
        $adapter = $this->_getWriteAdapter();
        $pathField = $adapter->quoteIdentifier('path');
        $select = $adapter->select()
            ->from($this->getMainTable(), array('entity_id'))
            ->where($pathField . ' LIKE :c_path');
        $childrenIds = $adapter->fetchCol($select, array('c_path' => $object->getPath() . '/%'));
        if (!empty($childrenIds)) {
            $adapter->delete(
                $this->getMainTable(),
                array('entity_id IN (?)' => $childrenIds)
            );
        }
        /**
         * Add deleted children ids to object
         * This data can be used in after delete event
         */
        $object->setDeletedChildrenIds($childrenIds);
        return $this;
    }

    /**
     * Process articulo data after save articulo object
     *
     * @access protected
     * @param Varien_Object $object
     * @return MyExtension_Mymodule_Model_Resource_Articulo
     * @author Ultimate Module Creator
     */
    protected function _afterSave(Varien_Object $object)
    {
        if (substr($object->getPath(), -1) == '/') {
            $object->setPath($object->getPath() . $object->getId());
            $this->_savePath($object);
        }
        return parent::_afterSave($object);
    }

    /**
     * Update path field
     *
     * @access protected
     * @param MyExtension_Mymodule_Model_Articulo $object
     * @return MyExtension_Mymodule_Model_Resource_Articulo
     * @author Ultimate Module Creator
     */
    protected function _savePath($object)
    {
        if ($object->getId()) {
            $this->_getWriteAdapter()->update(
                $this->getMainTable(),
                array('path' => $object->getPath()),
                array('entity_id = ?' => $object->getId())
            );
        }
        return $this;
    }

    /**
     * Get maximum position of child articulos by specific tree path
     *
     * @access protected
     * @param string $path
     * @return int
     * @author Ultimate Module Creator
     */
    protected function _getMaxPosition($path)
    {
        $adapter = $this->getReadConnection();
        $positionField = $adapter->quoteIdentifier('position');
        $level   = count(explode('/', $path));
        $bind = array(
            'c_level' => $level,
            'c_path'  => $path . '/%'
        );
        $select  = $adapter->select()
            ->from($this->getMainTable(), 'MAX(' . $positionField . ')')
            ->where($adapter->quoteIdentifier('path') . ' LIKE :c_path')
            ->where($adapter->quoteIdentifier('level') . ' = :c_level');

        $position = $adapter->fetchOne($select, $bind);
        if (!$position) {
            $position = 0;
        }
        return $position;
    }

    /**
     * Get children articulos count
     *
     * @access public
     * @param int $articuloId
     * @return int
     * @author Ultimate Module Creator
     */
    public function getChildrenCount($articuloId)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), 'children_count')
            ->where('entity_id = :entity_id');
        $bind = array('entity_id' => $articuloId);
        return $this->_getReadAdapter()->fetchOne($select, $bind);
    }

    /**
     * Check if articulo id exist
     *
     * @access public
     * @param int $entityId
     * @return bool
     * @author Ultimate Module Creator
     */
    public function checkId($entityId)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), 'entity_id')
            ->where('entity_id = :entity_id');
        $bind =  array('entity_id' => $entityId);
        return $this->_getReadAdapter()->fetchOne($select, $bind);
    }

    /**
     * Check array of articulos identifiers
     *
     * @access public
     * @param array $ids
     * @return array
     * @author Ultimate Module Creator
     */
    public function verifyIds(array $ids)
    {
        if (empty($ids)) {
            return array();
        }
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), 'entity_id')
            ->where('entity_id IN(?)', $ids);

        return $this->_getReadAdapter()->fetchCol($select);
    }

    /**
     * Get count of active/not active children articulos
     *
     * @param MyExtension_Mymodule_Model_Articulo $articulo
     * @param bool $isActiveFlag
     * @return int
     * @author Ultimate Module Creator
     */
    public function getChildrenAmount($articulo, $isActiveFlag = true)
    {
        $bind = array(
            'active_flag'  => $isActiveFlag,
            'c_path'   => $articulo->getPath() . '/%'
        );
        $select = $this->_getReadAdapter()->select()
            ->from(array('m' => $this->getMainTable()), array('COUNT(m.entity_id)'))
            ->where('m.path LIKE :c_path')
            ->where('status' . ' = :active_flag');
        return $this->_getReadAdapter()->fetchOne($select, $bind);
    }

    /**
     * Return parent articulos of articulo
     *
     * @access public
     * @param MyExtension_Mymodule_Model_Articulo $articulo
     * @return array
     * @author Ultimate Module Creator
     */
    public function getParentArticulos($articulo)
    {
        $pathIds = array_reverse(explode('/', $articulo->getPath()));
        $articulos = Mage::getResourceModel('myextension_mymodule/articulo_collection')
            ->addAttributeToFilter('entity_id', array('in' => $pathIds))
            ->addAttributeToSelect('*');
        return $articulos;
    }

    /**
     * Return child articulos
     *
     * @access public
     * @param MyExtension_Mymodule_Model_Articulo $articulo
     * @return MyExtension_Mymodule_Model_Resource_Articulo_Collection
     * @author Ultimate Module Creator
     */
    public function getChildrenArticulos($articulo)
    {
        $collection = $articulo->getCollection();
        $collection
            ->addAttributeToFilter('status', 1)
            ->addIdFilter($articulo->getChildArticulos())
            ->setOrder('position', Varien_Db_Select::SQL_ASC);
        return $collection;
    }

    /**
     * Return children ids of articulo
     *
     * @access public
     * @param MyExtension_Mymodule_Model_Articulo $articulo
     * @param boolean $recursive
     * @return array
     * @author Ultimate Module Creator
     */
    public function getChildren($articulo, $recursive = true)
    {
        $attributeId  = (int)$this->_getStatusAttributeId();
        $backendTable = $this->getTable(array($this->getEntityTablePrefix(), 'int'));
        $adapter      = $this->_getReadAdapter();
        $checkSql     = $adapter->getCheckSql('c.value_id > 0', 'c.value', 'd.value');
        $bind = array(
            'attribute_id' => $attributeId,
            'store_id'     => $articulo->getStoreId(),
            'scope'        => 1,
            'c_path'       => $articulo->getPath() . '/%'
        );
        $select = $this->_getReadAdapter()->select()
            ->from(array('m' => $this->getEntityTable()), 'entity_id')
            ->joinLeft(
                array('d' => $backendTable),
                'd.attribute_id = :attribute_id AND d.store_id = 0 AND d.entity_id = m.entity_id',
                array()
            )
            ->joinLeft(
                array('c' => $backendTable),
                'c.attribute_id = :attribute_id AND c.store_id = :store_id AND c.entity_id = m.entity_id',
                array()
            )
            ->where($checkSql . ' = :scope')
            ->where($adapter->quoteIdentifier('path') . ' LIKE :c_path');
        if (!$recursive) {
            $select->where($adapter->quoteIdentifier('level') . ' <= :c_level');
            $bind['c_level'] = $articulo->getLevel() + 1;
        }

        return $adapter->fetchCol($select, $bind);
    }

    protected $_statusAttributeId = null;

    /**
     * Get "is_active" attribute identifier
     *
     * @access protected
     * @return int
     * @author Ultimate Module Creator
     */
    protected function _getStatusAttributeId()
    {
        if ($this->_statusAttributeId === null) {
            $bind = array(
                'myextension_mymodule_articulo' => MyExtension_Mymodule_Model_Articulo::ENTITY,
                'status'        => 'status',
            );
            $select = $this->_getReadAdapter()->select()
                ->from(array('a'=>$this->getTable('eav/attribute')), array('attribute_id'))
                ->join(array('t'=>$this->getTable('eav/entity_type')), 'a.entity_type_id = t.entity_type_id')
                ->where('entity_type_code = :myextension_mymodule_articulo')
                ->where('attribute_code = :status');

            $this->_statusAttributeId = $this->_getReadAdapter()->fetchOne($select, $bind);
        }
        return $this->_statusAttributeId;
    }

    /**
     * Process articulo data before saving
     * prepare path and increment children count for parent articulos
     *
     * @access protected
     * @param Varien_Object $object
     * @return MyExtension_Mymodule_Model_Resource_Articulo
     * @author Ultimate Module Creator
     */
    protected function _beforeSave(Varien_Object $object)
    {
        parent::_beforeSave($object);
        if (!$object->getChildrenCount()) {
            $object->setChildrenCount(0);
        }
        if ($object->getLevel() === null) {
            $object->setLevel(1);
        }
        if (!$object->getId() && !$object->getInitialSetupFlag()) {
            $object->setPosition($this->_getMaxPosition($object->getPath()) + 1);
            $path  = explode('/', $object->getPath());
            $level = count($path);
            $object->setLevel($level);
            if ($level) {
                $object->setParentId($path[$level - 1]);
            }
            $object->setPath($object->getPath() . '/');
            $toUpdateChild = explode('/', $object->getPath());
            $this->_getWriteAdapter()->update(
                $this->getMainTable(),
                array('children_count'  => new Zend_Db_Expr('children_count+1')),
                array('entity_id IN(?)' => $toUpdateChild)
            );
        }
        return $this;
    }

    /**
     * Retrieve articulos
     *
     * @access public
     * @param integer $parent
     * @param integer $recursionLevel
     * @param boolean|string $sorted
     * @param boolean $asCollection
     * @param boolean $toLoad
     * @return Varien_Data_Tree_Node_Collection|MyExtension_Mymodule_Model_Resource_Articulo_Collection
     * @author Ultimate Module Creator
     */
    public function getArticulos(
        $parent,
        $recursionLevel = 0,
        $sorted = false,
        $asCollection = false,
        $toLoad = true
    )
    {
        $tree = Mage::getResourceModel('myextension_mymodule/articulo_tree');
        $nodes = $tree->loadNode($parent)
            ->loadChildren($recursionLevel)
            ->getChildren();
        $tree->addCollectionData(null, $sorted, $parent, $toLoad, true);
        if ($asCollection) {
            return $tree->getCollection();
        }
        return $nodes;
    }

    /**
     * Return all children ids of articulo (with articulo id)
     *
     * @access public
     * @param MyExtension_Mymodule_Model_Articulo $articulo
     * @return array
     * @author Ultimate Module Creator
     */
    public function getAllChildren($articulo)
    {
        $children = $this->getChildren($articulo);
        $myId = array($articulo->getId());
        $children = array_merge($myId, $children);
        return $children;
    }

    /**
     * Check articulo is forbidden to delete.
     *
     * @access public
     * @param integer $articuloId
     * @return boolean
     * @author Ultimate Module Creator
     */
    public function isForbiddenToDelete($articuloId)
    {
        return ($articuloId == Mage::helper('myextension_mymodule/articulo')->getRootArticuloId());
    }

    /**
     * Get articulo path value by its id
     *
     * @access public
     * @param int $articuloId
     * @return string
     * @author Ultimate Module Creator
     */
    public function getArticuloPathById($articuloId)
    {
        $select = $this->getReadConnection()->select()
            ->from($this->getMainTable(), array('path'))
            ->where('entity_id = :entity_id');
        $bind = array('entity_id' => (int)$articuloId);
        return $this->getReadConnection()->fetchOne($select, $bind);
    }

    /**
     * Move articulo to another parent node
     *
     * @access public
     * @param MyExtension_Mymodule_Model_Articulo $articulo
     * @param MyExtension_Mymodule_Model_Articulo $newParent
     * @param null|int $afterArticuloId
     * @return MyExtension_Mymodule_Model_Resource_Articulo
     * @author Ultimate Module Creator
     */
    public function changeParent(
        MyExtension_Mymodule_Model_Articulo $articulo,
        MyExtension_Mymodule_Model_Articulo $newParent,
        $afterArticuloId = null
    )
    {
        $childrenCount  = $this->getChildrenCount($articulo->getId()) + 1;
        $table          = $this->getMainTable();
        $adapter        = $this->_getWriteAdapter();
        $levelFiled     = $adapter->quoteIdentifier('level');
        $pathField      = $adapter->quoteIdentifier('path');

        /**
         * Decrease children count for all old articulo parent articulos
         */
        $adapter->update(
            $table,
            array('children_count' => new Zend_Db_Expr('children_count - ' . $childrenCount)),
            array('entity_id IN(?)' => $articulo->getParentIds())
        );
        /**
         * Increase children count for new articulo parents
         */
        $adapter->update(
            $table,
            array('children_count' => new Zend_Db_Expr('children_count + ' . $childrenCount)),
            array('entity_id IN(?)' => $newParent->getPathIds())
        );

        $position = $this->_processPositions($articulo, $newParent, $afterArticuloId);

        $newPath  = sprintf('%s/%s', $newParent->getPath(), $articulo->getId());
        $newLevel = $newParent->getLevel() + 1;
        $levelDisposition = $newLevel - $articulo->getLevel();

        /**
         * Update children nodes path
         */
        $adapter->update(
            $table,
            array(
                'path' => new Zend_Db_Expr(
                    'REPLACE(' . $pathField . ','.
                    $adapter->quote($articulo->getPath() . '/'). ', '.$adapter->quote($newPath . '/').')'
                ),
                'level' => new Zend_Db_Expr($levelFiled . ' + ' . $levelDisposition)
            ),
            array($pathField . ' LIKE ?' => $articulo->getPath() . '/%')
        );
        /**
         * Update moved articulo data
         */
        $data = array(
            'path'  => $newPath,
            'level' => $newLevel,
            'position'  =>$position,
            'parent_id' =>$newParent->getId()
        );
        $adapter->update($table, $data, array('entity_id = ?' => $articulo->getId()));
        // Update articulo object to new data
        $articulo->addData($data);
        return $this;
    }

    /**
     * Process positions of old parent articulo children and new parent articulo children.
     * Get position for moved articulo
     *
     * @access protected
     * @param MyExtension_Mymodule_Model_Articulo $articulo
     * @param MyExtension_Mymodule_Model_Articulo $newParent
     * @param null|int $afterArticuloId
     * @return int
     * @author Ultimate Module Creator
     */
    protected function _processPositions($articulo, $newParent, $afterArticuloId)
    {
        $table  = $this->getMainTable();
        $adapter= $this->_getWriteAdapter();
        $positionField  = $adapter->quoteIdentifier('position');

        $bind = array(
            'position' => new Zend_Db_Expr($positionField . ' - 1')
        );
        $where = array(
            'parent_id = ?' => $articulo->getParentId(),
            $positionField . ' > ?' => $articulo->getPosition()
        );
        $adapter->update($table, $bind, $where);

        /**
         * Prepare position value
         */
        if ($afterArticuloId) {
            $select = $adapter->select()
                ->from($table, 'position')
                ->where('entity_id = :entity_id');
            $position = $adapter->fetchOne($select, array('entity_id' => $afterArticuloId));
            $bind = array(
                'position' => new Zend_Db_Expr($positionField . ' + 1')
            );
            $where = array(
                'parent_id = ?' => $newParent->getId(),
                $positionField . ' > ?' => $position
            );
            $adapter->update($table, $bind, $where);
        } elseif ($afterArticuloId !== null) {
            $position = 0;
            $bind = array(
                'position' => new Zend_Db_Expr($positionField . ' + 1')
            );
            $where = array(
                'parent_id = ?' => $newParent->getId(),
                $positionField . ' > ?' => $position
            );
            $adapter->update($table, $bind, $where);
        } else {
            $select = $adapter->select()
                ->from($table, array('position' => new Zend_Db_Expr('MIN(' . $positionField. ')')))
                ->where('parent_id = :parent_id');
            $position = $adapter->fetchOne($select, array('parent_id' => $newParent->getId()));
        }
        $position += 1;
        return $position;
    }

    /**
     * check url key
     *
     * @access public
     * @param string $urlKey
     * @param bool $active
     * @return mixed
     * @author Ultimate Module Creator
     */
    public function checkUrlKey($urlKey, $storeId, $active = true)
    {
        $stores = array(Mage_Core_Model_App::ADMIN_STORE_ID, $storeId);
        $select = $this->_initCheckUrlKeySelect($urlKey, $stores);
        if (!$select) {
            return false;
        }
        $select->reset(Zend_Db_Select::COLUMNS)
            ->columns('e.entity_id')
            ->limit(1);
        return $this->_getReadAdapter()->fetchOne($select);
    }

    /**
     * init the check select
     *
     * @access protected
     * @param string $urlKey
     * @param array $store
     * @return Zend_Db_Select
     * @author Ultimate Module Creator
     */
    protected function _initCheckUrlKeySelect($urlKey, $store)
    {
        $urlRewrite = Mage::getModel('eav/config')->getAttribute('myextension_mymodule_articulo', 'url_key');
        if (!$urlRewrite || !$urlRewrite->getId()) {
            return false;
        }
        $table = $urlRewrite->getBackend()->getTable();
        $select = $this->_getReadAdapter()->select()
            ->from(array('e' => $table))
            ->where('e.attribute_id = ?', $urlRewrite->getId())
            ->where('e.value = ?', $urlKey)
            ->where('e.store_id IN (?)', $store)
            ->order('e.store_id DESC');
        return $select;
    }

    /**
     * Check for unique URL key
     *
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return bool
     * @author Ultimate Module Creator
     */
    public function getIsUniqueUrlKey(Mage_Core_Model_Abstract $object)
    {
        if (Mage::app()->isSingleStoreMode() || !$object->hasStores()) {
            $stores = array(Mage_Core_Model_App::ADMIN_STORE_ID);
        } else {
            $stores = (array)$object->getData('stores');
        }
        $select = $this->_initCheckUrlKeySelect($object->getData('url_key'), $stores);
        if ($object->getId()) {
            $select->where('e.entity_id <> ?', $object->getId());
        }
        if ($this->_getWriteAdapter()->fetchRow($select)) {
            return false;
        }
        return true;
    }

    /**
     * Check if the URL key is numeric
     *
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return bool
     * @author Ultimate Module Creator
     */
    protected function isNumericUrlKey(Mage_Core_Model_Abstract $object)
    {
        return preg_match('/^[0-9]+$/', $object->getData('url_key'));
    }

    /**
     * Check if the URL key is valid
     *
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return bool
     * @author Ultimate Module Creator
     */
    protected function isValidUrlKey(Mage_Core_Model_Abstract $object)
    {
        return preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $object->getData('url_key'));
    }
}
