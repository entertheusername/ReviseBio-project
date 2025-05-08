<!-- Rhys Khoo Ke TP076182 -->
<?php

use function PHPSTORM_META\type;

include("teach-session.php");
include("../conn.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReviseBio-Question Analytics</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../teacher/styling/teach-quiz-analytic.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
</head>

<body>
    <?php
    include("../teacher/teach-header.php");
    ?>

    <main>
        <?php
        echo "
        <div class='back-button-div'>
            <button class='back-button' onclick='location.href=`../teacher/teach-quiz-analytic.php?QuizID=" . $_GET['QuizID'] . "`;'>Back to Analytics</button>
        </div>";
        ?>
        <div class="questionanalycont">
            <!-- Student Info Section -->
            <?php
            $stuSql = "SELECT * FROM quizhistory qh 
                        RIGHT JOIN users u ON qh.UserID=u.UserID 
                        WHERE QHisID={$_GET['QhisID']}";
            $stuResult = mysqli_query($con, $stuSql);
            $stuRow = mysqli_fetch_array($stuResult);
            echo "
            <div class='student-info'>
                <div>Student Username: " . $stuRow['Username'] . "</div>
                <div>Score: " . round($stuRow['Score'] * 100) . "%</div>
            </div>";
            $attSql = "SELECT qh.Score, att.Ans_Txt, q.Ques, q.Type, a.Answer, a.Is_Correct FROM quizhistory qh 
                        RIGHT JOIN attempt att ON qh.QHisID=att.QHisID 
                        RIGHT JOIN question q ON att.QuesID=q.QuesID
                        RIGHT JOIN answer a ON att.AnsID=a.AnsID 
                        WHERE qh.QHisID={$_GET['QhisID']}";
            $attResult = mysqli_query($con, $attSql);
            while ($attRow = mysqli_fetch_array($attResult)) {
                switch ($attRow['Type']) {
                    case 'Short':
                        echo "
                        <div class='questions'>
                            <div class='question-box'>
                                <div class='question-text'>" . $attRow['Ques'] . "</div>
                                <div class='indicatorbox ";
                        $ansText = trim(str_replace(["\r", "\n"], '', $attRow['Answer']));
                        $pastText = trim(str_replace(["\r", "\n"], '', $attRow['Ans_Txt']));
                        if ($ansText != $pastText) {
                            echo "wrongans";
                        } else {
                            echo "correctans";
                        }
                        echo "'></div>
                            </div>
                        </div>";
                        break;
                    default:
                        echo "
                        <div class='questions'>
                            <div class='question-box'>
                                <div class='question-text'>" . $attRow['Ques'] . "</div>
                                <div class='indicatorbox ";
                        if ($attRow['Is_Correct'] != 1) {
                            echo "wrongans";
                        } elseif ($attRow['Is_Correct'] == 1) {
                            echo "correctans";
                        }
                        echo "'></div>
                            </div>
                        </div>";
                        break;
                }
            }
            ?>
        </div>
    </main>

    <?php
    include("../teacher/teach-footer.html");
    ?>
</body>

</html>