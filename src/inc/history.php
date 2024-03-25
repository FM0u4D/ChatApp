<?php

session_start();

if (isset($_SESSION["username"])) {
    # Importing necessary functions from other files
    include '../conn.php';
    include '../inc/user.php';
    include '../inc/conversations.php';
    include '../inc/last_chat.php';
    include '../inc/timeAgo.php';

    # Getting User conversations
    $conversations = getConversation($_SESSION["user_id"], $conn);

    if (!empty($conversations)) {
        foreach ($conversations as $conversation) { ?>
            <li class="list-group-item" style="opacity:.6">
                <a href="chat.php?user=<?= $conversation['username'] ?>" class="d-flex justify-content-between align-items-center p-2" target="_self">
                    <div class="d-flex align-items-center">
                        <div class="sub_p_img p_img rounded-circle d-flex">
                            <!-- file_exists function will check from history.php, -->
                            <!-- but the img will be called from home.php -->
                            <img style="width:40px;height:40px;" src="./uploads/profile_pic/<?= file_exists("../../uploads/profile_pic/" . $conversation['p_p']) ? $conversation['p_p'] : "user-default.png" ?>">
                        </div>
                        <h3 class="fs-xs m-2">
                            <?= $conversation['name'] ?><br>
                            <p class="lastMsg mb-0 <?= (isset(lastChat($_SESSION['user_id'], $conversation['user_id'], $conn)[1]) ? lastChat($_SESSION['user_id'], $conversation['user_id'], $conn)[1] : 1) ? '' : 'notify' ?>">
                                <?= isset(lastChat($_SESSION['user_id'], $conversation['user_id'], $conn)[0]) ? lastChat($_SESSION['user_id'], $conversation['user_id'], $conn)[0] : '' ?>
                            </p>
                        </h3>
                    </div>

                    <div class="statusBall" title="<?= (last_seen_OnOff($conversation['last_seen']) == "Active") ? "On" : "Off" ?>line">
                        <!-- 
                                    This is where we will update the On/Off ball (status)
                             -->
                    </div>
                </a>
            </li>
<?php }
    }
} else {
    header("Location: ./");
    exit();
}
