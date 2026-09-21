<?php
require_once __DIR__ . '/../model/AuthModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = loginUser($username, $password);

    if ($username === $user['LoginID'] && md5($password) === $user['password']) {
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
