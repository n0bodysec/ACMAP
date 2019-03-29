<?php

if (!defined('ACMARKET_LOADED')) exit;		// do not change!

/* autoload */
require('src/Exception.php');
require('src/ACMarket.php');

/* absolute path to the script directory */
if (!defined('ABSPATH'))
	define('ABSPATH', realpath(dirname(__FILE__) . '/..')); // dirname(__FILE__) . '/' if in root folder

/* general config */
ini_set('display_errors', 0);			// hide errors
?>