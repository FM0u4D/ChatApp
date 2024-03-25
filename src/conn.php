<?php 

# server name
$host = "localhost";
# user name
$user = "root";
# password
$pass = '';

# database name
$db_name = "chat_app_db";

#creating database connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
	echo "Connection failed : ". $e->getMessage();
}
