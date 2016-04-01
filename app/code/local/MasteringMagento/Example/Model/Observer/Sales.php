<?php
/**
 * app/code/local/MasteringMagento/Example/Model/Observer/Sales.php
 *
 * This example code is provided for use with the Mastering Magento video
 * series, by Packt Publishing.
 *
 * @author    Franklin P. Strube <franklin.strube@gmail.com>
 * @category  MasteringMagento
 * @package   Example
 * @copyright Copyright (c) 2012 Packt Publishing (http://packtpub.com)
 */
class MasteringMagento_Example_Model_Observer_Sales
{
    public function salesOrderPlaceAfter($observer)
    {
        $order  = $observer->getEvent()->getOrder();
        /* @var $order Mage_Sales_Model_Order */

        foreach ($order->getAllItems() as $item) {
            if ( $item->getProductType() != 'event' ) continue;

            $buyRequest = $item->getBuyRequest();

            if ( $tickets = $buyRequest->getTicket() ) {
                foreach ( $tickets as $ticketId => $ticket ) {
                    foreach ( $ticket['registrant_info'] as $registrant ) {
                        Mage::getModel('example/event_registrant')->setData($registrant)->save();
                    }
                }
            }
        }

        return $this;
    }
}
