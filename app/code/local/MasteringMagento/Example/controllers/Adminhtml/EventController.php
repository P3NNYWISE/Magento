<?php
/**
 * app/code/local/MasteringMagento/Example/controllers/Adminhtml/EventController.php
 *
 * This example code is provided for use with the Mastering Magento video
 * series, by Packt Publishing.
 *
 * @author    Franklin P. Strube <franklin.strube@gmail.com>
 * @category  MasteringMagento
 * @package   Example
 * @copyright Copyright (c) 2012 Packt Publishing (http://packtpub.com)
 */
class MasteringMagento_Example_Adminhtml_EventController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('example/events');

        $this->_addContent(
            $this->getLayout()->createBlock('example/adminhtml_event')
        );

        return $this->renderLayout();
    }



        public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('example/events');

        $this->_addContent(
            $this->getLayout()->createBlock('example/adminhtml_event_edit')
        );

        return $this->renderLayout();
    }


    public function saveAction()
    {
        $eventId = $this->getRequest()->getParam('event_id');
        $eventModel = Mage::getModel('example/event')->load($eventId);

        if ( $data = $this->getRequest()->getPost() ) {
            try {
                $eventModel->addData($data)->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    $this->__("Your event has been saved!")
                );
            } catch ( Exception $e ) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }
}

