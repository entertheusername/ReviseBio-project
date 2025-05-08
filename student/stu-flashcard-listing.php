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
    <link rel="stylesheet" href="../student/styling/stu-quiznfc-listing.css">
    <script defer src="../student/stu-flashcard-func.js"></script>
</head>

<body>
    <?php
        include("stu-header.php")
    ?>

<main class="main-body">
    <!-- Content Choosing Quiz-->
    <div class="list-selection-container">
        <?php
        $fcgrpSql = "SELECT * FROM fcgroup";
        $result = mysqli_query($con, $fcgrpSql);
        while ($row = mysqli_fetch_array($result)) {
            $id = $row['FCGrpID'];
            $fcSql = "SELECT COUNT(FCGrpID) AS FCCount FROM flashcard WHERE FCGrpID='$id'";
            $fcCount = mysqli_fetch_assoc(mysqli_query($con, $fcSql));
            echo "<a href='../student/stu-flashcard-progress.php?FCGrpID=$id' class='list-button'>
                <h2>" . $row['Title'] . "</h2>
                <hr style='width: 100%;'>
                <h3>Number of Cards: " . $fcCount['FCCount'] . "</h3>
                <hr style='width: 80%;'>
                <p>" . $row['FCDesc'] . "</p>
             </a>";
        }
        ?>
    </div>
</main>

    <?php
        include("stu-footer.html");
    ?>
</body>

</html>