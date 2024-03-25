<?php

session_start();

# check if the user is logged in
if (isset($_SESSION['username'])) {
	if (isset($_POST['id_2']) && isset($_POST["loaded_msg"]) && isset($_POST["more_msg"])) {
		# database connection file
		include '../conn.php';
		include '../inc/conf.php';
        include "../inc/chat.php";

		$id_1  = $_SESSION['user_id'];
		$id_2  = $_POST['id_2'];

        $loaded_msg = $_POST["loaded_msg"];
        $more_msg = $_POST["more_msg"];

        if ($more_msg != INITIAL_LOAD_MSG ) {
            $chats = getChats($conn, $id_1, $id_2, $loaded_msg, $more_msg);
        }else {
            $chats = getChats($conn, $id_1, $id_2);
        }


        # Looping through the chats
        if (!empty($chats)) {
            foreach ($chats as $chat) { ?>
                <p class="messagetxt <?php echo ($chat['from_id'] == $_SESSION['user_id']) ? "rtext align-self-end" : "ltext align-self-start" ?> mb-1">
                    <?= $chat['message'] ?>
                    <small class="d-block fw-bold">
                        <?= date("Y-M-d") > date("Y-M-d", strtotime($chat['created_at'])) ? date("l d-M-Y H:i", strtotime($chat['created_at'])) : date("H:i", strtotime($chat['created_at'])) ?>
                        <?php if ($chat['from_id'] == $_SESSION['user_id']) : ?>
                            <span class="checkmark">
                                <span class="CMtail read"></span>
                                <span class="CMsupport read"></span>
                            </span>
                        <?php endif ?>
                    </small>
                </p>
            <?php 
            }
        } else { 
            if ($loaded_msg > 0) { ?>
                <div id="start_convo" class="alert alert-info text-center">
                    <small class="d-flex justify-content-center align-items-center fw-bold mb-3">
                        <label class="alert-info mb-0">Created at : 
                            <?php 
                                # Last element in the conv which is the first message ever sent
                                $nbr = getNumberOfRows($conn, $id_1, $id_2);
                                $First_Chat = getChats($conn, $id_1, $id_2, $nbr - 1, 1);
                                echo $First_Chat[0]['created_at'];
                            ?>
                        </label>
                    </small>
                    <i class="fa fa-comments d-block fs-big"></i>
                    The beggining of the conversation
                </div>
            <?php }else { ?>
                <div id="start_convo" class="alert alert-info text-center">
                    <i class="fa fa-comments d-block fs-big"></i>
                    No messages yet, Start a new conversation
                </div>
        <?php } 
        }
	}
} else {
	header("Location: ../../");
	exit;
}
