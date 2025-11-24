<?php
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
<title>My Profile</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

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
