<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>SocialSphere — Connect. Share. Inspire.</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />

  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/home.css?v=1">
</head>
<body>

<main class="page-center">
  <div class="form-container">
    <h2>Welcome to SocialSphere</h2>
    <p class="lead">Connect. Share. Inspire.</p>

    <div class="actions">
      <a href="register.php" class="btn"> <i class="bx bxs-user-plus"></i> Register</a>
      <a href="login.php" class="btn"> <i class="bx bxs-log-in-circle"></i> Login</a>
    </div>
  </div>
</main>

<?php require_once 'includes/footer.php'; 
?>

<script src="assets/js/secret.js"></script>

</body>
</html>
