<?php
require "config/database.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $first = $_POST["first_name"];
    $last = $_POST["last_name"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $mysqli->prepare("INSERT INTO users(username, first_name, last_name, password_hash) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $first, $last, $password);

    if ($stmt->execute()) {
        $success = "Account created. You may now login.";
    } else {
        $error = "Username already exists.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Register • Mini Tumblr</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="form-container">
    <h2>Create Account</h2>

    <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
    <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>

    <form method="POST">
        <input name="username" placeholder="Username">
        <input name="first_name" placeholder="First Name">
        <input name="last_name" placeholder="Last Name">
        <input type="password" name="password" placeholder="Password">
        <button>Register</button>
    </form>

    <a class="small" href="login.php">Back to login</a>
</div>

</body>
</html>
