<?php

session_start();

# Check if the user is logged in
if (isset($_SESSION['username'])) {
	if (isset($_POST['message']) && isset($_POST['to_id'])) {
		# Gatabase connection file
		include '../conn.php';

		# Get data from XHR request and store them in var
		$message = $_POST['message'];
		$to_id = $_POST['to_id'];

		# Get the logged in user's username from the SESSION
		$from_id = $_SESSION['user_id'];

		$sql = "INSERT INTO chats (from_id, to_id, message) VALUES (?, ?, ?)";
		$stmt = $conn->prepare($sql);
		$res  = $stmt->execute([$from_id, $to_id, $message]);

		# if the message inserted
		if ($res) {
			// Setting up the time Zone
			// It Depends on your location or your P.C settings
			define('TIMEZONE', 'Africa/Casablanca');
			date_default_timezone_set(TIMEZONE);
			$time = date("H:i");

			# Check if this is the first Conversation between them then add it to the table
			$sql2 = "SELECT * FROM conversations WHERE (user_1=? AND user_2=?) OR (user_2=? AND user_1=?)";
			$stmt2 = $conn->prepare($sql2);
			$stmt2->execute([$from_id, $to_id, $from_id, $to_id]);

			if ($stmt2->rowCount() == 0) {
				# Insert them into conversations table 
				$sql3 = "INSERT INTO conversations(user_1, user_2) VALUES (?, ?)";
				$stmt3 = $conn->prepare($sql3);
				$stmt3->execute([$from_id, $to_id]);
			}
?>

			<p class="messagetxt rtext align-self-end mb-1">
				<?= $message ?>
				<small class="d-block fw-bold">
					<?= $time ?>
					<span class="checkmark">
						<span class="CMtail unread"></span>
						<span class="CMsupport unread"></span>
					</span>
				</small>
			</p>
<?php
		}
	}
} else {
	header("Location: ../../");
	exit;
}
