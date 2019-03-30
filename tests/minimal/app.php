<?php

if (!isset($_GET['appid'])) exit('Error');

define('ACMARKET_LOADED', true); // do not change!
require('config/config.php');

use n0bodysec\ACMarket\ACMarket;
use n0bodysec\ACMarket\Exception;

try {
	$api = new ACMarket(true);
} catch (Exception $e) {
	echo '<b>Fatal error:</b> ' . $e;
	exit();
}

try {
	
	$app = $api->decode($api->getAppInfo($_GET['appid']), true);
	
	echo "<pre>";
	print_r($app);
	echo "</pre>";

} catch (Exception $e) {
	echo 'Error: ', $api->ErrorInfo;
}