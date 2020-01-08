<?php
session_start();
include("global.php");
include("config.php");

header('Content-Type: application/json');
if( checklogin() == true ) {
	$user = $_SESSION['discord_user'];
	$pterodactyl_panelinfo = $conn->query("SELECT * FROM users WHERE discord_id='" . mysqli_real_escape_string($conn, $user->id) . "'")->fetch_assoc();
	$pterodactyl_username = $pterodactyl_panelinfo['pterodactyl_username'];
	$pterodactyl_password = $pterodactyl_panelinfo['pterodactyl_password'];
} else {
	echo json_encode(array(
		"error" => true,
		"error_http" => 403
	));
	die();
}
include("plans.php");

if( isset($_GET['server_info']) ) {
	if( !isset($_GET['sid']) ) {
		echo json_encode(array(
			"error" => true,
			"error_msg" => "`sid` is required"
		));
		die();
	}
	// Check if the user owns the server and if the server exists
	if( $conn->query("SELECT * FROM servers WHERE pterodactyl_serverid = " . mysqli_real_escape_string($conn, $_GET['sid']))->num_rows == 0 ) {
		echo json_encode(array(
			"error" => true,
			"error_http" => 403
		));
		die();
	}
	// Get server identifier
	$ch = curl_init("https://" . $ptero_domain . "/api/application/servers/" . $_GET['sid']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Authorization: Bearer " . $ptero_key,
		"Content-Type: application/json",
		"Accept: Application/vnd.pterodactyl.v1+json"
	));
	$result = curl_exec($ch);
	curl_close($ch);
	$server_identifier = json_decode($result, true)['attributes']['identifier'];
	// Get server information
	$ch = curl_init("https://" . $ptero_domain . "/api/client/servers/" . $server_identifier . "/utilization");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Authorization: Bearer " . $ptero_client_key,
		"Content-Type: application/json",
		"Accept: Application/vnd.pterodactyl.v1+json"
	));
	$result = curl_exec($ch);
	curl_close($ch);
	$result = json_decode($result, true);
	// Return the result
	echo json_encode(array(
		"server_state" => $result['attributes']['state'], // on, off
		"memory_used" => $result['attributes']['memory']['current'], // in MB
		"cpu_used" => $result['attributes']['cpu']['current'], // percentage
		"disk_used" => $result['attributes']['disk']['current'] // in MB
	));
}
?>