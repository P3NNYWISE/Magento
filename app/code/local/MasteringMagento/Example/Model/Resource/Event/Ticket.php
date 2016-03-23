<?php
/**
 * app/code/local/MasteringMagento/Model/Event.php
 *
 * This example code is provided for use with the Mastering Magento video
 * series, by Packt Publishing.
 *
 * @author    Franklin P. Strube <franklin.strube@gmail.com>
 * @category  MasteringMagento
 * @package   Example
 * @copyright Copyright (c) 2012 Packt Publishing (http://packtpub.com)
 */

class MasteringMagento_Example_Model_Event_Ticket extends Mage_Core_Model_Abstract

{
    public function _construct()
    {
        $this->_init('example/event_ticket');
    }
}
