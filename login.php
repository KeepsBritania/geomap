<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sample user data (replace with database validation in real scenarios)
    $users = [
        'admin' => ['password' => 'admin123', 'role' => 'admin', 'bio' => 'System Admin'],
        'client' => ['password' => 'client123', 'role' => 'client', 'bio' => 'Client User'],
        'fieldworker' => ['password' => 'worker123', 'role' => 'fieldworker', 'bio' => 'Field Worker']
    ];

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (isset($users[$username]) && $users[$username]['password'] == $password) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $users[$username]['role'];
        $_SESSION['bio'] = $users[$username]['bio'];
        header('Location: dashboard.php');  // Redirect to the dashboard
    } else {
        echo "Invalid credentials!";
    }
}
?>

<form method="POST" action="login.php">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
