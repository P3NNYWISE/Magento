<?php
/**
 * app/code/local/MasteringMagento/Example/controllers/HelloController.php
 *
 * This example code is provided for use with the Mastering Magento video
 * series, by Packt Publishing.
 *
 * @author    Franklin P. Strube <franklin.strube@gmail.com>
 * @category  MasteringMagento
 * @package   Example
 * @copyright Copyright (c) 2012 Packt Publishing (http://packtpub.com)
 */
class MasteringMagento_Example_HelloController extends Mage_Core_Controller_Front_Action
{
    public function worldAction()
    {
        echo 'Hello, World!';
    }
}
