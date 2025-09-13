<?php
session_start();
include 'functions.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit;
}

$email = $_SESSION['user_email'];
$website_name = get_user_website($email);
$website_path = "uploads/$website_name/";

if (!$website_name) {
    die("You haven't created a website yet!");
}

// Handle Backup
if (isset($_POST['backup'])) {
    $zip = new ZipArchive();
    $zip_file = "$website_name-backup.zip";
    
    if ($zip->open($zip_file, ZipArchive::CREATE) === TRUE) {
        $files = scandir($website_path);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                $zip->addFile($website_path . $file, $file);
            }
        }
        $zip->close();
        echo "<script>alert('Backup Created!'); window.location.href='$zip_file';</script>";
    } else {
        echo "<script>alert('Backup Failed!');</script>";
    }
}

// Handle Restore
if (isset($_POST['restore']) && isset($_FILES['restore_file'])) {
    $zip_file = $_FILES['restore_file']['tmp_name'];
    $zip = new ZipArchive();

    if ($zip->open($zip_file) === TRUE) {
        $zip->extractTo($website_path);
        $zip->close();
        echo "<script>alert('Website Restored!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Restore Failed!');</script>";
    }
}
?>

<link rel="stylesheet" href="styles.css">
<div class="dashboard-container">
    <h2>🛠 Backup & Restore</h2>

    <form method="POST">
        <button type="submit" name="backup">📦 Create Backup</button>
    </form>

    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="restore_file" accept=".zip" required>
        <button type="submit" name="restore">🔄 Restore Backup</button>
    </form>
</div>