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

class TestingMagento_Holamundo_Adminhtml_HolaController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {

    	
        $this->loadLayout();

        return $this->renderLayout();
    }
}
