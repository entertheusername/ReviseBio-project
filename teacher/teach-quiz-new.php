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

    <main>
        <div class="listing-container">
            <div class="title-container" style="justify-content: center;">
                <h2>New Quiz</h2>
            </div>
            <form action="../teacher/teach-quiz-new.php" method="post">
                <input name="title" maxlength="80" type="text" placeholder="Quiz Title" class="newquiz-input" required>
                <textarea name="desc" maxlength="200" placeholder="Quiz Description" class="newquiz-input" style="resize: none;" required></textarea>
                <div class="button-container" id="newquiz-container">
                    <button class="create-button" id="newquiz-button" type="button" onclick="location.href='../teacher/teach-quiz-create-listing.php';">Back</button>
                    <button class="create-button" id="newquiz-button" type="submit">Next</button>
                </div>
            </form>
        </div>
    </main>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = urlencode($_POST['title']);
        $desc = urlencode($_POST['desc']);
        echo $title . "<br>" . $desc;
        header("Location:../teacher/teach-quiz-editor.php?NQTitle=$title&NQDesc=$desc");
        exit;
    }
    ?>

    <?php
    include("teach-footer.html");
    ?>
</body>

</html>