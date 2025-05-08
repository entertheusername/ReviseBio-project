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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
</head>

<body>
    <?php
        include("stu-header.php");
    ?>

    <!-- Main Content Section -->
    <main>
        <div class="main-welcome-message">
            <?php
            echo "<h1>Hi {$_SESSION['name']}!</h1>";
            ?>
        </div>
        <div class="main-options-container">
            <div class="main-option">
                <a href="../student/stu-flashcard-listing.php" class="main-option-button">
                    <span class="material-symbols-outlined" style="width: 100%; height: auto; font-size: 20vw;">
                        style
                    </span>
                    Go To Flashcards Now!
                </a>
            </div>
            <div class="main-option">
                <a href="../student/stu-note-listing.php" class="main-option-button">
                    <span class="material-symbols-outlined" style="width: 100%; height: auto; font-size: 20vw;">
                        description
                    </span>
                    Go To Notes Now!
                </a>
            </div>
        </div>
    </main>

    <?php
        include("stu-footer.html");
    ?>
</body>

</html>