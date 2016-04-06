<?php

$this->startSetup();

    $catalogInstaller = new Mage_Catalog_Model_Resource_Setup($this->_resourceName);

    // Apply all simple attributes to event products
    $additionalTable = $catalogInstaller->getEntityType('catalog_product', 'additional_attribute_table');
  
  //die($additionalTable);
  //die($this->getTable($additionalTable));

// Error   $additionalTable Vacio   addi

      
    $this->run("
    UPDATE `{$this->getTable($additionalTable)}`
        SET apply_to = CONCAT_WS(',', apply_to, 'event')
        WHERE apply_to LIKE '%simple%' AND apply_to NOT LIKE '%event'
    ;
    ");

    // Add title for the event
    $catalogInstaller->addAttribute('catalog_product', 'event_title', array(
        'label'          => 'Event Title',
        'required'       => true,
        'group'          => 'Event Settings',
        'apply_to'       => 'event'
    ));

    // Add select list for the event
    $catalogInstaller->addAttribute('catalog_product', 'event_id', array(
        'label'          => 'Event ID',
        'required'       => true,
        'group'          => 'Event Settings',
        'input'          => 'select',
        'source'         => 'example/product_attribute_source_event',
        'apply_to'       => 'event',
        'note'           => 'Select your event from the list'
    ));


    
$this->endSetup();
