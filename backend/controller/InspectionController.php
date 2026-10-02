<?php
require_once __DIR__ . '/../model/InspectionModel.php';
require_once __DIR__ . '/../model/FireExtinguisherModel.php';
require_once __DIR__ . '/ActivityLogController.php';
require_once __DIR__ . '/../authentication/SessionChecker.php';


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $action = $_GET['action'] ?? null;

    if ($action === 'get') {

        $id = isset($_GET['id'])
            ? (int) $_GET['id']
            : 0;

        header('Content-Type: application/json');

        if ($id <= 0) {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'Invalid inspection ID.'
            ]);

            exit;
        }

        $inspection = getInspectionCheckListById($id);

        if ($inspection) {

            echo json_encode([
                'success' => true,
                'inspection' => $inspection
            ]);
        } else {

            http_response_code(404);

            echo json_encode([
                'success' => false,
                'message' => 'Inspection record not found.'
            ]);
        }

        exit;
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    if ($action == 'inspect') {
        $extinguisher_code = $_POST['extinguisher_code'] ?? null;
        $location = $_POST['location'] ?? null;
        $capacity = $_POST['capacity'] ?? null;
        $type = $_POST['type'] ?? null;
        $class = $_POST['class'] ?? null;
        $date_inspected = $_POST['date_inspected'] ?? null;
        $inspected_by = $_SESSION['EmployeeName'] ?? 'error while getting employee name';
        $verified_and_approved_by = 'N/A';
        $action_taken = 'N/A';
        $target_date_of_implementation = 'N/A';
        $remarks = $_POST['remarks'] ?? null;
        $branch = $_POST['branch'] ?? null;




        /*
        * Checklist
        *
        * TRUE  = Good 
        * FALSE = Not Good
        */
        $is_seal_ok = isset($_POST['is_seal_ok'])
            ? 1
            : 0;

        $is_pin_ok = isset($_POST['is_pin_ok'])
            ? 1
            : 0;

        $is_pressure_ok = isset($_POST['is_pressure_ok'])
            ? 1
            : 0;

        $is_hose_ok = isset($_POST['is_hose_ok'])
            ? 1
            : 0;

        $is_nozzle_ok = isset($_POST['is_nozzle_ok'])
            ? 1
            : 0;

        $is_belt_ok = isset($_POST['is_belt_ok'])
            ? 1
            : 0;

        $is_cylinder_body_ok = isset($_POST['is_cylinder_body_ok'])
            ? 1
            : 0;

        $is_demarcation_line_ok = isset($_POST['is_demarcation_line_ok'])
            ? 1
            : 0;

        $is_signage_ok = isset($_POST['is_signage_ok'])
            ? 1
            : 0;


        /*
        * STATUS RULE
        *
        * FALSE kahit isa sa:
        * - Pressure
        * - Hose
        * - Nozzle
        * - Cylinder Body
        *
        * = Not Good
        *
        * Lahat TRUE
        * = Good
        */

        if (
            $is_pressure_ok &&
            $is_hose_ok &&
            $is_nozzle_ok &&
            $is_cylinder_body_ok
        ) {
            $status = 1; // Good
        } else {
            $status = 0; // Not Good
        }


        /*
     * Save inspection checklist
     */
        $success = addInspectionChecklist(
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
        // Final redirect
        if ($success) {

            header(
                "Location: ../../QR-code.php?success-inspect=1"
            );
            exit;
        }
    } elseif ($action == 'update_evaluation_status') {

        $inspect_id =  $_POST['inspect_id'] ?? null;

        $eval_stats = $_POST['evaluation_status'] ?? null;

        $action_taken = trim($_POST['action_taken'] ?? '');

        $target_date = $_POST['target_date_of_implementation'] ?? null;

        $Approver_name =  $_SESSION['EmployeeName'] ?? null;

        $extinguisher_code = trim($_POST['extinguisher_code'] ?? '');
       


        if ($eval_stats == "Approved") {

            ApprovedBy($Approver_name,  $inspect_id);
        } elseif ($eval_stats == "Rejected") {

            RejectedBy($Approver_name, $inspect_id);
        }
        // this is for updating expiry date pag nag refill 
        if ($action_taken == 'Refill') {

            $exp_date = new DateTime($target_date);
            $exp_date->modify('+3 years');

            $exp_date = $exp_date->format('Y-m-d');

            updateExpiry($exp_date, $extinguisher_code);
        }

        // Evaluation Status
        $statusSuccess = updateEvalStats($inspect_id, $eval_stats);

        // Corrective Action
        $actionSuccess = updateCorrectiveAction($inspect_id, $action_taken, $target_date);

        // Final redirect
        if ($statusSuccess && $actionSuccess) {

            header(
                "Location: ../../inspection-pending.php?approved_success=1"
            );
            exit;
        }


        header(
            "Location: ../../inspection-pending.php?error=1"
        );
        exit;
    }
}


function updateExpiry($exp_date, $extinguisherCode)
{
    return updateFireExtinguisherExpiryDate($exp_date, $extinguisherCode);
}
function ApprovedBy($name, $inspectid)
{
    Approved_by($name, $inspectid);
}
function RejectedBy($name, $inspectid)
{
    Rejected_by($name, $inspectid);
}

function getAllInspected()
{
    return getAllInspectionCheckList();
}


// __________FOR PENDING APPROVAL__________________________________________


// ============================================================
// GET PENDING APPROVALS
// ============================================================

function getPendingApprovals($limit = 10, $offset = 0, $date = null)
{
    $role = strtolower(trim($_SESSION['Role'] ?? ''));

    // ========================================================
    // ADMIN
    // ========================================================
    if ($role === 'admin') {

        // Admin can select:
        // ?branch=all
        // ?branch=Laguna
        // ?branch=Main Office

        $branch = trim($_GET['branch'] ?? 'all');

        if ($branch === '') {
            $branch = 'all';
        }

        return getAllPendingApproval(
            $limit,
            $offset,
            $date,
            $branch
        );
    }


    // ========================================================
    // INSPECTOR
    // ========================================================
    if ($role === 'inspector') {

        $branch = trim($_SESSION['Branch'] ?? '');

        if ($branch === '') {
            return false;
        }

        return getAllPendingApproval(
            $limit,
            $offset,
            $date,
            $branch
        );
    }


    return false;
}


// ============================================================
// GET PENDING APPROVAL TOTAL
// ============================================================

function getPendingApprovalTotal($date = null)
{
    $role = strtolower(trim($_SESSION['Role'] ?? ''));


    // ========================================================
    // ADMIN
    // ========================================================
    if ($role === 'admin') {

        $branch = trim($_GET['branch'] ?? 'all');

        if ($branch === '') {
            $branch = 'all';
        }

        return getPendingApprovalCount(
            $date,
            $branch
        );
    }


    // ========================================================
    // INSPECTOR
    // ========================================================
    if ($role === 'inspector') {

        $branch = trim($_SESSION['Branch'] ?? '');

        if ($branch === '') {
            return 0;
        }

        return getPendingApprovalCount(
            $date,
            $branch
        );
    }


    return 0;
}

// __________FOR APPROVED APPROVAL__________________________________________

// ============================================================
// GET APPROVED APPROVALS
// ============================================================

function getApprovedApprovals($limit = 10, $offset = 0, $date = null)
{
    $role = strtolower(trim($_SESSION['Role'] ?? ''));


    // ========================================================
    // ADMIN
    // ========================================================

    if ($role === 'admin') {

        $branch = trim($_GET['branch'] ?? 'all');

        if ($branch === '') {
            $branch = 'all';
        }

        return getAllApprovedApproval(
            $limit,
            $offset,
            $date,
            $branch
        );
    }


    // ========================================================
    // INSPECTOR
    // ========================================================

    if ($role === 'inspector') {

        $branch = trim($_SESSION['Branch'] ?? '');

        if ($branch === '') {
            return false;
        }

        return getAllApprovedApproval(
            $limit,
            $offset,
            $date,
            $branch
        );
    }


    return false;
}


// Get total number of pending approvals
function getApprovedApprovalTotal($date = null)
{
    $role = strtolower(trim($_SESSION['Role'] ?? ''));


    // ========================================================
    // ADMIN
    // ========================================================

    if ($role === 'admin') {

        $branch = trim($_GET['branch'] ?? 'all');

        if ($branch === '') {
            $branch = 'all';
        }

        return getApprovedApprovalCount(
            $date,
            $branch
        );
    }


    // ========================================================
    // INSPECTOR
    // ========================================================

    if ($role === 'inspector') {

        $branch = trim($_SESSION['Branch'] ?? '');

        if ($branch === '') {
            return 0;
        }

        return getApprovedApprovalCount(
            $date,
            $branch
        );
    }


    return 0;
}


// __________FOR REJECTED APPROVAL__________________________________________
// ============================================================
// GET REJECTED APPROVALS
// ============================================================
function getRejectedApprovals($limit = 10, $offset = 0, $date = null)
{
    $role = strtolower(trim($_SESSION['Role'] ?? ''));

    // ADMIN
    if ($role === 'admin') {

        $branch = trim($_GET['branch'] ?? 'all');

        if ($branch === '') {
            $branch = 'all';
        }

        return getAllRejectedApproval(
            $limit,
            $offset,
            $date,
            $branch
        );
    }

    // INSPECTOR
    if ($role === 'inspector') {

        $branch = trim($_SESSION['Branch'] ?? '');

        if ($branch === '') {
            return false;
        }

        return getAllRejectedApproval(
            $limit,
            $offset,
            $date,
            $branch
        );
    }

    return false;
}


// ============================================================
// GET REJECTED APPROVAL TOTAL
// ============================================================
function getRejectedApprovalTotal($date = null)
{
    $role = strtolower(trim($_SESSION['Role'] ?? ''));

    // ADMIN
    if ($role === 'admin') {

        $branch = trim($_GET['branch'] ?? 'all');

        if ($branch === '') {
            $branch = 'all';
        }

        return getRejectedApprovalCount(
            $date,
            $branch
        );
    }

    // INSPECTOR
    if ($role === 'inspector') {

        $branch = trim($_SESSION['Branch'] ?? '');

        if ($branch === '') {
            return 0;
        }

        return getRejectedApprovalCount(
            $date,
            $branch
        );
    }

    return 0;
}

// _______________UPDATE EVALUATION STATUS_____________

function updateEvalStats($eval_id, $evalStatus)
{
    return updateEvaluationStatus(
        $eval_id,
        $evalStatus
    );
}
