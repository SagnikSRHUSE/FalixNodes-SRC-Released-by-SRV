<?php
//die("Maintenance");
session_start();

include("global.php");

include("config.php");

include("plans.php");
require_once("vendor/autoload.php"); //plesk API

if( checklogin() == false ) {

	notloggedin("You must be logged in.");

}

$user = $_SESSION['discord_user'];

if( isset($_POST['submit']) ) {

	if( empty($_POST['site_domain']) || empty($_POST['sitepackage']) || !isset($_POST['site_domain']) || !isset($_POST['sitepackage']) ) {

		ShowError("All the fields are required.");

	}

	

	//Check if user reached max sites

	$currentUserSitesAmount = $conn->query("SELECT * FROM sites WHERE owner_id='" . mysqli_real_escape_string($conn, $user->id) . "'")->num_rows;

	if( $currentUserSitesAmount >= ($maxsites + $user_extra_sites) ) {

		ShowError("Sorry, you have reached your maximum sites amount. (" . ($maxsites + $user_extra_sites) . " sites is your maximum amount)");

	}

	

	// Plan checker

	if( $GET_USER_LEVEL == 0 ) {
		// this user is free
		$allowed_packages = array("Free");
	} else {
		// this user is donator
		$allowed_packages = array("Free", "Donators");
	}

	if( !in_array($_POST['sitepackage'], $allowed_packages) ) {
		ShowError("You don't have permissions to create a site using this package.");
	}
	
	if( filter_var($_POST['site_domain'], FILTER_VALIDATE_DOMAIN) !== $_POST['site_domain'] ) {
		ShowError("You must enter a valid domain name.");
	}


	$plesk_user = "admin";
	$plesk_pass = "Jh7DksyP2uKZMk";
	$plesk_ip = "95.216.113.237";
	$plesk_hostname = "webhost01.falixnodes.host";
	function generateRandomString2($length = 10, $special_chars = false, $numbers = false) {
		if( $special_chars == false && $numbers == true ) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}
		if( $special_chars == false && $numbers == false ) {
			$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}
		if( $special_chars == true && $numbers == true ) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ~!@#$%^*()_';
		}
		if( $special_chars == true && $numbers == false ) {
			$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ~!@#$%^*()_';
		}
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	$user_domain = strtolower($_POST['site_domain']);
	$user_name = strtolower(generateRandomString2(9, false, false));
	$user_pass = generateRandomString2(13, true, true);
	$user_plan = $_POST['sitepackage'];
	$user_email = $user->email;
	// start plesk session and authenticate
	$client = new \PleskX\Api\Client($plesk_ip);
	$client->setCredentials($plesk_user, $plesk_pass);
	// check if domain already exists in plesk
	$packet = "
	<packet>
	<webspace>
	<get>
	   <filter>
		  <name>" . $user_domain . "</name>
	   </filter>
	   <dataset>
		  <hosting/>
	   </dataset>
	</get>
	</webspace>
	</packet>
	";
	try {
		$response = $client->request($packet);
		if( strval($response->status) == "ok" ) {
			ShowError("The entered domain is already hosted with FalixNodes.");
		}
	} catch (\Throwable $e) {
		// domain doesnt exists
		unset($e);
	}
	// create plesk customer
	$response = $client->customer()->create([
		'cname' => '',
		'pname' => 'FalixNodes User',
		'login' => $user_name,
		'passwd' => $user_pass,
		'email' => $user_email,
	]);
	if( empty($response->id) ) {
		ShowError("An error has occured while creating your site. If this error still exists for long time, please contact support.");
	}
	$customerid = $response->id;
	// create plesk subscription and link it to the customer that we previously created
	$packet = "
	<packet>
	<webspace>
	<add>
	   <gen_setup>
		  <name>" . $user_domain . "</name>
		  <owner-id>" . $customerid . "</owner-id>
		  <htype>vrt_hst</htype>
		  <ip_address>" . $plesk_ip . "</ip_address>
		  <status>0</status>
	   </gen_setup>
	   <hosting>
		  <vrt_hst>
			  <property>
				<name>ftp_login</name>
				<value>" . $user_name . "</value>
			  </property>
			  <property>
				<name>ftp_password</name>
				<value>" . $user_pass . "</value>
			  </property>
			  <ip_address>" . $plesk_ip . "</ip_address>
		   </vrt_hst>
		</hosting>
		<plan-name>" . $_POST['sitepackage'] . "</plan-name>
	</add>
	</webspace>
	</packet>
	";
	try {
		$response = $client->request($packet);
	} catch (\Throwable $e) {
		ShowError("An error has occured while creating your site. If this error still exists for long time, please contact support.");
	}
	if( strval($response->status) !== "ok" ) {
		ShowError("An error has occured while creating your site. If this error still exists for long time, please contact support.");
	}
	$subscription_id = $response->id;

	//add site to database
	$conn->query("INSERT INTO sites (owner_id, plesk_customerid, plesk_subscription_id, plesk_username, plesk_password, node, domain) VALUES ('" . mysqli_real_escape_string($conn, $user->id) . "', " . mysqli_real_escape_string($conn, $customerid) . ", " . mysqli_real_escape_string($conn, $subscription_id) . ", '" . mysqli_real_escape_string($conn, $user_name) . "', '" . mysqli_real_escape_string($conn, $user_pass) . "', 'webhost01', '" . mysqli_real_escape_string($conn, strtolower($_POST['site_domain'])) . "')");

	//redirect to homepage with success message

	ShowSuccess("Created site!");

}

?>

<form action="createsite" method="post">

	Site Domain: <input type="text" name="site_domain" class="form-control" required>

	<br /><br />

	Site Package:

		<div id="SitePackage">
		
			<div class="card" style="width: 27rem;">

				  <div class="card-body">

					<h5 class="card-title">Free</h5>
					
						<input type="radio" name="sitepackage" value="Free"> Free

				  </div>

			</div>
			
			<div class="card" style="width: 27rem;">

				  <div class="card-body">

					<h5 class="card-title">Donators</h5>
					
						<input type="radio" name="sitepackage" value="Donators"> Donators

				  </div>

			</div>
		
		</div>

	<br />

	<input type="submit" name="submit" value="Create!" class="btn btn-success">

</form>