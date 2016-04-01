<?php
/**
 * app/code/local/MasteringMagento/Example/Model/Observer/Catalog/Product.php
 *
 * This example code is provided for use with the Mastering Magento video
 * series, by Packt Publishing.
 *
 * @author    Franklin P. Strube <franklin.strube@gmail.com>
 * @category  MasteringMagento
 * @package   Example
 * @copyright Copyright (c) 2012 Packt Publishing (http://packtpub.com)
 */
class MasteringMagento_Example_Model_Observer_Catalog_Product
{
    // Actualiza el precio final de los tickets  en carrito
   
    public function getFinalPrice($observer)
    { 
    	$product = $observer->getEvent()->getProduct();
    	if ($buyRequest = $product->getCustomOption('info_buyRequest')){
    		$buyRequest = new Varien_Object(unserialize($buyRequest->getValue()));
    		$price = $product->getFinalPrice();
    		if ($tickets = $buyRequest->getTicket()){
    			foreach ($tickets as $ticketId => $data) {
    				$_ticket = Mage::getModel('example/event_ticket')->load($ticketId);
    				$price += $data['qty'] * $_ticket->getPrice();
    				
    			}
    		}
    		$product->setFinalPrice($price);
    	}
        
    	return $this;
    
    }

}
	