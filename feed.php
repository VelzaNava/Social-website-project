<?php
session_start();
require "config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$uid = $_SESSION["user_id"];

// Fetch current user data
$user_query = $mysqli->query("SELECT username, profile_image FROM users WHERE id = $uid");
if (!$user_query) {
    die("Database error: " . $mysqli->error);
}
$current_user = $user_query->fetch_assoc();

if (!$current_user) {
    die("User not found in database.");
}

// Fetch other users
$other_users_query = $mysqli->query("SELECT id, username, profile_image FROM users WHERE id != $uid");
if (!$other_users_query) {
    die("Database error: " . $mysqli->error);
}

// Fetch posts
$post_query = $mysqli->query("
    SELECT posts.content, posts.created_at, users.username, users.profile_image
    FROM posts
    JOIN users ON posts.user_id = users.id
    ORDER BY posts.id DESC
");
if (!$post_query) {
    die("Database error: " . $mysqli->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Feed</title>
    <link rel="stylesheet" href="assets/css/feed.css">
</head>
<body>
    <div class="top-bar">
        <button onclick="location.href='profile.php'">Profile</button>
        <button onclick="location.href='logout.php'">Logout</button>
    </div>

    <div class="container">
        <!-- Left Sidebar: Profile Section -->
        <div class="left-sidebar">
            <?php
            $profile_img = htmlspecialchars($current_user['profile_image']);
            if (empty($profile_img) || !file_exists($_SERVER['DOCUMENT_ROOT'] . '/Social_Sphere/' . $profile_img)) {
                $profile_img = 'assets/images/default.jpg';
            }
            ?>
            <img src="<?= $profile_img ?>" alt="Profile Picture" class="profile-pic">
            <div class="username">@<?= htmlspecialchars($current_user['username']) ?></div>
        </div>

        <!-- Main Content Area: Feed -->
        <div class="main-content">
            <div class="post-form">
                <form action="create_post.php" method="POST">
                    <textarea name="content" placeholder="What's on your mind?" required></textarea>
                    <button type="submit">Post</button>
                </form>
            </div>

            <div class="feed">
                <?php while ($p = $post_query->fetch_assoc()): ?>
                <div class="post-card">
                    <?php
                    $avatar_img = htmlspecialchars($p['profile_image']);
                    if (empty($avatar_img) || !file_exists($_SERVER['DOCUMENT_ROOT'] . '/Social_Sphere/' . $avatar_img)) {
                        $avatar_img = 'assets/images/default.jpg';
                    }
                    ?>
                    <img src="<?= $avatar_img ?>" alt="Avatar" class="avatar">
                    <div class="post-content">
                        <strong>@<?= htmlspecialchars($p['username']) ?></strong><br>
                        <span><?= htmlspecialchars($p['content']) ?></span><br>
                        <small><?= htmlspecialchars($p['created_at']) ?></small>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Right Sidebar: Other Users -->
        <div class="right-sidebar">
            <h3>Other Users</h3>
            <?php while ($u = $other_users_query->fetch_assoc()): ?>
            <a href="profile.php?user_id=<?= $u['id'] ?>" class="user-item">
                <?php
                $user_img = htmlspecialchars($u['profile_image']);
                if (empty($user_img) || !file_exists($_SERVER['DOCUMENT_ROOT'] . '/Social_Sphere/' . $user_img)) {
                    $user_img = 'assets/images/default.jpg';
                }
                ?>
                <img src="<?= $user_img ?>" alt="User Avatar">
                <span>@<?= htmlspecialchars($u['username']) ?></span>
            </a>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
