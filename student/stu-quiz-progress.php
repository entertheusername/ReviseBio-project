<!-- Woon Wei Jie TP076183 -->
<?php
include("../student/stu-session.php");
include("../conn.php");
ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReviseBio</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../student/styling/stu-quiz-general.css">
    <script src="../student/stu-quiz-progress.js"></script>
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
            <a href='../student/stu-quiz-listing.php'>
                <button class='back-to-quiz'>Back to Quiz</button>
            </a>
            <h2 class='quiz-title1'>" . mysqli_fetch_array($quizResult)['Title'] . "</h2>
        </div>";
    ?>

    <form class="quizform" name="quizform" action="stu-quiz-progress.php" method="post">
    <input type="hidden" name="QuizID" value="<?php echo ($_GET['QuizID']); ?>">
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
                echo    "<label class='answer-choice'>
                            <input type='radio' name='answers[" . $quesRow['QuesID'] . "]' value='" . $ansRow['AnsID'] . "' onchange='updateSummary(" . $quesRow['QuesID'] . ")' required>
                            " . $ansRow['Answer'] . "
                        </label>";
                };
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
                echo   "<label>
                            <input type='radio' name='answers[" . $quesRow['QuesID'] . "]' value='" . $ansRow['AnsID'] . "' onchange='updateSummary(" . $quesRow['QuesID'] . ")' required>
                            " . $ansRow['Answer'] . "
                        </label>";
                }
                echo"    </div>
                </div>";
                break;
            case 'Short':
                echo "
                <div class='quiz-container1'>
                    <div class='quiz-header'>";
                    if (!empty($quesRow['Media_Url'])){
                        echo "<img src='" . $quesRow['Media_Url'] . "' alt='Quiz Illustration' class='quiz-image'>";
                    };
                echo "
                            <p class='question-description'>" . $quesRow['Ques'] . "</p>
                    </div>
                        <textarea class='answer-box' name='answers[" . $quesRow['QuesID'] . "]' placeholder='Write your answer...' oninput='updateTextareaSummary(" . $quesRow['QuesID'] . ")' required></textarea>
                </div>";
                break;
            default:
                echo "error";
                break;
        }
    }


    ?>
    </form>
    <div class="button-container" style="justify-content: center;">
        <button name="quizform" onclick="hideForm()"  style="width: 100%;">Submit Answers</button>
    </div>
    
    <!-- Live Summary -->
    <div name="ansum" id="ansum" class="quiz-container1">
        <div class="quiz-header">
            <p class="question-description">Quiz Submission</p>
        </div>
        <div class="quiz-results">
            <?php
            $quizSql = "SELECT * 
                        FROM question 
                        WHERE QuizID = '" . $quizID . "';";
            $result = mysqli_query($con, $quizSql);
            $count = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "
                <div class='result-item'>
                <span class='question-number'>Question " . $count . "</span>
                <span id='summary-" . ($row['QuesID']) . "' class='question-status'>Not Answered</span>
                </div>
                ";
                $count++;
            }
            ?>
        </div>

        <div class="button-container">
            <button onclick="showForm()">Back</button>
            <button onclick="showForm(); submitForm();" id="submitquiz">Submit Quiz</button>
        </div>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['answers'])) {
            $timeStamp = date('Y-m-d H:i:s');
            $score = 0;
            $quizID = $_POST['QuizID'];
            $qhisSql = "INSERT INTO quizhistory (QuizID, UserID, Score,TimeStamp) VALUES ('$quizID', '{$_SESSION['mySession']}', $score,'$timeStamp')";
            mysqli_query($con, $qhisSql);
            $qhisID = mysqli_insert_id($con);

            $answers = $_POST['answers'];
            foreach ($answers as $quesID => $res) {
                if (!is_numeric($res)){
                    $getAnsIDSql = "SELECT *
                                    FROM answer
                                    WHERE QuesID = '" . $quesID . "';";
                    $AnsID = mysqli_fetch_assoc(mysqli_query($con, $getAnsIDSql))['AnsID'];
                    $attSql = "INSERT INTO attempt (QuesID, AnsID, Ans_Txt, QHisID) VALUES ('$quesID', '$AnsID', '$res', '$qhisID')";
                    mysqli_query($con, $attSql);
                } else {
                    $attSql = "INSERT INTO attempt (QuesID, AnsID, QHisID) VALUES ('$quesID', '$res', '$qhisID')";
                    mysqli_query($con, $attSql);
                }
            }

            function calScore() {
                global $con, $qhisID, $quizID;
                $mcqTfSql = "SELECT COUNT(Is_Correct) as Correct FROM `attempt` att 
                             JOIN `answer` a ON att.AnsID = a.AnsID
                             WHERE att.QHisID = $qhisID AND Ans_Txt = '' AND a.Is_Correct = 1;";
                $mcqTfResult = mysqli_query($con, $mcqTfSql);
                $mcqTfScore = mysqli_fetch_array($mcqTfResult)['Correct'];

                $shortSql = "SELECT COUNT(*) AS Correct FROM `attempt` att 
                             JOIN `answer` a ON att.AnsID = a.AnsID
                             WHERE att.QHisID = $qhisID AND 
                             TRIM(REPLACE(REPLACE(a.Answer, '\r', ''), '\n', '')) = TRIM(REPLACE(REPLACE(att.Ans_Txt, '\r', ''), '\n', ''));";
                $shortResult = mysqli_query($con, $shortSql);
                $shortScore = mysqli_fetch_array($shortResult)['Correct'];

                $quesCountSql = "SELECT COUNT(QuizID) AS QuesCount FROM question WHERE QuizID='$quizID'";
                $quesCount = mysqli_fetch_assoc(mysqli_query($con, $quesCountSql));

                $score = ($mcqTfScore + $shortScore) / $quesCount['QuesCount'];

                $scoreSql = "UPDATE `quizhistory` 
                             SET Score = $score
                             WHERE `QHisID` = $qhisID;";
                mysqli_query($con, $scoreSql);
            }
            calScore();
            header('Location:../student/stu-quiz-progresstolisting.php');
            exit;
        }
    }
    ?>

    <?php
        include("stu-footer.html");
    ?>
</body>

</html>