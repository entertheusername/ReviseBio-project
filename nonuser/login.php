<!-- Tsai Chin Xian TP075570 -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReviseBio</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../nonuser/styling/auth.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
</head>

<body>
    <!-- header -->
    <?php
    include("nonuser-header.html");
    ?>

    <!-- body -->
    <section>
        <div class="auth-box" style="width: 350px; height: 450px;">
            <form action="login.php" method="post">
                <h2>Login</h2>
                <div class="input-box">
                    <input type="email" name="user_email" placeholder=" " value="<?php echo isset($_COOKIE['userEmail']) ? $_COOKIE['userEmail'] : ''; ?>" required>
                    <span class="icon"> <span class="material-symbols-outlined">mail</span> </span>
                    <label>Email</label>
                </div>
                <div class="input-box">
                    <input type="password" name="user_password" placeholder=" " value="<?php echo isset($_COOKIE['userPassword']) ? $_COOKIE['userPassword'] : ''; ?>" required>
                    <span class="icon"> <span class="material-symbols-outlined">lock</span> </span>
                    <label>Password</label>
                </div>
                <div class="remember">
                    <label><input type="checkbox" name="remember_me">Remember me</label>
                </div>
                <button type="submit" name="login">Login</button>
                <div class="page-link">
                    <p>Don't have an account?<a href="role-select.php">Sign up</a></p>
                </div>
            </form>
        </div>
    </section>

    <!-- login function -->
    <?php
    session_start();
    include("../conn.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $userEmail = mysqli_real_escape_string($con, $_POST['user_email']);
        $userPass = mysqli_real_escape_string($con, $_POST['user_password']);

        $sql = "SELECT * FROM users WHERE Email='$userEmail'";
        $result = mysqli_query($con, $sql);

        $row = mysqli_fetch_array($result);
        $rowCount = mysqli_num_rows($result);

        if ($rowCount !== 1) {
            echo '<script>alert("Email doesnt exist.");</script>';
        } else {
            if (password_verify($userPass, $row['Password'])) {
                $_SESSION['mySession'] = $row['UserID'];
                $_SESSION['name'] = $row['Username'];
                $_SESSION['role'] = $row['Role'];

                if (isset($_POST['remember_me'])) {
                    setcookie('userEmail', $userEmail, time() + (30 * 24 * 60 * 60), '/');
                    setcookie('userPassword', $userPass, time() + (30 * 24 * 60 * 60), '/');
                }

                //Role redirect
                switch ($row['Role']) {
                    case 'Admin':
                        header("location: ../admin/admin-web-analytic.php");
                        break;
                    case 'Student':
                        header("location: ../student/stu-main.php");
                        break;
                    case 'Teacher':
                        header("location: ../teacher/teach-main.php");
                        break;
                    default:
                        echo '<script>alert("Role not recognized.");</script>';
                        break;
                }
            } else {
                echo '<script>alert("Incorrect password.");</script>';
            }

            mysqli_close($con);
        }
    }
    ?>

    <!-- footer -->
    <?php
    include("nonuser-footer.html");
    ?>
</body>

</html>