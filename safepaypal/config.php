<?php
// PayPal REST API app info. If you don't have one, create it here: https://developer.paypal.com/developer/applications/
$restapp['client_id'] = "";
$restapp['secret'] = "";

// PayPal NVP API info. If you don't have those, get them by following this tutorial: https://www.putler.com/support/faq/how-to-get-paypal-api-username-password-and-signature-information/
$nvp['user'] = "";
$nvp['password'] = "";
$nvp['signature'] = "";

// IPN information
$ipn_url = "https://DOMAIN/ipn/paypal.php";

// Database information.
// This database is used to store the IPN queue
$db['hostname'] = "localhost";
$db['user'] = "";
$db['pass'] = "";
$db['name'] = "";
$db['table_name'] = "safepaypal"; // The name of the table that SafePayPal should store data in.
$conn = new mysqli($db['hostname'], $db['user'], $db['pass'], $db['name']);

// Payment information.
// Those information will be displayed to your visitors on the buy widget too. Your buyers will send the money to $your_paypal_email with the currency $currency_you_want
$your_paypal_email = "";
$currency_you_want = "EUR";
?>
