<!-- Shim Yong Lin TP076209 -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Management</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../admin/styling/generalpopupadmin.css">
    <link rel="stylesheet" href="../admin/styling/admin-content-manage.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
</head>

<body>
    <?php
    include("admin-header.html");
    include("../conn.php");
    include("../admin/admin-session.php");

    $note = mysqli_query($con, "SELECT * FROM note");
    $fcgroup = mysqli_query($con, "SELECT * FROM fcgroup");
    $quiz = mysqli_query($con, "SELECT * FROM quiz");

    $selectedType = 'note';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contentType'])) {
        $selectedType = $_POST['contentType'];
    }

    // Handle note addition
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitnote_btn'])) {
        if (isset($_POST['notetitle'], $_POST['notedesc'], $_FILES['Pdf_Url'])) {
            $noteTitle = $con->real_escape_string($_POST['notetitle']);
            $noteDesc = $con->real_escape_string($_POST['notedesc']);
            $file = $_FILES['Pdf_Url'];

            $uploadDir = '../pdfs/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = time() . '-' . basename($file['name']);
            $filePath = $uploadDir . uniqid() . "_" . $fileName;

            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                $sql = "INSERT INTO note (Title, NoteDesc, Pdf_Url) VALUES ('$noteTitle', '$noteDesc', '$filePath')";
                if ($con->query($sql) === TRUE) {
                    echo '<script>alert("Note added successfully!");</script>';
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit;
                }
            } else {
                echo '<script>alert("File upload failed.");</script>';
            }
        } else {
            echo '<script>alert("Missing required fields or file.");</script>';
        }
    }

    // Handle file re-upload for notes
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['Pdf_Url'], $_POST['NoteID'])) {
        $noteID = intval($_POST['NoteID']);
        if ($noteID <= 0) {
            echo '<script>alert("Invalid NoteID.");</script>';
            exit;
        }
        $file = $_FILES['Pdf_Url'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            echo '<script>alert("File upload error: ' . $file['error'] . '");</script>';
            exit;
        }

        // Get the current file name
        $result = mysqli_query($con, "SELECT Pdf_Url FROM note WHERE NoteID = $noteID");
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $oldFilePath = $row['Pdf_Url'];
            // Delete the old file if it exists
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }

            // Upload the new file
            $uploadDir = '../pdfs/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $newFileName = time() . '-' . basename($file['name']);
            $newFilePath = $uploadDir . uniqid() . "_" . $newFileName;

            if (move_uploaded_file($file['tmp_name'], $newFilePath)) {
                // Update the database
                $stmt = mysqli_prepare($con, "UPDATE note SET Pdf_Url = ? WHERE NoteID = ?");
                mysqli_stmt_bind_param($stmt, "si", $newFilePath, $noteID);
                if (mysqli_stmt_execute($stmt)) {
                    echo '<script>alert("File re-uploaded successfully!");</script>';
                } else {
                    echo '<script>alert("Failed to update database.");</script>';
                }
                mysqli_stmt_close($stmt);
            } else {
                echo '<script>alert("File upload failed.");</script>';
            }
        } else {
            echo '<script>alert("Note not found in the database.");</script>';
        }
    }

    // Handle file download for note
    if (isset($_GET['action']) && $_GET['action'] === 'downloadNote' && isset($_GET['downloadNoteID'])) {
        $noteID = intval($_GET['downloadNoteID']);
        $result = mysqli_query($con, "SELECT Pdf_Url FROM note WHERE NoteID = $noteID");
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $filePath = $row['Pdf_Url'];

            if (file_exists($filePath)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filePath));
                readfile($filePath);
                exit;
            } else {
                echo '<script> alert("File not found."); </script>';
            }
        } else {
            echo '<script>alert("Note does not exist or file URL not found in database.");</script>';
        }
    }

    // Handle note deletion
    if (isset($_POST['deleteNoteID'])) {
        $noteID = intval($_POST['deleteNoteID']);
        $result = mysqli_query($con, "SELECT Pdf_Url FROM note WHERE NoteID = $noteID");
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $filePath = '../pdfs/' . $row['Pdf_Url'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $stmt = mysqli_prepare($con, "DELETE FROM note WHERE NoteID = ?");
        mysqli_stmt_bind_param($stmt, "i", $noteID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Handle flashcard deletion
    if (isset($_POST['deleteFCGrpID'])) {
        $FCGrpID = intval($_POST['deleteFCGrpID']);
        $stmt = mysqli_prepare($con, "DELETE FROM fcgroup WHERE FCGrpID = ?");
        mysqli_stmt_bind_param($stmt, "i", $FCGrpID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    //Handle flashcard addition
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitfcg_btn'])) {
        if (isset($_POST['fcgtitle'], $_POST['fcgdesc'], $_POST['front'], $_POST['back'])) {
            // Escape input values to prevent SQL injection
            $fcgTitle = $con->real_escape_string($_POST['fcgtitle']);
            $fcgDesc = $con->real_escape_string($_POST['fcgdesc']);
            $front = $con->real_escape_string($_POST['front']);
            $back = $con->real_escape_string($_POST['back']);

            // Insert into the fcgroup table
            $sql1 = "INSERT INTO fcgroup (Title, FCDesc) VALUES ('$fcgTitle', '$fcgDesc')";
            if ($con->query($sql1) === TRUE) {
                $fcGrpID = $con->insert_id;
                $sql2 = "INSERT INTO flashcard (Front, Back, FCGrpID) VALUES ('$front', '$back', $fcGrpID)";
                if ($con->query($sql2) === TRUE) {
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit;
                } else {
                    echo "Error inserting into flashcard table: " . $con->error;
                }
            } else {
                echo "Error inserting into fcgroup table: " . $con->error;
            }
        } else {
            echo "All fields are required.";
        }
    }
    ?>

    <form method="POST" id="contentTypeForm">
        <input type="hidden" name="contentType" id="contentTypeInput" value="<?php echo htmlspecialchars($selectedType); ?>">
    </form>

    <!-- Add Flashcard Popup -->
    <div id="add-flashcard-popup" class="admin-popup">
        <div class="admin-popup-content">
            <span id="flashclose-popup" class="admin-close-btn">&times;</span>
            <h3>Add New Flashcard</h3>
            <!-- Add flashcard Form -->
            <form id="add-flashcard-form" method="POST">
                <label for="front">Flashcard Title</label>
                <textarea id="fcgtitle" name="fcgtitle" required></textarea>
                <label for="front">Flashcard Description</label>
                <textarea id="fcgdesc" name="fcgdesc" required></textarea>
                <label for="front">Front:</label>
                <textarea id="front" name="front" required></textarea>
                <label for="back">Back:</label>
                <textarea id="back" name="back" required></textarea>
                <button type="submit" name="submitfcg_btn">Add Flashcard</button>
            </form>
        </div>
    </div>

    <!-- Add Note Popup -->
    <div id="add-notes-popup" class="admin-popup">
        <div class="admin-popup-content">
            <span id="close-popup" class="admin-close-btn">&times;</span>
            <h3>Add New Note</h3>
            <!-- Add Note Form -->
            <form id="add-note-form" method="POST" enctype="multipart/form-data">
                <label for="title">Note Title</label>
                <textarea id="notetitle" name="notetitle" requied></textarea>
                <label for="Description">Description</label>
                <textarea id="notedesc" name="notedesc" required></textarea>
                <label for="Pdf_Url">Upload Note</label>
                <input type="file" name="Pdf_Url" accept=".pdf, .mp4" style="display:block; text-align:left; margin-bottom:20px;" required>
                <button type="submit" name="submitnote_btn">Add Note</button>
            </form>
        </div>
    </div>

    <!-- Content -->
    <main>
        <div class="content-container">
            <div class="contenttype">
                <button class="option-btn <?php if ($selectedType === 'note') echo 'selected'; ?>" onclick="changeContentType('note')">Note</button>
                <button class="option-btn <?php if ($selectedType === 'flashcard') echo 'selected'; ?>" onclick="changeContentType('flashcard')">Flashcard</button>
                <button class="option-btn <?php if ($selectedType === 'quiz') echo 'selected'; ?>" onclick="changeContentType('quiz')">Quiz</button>
            </div>

            <div id="answer-options">
                <?php
                function generateContent($items, $type)
                {
                    $html = "";

                    if ($type === 'Note') {
                        $html .= "<div class=\"topiccontent\">\n";
                        $html .= "    <h3>{$type}s</h3>\n";
                        $html .= "    <div class=\"button-group\">\n";
                        $html .= "        <button id=\"addbtnnotes\" class=\"add\"><span class=\"material-symbols-outlined\">add</span></button>\n";
                        $html .= "    </div>\n";
                        $html .= "</div>\n";
                    } elseif ($type === 'Flashcard') {
                        $html .= "<div class=\"topiccontent\">\n";
                        $html .= "    <h3>{$type}s</h3>\n";
                        $html .= "    <div class=\"button-group\">\n";
                        $html .= "        <button id=\"addbtnflashcard\" class=\"add\"><span class=\"material-symbols-outlined\">add</span></button>\n";
                        $html .= "    </div>\n";
                        $html .= "</div>\n";
                    }

                    $html .= "<div class=\"answer-option\">";

                    if (mysqli_num_rows($items) > 0) {
                        while ($item = mysqli_fetch_assoc($items)) {
                            $title = htmlspecialchars($item['Title'] ?? $item['Name']);
                            $id = htmlspecialchars($item['NoteID'] ?? $item['FCGrpID'] ?? $item['QuizID']);
                            $buttons = '';

                            if ($type === 'Note') {
                                $buttons = "
                                <button class=\"upload\" onclick=\"document.getElementById('fileInput-{$id}').click();\">
                                    <span class=\"material-symbols-outlined\">publish</span>
                                </button>
                                <form id=\"uploadForm-{$id}\" method=\"POST\" enctype=\"multipart/form-data\" style=\"display: inline;\">
                                    <input type=\"hidden\" name=\"NoteID\" value=\"{$id}\">
                                    <input type=\"file\" name=\"Pdf_Url\" id=\"fileInput-{$id}\" style=\"display: none;\" accept=\".pdf, .mp4\" onchange=\"this.form.submit();\">
                                </form>
                                <button class=\"download\" onclick=\"window.location.href='?action=downloadNote&downloadNoteID={$id}'\">
                                    <span class=\"material-symbols-outlined\">download</span>
                                </button>
                                <form method=\"POST\" onsubmit=\"return confirm('Are you sure you want to delete this note?');\" style=\"display:inline;\">
                                    <input type=\"hidden\" name=\"deleteNoteID\" value=\"{$id}\">
                                    <button type=\"submit\" class=\"delete\">
                                        <span class=\"material-symbols-outlined\">delete</span>
                                    </button>
                                </form>
                                ";
                            } elseif ($type === 'Flashcard') {
                                $buttons = "
                                     <button onclick=\"location.href='../admin/admin-flashcard-editor.php?FCGrpID={$id}'\" class=\"upload\">
                                         <span class=\"material-symbols-outlined\">edit</span>
                                     </button>
                                <form method=\"POST\" onsubmit=\"return confirm('Are you sure you want to delete this flashcard?');\" style=\"display:inline;\">
                                    <input type=\"hidden\" name=\"deleteFCGrpID\" value=\"{$id}\">
                                    <button type=\"submit\" class=\"delete\">
                                        <span class=\"material-symbols-outlined\">delete</span>
                                    </button>
                                </form>
                            ";
                            } elseif ($type === 'Quiz') {
                                $buttons = "
                                <button onclick=\"location.href='../admin/quiz-editor-edit.php?QuizID={$id}'\" class=\"upload\">
                                         <span class=\"material-symbols-outlined\">edit</span>
                                     </button>
                            ";
                            }

                            $html .= "
                            <div class=\"answer-choicenote\" data-id=\"{$id}\">\n
                                <p>{$title}</p>\n
                                <div class=\"button-group\">\n
                                    {$buttons}\n
                                </div>\n
                            </div>\n
                        ";
                        }
                    } else {
                        $html .= '<p>No items available.</p>';
                    }
                    $html .= '</div>';
                    return $html;
                }

                if ($selectedType === 'note') {
                    echo generateContent($note, 'Note');
                } elseif ($selectedType === 'flashcard') {
                    echo generateContent($fcgroup, 'Flashcard');
                } elseif ($selectedType === 'quiz') {
                    echo generateContent($quiz, 'Quiz');
                }
                ?>
            </div>
        </div>
    </main>
    <!-- Initial Content End -->
    <?php include("admin-footer.html"); ?>

    <script>
        function changeContentType(type) {
            document.getElementById('contentTypeInput').value = type;
            document.getElementById('contentTypeForm').submit();
        }
        // Notes Popup
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('click', function(event) {
                const addBtnNotes = event.target.closest('#addbtnnotes');
                if (addBtnNotes) {
                    document.getElementById('add-notes-popup').style.display = "flex";
                }
            });

            // Close the popup when the close button is clicked
            const closePopupBtn = document.getElementById('close-popup');
            if (closePopupBtn) {
                closePopupBtn.addEventListener('click', function() {
                    document.getElementById('add-notes-popup').style.display = "none";
                });
            }

            // Close the popup if clicked outside
            window.addEventListener('click', function(event) {
                const popup = document.getElementById('add-notes-popup');
                if (event.target === popup) {
                    popup.style.display = "none";
                }
            });
        });

        // Flashcard Popup
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('click', function(event) {
                const addBtnFlashcard = event.target.closest('#addbtnflashcard');
                if (addBtnFlashcard) {
                    document.getElementById('add-flashcard-popup').style.display = "flex";
                }
            });

            // Close the popup when the close button is clicked
            const closePopupBtn = document.getElementById('flashclose-popup');
            if (closePopupBtn) {
                closePopupBtn.addEventListener('click', function() {
                    document.getElementById('add-flashcard-popup').style.display = "none";
                });
            }

            // Close the popup if clicked outside
            window.addEventListener('click', function(event) {
                const popup = document.getElementById('add-flashcard-popup');
                if (event.target === popup) {
                    popup.style.display = "none";
                }
            });
        });
    </script>
</body>

</html>