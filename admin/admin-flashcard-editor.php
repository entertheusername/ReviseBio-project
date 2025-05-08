<!-- Shim Yong Lin TP076209 -->
<?php
include("../conn.php");
if (isset($_GET['FCGrpID'])) {
    $FCGrpID = intval($_GET['FCGrpID']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashcard Editor</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../admin/styling/admin-flashcard-editor.css">
    <link rel="stylesheet" href="../admin/styling/generalpopupadmin.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
</head>

<body>
    <?php
    include("admin-header.html");

    // Fetch flashcard data
    $flashcards = [];
    $titleQuery = "SELECT title FROM fcgroup where fcgrpID = $FCGrpID ";
    $flashcardsQuery = "SELECT flashID, front, back FROM flashcard WHERE fcgrpID = $FCGrpID ORDER BY flashID";
    $titleResult = $con->query($titleQuery);
    $cardsResult = $con->query($flashcardsQuery);
    $title = ($titleResult->num_rows > 0) ? $titleResult->fetch_assoc()['title'] : "Untitled";

    if ($cardsResult->num_rows > 0) {
        while ($row = $cardsResult->fetch_assoc()) {
            $flashcards[] = $row;
        }
    }

    if (!isset($_SESSION['currentIndex'])) {
        $_SESSION['currentIndex'] = 0;
    } //  don't touch default to the first flashcard
    // Get current flashcard data for PHP rendering
    $currentFlashcard = isset($flashcards[$_SESSION['currentIndex']]) ? $flashcards[$_SESSION['currentIndex']] : ['front' => '', 'back' => ''];

    if (isset($_POST['submit_btn'], $_POST['front'], $_POST['back'])) {
        $front = $_POST['front'];
        $back = $_POST['back'];
        $FCGrpID = $_POST['FCGrpID'];
        $stmt = $con->prepare("INSERT INTO flashcard (front, back, fcgrpID) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $front, $back, $FCGrpID);

        if ($stmt->execute()) {
            header("Location:../admin/admin-flashcard-editor.php?FCGrpID=" . $FCGrpID);

            exit;
        }
        $error_message = "Error: " . $stmt->error;
        $stmt->close();
    }

    // Handle delete request
    if (isset($_POST['deleteFlashcard'], $_POST['flashID'])) {
        $flashID = $_POST['flashID'];
        $stmt = mysqli_prepare($con, "DELETE FROM flashcard WHERE flashID = ?");
        mysqli_stmt_bind_param($stmt, "i", $flashID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location:../admin/admin-flashcard-editor.php?FCGrpID=" . $FCGrpID);
        exit;
    }

    // Close the connection
    mysqli_close($con);
    ?>

    <!-- Content -->
    <main>
        <div class="flashcard-container1">
            <div class="title-bar">
                <input type="text" class="flashcard-title" placeholder="Flashcard Title" value="<?php echo htmlspecialchars($title); ?>" style="resizeable:none;" disabled>
            </div>
            <div class="flashcard-views">
                <div class="flashcard-view">
                    <h3>Front View</h3>
                    <textarea class="text-area" id="front-text" disabled><?php echo htmlspecialchars($currentFlashcard['front']); ?></textarea>
                </div>
                <div class="flashcard-view">
                    <h3>Back View</h3>
                    <textarea class="text-area" id="back-text" disabled><?php echo htmlspecialchars($currentFlashcard['back']); ?></textarea>
                </div>
            </div>
            <div class="action-buttons">
                <!-- Undo Button -->
                <button class="flashbuttonad" id="undo-btn"><span class="material-symbols-outlined">undo</span></button>
                <!-- Redo Button -->
                <button class="flashbuttonad" id="redo-btn"><span class="material-symbols-outlined">redo</span></button>
                <!-- Add Button -->
                <button class="flashbuttonad" id="add-btn"><span class="material-symbols-outlined">add</span></button>
                <!-- Delete Button -->
                <button class="flashbuttonad" id="delete-btn"><span class="material-symbols-outlined">delete</span></button>
                <!-- Done Button -->
                <button class="done-btn" onclick="location.href='../admin/admin-content-manage.php'">Done</button>
            </div>
        </div>
    </main>
    <!-- hidden form send post request to the server to delete flashcard -->
    <form id="delete-form" method="post" style="display: none;">
        <input type="hidden" name="flashID" id="flashID-input">
        <input type="hidden" name="deleteFlashcard" value="true">
    </form>

    <!-- Add Flashcard Popup -->
    <div id="add-flashcard-popup" class="admin-popup">
        <div class="admin-popup-content">
            <span id="close-popup" class="admin-close-btn">&times;</span>
            <h3>Add New Flashcard</h3>
            <!-- Add Flashcard Form -->
            <form id="add-flashcard-form" method="POST">
                <input type="hidden" name="FCGrpID" value='<?php echo "$FCGrpID" ?>'>
                <label for="front">Front:</label>
                <textarea id="front" name="front" required></textarea>
                <label for="back">Back:</label>
                <textarea id="back" name="back" required></textarea>
                <button type="submit" name="submit_btn">Add Flashcard</button>
            </form>
        </div>
    </div>

    <?php include("admin-footer.html"); ?>

    <script>
        // JavaScript to handle undo and redo actions
        const flashcards = <?php echo json_encode($flashcards); ?>;
        let currentIndex = <?php echo $_SESSION['currentIndex']; ?>;

        // Update flashcard views based on currentIndex
        function updateFlashcard() {
            const currentFlashcard = flashcards[currentIndex];
            document.getElementById('front-text').value = currentFlashcard.front || '';
            document.getElementById('back-text').value = currentFlashcard.back || '';
        }

        // Undo button functionality
        document.getElementById('undo-btn').addEventListener('click', function() {
            if (currentIndex > 0) {
                currentIndex--;
                <?php $_SESSION['currentIndex'] = 'currentIndex'; ?>
                updateFlashcard();
            }
        });

        // Redo button functionality
        document.getElementById('redo-btn').addEventListener('click', function() {
            if (currentIndex < flashcards.length - 1) {
                currentIndex++;
                <?php $_SESSION['currentIndex'] = 'currentIndex'; ?>
                updateFlashcard();
            }
        });

        // Delete button functionality
        document.getElementById('delete-btn').addEventListener('click', function() {
            const currentFlashcard = flashcards[currentIndex];
            if (!currentFlashcard || !currentFlashcard.flashID) {
                alert("No flashcard selected for deletion.");
                return;
            }

            const confirmDelete = confirm("Are you sure you want to delete this flashcard?");
            if (confirmDelete) {
                document.getElementById('flashID-input').value = currentFlashcard.flashID;
                document.getElementById('delete-form').submit();
            }
        });

        // Open the add flashcard popup when the "Add" button is clicked
        document.getElementById('add-btn').addEventListener('click', function() {
            document.getElementById('add-flashcard-popup').style.display = "flex";
        });

        // Close the popup when the close button is clicked
        document.getElementById('close-popup').addEventListener('click', function() {
            document.getElementById('add-flashcard-popup').style.display = "none";
        });

        // Close the popup if clicked outside
        window.addEventListener('click', function(event) {
            if (event.target == document.getElementById('add-flashcard-popup')) {
                document.getElementById('add-flashcard-popup').style.display = "none";
            }
        });

        // Initial flashcard update
        updateFlashcard();
    </script>
</body>