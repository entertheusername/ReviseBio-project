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
    <link rel="stylesheet" href="../student/styling/stu-note-progress.css">
</head>

<body>
    <?php
        include("stu-header.php")
    ?>

    <!-- Content -->
    <?php
    $noteSql = "SELECT * FROM note WHERE NoteID='{$_GET['NoteID']}'";
    $result = mysqli_query($con, $noteSql);
    $row = mysqli_fetch_array($result);
    echo "
        <main class='note-container'>
            <section class='note-description'>
                <h2> " . $row['Title'] ." </h2>
                ";
                if($row['Type'] == 'Video'){
                    echo "
                    <video width='100%' controls>
                        <source src='" . $row['Pdf_Url'] . "' type='video/mp4'>
                        <p>Your browser does not support HTML5 video. Please update your browser.</p>
                    </video>";
                } else {
                    echo "<embed src='" . $row['Pdf_Url'] . "' type='application/pdf' class='note-content'>";
                }
    echo "
            </section>
            <a href='../student/stu-quiz-listing.php' class='quiz-take-button'>Challenge yourself! Take a Quiz!</a>
        </main>";
    ?>

    <?php
        include("stu-footer.html");
    ?>
</body>

</html>