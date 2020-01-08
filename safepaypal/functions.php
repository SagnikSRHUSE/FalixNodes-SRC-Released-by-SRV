<?php
function GetTransactionInfo($transaction_id, $restapp_client_id, $restapp_secret, $nvp_user, $nvp_pass, $nvp_signature) {
	// Reference: https://developer.paypal.com/docs/api/get-an-access-token-curl/
	$ch = curl_init("https://api.paypal.com/v1/oauth2/token");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Accept: application/json",
		"Accept-Language: en_US"
	));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
	curl_setopt($ch, CURLOPT_USERPWD, $restapp_client_id . ':' . $restapp_secret);
	$result = curl_exec($ch);
	curl_close($ch);
	$access_token = json_decode($result, true)['access_token'];

	// Reference: https://developer.paypal.com/docs/api/payments/v1/#orders_get
	$ch = curl_init("https://api.paypal.com/v1/payments/orders/" . $transaction_id);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Content-Type: application/json",
		"Accept: application/json",
		"Authorization: Bearer " . $access_token
	));
	$result = curl_exec($ch);
	curl_close($ch);
	if( empty($result) ) {
		return array(
			"error" => true,
			"err_message" => "Empty response from PayPal",
			"err_id" => 2
		);
	} else {
		$result = json_decode($result, true);
		if( isset($result['name']) && $result['name'] == "INVALID_RESOURCE_ID" ) {
			return array(
				"error" => true,
				"err_message" => "This transaction ID doesn't exists",
				"err_id" => 1
			);
		} else {
			$transaction_id = $result['id'];
			$create_time = $result['create_time'];
			$transaction_state = strtolower($result['state']);
			$transaction_total = $result['amount']['total'];
			$transaction_currency = $result['amount']['currency'];

			// Reference: https://developer.paypal.com/docs/classic/express-checkout/ht-searchRetrieveTransactionData-curl-etc/#step-2-show-transaction-details
			$ch = curl_init("https://api-3t.paypal.com/nvp");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "USER=" . $nvp_user . "&PWD=" . $nvp_pass . "&SIGNATURE=" . $nvp_signature . "&METHOD=GetTransactionDetails&TRANSACTIONID=" . $transaction_id . "&VERSION=94");
			$result = curl_exec($ch);
			curl_close($ch);
			if( empty($result) ) {
				return array(
					"error" => true,
					"err_message" => "Empty response from PayPal",
					"err_id" => 2
				);
			} else {
				parse_str($result, $result);
				if( $result['PROTECTIONELIGIBILITY'] == "Ineligible" ) {
					$payment_type = "faf"; // Friends & Family
				} else {
					$payment_type = "gas"; // Goods & Services
				}
				$receiver_email = $result['RECEIVEREMAIL'];
				$sender_email = $result['EMAIL'];
				$sender_status = $result['PAYERSTATUS']; // verified/unverified
				
				return array(
					"txn_id" => $transaction_id, // The transaction ID
					"txn_createtime" => $create_time, // The transaction creation time
					"txn_state" => $transaction_state, // Possible values: completed, pending, authorized, voided, captured, refunded
					"txn_total" => $transaction_total, // The total amount of the transaction
					"txn_currency" => $transaction_currency, // The currency of the transaction
					"payment_type" => $payment_type, // The payment type. Possible values: faf, gas. "faf" means Friends & Family, "gas" means Goods & Services
					"receiver_email" => $result['RECEIVEREMAIL'], // The e-mail address of the receiver
					"sender_email" => $sender_email, // The e-mail address of the sender
					"sender_status" => $sender_status // The status of the sender. Possible values: verified, unverified
				);
			}
		}
	}
}
?>
