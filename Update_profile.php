<?php
session_start();
require "config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
    // handle error — redirect or show message
    header("Location: profile.php");
    exit;
}

// Validate file type (allow only images)
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($_FILES['profile_image']['type'], $allowedTypes)) {
    // invalid file type
    header("Location: profile.php");
    exit;
}

// Prepare destination folder
$uploadDir = __DIR__ . '/assets/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Generate a unique file name
$ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
$newFilename = 'pfp_' . $_SESSION["user_id"] . '_' . time() . '.' . $ext;
$destination = $uploadDir . $newFilename;

// Move uploaded file
if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $destination)) {
    // error
    header("Location: profile.php");
    exit;
}

// Build path to store in DB (web-accessible path)
$imagePath = '/assets/uploads/' . $newFilename;

// Update database
$stmt = $mysqli->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
$stmt->bind_param("si", $imagePath, $_SESSION["user_id"]);
$stmt->execute();
$stmt->close();

header("Location: profile.php");
exit;
