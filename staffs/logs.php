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
	  
	  <script>
		  $(window).on('load', function(){
			$("#createServerBox").load("create");
		  });
	  </script>
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
		  <a href="#" class="btn btn-primary" role="button" data-toggle="modal" data-target="#createserver"><i class="fas fa-plus"></i> Create a Server</a>&nbsp;
		  <a href="searchUsers" class="btn btn-primary" role="button">Search Users</a>
		  <a href="searchServers" class="btn btn-primary" role="button">Search Servers</a>
		  <a href="levels" class="btn btn-primary" role="button">Levels</a>
		  <a href="logs" class="btn btn-primary" role="button">Logs</a>
		  <br /><br />
		  <h3>Staffs Logs (Last 40)</h3>
		  <?php
		  $results = mysqli_query($conn, "SELECT * FROM staffs_logs ORDER BY id DESC LIMIT 40");
			echo "<table class=\"table table-striped\">";
			  echo "
				  <thead>
				  <tr>
					<th>Staff</th>
					<th>Action</th>
					<th>Time (dd/mm/yyyy 24-hour-clock)</th>
				  </tr>
				</thead>
			  ";
			  if( $results->num_rows !== 0 ) {
				 while($rowitem = mysqli_fetch_array($results)) {
					$staff_username = $conn->query("SELECT * FROM staffs WHERE id = " . mysqli_real_escape_string($conn, $rowitem['staff_id']))->fetch_assoc()['username'];
					echo "<tr>";
					echo "<td>" . $staff_username . "</td>";
					echo "<td>" . $rowitem['log_message'] . "</td>";
					echo "<td>" . date('d/m/Y H:i:s', intval($rowitem['time'])) . "</td>";
					echo "</tr>";
				}
			  } else {
				  echo "There's no logs to show.";
			  }
				echo "</table>"; //end table tag
		  ?>
	  </div>
		  
	  </center>
	  
	<!-- modal:createserver -->
	<div id="createserver" class="modal fade" role="dialog">
	  <div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<h4 class="modal-title">Create Server</h4>
		  </div>
		  <div class="modal-body" id="createServerBox">
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		  </div>
		</div>

	  </div>
	</div>

    <!-- Optional JavaScript -->
    <!-- first Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>