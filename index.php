<?php
include 'functions.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $users = get_users();
    foreach ($users as $user) {
        if ($user['email'] === $email && password_verify($password, $user['password'])) {
            $_SESSION['user_email'] = $user['email'];
            header("Location: dashboard.php");
            exit;
        }
    }
    echo "Invalid email or password!";
}
?>

<link rel="stylesheet" href="styles.css">
<div class="container">
    <h2>Login</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
        <a href="register.php">Don't have an account? Sign Up</a>
        <a href="forget_pass.php">Forgot Password?</a>
    </form>
</div>