<?php
require_once __DIR__ . '/../config/db.php';

// funciton for add inspection list
function addInspectionChecklist(
    $extinguisher_code,
    $location,
    $capacity,
    $type,
    $class,
    $date_inspected,
    $inspected_by,
    $verified_and_approved_by,
    $action_taken,
    $target_date_of_implementation,
    $is_seal_ok,
    $is_pin_ok,
    $is_pressure_ok,
    $is_hose_ok,
    $is_nozzle_ok,
    $is_belt_ok,
    $is_cylinder_body_ok,
    $is_demarcation_line_ok,
    $is_signage_ok,
    $status,
    $branch
) {
    global $conn;

    $sql = "INSERT INTO inspection_checklist_tbl
            (
                extinguisher_code,
                location,
                capacity,
                type,
                class,
                date_inspected,
                inspected_by,
                verified_and_approved_by,
                action_taken,
                target_date_of_implementation,
                is_seal_ok,
                is_pin_ok,
                is_pressure_ok,
                is_hose_ok,
                is_nozzle_ok,
                is_belt_ok,
                is_cylinder_body_ok,
                is_demarcation_line_ok,
                is_signage_ok,
                status,
                branch
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssssiiiiiiiiiis",
        $extinguisher_code,
        $location,
        $capacity,
        $type,
        $class,
        $date_inspected,
        $inspected_by,
        $verified_and_approved_by,
        $action_taken,
        $target_date_of_implementation,
        $is_seal_ok,
        $is_pin_ok,
        $is_pressure_ok,
        $is_hose_ok,
        $is_nozzle_ok,
        $is_belt_ok,
        $is_cylinder_body_ok,
        $is_demarcation_line_ok,
        $is_signage_ok,
        $status,
        $branch
    );

    return mysqli_stmt_execute($stmt);
}
// Get current logged-in branch
function getBranch()
{
    return $_SESSION['branch'] ?? null;
}


// ============================================================
// GET ALL LIST
// ============================================================

function getAllInspectionCheckList()
{
    global $conn;

    $branch = getBranch();

    if (empty($branch)) {
        return [];
    }

    $sql = "SELECT *
            FROM inspection_checklist_tbl
            WHERE LOWER(TRIM(branch)) = LOWER(TRIM(?))
            ORDER BY inspect_id DESC";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return [];
    }

    mysqli_stmt_bind_param($stmt, "s", $branch);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);

    return $data;
}


// ============================================================
// GET ALL LIST BY ID
// ============================================================

function getInspectionCheckListById($id)
{
    global $conn;

    $branch = getBranch();

    if (empty($branch)) {
        return null;
    }

    $sql = "SELECT *
            FROM inspection_checklist_tbl
            WHERE inspect_id = ?
              AND LOWER(TRIM(branch)) = LOWER(TRIM(?))
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return null;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "is",
        $id,
        $branch
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $inspection = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $inspection ?: null;
}


// ============================================================
// FOR PENDING APPROVAL
// ============================================================

// Get pending approvals with pagination and exact date filter
function getAllPendingApproval($limit = 10, $offset = 0, $date = null)
{
    global $conn;

    $branch = getBranch();

    if (empty($branch)) {
        return false;
    }

    $sql = "SELECT *
            FROM inspection_checklist_tbl
            WHERE evaluation_status = 'Pending'
              AND LOWER(TRIM(branch)) = LOWER(TRIM(?))";

    $types = "s";
    $params = [$branch];

    // Exact date filter
    if (!empty($date)) {
        $sql .= " AND DATE(date_inspected) = ?";
        $types .= "s";
        $params[] = $date;
    }

    $sql .= " ORDER BY inspect_id DESC
              LIMIT ? OFFSET ?";

    $types .= "ii";
    $params[] = (int) $limit;
    $params[] = (int) $offset;

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    return $result;
}


// For count all pending approval
function getPendingApprovalCount($date = null)
{
    global $conn;

    $branch = getBranch();

    if (empty($branch)) {
        return 0;
    }

    $sql = "SELECT COUNT(*) AS total
            FROM inspection_checklist_tbl
            WHERE evaluation_status = 'Pending'
              AND LOWER(TRIM(branch)) = LOWER(TRIM(?))";

    $types = "s";
    $params = [$branch];

    if (!empty($date)) {
        $sql .= " AND DATE(date_inspected) = ?";
        $types .= "s";
        $params[] = $date;
    }

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return 0;
    }

    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return (int) ($row['total'] ?? 0);
}


// ============================================================
// FOR APPROVED APPROVAL
// ============================================================

// Get approved approvals with pagination and exact date filter
function getAllApprovedApproval($limit = 10, $offset = 0, $date = null)
{
    global $conn;

    $branch = getBranch();

    if (empty($branch)) {
        return false;
    }

    $sql = "SELECT *
            FROM inspection_checklist_tbl
            WHERE evaluation_status = 'Approved'
              AND LOWER(TRIM(branch)) = LOWER(TRIM(?))";

    $types = "s";
    $params = [$branch];

    // Exact date filter
    if (!empty($date)) {
        $sql .= " AND DATE(date_inspected) = ?";
        $types .= "s";
        $params[] = $date;
    }

    $sql .= " ORDER BY inspect_id DESC
              LIMIT ? OFFSET ?";

    $types .= "ii";
    $params[] = (int) $limit;
    $params[] = (int) $offset;

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    return $result;
}


// For count all approved approval
function getApprovedApprovalCount($date = null)
{
    global $conn;

    $branch = getBranch();

    if (empty($branch)) {
        return 0;
    }

    $sql = "SELECT COUNT(*) AS total
            FROM inspection_checklist_tbl
            WHERE evaluation_status = 'Approved'
              AND LOWER(TRIM(branch)) = LOWER(TRIM(?))";

    $types = "s";
    $params = [$branch];

    if (!empty($date)) {
        $sql .= " AND DATE(date_inspected) = ?";
        $types .= "s";
        $params[] = $date;
    }

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return 0;
    }

    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return (int) ($row['total'] ?? 0);
}


// ============================================================
// FOR REJECTED APPROVAL
// ============================================================

// Get rejected approvals with pagination and exact date filter
function getAllRejectedApproval($limit = 10, $offset = 0, $date = null)
{
    global $conn;

    $branch = getBranch();

    if (empty($branch)) {
        return false;
    }

    $sql = "SELECT *
            FROM inspection_checklist_tbl
            WHERE evaluation_status = 'Rejected'
              AND LOWER(TRIM(branch)) = LOWER(TRIM(?))";

    $types = "s";
    $params = [$branch];

    // Exact date filter
    if (!empty($date)) {
        $sql .= " AND DATE(date_inspected) = ?";
        $types .= "s";
        $params[] = $date;
    }

    $sql .= " ORDER BY inspect_id DESC
              LIMIT ? OFFSET ?";

    $types .= "ii";
    $params[] = (int) $limit;
    $params[] = (int) $offset;

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    return $result;
}


// For count all rejected approval
function getRejectedApprovalCount($date = null)
{
    global $conn;

    $branch = getBranch();

    if (empty($branch)) {
        return 0;
    }

    $sql = "SELECT COUNT(*) AS total
            FROM inspection_checklist_tbl
            WHERE evaluation_status = 'Rejected'
              AND LOWER(TRIM(branch)) = LOWER(TRIM(?))";

    $types = "s";
    $params = [$branch];

    if (!empty($date)) {
        $sql .= " AND DATE(date_inspected) = ?";
        $types .= "s";
        $params[] = $date;
    }

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return 0;
    }

    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return (int) ($row['total'] ?? 0);
}


// ============================================================
// FOR UPDATE EVALUATION STATUS
// ============================================================

function updateEvaluationStatus($id, $evaluation_status)
{
    global $conn;

    $branch = getBranch();

    if (empty($branch)) {
        return false;
    }

    $sql = "UPDATE inspection_checklist_tbl
            SET evaluation_status = ?
            WHERE inspect_id = ?
              AND LOWER(TRIM(branch)) = LOWER(TRIM(?))";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sis",
        $evaluation_status,
        $id,
        $branch
    );

    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}


function Approved_by($name, $id)
{
    global $conn;

    $sql = "
        UPDATE inspection_checklist_tbl
        SET verified_and_approved_by = ?
        WHERE inspect_id = ?
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("si", $name, $id);

    return $stmt->execute();
}


function Rejected_by($name, $id)
{
    global $conn;

    $sql = "
        UPDATE inspection_checklist_tbl
        SET rejected_by = ?
        WHERE inspect_id = ?
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("si", $name, $id);

    return $stmt->execute();
}






function updateCorrectiveAction(
    $inspect_id,
    $action_taken,
    $target_date
) {
    global $conn;

    $branch = getCurrentBranch();

    if (empty($branch)) {
        return false;
    }

    $sql = "UPDATE inspection_checklist_tbl
            SET
                action_taken = ?,
                target_date_of_implementation = ?
            WHERE inspect_id = ?
              AND LOWER(TRIM(branch)) = LOWER(TRIM(?))";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "ssis",
        $action_taken,
        $target_date,
        $inspect_id,
        $branch
    );

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}
