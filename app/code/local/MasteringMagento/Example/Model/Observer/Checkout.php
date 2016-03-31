<?php
/**
 * app/code/local/MasteringMagento/Example/Model/Observer/Checkout.php
 *
 * This example code is provided for use with the Mastering Magento video
 * series, by Packt Publishing.
 *
 * @author    Franklin P. Strube <franklin.strube@gmail.com>
 * @category  MasteringMagento
 * @package   Example
 * @copyright Copyright (c) 2012 Packt Publishing (http://packtpub.com)
 */
class MasteringMagento_Example_Model_Observer_Checkout
{
    public function isAllowedGuest($observer)
    {
        $quote  = $observer->getEvent()->getQuote();
        /* @var $quote Mage_Sales_Model_Quote */
        $result = $observer->getEvent()->getResult();

        foreach ($quote->getAllItems() as $item) {

            if (($product = $item->getProduct()) /*&& $product->getTypeId() == 'event'*/) {
                $result->setIsAllowed(false);
                var_dump($product->getTypeId);
                break;
            }
        }

        return $this;
    }
}
