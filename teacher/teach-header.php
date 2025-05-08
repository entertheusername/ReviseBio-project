<!-- Import JS -->

<head>
    <script src="../sidebar.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
</head>

<!-- Header -->
<header>
    <a href="../teacher/teach-main.php"><img src="../imgs/ReviseBio.png"></a>
    <div class="nav-right">
        <button onclick="location.href='../teacher/teach-main.php';">Home</button>
        <button onclick="location.href='../teacher/teach-quiz-create-listing.php';">Create Quiz</button>
        <button onclick="location.href='../teacher/teach-quiz-analytic-listing.php';">Quiz Analytics</button>

        <!--sidepanel-->
        <?php
        include('../teacher/sidepanel-mobile.html');
        include('../sidepanel.php');
        ?>
    </div>
</header>