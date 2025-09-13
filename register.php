<?php
include 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $users = get_users();

    // Check if email exists
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            die("Email already registered! <a href='index.php'>Login here</a>");
        }
    }

    $users[] = ["name" => $name, "email" => $email, "password" => $password];
    save_users($users);

    echo "Registration successful! <a href='index.php'>Login here</a>";
}
?>

<link rel="stylesheet" href="styles.css">
<div class="container">
    <h2>Sign Up</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Register</button>
        <a href="index.php">Already have an account? Login</a>
    </form>
</div>