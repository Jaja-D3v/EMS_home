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
    $status
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
                status
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssssiiiiiiiiii",
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
        $status
    );

    return mysqli_stmt_execute($stmt);
}

// get all list 
function getAllInspectionCheckList()
{
    global $conn;

    $sql = "SELECT *
            FROM inspection_checklist_tbl
            ORDER BY inspect_id DESC";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        return [];
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// get all list by id
function getInspectionCheckListById($id)
{
    global $conn;

    $sql = "SELECT *
            FROM inspection_checklist_tbl
            WHERE inspect_id = ?
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return null;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $id
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $inspection = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $inspection ?: null;
}


// _________FOR PENDING APPROVAL______________________

// Get pending approvals with pagination and exact date filter
function getAllPendingApproval($limit = 10, $offset = 0, $date = null)
{
    global $conn;

    $sql = "SELECT *
            FROM inspection_checklist_tbl
            WHERE evaluation_status = 'Pending'";

    // Exact date filter
    if (!empty($date)) {
        $sql .= " AND DATE(date_inspected) = '" . mysqli_real_escape_string($conn, $date) . "'";
    }

    $sql .= " ORDER BY inspect_id DESC
              LIMIT $limit OFFSET $offset";

    $result = mysqli_query($conn, $sql);

    return $result;
}

// for count all the pending approval 
function getPendingApprovalCount($date = null)
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total
            FROM inspection_checklist_tbl
            WHERE evaluation_status = 'Pending'";

    if (!empty($date)) {
        $safeDate = mysqli_real_escape_string($conn, $date);

        $sql .= " AND DATE(date_inspected) = '$safeDate'";
    }

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    return (int) $row['total'];
}


// _________FOR APPROVED APPROVAL______________________

// Get approved approvals with pagination and exact date filter
function getAllApprovedApproval($limit = 10, $offset = 0, $date = null)
{
    global $conn;

    $sql = "SELECT *
            FROM inspection_checklist_tbl
            WHERE evaluation_status = 'Approved'";

    // Exact date filter
    if (!empty($date)) {
        $sql .= " AND DATE(date_inspected) = '" . mysqli_real_escape_string($conn, $date) . "'";
    }

    $sql .= " ORDER BY inspect_id DESC
              LIMIT $limit OFFSET $offset";

    $result = mysqli_query($conn, $sql);

    return $result;
}



// for count all the pending approval 
function getApprovedApprovalCount($date = null)
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total
            FROM inspection_checklist_tbl
            WHERE evaluation_status = 'Approved'";

    if (!empty($date)) {
        $safeDate = mysqli_real_escape_string($conn, $date);

        $sql .= " AND DATE(date_inspected) = '$safeDate'";
    }

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    return (int) $row['total'];
}


// _________FOR REJECTED APPROVAL______________________


// Get rejected approvals with pagination and exact date filter
function getAllRejectedApproval($limit = 10, $offset = 0, $date = null)
{
    global $conn;

    $sql = "SELECT *
            FROM inspection_checklist_tbl
            WHERE evaluation_status = 'Rejected'";

    // Exact date filter
    if (!empty($date)) {
        $sql .= " AND DATE(date_inspected) = '" . mysqli_real_escape_string($conn, $date) . "'";
    }

    $sql .= " ORDER BY inspect_id DESC
              LIMIT $limit OFFSET $offset";

    $result = mysqli_query($conn, $sql);

    return $result;
}



// for count all the rejected approval 
function getRejectedApprovalCount($date = null)
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total
            FROM inspection_checklist_tbl
            WHERE evaluation_status = 'Rejected'";

    if (!empty($date)) {
        $safeDate = mysqli_real_escape_string($conn, $date);

        $sql .= " AND DATE(date_inspected) = '$safeDate'";
    }

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    return (int) $row['total'];
}


// _________FOR UPDATE EVALUATION STATUS______________________


function updateEvaluationStatus($id, $evaluation_status)
{
    global $conn;

    $sql = "UPDATE inspection_checklist_tbl
            SET evaluation_status = ?
            WHERE inspect_id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, "si", $evaluation_status, $id);

    return mysqli_stmt_execute($stmt);
}
