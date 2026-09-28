<?php

session_start();

/*
|--------------------------------------------------------------------------
| Destroy PHP Session
|--------------------------------------------------------------------------
*/

// Unset all session variables
$_SESSION = [];

// Delete PHP session cookie
if (ini_get('session.use_cookies')) {

    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// Destroy the session
session_destroy();



http_response_code(200);

header('Content-Type: application/json');

echo json_encode([
    'success' => true,
    'message' => 'Session logged out successfully.'
]);

exit;