<?php
/**
 * app/code/local/MasteringMagento/Model/Resource/Event.php
 *
 * This example code is provided for use with the Mastering Magento video
 * series, by Packt Publishing.
 *
 * @author    Franklin P. Strube <franklin.strube@gmail.com>
 * @category  MasteringMagento
 * @package   Example
 * @copyright Copyright (c) 2012 Packt Publishing (http://packtpub.com)
 */
class MasteringMagento_Example_Model_Resource_Event_Registrant extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('example/event_registrant', 'registrant_id');
    }
}
