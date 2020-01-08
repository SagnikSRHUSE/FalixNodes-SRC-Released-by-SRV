<?php
error_reporting(0);
set_time_limit(0);
$base = dirname(__FILE__);
require_once($base . "/config.php");
// cron_section:IpnProcessor
while(($row = $conn->query("SELECT * FROM " . $db['table_name'] . " WHERE processed_by_ipn=0 AND being_processed_by_ipn=0")->fetch_assoc()) != NULL) {
	$conn->query("UPDATE " . $db['table_name'] . " SET being_processed_by_ipn=1 WHERE id=" . mysqli_real_escape_string($conn, $row['id']));
	$ch = curl_init($ipn_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, array(
		"txn_id" => $row['txn_id'],
		"txn_state" => $row['txn_state'],
		"txn_total" => intval($row['txn_total']),
		"txn_currency" => $row['txn_currency'],
		"payment_type" => $row['payment_type'],
		"sender_email" => $row['sender_email'],
		"sender_status" => $row['sender_status'],
		"custom" => strval($row['custom'])
	));
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	$result = curl_exec($ch);
	$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	if( $http_status == 200 ) {
		$conn->query("UPDATE " . $db['table_name'] . " SET processed_by_ipn=1 WHERE id=" . mysqli_real_escape_string($conn, $row['id']));
	}
	$conn->query("UPDATE " . $db['table_name'] . " SET being_processed_by_ipn=0 WHERE id=" . mysqli_real_escape_string($conn, $row['id']));
}
// cron_section:end()
?>