<?php
/**
 * app/code/local/MasteringMagento/Example/controllers/HelloController.php
 *
 * This example code is provided for use with the Mastering Magento video
 * series, by Packt Publishing.
 *
 * @author    Franklin P. Strube <franklin.strube@gmail.com>
 * @category  MasteringMagento
 * @package   Example
 * @copyright Copyright (c) 2012 Packt Publishing (http://packtpub.com)
 */
class MasteringMagento_Example_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {

    	#Testing Roles 
    	/*
    	mage::log(Mage::getModel('admin/roles'));
		$roles = Mage::getModel('admin/roles')->getCollection();
		foreach ($roles as $rol) {
			# code...
			if ($rol->getRoleName() == "Users"){

				mage::log("heelo Users");
			}


		}
		*/
        $this->loadLayout();
        return $this->renderLayout();
    }
}

