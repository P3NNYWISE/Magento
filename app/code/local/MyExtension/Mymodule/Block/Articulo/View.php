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
 * Articulo view block
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_Block_Articulo_View extends Mage_Core_Block_Template
{
    /**
     * get the current articulo
     *
     * @access public
     * @return mixed (MyExtension_Mymodule_Model_Articulo|null)
     * @author Ultimate Module Creator
     */
    public function getCurrentArticulo()
    {
        return Mage::registry('current_articulo');
    }
}
