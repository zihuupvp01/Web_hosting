<?php
session_start();
include 'functions.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit;
}

$email = $_SESSION['user_email'];
$website_name = get_user_website($email);

if (!$website_name) {
    die("You haven't created a website yet! <a href='create_website.php'>Create now</a>");
}

$website_path = UPLOAD_DIR . $website_name;
$files = scandir($website_path);
$files = array_diff($files, array('.', '..'));

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['file'])) {
    if (count($files) >= 6) {
        die("You can only upload 6 files! <a href='dashboard.php'>Go back</a>");
    }

    $file = $_FILES['file'];
    if ($file['size'] > 8 * 1024 * 1024) {
        die("File size exceeds 8MB! <a href='dashboard.php'>Go back</a>");
    }

    $file_path = $website_path . '/' . basename($file['name']);
    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        echo "File uploaded successfully! <a href='dashboard.php'>Go back</a>";
    } else {
        echo "Upload failed!";
    }
}
?>

<link rel="stylesheet" href="styles.css">
<div class="container">
    <h2>Upload Files</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="file" required><br>
        <button type="submit">Upload</button>
    </form>
</div>