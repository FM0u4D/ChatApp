<?php
session_start();
if (!isset($_SESSION['username'])) {
?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Chat App - Login</title>
		<link rel="icon" href="./src/assets/img/logo.png">
		<link rel="stylesheet" href="./src/assets/css/bootstrap5.0.1.min.css">
		<link rel="stylesheet" href="./src/assets/css/style.css">
		<link rel="stylesheet" href="./src/assets/css/landing.css">
		<link rel="stylesheet" href="./src/assets/css/all-min.css">
		<link rel="stylesheet" href="./src/assets/css/font-awesome-animation.css">
		<link rel="stylesheet" href="./src/assets/css/NotifStyling.css">
		<script src="./src/assets/js/jquery3.5.1.min.js"></script>
		<script src="./src/assets/js/all-min.js"></script>
	</head>

	<body class="bkb d-flex justify-content-center align-items-center vh-100">
		<div class="w-400 p-5 shadow rounded">
			<form method="post" action="./src/Auth/auth.php">
				<div class="d-flex justify-content-center align-items-center flex-column">
					<img src="./src/assets/img/logo.png" class="w-25">
					<h3 class="display-4 fs-1 fw-6 text-center">LOGIN</h3>
				</div>
				<?php if (isset($_GET['error'])) { ?>
					<div class="col-sm-12">
						<div class="alert fade alert-simple alert-danger alert-dismissible text-left font__family-montserrat font__size-16 font__weight-light brk-library-rendered rendered show" role="alert" data-brk-library="component__alert">
							<button type="button" class="close font__size-18" data-dismiss="alert">
								<span class="sr-only">Close</span>
								<span aria-hidden="true">
									<i class="fa fa-times danger "></i>
								</span>
							</button>
							<i class="start-icon far fa-times-circle faa-pulse animated"></i>
							<strong class="font__weight-semibold"><?php echo htmlspecialchars($_GET['error']); ?></strong>
						</div>
					</div>
				<?php } ?>

				<?php if (isset($_GET['success'])) { ?>
					<div class="col-sm-12">
						<div class="alert alert-simple alert-success text-left font__family-montserrat font__size-16 font__weight-light alert-dismissible fade show slideIn" role="alert">
							<button type="button" class="close font__size-24" data-dismiss="alert">
								<span class="sr-only">Close</span>
								<span aria-hidden="true">
									<a>
										<i class="fa fa-times greencross"></i>
									</a>
								</span>
							</button>
							<i class="start-icon far fa-check-circle faa-tada animated"></i>
							<strong class="font__weight-semibold">Well done !</strong><br>
							<?php echo htmlspecialchars($_GET['success']); ?>
						</div>
					</div>
				<?php } ?>
				
				<div class="mb-3">
					<label class="form-label">
						Username</label>
					<input type="text" class="form-control" name="username">
				</div>

				<div class="mb-3">
					<label class="form-label">Password</label>
					<input type="password" class="form-control" name="password">
				</div>

				<div class="d-flex justify-content-between align-items-center mb3">
					<button type="submit" class="btn btn-primary">LOGIN</button>
					<button id="signup" type="button" class="btn">Sign Up</button>
				</div>
			</form>
		</div>
		<div id="copyright">
			<p>Copyright &copy; FM0u4D</p>
		</div>
	</body>

	<script>
		$(document).ready(function() {
			$("#signup").on("click", function() {
				location = "./signup.php"
			})
		})
	</script>

	</html>
<?php
} else {
	header("Location: ./home.php");
	exit;
}
?>