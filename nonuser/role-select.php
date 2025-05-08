<!-- Tsai Chin Xian TP075570 -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReviseBio</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=history_edu,school">
</head>

<body>
    <!-- Header -->
    <?php
    include('nonuser-header.html')
    ?>

    <!-- Main Content Section -->
    <main>
        <div class="main-welcome-message">
            <h1>Are you a...</h1>
        </div>
        <div class="main-options-container">
            <div class="main-option">
                <a href="signup.php?Role=Student">
                    Student
                    <span class="material-symbols-outlined" style="width: 100%; height: auto; font-size: 20vw;">
                        school
                    </span>
                </a>
            </div>
            <div class="main-option">
                <a href="signup.php?Role=Teacher">
                    Teacher
                    <span class="material-symbols-outlined" style="width: 100%; height: auto; font-size: 20vw;">
                        history_edu
                    </span>
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php
    include('nonuser-footer.html')
    ?>

</body>

</html>