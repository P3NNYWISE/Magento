<?php
/**
 * MyExtension_Mymodule extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       MyExtension
 * @package        MyExtension_Mymodule
 * @copyright      Copyright (c) 2016
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
 /*
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
Mage::getModel('myextension_mymodule/articulo')
    ->load(1)
    ->setParentId(0)
    ->setPath(1)
    ->setLevel(0)
    ->setPosition(0)
    ->setChildrenCount(0)
    ->setTitulo('ROOT')
    ->setInitialSetupFlag(true)
    ->setAttributeSetId(Mage::getModel('myextension_mymodule/articulo')->getDefaultAttributeSetId())
    ->save();
