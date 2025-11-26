<?php
session_start();
require "config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $content = trim($_POST["content"]);
    $user_id = $_SESSION["user_id"];
    $image_path = null;

    // Handle image upload
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $upload_dir = "assets/uploads/"; // Create this folder
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        $image_name = uniqid() . "_" . basename($_FILES["image"]["name"]);
        $image_path = $upload_dir . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
    }

    $stmt = $mysqli->prepare("INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $content, $image_path);
    $stmt->execute();
    header("Location: feed.php"); // Redirect back to feed
}
?>