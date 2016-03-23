<?php

/* @var $this Mage_Core_Model_Resource_Setup */
$this->startSetup();

$this->run("
CREATE TABLE {$this->getTable('example/event')} (
	`event_id` INTEGER AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR(255),
	`start` DATETIME,
	`end` DATETIME,
	`created_at` DATETIME,
	`modified_at` DATETIME
);
");

$this->run("
CREATE TABLE {$this->getTable('example/event_registrant')} (
	`registrant_id` INTEGER AUTO_INCREMENT PRIMARY KEY,
	`fullname` VARCHAR(255),
	`company` VARCHAR(255),
	`title` VARCHAR(255),
	`email` VARCHAR(255),
	`telephone` VARCHAR(255),
	`age` INTEGER,
	`created_at` DATETIME,
	`modified_at` DATETIME
);
");

$this->endSetup();