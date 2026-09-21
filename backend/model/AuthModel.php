<?php
require_once __DIR__ . '/../config/db_user.php';


function loginUser($loginID, $password)
{
    global $conn;

    $sql = "SELECT LoginID, password
            FROM kane_users_login
            WHERE LoginID = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $loginID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    if (!$user) {
        return false;
    }

    return $user;
}
