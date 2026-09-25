<?php
require_once __DIR__ . '/../model/InspectionModel.php';
require_once __DIR__ . '/../model/FireExtinguisherModel.php';
require_once __DIR__ . '/ActivityLogController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    if ($action == 'inspect') {
        $extinguisher_code = $_POST['extinguisher_code'] ?? null;
        $location = $_POST['location'] ?? null;
        $capacity = $_POST['capacity'] ?? null;
        $type = $_POST['type'] ?? null;
        $class = $_POST['class'] ?? null;
        $date_inspected = $_POST['date_inspected'] ?? null;
        $inspected_by = $_POST['inspected_by'] ?? null;
        $verified_and_approved_by = $_POST['verified_and_approved_by'] ?? null;
        $action_taken = $_POST['action_taken'] ?? null;
        $target_date_of_implementation = $_POST['target_date_of_implementation'] ?? null;
        $remarks = $_POST['remarks'] ?? null;


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
            $status
        );


        if ($success) {
            if ($action_taken == "Refilled") {
                update_refilled($extinguisher_code);
            }
            update_remarks($remarks, $extinguisher_code );
            if($status) {
                update_status('Good', $extinguisher_code);
            }elseif(!$status) {
                update_status('Not Good', $extinguisher_code);
            }

            $user_name = 'jared';
            createActivityLog(
                $user_name,
                "Inspect Fire Extinguisher",
                "Inspect fire extinguisher $extinguisher_code"
            );
            header("Location: ../../QR-code.php?id=$extinguisher_code&success-inspect=1");
            exit;
        }
    }
}
