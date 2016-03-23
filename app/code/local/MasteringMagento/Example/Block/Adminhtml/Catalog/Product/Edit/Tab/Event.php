<?php
/**
 * app/code/local/MasteringMagento/Example/Block/Adminhtml/Catalog/Product/Edit/Tab/Event.php
 *
 * This example code is provided for use with the Mastering Magento video
 * series, by Packt Publishing.
 *
 * @author    Franklin P. Strube <franklin.strube@gmail.com>
 * @category  MasteringMagento
 * @package   Example
 * @copyright Copyright (c) 2012 Packt Publishing (http://packtpub.com)
 */
class MasteringMagento_Example_Block_Adminhtml_Catalog_Product_Edit_Tab_Event
    extends Mage_Adminhtml_Block_Widget implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    /**
     * Class constructor
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('example/product/edit/event.phtml');
    }

    /**
     * Retrieve product
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        return Mage::registry('current_product');
    }

    /**
     * Get tab label
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('example')->__('Event Information');
    }

    /**
     * Get tab title
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('example')->__('Event Information');
    }

    /**
     * Check if tab can be displayed
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Check if tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Retrieve Add button HTML
     *
     * @return string
     */
    
    public function getAddButtonHtml()
    {
        $addButton = $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
                'label' => Mage::helper('example')->__('Add New Ticket'),
                'id'    => 'add_ticket_item',
                'class' => 'add'
            ));
        return $addButton->toHtml();
    }

    /**
     * Return array of tickets
     *
     * @return array
     */
    
    public function getTicketData()
    {
        $ticketArr = array();


        $tickets = $this->getProduct()->getTypeInstance(true)->getTickets($this->getProduct());
        foreach ($tickets as $ticket) {
            $tmpTicketItem = array(
                'ticket_id' => $ticket->getId(),
                'title' => $this->escapeHtml($ticket->getTitle()),
                'price' => $this->getPriceValue($ticket->getPrice()),#getCanReadPrice(), 
                'sort_order' => $ticket->getSortOrder(),
            );
            $ticketArr[] = new Varien_Object($tmpTicketItem);
            
        }


        return $ticketArr;
    }

    /**
     * Return formated price with two digits after decimal point
     *
     * @param decimal $value
     * @return decimal
     */
    public function getPriceValue($value)
    {
        return number_format($value, 2, null, '');
    }

}
