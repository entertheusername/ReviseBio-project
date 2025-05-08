<!-- Sum Chun Hoe TP076270 -->
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
    <link rel="stylesheet" href="../student/styling/stu-flashcard-end.css">
</head>

<body>
    <?php
        include("stu-header.php");
    ?>

    <!-- content -->
    <?php
    $fcgrpID = $_GET['FCGrpID'];
    $fcSql = "SELECT * FROM fcgroup WHERE FCGrpID = $fcgrpID";
    $row = mysqli_fetch_array(mysqli_query($con, $fcSql));
    echo "
    <main>
        <div class='flashcards-content'>
            <div class='flashcard-finished'>
                <h2 class='flashcard-title'> " . $row['Title'] . " </h2>
                <p>Flashcard Finished!</p>
            </div>
            <button class='flashcard-button' onclick='window.location.href = `../student/stu-flashcard-listing.php`'> Back to Flashcards </button>
        </div>
    </main>
    ";
    ?>

    <?php
        include("stu-footer.html");
    ?>
</body>

</html>