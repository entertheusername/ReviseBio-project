<!-- Rhys Khoo Ke TP076182 -->
<?php
include("teach-session.php");
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
    include("teach-header.php");
    ?>

    <!--content-->
    <main>
        <div class="back-button-div">
            <button class="back-button" onclick="location.href='../teacher/teach-quiz-analytic-listing.php';">Back to Quizzes List</button>
        </div>
        <div class="questionanalycont">
            <div class="top">
                <!--student avergae score-->
                <?php
                $avgSql = "SELECT AVG(Score) AS AvgScore
                           FROM quizhistory
                           WHERE QuizID={$_GET['QuizID']}
                          ";
                $avgResult = mysqli_fetch_array(mysqli_query($con, $avgSql));
                echo "
                <div class='top-content'>Students Average Score: " . round($avgResult['AvgScore'] * 100) . "%</div>";
                ?>
                <!--dropdown-->
                <select class="top-content" id="viewby" onchange="selectOption(this.value)">
                    <option value="question" selected>View by Question</option>
                    <option value="student">View by Students</option>
                </select>
            </div>
            <div class="view-container" id="view-container">
                <!-- Content goes here -->
            </div>
        </div>
    </main>

    <?php
    include("teach-footer.html");
    ?>

    <!--dropdown-->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            selectOption('question');
        });

        function selectOption(optionType) {
            const viewContainer = document.getElementById('view-container');
            if (optionType === 'question') {
                viewContainer.innerHTML = `
                <?php
                $quizID = $_GET['QuizID'];

                $quesSql = "SELECT * FROM question WHERE QuizID='$quizID'";
                $quesResult = mysqli_query($con, $quesSql);

                while ($row = mysqli_fetch_array($quesResult)) {
                    $quesType = $row['Type'];
                    switch ($quesType) {
                        case 'Short':
                            $amtSql = "SELECT COUNT(*) AS Amount FROM `attempt` att
                                           RIGHT JOIN `answer` a ON att.AnsID=a.AnsID
                                           WHERE att.QuesID={$row['QuesID']} AND  att.Ans_Txt!='';";
                            $amtResult = mysqli_query($con, $amtSql);
                            $amtScore = mysqli_fetch_array($amtResult)['Amount'];

                            $correctSql = "SELECT COUNT(*) AS Correct FROM `attempt` att
                                               RIGHT JOIN `answer` a ON att.AnsID=a.AnsID
                                               WHERE att.QuesID={$row['QuesID']} AND 
                                               TRIM(REPLACE(REPLACE(a.Answer, '\r', ''), '\n', ''))=TRIM(REPLACE(REPLACE(att.Ans_Txt, '\r', ''), '\n', ''));";
                            $correctResult = mysqli_query($con, $correctSql);
                            $correctScore = mysqli_fetch_array($correctResult)['Correct'];
                            break;
                        default:
                            $amtSql = "SELECT COUNT(*) AS Amount FROM `attempt` att
                                           RIGHT JOIN `answer` a ON att.AnsID=a.AnsID
                                           WHERE att.QuesID={$row['QuesID']} AND Ans_Txt='';";
                            $amtResult = mysqli_query($con, $amtSql);
                            $amtScore = mysqli_fetch_array($amtResult)['Amount'];

                            $correctSql = "SELECT COUNT(*) AS Correct FROM `attempt` att
                                               RIGHT JOIN `answer` a ON att.AnsID=a.AnsID
                                               WHERE att.QuesID={$row['QuesID']} AND Ans_Txt='' AND Is_Correct=1;";
                            $correctResult = mysqli_query($con, $correctSql);
                            $correctScore = mysqli_fetch_array($correctResult)['Correct'];
                            break;
                    }

                    $percentage = ($correctScore / $amtScore) * 100;
                    $antiPercentage = 100 - $percentage;

                    echo "
                        <div class='question-bar'>
                            <div class='top'>
                                <span class='label'>" . $row['Ques'] . "</span>
                                <span>" . round($percentage) . "%</span>
                            </div>
                            <div class='bar-container'>
                                <div class='bar correct' style='width: " . $percentage . "%;'></div>
                                <div class='bar incorrect' style='width: " . $antiPercentage . "%;'></div>
                            </div>
                        </div>
                        ";
                }
                ?>
                `;
            } else if (optionType === 'student') {
                viewContainer.innerHTML = `
                <?php
                $quizID = $_GET['QuizID'];
                date_default_timezone_set('Asia/Kuala_Lumpur');
                $pAttSql = "SELECT * FROM quizhistory qh 
                            RIGHT JOIN users u ON qh.UserID=u.UserID 
                            WHERE QuizID=$quizID";
                $result = mysqli_query($con, $pAttSql);
                while ($row = mysqli_fetch_array($result)) {
                    $timestamp = $row['TimeStamp'];
                    $malayDate = date('d/m/Y', strtotime($timestamp));
                    $malayTime = date('H:i', strtotime($timestamp));
                    echo "
                        <a href='../teacher/teach-quiz-analytic-stu.php?QhisID=" . $row['QHisID'] . "&QuizID=" . $row['QuizID'] . "' class='quiz-item' id='analytic'>
                        <h2>" . $row['Username'] . "</h2>
                        <p>Date: " . $malayDate . "<br>Time: " . $malayTime . "</p>
                        <p style='float: right;'>Score: " . round($row['Score'] * 100) . "%</p>
                        </a>";
                }
                echo "
                </section>
                ";
                ?>
                `;
            }
        }
    </script>
</body>

</html>