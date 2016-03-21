

<?php
/**
 * app/code/local/MasteringMagento/Block/Adminhtml/Event/Edit/Form.php
 *
 * This example code is provided for use with the Mastering Magento video
 * series, by Packt Publishing.
 *
 * @author    Franklin P. Strube <franklin.strube@gmail.com>
 * @category  MasteringMagento
 * @package   Example
 * @copyright Copyright (c) 2012 Packt Publishing (http://packtpub.com)
 */
class MasteringMagento_Example_Block_Adminhtml_Event_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array('id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post')
        );

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('example')->__('General Information'), 'class' => 'fieldset-wide'));

        $fieldset->addField('name', 'text', array(
            'name'      => 'name',
            'label'     => Mage::helper('example')->__('Event Name '),
            'title'     => Mage::helper('example')->__('Event Name '),
            'required'  => true
        ));


        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $fieldset->addField('start', 'date', array(
            'name'      => 'start',
            'format'    => $dateFormatIso,
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'label'     => Mage::helper('example')->__('Start Date'),
            'title'     => Mage::helper('example')->__('Start Date'),
            'required'  => true
        ));

        $fieldset->addField('end', 'date', array(
            'name'      => 'end',
            'format'    => $dateFormatIso,
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'label'     => Mage::helper('example')->__('End Date'),
            'title'     => Mage::helper('example')->__('End Date'),
            'required'  => true
        ));

        $form->setUseContainer(true);
        $this->setForm($form);
    }
}