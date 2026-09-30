<?php

require_once __DIR__ . '/../model/AuthModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $branch = $_POST['branch'] ?? '';



    $user = loginUser($username, $password);

    if (
        $user &&
        $username === $user['LoginID'] &&
        md5($password) === $user['password'] &&  !empty($branch)
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
        $_SESSION['EmployeeName'] = $user['EmployeeName'];
        $_SESSION['branch'] = $branch;

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
            'Location: ../../authentication/login.php?error=invalid_credentials'
        );
        exit();
    }
}