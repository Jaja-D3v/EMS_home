<?php

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

session_set_cookie_params([
    'lifetime' => 3600,
    'path' => '/',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

header('Content-Type: application/json');

$timeout = 300; // 5 minutes

if (
    isset($_SESSION['LAST_ACTIVITY']) &&
    (time() - $_SESSION['LAST_ACTIVITY'] > $timeout)
) {
    session_unset();
    session_destroy();

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "Session expired."
    ]);

    exit;
}

$_SESSION['LAST_ACTIVITY'] = time();

if (!isset($_SESSION['id'])) {

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "Unauthorized."
    ]);

    exit;
}

echo json_encode([
    "success" => true,
    "user" => [
        "id" => $_SESSION['id'],
        "LoginID" => $_SESSION['LoginID'],
        "Role" => $_SESSION['Role']
    ]
]);