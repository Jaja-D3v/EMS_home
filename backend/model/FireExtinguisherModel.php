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
                manufactured_date,
                class,
                placement,
                condition_status,
                remarks,
                expiration_date,
                created_at,
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
                manufactured_date,
                class,
                placement,
                condition_status,
                remarks,
                expiration_date,
                created_at,
                updated_at,
                refilled_date
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
    $sql = "SELECT 
                extinguisher_code,
                location,
                type,
                capacity,
                class
            FROM fire_extinguishers_tbl
            WHERE extinguisher_code = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $code);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

// this function model is for adding new extinguisher
function addNewFireExtinguisherModel(
    $code,
    $type,
    $capacity,
    $location,
    $manufactured_date,
    $class,
    $placement,
    $condition_status,
    $remarks,
    $expiration_date
) {
    global $conn;
    $sql = "INSERT INTO fire_extinguishers_tbl
            (
            extinguisher_code,
            type,
            capacity,
            location,
            manufactured_date,
            class,
            placement,
            condition_status,
            remarks,
            expiration_date
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssss",
        $code,
        $type,
        $capacity,
        $location,
        $manufactured_date,
        $class,
        $placement,
        $condition_status,
        $remarks,
        $expiration_date
    );

    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        return true;
    } else {
        return false;
    }
}

// this function model is for updating existing extinguisher
function updateFireExtinguisherModel(
    $id,
    $code,
    $type,
    $capacity,
    $location,
    $manufactured_date,
    $class,
    $placement,
    $condition_status,
    $remarks,
    $expiration_date
) {
    global $conn;

    $sql = "UPDATE fire_extinguishers_tbl
            SET
                extinguisher_code = ?,
                type = ?,
                capacity = ?,
                location = ?,
                manufactured_date = ?,
                class = ?,
                placement = ?,
                condition_status = ?,
                remarks = ?,
                expiration_date = ?
            WHERE extinguisher_id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssssi",
        $code,
        $type,
        $capacity,
        $location,
        $manufactured_date,
        $class,
        $placement,
        $condition_status,
        $remarks,
        $expiration_date,
        $id
    );

    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        return true;
    } else {
        return false;
    }
}

// this is for delete fire extinguisher by id 
function deleteFireExtinguisherById($id)
{
    global $conn;
    $sql = "DELETE FROM fire_extinguishers_tbl
    WHERE extinguisher_id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    if ($stmt) {
        return true;
    } else {
        return false;
    }
}


// nearly expiring fire extinguisher
function getExpiringFireExtinguishers()
{
    global $conn;
    $sql = "SELECT COUNT(*) AS total
            FROM fire_extinguishers_tbl
            WHERE expiration_date >= CURDATE()
            AND expiration_date <= DATE_ADD(CURDATE(), INTERVAL 2 MONTH)";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        return 0;
    }

    $row = mysqli_fetch_assoc($result);
    return (int) $row['total'];
}


// count all registered fire extinguishers
function getAllFireExtinguishersCount()
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total
            FROM fire_extinguishers_tbl";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        return 0;
    }

    $row = mysqli_fetch_assoc($result);

    return (int) $row['total'];
}


// count all spare fire extinguishers
function getSpareFireExtinguishersCount()
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total
            FROM fire_extinguishers_tbl
            WHERE location = 'Storage'";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        return 0;
    }

    $row = mysqli_fetch_assoc($result);

    return (int) $row['total'];
}

// get total count of spare with good condition
function getGoodSpareFireExtinguishersCount()
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total
            FROM fire_extinguishers_tbl
            WHERE location = 'Storage'
            AND condition_status = 'Good'";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        return 0;
    }

    $row = mysqli_fetch_assoc($result);
    return (int) $row['total'];
}


// this is for count of not good 

function getNotGoodSpareFireExtinguishersCount()
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total
            FROM fire_extinguishers_tbl
            WHERE location = 'Storage'
            AND condition_status = 'Not Good'";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        return 0;
    }

    $row = mysqli_fetch_assoc($result);

    return (int) $row['total'];
}


// this is to get all the installed fire extinguisher
function getInstalledFireExtinguishersCount()
{
    global $conn;
    $sql = "SELECT COUNT(*) AS total
            FROM fire_extinguishers_tbl
            WHERE location != 'Storage'";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        return 0;
    }

    $row = mysqli_fetch_assoc($result);

    return (int) $row['total'];
}

// this is to get all the installed with good condition
function getGoodInstalledFireExtinguishersCount()
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total
            FROM fire_extinguishers_tbl
            WHERE location != 'Storage'
            AND condition_status = 'Good'";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        return 0;
    }

    $row = mysqli_fetch_assoc($result);

    return (int) $row['total'];
}

// this is to get all the installed with not good condition
function getNotGoodInstalledFireExtinguishersCount()
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total
            FROM fire_extinguishers_tbl
            WHERE location != 'Storage'
            AND condition_status = 'Not Good'";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        return 0;
    }

    $row = mysqli_fetch_assoc($result);

    return (int) $row['total'];
}


// get all good
function getAllGoodCondition () {
    global $conn;

    $sql = "SELECT COUNT(*) AS total
            FROM fire_extinguishers_tbl
            WHERE condition_status = 'Good';";

     $result = mysqli_query($conn, $sql);

    if (!$result) {
        return 0;
    }

    $row = mysqli_fetch_assoc($result);

    return (int) $row['total'];
}

// get all not good
function getAllNotGoodCondition () {
    global $conn;

    $sql = "SELECT COUNT(*) AS total
            FROM fire_extinguishers_tbl
            WHERE condition_status = 'Not Good'";
            
     $result = mysqli_query($conn, $sql);

    if (!$result) {
        return 0;
    }

    $row = mysqli_fetch_assoc($result);

    return (int) $row['total'];

}


// get next code 
function getNextFireExtinguisherCodeModel()
{
    global $conn;

    $sql = "SELECT extinguisher_code
            FROM fire_extinguishers_tbl
            WHERE extinguisher_code LIKE 'FE-%'
            ORDER BY CAST(SUBSTRING(extinguisher_code, 4) AS UNSIGNED) DESC
            LIMIT 1";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        return 'FE-001';
    }

    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        return 'FE-001';
    }

    $lastNumber = (int) substr($row['extinguisher_code'], 3);

    return 'FE-' . str_pad(
        $lastNumber + 1,
        3,
        '0',
        STR_PAD_LEFT
    );
}


// this function is for updating refilled date 
function update_refilled($ext_code)
{
    global $conn;

    $sql = "UPDATE fire_extinguishers_tbl
            SET
                refilled_date = CURDATE(),
                expiration_date = DATE_ADD(CURDATE(), INTERVAL 3 YEAR)
            WHERE extinguisher_code = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $ext_code
    );

    return mysqli_stmt_execute($stmt);
}

// This function is for updating remarks
function update_remarks($remarks, $ext_code)
{
    global $conn;

    $sql = "UPDATE fire_extinguishers_tbl
            SET
                remarks = ?
            WHERE extinguisher_code = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $remarks,
        $ext_code
    );

    return mysqli_stmt_execute($stmt);
}

// this function is for updating fe status 
function update_status($status, $ext_code)
{
    global $conn;

    $sql = "UPDATE fire_extinguishers_tbl
            SET
                condition_status = ?
            WHERE extinguisher_code = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $status,
        $ext_code
    );

    return mysqli_stmt_execute($stmt);
}
