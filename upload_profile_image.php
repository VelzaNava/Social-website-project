<?php
session_start();
require "config/database.php";

if (!isset($_SESSION["user_id"])) exit();

$id = $_SESSION["user_id"];

$upload_dir = "uploads/profile/";

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if (!empty($_FILES["profile_image"]["name"])) {
    $filename = time() . "_" . basename($_FILES["profile_image"]["name"]);
    $target = $upload_dir . $filename;

    move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target);

    $stmt = $mysqli->prepare("UPDATE users SET profile_image=? WHERE id=?");
    $stmt->bind_param("si", $target, $id);
    $stmt->execute();
}

header("Location: profile.php");
exit();
?>
