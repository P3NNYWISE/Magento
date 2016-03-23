<?php

/* @var $this Mage_Core_Model_Resource_Setup */
$this->startSetup();

$catalogInstaller  = new Mage_Catalog_Model_Resource_Setup($this->_resourceName);

// Remove the event title as a product attribute (redundant)
$catalogInstaller->removeAttribute('catalog_product', 'event_title');

// Remove the event_id attribute from the Event Settings tab
$catalogInstaller->addAttribute('catalog_product', 'event_id', array(
	'visible' => false,
	'visible_on_front' => false,
	'user_defined' => false
));

$groupId = $catalogInstaller->getAttributeGroup('catalog_product', 'Default', 'Event Settings', 'attribute_group_id');
if ( $groupId ) {
	$catalogInstaller->removeAttributeGroup('catalog_product', 'Default', $groupId);
}

$this->endSetup();