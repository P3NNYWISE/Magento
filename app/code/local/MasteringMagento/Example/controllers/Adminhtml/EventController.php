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
            $this->getLayout()->createBlock('example/adminhtml_event_edit')
        );

        return $this->renderLayout();
    }
}
