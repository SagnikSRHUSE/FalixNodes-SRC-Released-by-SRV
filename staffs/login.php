<?php
session_start();
include("global.php");
include("../config.php");
if( checklogin() == true ) {
	header("Location: index");
	die();
}

if( isset($_POST['submit']) ) {
	if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        $secret = '6LfqHrQUAAAAALx3ZJOdAodXSx1KMZOj8WOEOw6g';
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
        if(!$responseData->success) {
            die("ERROR: Captcha failed.");
        }
	} else {
		die("ERROR: Captcha failed.");
	}
	if( !isset($_POST['username']) || empty($_POST['username']) || !isset($_POST['password']) || empty($_POST['password']) ) {
		die("ERROR: All the fields are required.");
	}
	$user_check = $conn->query("SELECT * FROM staffs WHERE username='" . mysqli_real_escape_string($conn, $_POST['username']) . "' AND password='" . mysqli_real_escape_string($conn, md5(md5($_POST['password']))) . "'");
	if( $user_check->num_rows == 0 ) {
		die("ERROR: Invalid username and/or password.");
	} else {
		$_SESSION['staffs_isLoggedIn'] = true;
		$_SESSION['user'] = $user_check->fetch_assoc()['id'];
		header("Location: index");
		die();
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	  
	  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	  
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	  
	  <script src='https://www.google.com/recaptcha/api.js' async defer></script>

    <title>FalixNodes | Login</title>
  </head>
  <body>
	  
	  <center>
	
			<div class="jumbotron">
			  <h1 class="display-4">FalixNodes</h1>
				<hr class="my-4">
			  <p class="lead">Staffs Panel</p>
			</div>
		  
	  <div class="container">
			<form action="" method="post">
				Username: <input type="text" name="username" class="form-control" required>
				<br />
				Password: <input type="password" name="password" class="form-control" required>
				<br />
				<div class="g-recaptcha" data-sitekey="6LfqHrQUAAAAAASVUQRGdCIcx-5MGgzKw2KpLKij"></div>
				<br />
				<input type="submit" name="submit" class="btn btn-primary">
			</form>
	  </div>
		  
	  </center>

    <!-- Optional JavaScript -->
    <!-- first Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>