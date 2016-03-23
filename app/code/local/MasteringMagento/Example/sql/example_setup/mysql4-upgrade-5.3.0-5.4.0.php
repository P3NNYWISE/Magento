<?php

/* @var $this Mage_Core_Model_Resource_Setup */
$this->startSetup();

$catalogInstaller  = new Mage_Catalog_Model_Resource_Setup($this->_resourceName);

// Create a table to store the event tickets
$this->run("
CREATE TABLE `{$this->getTable('example/event_ticket')}` (
	`ticket_id` INTEGER AUTO_INCREMENT PRIMARY KEY,
	`event_id` INTEGER,
	`product_id` INTEGER,
	`title` VARCHAR(255),
	`price` DECIMAL(12,4),
	`sort_order` INTEGER DEFAULT 0,
	`created_at` DATETIME,
	`modified_at` DATETIME
)
");

$this->endSetup();