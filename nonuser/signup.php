<!-- Tsai Chin Xian TP075570 -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReviseBio</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../nonuser/styling/auth.css">
    <style>
        button {
            width: 100%;
            height: 40px;
            border: none;
            outline: none;
            border-radius: 40px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <!-- header -->
    <?php
    include("nonuser-header.html");
    ?>

    <!--body-->
    <section>
        <div class="auth-box" style="width: 350px; height: 550px;">
            <form action="signup.php" method="post">
                <h2>Sign Up</h2>
                <div class="input-box">
                    <input type="text" name="user_name" placeholder=" " required>
                    <label>Username</label>
                </div>
                <div class="input-box">
                    <input type="email" name="user_email" placeholder=" " required>
                    <label>Email</label>
                </div>
                <div class="input-box">
                    <input type="password" name="user_password" placeholder=" " required>
                    <label>Password</label>
                </div>
                <div class="input-box">
                    <input type="password" name="user_cpassword" placeholder=" " required>
                    <label>Confirm Password</label>
                </div>
                <?php echo '<input type="hidden" name="user_role" value="' . $_GET['Role'] . '">'; ?>
                <button type="submit" name="register">Register</button>
                <div class="page-link">
                    <p>Have an account?<a href="login.php">Login</a></p>
                </div>
            </form>
        </div>
    </section>

    <!-- signup function -->
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include("../conn.php");

        $userName = $_POST['user_name'];
        $userEmail = $_POST['user_email'];
        $userPass = $_POST['user_password'];
        $userCPass = $_POST['user_cpassword'];
        $role = $_POST['user_role'];

        if ($userCPass !== $userPass) {
            echo '<script>alert("Passwords are not the same!"); window.location.href = "../nonuser/signup.php?Role=' . $role . '";</script>';
        } elseif (strlen($userPass) < 8) {
            echo '<script>alert("Passwords should be longer than 8 characters!"); window.location.href = "../nonuser/signup.php?Role=' . $role . '";</script>';
        } else {
            $hashedPass = password_hash($userPass, PASSWORD_DEFAULT);
            try {
                $sql = 'INSERT INTO users (Username, Email, Password, Role)' . "
                VALUES ('$userName','$userEmail','$hashedPass', '$role')";

                mysqli_query($con, $sql);
                echo '<script>alert("Registered!"); window.location.href = "../nonuser/login.php"; </script>';
            } catch (Exception $e) {
                echo '<script>alert("Email or Username is already in use!"); window.location.href = "../nonuser/signup.php?Role=' . $role . '";</script>';
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