<?php session_start();
include 'functions.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit;
}

$email = $_SESSION['user_email'];
$website_name = get_user_website($email);
$website_path = "uploads/$website_name/";

$total_size = $website_name ? get_folder_size($website_path) : 0;
$total_size_mb = round($total_size / (1024 * 1024), 2);
$file_count = $website_name ? count(glob("$website_path/*")) : 0;
$max_size = 8; // 8MB limit

?>

<link rel="stylesheet" href="styles.css">
<div class="dashboard-container">
    <h2>Welcome, <?php echo $email; ?>!</h2>

    <?php if ($website_name): ?>
        <div class="website-card">
            <p><strong>Your Website:</strong> <?php echo $website_name; ?></p>
            <p><strong>File Count:</strong> <?php echo $file_count; ?>/6</p>
            <p><strong>Disk Usage:</strong> <?php echo $total_size_mb; ?>MB / 8MB</p>

            <?php if ($total_size_mb >= 7.5): ?>
                <p style="color: red;"><b>⚠ Warning:</b> You are close to the 8MB limit!</p>
            <?php endif; ?>

            <a href="file_manager.php"><button class="manage-btn">📂 File Manager</button></a>
            <a href="uploads/<?php echo $website_name; ?>/"><button class="go-btn">🌐 Go to Website</button></a>

            <form method="POST">
                <button class="delete-btn" name="delete_website">🗑 Delete Website</button>
            </form>
        </div>

        <!-- Backup & Restore -->
        <div class="backup-restore">
            <h3>🔄 Backup & Restore</h3>
            <form method="POST">
                <button class="backup-btn" name="backup">📦 Backup</button>
            </form>

            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="restore_file" accept=".zip" required>
                <button class="restore-btn" name="restore">🔄 Restore</button>
            </form>
        </div>

        <!-- Recent Activity Log -->
        <div class="activity-log">
            <h3>📌 Recent Activity</h3>
            <p>No recent activity (Coming soon!)</p>
        </div>
    <?php else: ?>
        <a href="create_website.php"><button class="create-btn">➕ Create Website</button></a>
    <?php endif; ?>

    <div class="links">
        <a href="https://t.me/teamxcutehacker" target="_blank">🔗 Owner's Channel</a>
        <a href="support.php">📩 Support</a>
        <a href="copyright.php">⚖️ Copyright Infringement</a>
        <a href="logout.php">🚪 Logout</a>
    </div>
</div>

<?php
// Handle Backup
if (isset($_POST['backup'])) {
    $zip = new ZipArchive();
    $zip_file = "$website_name-backup.zip";
    
    if ($zip->open($zip_file, ZipArchive::CREATE) === TRUE) {
        foreach (glob("$website_path/*") as $file) {
            $zip->addFile($file, basename($file));
        }
        $zip->close();
        echo "<script>alert('Backup Created!'); window.location.href='$zip_file';</script>";
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
    }
}

// Handle Website Deletion
if (isset($_POST['delete_website']) && $website_name) {
    array_map('unlink', glob("$website_path/*"));
    rmdir($website_path);
    set_user_website($email, null);
    echo "<script>alert('Website deleted!'); window.location.href='dashboard.php';</script>";
}
?>