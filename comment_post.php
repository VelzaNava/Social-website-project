<?php
session_start();
require "config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $post_id = (int)$_POST["post_id"];
    $comment = trim($_POST["comment"]);
    $user_id = $_SESSION["user_id"];

    $stmt = $mysqli->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $post_id, $user_id, $comment);
    $stmt->execute();
    header("Location: feed.php");
}
?>