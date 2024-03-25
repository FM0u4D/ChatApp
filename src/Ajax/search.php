<?php

session_start();


# Check if the user is logged in
if (isset($_SESSION['username']) && isset($_POST['key'])) {
	# Database connection file
	include '../conn.php';

	# Last seen (check whether searched user is online or offline)
	include "../inc/timeAgo.php";

	# Creating simple search algorithm :) 
	$key = "%{$_POST['key']}%";

	$sql = "SELECT * FROM users WHERE username LIKE ? OR name LIKE ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$key, $key]);

	if ($stmt->rowCount() > 0) {
		$users = $stmt->fetchAll();
		foreach ($users as $user) {
			if ($user['user_id'] == $_SESSION['user_id']) continue;
?>
			<li class="list-group-item" style="opacity:.8">
				<a href="chat.php?user=<?= $user['username'] ?>" class="d-flex justify-content-between align-items-center p-2">
					<style>
						.circle_mark::before {
							<?php if (last_seen($user['last_seen']) == "Active") {
								echo "background: lightgreen;animation: blink 2s ease-in-out infinite;";
							} else {
								echo "border: 2px red solid;";
							}
							?>
						}
					</style>
					<div class="circle_mark d-flex align-items-center">
						<img style="z-index: 2;height: 33px;width: 33px;" src="./uploads/profile_pic/<?= $user['p_p'] ?>" class="rounded-circle">
						<h3 class="fs-xs m-2">
							<?= $user['name'] ?>
						</h3>
					</div>
				</a>
			</li>
		<?php }
	} else { ?>
		<div class="alert alert-info text-center">
			<i class="fa fa-user-times d-block fs-big"></i>
			The user «<?= htmlspecialchars($_POST['key']) ?>» is not found !
		</div>
<?php	}
} else {
	header("Location: ../");
	exit;
}
