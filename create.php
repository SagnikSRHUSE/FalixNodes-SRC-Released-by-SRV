<?php
session_start();

include("global.php");

include("config.php");

include("plans.php");

if( checklogin() == false ) {

	notloggedin("You must be logged in.");

}

$user = $_SESSION['discord_user'];

$getPanelIDofUser = $conn->query("SELECT * FROM users WHERE discord_id='" . mysqli_real_escape_string($conn, $user->id) . "'")->fetch_assoc()['pterodactyl_userid'];

ShowError("Server creations has been stopped for some time. Please check back later!");

if( isset($_POST['submit']) ) {

	if( empty($_POST['server_name']) || empty($_POST['server_ram']) || empty($_POST['location']) || empty($_POST['servertype']) || empty($_POST['cpu']) || !isset($_POST['server_name']) || !isset($_POST['server_ram']) || !isset($_POST['location']) || !isset($_POST['servertype']) || !isset($_POST['cpu']) ) {

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

	

	if( $_POST['server_ram'] < 256 ) {

		ShowError("The minimum memory is 256 MB.");

	}

	

	//Check if user reached max servers or max CPU cores

	$currentUserServersAmount = $conn->query("SELECT * FROM servers WHERE owner_id='" . mysqli_real_escape_string($conn, $user->id) . "'")->num_rows;

	if( $currentUserServersAmount >= ($maxservers + $user_extra_servers) ) {

		ShowError("Sorry, you have reached your maximum servers amount. (" . $maxservers . " servers is your maximum amount)");

	}

	if( $_POST['cpu'] > ($max_cores + $user_extra_cores) ) {

		ShowError("Sorry, you have reached your maximum CPU cores. (" . ($max_cores + $user_extra_cores) . " is your maximum CPU cores)");

	}

	

	//Check if user exceeded his max RAM balance

	$currentUsedBalance = 0 + $_POST['server_ram'];

	$userServers = $conn->query("SELECT * FROM servers WHERE owner_id='" . mysqli_real_escape_string($conn, $user->id) . "'");

	if($userServers->num_rows > 0) {

		while($row = $userServers->fetch_assoc()) {

			$ch = curl_init("https://" . $ptero_domain . "/api/application/servers/" . $row['pterodactyl_serverid']);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			curl_setopt($ch, CURLOPT_HTTPHEADER, array(

				"Authorization: Bearer " . $ptero_key,

				"Content-Type: application/json",

				"Accept: Application/vnd.pterodactyl.v1+json"

			));

			$result = curl_exec($ch);

			curl_close($ch);

			$currentUsedBalance = $currentUsedBalance + json_decode($result, true)['attributes']['limits']['memory'];

		}

	}

	if( $currentUsedBalance > ($rambalance + $user_extra_ram) ) {

		ShowError("Sorry, you have reached your max RAM balance. (" . ($rambalance + $user_extra_ram) . " MB is your balance)");

	}

	

	// Location checker

	if( $GET_USER_LEVEL == 0 ) {

		// this user is free

		$allowed_locations = array(1, 2);

	} else {

		// this user is donator

		$allowed_locations = array(1, 2);

	}

	if( !in_array($_POST['location'], $allowed_locations) ) {

		ShowError("You don't have permissions to create a server in this location.");

	}

	

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

			"swap" => $_POST['server_ram'],

			"disk" => 5000,

			"io" => 500,

			"cpu" => intval($_POST['cpu']) * 100

		),

		"feature_limits" => array(

			"databases" => 1,

			"allocations" => 0

		),

		"environment" => array(

			"DL_VERSION" => "latest",
			
			"VANILLA_VERSION" => "latest",

			"SERVER_JARFILE" => "server.jar",

			"BUILD_NUMBER" => "latest",

            "STARTUP_CMD" => "CHANGE_IT_TO_YOUR_OWN",
            
			"BUNGEE_VERSION" => "latest",
			
			"PMMP_VERSION" => "latest",
			
			"NUKKIT_VERSION" => "latest",
			
			"MC_VERSION" => "latest",
			
			"BEDROCK_VERSION" => "latest",
			
			"LD_LIBRARY_PATH" => ".",
			
			"WATERFALL_VERSION" => "latest",
			
			"VANILLA_VERSION" => "latest",
			
			"DL_VERSION" => "latest",
			
			"DL_PATH" => ".",
			
			"BEDROCK_VERSION" => "latest",
			
			"MODPACK_VERSION" => "1.1.1",
			
			"MODPACK_VERSIONHEXXIT" => "1.0.10",
			
			"MODPACK_VERSIONBLIGHTFALL" => "2.1.5",
			
			"MODPACK_VERSIONATTACK" => "1.0.12c",
			
			"TMODLOADER_VERSION" => "v0.10.1.5",
			
			"TERRARIA_VERSION" => "1.3.5.3",
			
			"MAX_PLAYERS" => "8",
			
			"WORLD_SIZE" => "1",
			
			"Tshock Version" => "4.3.25",
			
			"WORLD_NAME" => "world",
			
			"STEAM_USER" => "anonymous",
			
			

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

		ShowError("An error has occured while creating your server. If this error still exists for long time, please contact support.");

	}

	

	//add server to database

	$conn->query("INSERT INTO servers (pterodactyl_serverid, owner_id) VALUES (" . $serverinfo['attributes']['id'] . ", '" . mysqli_real_escape_string($conn, $user->id) . "')");

	

	//redirect to homepage with success message

	ShowSuccess("Created server!");

}





// Get user's RAM balance

$currentUsedBalance = 0;

$userServers = $conn->query("SELECT * FROM servers WHERE owner_id='" . mysqli_real_escape_string($conn, $user->id) . "'");

if($userServers->num_rows > 0) {

	while($row = $userServers->fetch_assoc()) {

		$ch = curl_init("https://" . $ptero_domain . "/api/application/servers/" . $row['pterodactyl_serverid']);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(

			"Authorization: Bearer " . $ptero_key,

			"Content-Type: application/json",

			"Accept: Application/vnd.pterodactyl.v1+json"

		));

		$result = curl_exec($ch);

		curl_close($ch);

		$currentUsedBalance = $currentUsedBalance + json_decode($result, true)['attributes']['limits']['memory'];

	}

}

?>

<strong>Your used RAM balance is:</strong> <?php echo $currentUsedBalance; ?> MB / <?php echo ($rambalance + $user_extra_ram); ?> MB

<form action="create" method="post">

	Server Name: <input type="text" name="server_name" class="form-control" required><br />

	Server RAM (in MB): <input type="number" name="server_ram" class="form-control" min="256" value="1024" required><br />

	CPU Cores: <input <input type="number" name="cpu" class="form-control" min="1" value="1" required><br />

	Location:
	
					<select name="location" class="form-control">
                <option name="location" value="1" selected="selected">Free (Finland)</option>
					</select>

	<br />

	Server Type:

		<div id="ServerType">
			
				<select name="servertype" class="form-control">
			    <option value="" disabled selected>Choose The Server Type</option>
			    <option value="" disabled>Minecraft Java Edition</option>
                <option name="servertype" value="23">Vanilla</option>
                <option name="servertype" value="3">Spigot (PaperSpigot)</option>
                <option name="servertype" value="1">BungeeCord (Waterfall)</option>
                <option name="servertype" value="21">Forge</option>
			    <option value="" disabled>Minecraft Bedrock Edition</option>
                <option name="servertype" value="24">Vanilla</option>
                <option name="servertype" value="16">Nukkit</option>
                <option name="servertype" value="19">PocketMineMP</option>
			    <option value="" disabled>Discord Bot</option>
                <option name="servertype" value="18">NodeJS 10 / Python 3.6</option>
					</select>
				<br />
					
						<br /><br />
				

	<input type="submit" name="submit" value="Create!" class="btn btn-success">

</form>