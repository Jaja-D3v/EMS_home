<?php

require_once __DIR__ . '/../config/db.php';

// Get All Fire Extinguisher 

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

// Get Fire Extinguisher by their id 

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

// Get Fire Extinguisher by their code 

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

// this function model is for adding new extinguisher
function addNewFireExtinguisherModel($code, $type, $capacity, $location, $date_acquired, $expiration_date, $status)
{
    global $conn;
    $sql = "INSERT INTO fire_extinguishers_tbl
            (extinguisher_code, type, capacity, location, date_acquired, expiration_date, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sssssss",
        $code,
        $type,
        $capacity,
        $location,
        $date_acquired,
        $expiration_date,
        $status
    );

    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        return "Fire extinguisher added successfully.";
    }

    return "Failed to add fire extinguisher.";
}

// this is for delete fire extinguisher by id 
function deleteFireExtinguisherById($id) {
    global $conn;
    $sql = "DELETE FROM fire_extinguishers_tbl
    WHERE extinguisher_id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}
