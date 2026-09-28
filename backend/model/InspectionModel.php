<?php
require_once __DIR__ . '/../config/db.php';

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
