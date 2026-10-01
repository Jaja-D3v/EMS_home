<?php

require_once __DIR__ . '/../config/db.php';


// =====================================================
// GET SESSION BRANCH
// =====================================================

function getCurrentBranch()
{
    return $_SESSION['branch'] ?? null;
}


// =====================================================
// GET ALL FIRE EXTINGUISHERS
// =====================================================

function getAll($limit, $offset)
{
    global $conn;

    $branch = getCurrentBranch();

    if (empty($branch)) {
        return false;
    }

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
                branch
            FROM fire_extinguishers_tbl
            WHERE archived = 0
              AND LOWER(TRIM(branch)) = LOWER(TRIM(?))
            ORDER BY extinguisher_id DESC
            LIMIT ? OFFSET ?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sii",
        $branch,
        $limit,
        $offset
    );

    mysqli_stmt_execute($stmt);

    return mysqli_stmt_get_result($stmt);
}


// =====================================================
// GET FIRE EXTINGUISHER BY ID
// =====================================================

function getById($id)
{
    global $conn;

    $branch = getCurrentBranch();

    if (empty($branch)) {
        return false;
    }

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
            WHERE extinguisher_id = ?
              AND LOWER(TRIM(branch)) = LOWER(TRIM(?))";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "is",
        $id,
        $branch
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result);
}


// =====================================================
// GET FIRE EXTINGUISHER BY CODE
// =====================================================

function getByCode($code)
{
    global $conn;

    $branch = 'KPLaguna';

    if (empty($branch)) {
        return false;
    }

    $sql = "SELECT
                extinguisher_code,
                location,
                type,
                capacity,
                class
            FROM fire_extinguishers_tbl
            WHERE extinguisher_code = ?
              AND LOWER(TRIM(branch)) = LOWER(TRIM(?))";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $code,
        $branch
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result);
}


// =====================================================
// ADD NEW FIRE EXTINGUISHER
// =====================================================

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
    $expiration_date,
    $added_by
) {
    global $conn;

    $branch = getCurrentBranch();

    if (empty($branch)) {
        return false;
    }

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
                expiration_date,
                branch,
                added_by
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssssss",
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
        $branch,
        $added_by
    );

    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}


// =====================================================
// UPDATE FIRE EXTINGUISHER
// =====================================================

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

    $branch = getCurrentBranch();

    if (empty($branch)) {
        return false;
    }

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
            WHERE extinguisher_id = ?
              AND LOWER(TRIM(branch)) = LOWER(TRIM(?))";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssssis",
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
        $id,
        $branch
    );

    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}


// =====================================================
// SOFT DELETE / ARCHIVE
// =====================================================

function deleteFireExtinguisherById($id)
{
    global $conn;

    $branch = getCurrentBranch();

    if (empty($branch)) {
        return false;
    }

    $sql = "UPDATE fire_extinguishers_tbl
            SET archived = 1
            WHERE extinguisher_id = ?
              AND LOWER(TRIM(branch)) = LOWER(TRIM(?))";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "is",
        $id,
        $branch
    );

    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}


// =====================================================
// GET NEXT FIRE EXTINGUISHER CODE
// =====================================================

function getNextFireExtinguisherCodeModel()
{
    global $conn;

    $branch = getCurrentBranch();

    if (empty($branch)) {
        return 'FE-001';
    }

    $sql = "SELECT extinguisher_code
            FROM fire_extinguishers_tbl
            WHERE extinguisher_code LIKE 'FE-%'
              AND LOWER(TRIM(branch)) = LOWER(TRIM(?))
            ORDER BY CAST(SUBSTRING(extinguisher_code, 4) AS UNSIGNED) DESC
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return 'FE-001';
    }

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $branch
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

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


// =====================================================
// UPDATE REFILLED DATE
// =====================================================

function update_refilled($ext_code)
{
    global $conn;

    $branch = getCurrentBranch();

    if (empty($branch)) {
        return false;
    }

    $sql = "UPDATE fire_extinguishers_tbl
            SET
                refilled_date = CURDATE(),
                expiration_date = DATE_ADD(CURDATE(), INTERVAL 3 YEAR)
            WHERE extinguisher_code = ?
              AND LOWER(TRIM(branch)) = LOWER(TRIM(?))";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $ext_code,
        $branch
    );

    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}


// =====================================================
// UPDATE REMARKS
// =====================================================

function update_remarks($remarks, $ext_code)
{
    global $conn;

    $branch = getCurrentBranch();

    if (empty($branch)) {
        return false;
    }

    $sql = "UPDATE fire_extinguishers_tbl
            SET remarks = ?
            WHERE extinguisher_code = ?
              AND LOWER(TRIM(branch)) = LOWER(TRIM(?))";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $remarks,
        $ext_code,
        $branch
    );

    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}


// =====================================================
// UPDATE FIRE EXTINGUISHER STATUS
// =====================================================

function update_status($status, $ext_code)
{
    global $conn;

    $branch = getCurrentBranch();

    if (empty($branch)) {
        return false;
    }

    $sql = "UPDATE fire_extinguishers_tbl
            SET condition_status = ?
            WHERE extinguisher_code = ?
              AND LOWER(TRIM(branch)) = LOWER(TRIM(?))";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $status,
        $ext_code,
        $branch
    );

    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}


// =====================================================
// GET TOTAL FIRE EXTINGUISHERS
// =====================================================

function getTotalFireExtinguishersModel()
{
    global $conn;

    $branch = getCurrentBranch();

    if (empty($branch)) {
        return 0;
    }

    $sql = "SELECT COUNT(*) AS total
            FROM fire_extinguishers_tbl
            WHERE archived = 0
              AND LOWER(TRIM(branch)) = LOWER(TRIM(?))";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return 0;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $branch
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return (int) $row['total'];
}

function getAllDeletedFireExtinguishers($limit, $offset)
{
    global $conn;

    $branch = $_SESSION['branch'] ?? null;

    if (empty($branch)) {
        return [];
    }

    $sql = "SELECT *
            FROM fire_extinguishers_tbl
            WHERE archived = 1
              AND LOWER(TRIM(branch)) = LOWER(TRIM(?))
            ORDER BY extinguisher_id DESC
            LIMIT ? OFFSET ?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return [];
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sii",
        $branch,
        $limit,
        $offset
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);

    return $data;
}


function getAllDeletedFireExtinguishersModel()
{
    global $conn;

    $branch = $_SESSION['branch'] ?? null;

    if (empty($branch)) {
        return 0;
    }

    $sql = "SELECT COUNT(*) AS total
            FROM fire_extinguishers_tbl
            WHERE archived = 1
              AND LOWER(TRIM(branch)) = LOWER(TRIM(?))";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return 0;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $branch
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return (int) ($row['total'] ?? 0);
}
