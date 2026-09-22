<?php
require_once __DIR__ . '/../config/db.php';

// add activity log
function addActivityLog($user_name, $action, $description)
{
    global $conn;

    $sql = "INSERT INTO activity_logs_tbl
            (
                user_name,
                action,
                description
            )
            VALUES (?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $user_name,
        $action,
        $description
    );

    return mysqli_stmt_execute($stmt);
}

// get all activity log
function getAllActivityLogs()
{
    global $conn;
    $sql = "SELECT
                log_id,
                user_name,
                action,
                description,
                created_at
            FROM activity_logs_tbl
            ORDER BY log_id DESC";

    $result = mysqli_query($conn, $sql);
    return $result;
}