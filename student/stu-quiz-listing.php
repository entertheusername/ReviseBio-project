<!-- Woon Wei Jie TP076183 -->
<?php
include("../student/stu-session.php");
include("../conn.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReviseBio</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../student/styling/stu-quiznfc-listing.css">
</head>

<body>
    <?php
        include("stu-header.php");
    ?>

<main class="main-body">
    <!-- Content Choosing Quiz-->
    <div class="list-selection-container">
        <?php
        $quizSql = "SELECT * FROM quiz";
        $quizResult = mysqli_query($con, $quizSql);
        while ($row = mysqli_fetch_array($quizResult)) {
            $id = $row['QuizID'];
            $quizSql = "SELECT COUNT(QuizID) AS QuesCount FROM question WHERE QuizID='$id'";
            $quesCount = mysqli_fetch_assoc(mysqli_query($con, $quizSql));
            $qhisSql = "SELECT * FROM quizhistory WHERE UserID={$_SESSION['mySession']} AND QuizID = $id";
            $qhisResult = mysqli_query($con, $qhisSql);
            $qhisCount = mysqli_num_rows($qhisResult);
            echo "<a href='";
            if ($qhisCount !== 0) {
                echo "../student/stu-past-listing.php?QuizID=$id";
            } else {
                echo "../student/stu-quiz-start.php?QuizID=$id";
            };
            echo "' class='list-button'>
                <h2>" . $row['Title'] . "</h2>
                <hr style='width: 100%;'>
                <h3>Number of Questions: " . $quesCount['QuesCount'] . "</h3>
                <hr style='width: 80%;'>
                <p>" . $row['QuizDesc'] . "</p>
             </a>";
        }
        ?>
    </div>
    </div>
</main>

    <?php
        include("stu-footer.html");
    ?>
</body>

</html>