<?php
session_start();
require "config/database.php";

$error = "";

// AUTO DETECT DB CONNECTION VARIABLE
$DB = $mysqli ?? $conn ?? null;

if (!$DB) {
    die("Database connection not found. Check database.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $DB->prepare("SELECT id, password_hash FROM users WHERE username=?");

    if (!$stmt) {
        die("Prepare failed: " . $DB->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hash);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hash)) {
            $_SESSION["user_id"] = $id;
            header("Location: feed.php");
            exit;
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="form-container">
    <h2>Sign in</h2>

    <?php if ($error): ?>
    <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button>Login</button>
    </form>

    <a class="small" href="register.php">Create account</a>
</div>

</body>
</html>
