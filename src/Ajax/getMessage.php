<?php

session_start();

# check if the user is logged in
if (isset($_SESSION['username'])) {
	if (isset($_POST['id_2'])) {
		# database connection file
		include '../conn.php';

		$id_1  = $_SESSION['user_id'];
		$id_2  = $_POST['id_2'];
		$opened = 0;

		$sql = "SELECT * FROM chats WHERE to_id=? AND from_id= ? ORDER BY chat_id";
		$stmt = $conn->prepare($sql);
		$stmt->execute([$id_1, $id_2]);

		if ($stmt->rowCount() > 0) {
			$chats = $stmt->fetchAll();
			# looping through the chats
			foreach ($chats as $chat) {
				if ($chat['opened'] == 0) {
					$opened = 1;
					$chat_id = $chat['chat_id'];

					$sql2 = "UPDATE chats SET opened = ? WHERE chat_id = ?";
					$stmt2 = $conn->prepare($sql2);
					$stmt2->execute([$opened, $chat_id]);
?>
					<p class="messagetxt ltext align-self-start mb-1">
						<?= $chat['message'] ?>
						<small class="d-block fw-bold">
							<?= date("Y-M-d") > date("Y-M-d", strtotime($chat['created_at'])) ? date("l d-M-Y H:i", strtotime($chat['created_at'])) : date("H:i", strtotime($chat['created_at'])) ?>
						</small>
					</p>
<?php
				}
			}
		}
	}
} else {
	header("Location: ../../");
	exit;
}
