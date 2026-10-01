<?php

require_once __DIR__ . '/../model/FireExtinguisherModel.php';
require_once __DIR__ . '/ActivityLogController.php';
require_once __DIR__ . '/../authentication/SessionChecker.php';



// Get paginated fire extinguishers
function getAllFireExtinguishers($limit = 10, $offset = 0)
{
    return getAll($limit, $offset);
}

// Get fire extinguisher by ID
function getFireExtinguisherById($id)
{
    return getById($id);
}

// Get fire extinguisher by code
function getFireExtinguisherByCode($code)
{
    return getByCode($code);
}

// same purpose with the code above but it is to get the 
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $action = $_GET['action'] ?? '';

    if ($action === 'checkCode') {

        header('Content-Type: application/json');

        $code = trim($_GET['code'] ?? '');


        if ($code === '') {

            echo json_encode([
                'success' => true,
                'exists' => false
            ]);

            exit;
        }


        $data = getByCode($code);


        echo json_encode([
            'success' => true,
            'exists' => !empty($data)
        ]);

        exit;
    }


    // GET NEXT FIRE EXTINGUISHER CODE

    if ($action === 'getNextCode') {

        header('Content-Type: application/json');

        $code = getNextFireExtinguisherCode();


        echo json_encode([
            'success' => true,
            'code' => $code
        ]);

        exit;
    }
}


// controller function for creating/adding new fire extinguisher

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $ext_code = $_POST['extinguisher_code'] ?? null;
    $ext_type = $_POST['type'] ?? null;
    $ext_capacity = $_POST['capacity'] ?? null;
    $ext_location = $_POST['location'] ?? null;
    $ext_manufactured_date = $_POST['manufactured_date'] ?? null;
    $ext_class = $_POST['class'] ?? null;
    $ext_placement = $_POST['placement'] ?? null;
    $ext_condition_status = $_POST['condition_status'] ?? null;
    $ext_remarks = $_POST['remarks'] ?? null;
    $ext_expiration_date = $_POST['expiration_date'] ?? null;
    $ext_id = $_POST['extinguisher_id'] ?? null;
    $ext_branch = $_SESSION['branch'] ?? null;
    $action = $_POST['action'] ?? null;
    $added_by = $added_by = $_SESSION['EmployeeName'] ?? null;


    if ($action == 'add') {

        addNewExtinguisher(
            $ext_code,
            $ext_type,
            $ext_capacity,
            $ext_location,
            $ext_manufactured_date,
            $ext_class,
            $ext_placement,
            $ext_condition_status,
            $ext_remarks,
            $ext_expiration_date,
            $added_by
        );
    } else if ($action == 'update') {

        updateExtinguisher(
            $ext_id,
            $ext_code,
            $ext_type,
            $ext_capacity,
            $ext_location,
            $ext_manufactured_date,
            $ext_class,
            $ext_placement,
            $ext_condition_status,
            $ext_remarks,
            $ext_expiration_date
        );
    }
}

// update extinguisher
function updateExtinguisher(
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

    $success = updateFireExtinguisherModel(
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
    );

    if ($success) {

        $user_name = $_SESSION['EmployeeName']
            ?? 'error while getting employee name';

        createActivityLog(
            $user_name,
            "Update Fire Extinguisher",
            "Updated fire extinguisher $code"
        );

        header("Location: ../../list-extinguisher.php?id=$id&success-update=1");
        exit;
    }

    return $success;
}

// GET - fetch fire extinguisher for edit modal & get by code for fire extinguisher
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $action = $_GET['action'] ?? null;

    if ($action === 'get') {

        $ext_id = $_GET['id'] ?? null;

        header('Content-Type: application/json');

        if (!$ext_id) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid fire extinguisher ID.'
            ]);
            exit;
        }

        $data = getById($ext_id);

        if (!$data) {
            echo json_encode([
                'success' => false,
                'message' => 'Fire extinguisher not found.'
            ]);
            exit;
        }

        echo json_encode([
            'success' => true,
            'data' => $data
        ]);

        exit;
    } 
}

// add new extinguisher
function addNewExtinguisher(
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

    $success = addNewFireExtinguisherModel(
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
    );

    if ($success) {

        $user_name = $_SESSION['EmployeeName']
            ?? 'error while getting employee name';

        createActivityLog(
            $user_name,
            "Add Fire Extinguisher",
            "Added fire extinguisher $code"
        );

        header("Location: ../../list-extinguisher.php?success-add=1");
        exit;
    }

    return $success;
}

// for delete FE function
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    if ($action === 'delete') {
        $ext_id = $_GET['id'] ?? null;
        if (!$ext_id) {

            header("Location: ../../list-extinguisher.php?error=invalid_id");
            exit;
        }
        $data = getFireExtinguisherById($ext_id);
        $code = $data['extinguisher_code'];
        $success = deleteFireExtinguisherById($ext_id);
        if ($success) {


            $user_name = $_SESSION['EmployeeName'] ?? 'error while getting employee name';
            createActivityLog(
                $user_name,
                "Delete Fire Extinguisher",
                "Deleted fire extinguisher $code"
            );
            header("Location: ../../list-extinguisher.php?success-delete=1");
            exit;
        }
        header("Location: ../../list-extinguisher.php?error=delete_failed");
        exit;
    }
}

// get next code 
function getNextFireExtinguisherCode()
{
    return getNextFireExtinguisherCodeModel();
}

function getTotalFireExtinguishers()
{
    return getTotalFireExtinguishersModel();
}

function getAllArchiveFireExtinguishers($limit = 10, $offset = 0)
{
    return getAllDeletedFireExtinguishersModel($limit, $offset);
}


function getTotalDeletedFireExtinguishers()
{
    return getAllDeletedFireExtinguishersModel();
}
