<?php
session_start();

include("global.php");

include("config.php");
include("join_for_resources.php");

if( checklogin() == true ) {

	$user = $_SESSION['discord_user'];

} else {

	header("Location: auth");

	die();

}

include("plans.php");
//ShowError("Webhosting will be back soon!");

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

  

  <title>FalixNodes: Webhosting</title>

  

  	  <script>

		  $(window).on('load', function(){

			$("#createSiteBox").load("createsite");

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

            <a class="nav-link" href="#" data-toggle="modal" data-target="#createsite">

              <i class="ni ni-fat-add text-blue"></i> Create Site

            </a>

          </li>

          <li class="nav-item">

            <a class="nav-link" href="#" data-toggle="modal" data-target="#plan_info">

              <i class="ni ni-collection text-orange"></i> My Plan

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

      <div class="row mt-5">

          <div class="card shadow">

            <div class="card-header border-0">

              <div class="row align-items-center">

                <div class="col">

                  <h3 class="mb-0">Sites</h3>

                </div>

              </div>

            </div>

            <div class="table-responsive">

              <!-- Projects table -->

              <table class="table align-items-center table-flush">

                <thead class="thead-light">

                  <tr>

                    <th scope="col">Domain</th>
					
					<th scope="col">Nameservers</th>

					<th scope="col">Actions</th>

                  </tr>

                </thead>

                <tbody>

				<?php

				$results = mysqli_query($conn, "SELECT * FROM sites WHERE owner_id='" . mysqli_real_escape_string($conn, $user->id) . "'");

				if( $results->num_rows !== 0 ) {

					 while($rowitem = mysqli_fetch_array($results)) {
						 
					    $nameservers = $conn->query("SELECT * FROM webhosting_nameservers WHERE node='" . mysqli_real_escape_string($conn, $rowitem['node']) . "'")->fetch_assoc();
						$nameservers = $nameservers['ns1'] . ", " . $nameservers['ns2'];

						echo "<tr>";

						echo "<td>" . htmlspecialchars($rowitem['domain']) . "</td>";
						
						echo "<td>" . htmlspecialchars($nameservers) . "</td>";

						echo "<td>" . '<form target="_blank" action="https://' . $rowitem['node'] . '.falixnodes.host:8443/login_up.php" method="post"><input type="hidden" name="login_name" value="' . $rowitem['plesk_username'] . '"><input type="hidden" name="passwd" value="' . $rowitem['plesk_password'] . '"><input type="hidden" name="locale_id" value="default"><input type="submit" class="btn btn-info btn-sm" value="Login to Plesk"></form> <br /> <a href="deletesite?id=' . $rowitem['id'] . '" class="btn btn-danger btn-sm" role="button">Delete</a>' . "</td>";
						
						echo "</tr>";

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

  

  	<!-- modal:createsite -->

	<div id="createsite" class="modal fade" role="dialog">

	  <div class="modal-dialog">



		<!-- Modal content-->

		<div class="modal-content">

		  <div class="modal-header">

			<h4 class="modal-title">Create Site</h4>

		  </div>

		  <div class="modal-body" id="createSiteBox">

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