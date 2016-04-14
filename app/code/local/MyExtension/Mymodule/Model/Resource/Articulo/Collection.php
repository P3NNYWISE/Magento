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
 * Articulo collection resource model
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_Model_Resource_Articulo_Collection extends Mage_Catalog_Model_Resource_Collection_Abstract
{
    protected $_joinedFields = array();

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('myextension_mymodule/articulo');
    }

    /**
     * Add Id filter
     *
     * @access public
     * @param array $articuloIds
     * @return MyExtension_Mymodule_Model_Resource_Articulo_Collection
     * @author Ultimate Module Creator
     */
    public function addIdFilter($articuloIds)
    {
        if (is_array($articuloIds)) {
            if (empty($articuloIds)) {
                $condition = '';
            } else {
                $condition = array('in' => $articuloIds);
            }
        } elseif (is_numeric($articuloIds)) {
            $condition = $articuloIds;
        } elseif (is_string($articuloIds)) {
            $ids = explode(',', $articuloIds);
            if (empty($ids)) {
                $condition = $articuloIds;
            } else {
                $condition = array('in' => $ids);
            }
        }
        $this->addFieldToFilter('entity_id', $condition);
        return $this;
    }

    /**
     * Add articulo path filter
     *
     * @access public
     * @param string $regexp
     * @return MyExtension_Mymodule_Model_Resource_Articulo_Collection
     * @author Ultimate Module Creator
     */
    public function addPathFilter($regexp)
    {
        $this->addFieldToFilter('path', array('regexp' => $regexp));
        return $this;
    }

    /**
     * Add articulo path filter
     *
     * @access public
     * @param array|string $paths
     * @return MyExtension_Mymodule_Model_Resource_Articulo_Collection
     * @author Ultimate Module Creator
     */
    public function addPathsFilter($paths)
    {
        if (!is_array($paths)) {
            $paths = array($paths);
        }
        $write  = $this->getResource()->getWriteConnection();
        $cond   = array();
        foreach ($paths as $path) {
            $cond[] = $write->quoteInto('e.path LIKE ?', "$path%");
        }
        if ($cond) {
            $this->getSelect()->where(join(' OR ', $cond));
        }
        return $this;
    }

    /**
     * Add articulo level filter
     *
     * @access public
     * @param int|string $level
     * @return MyExtension_Mymodule_Model_Resource_Articulo_Collection
     * @author Ultimate Module Creator
     */
    public function addLevelFilter($level)
    {
        $this->addFieldToFilter('level', array('lteq' => $level));
        return $this;
    }

    /**
     * Add root articulo filter
     *
     * @access public
     * @return MyExtension_Mymodule_Model_Resource_Articulo_Collection
     */
    public function addRootLevelFilter()
    {
        $this->addFieldToFilter('path', array('neq' => '1'));
        $this->addLevelFilter(1);
        return $this;
    }

    /**
     * Add order field
     *
     * @access public
     * @param string $field
     * @return MyExtension_Mymodule_Model_Resource_Articulo_Collection
     */
    public function addOrderField($field)
    {
        $this->setOrder($field, self::SORT_ORDER_ASC);
        return $this;
    }

    /**
     * Add active articulo filter
     *
     * @access public
     * @return MyExtension_Mymodule_Model_Resource_Articulo_Collection
     */
    public function addStatusFilter($status = 1)
    {
        $this->addFieldToFilter('status', $status);
        return $this;
    }

    /**
     * get articulos as array
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _toOptionArray($valueField='entity_id', $labelField='titulo', $additional=array())
    {
        $res = array();
        $additional['value'] = $valueField;
        $additional['label'] = $labelField;

        foreach ($this as $item) {
            if ($item->getId() == Mage::helper('myextension_mymodule/articulo')->getRootArticuloId()) {
                continue;
            }
            foreach ($additional as $code => $field) {
                $data[$code] = $item->getData($field);
            }
            $res[] = $data;
        }
        return $res;
    }

    /**
     * get options hash
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _toOptionHash($valueField='entity_id', $labelField='titulo')
    {
        $res = array();
        foreach ($this as $item) {
            if ($item->getId() == Mage::helper('myextension_mymodule/articulo')->getRootArticuloId()) {
                continue;
            }
            $res[$item->getData($valueField)] = $item->getData($labelField);
        }
        return $res;
    }

    /**
     * add the product filter to collection
     *
     * @access public
     * @param mixed (Mage_Catalog_Model_Product|int) $product
     * @return MyExtension_Mymodule_Model_Resource_Articulo_Collection
     * @author Ultimate Module Creator
     */
    public function addProductFilter($product)
    {
        if ($product instanceof Mage_Catalog_Model_Product) {
            $product = $product->getId();
        }
        if (!isset($this->_joinedFields['product'])) {
            $this->getSelect()->join(
                array('related_product' => $this->getTable('myextension_mymodule/articulo_product')),
                'related_product.articulo_id = e.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_product.product_id = ?', $product);
            $this->_joinedFields['product'] = true;
        }
        return $this;
    }

    /**
     * add the category filter to collection
     *
     * @access public
     * @param mixed (Mage_Catalog_Model_Category|int) $category
     * @return MyExtension_Mymodule_Model_Resource_Articulo_Collection
     * @author Ultimate Module Creator
     */
    public function addCategoryFilter($category)
    {
        if ($category instanceof Mage_Catalog_Model_Category) {
            $category = $category->getId();
        }
        if (!isset($this->_joinedFields['category'])) {
            $this->getSelect()->join(
                array('related_category' => $this->getTable('myextension_mymodule/articulo_category')),
                'related_category.articulo_id = e.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_category.category_id = ?', $category);
            $this->_joinedFields['category'] = true;
        }
        return $this;
    }

    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     *
     * @access public
     * @return Varien_Db_Select
     * @author Ultimate Module Creator
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);
        return $countSelect;
    }
}
