<?php
//session_start();
//include("global.php");
//include("config.php");
if( checklogin() == true ) {
	$user = $_SESSION['discord_user'];
	$user_guilds = $_SESSION['discord_user_guilds'];
	$joined_servers_value = $conn->query("SELECT * FROM users WHERE discord_id='" . mysqli_real_escape_string($conn, $user->id) . "'")->fetch_assoc()['joined_servers'];
	$joined_servers = explode(",", $joined_servers_value);
	$user_discord_servers = array();
	foreach($user_guilds as $user_guild) {
		if( !empty($user_guild->id) ) {
			array_push($user_discord_servers, $user_guild->id);
		}
	}
	
	$sql = "SELECT * FROM join_for_resources_servers";
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc()) {
		$extra_ram = $row['extra_ram'];
		$extra_servers = $row['extra_servers'];
		$extra_sites = $row['extra_servers'];
		$extra_cores = $row['extra_cores'];
		$extra_disk = $row['extra_disk'];
		if( !in_array($row['server_id'], $joined_servers) && in_array($row['server_id'], $user_discord_servers) ) {
			$conn->query("UPDATE users SET extra_ram = extra_ram + " . mysqli_real_escape_string($conn, $extra_ram) . " WHERE discord_id='" . mysqli_real_escape_string($conn, $user->id) . "'");
			$conn->query("UPDATE users SET extra_servers = extra_servers + " . mysqli_real_escape_string($conn, $extra_servers) . " WHERE discord_id='" . mysqli_real_escape_string($conn, $user->id) . "'");
			$conn->query("UPDATE users SET extra_sites = extra_sites + " . mysqli_real_escape_string($conn, $extra_sites) . " WHERE discord_id='" . mysqli_real_escape_string($conn, $user->id) . "'");
			$conn->query("UPDATE users SET extra_cores = extra_cores + " . mysqli_real_escape_string($conn, $extra_cores) . " WHERE discord_id='" . mysqli_real_escape_string($conn, $user->id) . "'");
			$conn->query("UPDATE users SET extra_disk = extra_disk + " . mysqli_real_escape_string($conn, $extra_disk) . " WHERE discord_id='" . mysqli_real_escape_string($conn, $user->id) . "'");
			$joined_servers_value = $conn->query("SELECT * FROM users WHERE discord_id='" . mysqli_real_escape_string($conn, $user->id) . "'")->fetch_assoc()['joined_servers'];
			$new_joined_servers_value = $joined_servers_value . $row['server_id'] . ",";
			$conn->query("UPDATE users SET joined_servers='" . mysqli_real_escape_string($conn, $new_joined_servers_value) . "' WHERE discord_id='" . mysqli_real_escape_string($conn, $user->id) . "'");
		}
		if( in_array($row['server_id'], $joined_servers) && !in_array($row['server_id'], $user_discord_servers) ) {
			$conn->query("UPDATE users SET extra_ram = extra_ram - " . mysqli_real_escape_string($conn, $extra_ram) . " WHERE discord_id='" . mysqli_real_escape_string($conn, $user->id) . "'");
			$conn->query("UPDATE users SET extra_servers = extra_servers - " . mysqli_real_escape_string($conn, $extra_servers) . " WHERE discord_id='" . mysqli_real_escape_string($conn, $user->id) . "'");
			$conn->query("UPDATE users SET extra_sites = extra_sites - " . mysqli_real_escape_string($conn, $extra_sites) . " WHERE discord_id='" . mysqli_real_escape_string($conn, $user->id) . "'");
			$conn->query("UPDATE users SET extra_cores = extra_cores - " . mysqli_real_escape_string($conn, $extra_cores) . " WHERE discord_id='" . mysqli_real_escape_string($conn, $user->id) . "'");
			$conn->query("UPDATE users SET extra_disk = extra_disk - " . mysqli_real_escape_string($conn, $extra_disk) . " WHERE discord_id='" . mysqli_real_escape_string($conn, $user->id) . "'");
			$joined_servers_value = $conn->query("SELECT * FROM users WHERE discord_id='" . mysqli_real_escape_string($conn, $user->id) . "'")->fetch_assoc()['joined_servers'];
			$new_joined_servers_value = str_replace($row['server_id'] . ",", "", $joined_servers_value);
			$conn->query("UPDATE users SET joined_servers='" . mysqli_real_escape_string($conn, $new_joined_servers_value) . "' WHERE discord_id='" . mysqli_real_escape_string($conn, $user->id) . "'");
		}
    }
}
?>