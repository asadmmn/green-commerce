<?php
include '../includes/conn.php';
session_start();

// Check if the user session is set and is an array
if(isset($_SESSION['user']) && is_array($_SESSION['user'])) {
    // Assign the user session to the $user variable
    $user = $_SESSION['user'];
} else {
    // Handle the case when the user session is not set or is not an array
    // Redirect the user to the homepage or display an error message
    // header('location: ../index.php');
    exit();
}

// At this point, you can access the user's information directly from the $user variable

// Remember to properly sanitize and validate user input to prevent security vulnerabilities
?>
