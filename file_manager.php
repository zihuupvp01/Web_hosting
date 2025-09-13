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

// Handle file deletion
if (isset($_POST['delete'])) {
    $file = $_POST['file'];
    if (file_exists($website_path . $file)) {
        unlink($website_path . $file);
        echo "<script>alert('File deleted!'); window.location.href='file_manager.php';</script>";
    }
}

// Handle renaming files
if (isset($_POST['rename'])) {
    $old_name = $_POST['old_name'];
    $new_name = $_POST['new_name'];
    if (file_exists($website_path . $old_name)) {
        rename($website_path . $old_name, $website_path . $new_name);
        echo "<script>alert('File renamed!'); window.location.href='file_manager.php';</script>";
    }
}

$files = array_diff(scandir($website_path), array('.', '..'));
?>

<link rel="stylesheet" href="styles.css">
<div class="dashboard-container">
    <h2>📁 File Manager - <?php echo $website_name; ?></h2>

    <table>
        <tr>
            <th>Filename</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($files as $file): ?>
        <tr>
            <td><?php echo $file; ?></td>
            <td>
                <a href="<?php echo $website_path . $file; ?>" target="_blank">🔍 View</a>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="file" value="<?php echo $file; ?>">
                    <button type="submit" name="delete">🗑 Delete</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="old_name" value="<?php echo $file; ?>">
                    <input type="text" name="new_name" placeholder="Rename">
                    <button type="submit" name="rename">✏ Rename</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="upload.php"><button class="upload-btn">📤 Upload New File</button></a>
</div>