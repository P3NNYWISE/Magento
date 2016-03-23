<?php

class MasteringMagento_Example_Model_Resource_Ticket_Event_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
    protected function _construct()
    {
            $this->_init('example/event_ticket');
    }
}