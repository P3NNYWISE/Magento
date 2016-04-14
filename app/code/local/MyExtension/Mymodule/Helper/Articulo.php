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
 * Articulo helper
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_Helper_Articulo extends Mage_Core_Helper_Abstract
{

    /**
     * get the url to the articulos list page
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getArticulosUrl()
    {
        if ($listKey = Mage::getStoreConfig('myextension_mymodule/articulo/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('myextension_mymodule/articulo/index');
    }

    /**
     * check if breadcrumbs can be used
     *
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function getUseBreadcrumbs()
    {
        return Mage::getStoreConfigFlag('myextension_mymodule/articulo/breadcrumbs');
    }
    const ARTICULO_ROOT_ID = 1;
    /**
     * get the root id
     *
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getRootArticuloId()
    {
        return self::ARTICULO_ROOT_ID;
    }

    /**
     * check if the rss for articulo is enabled
     *
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function isRssEnabled()
    {
        return  Mage::getStoreConfigFlag('rss/config/active') &&
            Mage::getStoreConfigFlag('myextension_mymodule/articulo/rss');
    }

    /**
     * get the link to the articulo rss list
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRssUrl()
    {
        return Mage::getUrl('myextension_mymodule/articulo/rss');
    }

    /**
     * get base files dir
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getFileBaseDir()
    {
        return Mage::getBaseDir('media').DS.'articulo'.DS.'file';
    }

    /**
     * get base file url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getFileBaseUrl()
    {
        return Mage::getBaseUrl('media').'articulo'.'/'.'file';
    }

    /**
     * get articulo attribute source model
     *
     * @access public
     * @param string $inputType
     * @return mixed (string|null)
     * @author Ultimate Module Creator
     */
     public function getAttributeSourceModelByInputType($inputType)
     {
         $inputTypes = $this->getAttributeInputTypes();
         if (!empty($inputTypes[$inputType]['source_model'])) {
             return $inputTypes[$inputType]['source_model'];
         }
         return null;
     }

    /**
     * get attribute input types
     *
     * @access public
     * @param string $inputType
     * @return array()
     * @author Ultimate Module Creator
     */
    public function getAttributeInputTypes($inputType = null)
    {
        $inputTypes = array(
            'multiselect' => array(
                'backend_model' => 'eav/entity_attribute_backend_array'
            ),
            'boolean'     => array(
                'source_model'  => 'eav/entity_attribute_source_boolean'
            ),
            'file'          => array(
                'backend_model' => 'myextension_mymodule/articulo_attribute_backend_file'
            ),
            'image'          => array(
                'backend_model' => 'myextension_mymodule/articulo_attribute_backend_image'
            ),
        );

        if (is_null($inputType)) {
            return $inputTypes;
        } else if (isset($inputTypes[$inputType])) {
            return $inputTypes[$inputType];
        }
        return array();
    }

    /**
     * get articulo attribute backend model
     *
     * @access public
     * @param string $inputType
     * @return mixed (string|null)
     * @author Ultimate Module Creator
     */
    public function getAttributeBackendModelByInputType($inputType)
    {
        $inputTypes = $this->getAttributeInputTypes();
        if (!empty($inputTypes[$inputType]['backend_model'])) {
            return $inputTypes[$inputType]['backend_model'];
        }
        return null;
    }

    /**
     * filter attribute content
     *
     * @access public
     * @param MyExtension_Mymodule_Model_Articulo $articulo
     * @param string $attributeHtml
     * @param string @attributeName
     * @return string
     * @author Ultimate Module Creator
     */
    public function articuloAttribute($articulo, $attributeHtml, $attributeName)
    {
        $attribute = Mage::getSingleton('eav/config')->getAttribute(
            MyExtension_Mymodule_Model_Articulo::ENTITY,
            $attributeName
        );
        if ($attribute && $attribute->getId() && !$attribute->getIsWysiwygEnabled()) {
            if ($attribute->getFrontendInput() == 'textarea') {
                $attributeHtml = nl2br($attributeHtml);
            }
        }
        if ($attribute->getIsWysiwygEnabled()) {
            $attributeHtml = $this->_getTemplateProcessor()->filter($attributeHtml);
        }
        return $attributeHtml;
    }

    /**
     * get the template processor
     *
     * @access protected
     * @return Mage_Catalog_Model_Template_Filter
     * @author Ultimate Module Creator
     */
    protected function _getTemplateProcessor()
    {
        if (null === $this->_templateProcessor) {
            $this->_templateProcessor = Mage::helper('catalog')->getPageTemplateProcessor();
        }
        return $this->_templateProcessor;
    }
}
