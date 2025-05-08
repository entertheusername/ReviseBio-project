<!-- Rhys Khoo Ke TP076182 -->
<?php
include("../conn.php");
include("teach-session.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReviseBio</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../teacher/styling/teach-newq-quiznanaly-listing.css">
</head>

<body>
    <?php
    include("teach-header.php");
    ?>

    <!--quiz analytics-->
    <main>
    <div class='listing-container'>
        <div class='title-container'>
            <h2 class='quiz-title'>Your Quizzes</h2>
        </div>
        <div class='quiz-list'>
    <?php
    $quizSql = "SELECT * FROM quiz WHERE UserID={$_SESSION['mySession']}";
    $quizResult = mysqli_query($con, $quizSql);
    while($quizRow = mysqli_fetch_array($quizResult)){
        $quizCountSql = "SELECT COUNT(*) AS QuizCount FROM quizhistory WHERE QuizID={$quizRow['QuizID']}";
        $quizCountResult = mysqli_fetch_array(mysqli_query($con, $quizCountSql));
        echo "
            <a href='../teacher/teach-quiz-analytic.php?QuizID=" . $quizRow['QuizID'] . "'>
                <div class='quiz-item' id='analytic'>
                    <h2>" . $quizRow['Title'] . "</h2>
                    <p>Total Submissions: " . $quizCountResult['QuizCount'] . "  </p>
                </div>
            </a>";
    }

    ?>
        </div>
    </div>
    </main>

    <?php
    include("teach-footer.html");
    ?>

</body>

</html>