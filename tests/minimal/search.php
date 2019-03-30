<?php

if (!isset($_GET['s'])) exit('Error');

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
	$search = $api->decode($api->searchApp($_GET['s']));
	
	if (!isset($search->data->list[0]))
		exit("No results found :(");
	
	// HTML Tags
	echo '
	<html>
		<head>
			<style>
				.info { text-align: right; font-weight: bold; }
				.img { max-height: 110px; }
			</style>
		</head>
		<body>
	';
	
	// Apps
	for ($i = 0; $i < sizeof($search->data->list); $i++)
	{
		echo "
			<div id='app'>
				<div style='float:left'>
					<img class='img' src=".$search->data->list[$i]->icon." />
				</div>
				<div>
					<table> 
						<tr>
							<td class='info'>Name:</td>
							<td>".$search->data->list[$i]->name."</td>
						</tr>
						<tr>
							<td class='info'>appid:</td>
							<td>".$search->data->list[$i]->appid."</td>
						</tr>
						<tr>
							<td class='info'>Seller:</td>
							<td>".$search->data->list[$i]->seller."</td>
						</tr>
						<tr>
							<td class='info'>Rating:</td>
							<td>".$search->data->list[$i]->rating."</td>
						</tr>
						<tr><td class='info'><a href='app.php?appid=".$search->data->list[$i]->appid."'>Go</a></td></tr>
					</table>
				</div>
			</div>
		";
	}
	
	// HTML Tags
	echo '
		</body>
	</html>
	';
} catch (Exception $e) {
	echo 'Error: ', $api->ErrorInfo;
}