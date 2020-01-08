<?php

session_start();

include("global.php");

include("config.php");
include("join_for_resources.php");

if( checklogin() == true ) {

	$user = $_SESSION['discord_user'];

	$pterodactyl_panelinfo = $conn->query("SELECT * FROM users WHERE discord_id='" . mysqli_real_escape_string($conn, $user->id) . "'")->fetch_assoc();

	$pterodactyl_username = $pterodactyl_panelinfo['pterodactyl_username'];

	$pterodactyl_password = $pterodactyl_panelinfo['pterodactyl_password'];

} else {

	header("Location: auth");

	die();

}

include("plans.php");

?>

<!DOCTYPE html>

<html>



<head>

  <meta charset="utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  <meta property="og:title" content="FalixNodes - Free Hosting" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://falixnodes.host" />
  <meta property="og:image" content="https://falixnodes.host/assets/img/brand/blue.png" />
  <meta property="og:description" content="Free Hosting for everyone." />

  

  <!-- jQuery -->

  <script src="./assets/vendor/jquery/dist/jquery.min.js"></script>

  

  <title>FalixNodes</title>


  

  	  <script>

		  $(window).on('load', function(){

			$("#createServerBox").load("create");

		  });

	  </script>

	

  <!-- Favicon -->

  <link href="./assets/img/brand/favicon.png" rel="icon" type="image/png">

  <!-- Fonts -->

  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

  <!-- Icons -->

  <link href="./assets/vendor/nucleo/css/nucleo.css" rel="stylesheet">

  <link href="./assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">

  <!-- Argon CSS -->

  <link type="text/css" href="./assets/css/argon.css?v=1.0.0" rel="stylesheet">
  <link type="text/css" href="./assets/css/argon.dark.css?v=1.0.1" rel="stylesheet">
  
  <style>
	/* The switch - the box around the slider */
	.switch {
	  position: relative;
	  display: inline-block;
	  width: 60px;
	  height: 34px;
	}

	/* Hide default HTML checkbox */
	.switch input {
	  opacity: 0;
	  width: 0;
	  height: 0;
	}

	/* The slider */
	.slider {
	  position: absolute;
	  cursor: pointer;
	  top: 0;
	  left: 0;
	  right: 0;
	  bottom: 0;
	  background-color: #ccc;
	  -webkit-transition: .4s;
	  transition: .4s;
	}

	.slider:before {
	  position: absolute;
	  content: "";
	  height: 26px;
	  width: 26px;
	  left: 4px;
	  bottom: 4px;
	  background-color: white;
	  -webkit-transition: .4s;
	  transition: .4s;
	}

	input:checked + .slider {
	  background-color: #2196F3;
	}

	input:focus + .slider {
	  box-shadow: 0 0 1px #2196F3;
	}

	input:checked + .slider:before {
	  -webkit-transform: translateX(26px);
	  -ms-transform: translateX(26px);
	  transform: translateX(26px);
	}

	/* Rounded sliders */
	.slider.round {
	  border-radius: 34px;
	}

	.slider.round:before {
	  border-radius: 50%;
	}
	</style>

</head>



<body>

  <!-- Sidenav -->

  <nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">

    <div class="container-fluid">

      <!-- Toggler -->

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">

        <span class="navbar-toggler-icon"></span>

      </button>

      <!-- Brand -->

      <a class="navbar-brand pt-0" href="./index">

        <img src="./assets/img/brand/blue.png" class="navbar-brand-img" alt="...">

      </a>

      <!-- User -->

      <ul class="nav align-items-center d-md-none">

          </a>

            <div class="media align-items-center">


              </span>

            </div>

          </a>

          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">

            </a>

          </div>

        </li>

      </ul>

      <!-- Collapse -->

      <div class="collapse navbar-collapse" id="sidenav-collapse-main">

        <!-- Collapse header -->

        <div class="navbar-collapse-header d-md-none">

          <div class="row">

            <div class="col-6 collapse-brand">

              <a href="./index">

                <img src="./assets/img/brand/blue.png">

              </a>

            </div>

            <div class="col-6 collapse-close">

              <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">

                <span></span>

                <span></span>

              </button>

            </div>

          </div>

        </div>

        <!-- Form -->


        <!-- Navigation -->

        <ul class="navbar-nav">

          <li class="nav-item">

            <a class="nav-link" href="./index">

              <i class="ni ni-tv-2 text-primary"></i> Dashboard

            </a>

          </li>
		  
		  <li class="nav-item">

            <a class="nav-link" href="./webhosting">

              <i class="ni ni-world text-primary"></i> Webhosting

            </a>

          </li>

          <li class="nav-item">

            <a class="nav-link" href="#" data-toggle="modal" data-target="#createserver">

              <i class="ni ni-fat-add text-blue"></i> Create Server

            </a>

          </li>

          <li class="nav-item">

            <a class="nav-link" href="#" data-toggle="modal" data-target="#plan_info">

              <i class="ni ni-collection text-orange"></i> My Plan

            </a>

          </li>

		  <li class="nav-item">

            <a class="nav-link" href="#" data-toggle="modal" data-target="#logintopanel">

              <i class="ni ni-send text-red"></i> Login to Panel

            </a>

          </li>

		  <li class="nav-item">

            <a class="nav-link" href="changeram">

              <i class="ni ni-tie-bow text-green"></i> Customize RAM

            </a>

          </li>

          <li class="nav-item">

            <a class="nav-link" href="pricing">

              <i class="ni ni-cart text-yellow"></i> Pricing

            </a>

          </li>

          <li class="nav-item">

            <a class="nav-link" href="logout">

              <i class="ni ni-key-25 text-info"></i> Logout

            </a>

          </li>

        </ul>

      </div>

    </div>

  </nav>

  <!-- Main content -->

  <div class="main-content">

    <!-- Top navbar -->

    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">

      <div class="container-fluid">

        <!-- Brand -->

        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="./index">Dashboard</a>

        <!-- Form -->

          <div class="form-group mb-0">

            <div class="input-group input-group-alternative">

              <div class="input-group-prepend">

              </div>

            </div>

          </div>

        </form>

        <!-- User -->

        <ul class="navbar-nav align-items-center d-none d-md-flex">

          <li class="nav-item dropdown">

            <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

              <div class="media align-items-center">

                </span>

                <div class="media-body ml-2 d-none d-lg-block">

                  <span class="mb-0 text-sm  font-weight-bold"><?php echo htmlspecialchars($user->username) . '#' . htmlspecialchars($user->discriminator); ?></span>

                </div>

              </div>

            </a>

            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">

              <div class=" dropdown-header noti-title">

                <h6 class="text-overflow m-0">Welcome!</h6>

              </div>

              <div class="dropdown-divider"></div>

              <a href="logout" class="dropdown-item">

                <i class="ni ni-user-run"></i>

                <span>Logout</span>

              </a>

            </div>

          </li>

        </ul>

      </div>

    </nav>

    <!-- Header -->

    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">

      <div class="container-fluid">

        <div class="header-body">

          <!-- Card stats -->

          <div class="row">

            <div class="col-xl-3 col-lg-6">

            </div>

          </div>

        </div>

      </div>

    </div>

    <!-- Page content -->

    <div class="container-fluid mt--7">

      <div class="row">

      </div>

	<?php

					if( isset($_GET['success']) ) {

						echo '

						<div class="alert alert-success" role="alert">

						  <strong>Success!</strong> ' . htmlspecialchars(base64_decode($_GET['success'])) . '

						</div>

						';

					}

					if( isset($_GET['error']) ) {

						echo '

						<div class="alert alert-danger" role="alert">

						  <strong>Error!</strong> ' . htmlspecialchars(base64_decode($_GET['error'])) . '

						</div>

						';

					}

	?>

      <div class="row mt-5">

          <div class="card shadow">

            <div class="card-header border-0">

              <div class="row align-items-center">

                <div class="col">

                  <h3 class="mb-0">Servers</h3>

                </div>

              </div>

            </div>

            <div class="table-responsive">

              <!-- Projects table -->

              <table class="table align-items-center table-flush">

                <thead class="thead-light">

                  <tr>

                    <th scope="col">Name</th>

                    <th scope="col">RAM</th>
					<th scope="col">RAM used</th>

                    <th scope="col">Disk</th>
					<th scope="col">Disk used</th>

                    <th scope="col">CPU cores</th>
					<th scope="col">CPU used</th>
					
					<th scope="col">Server Status</th>

					<th scope="col">Actions</th>

                  </tr>

                </thead>

                <tbody>

				<?php

				$results = mysqli_query($conn, "SELECT * FROM servers WHERE owner_id='" . mysqli_real_escape_string($conn, $user->id) . "'");

				if( $results->num_rows !== 0 ) {

					 while($rowitem = mysqli_fetch_array($results)) {

						 $ch = curl_init("https://" . $ptero_domain . "/api/application/servers/" . $rowitem['pterodactyl_serverid']);

						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

						curl_setopt($ch, CURLOPT_HTTPHEADER, array(

							"Authorization: Bearer " . $ptero_key,

							"Content-Type: application/json",

							"Accept: Application/vnd.pterodactyl.v1+json"

						));

						$result = curl_exec($ch);

						curl_close($ch);

						 $result = json_decode($result, true);

						 $serverRAM = $result['attributes']['limits']['memory'] . " MB";

						 $serverDisk = $result['attributes']['limits']['disk'] . " MB";

						 $serverCores = $result['attributes']['limits']['cpu'] / 100;

						 $serverName = $result['attributes']['name'];

						echo "<tr>";

						echo "<td>" . htmlspecialchars($serverName) . "</td>";

						echo "<td>" . htmlspecialchars($serverRAM) . "</td>";
						
						$usedram_div_id = substr(str_shuffle(MD5(microtime())), 0, 14);
						echo "<td>" . '<div id="' . $usedram_div_id . '">0 MB</div>' . "</td>";

						echo "<td>" . htmlspecialchars($serverDisk) . "</td>";
						
						$useddisk_div_id = substr(str_shuffle(MD5(microtime())), 0, 14);
						echo "<td>" . '<div id="' . $useddisk_div_id . '">0 MB</div>' . "</td>";

						echo "<td>" . htmlspecialchars($serverCores) . "</td>";
						
						$usedcpu_div_id = substr(str_shuffle(MD5(microtime())), 0, 14);
						echo "<td>" . '<div id="' . $usedcpu_div_id . '">0%</div>' . "</td>";
						
						$serverstatus_div_id = substr(str_shuffle(MD5(microtime())), 0, 14);
						echo "<td>" . '<div id="' . $serverstatus_div_id . '"><strong><font color="red">Offline</font></strong></div>' . "</td>";

						echo "<td>" . '<a href="deleteserver?id=' . $rowitem['pterodactyl_serverid'] . '" class="btn btn-danger btn-sm" role="button">Delete</a> &nbsp; <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#changecpu" onclick="document.getElementById(\'changecpu_serverid\').value=\'' . $rowitem['pterodactyl_serverid'] . '\'">Change CPU</button> &nbsp; <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#changedisk" onclick="document.getElementById(\'changedisk_serverid\').value=\'' . $rowitem['pterodactyl_serverid'] . '\'">Change Disk</button> &nbsp; <a href="backup?serverid=' . $rowitem['pterodactyl_serverid'] . '" class="btn btn-info btn-sm" role="button">Backup Manager</a>' . "</td>";

						echo "</tr>";
						
						echo '
						<script>
							setInterval(function(){
								$.get("ajax?server_info&sid=' . $rowitem['pterodactyl_serverid'] . '", function(data, status){
									if( status == "success" ) {
										if( data["memory_used"] !== null && data["disk_used"] !== null && data["cpu_used"] !== null ) {
											document.getElementById("' . $usedram_div_id . '").innerHTML = data["memory_used"] + " MB";
											document.getElementById("' . $useddisk_div_id . '").innerHTML = data["disk_used"] + " MB";
											document.getElementById("' . $usedcpu_div_id . '").innerHTML = data["cpu_used"] + "%";
										} else {
											document.getElementById("' . $usedram_div_id . '").innerHTML =  "0 MB";
											document.getElementById("' . $useddisk_div_id . '").innerHTML = "0 MB";
											document.getElementById("' . $usedcpu_div_id . '").innerHTML = "0%";
										}
										if( data["server_state"] == null || data["server_state"] == "off" ) {
											document.getElementById("' . $serverstatus_div_id . '").innerHTML = "<strong><font color=\"red\">Offline</font></strong>";
										} else {
											document.getElementById("' . $serverstatus_div_id . '").innerHTML = "<strong><font color=\"green\">Online</font></strong>";
										}
									}										
								});
							}, 2000);
						</script>
						';

					}

				  } else {

				  }

				?>

                </tbody>

              </table>

            </div>

          </div>

      </div>

      <!-- Footer -->

      <footer class="footer">

        <?php include("templates/footer.php"); ?>

      </footer>

    </div>

  </div>

  <!-- Argon Scripts -->

  <!-- Core -->

  <script src="./assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Optional JS -->

  <script src="./assets/vendor/chart.js/dist/Chart.min.js"></script>

  <script src="./assets/vendor/chart.js/dist/Chart.extension.js"></script>

  <!-- Argon JS -->

  <script src="./assets/js/argon.js?v=1.0.0"></script>

  

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

	

	<!-- modal:changecpu -->

	<div id="changecpu" class="modal fade" role="dialog">

	  <div class="modal-dialog">



		<!-- Modal content-->

		<div class="modal-content">

		  <div class="modal-header">

			<h4 class="modal-title">Change Server CPU</h4>

		  </div>

		  <div class="modal-body">

				<form action="changecpu" method="get">

				<input type="hidden" name="id" id="changecpu_serverid" class="form-control">

				New server CPU cores: <input type="number" name="newcpu" class="form-control" min="1" value="1" required><br />

				<input type="submit" value="Change CPU" class="btn btn-success">

				</form>

		  </div>

		  <div class="modal-footer">

			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

		  </div>

		</div>



	  </div>

	</div>
	
	
	
	
	<!-- modal:changedisk -->

	<div id="changedisk" class="modal fade" role="dialog">

	  <div class="modal-dialog">



		<!-- Modal content-->

		<div class="modal-content">

		  <div class="modal-header">

			<h4 class="modal-title">Change Server Disk</h4>

		  </div>

		  <div class="modal-body">

				<form action="changedisk" method="get">

				<input type="hidden" name="id" id="changedisk_serverid" class="form-control">

				New server disk space (in MB): <input type="number" name="newdisk" class="form-control" min="1000" value="1000" required><br />

				<input type="submit" value="Change Disk" class="btn btn-success">

				</form>

		  </div>

		  <div class="modal-footer">

			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

		  </div>

		</div>



	  </div>

	</div>

	

	<?php

	  if( checklogin() == true ) {

		  $PlanExpiry = $conn->query("SELECT * FROM users WHERE discord_id='" . mysqli_real_escape_string($conn, $user->id) . "'")->fetch_assoc()['plan_expiry'];

		  if( $PlanExpiry == 0 ) {

			  $PlanExpiry = "Never";

		  } else {

			  $PlanExpiry = date('m/d/Y', $PlanExpiry);

		  }

		  echo '

		  	<!-- modal:logintopanel -->

			<div id="logintopanel" class="modal fade" role="dialog">

			  <div class="modal-dialog">



				<!-- Modal content-->

				<div class="modal-content">

				  <div class="modal-header">

					<h4 class="modal-title">Login to Panel</h4>

				  </div>

				  <div class="modal-body">

				  	<strong>Your Panel Username:</strong> ' . $pterodactyl_username . '<br />

					<strong>Your Panel Password:</strong> ' . $pterodactyl_password . '

					<hr>

					<a target="_blank" href="https://' . $ptero_domain . '/auth/login" class="btn btn-primary" role="button">Panel Login</a>

				  </div>

				  <div class="modal-footer">

					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

				  </div>

				</div>



			  </div>

			</div>

			

			<!-- modal:plan_info -->

			<div id="plan_info" class="modal fade" role="dialog">

			  <div class="modal-dialog">



				<!-- Modal content-->

				<div class="modal-content">

				  <div class="modal-header">

					<h4 class="modal-title">Plan Info</h4>

				  </div>

				  <div class="modal-body">

				  	<strong>You are currently on:</strong> ' . $level_data['title'] . '<br />

					<strong>Your plan gives you:</strong> ' . $level_data['ram_balance'] . ' MB RAM balance, ' . $level_data['max_servers'] . ' max servers, ' . $level_data['max_cores'] . ' max CPU cores, and ' . $level_data['max_disk'] . ' MB max disk.<br />

					<strong>Your plan will expire on:</strong> ' . $PlanExpiry . '

					<hr>

					You have additional <strong>' . $user_extra_ram . '</strong> MB RAM balance, <strong>' . $user_extra_servers . '</strong> servers. <strong>' . $user_extra_cores . '</strong> CPU cores, and <strong>' . $user_extra_disk . '</strong> MB disk.<br />

				  </div>

				  <div class="modal-footer">

					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

				  </div>

				</div>



			  </div>

			</div>

		  ';

	  }

	  ?>

	

	

</body>



</html>