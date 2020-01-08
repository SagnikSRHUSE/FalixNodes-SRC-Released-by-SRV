<?php
//die("Maintenance");
session_start();

include("global.php");

include("config.php");

require_once("vendor/autoload.php"); //plesk API

if( checklogin() == true ) {

	$user = $_SESSION['discord_user'];

} else {

	notloggedin("You must be logged in.");

}



if( !isset($_GET['id']) || empty($_GET['id']) ) {

	header("Location: /");

	die();

}



// Check if user have permissions for the site ID, and if the site exists

$checkperms = $conn->query("SELECT * FROM sites WHERE owner_id='" . mysqli_real_escape_string($conn, $user->id) . "' AND id=" . mysqli_real_escape_string($conn, $_GET['id']));

if( $checkperms->num_rows == 0 ) {

	ShowError("You don't have permissions to delete this site or this site doesn't exists.");

}



// Delete the site now

$plesk_user = "admin";
$plesk_pass = "Jh7DksyP2uKZMk";
$plesk_ip = "95.216.113.237";
$plesk_hostname = "webhost01.falixnodes.host";
// start plesk session and authenticate
$client = new \PleskX\Api\Client($plesk_ip);
$client->setCredentials($plesk_user, $plesk_pass);

$customerid = $conn->query("SELECT * FROM sites WHERE id=" . mysqli_real_escape_string($conn, $_GET['id']))->fetch_assoc()['plesk_customerid'];
$packet = '
<packet version="1.6.3.0">
<customer>
   <del>
      <filter>
          <id>' . $customerid . '</id>
      </filter>
   </del>
</customer>
</packet>
';

// Redirect user to homepage with success message

try {
	$response = $client->request($packet);
} catch (\Throwable $e) {
	ShowError("An error has occured while deleting your site.");
}
if( strval($response->status) !== "ok" ) {
	ShowError("An error has occured while deleting your site.");
}
$conn->query("DELETE FROM sites WHERE id=" . mysqli_real_escape_string($conn, $_GET['id']));
ShowSuccess("Deleted site!");

?>