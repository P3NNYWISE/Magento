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
