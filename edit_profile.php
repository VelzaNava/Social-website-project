    <?php
    session_start();
    require "config/database.php";

    if (!isset($_SESSION["user_id"])) header("Location: login.php");

    $id = $_SESSION["user_id"];

    // Load current user info
    $stmt = $mysqli->prepare("SELECT username, first_name, last_name, profile_image FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($username, $first, $last, $image);
    $stmt->fetch();
    $stmt->close();

        // If user submitted the form
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $new_username = $_POST["username"];
        $new_first = $_POST["first_name"];
        $new_last = $_POST["last_name"];

        // Check if username is taken by another user
        $check = $mysqli->prepare("SELECT id FROM users WHERE username=? AND id!=?");
        $check->bind_param("si", $new_username, $id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
        $error = "Username is already taken.";
         } else {
        // If user uploaded a new image
        if (!empty($_FILES["profile_image"]["name"])) {
            $image_name = "uploads/" . time() . "_" . basename($_FILES["profile_image"]["name"]);
            move_uploaded_file($_FILES["profile_image"]["tmp_name"], $image_name);

            $stmt = $mysqli->prepare("UPDATE users SET username=?, first_name=?, last_name=?, profile_image=? WHERE id=?");
            $stmt->bind_param("ssssi", $new_username, $new_first, $new_last, $image_name, $id);
        } else {
            // No new image uploaded
            $stmt = $mysqli->prepare("UPDATE users SET username=?, first_name=?, last_name=? WHERE id=?");
            $stmt->bind_param("sssi", $new_username, $new_first, $new_last, $id);
        }
    

        $stmt->execute();
        header("Location: profile.php");
        exit();
    }
}

    ?>
    <!DOCTYPE html>
    <html>
    <head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
        
    <div class="top-bar">
        <a href="feed.php">Feed</a>
        <a href="logout.php">Logout</a>
    </div>

    <h1>Edit Profile</h1>
    <div class="form-container">


        <form method="POST">
            <!-- CURRENT PROFILE PHOTO -->
            <img src="<?= $image ?>" width="120" height="120"><br><br>

            <div class = "EditProfile_inputbox">
                <label>Change Photo:</label><br>
                <input type="file" name="profile_image">

            </div>

            <!-- USERNAME -->
            <div class = "EditProfile_inputbox">
                <input type="text" name="username" placeholder="Username" value="<?= $username ?>" required>
            </div>

            <!-- FIRST NAME -->
            <div class = "EditProfile_inputbox">
                <input type="text" name="first_name" placeholder="Fiest Name" value="<?= $first ?>" required>
            </div>

            <!-- LAST NAME -->
            <div class = "EditProfile_inputbox">
                <input type="text" name="last_name" placeholder="Last Name" value="<?= $last ?>" required>
            </div>

            <button type="submit">Save Changes</button>
        </form>
    </div>

    </body>
    </html>
