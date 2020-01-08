<?php
session_start();
include("global.php");
include("../config.php");
if( checklogin() == true ) {
	$user = $conn->query("SELECT * FROM staffs WHERE id=" . mysqli_real_escape_string($conn, $_SESSION['user']))->fetch_assoc();
} else {
	header("Location: login");
	die();
}

if( !isset($_GET['id']) || empty($_GET['id']) || !isset($_GET['newram']) || empty($_GET['newram']) ) {
	header("Location: /");
	die();
}

if(is_numeric($_GET['newram']) && $_GET['newram'] > 0 && $_GET['newram'] == round($_GET['newram'], 0)){
	//pass
} else {
	ShowError("RAM must be a number.");
}

// Get some server information, those are needed to update RAM
$ch = curl_init("https://" . $ptero_domain . "/api/application/servers/" . $_GET['id']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"Authorization: Bearer " . $ptero_key,
	"Content-Type: application/json",
	"Accept: Application/vnd.pterodactyl.v1+json"
));
$result = curl_exec($ch);
curl_close($ch);
$ServerAllocation = json_decode($result, true)['attributes']['allocation'];
$x_ServerDisk = json_decode($result, true)['attributes']['limits']['disk'];
$x_ServerCpu = json_decode($result, true)['attributes']['limits']['cpu'];
$x_ServerIO = json_decode($result, true)['attributes']['limits']['io'];
$x_ServerDbs = json_decode($result, true)['attributes']['feature_limits']['databases'];
$x_ServerAllocations = json_decode($result, true)['attributes']['feature_limits']['allocations'];

// Update server RAM now
$ch = curl_init("https://" . $ptero_domain . "/api/application/servers/" . $_GET['id'] . "/build");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
	"oom_disabled" => false,
	"allocation" => $ServerAllocation,
	"memory" => intval($_GET['newram']),
	"swap" => 0,
	"disk" => $x_ServerDisk,
	"io" => $x_ServerIO,
	"cpu" => $x_ServerCpu,
	"feature_limits" => array(
		"databases" => $x_ServerDbs,
		"allocations" => $x_ServerAllocations
	)
)));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"Authorization: Bearer " . $ptero_key,
	"Content-Type: application/json",
	"Accept: Application/vnd.pterodactyl.v1+json"
));
$result = curl_exec($ch);
curl_close($ch);

$server_owner_id = $conn->query("SELECT * FROM servers WHERE pterodactyl_serverid = " . mysqli_real_escape_string($conn, $_GET['id']))->fetch_assoc()['owner_id'];
// Log the action
$log_staff_id = $user['id'];
$log_message = "Changed RAM of server #" . $_GET['id'] . " owned by " . $server_owner_id . " to " . $_GET['newram'] . " MB";
$log_time = time();
$conn->query("INSERT INTO staffs_logs (staff_id, log_message, time) VALUES (" . mysqli_real_escape_string($conn, $log_staff_id) . ", '" . mysqli_real_escape_string($conn, $log_message) . "', '" . mysqli_real_escape_string($conn, $log_time) . "')");

// Redirect user to homepage with success message
ShowSuccess("Changed RAM!");
?>