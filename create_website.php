<?php
session_start();
include 'functions.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit;
}

$email = $_SESSION['user_email'];
$existing_website = get_user_website($email);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($existing_website) {
        die("You can only create one website! <a href='dashboard.php'>Go back</a>");
    }

    $website_name = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['website_name']);
    if (!$website_name) die("Invalid website name!");

    $website_path = UPLOAD_DIR . $website_name;

    if (!file_exists($website_path)) {
        mkdir($website_path, 0777, true);
        set_user_website($email, $website_name);
        echo "Website '$website_name' created! <a href='dashboard.php'>Go to Dashboard</a>";
    } else {
        echo "Website already exists!";
    }
}
?>

<link rel="stylesheet" href="styles.css">
<div class="container">
    <h2>Create Your Website</h2>
    <form method="POST">
        <input type="text" name="website_name" placeholder="Website Name" required><br>
        <button type="submit">Create</button>
    </form>
</div>