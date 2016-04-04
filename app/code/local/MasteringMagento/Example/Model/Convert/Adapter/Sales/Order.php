<?php
/**
 * app/code/local/MasteringMagento/Model/Convert/Adapter/Sales/Order.php
 *
 * This example code is provided for use with the Mastering Magento video
 * series, by Packt Publishing.
 *
 * @author    Franklin P. Strube <franklin.strube@gmail.com>
 * @category  MasteringMagento
 * @package   Example
 * @copyright Copyright (c) 2012 Packt Publishing (http://packtpub.com)
 */
class MasteringMagento_Example_Model_Convert_Adapter_Sales_Order extends Mage_Dataflow_Model_Convert_Adapter_Abstract
{
    /**
     * Need to override abstract functions
     */
    public function load()
    {
        return $this;
    }

    /**
     * Need to override abstract functions
     */
    public function save()
    {
        return $this;
    }

    /**
     * The actual function that will be run
     */
    public function saveRow($importData)
    {
        // TODO follow along with the video! 7.4
// Create a sales_quote
    
    $quote = Mage::getModel('sales/quote')
        ->setStoreId($this->getBatchParams('store'))
        ->setCustomerIsGuest(true)
        ->setCustomerFirstname($importData['firstname'])
        ->setCustomerLastname($importData['lastname'])
        ->setCustomerEmail($importData['email']);

    // Add the product
    $product = Mage::getModel('catalog/product');
    $product->load($product->getIdBySku($importData['sku']));
    $quote->addProduct($product, $importData['qty']);

    // Add the shipping address
    $quote->getShippingAddress()
        ->setFirstname($importData['firstname'])
        ->setLastname($importData['lastname'])
        ->setStreet($importData['shipping_street'])
        ->setCity($importData['shipping_city'])
        ->setRegion($importData['shipping_region'])
        ->setCountryId($importData['shipping_country'])
        ->setPostcode($importData['shipping_postcode'])
        ->setTelephone($importData['shipping_telephone'])
        ->setShippingMethod('flatrate_flatrate')
        ->setShippingDescription('Flat Rate - Fixed')
        ->setCollectShippingRates(true)
        ;

    // Add the billing address

    $quote->getBillingAddress()
        ->setFirstname($importData['firstname'])
        ->setLastname($importData['lastname'])
        ->setStreet($importData['shipping_street'])
        ->setCity($importData['shipping_city'])
        ->setRegion($importData['shipping_region'])
        ->setCountryId($importData['shipping_country'])
        ->setPostcode($importData['shipping_postcode'])
        ->setTelephone($importData['shipping_telephone']);

    // Add the payment method   cashondelivery
    Mage::log("inicio payment");
    $quote->getPayment()->setMethod('inperson');
       
    #$quote->getPayment()->setMethod('cashondelivery');
    // Save the quote in the database
    Mage::log("fin-pay"); 
    $quote->save()->collectTotals();

    // Create a service object to convert the quote into an order
    $service = Mage::getModel('sales/service_quote', $quote);
    $service->submitAll();

    // The order that was created
    $order = $service->getOrder();
    Mage::log("Order #{$order->getIncrementId()} created.");

    return $this;


    }
}
