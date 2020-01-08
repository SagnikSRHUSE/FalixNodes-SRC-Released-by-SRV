<?php
session_start();

include("config.php");

include("global.php");

$user_ip = getUserIP();

if( checklogin() == true ) {

	//already logged in

	header("Location: /");

	die();

}

define('OAUTH2_CLIENT_ID', '607961536501252141');

define('OAUTH2_CLIENT_SECRET', 'GQNdYrrAnMUlLOmz_5acYhlnaLeAnKB3');

$authorizeURL = 'https://discordapp.com/api/oauth2/authorize';

$tokenURL = 'https://discordapp.com/api/oauth2/token';

$apiURLBase = 'https://discordapp.com/api/users/@me';

// Start the login process by sending the user to Discord's authorization page

if(get('action') == 'login') {

  $params = array(

    'client_id' => OAUTH2_CLIENT_ID,

    'redirect_uri' => 'http://falixnodes.host/login',

    'response_type' => 'code',

    'scope' => 'identify guilds guilds.join email'

  );

  // Redirect the user to Discord's authorization page

  header('Location: https://discordapp.com/api/oauth2/authorize' . '?' . http_build_query($params));

  die();

}

// When Discord redirects the user back here, there will be a "code" and "state" parameter in the query string

if(get('code')) {

  // Exchange the auth code for a token

  $token = apiRequest($tokenURL, array(

    "grant_type" => "authorization_code",

    'client_id' => OAUTH2_CLIENT_ID,

    'client_secret' => OAUTH2_CLIENT_SECRET,

    'redirect_uri' => 'http://falixnodes.host/login',

    'code' => get('code')

  ));

  $logout_token = $token->access_token;

  $_SESSION['access_token'] = $token->access_token;

  header('Location: ' . $_SERVER['PHP_SELF']);

	die();

}

if(session('access_token')) {

  $user = apiRequest($apiURLBase);
  $user_guilds = apiRequest($apiURLBase . "/guilds");
  
  if( !isset($user->email) || empty($user_guilds) ) {
	  die("Please don't modify the scopes.");
  }


	  // check if user uses VPN/proxy
	  if( $conn->query("SELECT * FROM allowed_ips WHERE ip='" . mysqli_real_escape_string($conn, $user_ip) . "'")->num_rows == 0 ) {
		  if( isProxy($user_ip) == true ) {
			  die("Possible fraud attempt, please disable any VPN or proxy services to continue.");
		  }
	  }

	$checkUserInDb = $conn->query("SELECT * FROM users WHERE discord_id='" . mysqli_real_escape_string($conn, $user->id) . "'");

	if( $checkUserInDb->num_rows == 0 ) {

		

		  // check if user have another account on same IP - and use cookie to detect multi-accounts

		  $CheckLastlogins = $conn->query("SELECT * FROM users WHERE lastlogin_ip='" . mysqli_real_escape_string($conn, $user_ip) . "'")->num_rows;

		  $CheckRegisters = $conn->query("SELECT * FROM users WHERE register_ip='" . mysqli_real_escape_string($conn, $user_ip) . "'")->num_rows;

		  if( isset($_COOKIE['fn_cf']) ) {

			  $CookieDetected = true;

		  } else {

			  $CookieDetected = false;

		  }

		  if( $CheckLastlogins >= 1 || $CheckRegisters >= 1 || $CookieDetected == true ) {

			  die("You already have another account. Multi-accounts are disallowed!");

		  }

		

		//create pterodactyl panel account

		$pterodactyl_username = strtolower(generateRandomString(7));

		$pterodactyl_password = generateRandomString(10);

		$ch = curl_init("https://" . $ptero_domain . "/api/application/users");

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(

			"Authorization: Bearer " . $ptero_key,

			"Content-Type: application/json",

			"Accept: Application/vnd.pterodactyl.v1+json"

		));

		curl_setopt($ch, CURLOPT_POST, 1);

		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(

			"username" => $pterodactyl_username,

			"email" => $user->email,

			"first_name" => "A",

			"last_name" => "User",

			"password" => $pterodactyl_password,

		)));

		$pterodactyl_result = curl_exec($ch);

		curl_close($ch);

		if( json_decode($pterodactyl_result, true)['object'] == "user" ) {

			$pterodactyl_userid = json_decode($pterodactyl_result, true)['attributes']['id'];

		} else {

			die("An error had occured while logging you in, please contact support.");

		}

		

		// let the user join the falixnodes discord server (if not already joined) and add the role to him/her (if not already added)

		JoinGuild($user->id, $discord['autojoin_guildid'], $discord['autojoin_role'], $discord['bot_token']);

		AddRoleToGuildMember($user->id, $discord['autojoin_guildid'], $discord['autojoin_role'], $discord['bot_token']);

		

		//add user in database

		$conn->query("INSERT INTO users (discord_id, discord_email, pterodactyl_userid, pterodactyl_username, pterodactyl_password, level, register_ip, lastlogin_ip, extra_ram, extra_servers, extra_cores, extra_disk, plan_expiry, lastbackup_time, joined_servers, extra_sites) VALUES ('" . mysqli_real_escape_string($conn, $user->id) . "', '" . mysqli_real_escape_string($conn, $user->email) . "', " . $pterodactyl_userid . ", '" . $pterodactyl_username . "', '" . $pterodactyl_password . "', 0, '" . mysqli_real_escape_string($conn, $user_ip) . "', '" . mysqli_real_escape_string($conn, $user_ip) . "', 0, 0, 0, 0, 0, 0, '', 0)");

		

		setcookie(

		  "cf_cloud",

		  base64_encode(base64_encode($user->id)),

		  time() + (10 * 365 * 24 * 60 * 60)

		);

	} else {
		
		  $UserIsInOurDiscordServer = false;
		  foreach($user_guilds as $user_guild) {
			if( !empty($user_guild->id) ) {
				if( $user_guild->id == $discord['autojoin_guildid'] ) {
					$UserIsInOurDiscordServer = true;
				}
			}
		  }
		  if( $UserIsInOurDiscordServer == false ) {
			  die("You must be in our Discord server. <a href=\"https://discord.gg/Fh2DjES\" target=\"_blank\">Click here to join our Discord server.</a>");
		  }

		// let the user join the falixnodes discord server (if not already joined) and add the role to him/her (if not already added)

		JoinGuild($user->id, $discord['autojoin_guildid'], $discord['autojoin_role'], $discord['bot_token']);
		AddRoleToGuildMember($user->id, $discord['autojoin_guildid'], $discord['autojoin_role'], $discord['bot_token']);

		

		$conn->query("UPDATE users SET lastlogin_ip='" . mysqli_real_escape_string($conn, $user_ip) . "' WHERE discord_id='" . mysqli_real_escape_string($conn, $user->id) . "'");
		$conn->query("UPDATE users SET discord_email='" . mysqli_real_escape_string($conn, $user->email) . "' WHERE discord_id='" . mysqli_real_escape_string($conn, $user->id) . "'");
		
		if( !isset($_COOKIE['fn_cf']) ) {

			setcookie(

			  "cf_cloud",

			  base64_encode(base64_encode($user->id)),

			  time() + (10 * 365 * 24 * 60 * 60)

			);

		}

	}

	$_SESSION['discord_user'] = $user;
	
	$_SESSION['discord_user_guilds'] = $user_guilds;

	$_SESSION['isLoggedIn'] = true;

  	header("Location: /");

	die();

} else {

  	header("Location: login?action=login");

	die();

}

function apiRequest($url, $post=FALSE, $headers=array()) {

  $ch = curl_init($url);

  curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

  if($post)

    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

  $headers[] = 'Accept: application/json';

  if(session('access_token'))

    $headers[] = 'Authorization: Bearer ' . session('access_token');

  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $response = curl_exec($ch);

  return json_decode($response);

	curl_close($ch);

}



function JoinGuild($user_id, $guild_id, $role_id, $bot_token) {

	  $ch = curl_init("https://discordapp.com/api/guilds/" . $guild_id . "/members/" . $user_id);

	  curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

	  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

	  $headers[] = 'Accept: application/json';

	  $headers[] = 'Content-Type: application/json';

	  if(session('access_token')) {

			$headers[] = 'Authorization: Bot ' . $bot_token;

			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(

				"access_token" => session('access_token'),

				"roles" => array($role_id)

			)));

	  }

	  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	  $response = curl_exec($ch);

	  return json_decode($response);

	  curl_close($ch);

}

function AddRoleToGuildMember($user_id, $guild_id, $role_id, $bot_token) {

	  $ch = curl_init("https://discordapp.com/api/guilds/" . $guild_id . "/members/" . $user_id . "/roles/" . $role_id);

	  curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

	  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

	  $headers[] = 'Accept: application/json';

	  $headers[] = 'Content-Type: application/json';

	  if(session('access_token')) {

			$headers[] = 'Authorization: Bot ' . $bot_token;

			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(

				"access_token" => session('access_token')

			)));

	  }

	  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	  $response = curl_exec($ch);

	  return json_decode($response);

	  curl_close($ch);

}



function get($key, $default=NULL) {

  return array_key_exists($key, $_GET) ? $_GET[$key] : $default;

}

function session($key, $default=NULL) {

  return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;

}

?>