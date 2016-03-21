<?php
/**
 * app/code/local/MasteringMagento/Example/controllers/Adminhtml/ExampleController.php
 *
 * This example code is provided for use with the Mastering Magento video
 * series, by Packt Publishing.
 *
 * @author    Franklin P. Strube <franklin.strube@gmail.com>
 * @category  MasteringMagento
 * @package   Example
 * @copyright Copyright (c) 2012 Packt Publishing (http://packtpub.com)
 */
class MasteringMagento_Example_Adminhtml_ExampleController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {


    	$event = Mage::getModel('example/event');
    	$event -> setName('Test Event')->save();
    	Mage::getSingleton('adminhtml/session')->addSuccess('Event saved. ID = '.$event->getId()
    		);

        $this->loadLayout();

        return $this->renderLayout();
    }
}
