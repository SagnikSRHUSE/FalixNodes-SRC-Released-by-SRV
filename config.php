<?php
$servername = "localhost";
$username = "";
$password = "";
$dbname = "";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Pterodactyl API settings
$ptero_domain = "";
$ptero_key = "";
$ptero_client_key = "";

// Payment settings
$paypal['email'] = "";

// Discord server settings
$discord['autojoin_role'] = ""; //role ID
$discord['autojoin_guildid'] = ""; //server ID
$discord['bot_token'] = "";
?>