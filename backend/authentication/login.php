<?php
include './backend/config/db.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $db_username = 'admin';
    $db_password = 'admin456';

    if($username === $db_username && $password === $db_password) {
        session_start();
        $_SESSION['username'] = $username;
        header('Location: ../../dashboard.php');
        exit();
    } else {
        echo 'Invalid username or password.';
        header('Location: ../../authentication/login.html?error=invalid_credentials');
        exit();
    }
}