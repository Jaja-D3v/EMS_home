<?php

require_once __DIR__ . '/../model/AuthModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = loginUser($username, $password);

    if (
        $user &&
        $username === $user['LoginID'] &&
        md5($password) === $user['password']
    ) {

        /*
         * Start session
         */
        session_start();

        /*
         * Regenerate session ID after successful login
         */
        session_regenerate_id(true);

        /*
         * Store authenticated user information
         */
        $_SESSION['id'] = $user['id'];
        $_SESSION['LoginID'] = $user['LoginID'];
        $_SESSION['Role'] = $user['Role'];

        /*
         * Start inactivity timer
         */
        $_SESSION['LAST_ACTIVITY'] = time();

        /*
         * Redirect to dashboard
         */
        header('Location: ../../dashboard.php');
        exit();

    } else {

        header(
            'Location: ../../authentication/login.html?error=invalid_credentials'
        );
        exit();
    }
}