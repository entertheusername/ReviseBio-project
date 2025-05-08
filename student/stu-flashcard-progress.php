<!-- Sum Chun Hoe TP076270 -->
<?php
include("../student/stu-session.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReviseBio</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../student/styling/stu-flashcard-progress.css">
</head>
<script src="../student/stu-flashcard-func.js"></script>

<body>
    <?php
        include("stu-header.php");
    ?>

    <?php
    $fcgrpID = $_GET['FCGrpID'];
    $fcSql = "SELECT * FROM flashcard WHERE FCGrpID = $fcgrpID";
    $result = mysqli_query($con, $fcSql);
    $data = [];
    while ($row = mysqli_fetch_array($result)) {
        $data[] = [
            'front' => $row['Front'],
            'back' => $row['Back']
        ];
    }
    $json = json_encode($data);
    echo "<script>window.flashcardsData = $json; window.FCGrpID = $fcgrpID;</script>";
    ?>

    <!-- Flashcards Content -->
    <main>
        <div class="flashcards-content">
            <div class="flashcard-wrapper">
                <div class="flashcard" onclick="flipCard(this)">
                    <div class="flashcard-inner">
                        <div class="flashcard-front" id="flashcard-front"></div>
                        <div class="flashcard-back" id="flashcard-back"></div>
                    </div>
                </div>
            </div>
            
            <div class="progress-container">
                <h3 class="progress-title">Track Progress</h3>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 0%;"></div>
                </div>
            </div>

            <div class="flashcard-button-container">
                <button class="flashcard-button" id="prev-button" onclick="showPreviousCard()">Previous</button>
                <button class="flashcard-button" id="next-button" onclick="showNextCard()">Next</button>
            </div>
        </div>
    </main>

    <?php
        include("stu-footer.html");
    ?>
</body>

</html>