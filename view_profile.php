<?php
session_start();
require "config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['user_id'])) {
    header("Location: feed.php");
    exit();
}

$user_id = (int)$_GET['user_id'];

$stmt = $mysqli->prepare("SELECT username, first_name, last_name, profile_image FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $first, $last, $profile_image);
$stmt->fetch();
$stmt->close();

if (empty($profile_image) || !file_exists($profile_image)) {
    $profile_image = "assets/images/default.jpg";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>@<?= htmlspecialchars($username) ?>'s Profile</title>
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

    </div>
</div>

</body>
</html>
