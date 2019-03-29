<?php
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
	$search = $api->searchApp("netguard");
	$appid = $api->decode($search)->data->list[0]->appid;
	
	$app = $api->decode($api->getAppInfo($appid));
	$app = $app->data->links[0]; // Gets the latest version
	
	echo "<b>App name:</b> ".$appid."<br/><b>Version:</b> ".$app->version."<br/><b>DL Link:</b> <a href='".$app->real_link."'>".$app->real_link."</a>";
} catch (Exception $e) {
	echo 'Error: ', $api->ErrorInfo;
}