<?php

session_start();


# Check if the user is logged in
if (isset($_SESSION['username'])) {
    # Database connection file
    include '../conn.php';
    include '../inc/timeAgo.php';
    include '../inc/user.php';

    # Get the recipiant username from SESSION
    $recipiant_username = $_SESSION["recipiant_username"];

    # Getting User conversations
    $user_data = getUser($conn, $recipiant_username);

    if (last_seen($user_data['last_seen']) == "Active") { ?>
        <div class="online"></div>
        <small class="d-block p-1">Online</small>
    <?php
    } else if (last_seen($user_data['last_seen']) == "Busy") { ?>
        <div class="online-busy"></div>
        <small class="d-block p-1">Busy</small>
    <?php
    } else {
    ?>
        <small class="d-block p-1">
            Last seen: <?= last_seen($user_data['last_seen']) ?>
        </small>
<?php
    }
} else {
    header("Location: ../../");
    exit;
}
?>