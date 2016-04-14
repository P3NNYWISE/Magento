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
 * Articulo - Categories relation model
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_Model_Resource_Articulo_Category extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * initialize resource model
     *
     * @access protected
     * @return void
     * @see Mage_Core_Model_Resource_Abstract::_construct()
     * @author Ultimate Module Creator
     */
    protected function  _construct()
    {
        $this->_init('myextension_mymodule/articulo_category', 'rel_id');
    }

    /**
     * Save articulo - category relations
     *
     * @access public
     * @param MyExtension_Mymodule_Model_Articulo $articulo
     * @param array $data
     * @return MyExtension_Mymodule_Model_Resource_Articulo_Category
     * @author Ultimate Module Creator
     */
    public function saveArticuloRelation($articulo, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('articulo_id=?', $articulo->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $categoryId) {
            if (!empty($categoryId)) {
                $insert = array(
                    'articulo_id' => $articulo->getId(),
                    'category_id'   => $categoryId,
                    'position'      => 1
                );
                $this->_getWriteAdapter()->insertOnDuplicate($this->getMainTable(), $insert, array_keys($insert));
            }
        }
        return $this;
    }

    /**
     * Save  category - articulo relations
     *
     * @access public
     * @param Mage_Catalog_Model_Category $category
     * @param array $data
     * @return MyExtension_Mymodule_Model_Resource_Articulo_Category
     * @author Ultimate Module Creator
     */
    public function saveCategoryRelation($category, $articuloIds)
    {

        $oldArticulos = Mage::helper('myextension_mymodule/category')->getSelectedArticulos($category);
        $oldArticuloIds = array();
        foreach ($oldArticulos as $articulo) {
            $oldArticuloIds[] = $articulo->getId();
        }
        $insert = array_diff($articuloIds, $oldArticuloIds);
        $delete = array_diff($oldArticuloIds, $articuloIds);
        $write = $this->_getWriteAdapter();
        if (!empty($insert)) {
            $data = array();
            foreach ($insert as $articuloId) {
                if (empty($articuloId)) {
                    continue;
                }
                $data[] = array(
                    'articulo_id' => (int)$articuloId,
                    'category_id'  => (int)$category->getId(),
                    'position'=> 1
                );
            }
            if ($data) {
                $write->insertMultiple($this->getMainTable(), $data);
            }
        }
        if (!empty($delete)) {
            foreach ($delete as $articuloId) {
                $where = array(
                    'category_id = ?'   => (int)$category->getId(),
                    'articulo_id = ?' => (int)$articuloId,
                );
                $write->delete($this->getMainTable(), $where);
            }
        }
        return $this;
    }
}
