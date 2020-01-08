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

if( !isset($_GET['id']) || empty($_GET['id']) ) {
	header("Location: /");
	die();
}

// Delete the server now
$ch = curl_init("https://" . $ptero_domain . "/api/application/servers/" . $_GET['id'] . "/force");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
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
$log_message = "Deleted server #" . $_GET['id'] . " owned by " . $server_owner_id;
$log_time = time();
$conn->query("INSERT INTO staffs_logs (staff_id, log_message, time) VALUES (" . mysqli_real_escape_string($conn, $log_staff_id) . ", '" . mysqli_real_escape_string($conn, $log_message) . "', '" . mysqli_real_escape_string($conn, $log_time) . "')");

if( empty($result) ) {
	// Delete server from database
	$conn->query("DELETE FROM servers WHERE pterodactyl_serverid=" . mysqli_real_escape_string($conn, $_GET['id']));
	ShowSuccess("Deleted server!");
} else {
	ShowError("An error had occured while deleting this server.");
}
?>