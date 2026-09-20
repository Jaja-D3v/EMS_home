<?php

require_once './backend/config/db.php';

function getAll()
{
    global $conn;

    $sql = "SELECT
                extinguisher_id,
                extinguisher_code,
                type,
                capacity,
                location,
                date_acquired,
                expiration_date,
                status,
                create_at,
                updated_at
            FROM fire_extinguishers_tbl
            ORDER BY extinguisher_id DESC";

    $result = mysqli_query($conn, $sql);

    return $result;
}


function getById($id)
{
    global $conn;

    $sql = "SELECT
                extinguisher_id,
                extinguisher_code,
                type,
                capacity,
                location,
                date_acquired,
                expiration_date,
                status,
                create_at,
                updated_at
            FROM fire_extinguishers_tbl
            WHERE extinguisher_id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result);
}

function getByCode($code)
{
    global $conn;

    $sql = "SELECT *
            FROM fire_extinguishers_tbl
            WHERE extinguisher_code = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $code);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result);
}