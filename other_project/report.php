<?php
require_once "/var/www/magento/bootstrap.php";

Mage::init();

echo "=========================================================================\n";
echo "Store: " .
    Mage::app()->getWebsite()->getName() . " / " .
    Mage::app()->getGroup()->getName() . " / " .
    Mage::app()->getStore()->getName() . "\n"
echo "=========================================================================\n";

$collection = Mage::getModel('sales/order')->getCollection();

// Make some CSV output of orders
echo "increment_id,customer_email,grand_total\n";
foreach ( $collection as $order ) {
    printf("%s,%s,%s\n", $order->getIncrementId(), $order->getCustomerEmail(), $order->getGrandTotal());
}
