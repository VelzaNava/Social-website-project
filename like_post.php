<?php
session_start();
require "config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
// like and unlike post
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $post_id = (int)$_POST["post_id"];
    $user_id = $_SESSION["user_id"];
    // checking
    $check = $mysqli->query("SELECT id FROM likes WHERE post_id = $post_id AND user_id = $user_id");

    if ($check->num_rows > 0) {
        $mysqli->query("DELETE FROM likes WHERE post_id = $post_id AND user_id = $user_id");
    } else {
        $mysqli->query("INSERT INTO likes (post_id, user_id) VALUES ($post_id, $user_id)");
    }

    header("Location: feed.php");
}
?>
