<?php
session_start();

# check if username & password  submitted
if (isset($_POST['username']) && isset($_POST['password'])) {
	# database connection file
	include '../conn.php';

	# get data from POST request and store them in var
	$password = $_POST['password'];
	$username = $_POST['username'];

	#simple form Validation
	if (empty($username)) {
		# Error message
		$em = "Username is required";

		# Redirect to 'index.php' and passing error message
		header("Location: ../../?error=$em");
	} else if (empty($password)) {
		# Error message
		$em = "Password is required";

		# Redirect to 'index.php' and passing error message
		header("Location: ../../?error=$em");
	} else {
		$sql  = "SELECT * FROM users WHERE username=?";
		$stmt = $conn->prepare($sql);
		$stmt->execute([$username]);

		# if the username is exist
		if ($stmt->rowCount() === 1) {
			# fetching user data
			$user = $stmt->fetch();

			# if both username's are strictly equal
			if (strtolower($user['username']) === strtolower($username)) {

				# verifying the encrypted password
				if (password_verify($password, $user['password'])) {

					# Successfully logged in
					# Creating the SESSION
					$_SESSION['user_id']  = $user['user_id'];
					$_SESSION['name']     = $user['name'];
					$_SESSION['username'] = $user['username'];
					$_SESSION['p_p']  = $user['p_p'];
					$_SESSION['last_seen']  = $user['last_seen'];

					# Redirect to 'home.php'
					header("Location: ../../home.php");
				} else {
					# Error message
					$em = "Incorect Username or password";

					# Redirect to 'index.php' and passing error message
					header("Location: ../../?error=$em");
				}
			} else {
				# Error message
				$em = "Incorect Username or password";
				
				# Redirect to 'index.php' and passing error message
				header("Location: ../../?error=$em");
			}
		}else {
			# Error message
			$em = "Account not existed !";
			
			# Redirect to 'index.php' and passing error message
			header("Location: ../../?error=$em");
			exit;
		}
	}
} else {
	header("Location: ../../");
	exit;
}
