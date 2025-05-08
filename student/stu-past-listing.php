<!-- Woon Wei Jie TP076183 -->
<?php
include("../conn.php");
include("../student/stu-session.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReviseBio</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../student/styling/stu-notenpast-listing.css">
</head>

<body>
    <?php
        include("stu-header.php")
    ?>

    <!-- Past Attempts Section -->
    <main class="notes-container">
        <div>
            <section class="notes-section">
                <h2>Past Attempts</h2>
                <?php
                $id = $_GET['QuizID'];
                date_default_timezone_set('Asia/Kuala_Lumpur');
                $pAttSql = "SELECT * FROM quizhistory WHERE UserID={$_SESSION['mySession']} AND QuizID=$id";
                $result = mysqli_query($con, $pAttSql);
                while ($row = mysqli_fetch_array($result)) {
                    $timestamp = $row['TimeStamp'];
                    $malayDate = date('d/m/Y', strtotime($timestamp));
                    $malayTime = date('H:i', strtotime($timestamp));
                    echo "
                    <a href='../student/stu-past-attempt.php?QhisID=" . $row['QHisID'] . "&QuizID=" . $id . "' class='note-buttonstudy'>
                    <span>Date:" . $malayDate . "Time: " . $malayTime . "</span>
                    <span style='float: right;'>Score: " . round($row['Score']*100) . "%</span>
                    </a>";
                }
            echo "
            </section>
            <section class='start-quiz'>
                    <a href='../student/stu-quiz-progress.php?QuizID=" . $id . "' class='quiz-start-button'>Start Quiz</a>
            </section>
            ";
            ?>
        </div>
    </main>

    <?php
        include("stu-footer.html");
    ?>
</body>

</html>