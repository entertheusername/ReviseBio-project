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
    <link rel="stylesheet" href="../teacher/styling/teach-newq-quiznanaly-listing.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
</head>

<body>
    <?php
    include("teach-header.php");
    ?>

    <!--main body-->
    <main>
        <div class="listing-container">
            <div class="title-container">
                <h2 class="quiz-title">Your Quizzes</h2>
                <button class="create-button" onclick="location.href='../teacher/teach-quiz-new.php';"><b>Create New Quiz</b></button>
            </div>
            <div class="quiz-list">
                <?php
                $quizSql = "SELECT * FROM quiz WHERE UserID={$_SESSION['mySession']}";
                $quizResult = mysqli_query($con, $quizSql);
                while($quizRow = mysqli_fetch_array($quizResult)){
                echo "
                <div class='quiz-item'>
                    <br>
                    <h2 class='text'>" . $quizRow['Title'] . "</h2>
                    <div class='button-container'>
                    <button class='row-button' onclick='location.href=`../teacher/teach-quiz-editor-edit.php?QuizID=" . $quizRow['QuizID'] . "`;'>
                        <span class='material-symbols-outlined'>edit</span>
                    </button>
                    <button class='row-button' onclick='openPopup(" . $quizRow['QuizID'] . ")'>
                        <span class='material-symbols-outlined'>delete</span>
                    </button>
                    </div>
                    <br>
                </div>";
                }
                ?>
            </div>
        </div>

        <script>
            function openPopup(index) {
                const popup = document.getElementById('del');
                popup.style.display = 'block';

                const container = document.getElementById('form');
                container.innerHTML = `
                <input name="submit" type="hidden" value="` + index + `">
                `;
            }
        </script>

        <div id="del" class="general-popup">
            <form action="teach-quiz-create-listing.php" method="post">
            <div class="general-popup-content">
                <div id="form"></div>
                <p>Are you sure you want to delete this quiz?</p>
                <button type="submit">Yes</button>
                <button type="button" onclick="closePopup('del')">No</button>
            </div>
            </form>
        </div>
    <?php
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $index = $_POST['submit'];
        $delSql = "DELETE FROM quiz WHERE QuizID=$index";
        mysqli_query($con, $delSql);

        header("Location: ../teacher/teach-quiz-create-listing.php");
        exit();
    }
    ?>
    
    </main>

    <?php
    include("teach-footer.html");
    ?>
</body>

</html>