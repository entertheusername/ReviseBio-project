<!-- Chan Zyanne TP075156 -->
<?php
include("../conn.php");
include("teach-session.php");
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
    <script src="../teacher/teach-quiz-editor.js"></script>
</head>
<?php
include("teach-header.php");
?>
<!--questions-->
<main>
    <form id="form" name="quizform" enctype="multipart/form-data" action="teach-quiz-editor.php?QuizID=77" method="post">
        <input name="nq-title" type="hidden" value="<?php echo"{$_GET['NQTitle']}"?>">
        <input name="nq-desc" type="hidden" value="<?php echo"{$_GET['NQDesc']}"?>"> 
        <!--Auto generate stuff here-->
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
            <h2><?php echo $_GET['NQTitle'] ?></h2>
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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nqTitle = $_POST['nq-title'];
    $nqDesc = $_POST['nq-desc'];

    $nqSql = "INSERT INTO quiz (Title, QuizDesc, UserID) VALUES ('$nqTitle', '$nqDesc', '{$_SESSION['mySession']}')";
    mysqli_query($con, $nqSql);
    $quizID = mysqli_insert_id($con);

    $questions = $_POST['questions'];
    $questionTypes = $_POST['question_types'];
    $explanations = $_POST['explanations'];
    $allOptions = isset($_POST['options']) ? $_POST['options'] : [];
    $correctAnswers = isset($_POST['correct_answers']) ? $_POST['correct_answers'] : [];
    $answers = isset($_POST['answers']) ? $_POST['answers'] : [];

    foreach ($questions as $index => $question) {
        $type = ucfirst($questionTypes[$index]);
        $explanation = $explanations[$index];
        if($question == '' || $question == null){
            continue;
        }

        $filePath=null;
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

        $quesSql="INSERT INTO question (Ques, Media_Url, Type, Note, QuizID) VALUES ('$question', '$filePath', '$type', '$explanation', '$quizID')";
        mysqli_query($con, $quesSql);
        $quesID = mysqli_insert_id($con);

        switch ($type) {
            case 'Short':
                $answer = $answers[$index];
                echo $answer;
                $isCorrect = 1;
                $ansSql="INSERT INTO answer (Answer, Is_Correct, QuesID) VALUES ('$answer', '$isCorrect', '$quesID')";
                mysqli_query($con, $ansSql);
                break;
            case 'Mcq':
                $options = $allOptions[$index];
                $correctOption = $correctAnswers[$index];

                foreach ($options as $optIndex => $option) {
                    $isCorrect = ($correctOption == $optIndex) ? 1 : 0;
                    $ansSql="INSERT INTO answer (Answer, Is_Correct, QuesID) VALUES ('$option', '$isCorrect', '$quesID')";
                    mysqli_query($con, $ansSql);
                }
                break;
            case 'Truefalse':
                $correctOption = $correctAnswers[$index];
                $trueFalse = array("True", "False");
                foreach ($trueFalse as $value) {
                    $isCorrect = ($correctOption == $value) ? 1 : 0;
                    $ansSql="INSERT INTO answer (Answer, Is_Correct, QuesID) VALUES ('$value', '$isCorrect', '$quesID')";
                    mysqli_query($con, $ansSql);
                }
        }
    }

    header("Location: ../teacher/teach-quiz-create-listing.php");
    exit();
}
?>

<?php
include("teach-footer.html");
?>


</body>

</html>