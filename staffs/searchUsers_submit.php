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

if( !isset($_POST['discord_id']) || empty($_POST['discord_id']) ) {
	ShowError("The Discord ID field is required.");
}

if( isset($_POST['ChangeExtraRam']) ) {
	$conn->query("UPDATE users SET extra_ram=" . mysqli_real_escape_string($conn, $_POST['new_extra_ram']) . " WHERE discord_id='" . mysqli_real_escape_string($conn, $_POST['discord_id']) . "'");
	// Log the action
	$log_staff_id = $user['id'];
	$log_message = "Changed extra RAM of user with ID " . $_POST['discord_id'] . " to " . $_POST['new_extra_ram'] . " MB";
	$log_time = time();
	$conn->query("INSERT INTO staffs_logs (staff_id, log_message, time) VALUES (" . mysqli_real_escape_string($conn, $log_staff_id) . ", '" . mysqli_real_escape_string($conn, $log_message) . "', '" . mysqli_real_escape_string($conn, $log_time) . "')");
	ShowSuccess("Done action!");
}

if( isset($_POST['ChangeExtraDisk']) ) {
	$conn->query("UPDATE users SET extra_disk=" . mysqli_real_escape_string($conn, $_POST['new_extra_disk']) . " WHERE discord_id='" . mysqli_real_escape_string($conn, $_POST['discord_id']) . "'");
	// Log the action
	$log_staff_id = $user['id'];
	$log_message = "Changed extra disk of user with ID " . $_POST['discord_id'] . " to " . $_POST['new_extra_disk'] . " MB";
	$log_time = time();
	$conn->query("INSERT INTO staffs_logs (staff_id, log_message, time) VALUES (" . mysqli_real_escape_string($conn, $log_staff_id) . ", '" . mysqli_real_escape_string($conn, $log_message) . "', '" . mysqli_real_escape_string($conn, $log_time) . "')");
	ShowSuccess("Done action!");
}

if( isset($_POST['ChangeExtraCores']) ) {
	$conn->query("UPDATE users SET extra_cores=" . mysqli_real_escape_string($conn, $_POST['new_extra_cores']) . " WHERE discord_id='" . mysqli_real_escape_string($conn, $_POST['discord_id']) . "'");
	// Log the action
	$log_staff_id = $user['id'];
	$log_message = "Changed extra cores of user with ID " . $_POST['discord_id'] . " to " . $_POST['new_extra_cores'];
	$log_time = time();
	$conn->query("INSERT INTO staffs_logs (staff_id, log_message, time) VALUES (" . mysqli_real_escape_string($conn, $log_staff_id) . ", '" . mysqli_real_escape_string($conn, $log_message) . "', '" . mysqli_real_escape_string($conn, $log_time) . "')");
	ShowSuccess("Done action!");
}

if( isset($_POST['ChangeExtraServers']) ) {
	$conn->query("UPDATE users SET extra_servers=" . mysqli_real_escape_string($conn, $_POST['new_extra_servers']) . " WHERE discord_id='" . mysqli_real_escape_string($conn, $_POST['discord_id']) . "'");
	// Log the action
	$log_staff_id = $user['id'];
	$log_message = "Changed extra servers of user with ID " . $_POST['discord_id'] . " to " . $_POST['new_extra_servers'];
	$log_time = time();
	$conn->query("INSERT INTO staffs_logs (staff_id, log_message, time) VALUES (" . mysqli_real_escape_string($conn, $log_staff_id) . ", '" . mysqli_real_escape_string($conn, $log_message) . "', '" . mysqli_real_escape_string($conn, $log_time) . "')");
	ShowSuccess("Done action!");
}

if( isset($_POST['ChangeExtraSites']) ) {
	$conn->query("UPDATE users SET extra_sites=" . mysqli_real_escape_string($conn, $_POST['new_extra_sites']) . " WHERE discord_id='" . mysqli_real_escape_string($conn, $_POST['discord_id']) . "'");
	// Log the action
	$log_staff_id = $user['id'];
	$log_message = "Changed extra sites of user with ID " . $_POST['discord_id'] . " to " . $_POST['new_extra_sites'];
	$log_time = time();
	$conn->query("INSERT INTO staffs_logs (staff_id, log_message, time) VALUES (" . mysqli_real_escape_string($conn, $log_staff_id) . ", '" . mysqli_real_escape_string($conn, $log_message) . "', '" . mysqli_real_escape_string($conn, $log_time) . "')");
	ShowSuccess("Done action!");
}

if( isset($_POST['ChangeLevelId']) ) {
	$conn->query("UPDATE users SET level=" . mysqli_real_escape_string($conn, $_POST['new_level']) . " WHERE discord_id='" . mysqli_real_escape_string($conn, $_POST['discord_id']) . "'");
	// Log the action
	$log_staff_id = $user['id'];
	$log_message = "Changed level of user with ID " . $_POST['discord_id'] . " to " . $_POST['new_level'];
	$log_time = time();
	$conn->query("INSERT INTO staffs_logs (staff_id, log_message, time) VALUES (" . mysqli_real_escape_string($conn, $log_staff_id) . ", '" . mysqli_real_escape_string($conn, $log_message) . "', '" . mysqli_real_escape_string($conn, $log_time) . "')");
	ShowSuccess("Done action!");
}

if( isset($_POST['ChangePanelPass']) ) {
	$UserPanelId = $conn->query("SELECT * FROM users WHERE discord_id='" . mysqli_real_escape_string($conn, $_POST['discord_id']) . "'")->fetch_assoc()['pterodactyl_userid'];
	// Get some server information, those are needed to change panel password
	$ch = curl_init("https://" . $ptero_domain . "/api/application/users/" . $UserPanelId);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Authorization: Bearer " . $ptero_key,
		"Content-Type: application/json",
		"Accept: Application/vnd.pterodactyl.v1+json"
	));
	$result = curl_exec($ch);
	curl_close($ch);
	$UserUsername = json_decode($result, true)['attributes']['username'];
	$UserEmail = json_decode($result, true)['attributes']['email'];
	$UserFname = json_decode($result, true)['attributes']['first_name'];
	$UserLname = json_decode($result, true)['attributes']['last_name'];
	// Update user's panel password now
	$ch = curl_init("https://" . $ptero_domain . "/api/application/users/" . $UserPanelId);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Authorization: Bearer " . $ptero_key,
		"Content-Type: application/json",
		"Accept: Application/vnd.pterodactyl.v1+json"
	));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
		"username" => $UserUsername,
		"email" => $UserEmail,
		"first_name" => $UserFname,
		"last_name" => $UserLname,
		"password" => $_POST['new_password']
	)));
	$result = curl_exec($ch);
	curl_close($ch);
	
	// Log the action
	$log_staff_id = $user['id'];
	$log_message = "Changed panel password of user with ID " . $_POST['discord_id'];
	$log_time = time();
	$conn->query("INSERT INTO staffs_logs (staff_id, log_message, time) VALUES (" . mysqli_real_escape_string($conn, $log_staff_id) . ", '" . mysqli_real_escape_string($conn, $log_message) . "', '" . mysqli_real_escape_string($conn, $log_time) . "')");
	
	$conn->query("UPDATE users SET pterodactyl_password=" . mysqli_real_escape_string($conn, $_POST['new_password']) . " WHERE discord_id='" . mysqli_real_escape_string($conn, $_POST['discord_id']) . "'");
	ShowSuccess("Done action!");
}
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	  
	  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	  
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <title>FalixNodes | Free Minecraft Server Hosting</title>
  </head>
  <body>
	  
	  <center>
	
			<div class="jumbotron">
			  <h1 class="display-4">FalixNodes</h1>
				<hr class="my-4">
			  <p class="lead">Staffs Panel</p>
				<?php
				echo '<hr class="my-4">Welcome ' . $user['username'] . '!<br /><a class="btn btn-primary btn-lg" href="logout" role="button"><i class="fas fa-sign-out-alt"></i> Logout</a><br /><br />';
					if( isset($_GET['success']) ) {
						echo '
						<div class="alert alert-success" role="alert">
						  <strong>Success!</strong> ' . base64_decode($_GET['success']) . '
						</div>
						';
					}
					if( isset($_GET['error']) ) {
						echo '
						<div class="alert alert-danger" role="alert">
						  <strong>Error!</strong> ' . base64_decode($_GET['error']) . '
						</div>
						';
					}
				?>
			</div>
		  
	  <div class="container">
		  <a href="index" class="btn btn-primary" role="button">Home</a>&nbsp;
		  <a href="searchUsers" class="btn btn-primary" role="button">Search Users</a>
		  <a href="searchServers" class="btn btn-primary" role="button">Search Servers</a>
		  <a href="levels" class="btn btn-primary" role="button">Levels</a>
		  <a href="logs" class="btn btn-primary" role="button">Logs</a>
		  <br /><br />
		  <?php
		  $userSearch = $conn->query("SELECT * FROM users WHERE discord_id='" . mysqli_real_escape_string($conn, $_POST['discord_id']) . "'")->fetch_assoc();
		  $ch = curl_init("https://" . $ptero_domain . "/api/application/users/" . $userSearch['pterodactyl_userid']);
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Authorization: Bearer " . $ptero_key,
			"Content-Type: application/json",
			"Accept: Application/vnd.pterodactyl.v1+json"
		  ));
		  $result = curl_exec($ch);
		  curl_close($ch);
		  $UserUsername = json_decode($result, true)['attributes']['username'];
		  $UserEmail = json_decode($result, true)['attributes']['email'];
		  echo '
		  <strong>Discord ID:</strong> ' . $userSearch['discord_id'] . '<br />
		  <strong>Discord Email:</strong> ' . $userSearch['discord_email'] . '<br />
		  <strong>Discord Username:</strong> ' . DiscordIdToUsername($discord['autojoin_guildid'], $userSearch['discord_id'], $discord['bot_token']) . '<br />
		  <strong>Plan Level ID:</strong> ' . $userSearch['level'] . '<br />
		  <strong>Extra RAM (in MB):</strong> ' . $userSearch['extra_ram'] . '<br />
		  <strong>Extra disk (in MB):</strong> ' . $userSearch['extra_disk'] . '<br />
		  <strong>Extra cores:</strong> ' . $userSearch['extra_cores'] . '<br />
		  <strong>Extra servers:</strong> ' . $userSearch['extra_servers'] . '<br />
		  <strong>Extra sites:</strong> ' . $userSearch['extra_sites'] . '<br />
		  <strong>Pterodactyl User ID:</strong> ' . $userSearch['pterodactyl_userid'] . '<br />
		  <strong>Pterodactyl Username:</strong> ' . $UserUsername . '<br />
		  <strong>Pterodactyl Email:</strong> ' . $UserEmail . '<br />
		  <strong>Pterodactyl Password:</strong> ' . $userSearch['pterodactyl_password'] . '<br />
		  <strong>Plan Expiry Date (day/month/year):</strong> ' . date('d/m/Y', $userSearch['plan_expiry']) . ' - <em>If 00/00/0000 or 01/01/1970 then it means Never.</em>
		  ';
		  ?>
		  
		  <hr>
		  
		  <h3>Change extra RAM</h3>
		  <form action="" method="post">
		  <input type="hidden" name="discord_id" value="<?php echo $_POST['discord_id']; ?>">
		  New extra RAM (in MB): <input type="number" name="new_extra_ram" class="form-control" required><br />
		  <input type="submit" name="ChangeExtraRam" class="btn btn-primary" value="Change!">
		  </form>
		  
		  <hr>
		  
		  <h3>Change extra servers</h3>
		  <form action="" method="post">
		  <input type="hidden" name="discord_id" value="<?php echo $_POST['discord_id']; ?>">
		  New extra servers: <input type="number" name="new_extra_servers" class="form-control" required><br />
		  <input type="submit" name="ChangeExtraServers" class="btn btn-primary" value="Change!">
		  </form>
		  
		  <hr>
		  
		  <h3>Change extra sites</h3>
		  <form action="" method="post">
		  <input type="hidden" name="discord_id" value="<?php echo $_POST['discord_id']; ?>">
		  New extra sites: <input type="number" name="new_extra_sites" class="form-control" required><br />
		  <input type="submit" name="ChangeExtraSites" class="btn btn-primary" value="Change!">
		  </form>
		  
		  <hr>
		  
		  <h3>Change extra disk</h3>
		  <form action="" method="post">
		  <input type="hidden" name="discord_id" value="<?php echo $_POST['discord_id']; ?>">
		  New extra disk (in MB): <input type="number" name="new_extra_disk" class="form-control" required><br />
		  <input type="submit" name="ChangeExtraDisk" class="btn btn-primary" value="Change!">
		  </form>
		  
		  <hr>
		  
		  <h3>Change extra cores</h3>
		  <form action="" method="post">
		  <input type="hidden" name="discord_id" value="<?php echo $_POST['discord_id']; ?>">
		  New cores: <input type="number" name="new_extra_cores" class="form-control" required><br />
		  <input type="submit" name="ChangeExtraCores" class="btn btn-primary" value="Change!">
		  </form>
		  
		  <hr>
		  
		  <h3>Change plan level ID</h3>
		  <form action="" method="post">
		  <input type="hidden" name="discord_id" value="<?php echo $_POST['discord_id']; ?>">
		  New level ID: <input type="number" name="new_level" class="form-control" required><br />
		  <input type="submit" name="ChangeLevelId" class="btn btn-primary" value="Change!">
		  </form>
		  
		  <hr>
		  
		  <h3>Change panel's password</h3>
		  <form action="" method="post">
		  <input type="hidden" name="discord_id" value="<?php echo $_POST['discord_id']; ?>">
		  New password: <input type="text" name="new_password" class="form-control" required><br />
		  <input type="submit" name="ChangePanelPass" class="btn btn-primary" value="Change!">
		  </form>
		  
		  <br /><br />
	  </div>
		  
	  </center>

    <!-- Optional JavaScript -->
    <!-- first Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>