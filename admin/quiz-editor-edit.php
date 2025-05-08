<!-- Shim Yong Lin TP076209 -->
<?php
include("../conn.php");
include("../admin/admin-session.php");
ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReviseBio</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../teacher/styling/teach-quiz-editor.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
    <script src="../teacher/teach-quiz-editor-general.js"></script>
    <script src="../teacher/teach-quiz-editor-edit.js"></script>
</head>

<body>
    <?php
    include("admin-header.html");
    ?>

    <!-- DO NOT MOVE THIS THING BELOW ITS VODOO IDK WHY BUT POST DOESNT WORK IF U MOVE IT -->
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nqTitle = $_POST['nq-title'];
        $nqDesc = $_POST['nq-desc'];
        $quizID = $_POST['quizid'];

        $delAllPastAttSql = "DELETE FROM quizhistory WHERE QuizID=$quizID";
        mysqli_query($con, $delAllPastAttSql);

        $nqSql = "UPDATE quiz SET Title='$nqTitle', QuizDesc='$nqDesc' WHERE QuizID=$quizID";
        mysqli_query($con, $nqSql);

        $iquesIdArray = json_decode($_POST['iquesid-array'][0]);
        $iquesId = $_POST['quesid'];
        $questions = $_POST['questions'];
        $questionTypes = $_POST['question_types'];
        $explanations = $_POST['explanations'];
        $allOptions = isset($_POST['options']) ? $_POST['options'] : [];
        $correctAnswers = isset($_POST['correct_answers']) ? $_POST['correct_answers'] : [];
        $answers = isset($_POST['answers']) ? $_POST['answers'] : [];

        foreach ($iquesIdArray as $ques) {
            if (!in_array($ques, $iquesId)) {
                $delQuesSql = "DELETE FROM question WHERE QuesID=$ques";
                mysqli_query($con, $delQuesSql);
            }
        }

        foreach ($questions as $index => $question) {
            $type = ucfirst($questionTypes[$index]);
            $explanation = $explanations[$index];
            $quesID = $iquesId[$index] ?? '';
            echo $question;
            echo $index;
            echo $quesID;
            if ($question == '' || $question == null) {
                continue;
            }

            $filePath = null;
            if ($_FILES['file']['name'][$index] != "") {
                $fileName = basename($_FILES['file']['name'][$index]);
                $targetDir = "../imgs/";
                $targetFile = $targetDir . uniqid() . "_" . $fileName;

                if (move_uploaded_file($_FILES['file']['tmp_name'][$index], $targetFile)) {
                    $filePath = $targetFile;
                }
            }
            if ($_FILES['file']['error'][$index] !== UPLOAD_ERR_OK) {
                echo "Error with file upload: " . $_FILES['file']['error'][$index];
            }

            if (!empty($quesID)) {
                echo "helqoijs<br>";
                if ($filePath == null) {
                    $quesSql = "UPDATE question SET Ques='$question', Type='$type', Note='$explanation' WHERE QuesID=$quesID";
                } else {
                    $quesSql = "UPDATE question SET Ques='$question', Media_Url='$filePath', Type='$type', Note='$explanation' WHERE QuesID=$quesID";
                }
                mysqli_query($con, $quesSql);
                $delAnsSql = "DELETE FROM answer WHERE QuesID=$quesID";
                mysqli_query($con, $delAnsSql);
            } else {
                $quesSql = "INSERT INTO question (Ques, Media_Url, Type, Note, QuizID) VALUES ('$question', '$filePath', '$type', '$explanation', '$quizID')";
                mysqli_query($con, $quesSql);
                $quesID = mysqli_insert_id($con);
            }

            switch ($type) {
                case 'Short':
                    $answer = $answers[$index];
                    echo $answer;
                    $isCorrect = 1;
                    $ansSql = "INSERT INTO answer (Answer, Is_Correct, QuesID) VALUES ('$answer', '$isCorrect', '$quesID')";
                    mysqli_query($con, $ansSql);
                    break;
                case 'Mcq':
                    $options = $allOptions[$index];
                    $correctOption = $correctAnswers[$index];

                    foreach ($options as $optIndex => $option) {
                        $isCorrect = ($correctOption == $optIndex) ? 1 : 0;
                        $ansSql = "INSERT INTO answer (Answer, Is_Correct, QuesID) VALUES ('$option', '$isCorrect', '$quesID')";
                        mysqli_query($con, $ansSql);
                    }
                    break;
                case 'Truefalse':
                    $correctOption = $correctAnswers[$index];
                    $trueFalse = array("True", "False");
                    foreach ($trueFalse as $value) {
                        $isCorrect = ($correctOption == $value) ? 1 : 0;
                        $ansSql = "INSERT INTO answer (Answer, Is_Correct, QuesID) VALUES ('$value', '$isCorrect', '$quesID')";
                        mysqli_query($con, $ansSql);
                    }
            }
        }

        header("Location: ../admin/admin-content-manage.php");
        exit();
    }
    ?>

    <?php
    $quizSql = "SELECT * FROM quiz WHERE QuizID={$_GET['QuizID']}";
    $quizRow = mysqli_fetch_array(mysqli_query($con, $quizSql));

    if (!$quizRow) {
        echo "Quiz not found.";
        exit();
    }
    ?>

    <!--questions-->
    <main>
        <form id="form" name="quizform" enctype="multipart/form-data" action="quiz-editor-edit.php" method="post">
            <input type='hidden' name='quizid' value="<?php echo "{$_GET['QuizID']}" ?>">
            <input name="nq-title" type="text" value="<?php echo "{$quizRow['Title']}" ?>" class="nq" maxlength="80" placeholder="QuizTitle" required>
            <textarea name="nq-desc" maxlength="200" placeholder="Quiz Description" class="nq" style="resize: none; height: 100px; padding-bottom:20px;" required>
            <?php $quizDesc = trim(str_replace(["\r", "\n"], '', $quizRow['QuizDesc']));
            echo $quizDesc; ?></textarea>
            <!-- DONT TOUCH LINE ABOVE CUZ THRS WEIRD SPACING ISSUES IF U ENTER STUFF -->
            <!--Auto generate stuff here-->
            <?php
            $iQuesSql = "SELECT * FROM question WHERE QuizID={$_GET['QuizID']}";
            $iQuesResult = mysqli_query($con, $iQuesSql);
            while ($iQuesRow = mysqli_fetch_array($iQuesResult)) {
                $iAnsSql = "SELECT * FROM answer WHERE QuesID={$iQuesRow['QuesID']}";
                $iAnsResult = mysqli_query($con, $iAnsSql);
                $iQuesID[] = $iQuesRow['QuesID'];

                while ($iAnsRow = mysqli_fetch_array($iAnsResult)) {
                    $iAnsAnswers[] = $iAnsRow['Answer'];
                    $iAnsCorrect[] = $iAnsRow['Is_Correct'];
                }

                if ($iQuesRow['Type'] == 'Short') {
                    $iAnsAnswers[0] = trim(str_replace(["\r", "\n"], '', $iAnsAnswers[0]));
                }

                $quesExp = trim(str_replace(["\r", "\n"], '', $iQuesRow['Note']));
                echo "<script>addInitialQuestionBlock(`" . $iQuesRow['Ques'] . "`,'',`" . $iQuesRow['Type'] . "`,'" . $quesExp . "',"
                    . json_encode($iAnsAnswers) . "," . json_encode($iAnsCorrect) . ", " . $iQuesRow['QuesID'] . ")</script>";
                // " . $iQuesRow['Media_Url'] . "
                unset($iAnsAnswers);
                unset($iAnsCorrect);
            }
            echo "<input type='hidden' name='iquesid-array[]' value=" . json_encode($iQuesID) . ">";
            ?>
        </form>
        <div id="form-btn" class="button-container">
            <button class="toolbar-button" onclick="addQuestionBlock()">
                <span class="material-symbols-outlined">add</span>
            </button>
            <button onclick="hideForm(); printQues();" class="toolbar-button">
                <span class="material-symbols-outlined">check</span>
            </button>
        </div>

        <div name="ansum" id="ansum" class="form-container">
            <div class="quiz-header">
                <h2>
                    <script>
                        getQuizTitle();
                    </script>
                </h2>
            </div>
            <div id="ques-list" class="ques-list">
                <!-- Auto generate stuff here -->
            </div>

            <div class="button-container" style="width: 100%;">
                <button class="submit-button" onclick="showForm()">
                    Back
                </button>
                <button class="submit-button" onclick="showForm(); submitForm();">
                    Submit Quiz
                </button>
            </div>
        </div>
    </main>

    <!--popup window delete-->
    <div id="popup" class="general-popup">
        <div class="general-popup-content">
            <p>Are you sure you want to delete question?</p>
            <button>Yes</button>
            <button onclick="closePopup('popup')">No</button>
        </div>
    </div>

    <?php
    include("admin-footer.html");
    ?>

</body>

</html>