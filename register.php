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
        // Redirect to login page after successful registration
        header("Location: login.php");
        exit();
    } else {
        $error = "Username already exists OR SQL error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Register</title>

<link rel="stylesheet" href="assets/css/register.css?v=2">

</head>
<body>

<div class="form-container">

    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>

    <form method="POST">
    <h2>Create Account</h2>
        <div class = "registerinputbox">
            <input name="username" 
            placeholder="Username" 
            required>
        </div>

        <div class = "registerinputbox">
        <input name="first_name" 
        placeholder="First Name" 
        required>
        </div>

        <div class = "registerinputbox">
        <input name="last_name" 
        placeholder="Last Name" 
        required>
        </div>

        <div class = "registerinputbox">
        <input type="password" 
        name="password" 
        placeholder="Password" 
        required>
        </div>

        <button type = "register" class="Regbutton"> Sign Up </button>

        <div class = "accAlready">
            <a href = "Index.php"> Back to Home </a>
        </div>

    </form>

</div>

<script src="assets/js/secret.js"></script>

</body>
</html>
