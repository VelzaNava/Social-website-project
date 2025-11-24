<?php
require "config/database.php";

$error = "";
$success = "";

$DB = $conn ?? $mysqli ?? null;

if (!$DB) {
    die("Database connection missing. Check database.php");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $first = trim($_POST["first_name"]);
    $last = trim($_POST["last_name"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $DB->prepare("
        INSERT INTO users (username, first_name, last_name, password_hash)
        VALUES (?, ?, ?, ?)
    ");

    if (!$stmt) {
        die("SQL error: " . $DB->error);
    }

    $stmt->bind_param("ssss", $username, $first, $last, $password);

    if ($stmt->execute()) {
        $success = "Account created. You may now login.";
    } else {
        $error = "Username already exists OR SQL error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="form-container">
    <h2>Create Account</h2>

    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>

    <form method="POST">
        <input name="username" placeholder="Username" required>
        <input name="first_name" placeholder="First Name" required>
        <input name="last_name" placeholder="Last Name" required>
        <input type="password" name="password" placeholder="Password" required>
        <button>Create Account</button>
    </form>

    <a class="small" href="login.php">Back to login</a>
</div>

</body>
</html>
