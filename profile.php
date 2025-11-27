<?php
session_start();
require "config/database.php";

if (!isset($_SESSION["user_id"])) header("Location: login.php");

$id = $_SESSION["user_id"];

$stmt = $mysqli->prepare("SELECT username, first_name, last_name, profile_image FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($username, $first, $last, $image);
$stmt->fetch();

if (empty($image)) {
    $image = "assets/images/default.jpg";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="assets/css/profile.css">
</head>
<body>

<div class="top-bar">
    <a href="feed.php">Feed</a>
    <a href="logout.php">Logout</a>
</div>

<div class="profile-box">
    <img src="<?= htmlspecialchars($image) ?>" class="profile-img">

    <h2>@<?= htmlspecialchars($username) ?></h2>
    <p><?= htmlspecialchars($first) ?> <?= htmlspecialchars($last) ?></p>

    <form action="upload_profile_image.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="profile_image" accept="image/*">
        <button type="submit">Upload New Image</button>
    </form>
</div>

</body>
</html>
