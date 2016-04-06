<?php
/**
 * app/code/local/MasteringMagento/Example/Block/Checkout/Onepage/Event/Registration.php
 *
 * This example code is provided for use with the Mastering Magento video
 * series, by Packt Publishing.
 *
 * @author    Franklin P. Strube <franklin.strube@gmail.com>
 * @category  MasteringMagento
 * @package   Example
 * @copyright Copyright (c) 2012 Packt Publishing (http://packtpub.com)
 */
class MasteringMagento_Example_Block_Checkout_Onepage_Event_Registration
    extends Mage_Checkout_Block_Onepage_Abstract
{
    public function getRegistrant()
    {
        // TODO Return the registrant information
        return new Varien_Object();
    }

    /**
     * Get all of the tickets that are about to be ordered
     *
     * @return array
     */
    public function getEvents()
    {
        $checkout = Mage::getSingleton('checkout/session');
        $quote = $checkout->getQuote();

        $events = array();
        foreach ( $quote->getAllItems() as $item ) {
            if ( ($product = $item->getProduct()) && $product->getTypeId() != 'event' ) continue;

            $events[] = $item;
        }

        return $events;
    }
}
