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

    <!-- Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Updated CSS -->
    <link rel="stylesheet" href="assets/css/login.css?v=3">
</head>

<body>

<div id="design" class="designs"></div>

<div id="form" class="form-container">

    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">

        <h2>Login</h2>

        <div class="inputbox">
            <input type="text" name="username" placeholder="Username" required>
            <i class='bx bxs-user'></i>
        </div>

        <div class="inputbox">
            <input type="password" name="password" placeholder="Password" required>
            <i class='bx bxs-lock'></i>
        </div>

        <div class="ForgotPass">
            <a href="#">Forgot Password?</a>
        </div>

        <!-- Login Button -->
        <button type="submit" class="btn">Login</button>

        <div class="register-link">
            <p>
                Don't have an account?
                <a href="register.php">Register</a>
            </p>
        </div>

    </form>

</div>

</body>
</html>
