<?php
error_reporting(0);
set_time_limit(0);

$base = dirname(dirname(__FILE__));
include($base . "/config.php");
include($base . "/global.php");

$ch = curl_init("https://" . $ptero_domain . "/api/application/servers");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"Authorization: Bearer " . $ptero_key,
	"Content-Type: application/json",
	"Accept: Application/vnd.pterodactyl.v1+json"
));
$result = curl_exec($ch);
curl_close($ch);
$result = json_decode($result, true);
$total_pages = intval($result['meta']['pagination']['total_pages']);

foreach(range(1,$total_pages) as $index) {
	$ch = curl_init("https://" . $ptero_domain . "/api/application/servers?page=" . $index);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Authorization: Bearer " . $ptero_key,
		"Content-Type: application/json",
		"Accept: Application/vnd.pterodactyl.v1+json"
	));
	$result = curl_exec($ch);
	curl_close($ch);
	$result = json_decode($result, true);
	foreach($result['data'] as $server) {
		$server_shortid = $server['attributes']['id'];
		$ch = curl_init("https://" . $ptero_domain . "/api/client/servers/" . $server['attributes']['identifier'] . "/utilization");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Authorization: Bearer " . $ptero_client_key,
			"Content-Type: application/json",
			"Accept: Application/vnd.pterodactyl.v1+json"
		));
		$result = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($result, true);
		if( $result['attributes']['state'] == "on" ) {
			if( intval($result['attributes']['cpu']['current']) > 80 ) {
				// Suspend server
				$ch = curl_init("https://" . $ptero_domain . "/api/application/servers/" . $server_shortid . "/suspend");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					"Authorization: Bearer " . $ptero_key,
					"Content-Type: application/json",
					"Accept: Application/vnd.pterodactyl.v1+json"
				));
				$result = curl_exec($ch);
				curl_close($ch);
			}
		}
	}
}
?>