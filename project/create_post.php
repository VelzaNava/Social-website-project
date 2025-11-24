<?php
session_start();
require "config/database.php";
// this should have a popup window to make a post//
if (!isset($_SESSION["user_id"])) exit;

$content = trim($_POST["content"]);
if ($content !== "") {
    $stmt = $mysqli->prepare("INSERT INTO posts(user_id, content) VALUES (?, ?)");
    $stmt->bind_param("is", $_SESSION["user_id"], $content);
    $stmt->execute();
}

header("Location: feed.php");
?>
