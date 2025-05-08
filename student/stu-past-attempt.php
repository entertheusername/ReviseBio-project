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
    <link rel="stylesheet" href="../student/styling/stu-quiz-general.css">
</head>

<body>
    <?php
        include("stu-header.php");
    ?>
    
    <!-- Content -->
    <?php
    $quizID = $_GET['QuizID'];
    $quizSql = "SELECT * FROM quiz WHERE QuizID = '" . $quizID . "';";
    $quizResult = mysqli_query($con, $quizSql);
    echo"
        <div class='back-button-container'>
            <h2 class='quiz-title1' style='margin-right: 0;'>Attempt of " . mysqli_fetch_array($quizResult)['Title'] . "</h2>
        </div>";
    ?>

    <input type="hidden" name="QuizID" value="<?php echo htmlspecialchars($_GET['QuizID']); ?>">
    <?php
    $quizID = $_GET['QuizID'];
    $quizSql = "SELECT *
                FROM question
                WHERE QuizID = '" . $quizID . "';";
    $quizResult = mysqli_query($con, $quizSql);
    while ($quesRow = mysqli_fetch_array($quizResult)) {
        $quesID = $quesRow['QuesID'];
        $quesSql = "SELECT *
                    FROM answer
                    WHERE QuesID = '" . $quesID . "';";
        $quesResult = mysqli_query($con, $quesSql);
        $qhisID = $_GET['QhisID'];
        $pastSql = "SELECT * FROM `attempt` att 
                     JOIN `answer` a ON att.AnsID = a.AnsID
                     WHERE att.QHisID = $qhisID AND att.QuesID=$quesID";
        $pastResult = mysqli_query($con, $pastSql);
        $pastRow = mysqli_fetch_array($pastResult);
        switch ($quesRow['Type']) {
            case 'Mcq':
                echo "
                <div class='quiz-container1'>
                    <div class='quiz-header'>";
                    if (!empty($quesRow['Media_Url'])){
                        echo "<img src='" . $quesRow['Media_Url'] . "' alt='Quiz Illustration' class='quiz-image'>";
                    };
                echo "
                        <p class='question-description'>" . $quesRow['Ques'] . "</p>
                    </div> 
                    <div class='answer-container'>";
                while ($ansRow = mysqli_fetch_array($quesResult)) {
                echo    "<label class='answer-choice'";
                        if(($ansRow['AnsID'] == $pastRow['AnsID'])&&$pastRow['Is_Correct']!=1){
                            echo"style='background-color: #eb2d2d;'";
                        } elseif ($ansRow['Is_Correct']==1){
                            echo"style='background-color: #a1eb4e;'";
                        }
                        echo ">
                            <input type='radio' name='answers[" . $quesRow['QuesID'] . "]' value='" . $ansRow['AnsID'] . "'" . ($ansRow['AnsID'] == $pastRow['AnsID'] ? 'checked' : '') . " disabled>
                            " . $ansRow['Answer'] . "
                        </label>";
                };
                echo "<div class='teacher-explanation'>" . $quesRow['Note'] . "</div>";
                echo"    </div>
                    </div>";
                break;
            case 'Truefalse':
                echo "
                <div class='quiz-container1'>
                    <div class='quiz-header'>";
                    if (!empty($quesRow['Media_Url'])){
                        echo "<img src='" . $quesRow['Media_Url'] . "' alt='Quiz Illustration' class='quiz-image'>";
                    };
                echo "
                            <p class='question-description'>" . $quesRow['Ques'] . "</p>
                    </div>
                    <div class='answer-options'>";
                while ($ansRow = mysqli_fetch_array($quesResult)) {
                echo   "<label ";
                        if(($ansRow['AnsID'] == $pastRow['AnsID'])&&$pastRow['Is_Correct']!=1){
                            echo"style='background-color: #eb2d2d;'";
                        } elseif ($ansRow['Is_Correct']==1){
                            echo"style='background-color: #a1eb4e;'";
                        }
                        echo ">
                            <input type='radio' name='answers[" . $quesRow['QuesID'] . "]' value='" . $ansRow['AnsID'] . "'  disabled>
                            " . $ansRow['Answer'] . "
                        </label>";
                }
                echo "<div class='teacher-explanation'>" . $quesRow['Note'] . "</div>";
                echo"    </div>
                </div>";
                break;
            case 'Short':
                echo "
                <div class='quiz-container1'>
                    <div class='quiz-header'>";
                    if (!empty($quesRow['Media_Url'])){
                        echo "<img src='" . $quesRow['Media_Url'] . "' alt='Quiz Illustration' class='quiz-image'>";
                    }
                echo "
                            <p class='question-description'>" . $quesRow['Ques'] . "</p>
                    </div>";
                    $ansRow = mysqli_fetch_array($quesResult);
                    $ansText = trim(str_replace(["\r", "\n"], '', $ansRow['Answer']));
                    $pastText = trim(str_replace(["\r", "\n"], '', $pastRow['Ans_Txt']));
                    echo "
                        <textarea class='answer-box' name='answers[" . $quesRow['QuesID'] . "]'";
                        if($ansText != $pastText){
                            echo"style='background-color: #eb2d2d;'";
                        } else{
                            echo"style='background-color: #a1eb4e;'";
                        }
                        echo " disabled>{$pastRow['Ans_Txt']}</textarea>";
                echo "<div class='teacher-explanation'>" . $quesRow['Note'] . "</div>";
                echo "</div>";
                break;
            default:
                echo "error";
                break;
        }
    }

    echo "
        <div class='button-container' style='justify-content: center;'>
            <button name='quizform' onclick='location.href=`../student/stu-past-listing.php?QuizID=" . $quizID . "`;'  style='width: 100%;'>Back to Attempt List</button>
        </div>";
        ?>
    
    <?php
        include("stu-footer.html");
    ?>
</body>

</html>