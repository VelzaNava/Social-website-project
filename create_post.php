<?php
// create_post.php
session_start();
require_once 'config/database.php'; // adjust path
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authorized']);
    exit;
}

$uid = intval($_SESSION['user_id']);
$content = trim($_POST['content'] ?? '');

if ($content === '' && empty($_FILES['image']['name'])) {
    echo json_encode(['success' => false, 'error' => 'Post empty']);
    exit;
}

// Handle image upload if provided
$image_path = null;
if (!empty($_FILES['image']['name'])) {
    $uploadDir = __DIR__ . '/uploads/posts/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $file = $_FILES['image'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','webp'];
        if (!in_array($ext, $allowed)) {
            echo json_encode(['success' => false, 'error' => 'Unsupported image format']);
            exit;
        }
        $safeName = uniqid('post_', true) . '.' . $ext;
        $destPath = $uploadDir . $safeName;
        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            echo json_encode(['success' => false, 'error' => 'Failed to move uploaded file']);
            exit;
        }
        // Save web path relative to project root for display
        $image_path = 'uploads/posts/' . $safeName;
    } else {
        echo json_encode(['success' => false, 'error' => 'Image upload error']);
        exit;
    }
}

// Insert into DB
$stmt = $mysqli->prepare("INSERT INTO posts (user_id, content, image_path, created_at) VALUES (?, ?, ?, NOW())");
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'DB prepare failed: '.$mysqli->error]);
    exit;
}
$stmt->bind_param('iss', $uid, $content, $image_path);
if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'error' => 'DB execute failed: '.$stmt->error]);
    exit;
}

if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest' || strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) {
    echo json_encode(['success' => true]);
} else {
    // non-AJAX fallback
    header('Location: feed.php');
}
exit;
