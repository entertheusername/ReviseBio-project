<?php
session_start();
if (!isset($_SESSION['mySession'])) {
    // Redirect to login page if session is not set
    echo "<script>alert('Please login!');window.location.href='../nonuser/login.php';</script>";
    exit();
} else if ($_SESSION['role'] != 'Student') {
    // Redirect to login page if session role doesnt match
    echo "<script>alert('You are not a student!');window.location.href='../nonuser/login.php';</script>";
    exit();
}
?>