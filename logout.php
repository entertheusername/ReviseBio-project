<?php
session_start();
session_destroy();
echo '<script>alert("Successfully Logged Out!"); window.location.href="nonuser/login.php";</script>';
?>
