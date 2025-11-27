<?php
session_start();
require_once "config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$uid = $_SESSION["user_id"];
$content = trim($_POST["content"] ?? "");
$image_path = null;

// tagagawa ng polder for post
$uploadDir = "uploads/posts/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Handle image upload
if (!empty($_FILES["image"]["name"])) {
    $file = $_FILES["image"];
    $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

    $allowed = ["jpg", "jpeg", "png", "gif", "webp"];
    if (in_array($ext, $allowed)) {
        $newName = uniqid("post_", true) . "." . $ext;
        $fullPath = $uploadDir . $newName;

        if (move_uploaded_file($file["tmp_name"], $fullPath)) {
            $image_path = $fullPath;
        }
    }
}

// Insert post
$stmt = $mysqli->prepare("
    INSERT INTO posts (user_id, content, image_path)
    VALUES (?, ?, ?)
");
$stmt->bind_param("iss", $uid, $content, $image_path);
$stmt->execute();

header("Location: feed.php");
exit;
