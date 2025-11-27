<?php
session_start();
require "config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$uid = $_SESSION["user_id"];

// Current user info
$stmt = $mysqli->prepare("SELECT username, profile_image FROM users WHERE id = ?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();
$current_user = $result->fetch_assoc();

// Other users
$other_users_query = $mysqli->query("SELECT id, username, profile_image FROM users WHERE id != $uid");

// Posts
$post_query = $mysqli->query("
    SELECT posts.id AS post_id, posts.content, posts.image_path, posts.created_at,
           users.username, users.profile_image,
           (SELECT COUNT(*) FROM likes WHERE post_id = posts.id) AS like_count,
           (SELECT COUNT(*) FROM comments WHERE post_id = posts.id) AS comment_count,
           (SELECT COUNT(*) FROM likes WHERE post_id = posts.id AND user_id = $uid) AS user_liked
    FROM posts
    JOIN users ON posts.user_id = users.id
    ORDER BY posts.id DESC
");
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

    <!-- LEFT SIDEBAR -->
    <div class="left-sidebar">
        <?php
        $img = file_exists($current_user['profile_image']) ? $current_user['profile_image'] : "assets/images/default.jpg";
        ?>
        <img src="<?= $img ?>" class="profile-pic">
        <div class="username">@<?= htmlspecialchars($current_user["username"]) ?></div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- Create post -->
        <div class="post-form">
            <button id="openPostModal" class="open-post-btn">Create Post</button>
        </div>

        <!-- FEED -->
        <div class="feed">
            <?php while ($p = $post_query->fetch_assoc()): ?>
            <div class="post-card">

                <?php
                $avatar = file_exists($p["profile_image"]) ? $p["profile_image"] : "assets/images/default.jpg";
                ?>
                <img src="<?= $avatar ?>" class="avatar">

                <div class="post-content">
                    <strong>@<?= htmlspecialchars($p["username"]) ?></strong><br>
                    <small><?= $p["created_at"] ?></small><br><br>

                    <?= nl2br(htmlspecialchars($p["content"])) ?>

                    <?php if (!empty($p["image_path"]) && file_exists($p["image_path"])): ?>
                        <div class="post-image-wrapper">
                            <img src="<?= $p["image_path"] ?>" class="post-image">
                        </div>
                    <?php endif; ?>

                    <!-- LIKE BUTTON -->
                    <form action="like_post.php" method="POST">
                        <input type="hidden" name="post_id" value="<?= $p["post_id"] ?>">
                        <button class="like-btn" type="submit">
                            <?= $p["user_liked"] ? "❤️ Unlike" : "🤍 Like" ?>
                        </button>
                        <span><?= $p["like_count"] ?> Likes</span>
                    </form>

                    <!-- COMMENT SECTION -->
                    <div class="comment-section">
                        <strong><?= $p["comment_count"] ?> Comments</strong>

                        <?php
                        $cid = $p["post_id"];
                        $comments = $mysqli->query("
                            SELECT comments.comment, comments.created_at, users.username
                            FROM comments 
                            JOIN users ON comments.user_id = users.id
                            WHERE comments.post_id = $cid
                            ORDER BY comments.id ASC
                        ");
                        ?>

                        <?php while ($c = $comments->fetch_assoc()): ?>
                            <div class="comment-item">
                                <strong>@<?= htmlspecialchars($c["username"]) ?></strong>:
                                <?= htmlspecialchars($c["comment"]) ?>
                                <br><small><?= $c["created_at"] ?></small>
                            </div>
                        <?php endwhile; ?>

                        <form action="comment_post.php" method="POST" class="comment-form">
                            <input type="hidden" name="post_id" value="<?= $p["post_id"] ?>">
                            <input type="text" name="comment" placeholder="Write a comment..." required>
                            <button type="submit">Comment</button>
                        </form>
                    </div>

                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- RIGHT SIDEBAR -->
    <div class="right-sidebar">
        <h3>Other Users</h3>
        <?php while ($u = $other_users_query->fetch_assoc()): ?>
            <?php
            $uimg = file_exists($u["profile_image"]) ? $u["profile_image"] : "assets/images/default.jpg";
            ?>
            <a href="view_profile.php?user_id=<?= $u["id"] ?>" class="user-item">
                <img src="<?= $uimg ?>" alt="avatar">
                <span>@<?= htmlspecialchars($u["username"]) ?></span>
            </a>
        <?php endwhile; ?>
    </div>
</div>

<!-- POST MODAL -->
<div id="postModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>

        <h2>Create a Post</h2>

        <form action="create_post.php" method="POST" enctype="multipart/form-data">
            <textarea name="content" placeholder="Write something..." required></textarea>
            <div id="dropArea" class="drop-area">
                Drag & Drop Image Here or Click Below
            </div>
            <input type="file" id="imageInput" name="image" accept="image/*">
            <button type="submit">Post</button>
        </form>
    </div>
</div>

<script src="assets/js/feed.js"></script>
<script src="assets/js/secret.js"></script>
</body>
</html>
