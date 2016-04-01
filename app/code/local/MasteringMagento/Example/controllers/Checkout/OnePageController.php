<?php
/**
 * app/code/local/MasteringMagento/Example/controllers/Checkout/OnepageController.php
 *
 * This example code is provided for use with the Mastering Magento video
 * series, by Packt Publishing.
 *
 * @category  MasteringMagento
 * @package   Example
 * @copyright Copyright (c) 2012 Packt Publishing (http://packtpub.com)
 */
class MasteringMagento_Example_Checkout_OnepageController extends Mage_Checkout_OnepageController
{
    public function saveEventAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('ticket', '');

            // Store the data in the item's buyRequest
            $quote = $this->getOnepage()->getQuote();

            foreach ( $quote->getAllItems() as $item ) {
                if ( ($product = $item->getProduct()) && $product->getTypeId() != 'event' ) continue;

                $buyRequest = $item->getBuyRequest();
                $updated = false;

                if ( $tickets = $buyRequest->getTicket() ) {
                    foreach ( $tickets as $ticketId => $ticket ) {
                        foreach ( $data as $info ) {
                            // Inject registrant info into the buyRequest
                            if ( $item->getId() == $info['item_id'] && $ticketId == $info['ticket_id'] ) {
                                unset($info['item_id']);
                                if ( !isset($tickets[$ticketId]['registrant_info']) ) {
                                    $tickets[$ticketId]['registrant_info'] = array();
                                }
                                $tickets[$ticketId]['registrant_info'][] = $info;
                                $updated = true;
                            }
                        }
                    }
                    $buyRequest->setTicket($tickets);
                }

                if ( $updated ) {
                    Mage::log($buyRequest->getData());

                    // Save the new buyRequest
                    $item->getOptionByCode('info_buyRequest')
                        ->setValue(serialize($buyRequest->getData()))
                        ->save();
                }
            }

            $result = array();
            $result['goto_section'] = 'billing';

            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }
}
