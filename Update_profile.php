<?php
// Still working in progress//
session_start();
require "config\database.php";

if (!isset($_SESSION["user_id"])) header("Location: login.php");

$id = $_SESSION["user_id"];

$stmt = $mysqli->prepare("SELECT username, first_name, last_name, profile_image FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($username, $first, $last, $image);
$stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Profile</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="form-container">
    <h2>Edit Profile Account</h2>
<form method="POST" >

    <label>Username:</label><br>
    <input name="username" value="<?= $user['username'] ?>" ><br><br>
    <label>First Name:</label><br>
    <input name="first_name" value="<?= $user['first_name'] ?>" ><br><br>
    <label>Last Name:</label><br>
    <input name="last_name" value="<?= $user['last_name'] ?>" ><br><br>
    <label>Current Profile Picture:</label><br>
    <img src="uploads/<?= $user['profile_image'] ?>" width="120"><br><br>
    <label>Upload New Profile Picture:</label><br>
    <input type="file" name="profile_image"><br><br>
    <button type="submit">Save Changes</button>
</form>
    <a class="small" href="profile.php"> Back to Profile </a>
</div>
<div class="top-bar">
    <a href="feed.php">Feed</a>
    <a href="logout.php">Logout</a>
</div>

<div class="profile-box">
    <img src="<?= $image ?>" class="profile-img">
    <h2>@<?= $username ?></h2>
    <p><?= $first ?> <?= $last ?></p>
</div>

</body>
</html>