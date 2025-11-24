<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$loggedIn = isset($_SESSION['user_id']);
?>
<header class="site-header">
  <div class="container header-inner">
    <div class="brand"><a href="index.php">SocialSphere</a></div>

    <nav class="nav">
      <a href="feed.php">Feed</a>
      <?php if ($loggedIn): ?>
        <a href="profile.php">Profile</a>
        <a href="logout.php" class="btn small">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php" class="btn small primary">Sign up</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
