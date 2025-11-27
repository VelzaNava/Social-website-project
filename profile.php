<?php
session_start();
require "config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION["user_id"];

$stmt = $mysqli->prepare("SELECT username, first_name, last_name, profile_image FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($username, $first, $last, $profile_image);
$stmt->fetch();
$stmt->close();

/* Ensure working default */
if (empty($profile_image) || !file_exists($profile_image)) {
    $profile_image = "assets/images/default.jpg";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Profile</title>
<link rel="stylesheet" href="assets/css/profile.css">
</head>

<body>

<div class="top-bar">
    <a href="feed.php" class="top-btn">Feed</a>
    <a href="logout.php" class="top-btn logout">Logout</a>
</div>

<div class="profile-container">
    <div class="profile-box">

        <img src="<?= htmlspecialchars($profile_image) ?>" class="profile-img">

        <h2>@<?= htmlspecialchars($username) ?></h2>
        <p><?= htmlspecialchars($first) ?> <?= htmlspecialchars($last) ?></p>

        <button class="edit-btn" id="openModal">Edit Profile</button>

    </div>
</div>

<!-- ==== MODAL ==== -->
<div id="profileModal" class="modal"> <!-- FIXED ID HERE -->
    <div class="modal-content">

        <span id="closeModal" class="close-modal">&times;</span>

        <h2>Edit Profile</h2>

        <form action="edit_profile.php" method="POST" enctype="multipart/form-data">

            <!-- Drag & Drop Upload Box -->
            <label>Change Profile Picture:</label>
            <div class="drop-area" id="dropArea">
                <p>Drag & Drop Image Here<br>or click to select</p>
            </div>

            <input type="file" name="profile_image" id="fileInput" accept="image/*" hidden>

            <!-- Username -->
            <label>Username:</label>
            <input type="text" name="username" class="modal-input"
                   value="<?= htmlspecialchars($username) ?>" required>

            <!-- First Name -->
            <label>First Name:</label>
            <input type="text" name="first_name" class="modal-input"
                   value="<?= htmlspecialchars($first) ?>" required>

            <!-- Last Name -->
            <label>Last Name:</label>
            <input type="text" name="last_name" class="modal-input"
                   value="<?= htmlspecialchars($last) ?>" required>

            <button type="submit" class="submit-btn">Save Changes</button>
        </form>

    </div>
</div>

<script src="assets/js/profile_modal.js"></script>

</body>
</html>
