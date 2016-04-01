<?php 
$this->startSetup();

$this->run("ALTER TABLE  {$this->getTable('example/event_registrant')}
	ADD COLUMN `event_id` INTEGER AFTER   `registrant_id`,
	ADD COLUMN `ticket_id` INTEGER AFTER `event_id`;
");

$this->endSetup();