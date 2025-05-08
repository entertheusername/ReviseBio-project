<!-- Shim Yong Lin TP076209 -->
<?php
include("admin-header.html");
include("../conn.php");
include("../admin/admin-session.php");
$sql = "SELECT * FROM users WHERE Role != 'admin'";
$result = mysqli_query($con, $sql);

// Handle user deletion on the server side
if (isset($_POST['deleteUser'])) {
    $userID = $_POST['deleteUser']; // Get the UserID to delete
    // Prepare and execute the delete query
    $stmt = mysqli_prepare($con, "DELETE FROM users WHERE UserID = ?");
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);
    header("Location: " . $_SERVER['PHP_SELF']);
    mysqli_stmt_close($stmt);
    exit();
}


if (isset($_POST['newRole']) && isset($_POST['userID'])) {
    $userID = $_POST['userID'];
    $newRole = ucfirst($_POST['newRole']);
    $stmt = mysqli_prepare($con, "UPDATE users SET Role = ? WHERE UserID = ?");
    mysqli_stmt_bind_param($stmt, "si", $newRole, $userID);
    mysqli_stmt_execute($stmt);
    header("Location: " . $_SERVER['PHP_SELF']);
    mysqli_stmt_close($stmt);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../admin/styling/admin-user-manage.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
</head>

<body>
    <form method="post">
        <main style="margin-top:2%; margin-bottom:3%;">
            <div class="user-management-container">
                <h2>User Management</h2>
                <div class="search-bar">
                    <input type="text" id="searchInput" placeholder="Search users..." onkeyup="filterUsers()">
                </div>

                <div class="username-container">
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $username = $row['Username'];
                            $userID = $row['UserID'];
                            $userRole = $row['Role'];
                            echo '
                        <div class="user-row" data-user-id="' . $userID . '">
                            <div class="user-info">' . $username . '</div>
                            <div class="user-actions">
                                <select class="role-dropdown" onchange="confirmRoleChange(this, ' . $userID . ')"data-current-role="' . $userRole . '">
                                    <option value="student" ' . ($userRole == 'Student' ? 'selected' : '') . '>Student</option>
                                    <option value="teacher"' . ($userRole == 'Teacher' ? 'selected' : '') . '>Teacher</option>
                                </select>
                                <button class="delete-btn" name="deleteUser" value="' . $userID . '">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </div>
                        </div>';
                        }
                    } else {
                        echo "<p>No users found.</p>";
                    }
                    ?>
                </div>
            </div>
        </main>
    </form>


    <?php
    include("admin-footer.html");
    mysqli_close($con);
    ?>
    <form id="roleUpdateForm" method="post" style="display: none;">
        <input type="hidden" id="hiddenUserID" name="userID">
        <input type="hidden" id="hiddenNewRole" name="newRole">
        <input type="hidden" name="updateRole" value="true">
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Select all delete buttons
            const deleteButtons = document.querySelectorAll('.delete-btn');

            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const username = this.closest('.user-row').querySelector('.user-info').textContent.trim();
                    const confirmDelete = confirm(`Are you sure you want to delete user: ${username}?`);

                    // Proceed with the deletion only if confirmed
                    if (!confirmDelete) {
                        // Prevent form submission if not confirmed
                        event.preventDefault();
                    }
                });
            });
        });

        function filterUsers() {
            let input = document.getElementById('searchInput').value.toLowerCase();
            let userRows = document.getElementsByClassName('user-row');
            for (let i = 0; i < userRows.length; i++) {
                let username = userRows[i].getElementsByClassName('user-info')[0];
                if (username) {
                    let txtValue = username.textContent || username.innerText;
                    userRows[i].style.display = txtValue.toLowerCase().startsWith(input) ? "" : "none";
                }
            }
        }

        function confirmRoleChange(dropdown, userID) {
            const newRole = dropdown.value;
            const currentRole = dropdown.getAttribute('data-current-role');

            if (newRole !== currentRole) {
                const confirmChange = confirm(`Are you sure you want to change the role to '${newRole}'?`);
                if (confirmChange) {
                    document.getElementById('hiddenUserID').value = userID;
                    document.getElementById('hiddenNewRole').value = newRole;
                    document.getElementById('roleUpdateForm').submit();
                } else {
                    location.reload(true)
                }
            }
        }
    </script>
</body>

</html>