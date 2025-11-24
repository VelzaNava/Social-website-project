<?php
session_start();
require "config/database.php";

if (!isset($_SESSION["user_id"])) header("Location: login.php");

$uid = $_SESSION["user_id"];

$post_query = $mysqli->query("
  SELECT posts.content, posts.created_at, users.username, users.profile_image
  FROM posts
  JOIN users ON posts.user_id = users.id
  ORDER BY posts.id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<title>News Feed</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="top-bar">
    <a href="profile.php">Profile</a>
    <a href="logout.php">Logout</a>
</div>

<div class="post-form">
    <form action="create_post.php" method="POST">
        <textarea name="content" placeholder="What's on your mind?"></textarea>
        <button>Post</button>
    </form>
</div>

<div class="feed">
    <?php while ($p = $post_query->fetch_assoc()): ?>
    <div class="post-card">
        <img src="<?= $p['profile_image'] ?>" class="avatar">
        <div class="post-content">
            <strong>@<?= $p['username'] ?></strong><br>
            <span><?= $p['content'] ?></span><br>
            <small><?= $p['created_at'] ?></small>
        </div>
    </div>
    <?php endwhile; ?>
</div>

</body>
</html>
