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
 * Articulo children list block
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_Block_Articulo_Children extends MyExtension_Mymodule_Block_Articulo_List
{
    /**
     * prepare the layout
     *
     * @access protected
     * @return MyExtension_Mymodule_Block_Articulo_Children
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        $this->getArticulos()->addFieldToFilter('parent_id', $this->getCurrentArticulo()->getId());
        return $this;
    }

    /**
     * get the current articulo
     *
     * @access protected
     * @return MyExtension_Mymodule_Model_Articulo
     * @author Ultimate Module Creator
     */
    public function getCurrentArticulo()
    {
        return Mage::registry('current_articulo');
    }
}
