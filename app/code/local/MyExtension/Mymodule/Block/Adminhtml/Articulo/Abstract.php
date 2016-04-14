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
 * Articulo admin block abstract
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_Block_Adminhtml_Articulo_Abstract extends Mage_Adminhtml_Block_Template
{
    /**
     * get current articulo
     *
     * @access public
     * @return MyExtension_Mymodule_Model_Entity
     * @author Ultimate Module Creator
     */
    public function getArticulo()
    {
        return Mage::registry('articulo');
    }

    /**
     * get current articulo id
     *
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getArticuloId()
    {
        if ($this->getArticulo()) {
            return $this->getArticulo()->getId();
        }
        return null;
    }

    /**
     * get current articulo Titulo
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getArticuloTitulo()
    {
        return $this->getArticulo()->getTitulo();
    }

    /**
     * get current articulo path
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getArticuloPath()
    {
        if ($this->getArticulo()) {
            return $this->getArticulo()->getPath();
        }
        return Mage::helper('myextension_mymodule/articulo')->getRootArticuloId();
    }

    /**
     * check if there is a root articulo
     *
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function hasRootArticulo()
    {
        $root = $this->getRoot();
        if ($root && $root->getId()) {
            return true;
        }
        return false;
    }

    /**
     * get the root
     *
     * @access public
     * @param MyExtension_Mymodule_Model_Articulo|null $parentNodeArticulo
     * @param int $recursionLevel
     * @return Varien_Data_Tree_Node
     * @author Ultimate Module Creator
     */
    public function getRoot($parentNodeArticulo = null, $recursionLevel = 3)
    {
        if (!is_null($parentNodeArticulo) && $parentNodeArticulo->getId()) {
            return $this->getNode($parentNodeArticulo, $recursionLevel);
        }
        $root = Mage::registry('root');
        if (is_null($root)) {
            $rootId = Mage::helper('myextension_mymodule/articulo')->getRootArticuloId();
            $tree = Mage::getResourceSingleton('myextension_mymodule/articulo_tree')
                ->load(null, $recursionLevel);
            if ($this->getArticulo()) {
                $tree->loadEnsuredNodes($this->getArticulo(), $tree->getNodeById($rootId));
            }
            $tree->addCollectionData($this->getArticuloCollection());
            $root = $tree->getNodeById($rootId);
            if ($root && $rootId != Mage::helper('myextension_mymodule/articulo')->getRootArticuloId()) {
                $root->setIsVisible(true);
            } elseif ($root && $root->getId() == Mage::helper('myextension_mymodule/articulo')->getRootArticuloId()) {
                $root->setTitulo(Mage::helper('myextension_mymodule')->__('Root'));
            }
            Mage::register('root', $root);
        }
        return $root;
    }

    /**
     * Get and register articulos root by specified articulos IDs
     *
     * @accsess public
     * @param array $ids
     * @return Varien_Data_Tree_Node
     * @author Ultimate Module Creator
     */
    public function getRootByIds($ids)
    {
        $root = Mage::registry('root');
        if (null === $root) {
            $articuloTreeResource = Mage::getResourceSingleton('myextension_mymodule/articulo_tree');
            $ids     = $articuloTreeResource->getExistingArticuloIdsBySpecifiedIds($ids);
            $tree   = $articuloTreeResource->loadByIds($ids);
            $rootId = Mage::helper('myextension_mymodule/articulo')->getRootArticuloId();
            $root   = $tree->getNodeById($rootId);
            if ($root && $rootId != Mage::helper('myextension_mymodule/articulo')->getRootArticuloId()) {
                $root->setIsVisible(true);
            } elseif ($root && $root->getId() == Mage::helper('myextension_mymodule/articulo')->getRootArticuloId()) {
                $root->setName(Mage::helper('myextension_mymodule')->__('Root'));
            }
            $tree->addCollectionData($this->getArticuloCollection());
            Mage::register('root', $root);
        }
        return $root;
    }

    /**
     * get specific node
     *
     * @access public
     * @param MyExtension_Mymodule_Model_Articulo $parentNodeArticulo
     * @param $int $recursionLevel
     * @return Varien_Data_Tree_Node
     * @author Ultimate Module Creator
     */
    public function getNode($parentNodeArticulo, $recursionLevel = 2)
    {
        $tree = Mage::getResourceModel('myextension_mymodule/articulo_tree');
        $nodeId     = $parentNodeArticulo->getId();
        $parentId   = $parentNodeArticulo->getParentId();
        $node = $tree->loadNode($nodeId);
        $node->loadChildren($recursionLevel);
        if ($node && $nodeId != Mage::helper('myextension_mymodule/articulo')->getRootArticuloId()) {
            $node->setIsVisible(true);
        } elseif ($node && $node->getId() == Mage::helper('myextension_mymodule/articulo')->getRootArticuloId()) {
            $node->setTitulo(Mage::helper('myextension_mymodule')->__('Root'));
        }
        $tree->addCollectionData($this->getArticuloCollection());
        return $node;
    }

    /**
     * get url for saving data
     *
     * @access public
     * @param array $args
     * @return string
     * @author Ultimate Module Creator
     */
    public function getSaveUrl(array $args = array())
    {
        $params = array('_current'=>true);
        $params = array_merge($params, $args);
        return $this->getUrl('*/*/save', $params);
    }

    /**
     * get url for edit
     *
     * @access public
     * @param array $args
     * @return string
     * @author Ultimate Module Creator
     */
    public function getEditUrl()
    {
        return $this->getUrl(
            "*/mymodule_articulo/edit",
            array('_current' => true, '_query'=>false, 'id' => null, 'parent' => null)
        );
    }

    /**
     * Return root ids
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getRootIds()
    {
        return array(Mage::helper('myextension_mymodule/articulo')->getRootArticuloId());
    }
}
