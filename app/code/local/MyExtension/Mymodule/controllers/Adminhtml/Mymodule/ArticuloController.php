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
 * Articulo admin controller
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_Adminhtml_Mymodule_ArticuloController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Initialize requested articulo and put it into registry.
     * Root articulo can be returned, if inappropriate store/articulo is specified
     *
     * @access protected
     * @param bool $getRootInstead
     * @return MyExtension_Mymodule_Model_Articulo
     * @author Ultimate Module Creator
     */
    protected function _initArticulo($getRootInstead = false)
    {
        $this->_title($this->__('MyMenu'))
             ->_title($this->__('Manage Articulos'));
        $articuloId = (int) $this->getRequest()->getParam('id', false);
        $storeId    = (int) $this->getRequest()->getParam('store');
        $articulo = Mage::getModel('myextension_mymodule/articulo');
        $articulo->setStoreId($storeId);

        if ($articuloId) {
            $articulo->load($articuloId);
            if ($storeId) {
                $rootId = Mage::helper('myextension_mymodule/articulo')->getRootArticuloId();
                if (!in_array($rootId, $articulo->getPathIds())) {
                    // load root articulo instead wrong one
                    if ($getRootInstead) {
                        $articulo->load($rootId);
                    } else {
                        $this->_redirect('*/*/', array('_current'=>true, 'id'=>null));
                        return false;
                    }
                }
            }
        }

        if ($activeTabId = (string) $this->getRequest()->getParam('active_tab_id')) {
            Mage::getSingleton('admin/session')->setArticuloActiveTabId($activeTabId);
        }

        Mage::register('articulo', $articulo);
        Mage::register('current_articulo', $articulo);
        Mage::getSingleton('cms/wysiwyg_config')->setStoreId($this->getRequest()->getParam('store'));
        return $articulo;
    }

    /**
     * index action
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function indexAction()
    {
        $this->_forward('edit');
    }

    /**
     * Add new articulo form
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function addAction()
    {
        Mage::getSingleton('admin/session')->unsArticuloActiveTabId();
        $this->_forward('edit');
    }

    /**
     * Edit articulo page
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $params['_current'] = true;
        $redirect = false;

        $storeId = (int) $this->getRequest()->getParam('store');
        $parentId = (int) $this->getRequest()->getParam('parent');
        $_prevStoreId = Mage::getSingleton('admin/session')
            ->getArticuloLastViewedStore(true);

        if (!empty($_prevStoreId) && !$this->getRequest()->getQuery('isAjax')) {
            $params['store'] = $_prevStoreId;
            $redirect = true;
        }

        $articuloId = (int) $this->getRequest()->getParam('id');
        $_prevArticuloId = Mage::getSingleton('admin/session')
            ->getLastEditedArticulo(true);


        if ($_prevArticuloId
            && !$this->getRequest()->getQuery('isAjax')
            && !$this->getRequest()->getParam('clear')) {
             $this->getRequest()->setParam('id', $_prevArticuloId);
        }

        if ($redirect) {
            $this->_redirect('*/*/edit', $params);
            return;
        }

        if ($storeId && !$articuloId && !$parentId) {
            $store = Mage::app()->getStore($storeId);
            $_prevArticuloId = (int)Mage::helper('myextension_mymodule/articulo')->getRootArticuloId();
            $this->getRequest()->setParam('id', $_prevArticuloId);
        }

        if (!($articulo = $this->_initArticulo())) {
            return;
        }

        $this->_title($articuloId ? $articulo->getName() : $this->__('New Articulo'));

        $data = Mage::getSingleton('adminhtml/session')->getArticuloData(true);
        if (isset($data['articulo'])) {
            $articulo->addData($data['articulo']);
        }

        /**
         * Build response for ajax request
         */
        if ($this->getRequest()->getQuery('isAjax')) {
            $breadcrumbsPath = $articulo->getPath();
            if (empty($breadcrumbsPath)) {
                $breadcrumbsPath = Mage::getSingleton('admin/session')->getArticuloDeletedPath(true);
                if (!empty($breadcrumbsPath)) {
                    $breadcrumbsPath = explode('/', $breadcrumbsPath);
                    if (count($breadcrumbsPath) <= 1) {
                        $breadcrumbsPath = '';
                    } else {
                        array_pop($breadcrumbsPath);
                        $breadcrumbsPath = implode('/', $breadcrumbsPath);
                    }
                }
            }

            Mage::getSingleton('admin/session')
                ->setArticuloLastViewedStore($this->getRequest()->getParam('store'));
            Mage::getSingleton('admin/session')
                ->setLastEditedArticulo($articulo->getId());
            $this->loadLayout();

            $eventResponse = new Varien_Object(
                array(
                    'content' => $this->getLayout()->getBlock('articulo.edit')->getFormHtml()
                        . $this->getLayout()->getBlock('articulo.tree')
                        ->getBreadcrumbsJavascript($breadcrumbsPath, 'editingArticuloBreadcrumbs'),
                    'messages' => $this->getLayout()->getMessagesBlock()->getGroupedHtml(),
                )
            );

            Mage::dispatchEvent(
                'articulo_prepare_ajax_response',
                array(
                    'response' => $eventResponse,
                    'controller' => $this
                )
            );

            $this->getResponse()->setBody(
                Mage::helper('core')->jsonEncode($eventResponse->getData())
            );

            return;
        }

        $this->loadLayout();
        $this->_setActiveMenu('myextension_mymodule/articulo');
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true)
            ->setContainerCssClass('articulos');

        $this->_addBreadcrumb(
            Mage::helper('myextension_mymodule')->__('Manage Articulos'),
            Mage::helper('catalog')->__('Manage Articulos')
        );

        $block = $this->getLayout()->getBlock('catalog.wysiwyg.js');
        if ($block) {
            $block->setStoreId($storeId);
        }

        $this->renderLayout();
    }

    /**
     * WYSIWYG editor action for ajax request
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function wysiwygAction()
    {
        $elementId = $this->getRequest()->getParam('element_id', md5(microtime()));
        $storeId = $this->getRequest()->getParam('store_id', 0);
        $storeMediaUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);

        $content = $this->getLayout()->createBlock(
            'adminhtml/catalog_helper_form_wysiwyg_content',
            '',
            array(
                'editor_element_id' => $elementId,
                'store_id'          => $storeId,
                'store_media_url'   => $storeMediaUrl,
            )
        );

        $this->getResponse()->setBody($content->toHtml());
    }

    /**
     * Get tree node (Ajax version)
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function articulosJsonAction()
    {
        if ($this->getRequest()->getParam('expand_all')) {
            Mage::getSingleton('admin/session')->setArticuloIsTreeWasExpanded(true);
        } else {
            Mage::getSingleton('admin/session')->setArticuloIsTreeWasExpanded(false);
        }
        if ($articuloId = (int) $this->getRequest()->getPost('id')) {
            $this->getRequest()->setParam('id', $articuloId);

            if (!$articulo = $this->_initArticulo()) {
                return;
            }
            $this->getResponse()->setBody(
                $this->getLayout()->createBlock('myextension_mymodule/adminhtml_articulo_tree')
                    ->getTreeJson($articulo)
            );
        }
    }

    /**
     * Articulo save
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if (!$articulo = $this->_initArticulo()) {
            return;
        }

        $storeId = $this->getRequest()->getParam('store');
        $refreshTree = 'false';
        if ($data = $this->getRequest()->getPost()) {
            $articulo->addData($data['articulo']);
            if (!$articulo->getId()) {
                $parentId = $this->getRequest()->getParam('parent');
                if (!$parentId) {
                    $parentId = Mage::helper('myextension_mymodule/articulo')->getRootArticuloId();
                }
                $parentArticulo = Mage::getModel('myextension_mymodule/articulo')->load($parentId);
                $articulo->setPath($parentArticulo->getPath());
            }

            /**
             * Process "Use Config Settings" checkboxes
             */
            if ($useConfig = $this->getRequest()->getPost('use_config')) {
                foreach ($useConfig as $attributeCode) {
                    $articulo->setData($attributeCode, null);
                }
            }

            $articulo->setAttributeSetId($articulo->getDefaultAttributeSetId());

            Mage::dispatchEvent(
                'myextension_mymodule_articulo_prepare_save',
                array(
                    'articulo' => $articulo,
                    'request' => $this->getRequest()
                )
            );

            $articulo->setData("use_post_data_config", $this->getRequest()->getPost('use_config'));

            try {
                $products = $this->getRequest()->getPost('articulo_products', -1);
                if ($products != -1) {
                    $productData = array();
                    parse_str($products, $productData);
                    $products = array();
                    foreach ($productData as $id => $position) {
                        $products[$id]['position'] = $position;
                    }
                    $articulo->setProductsData($productData);
                }
                $categories = $this->getRequest()->getPost('category_ids', -1);
                if ($categories != -1) {
                    $categories = explode(',', $categories);
                    $categories = array_unique($categories);
                    $articulo->setCategoriesData($categories);
                }
                /**
                 * Check "Use Default Value" checkboxes values
                 */
                if ($useDefaults = $this->getRequest()->getPost('use_default')) {
                    foreach ($useDefaults as $attributeCode) {
                        $articulo->setData($attributeCode, false);
                    }
                }

                /**
                 * Unset $_POST['use_config'] before save
                 */
                $articulo->unsetData('use_post_data_config');

                $articulo->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('myextension_mymodule')->__('The articulo has been saved.')
                );
                $refreshTree = 'true';
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage())
                    ->setArticuloData($data);
                $refreshTree = 'false';
            }
        }
        $url = $this->getUrl('*/*/edit', array('_current' => true, 'id' => $articulo->getId()));
        $this->getResponse()->setBody(
            '<script type="text/javascript">parent.updateContent("' . $url . '", {}, '.$refreshTree.');</script>'
        );
    }

    /**
     * Move articulo action
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function moveAction()
    {
        $articulo = $this->_initArticulo();
        if (!$articulo) {
            $this->getResponse()->setBody(
                Mage::helper('myextension_mymodule')->__('Articulo move error')
            );
            return;
        }
        $parentNodeId   = $this->getRequest()->getPost('pid', false);
        $prevNodeId     = $this->getRequest()->getPost('aid', false);

        try {
            $articulo->move($parentNodeId, $prevNodeId);
            $this->getResponse()->setBody("SUCCESS");
        } catch (Mage_Core_Exception $e) {
            $this->getResponse()->setBody($e->getMessage());
        } catch (Exception $e) {
            $this->getResponse()->setBody(
                Mage::helper('myextension_mymodule')->__('Articulo move error')
            );
            Mage::logException($e);
        }

    }

    /**
     * Delete articulo action
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ($id = (int) $this->getRequest()->getParam('id')) {
            try {
                $articulo = Mage::getModel('myextension_mymodule/articulo')->load($id);
                Mage::dispatchEvent(
                    'myextension_mymodule_controller_articulo_delete',
                    array('articulo' => $articulo)
                );

                Mage::getSingleton('admin/session')->setArticuloDeletedPath($articulo->getPath());

                $articulo->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('myextension_mymodule')->__('The articulo has been deleted.')
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('_current'=>true)));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('myextension_mymodule')->__('An error occurred while trying to delete the articulo.')
                );
                $this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('_current'=>true)));
                return;
            }
        }
        $this->getResponse()->setRedirect($this->getUrl('*/*/', array('_current'=>true, 'id'=>null)));
    }

    /**
     * Tree Action
     * Retrieve articulo tree
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function treeAction()
    {
        $storeId = (int) $this->getRequest()->getParam('store');
        $articuloId = (int) $this->getRequest()->getParam('id');

        if ($storeId) {
            if (!$articuloId) {
                $store = Mage::app()->getStore($storeId);
                $rootId = Mage::helper('myextension_mymodule/articulo')->getRootArticuloId();
                $this->getRequest()->setParam('id', $rootId);
            }
        }

        $articulo = $this->_initArticulo();

        $block = $this->getLayout()->createBlock('myextension_mymodule/adminhtml_articulo_tree');
        $root  = $block->getRoot();
        $this->getResponse()->setBody(
            Mage::helper('core')->jsonEncode(
                array(
                    'data' => $block->getTree(),
                    'parameters' => array(
                        'text'         => $block->buildNodeName($root),
                        'draggable'    => false,
                        'allowDrop'    => ($root->getIsVisible()) ? true : false,
                        'id'           => (int) $root->getId(),
                        'expanded'     => (int) $block->getIsWasExpanded(),
                        'store_id'     => (int) $block->getStore()->getId(),
                        'articulo_id' => (int) $articulo->getId(),
                        'root_visible' => (int) $root->getIsVisible()
                    )
                )
            )
        );
    }

   /**
    * Build response for refresh input element 'path' in form
    *
    * @access public
    * @author Ultimate Module Creator
    */
    public function refreshPathAction()
    {
        if ($id = (int) $this->getRequest()->getParam('id')) {
            $articulo = Mage::getModel('myextension_mymodule/articulo')->load($id);
            $this->getResponse()->setBody(
                Mage::helper('core')->jsonEncode(
                    array(
                       'id' => $id,
                       'path' => $articulo->getPath(),
                    )
                )
            );
        }
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Ultimate Module Creator
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('myextension_mymodule/articulo');
    }

    /**
     * get the products grid
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function productsgridAction()
    {
        if (!$articulo = $this->_initArticulo()) {
            return;
        }
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock(
                'myextension_mymodule/adminhtml_articulo_edit_tab_product',
                'articulo.product.grid'
            )
            ->toHtml()
        );
    }

    /**
     * get child categories action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function categoriesJsonAction()
    {
        $this->_initArticulo();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('myextension_mymodule/adminhtml_articulo_edit_tab_categories')
                ->getCategoryChildrenJson($this->getRequest()->getParam('category'))
        );
    }
}
