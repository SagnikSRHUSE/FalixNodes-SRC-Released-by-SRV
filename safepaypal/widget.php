<?php
require_once("config.php");
require_once("functions.php");

$required_postfields = array(
	"item_name", // The name of the item.
	"item_price", // The price of the item.
	"custom" // A passthrou value that you can use to send data to the IPN.
);
foreach($required_postfields as $required_postfield) {
	if( !isset($_POST[$required_postfield]) || empty($_POST[$required_postfield]) ) {
		http_response_code(400);
		die("400 Bad Request");
	}
}

if( isset($_POST['submit']) ) {
	$required_postfields = array(
		"txn_id",
		"item_name",
		"item_price",
		"custom"
	);
	if( $_POST['custom'] == "spp_none" ) {
		$custom = "";
	} else {
		$custom = $_POST['custom'];
	}
	foreach($required_postfields as $required_postfield) {
		if( !isset($_POST[$required_postfield]) || empty($_POST[$required_postfield]) ) {
			$error = "The transaction ID field is required.";
		}
	}
	if( $conn->query("SELECT * FROM " . $db['table_name'] . " WHERE txn_id = '" . mysqli_real_escape_string($conn, strtoupper($_POST['txn_id'])) . "'")->num_rows >= 1 ) {
		$error = "This transaction ID was used before already.";
	}
	if( !isset($error) ) {
		$transaction_info = GetTransactionInfo($_POST['txn_id'], $restapp['client_id'], $restapp['secret'], $nvp['user'], $nvp['password'], $nvp['signature']);
		if( $transaction_info['error'] == true ) {
			if( $transaction_info['err_id'] == 1 ) {
				$error = "Invalid transaction ID.";
			}
			if( $transaction_info['err_id'] == 2 ) {
				$error = "Unable to connect to PayPal.";
			}
		} else {
			if( $transaction_info['txn_state'] !== "completed" ) {
				$error = "The transaction state isn't complete. If you already completed it but you see this error, then please try to input the transaction ID again after exactly 24 hours.";
			} else {
				if( strtolower($transaction_info['txn_currency']) !== strtolower($currency_you_want) ) {
					$error = "You didn't send the money with the currency " . $currency_you_want;
				} else {
					if( intval($transaction_info['txn_total']) < intval($_POST['item_price']) ) {
						$error = "You didn't send enough money.";
					} else {
						if( $transaction_info['payment_type'] !== "faf" ) {
							$error = "You didn't do the transaction as \"Friends & Family\"";
						} else {
							if( strtolower($transaction_info['receiver_email']) !== strtolower($your_paypal_email) ) {
								$error = "You didn't send the money to the correct address.";
							} else {
								// All the checks were done and the transaction is good.
								$conn->query("INSERT INTO " . $db['table_name'] . " (txn_id, txn_state, txn_total, txn_currency, payment_type, sender_email, sender_status, custom, processed_by_ipn, being_processed_by_ipn) VALUES ('" . mysqli_real_escape_string($conn, strtoupper($_POST['txn_id'])) . "', '" . mysqli_real_escape_string($conn, $transaction_info['txn_state']) . "', " . mysqli_real_escape_string($conn, intval($transaction_info['txn_total'])) . ", '" . mysqli_real_escape_string($conn, strtolower($transaction_info['txn_currency'])) . "', '" . mysqli_real_escape_string($conn, $transaction_info['payment_type']) . "', '" . mysqli_real_escape_string($conn, strtolower($transaction_info['sender_email'])) . "', '" . mysqli_real_escape_string($conn, strtolower($transaction_info['sender_status'])) . "', '" . mysqli_real_escape_string($conn, $custom) . "', 0, 0)");
								$success = "Thank you for your payment :-)";
							}
						}
					}
				}
			}
		}
	}
}
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>SPP Widget</title>
  </head>
  <body>
    <center>
	
		<div class="jumbotron">
			  <h1 class="display-4">SPP</h1>
				<hr class="my-4">
			  <p class="lead">Widget</p>
		</div>
		
		<div class="container">
			<?php
			if( isset($error) && !empty($error) ) {
				echo '
					<div class="alert alert-danger" role="alert">
					  <strong>Error!</strong> ' . $error . '
					</div>
				';
			}
			if( isset($success) && !empty($success) ) {
				echo '
					<div class="alert alert-success" role="alert">
					  <strong>Success!</strong> ' . $success . '
					</div>
				';
			}
			?>
			<p>You are buying <strong><?php echo htmlspecialchars($_POST['item_name']); ?></strong></p>
			<hr>
			<h3>Please read every word written on this page carefully! We are not responsible for any money loss that may happen if you didn't read this carefully.</h3>
			<hr>
			<p>Please send <em>exactly</em> <strong><?php echo htmlspecialchars(intval($_POST['item_price'])); ?> <?php echo htmlspecialchars(strtoupper($currency_you_want)); ?></strong> (<em>please note that it must be <?php echo htmlspecialchars(strtoupper($currency_you_want)); ?></em>) to the PayPal address <strong><?php echo htmlspecialchars(strtolower($your_paypal_email)); ?></strong> as a <strong>"Friends & Family"</strong> payment!</p>
			<hr>
			<p>If you've done that, please put the transaction ID in the below field then click "Proceed":</p>
			<form method="post" action="">
				<input type="text" name="txn_id" required="">
				<input type="hidden" name="item_name" value="<?php echo $_POST['item_name']; ?>">
				<input type="hidden" name="item_price" value="<?php echo $_POST['item_price']; ?>">
				<input type="hidden" name="custom" value="<?php echo $_POST['custom']; ?>">
				<input type="submit" name="submit" value="Proceed" class="btn btn-primary">
			</form>
		</div>
		
	</center>
	
	<br /><br /><br />

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>
