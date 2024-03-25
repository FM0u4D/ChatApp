<?php

session_start();

# Database connection file
include '../conn.php';
include '../inc/photo_profile.php';

if (isset($_FILES['file']['name'])) {
    // File name
    $filename = $_FILES['file']['name'];

    // Location
    $location = '../../uploads/profile_pic/' . $filename;

    // File extension
    $file_extension = pathinfo($location, PATHINFO_EXTENSION);
    $file_extension = strtolower($file_extension);

    // Allowed extensions
    $valid_ext = array("jpg", "png", "jpeg", "gif");

    $response = 0;
    if (in_array($file_extension, $valid_ext)) {
        // Upload file
        if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
            
            # Get the logged in user's username from SESSION
            $id = $_SESSION['user_id'];
            
            # Update the filename of profile image into the DB 
            $response = 1;
            updatePhotoProfile($id, $filename, $conn); # To see through later !!!
            
        }
    }
    echo $response;
    exit;
}

