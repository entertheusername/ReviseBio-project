<?php
include("conn.php");
?>

<div class="profile-icon">
    <div id="sidepanel" class="sidepanel">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav('sidepanel')">×</a>
        <h2 id="margin">
            <span id="iconprofile" class="material-symbols-outlined" style="font-size: 48px;">
                account_circle
            </span>
            <b>&nbsp;&nbsp;Account Settings</b>
        </h2><br>
        <a href="javascript:void(0)" onclick="showPopup('popup0')">Username</a><br>
        <a href="javascript:void(0)" onclick="showPopup('popup1')">Email</a><br>
        <a href="javascript:void(0)" onclick="showPopup('popup2')">Password</button></a><br>
        <a href="javascript:void(0)" onclick="showPopup('popupdelete')">Delete Account</a><br>
        <hr>
        <br>
        <a href="javascript:void(0)" id="buttonpopup" onclick="showPopup('popuplogout')">Log Out</a>
    </div>
    <button id="sidepanelbtn" class="openbtn" onclick="openNav('sidepanel')">
        <span id="iconprofile" class="material-symbols-outlined" style="font-size: 48px;">
            account_circle
        </span>
    </button>
</div>

<?php
$interList = array("Username", "Email", "Password");
for ($i = 0; $i < sizeof($interList); $i++) {
    switch ($interList[$i]) {
        case 'Username':
            $type = 'text';
            break;
        case 'Email':
            $type = 'email';
            break;
        case 'Password':
            $type = 'password';
            break;
    }
    echo "
    <div id='popup" . $i . "' class='sidepanel-popup'>
        <div class='sidepanel-popup-content'>
            <p style='margin-top: 10%; font-size:large;'>Enter New " . $interList[$i] . ":</p>
            <form action='" . $_SERVER['PHP_SELF'] . "' method='post'>
                <input type='" . $type . "' name='barval' placeholder='Enter New " . $interList[$i] . "' style='width: 80%; margin-left: 10%; padding: 5px;'>
                <input type='hidden' name='barsel' value='" . $interList[$i] . "'>    
                <br><br>
                <button id='closePopup' name='submitchg' onclick='closePopup(`popup" . $i . "`)'>Ok</button>
            </form>
            <button id='closePopup' onclick='closePopup(`popup" . $i . "`)'>Back</button>
        </div>
    </div>";
}
?>

<?php
if (isset($_POST['submitchg'])) {
    $exe = $_POST['barsel'];
    switch ($exe) {
        case 'Username':
            if (empty($_POST['barval'])) {
                echo '<script>alert("Username is empty!");</script>';
                if($_SESSION['role'] == 'Teacher') {
                    echo "<script>window.location.href='../teacher/teach-main.php';</script>";
                } else {
                    echo "<script>window.location.href='../student/stu-main.php';</script>";
                }
            } else {
                try {
                    $userSql = "UPDATE users SET Username = '$_POST[barval]' WHERE UserID = {$_SESSION['mySession']}";
                    mysqli_query($con, $userSql);
                    echo '<script>alert("Updated!"); window.location.href="../logout.php"</script>';
                } catch (Exception) {
                    echo '<script>alert("Username already in use!");</script>';
                    if($_SESSION['role'] == 'Teacher') {
                        echo "<script>window.location.href='../teacher/teach-main.php';</script>";
                    } else {
                        echo "<script>window.location.href='../student/stu-main.php';</script>";
                    }
                }
            }

            break;
        case 'Email':
            if (empty($_POST['barval'])) {
                echo '<script>alert("Email is empty!");</script>';
                if($_SESSION['role'] == 'Teacher') {
                    echo "<script>window.location.href='../teacher/teach-main.php';</script>";
                } else {
                    echo "<script>window.location.href='../student/stu-main.php';</script>";
                }
            } else {
                try {
                    $userSql = "UPDATE users SET Email = '$_POST[barval]' WHERE UserID = {$_SESSION['mySession']}";
                    mysqli_query($con, $userSql);
                    echo '<script>alert("Updated!"); window.location.href="../logout.php"</script>';
                } catch (Exception) {
                    echo '<script>alert("Email already in use!");</script>';
                    if($_SESSION['role'] == 'Teacher') {
                        echo "<script>window.location.href='../teacher/teach-main.php';</script>";
                    } else {
                        echo "<script>window.location.href='../student/stu-main.php';</script>";
                    }
                }
            }

            break;
        case 'Password':
            if (strlen($_POST['barval']) > 8) {
                $hashedPass = password_hash($_POST['barval'], PASSWORD_DEFAULT);
                $userSql = "UPDATE users SET Password = '$hashedPass' WHERE UserID = {$_SESSION['mySession']}";
                mysqli_query($con, $userSql);
                echo '<script>alert("Updated!"); window.location.href="../logout.php"</script>';
            } else {
                echo '<script>alert("Passwords should be longer than 8 characters!");</script>';
                if($_SESSION['role'] == 'Teacher') {
                    echo "<script>window.location.href='../teacher/teach-main.php';</script>";
                } else {
                    echo "<script>window.location.href='../student/stu-main.php';</script>";
                }
            }

            break;
        default:
            echo '<script>alert("Update Failed!");</script>';
            break;
    }
}
?>

<!--popup window delete-->
<div id="popupdelete" class="general-popup">
    <div class="general-popup-content">
        <p style="margin-left: 20%;">Are you sure you want to delete account?!</p>
        <button onclick="window.location.href='../del-acc.php';">Yes</button>
        <button id="closePopup" onclick="closePopup('popupdelete')">No</button>
    </div>
</div>

<!--popup window logout-->
<div id="popuplogout" class="general-popup">
    <div class="general-popup-content">
        <p>Are you sure you want to logout?!</p>
        <button onclick="window.location.href='../logout.php';">Yes</button>
        <button id="closePopup" onclick="closePopup('popuplogout')">No</button>
    </div>
</div>