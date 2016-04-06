<?php
/**
 * bootstrap.php
 *
 * This example code is provided for use with the Mastering Magento video
 * series, by Packt Publishing.
 *
 * @author    Franklin P. Strube <franklin.strube@gmail.com>
 * @category  MasteringMagento
 * @package   Example
 * @copyright Copyright (c) 2012 Packt Publishing (http://packtpub.com)
 */
if (version_compare(phpversion(), '5.2.0', '<')===true) {
    echo '<div style="font:12px/1.35em arial, helvetica, sans-serif;">
<div style="margin:0 0 25px 0; border-bottom:1px solid #ccc;">
<h3 style="margin:0; font-size:1.7em; font-weight:normal; text-transform:none; text-align:left; color:#2f2f2f;">
Whoops, it looks like you have an invalid PHP version.</h3></div><p>Magento support PHP 5.2.0 or newer.
<a href="http://www.magentocommerce.com/install" target="">Find out</a> how to install</a>
 Magento using PHP-CGI as a work-around.</p></div>';
    exit;
}

/**
 * Error reporting
 */
error_reporting(E_ALL | E_STRICT);

/**
 * Compilation includes configuration file
 */
define('MAGENTO_ROOT', dirname(__FILE__));

$compilerConfig = MAGENTO_ROOT . '/includes/config.php';
if (file_exists($compilerConfig)) {
    include $compilerConfig;
}

$mageFilename = MAGENTO_ROOT . '/app/Mage.php';

if (!file_exists($mageFilename)) {
    echo $mageFilename." was not found";
    exit;
}

require_once $mageFilename;

#Varien_Profiler::enable();

if (isset($_SERVER['MAGE_IS_DEVELOPER_MODE'])) {
    Mage::setIsDeveloperMode(true);
}

#ini_set('display_errors', 1);

Mage::app();
