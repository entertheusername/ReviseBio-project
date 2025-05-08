<!-- Shim Yong Lin TP076209 -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Selection</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../admin/styling/admin-web-analytic.css">
</head>

<body>
    <?php
    include("admin-header.html");
    include("../conn.php");
    include("../admin/admin-session.php");
    $totalusers = "SELECT Count(*) as userscount FROM users";
    $usersresult = mysqli_query($con, $totalusers);
    if ($usersresult) {
        $usersrow = mysqli_fetch_assoc($usersresult);
        $userCount = $usersrow['userscount'];
    }

    $totalnote = "SELECT COUNT(*) AS notecount FROM note";
    $noteresult = mysqli_query($con, $totalnote);
    if ($noteresult) {
        $noterow = mysqli_fetch_assoc($noteresult);
        $noteCount = $noterow['notecount'];
    }

    $totalFC = "SELECT COUNT(*) AS FCCount FROM FCgroup";
    $FCresult = mysqli_query($con, $totalFC);
    if ($FCresult) {
        $FCrow = mysqli_fetch_assoc($FCresult);
        $FCCount = $FCrow['FCCount'];
    }

    $totalQH = "SELECT COUNT(*) AS QHCount FROM quizhistory";
    $QHresult = mysqli_query($con, $totalQH);
    if ($QHresult) {
        $QHrow = mysqli_fetch_assoc($QHresult);
        $QHCount = $QHrow['QHCount'];
    }

    $totalQuiz = "SELECT COUNT(*) AS QuizCount FROM quiz";
    $Quizresult = mysqli_query($con, $totalQuiz);
    if ($Quizresult) {
        $Quizrow = mysqli_fetch_assoc($Quizresult);
        $QuizCount = $Quizrow['QuizCount'];
    }

    mysqli_close($con);
    ?>

    <!-- Content -->
    <main>
        <div class="dashboard-containertop">
            <div class="dashboard-item">
                <h3>Total Signed Up Users</h3>
                <p><?php echo $userCount; ?></p>
            </div>
            <div class="dashboard-item">
                <h3>Total Amount of Notes</h3>
                <p><?php echo $noteCount; ?></p>
            </div>
            <div class="dashboard-item">
                <h3>Amount of Flashcard Group</h3>
                <p><?php echo $FCCount; ?></p>
            </div>
        </div>
        <div class="dashboard-containerbottom">
            <div class="dashboard-itembottom">
                <h3>Amount of Quizzes Taken</h3>
                <p><?php echo $QHCount; ?></p>
            </div>
            <div class="dashboard-itembottom">
                <h3>Amount of Quizzes Created</h3>
                <p><?php echo $QuizCount; ?></p>
            </div>
        </div>
    </main>

    <?php
    include("admin-footer.html");
    ?>
</body>

</html>