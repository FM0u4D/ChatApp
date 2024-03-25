<?php

session_start();


# check if the user is logged in
if (isset($_SESSION['username']) && isset($_POST['isTyping'])) {
	# database connection file
	include '../conn.php';

	$user  = $_SESSION['user_id'];
	$isTyping = $_POST['isTyping'];

	$sql = "UPDATE `chats` SET isTyping=? WHERE from_id=? ORDER BY chat_id DESC LIMIT 1";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$isTyping, $user]);

} else {
	header("Location: ../../");
	exit;
}
