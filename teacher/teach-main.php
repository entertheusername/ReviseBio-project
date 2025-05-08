<!-- Chan Zyanne TP075156 -->
<?php
include("teach-session.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReviseBio - Teacher Homepage</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
</head>

<body>

    <?php
    include("teach-header.php");
    ?>

    <!-- Main Content Section -->
    <main>
        <div class="main-welcome-message">
            <?php
            echo "<h1>Hello, {$_SESSION['name']}!</h1>";
            ?>
        </div>
        <div class="main-options-container">
            <div class="main-option">
                <a href="../teacher/teach-quiz-analytic-listing.php" class="main-option-button">
                    <span class="material-symbols-outlined" style="width: 100%; height: auto; font-size: 20vw;">
                        insert_chart
                    </span>
                    Quiz Analytics
                </a>
            </div>
            <div class="main-option">
                <a href="../teacher/teach-quiz-create-listing.php" class="main-option-button">
                    <span class="material-symbols-outlined" style="width: 100%; height: auto; font-size: 20vw;">
                        quiz
                    </span>
                    Create Quiz
                </a>
            </div>
        </div>
    </main>

    <?php
    include("teach-footer.html");
    ?>
</body>

</html>