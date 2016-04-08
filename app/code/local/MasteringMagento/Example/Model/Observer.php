<?php

/**
*  
*/
class MasteringMagento_Example_Model_Observer 
{
	
	public function controllerActionPredispatch($observer)
	{
		# @var $observer Mage_Core_Model_Observer  Varien_Event_Observer?
		#die("running observer");
		$controllerAction = $observer->getEvent()->getControllerAction();
		Mage::log($controllerAction->getRequest()->getParams());


	}
}