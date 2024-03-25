<?php

session_start();


# Check if the user is logged in
if (isset($_SESSION['username'])) {
	# Database connection file
	include '../conn.php';
	include '../inc/timeAgo.php';
	include '../inc/conversations.php';

	# Get the logged in user's username from SESSION
	$id = $_SESSION['user_id'];

	# Getting User conversations
	$_users = getConversation($id, $conn);

	$status_array = [];
	foreach($_users as $_user) {
		if (last_seen_OnOff($_user['last_seen']) == "Active") {
			array_push($status_array, "<div class=\"online\"></div>");
		} else {
			array_push($status_array, "<div class=\"offline\"></div>");
		}
	}
	echo json_encode($status_array);
} else {
	header("Location: ../../");
	exit;
}
?>