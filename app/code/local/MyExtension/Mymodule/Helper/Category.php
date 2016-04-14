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
 * Category helper
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_Helper_Category extends MyExtension_Mymodule_Helper_Data
{

    /**
     * get the selected articulos for a category
     *
     * @access public
     * @param Mage_Catalog_Model_Category $category
     * @return array()
     * @author Ultimate Module Creator
     */
    public function getSelectedArticulos(Mage_Catalog_Model_Category $category)
    {
        if (!$category->hasSelectedArticulos()) {
            $articulos = array();
            foreach ($this->getSelectedArticulosCollection($category) as $articulo) {
                $articulos[] = $articulo;
            }
            $category->setSelectedArticulos($articulos);
        }
        return $category->getData('selected_articulos');
    }

    /**
     * get articulo collection for a category
     *
     * @access public
     * @param Mage_Catalog_Model_Category $category
     * @return MyExtension_Mymodule_Model_Resource_Articulo_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedArticulosCollection(Mage_Catalog_Model_Category $category)
    {
        $collection = Mage::getResourceSingleton('myextension_mymodule/articulo_collection')
            ->addCategoryFilter($category);
        return $collection;
    }
}
