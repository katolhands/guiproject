<?php
session_start();
include(__DIR__ . '/includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Registered Successfully! Please login.'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Registration Failed!');</script>";
    }
}
?>

<link rel="stylesheet" href="assets/style.css">

<div class="container">
    <h2>Register</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Register</button>
    </form>
    <a href="index.php">Back to Login</a>
</div>
