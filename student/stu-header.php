<!--Import JS-->
<head>
    <script src="../sidebar.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
</head>

<!-- Header -->
<header>
    <a href="../student/stu-main.php"><img src="../imgs/ReviseBio.png"></a>
    <div class="nav-right">
        <button onclick="location.href='../student/stu-main.php';">Home</button>
        <button onclick="location.href='../student/stu-note-listing.php';">Study Notes</button>
        <button onclick="location.href='../student/stu-flashcard-listing.php';">Flashcards</button>
        <button onclick="location.href='../student/stu-quiz-listing.php';">Quizzes</button>

        <!--Sidepanel-->
        <?php
        include('../student/sidepanel-mobile.html');
        include('../sidepanel.php');
        ?>
    </div>
</header>