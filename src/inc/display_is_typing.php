<?php

session_start();

# Check if the user is logged in
if (isset($_SESSION['username']) && isset($_SESSION["recipiant_id"])) {

    # Database connection file
    include '../conn.php';

    # Get the current user_id from SESSION
    $from_id = $_SESSION["user_id"];
    # Get the recipiant's user_id from POST
    $to_id = $_SESSION["recipiant_id"];

    $sql = "SELECT `isTyping` FROM `chats` WHERE from_id=? AND to_id=? ORDER BY chat_id DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$to_id, $from_id]);

    if ($stmt->rowCount() == 1) {
        $conversation = $stmt->fetch();
        echo $conversation["isTyping"];
        #print_r($conversation);
    } else {
        echo 0;
    }
} else {
    header("Location: ../../");
    exit;
}
