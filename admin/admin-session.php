<!-- Shim Yong Lin TP076209 -->
<?php
session_start();
if (!isset($_SESSION['mySession'])) {
    // Redirect to login page if session is not set
    echo "<script>alert('Please login!');window.location.href='../nonuser/login.php';</script>";
    exit();
} else if ($_SESSION['role'] != 'Admin') {
    // Redirect to login page if session role doesnt match
    echo "<script>alert('You are not a admin!');window.location.href='../nonuser/login.php';</script>";
    exit();
}
?>