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
    <link rel="stylesheet" href="../student/styling/stu-notenpast-listing.css">
</head>

<body>
    <?php
        include("stu-header.php");
    ?>

    <!-- Content -->
    <!-- Selection of Animal / Plant Cell Notes-->
    <main class="notes-container">
        <section class="notes-section">
            <h2> Cell Notes </h2>
            <?php
            $noteSql = "SELECT * FROM note";
            $result = mysqli_query($con, $noteSql);
            while ($row = mysqli_fetch_array($result)) {
            echo "
                <a href='../student/stu-note-progress.php?NoteID=" . $row['NoteID'] . "' class='note-buttonstudy'>
                    <h3> " . $row['Title'] . " </h3>
                    <p> " . $row['NoteDesc'] . " </p>
                </a>";
            }
            ?>
        </section>
    </main>
    <?php
        include("stu-footer.html");
    ?>
</body>

</html>