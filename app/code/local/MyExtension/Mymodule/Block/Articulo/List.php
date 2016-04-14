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
 * Articulo list block
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author Ultimate Module Creator
 */
class MyExtension_Mymodule_Block_Articulo_List extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        parent::_construct();
        $articulos = Mage::getResourceModel('myextension_mymodule/articulo_collection')
                         ->setStoreId(Mage::app()->getStore()->getId())
                         ->addAttributeToSelect('*')
                         ->addAttributeToFilter('status', 1);
        ;
        $articulos->getSelect()->order('e.position');
        $this->setArticulos($articulos);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return MyExtension_Mymodule_Block_Articulo_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->getArticulos()->addFieldToFilter('level', 1);
        if ($this->_getDisplayMode() == 0) {
            $pager = $this->getLayout()->createBlock(
                'page/html_pager',
                'myextension_mymodule.articulos.html.pager'
            )
            ->setCollection($this->getArticulos());
            $this->setChild('pager', $pager);
            $this->getArticulos()->load();
        }
        return $this;
    }

    /**
     * get the pager html
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
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
        return Mage::getStoreConfigFlag('myextension_mymodule/articulo/tree');
    }

    /**
     * draw articulo
     *
     * @access public
     * @param MyExtension_Mymodule_Model_Articulo
     * @param int $level
     * @return int
     * @author Ultimate Module Creator
     */
    public function drawArticulo($articulo, $level = 0)
    {
        $html = '';
        $recursion = $this->getRecursion();
        if ($recursion !== '0' && $level >= $recursion) {
            return '';
        }
        if (!$articulo->getStatus()) {
            return '';
        }
        $articulo->setStoreId(Mage::app()->getStore()->getId());
        $children = $articulo->getChildrenArticulos()->addAttributeToSelect('*');
        $activeChildren = array();
        if ($recursion == 0 || $level < $recursion-1) {
            foreach ($children as $child) {
                if ($child->getStatus()) {
                    $activeChildren[] = $child;
                }
            }
        }
        $html .= '<li>';
        $html .= '<a href="'.$articulo->getArticuloUrl().'">'.$articulo->getTitulo().'</a>';
        if (count($activeChildren) > 0) {
            $html .= '<ul>';
            foreach ($children as $child) {
                $html .= $this->drawArticulo($child, $level+1);
            }
            $html .= '</ul>';
        }
        $html .= '</li>';
        return $html;
    }

    /**
     * get recursion
     *
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getRecursion()
    {
        if (!$this->hasData('recursion')) {
            $this->setData('recursion', Mage::getStoreConfig('myextension_mymodule/articulo/recursion'));
        }
        return $this->getData('recursion');
    }
}
