<?php
/**
 * app/code/local/MasteringMagento/Block/Adminhtml/Event.php
 *
 * This example code is provided for use with the Mastering Magento video
 * series, by Packt Publishing.
 *
 * @author    Franklin P. Strube <franklin.strube@gmail.com>
 * @category  MasteringMagento
 * @package   Example
 * @copyright Copyright (c) 2012 Packt Publishing (http://packtpub.com)
 */
class MasteringMagento_Example_Block_Adminhtml_Event extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'example';
        $this->_controller = 'adminhtml_event';
        $this->_headerText = Mage::helper('example')->__('Events');
        $this->_addButtonLabel = Mage::helper('example')->__('Add New Event');

        parent::__construct();
    }
}
