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
    <link rel="stylesheet" href="../student/styling/stu-quiz-start.css">
</head>

<body>
    <?php
        include("stu-header.php");
    ?>

    <!-- Quiz Content -->
    <?php
    $id = $_GET['QuizID'];
    $quizSql = "SELECT * FROM quiz WHERE QuizID = '$id'";
    $result = mysqli_fetch_assoc(mysqli_query($con, $quizSql));
    echo "<main class='quiz-container'>
            <section class='quiz-description'>
                <h2> " . $result['Title'] . " </h2>
                <hr style='width: 100%;'>
                <h3> " . $result['QuizDesc'] . " </h3>
            </section>
            <section class='start-quiz'>
                <a href='../student/stu-quiz-progress.php?QuizID=" . $id . "' class='quiz-start-button'>Start Quiz</a>
            </section>
        </main>";
    ?>
    
    <?php
        include("stu-footer.html");
    ?>
</body>

</html>