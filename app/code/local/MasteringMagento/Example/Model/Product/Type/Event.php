<?php
/**
 * app/code/local/MasteringMagento/Model/Project/Type/Event.php
 *
 * This example code is provided for use with the Mastering Magento video
 * series, by Packt Publishing.
 *
 * @author    Franklin P. Strube <franklin.strube@gmail.com>
 * @category  MasteringMagento
 * @package   Example
 * @copyright Copyright (c) 2012 Packt Publishing (http://packtpub.com)






 
 */
class MasteringMagento_Example_Model_Product_Type_Event extends Mage_Catalog_Model_Product_Type_Abstract
{

    public function processBuyRequest($product , $buyRequest)
    {
            $options = array();
            if ($tickets = $buyRequest->getTickets()){
                $options['ticket']=  $tickets ;

            }
            return $options;

    }



     public function getTickets($product = null)
    {
        $product = $this->getProduct($product);
        $collection = Mage::getModel("example/event_ticket")->getCollection()
            ->addFieldToFilter('event_id', $product->getEventId())
            ->addFieldToFilter('product_id', $product->getId())
            ->setOrder('sort_order', 'asc');

        return $collection;
    }

    /**
     * Save Product event information
     *
     * @param Mage_Catalog_Model_Product $product
     * @return MasteringMagento_Example_Model_Product_Type_Event
     */
    public function save($product = null)
    {
        parent::save($product);

        $product = $this->getProduct($product);
        /* @var Mage_Catalog_Model_Product $product */

        if ($eventData = $product->getEventData()) {
            if ( $eventData['ticket'] ) {
                foreach ( $eventData['ticket'] as $ticket ) {
                    // Load the model
                    $ticketModel = Mage::getModel('example/event_ticket')->load($ticket['ticket_id']);
                    unset($ticket['ticket_id']);

                    if ( $ticket['is_delete'] == 1 ) {
                        $ticketModel->delete();
                    } else {
                        unset($ticket['is_delete']);

                        // Set the ticket's event id
                        $ticket['event_id'] = $product->getEventId();
                        $ticket['product_id'] = $product->getId();

                        // Save new data to the ticket
                        $ticketModel->addData($ticket);
                        $ticketModel->save();
                    }
                }
            }
        }

        return $this;
    }
    public function hasOptions($product = null)
    {
        return true;
    }

}
    public function _prepareOptions(Varien_Object $buyRequest, $product, $processMode)
    {
        $product = $this->getProduct($product);
        $isStrictProcessMode = $this->_isStrictProcessMode($processMode);

        // Run the parent method to start
        $_options = parent::_prepareOptions($buyRequest, $product, $processMode);

        // Add ticket information to additional options for Magento to display
        $additionalOptions = array();
        if ( $tickets = $buyRequest->getTicket() ) {
            foreach ( $tickets as $ticketId => $data ) {
                $_ticket = Mage::getModel('example/event_ticket')->load($ticketId);
                if ( !$_ticket->getId() ) {
                    $message = Mage::helper('example')->__('Ticket does not exist!');
                    Mage::throwException($message);
                }

                // Add the ticket information to the additional options array
                $additionalOptions[] = array(
                    'label' => $_ticket->getTitle(),
                    'value' => Mage::helper('example')->__('Qty: %s', $data['qty'])
                );
            }
        }
        $product->addCustomOption('additional_options', serialize($additionalOptions));

        // Return the options
        return $_options;
    }
