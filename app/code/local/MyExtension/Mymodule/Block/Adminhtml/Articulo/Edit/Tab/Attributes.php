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
 * Articulo admin edit tab attributes block
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
*/
class MyExtension_Mymodule_Block_Adminhtml_Articulo_Edit_Tab_Attributes extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the attributes for the form
     *
     * @access protected
     * @return void
     * @see Mage_Adminhtml_Block_Widget_Form::_prepareForm()
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setDataObject(Mage::registry('current_articulo'));
        $fieldset = $form->addFieldset(
            'info',
            array(
                'legend' => Mage::helper('myextension_mymodule')->__('Articulo Information'),
                'class' => 'fieldset-wide',
            )
        );
        $attributes = $this->getAttributes();
        foreach ($attributes as $attribute) {
            $attribute->setEntity(Mage::getResourceModel('myextension_mymodule/articulo'));
        }
        if ($this->getAddHiddenFields()) {
            if (!$this->getArticulo()->getId()) {
                // path
                if ($this->getRequest()->getParam('parent')) {
                    $fieldset->addField(
                        'path',
                        'hidden',
                        array(
                            'name'  => 'path',
                            'value' => $this->getRequest()->getParam('parent')
                        )
                    );
                } else {
                    $fieldset->addField(
                        'path',
                        'hidden',
                        array(
                            'name'  => 'path',
                            'value' => 1
                        )
                    );
                }
            } else {
                $fieldset->addField(
                    'id',
                    'hidden',
                    array(
                        'name'  => 'id',
                        'value' => $this->getArticulo()->getId()
                    )
                );
                $fieldset->addField(
                    'path',
                    'hidden',
                    array(
                        'name'  => 'path',
                        'value' => $this->getArticulo()->getPath()
                    )
                );
            }
        }
        $this->_setFieldset($attributes, $fieldset, array());
        $formValues = Mage::registry('current_articulo')->getData();
        if (!Mage::registry('current_articulo')->getId()) {
            foreach ($attributes as $attribute) {
                if (!isset($formValues[$attribute->getAttributeCode()])) {
                    $formValues[$attribute->getAttributeCode()] = $attribute->getDefaultValue();
                }
            }
        }
        //do not set default value for path
        unset($formValues['path']);
        $form->addValues($formValues);
        $form->setFieldNameSuffix('articulo');
        $this->setForm($form);
    }

    /**
     * prepare layout
     *
     * @access protected
     * @return void
     * @see Mage_Adminhtml_Block_Widget_Form::_prepareLayout()
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        Varien_Data_Form::setElementRenderer(
            $this->getLayout()->createBlock('adminhtml/widget_form_renderer_element')
        );
        Varien_Data_Form::setFieldsetRenderer(
            $this->getLayout()->createBlock('adminhtml/widget_form_renderer_fieldset')
        );
        Varien_Data_Form::setFieldsetElementRenderer(
            $this->getLayout()->createBlock('myextension_mymodule/adminhtml_mymodule_renderer_fieldset_element')
        );
    }

    /**
     * get the additional element types for form
     *
     * @access protected
     * @return array()
     * @see Mage_Adminhtml_Block_Widget_Form::_getAdditionalElementTypes()
     * @author Ultimate Module Creator
     */
    protected function _getAdditionalElementTypes()
    {
        return array(
            'file'     => Mage::getConfig()->getBlockClassName(
                'myextension_mymodule/adminhtml_articulo_helper_file'
            ),
            'image'    => Mage::getConfig()->getBlockClassName(
                'myextension_mymodule/adminhtml_articulo_helper_image'
            ),
            'textarea' => Mage::getConfig()->getBlockClassName(
                'adminhtml/catalog_helper_form_wysiwyg'
            )
        );
    }

    /**
     * get current entity
     *
     * @access protected
     * @return MyExtension_Mymodule_Model_Articulo
     * @author Ultimate Module Creator
     */
    public function getArticulo()
    {
        return Mage::registry('current_articulo');
    }
}
