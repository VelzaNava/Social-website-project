<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>SocialSphere — Connect. Share. Inspire.</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <link rel="stylesheet" href="assets/css/home.css">
  <script defer src="assets/js/home.js"></script>
</head>
<body>
  <?php require_once "includes/header.php"; ?>

  <main class="hero">
    <div class="hero-inner">
      <div class="hero-copy">
        <h1>Connect. Share. Inspire.</h1>
        <p class="lead">A minimalistic social website for sharing your thoughts with others.</p>
        <div class="cta">
          <a class="btn primary" href="register.php">Get Started</a>
          <a class="btn outline" href="login.php">Sign In</a>
        </div>
      </div>

      <div class="hero-art">
        <div class="card-grid">
          <div class="card">Post your ideas</div>
          <div class="card">View timelines</div>
          <div class="card">Connect with others</div>
        </div>
      </div>
    </div>
  </main>

  <section class="features">
    <div class="container">
      <div class="feature">
        <h3>Simple</h3>
        <p>Clean and user friendly interface</p>
      </div>
      <div class="feature">
        <h3>Fast</h3>
        <p>Powered by BaronGBT</p>
      </div>
      <div class="feature">
        <h3>Minimalist</h3>
        <p>Designed with minimal colors</p>
      </div>
    </div>
  </section>

  <?php require_once "includes/footer.php"; ?>
</body>
</html>
