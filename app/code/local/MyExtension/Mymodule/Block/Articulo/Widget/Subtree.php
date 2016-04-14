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
 * Articulo subtree block
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_Block_Articulo_Widget_Subtree extends MyExtension_Mymodule_Block_Articulo_List implements
    Mage_Widget_Block_Interface
{
    protected $_template = 'myextension_mymodule/articulo/widget/subtree.phtml';
    /**
     * prepare the layout
     *
     * @access protected
     * @return MyExtension_Mymodule_Block_Articulo_Widget_Subtree
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        $this->getArticulos()->addFieldToFilter('entity_id', $this->getArticuloId());
        return $this;
    }

    /**
     * get the display mode
     *
     * @access protected
     * @return int
     * @author Ultimate Module Creator
     */
    protected function _getDisplayMode()
    {
        return 1;
    }

    /**
     * get the element id
     *
     * @access protected
     * @return int
     * @author Ultimate Module Creator
     */
    public function getUniqueId()
    {
        if (!$this->getData('uniq_id')) {
            $this->setData('uniq_id', uniqid('subtree'));
        }
        return $this->getData('uniq_id');
    }
}
