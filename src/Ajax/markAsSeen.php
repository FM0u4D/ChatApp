<?php

session_start();


# Check if the user is logged in
if (isset($_SESSION['username']) && isset($_POST["id_recipient"])) {
    # Database connection file
    include '../conn.php';
    include '../inc/timeAgo.php';
    include '../inc/user.php';
    include '../inc/last_chat.php';

    # Get the logged in user's ID from SESSION
    $from_id = $_SESSION['user_id'];

    # Get the recipiant user's ID
    $to_id = $_POST["id_recipient"];

    $is_opened = lastChat($from_id, $to_id, $conn)[1];

    echo $is_opened;
} else {
    header("Location: ../../");
    exit;
}
?>