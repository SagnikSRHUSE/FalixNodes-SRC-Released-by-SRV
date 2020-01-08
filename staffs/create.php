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
if( isset($_POST['submit']) ) {
	if( empty($_POST['server_name']) || empty($_POST['server_ram']) || empty($_POST['location']) || empty($_POST['servertype']) || empty($_POST['cpu']) || empty($_POST['ownerid']) || !isset($_POST['server_name']) || !isset($_POST['server_ram']) || !isset($_POST['location']) || !isset($_POST['servertype']) || !isset($_POST['cpu']) || !isset($_POST['ownerid']) ) {
		ShowError("All the fields are required.");
	}
	if(is_numeric($_POST['server_ram']) && $_POST['server_ram'] > 0 && $_POST['server_ram'] == round($_POST['server_ram'], 0)){
		//pass
	} else {
		ShowError("RAM must be a number.");
	}
	if(is_numeric($_POST['cpu']) && $_POST['cpu'] > 0 && $_POST['cpu'] == round($_POST['cpu'], 0)){
		//pass
	} else {
		ShowError("CPU must be a number.");
	}
	
	if( $_POST['server_ram'] < 128 ) {
		ShowError("The minimum memory is 128 MB.");
	}
	
	if( $conn->query("SELECT * FROM users WHERE discord_id='" . mysqli_real_escape_string($conn, $_POST['ownerid']) . "'")->num_rows == 0 ) {
		ShowError("The specified owner ID doesn't exists.");
	}
	
	$getPanelIDofUser = $conn->query("SELECT * FROM users WHERE discord_id='" . mysqli_real_escape_string($conn, $_POST['ownerid']) . "'")->fetch_assoc()['pterodactyl_userid'];
	
	// get some info for the specificied egg .. those are needed to create server
	$ch = curl_init("https://" . $ptero_domain . "/api/application/nests/1/eggs/" . intval($_POST['servertype']));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Authorization: Bearer " . $ptero_key,
		"Content-Type: application/json",
		"Accept: Application/vnd.pterodactyl.v1+json"
	));
	$result = curl_exec($ch);
	curl_close($ch);
	$result_jdecoded = json_decode($result, true);
	$docker_image = $result_jdecoded['attributes']['docker_image'];
	$startup_info = $result_jdecoded['attributes']['startup'];
	
	$ch = curl_init("https://" . $ptero_domain . "/api/application/servers");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Authorization: Bearer " . $ptero_key,
		"Content-Type: application/json",
		"Accept: Application/vnd.pterodactyl.v1+json"
	));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
		"name" => $_POST['server_name'],
		"user" => intval($getPanelIDofUser),
		"nest" => 1,
		"egg" => intval($_POST['servertype']),
		"docker_image" => $docker_image,
		"startup" => $startup_info,
		"limits" => array(
			"memory" => $_POST['server_ram'],
			"swap" => 0,
			"disk" => 5000,
			"io" => 500,
			"cpu" => intval($_POST['cpu']) * 100
		),
		"feature_limits" => array(
			"databases" => 0,
			"allocations" => 0
		),
		"environment" => array(
			"DL_VERSION" => "latest",
			"SERVER_JARFILE" => "server.jar",
			"BUILD_NUMBER" => "latest",
			"BUNGEE_VERSION" => "latest",
			"WATERFALL_VERSION" => "latest",
			"NUKKIT_VERSION" => "latest",
			"PMMP_VERSION" => "latest",
			"BEDROCK_VERSION" => "latest",
			"LD_LIBRARY_PATH" => "."
		),
		"deploy" => array(
			"locations" => [intval($_POST['location'])],
			"dedicated_ip" => false,
			"port_range" => []
		),
		"start_on_completion" => false
	)));
	$result = curl_exec($ch);
	curl_close($ch);
	$serverinfo = json_decode($result, true);
	
	if( $serverinfo['object'] !== "server" ) {
		ShowError("An error has occured while creating this server. If this error still exists for long time, please contact support. ugh idk.. this message shouldnt appear for the support already");
	}
	
	//add server to database
	$conn->query("INSERT INTO servers (pterodactyl_serverid, owner_id) VALUES (" . mysqli_real_escape_string($conn, $serverinfo['attributes']['id']) . ", '" . mysqli_real_escape_string($conn, $_POST['ownerid']) . "')");
	
	// Log the action
	$log_staff_id = $user['id'];
	$log_message = "Created server with owner ID " . $_POST['ownerid'];
	$log_time = time();
	$conn->query("INSERT INTO staffs_logs (staff_id, log_message, time) VALUES (" . mysqli_real_escape_string($conn, $log_staff_id) . ", '" . mysqli_real_escape_string($conn, $log_message) . "', '" . mysqli_real_escape_string($conn, $log_time) . "')");
	
	//redirect to homepage with success message
	ShowSuccess("Created server!");
}
?>
<form action="create" method="post">
	Server Name: <input type="text" name="server_name" class="form-control" required><br />
	Server RAM (in MB): <input type="number" name="server_ram" class="form-control" min="128" value="128" required><br />
	CPU Cores: <input <input type="number" name="cpu" class="form-control" min="1" value="1" required><br />
	Owner's Discord ID: <input type="text" name="ownerid" class="form-control" required><br />
	Location:

		<input type="radio" name="location" value="1"> Free (Finland)

	<br /><br />

	Server Type:

		<div id="ServerType">
		
			<div class="card" style="width: 27rem;">

				  <div class="card-body">

					<h5 class="card-title">Minecraft Java</h5>

						<input type="radio" name="servertype" value="3"> Spigot
						

				  </div>

			</div>
		
		
			<div class="card" style="width: 27rem;">

				  <div class="card-body">

					<h5 class="card-title">Minecraft Bedrock</h5>

						<input type="radio" name="servertype" value="16"> Nukkit
						
						<input type="radio" name="servertype" value="19"> PMMP

				  </div>

			</div>
			
			<div class="card" style="width: 27rem;">

				  <div class="card-body">

					<h5 class="card-title">Discord Bot</h5>

						<input type="radio" name="servertype" value="18"> NodeJS 10 / Python 3.6

				  </div>

			</div>
		
		</div>
		
	<br />
	<input type="submit" name="submit" value="Create!" class="btn btn-success">
</form>