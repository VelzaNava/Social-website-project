<?php
// create_post.php
session_start();
require_once 'config/database.php'; // adjust if needed

// For AJAX compatibility
header('Content-Type: application/json; charset=utf-8');

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authorized']);
    exit;
}

$uid = intval($_SESSION['user_id']);
$content = trim($_POST['content'] ?? '');

// Prevent empty post
if ($content === '' && empty($_FILES['image']['name'])) {
    echo json_encode(['success' => false, 'error' => 'Post is empty']);
    exit;
}

// --------------------------------------------------
// IMAGE UPLOAD HANDLING
// --------------------------------------------------
$image_path = null;

if (!empty($_FILES['image']['name'])) {

    $uploadDir = __DIR__ . '/uploads/posts/';

    // Create folder if missing
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $file = $_FILES['image'];

    if ($file['error'] === UPLOAD_ERR_OK) {

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($ext, $allowed)) {
            echo json_encode(['success' => false, 'error' => 'Unsupported image format']);
            exit;
        }

        // safe unique name
        $newName = uniqid("post_", true) . "." . $ext;
        $fullPath = $uploadDir . $newName;

        // Move file to uploads/posts/
        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            echo json_encode(['success' => false, 'error' => 'Failed to save uploaded image']);
            exit;
        }

        // Path stored in database (web-accessible)
        $image_path = "uploads/posts/" . $newName;

    } else {
        echo json_encode(['success' => false, 'error' => 'Image upload error']);
        exit;
    }
}

// --------------------------------------------------
// INSERT POST INTO DATABASE
// --------------------------------------------------
$stmt = $mysqli->prepare("
    INSERT INTO posts (user_id, content, image_path, created_at)
    VALUES (?, ?, ?, NOW())
");

if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'DB prepare failed: ' . $mysqli->error]);
    exit;
}

$stmt->bind_param("iss", $uid, $content, $image_path);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'error' => 'DB execute failed: ' . $stmt->error]);
    exit;
}

// --------------------------------------------------
// SUCCESS RESPONSE
// --------------------------------------------------
$isAjax =
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest' ||
    str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');

if ($isAjax) {
    echo json_encode(['success' => true]);
} else {
    // Normal redirect fallback
    header("Location: feed.php");
}

exit;
