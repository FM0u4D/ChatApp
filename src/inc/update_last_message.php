<?php

session_start();

if (isset($_SESSION["username"])) {
    # Database connection file & useful functions
    include "../conn.php";
    include "../inc/conversations.php";
    include "../inc/last_chat.php";

    $user_id = $_SESSION['user_id'];

    $conversations = getConversation($user_id, $conn);
    print_r($conversations);
    echo $conv["username"];
    exit;
    $history = array();

    foreach($conversations as $conv) {
        $message = array();
        $userData = getUser($conn, $conv["username"]);
        $LastMsg = lastChat($user_id, $userData["user_id"], $conn)[0];
        $isOpened = lastChat($user_id, $userData["user_id"], $conn)[1];

        array_push($message, $LastMsg, $isOpened);
        array_push($history,$message);
    }
    print_r($history);
}else {
    header("Location: ../../");
    exit;
}

?>


<!-- To make message bold in case just been received -->
<!--?= (isset(lastChat($_SESSION['user_id'], $conversation['user_id'], $conn)[1]) ? lastChat($_SESSION['user_id'], $conversation['user_id'], $conn)[1] : 1) ? '' : 'notify' ?-->
<!-- .msg-received :  This css class is for blinking -->