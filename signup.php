<?php
session_start();
if (!isset($_SESSION['username'])) {
?>

	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Chat App - Sign Up</title>
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
		<div class="w-400 p-4 shadow rounded">
			<form method="post" action="./src/Auth/signup.php" enctype="multipart/form-data">
				<div class="d-flex justify-content-center align-items-center flex-column">
					<img src="./src/assets/img/SignupLogo.png" class="w-25">
					<h3 class="display-4 fs-1 text-center">Sign Up</h3>
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
							<strong class="font__weight-semibold">
								<?php echo htmlspecialchars($_GET['error']); ?>
							</strong>
						</div>
					</div>
				<?php }
				$name = (isset($_GET['name'])) ? $_GET['name'] : '';
				$username = (isset($_GET['username'])) ? $_GET['username'] : '';
				?>

				<div class="mb-2">
					<label class="form-label">Username</label>
					<input type="text" class="form-control" name="username" value="<?= $username ?>" autocomplete="off" required>
				</div>

				<div class="mb-2">
					<label class="form-label">Full Name</label>
					<input type="text" name="name" value="<?= $name ?>" class="form-control" autocomplete="off" required>
				</div>

				<div class="mb-2">
					<label class="form-label">Password</label>
					<input type="password" class="form-control" name="password" autocomplete="off" required>
				</div>

				<div class="mb-2">
					<label class="form-label">Profile Picture</label>
					<input type="file" class="form-control" name="pp">
				</div>

				<div class="d-flex justify-content-between align-items-center mb2">
					<button type="submit" class="btn btn-primary">Sign Up</button>
					<button id="login" type="button" class="btn">Login</button>
				</div>
			</form>
		</div>
		<div id="copyright">
			Copyright &copy; FM0u4D
		</div>
	</body>
	<script>
		$(document).ready(function() {
			$("#login").on("click", function() {
				location = "./"
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