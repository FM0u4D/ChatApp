<?php

session_start();

if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {

?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="UTF-8">
		<title>Chat App - Home</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" href="./src/assets/img/logo.png">
		<link rel="stylesheet" href="./src/assets/css/font-awesome-all.6.5.1.min.css">
		<link rel="stylesheet" href="./src/assets/css/bootstrap5.0.1.min.css">
		<link rel="stylesheet" href="./src/assets/css/style.css">
		<link rel="stylesheet" href="./src/assets/css/mainChat.css">
		<link rel="stylesheet" href="./src/assets/css/landing.css">
		<link rel="stylesheet" href="./src/assets/css/home.css">
		<link rel="stylesheet" href="./src/assets/css/CustomScrol.css">
		<style>
			/*--------- Received message as blinked -----------*/
			.msg-received {
				background: #eb9941;
				animation: blinked 2s ease-in-out infinite;
			}

			/*========== Received message as blinked ============*/
		</style>
	</head>

	<body class="bkb d-flex justify-content-center align-items-center vh-100">
		<div class="bghome p-2 w-400 rounded shadow" style="height: 90vh">
			<div class="overflow-hidden" style="height: 100%;">
				<div class="profile_banner d-flex mb-3 p-3 bg-light justify-content-between align-items-center">
					<div class="p_img rounded-circle d-flex align-items-center">
						<label class="-label d-flex justify-content-center align-items-center" for="file">
							<i class="fa-solid fa-camera fa-beat-fade fa-lg" style="color:#FFF"></i>
						</label>
						<input id="file" type="file" accept="image/*" onchange="loadFile(event)" />
						<img id="p_pUpload" src="./uploads/profile_pic/<?= file_exists("./uploads/profile_pic/" . $_SESSION['p_p']) ? $_SESSION['p_p'] : "user-default.png" ?>" />
					</div>
					<h3 class="fs-xs m-2"><?= $_SESSION['name'] ?></h3>
					<a id="sign-out" href="./logout.php" class="btn btn-dark">Logout</a>
				</div>

				<div class="input-group mx-auto" style="width: 96%;">
					<input type="text" placeholder="Search..." id="searchText" class="form-control">
					<button class="btn btn-primary" id="serachBtn">
						<i class="fa fa-search"></i>
					</button>
				</div>

				<ul id="chatList" class="list-group mt-3 ms-1 pe-1">
					<!-- 
	
							This is where chat histories will be added
	
																				-->
				</ul>
				<ul id="searchList" class="list-group mt-3 ms-1 pe-1 d-none">
					<!-- 

							This is where searched profiles !

																				-->

				</ul>
			</div>
		</div>

		<div id="copyright">Copyright &copy; FM0u4D</div>

		<script src="./src/assets/js/jquery3.5.1.min.js"></script>

		<script>
			//To change color of status ball 
			function StatusBall() {
				$.get("./src/Ajax/On-Off-Ligne.php",
					function(data, status) {
						$(".statusBall").each(function(i, obj) {
							obj.innerHTML = JSON.parse(data)[i]
						})
					})
			}

			// Auto update last seen for logged in user
			function lastSeenUpdate() {
				$.get("./src/Ajax/update_last_seen.php");
			}

			// To add/remove scroll bar to chat History
			function AddRemove_Scroller() {
				if ($("#chatList")[0].scrollHeight > $("#chatList")[0].clientHeight) {
					$("#chatList").addClass("scroller")
				} else {
					$("#chatList").removeClass("scroller")
				}
			}

			// Load image when uploading and store it to uploads folder
			//// We can declare a function as variable like so :
			let loadFile = function(event) {
				let image = document.getElementById("p_pUpload");
				let files = document.getElementById("file").files;

				if (files.length > 0) {
					var formData = new FormData();
					formData.append("file", files[0]);
					var xhttp = new XMLHttpRequest();

					// Set POST method and ajax file path
					xhttp.open("POST", "./src/Ajax/update_photo_profile.php", true);

					// Call on request changes state
					xhttp.onreadystatechange = function() {
						/*	
						+-------+----------------------------------
						| State | Description
						+-------+----------------------------------
						| 0     | The request is not initialized
						| 1     | The request has been set up
						| 2     | The request has been sent
						| 3     | The request is in process
						| 4     | The request is complete
						+------+----------------------------------
						*/
						if (this.readyState == 4 && this.status == 200) {
							var response = this.responseText;
							if (response == 1) {
								if (event.target.files && event.target.files[0]) {
									image_filename = URL.createObjectURL(event.target.files[0]);
									/*
									image.onload = () => {
										URL.revokeObjectURL(image.src); // No longer needed, free memory
									}
									*/
									image.src = image_filename; // Set src to blob url
								}
							} else {
								// A notification Error would be better
								alert(`File not uploaded - ${response} `);
							}
						}
					};

					// Send request with data
					xhttp.send(formData);
				} else {
					alert("Please select a file"); // A notification warning would be better
				}
			}

			// List out all history conversations and define it as function for later use
			function history() {
				$.get('./src/Ajax/history.php',
					function(data, status) {
						$("#chatList").html(data)
						$("#searchList").addClass("d-none")
						$("#chatList").removeClass("d-none")
						AddRemove_Scroller()
					});
			}

			// Getting searched user(s)
			function search(searchText) {
				$.post('./src/Ajax/search.php', {
						key: searchText
					},
					function(data, status) {
						$("#searchList").html(data);
						$("#chatList").addClass("d-none")
						$("#searchList").removeClass("d-none")
						AddRemove_Scroller();
					});
			}

			// Update received messages
			function lastMsgUpdate() {
				$.get("./src/Ajax/update_last_message.php"),
					function(data, status) {
						//console.info(data)
						console.info("Updated successfully !")
					}
			}

			$(document).ready(function() {
				// Auto update last seen every 0.5 sec
				lastSeenUpdate()
				setInterval(lastSeenUpdate, 500)

				// Call back all the opened conversations
				history()

				//Auto Check Last Seen every 1 sec [ To fix later ]
				StatusBall()
				setInterval(StatusBall, 1000)

				//Auto Check whether an user has received a message every half sec [ To fix later ]
				lastMsgUpdate()
				setInterval(lastMsgUpdate, 500)				

				// Search directly within the input SEARCH
				$("#searchText").on("input", function() {
					let searchText = $("#searchText").val()
					if (searchText.length > 0) {
						search(searchText) 
					} else {
						history()
					}
				});

				// Search using the button : Optional
				// Since we're using AJAX here, the result will show up immediately
				$("#serachBtn").on("click", function() {
					let searchText = $("#searchText").val();
					if (searchText.length > 0) {
						search(searchText)
					} else {
						history()
					}
				});
			});
		</script>
	</body>

<?php
} else {
	session_start();
	session_unset();
	session_destroy();

	header("Cache-Control: no-cache, must-revalidate");
	header("Content-Type: application/xml; charset=utf-8");
	header("Location: ./");
	exit;
}
?>

	</html>