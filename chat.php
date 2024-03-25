<?php
session_start();

if (isset($_SESSION['username'])) {
	# Database connection file
	include './src/conn.php';
	include "./src/inc/conf.php";
	include './src/inc/user.php';
	include './src/inc/chat.php';
	include './src/inc/start.php';
	include './src/inc/opened.php';
	include './src/inc/timeAgo.php';
	include './src/inc/conversations.php';

	if (!isset($_GET['user'])) {
		header("Location: ./home.php");
		exit;
	}

	# Getting User data
	$chatWith = getUser($conn, $_GET['user']);

	if (empty($chatWith)) {
		header("Location: ./home.php");
		exit;
	}
	// Store recipiant data in SESSION for later use
	$_SESSION["recipiant_id"] = $chatWith["user_id"];
	$_SESSION["recipiant_name"] = $chatWith["name"];
	$_SESSION["recipiant_username"] = $chatWith["username"];
	$_SESSION["recipiant_pp"] = $chatWith["p_p"];
	$_SESSION["recipiant_last_seen"] = $chatWith["last_seen"];

	# Getting conversation between the current user and the chosen recipiant 
	$convo = getConversation($_SESSION["recipiant_id"], $conn);

	# Check first if the users have already spoken
	# In case this is the first time the current user start this conversation
	if (empty($convo)) {
		CreateConversation($conn, $_SESSION['user_id'], $_SESSION["recipiant_id"]);
	}

	# Getting current chat from user to selected recipient 
	$chats = getChats($conn, $_SESSION['user_id'], $_SESSION["recipiant_id"]);

	# Once you refresh/open a conversation every message will be set as opened
	opened($_SESSION["recipiant_id"], $conn, $chats);
?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Chat App | <?= $_SESSION["recipiant_name"] ?></title>
		<link rel="icon" href="./src/assets/img/logo.png">
		<link rel="stylesheet" href="./src/assets/css/font-awesome-all.6.5.1.min.css">
		<link rel="stylesheet" href="./src/assets/css/bootstrap5.0.1.min.css">
		<link rel="stylesheet" href="./src/assets/css/style.css">
		<link rel="stylesheet" href="./src/assets/css/mainChat.css">
		<link rel="stylesheet" href="./src/assets/css/landing.css">
		<link rel="stylesheet" href="./src/assets/css/CustomScrol.css">
		<style>
			html {
				margin-left: calc(100vw - 100%);
				margin-right: 0;
			}

			.not-allowed {
				cursor: not-allowed !important;
			}
		</style>
	</head>

	<body class="bkb d-flex justify-content-center align-items-center vh-100">
		<div class="bgchat w-400 shadow p-4 rounded" style="height: 90vh;">
			<div class="d-flex align-items-center justify-content-between mb-2">
				<div id="back_chat" class="bg-dark w-10 d-flex justify-content-center align-item-center rounded-circle">
					<a href="./home.php" class="fs-4 link-light" title="Back" onclick="is_typing(0);">
						<i class="fa-solid fa-chevron-left"></i><!-- &#8249; -->
					</a>
				</div>

				<h3 class="d-flex flex-column justify-content-start align-items-center fs-sm m-2">
					<?= $_SESSION["recipiant_name"] ?> <br>
					<div id="UserStatus" class="d-flex align-items-center" title="Status">
						<!-- 
								STATUS TO BE UPDATED HERE !
						 -->
					</div>
				</h3>

				<div class="p_img rounded-circle">
					<img src="./uploads/profile_pic/<?= $_SESSION["recipiant_pp"] ?>" alt="Profile Image">
				</div>
			</div>

			<div style="height:55vh;position:relative">
				<div id="chatBox" class="shadow p-4 rounded d-flex flex-column h-100">
					<div id="loading" class="d-flex justify-content-center align-item-center p-2">
						<i class="fa fa-refresh fa-2x fa-spin" style="color: var(--bs-primary);" aria-hidden="true"></i>
					</div>
					<!-- 
	
								This is where messages will be added !
	
																					-->

					<p id="typing" class="messagetxt ltext align-self-start mb-1 p-2 fade-in-up d-none">
						<img src="./src/assets/img/loading_dotted.svg" alt="Typing" />
					</p>
				</div>
				<div id="envelopeChat"></div>
			</div>
			<div class="input-group mt-2">
				<textarea cols="3" id="message" class="form-control" placeholder="Send a message..."></textarea>
				<button class="btn btn-secondary" id="sendBtn" style="opacity: .8;" disabled>
					<i class="fa fa-paper-plane"></i>
				</button>
			</div>
		</div>

		<div id="copyright">
			Copyright &copy; FM0u4D
		</div>

	</body>
	<script src="./src/assets/js/jquery3.5.1.min.js"></script>

	<script>
		// Declare a global variable as a flag to scrollDown ONCE when the `typing` block is showing up
		let c=1
		// Display typing animation
		function displayTyping() {
			$.get("./src/Ajax/display_is_typing.php",
				function(data, status) {
					//console.info(`ajax get ${data}`)
					// 0 if there is no typing otherwise 1
					if (data == 1) {
						$("#chatBox").addClass("pb-0")
						$("#typing").removeClass("d-none")
						if (c==1){
							scrollDown()
							c = 0
						}
					} else {
						$("#chatBox").removeClass("pb-0")
						$("#typing").addClass("d-none")
						c = 1
					}
					AddRemove_Scroller()
				}
			)
		}

		// Updating isTyping when called upon from keys strokes
		function is_typing(bool) {
			$.post("./src/Ajax/update_is_typing.php", {
					isTyping: bool
				},
				function(data, status) {
					console.info("isTyping updated successfully")
				}
			)
		}

		// Auto check if message is opened/seen
		function CheckSeen() {
			$.post("./src/Ajax/markAsSeen.php", {
					id_recipient: <?= $_SESSION["recipiant_id"] ?>
				},
				function(data, status) {
					if (data == 1) {
						$(".CMtail").removeClass("unread")
						$(".CMtail").addClass("read")

						$(".CMsupport").removeClass("unread")
						$(".CMsupport").addClass("read")
					}
				}
			)
		}

		// Charge Messages
		function chargeMessages(limit = <?= INITIAL_LOAD_MSG ?>) {
			let nbr_of_msg = $("#chatBox").children().length - 2
			$("#chatBox").removeClass("pt-0")
			$("#loading").addClass("d-none")
			$.post("./src/Ajax/chargeMessage.php", {
				id_2: <?= $_SESSION["recipiant_id"] ?>,
				loaded_msg: (nbr_of_msg != "Nan" ? nbr_of_msg : 0),
				more_msg: limit
			}, function(data, status) {
				if (status == "success") {
					$(data).insertAfter("#loading"); // Insert message after the `loading` block
					AddRemove_Scroller()
					if ($("#chatBox").children().length - 2 == limit) {
						//To scroll down immediately once you open a chat
						scrollDown()
					}
				} else {
					$("#loading i").removeClass("fa-spin");
				}
			})
		}

		// Send Message and displays it to the right
		function SendMessage() {
			message = $("#message").val();
			if (message == '') return;
			$.post("./src/Ajax/insert.php", {
					message: message,
					to_id: <?= $_SESSION["recipiant_id"] ?>
				},
				function(data, status) {
					$("#message").val("");
					$(data).insertBefore("#typing"); // Insert message before the `typing` block
					// This line is set in case the conversation is new 
					if ($("#start_convo").length > 0) $("#start_convo").remove();
					AddRemove_Scroller();
					scrollDown();
				});
		};

		//Check last seen for logged in user 
		function CheckLastSeen() {
			$.get("./src/Ajax/CheckLastSeen.php",
				function(data, status) {
					// Change the satus of the logged in user
					$("#UserStatus").html(data)
				});
		}

		//Auto update last seen for logged in user
		function lastSeenUpdate() {
			$.get("./src/Ajax/update_last_seen.php");
		}

		// Auto refresh / reload
		function fetchData() {
			$.post("./src/Ajax/getMessage.php", {
					id_2: <?= $_SESSION["recipiant_id"] ?>
				},
				function(data, status) {
					$(data).insertBefore("#typing"); // Insert message before the `typing` block
					if (data != "") {
						AddRemove_Scroller();
						scrollDown();
					}
				});
		}

		//Scroll down function
		function scrollDown() {
			let chatBox = document.getElementById('chatBox')
			chatBox.scrollTop = chatBox.scrollHeight
		}

		//Scroll up function
		function scrollUp() {
			let chatBox = document.getElementById('chatBox')
			chatBox.scrollTop = 0
		}

		// To add/remove scroll bar to chat History
		function AddRemove_Scroller() {
			if ($("#chatBox")[0].scrollHeight >= $("#chatBox")[0].clientHeight) {
				$("#chatBox").addClass("scroller")
			} else {
				$("#chatBox").removeClass("scroller")
			}
		}

		// To change Color of sending button from grey to blue in case the user is typing
		function BrightsUp(event) {
			let temp_len;
			let msg_len = document.getElementById("message").value

			if (event.keyCode == 46 || event.keyCode == 8) {
				if (msg_len.length != 0) {
					temp_len = msg_len.length - 1
				}
			} else {
				temp_len = msg_len.length + 1
			}

			if (temp_len == undefined) {
				//[From grey to blue] In case the user still pressing the back space when there's no more characters to be deleted !
				$("#sendBtn").removeClass("btn-primary")
				$("#sendBtn").addClass("btn-secondary")
				$("#sendBtn").addClass("not-allowed")
				$("#sendBtn").attr("disabled")
				is_typing(0)
				//console.info("Message length !== 0")
			} else if (temp_len != 0) {
				//Change the color of sending button [From grey to blue]
				$("#sendBtn").removeClass("btn-secondary")
				$("#sendBtn").addClass("btn-primary")
				$("#sendBtn").removeClass("not-allowed")
				$("#sendBtn").removeAttr("disabled");
				is_typing(1)
				//console.info("Message length != 0")
			} else {
				//Change the color of sending button [From blue to grey]
				$("#sendBtn").removeClass("btn-primary")
				$("#sendBtn").addClass("btn-secondary")
				$("#sendBtn").addClass("not-allowed") // Not working on disabled button
				$("#sendBtn").attr("disabled")
				is_typing(0)
				//console.info("Message length == 0")
			}
			scrollDown()
			//console.info(`Message length => ${temp_len}`)
		}

		userHasScrolled = false;
		$(document).ready(function() {
			// Get the fisrt 10 messages 
			chargeMessages()

			//Auto update messages every 0.3 sec
			fetchData();
			setInterval(fetchData, 300);

			//Auto check if the recipiant is `typing` a message
			setInterval(displayTyping, 300)

			//Auto update last seen every 0.3 sec
			lastSeenUpdate();
			setInterval(lastSeenUpdate, 300);

			//Auto Check Last Seen every 1 sec
			CheckLastSeen();
			setInterval(CheckLastSeen, 1000);

			//Auto update every 0.1 sec
			CheckSeen();
			setInterval(CheckSeen, 100);

			// Charging more messages
			$("#chatBox").scroll(function(event) {
				let top_conv = ($("#chatBox").children().length > 2 ? $("#chatBox").children()[1].id : 'QlqChose')
				let ScrollTop_coord = $("#chatBox").scrollTop();
				// When scrolled UP
				if (ScrollTop_coord == 0) {
					if (top_conv == "start_convo") {
						// Break the process once we reach the TOP of conversation
						// Exactely when we find the child node "#start_convo" which is placed 
						// in the parentNode "#chatBox" in the 2th postion [1]
						$("#loading").addClass("d-none")
						$("#chatBox").removeClass("pt-0")
						return;
					}
					$("#chatBox").addClass("pt-0")
					$("#loading").removeClass("d-none")
					// Call the function to reload more messages (10 at max)
					chargeMessages(<?= STEP_LOAD_MSG ?>)
				}
			})


			// Reload more messages in case of lost connexion 
			$("#loading i").on("click", function() {
				$("#loading").addClass("fa-spin")
				setInterval(chargeMessages(<?= STEP_LOAD_MSG ?>), 1200)
			})

			// Sending message with a button
			$("#sendBtn").click(function() {
				SendMessage();
			})

			// Click Event on pressing ENTER to trigger the sending 
			// & call is_typing function that change isTyping status
			$("#message").keydown(function(event) {
				//Get the length of message textarea
				len_message = $("#message").val().length + 1
				BrightsUp(event);
				if (event.keyCode == 13 && !event.shiftKey) { // Shift + ENTER
					SendMessage()
				}
			})
		});
	</script>

	</html>
<?php
} else {
	header("Location: ./");
	exit;
}
?>