<?php
/**
 * app/code/local/MasteringMagento/Model/Convert/Adapter/Http/Curl.php
 *
 * This example code is provided for use with the Mastering Magento video
 * series, by Packt Publishing.
 *
 * @author    Franklin P. Strube <franklin.strube@gmail.com>
 * @category  MasteringMagento
 * @package   Example
 * @copyright Copyright (c) 2012 Packt Publishing (http://packtpub.com)
 */
class MasteringMagento_Example_Model_Convert_Adapter_Http_Curl extends Mage_Dataflow_Model_Convert_Adapter_Http_Curl
{
    /**
     * @var Mage_Dataflow_Model_Batch
     */
    protected $_batch;

    /**
     * Fix bug in Dataflow adapters.
     */
    public function load()
    {
        parent::load();

        if ( $data = $this->getData() ) {
            $batchModel = $this->getBatchModel();
            $batchIoAdapter = $this->getBatchModel()->getIoAdapter();
            $batchIoAdapter->open(true); // true for write
            $batchIoAdapter->write($data);
            $batchIoAdapter->close();
        }
    }

    /**
     * Retrieve Batch model singleton
     *
     * @return Mage_Dataflow_Model_Batch
     * @see  Mage_Dataflow_Model_Convert_Parser_Abstract::getBatchModel
     */
    public function getBatchModel()
    {
        if (is_null($this->_batch)) {
            $this->_batch = Mage::getSingleton('dataflow/batch');
        }
      
        return $this->_batch;
    }
}
