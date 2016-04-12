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
            #$this->getLayout()->createBlock('example/adminhtml_event')
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

        if ( $eventId = $this->getRequest()->getParam('event_id'))
        {
            mage::register('current_event',  Mage::getModel('example/event')->load($eventId));
        }

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
                Mage::getSingleton('adminhtml/session')->setEventFormData($data);
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                return $this->_redirect('*/*/edit', array('_current'=> true));
            }

        }

        return $this->_redirect('*/*/index');
    }

    public function deleteAction()
    {
        $eventId = $this->getRequest()->getParam('event_id');
        $eventModel = Mage::getModel('example/event')->load($eventId);

   
            try {
                $eventModel->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    $this->__("Your event has been deleted!")
                );
            } catch ( Exception $e ) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                return $this->_redirect('*/*/edit', array('_current' => true));
            }
     

        return $this->_redirect('*/*/index');
    }

   public function massDeleteAction()
    {   
         if ( $eventIds = $this->getRequest()->getParam('event_ids'))  {
            try {
                 foreach ($eventIds as $eventId) {

                     # code...
                     $model = Mage::getModel('example/event')->load($eventId);
                     $model->delete();
                 }
                 Mage::getSingleton('adminhtml/session')->addSuccess(
                     $this->__("%d event(s) have been deleted!",count($eventIds))
                    );
             } catch ( Exception $e ) {
              Mage::getSingleton('adminhtml/session')->addError(
                     $e->getMessage()
                     );
             }
         }else 
         {
                 Mage::getSingleton('adminhtml/session')->addError(
                     $this->__('you must select an event.')
                     );
         }

         return $this->_redirect('*/*/index');
    }
    /**
        Export data to csv
    **/

    public function exportCsvAction()
    {
        // TODO follow along with the video!
        $filename = 'events.csv';
        $grid = $this->getLayout()->createBlock('example/adminhtml_event_grid');
        $this->_prepareDownloadResponse($filename , $grid->getCsvFile());


    }

    /**
    Export data to eXcel- it's not working
    **/
    public function exportExcelAction()
    {
        // TODO follow along with the video!

        $filename ='events.xlsx';
        $grid =  $this->getLayout()->createBlock('example/adminhtml_event_grid');
        $this->_prepareDownloadResponse($filename , $grid->getExcelFile($filename));
    }


}

