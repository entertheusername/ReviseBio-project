<?php
include('conn.php');
session_start();
$userSql = "DELETE FROM users WHERE UserID = {$_SESSION['mySession']}";
mysqli_query($con, $userSql);
echo '<script>alert("Successfully Deleted Account!");window.location.href="nonuser/login.php";</script>';
