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
 * Articulo front contrller
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_ArticuloController extends Mage_Core_Controller_Front_Action
{

    /**
      * default action
      *
      * @access public
      * @return void
      * @author Ultimate Module Creator
      */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if (Mage::helper('myextension_mymodule/articulo')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label' => Mage::helper('myextension_mymodule')->__('Home'),
                        'link'  => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'articulos',
                    array(
                        'label' => Mage::helper('myextension_mymodule')->__('Articulos'),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', Mage::helper('myextension_mymodule/articulo')->getArticulosUrl());
        }
        if ($headBlock) {
            $headBlock->setTitle(Mage::getStoreConfig('myextension_mymodule/articulo/meta_title'));
            $headBlock->setKeywords(Mage::getStoreConfig('myextension_mymodule/articulo/meta_keywords'));
            $headBlock->setDescription(Mage::getStoreConfig('myextension_mymodule/articulo/meta_description'));
        }
        $this->renderLayout();
    }

    /**
     * init Articulo
     *
     * @access protected
     * @return MyExtension_Mymodule_Model_Articulo
     * @author Ultimate Module Creator
     */
    protected function _initArticulo()
    {
        $articuloId   = $this->getRequest()->getParam('id', 0);
        $articulo     = Mage::getModel('myextension_mymodule/articulo')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($articuloId);
        if (!$articulo->getId()) {
            return false;
        } elseif (!$articulo->getStatus()) {
            return false;
        }
        return $articulo;
    }

    /**
     * view articulo action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function viewAction()
    {
        $articulo = $this->_initArticulo();
        if (!$articulo) {
            $this->_forward('no-route');
            return;
        }
        if (!$articulo->getStatusPath()) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_articulo', $articulo);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('mymodule-articulo mymodule-articulo' . $articulo->getId());
        }
        if (Mage::helper('myextension_mymodule/articulo')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label'    => Mage::helper('myextension_mymodule')->__('Home'),
                        'link'     => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'articulos',
                    array(
                        'label' => Mage::helper('myextension_mymodule')->__('Articulos'),
                        'link'  => Mage::helper('myextension_mymodule/articulo')->getArticulosUrl(),
                    )
                );
                $parents = $articulo->getParentArticulos();
                foreach ($parents as $parent) {
                    if ($parent->getId() != Mage::helper('myextension_mymodule/articulo')->getRootArticuloId() &&
                        $parent->getId() != $articulo->getId()) {
                        $breadcrumbBlock->addCrumb(
                            'articulo-'.$parent->getId(),
                            array(
                                'label'    => $parent->getTitulo(),
                                'link'    => $link = $parent->getArticuloUrl(),
                            )
                        );
                    }
                }
                $breadcrumbBlock->addCrumb(
                    'articulo',
                    array(
                        'label' => $articulo->getTitulo(),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $articulo->getArticuloUrl());
        }
        if ($headBlock) {
            if ($articulo->getMetaTitle()) {
                $headBlock->setTitle($articulo->getMetaTitle());
            } else {
                $headBlock->setTitle($articulo->getTitulo());
            }
            $headBlock->setKeywords($articulo->getMetaKeywords());
            $headBlock->setDescription($articulo->getMetaDescription());
        }
        $this->renderLayout();
    }

    /**
     * articulos rss list action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function rssAction()
    {
        if (Mage::helper('myextension_mymodule/articulo')->isRssEnabled()) {
            $this->getResponse()->setHeader('Content-type', 'text/xml; charset=UTF-8');
            $this->loadLayout(false);
            $this->renderLayout();
        } else {
            $this->getResponse()->setHeader('HTTP/1.1', '404 Not Found');
            $this->getResponse()->setHeader('Status', '404 File not found');
            $this->_forward('nofeed', 'index', 'rss');
        }
    }

    /**
     * Submit new comment action
     * @access public
     * @author Ultimate Module Creator
     */
    public function commentpostAction()
    {
        $data   = $this->getRequest()->getPost();
        $articulo = $this->_initArticulo();
        $session    = Mage::getSingleton('core/session');
        if ($articulo) {
            if ($articulo->getAllowComments()) {
                if ((Mage::getSingleton('customer/session')->isLoggedIn() ||
                    Mage::getStoreConfigFlag('myextension_mymodule/articulo/allow_guest_comment'))) {
                    $comment  = Mage::getModel('myextension_mymodule/articulo_comment')->setData($data);
                    $validate = $comment->validate();
                    if ($validate === true) {
                        try {
                            $comment->setArticuloId($articulo->getId())
                                ->setStatus(MyExtension_Mymodule_Model_Articulo_Comment::STATUS_PENDING)
                                ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
                                ->setStores(array(Mage::app()->getStore()->getId()))
                                ->save();
                            $session->addSuccess($this->__('Your comment has been accepted for moderation.'));
                        } catch (Exception $e) {
                            $session->setArticuloCommentData($data);
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    } else {
                        $session->setArticuloCommentData($data);
                        if (is_array($validate)) {
                            foreach ($validate as $errorMessage) {
                                $session->addError($errorMessage);
                            }
                        } else {
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    }
                } else {
                    $session->addError($this->__('Guest comments are not allowed'));
                }
            } else {
                $session->addError($this->__('This articulo does not allow comments'));
            }
        }
        $this->_redirectReferer();
    }
}
