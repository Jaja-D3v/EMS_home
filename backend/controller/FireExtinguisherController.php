<?php

require_once __DIR__ . '/../model/FireExtinguisherModel.php';
require_once __DIR__ . '/ActivityLogController.php';
require_once __DIR__ . '/../authentication/SessionChecker.php';


// =====================================================
// Get paginated fire extinguishers
// =====================================================

function getAllFireExtinguishers($limit = 10, $offset = 0)
{
    /*
    |--------------------------------------------------------------------------
    | IMPORTANT
    |--------------------------------------------------------------------------
    | The model handles the actual branch restriction.
    |
    | Admin:
    |   ?branch=all       = all branches
    |   ?branch=Laguna    = Laguna only
    |
    | Inspector:
    |   always uses $_SESSION['Branch']
    |--------------------------------------------------------------------------
    */

    return getAll($limit, $offset);
}


// =====================================================
// Get fire extinguisher by ID
// =====================================================

function getFireExtinguisherById($id)
{
    return getById($id);
}


// =====================================================
// Get fire extinguisher by code
// =====================================================

function getFireExtinguisherByCode($code)
{
    return getByCode($code);
}


// =====================================================
// Check code / Get next code
// =====================================================

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $action = $_GET['action'] ?? '';


    // =================================================
    // CHECK FIRE EXTINGUISHER CODE
    // =================================================

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


    // =================================================
    // GET NEXT FIRE EXTINGUISHER CODE
    // =================================================

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


// =====================================================
// Controller function for creating/adding
// new fire extinguisher
// =====================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $ext_code =
        $_POST['extinguisher_code'] ?? null;

    $ext_type =
        $_POST['type'] ?? null;

    $ext_capacity =
        $_POST['capacity'] ?? null;

    $ext_location =
        $_POST['location'] ?? null;

    $ext_manufactured_date =
        $_POST['manufactured_date'] ?? null;

    $ext_class =
        $_POST['class'] ?? null;

    $ext_placement =
        $_POST['placement'] ?? null;

    $ext_condition_status =
        $_POST['condition_status'] ?? null;

    $ext_remarks =
        $_POST['remarks'] ?? null;

    $ext_expiration_date =
        $_POST['expiration_date'] ?? null;

    $ext_id =
        $_POST['extinguisher_id'] ?? null;

    /*
    |--------------------------------------------------------------------------
    | FIXED SESSION NAME
    |--------------------------------------------------------------------------
    | Your login uses $_SESSION['Branch']
    | not $_SESSION['branch']
    |--------------------------------------------------------------------------
    */

    $ext_branch =
        $_SESSION['Branch'] ?? null;

    $action =
        $_POST['action'] ?? null;

    $added_by =
        $_SESSION['EmployeeName'] ?? null;


    // =================================================
    // ADD
    // =================================================

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
    }


    // =================================================
    // UPDATE
    // =================================================

    else if ($action == 'update') {

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


// =====================================================
// UPDATE EXTINGUISHER
// =====================================================

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

        $user_name =
            $_SESSION['EmployeeName']
            ?? 'error while getting employee name';

        createActivityLog(
            $user_name,
            "Update Fire Extinguisher",
            "Updated fire extinguisher $code"
        );

        header(
            "Location: ../../list-extinguisher.php?id=$id&success-update=1"
        );

        exit;
    }

    return $success;
}


// =====================================================
// GET - fetch fire extinguisher for edit modal
// & get by code for fire extinguisher
// =====================================================

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $action = $_GET['action'] ?? null;


    // =================================================
    // GET BY ID
    // =================================================

    if ($action === 'get') {

        $ext_id = $_GET['id'] ?? null;

        header('Content-Type: application/json');

        if (!$ext_id) {

            echo json_encode([
                'success' => false,
                'message' =>
                    'Invalid fire extinguisher ID.'
            ]);

            exit;
        }


        $data = getById($ext_id);


        if (!$data) {

            echo json_encode([
                'success' => false,
                'message' =>
                    'Fire extinguisher not found.'
            ]);

            exit;
        }


        echo json_encode([
            'success' => true,
            'data' => $data
        ]);

        exit;
    }


    // =================================================
    // GET BY CODE
    // =================================================

    else if ($action === 'getByCode') {

        header('Content-Type: application/json');

        $code = trim($_GET['code'] ?? '');


        if ($code === '') {

            echo json_encode([
                'success' => false,
                'message' => 'Invalid QR code.'
            ]);

            exit;
        }


        $data = getFireExtinguisherByCode($code);


        if (!$data) {

            echo json_encode([
                'success' => false,
                'message' =>
                    'Fire extinguisher not found.'
            ]);

            exit;
        }


        echo json_encode([
            'success' => true,
            'data' => $data
        ]);

        exit;
    }


    // =================================================
    // GET BRANCHES
    // =================================================

    else if ($action === 'getBranches') {

        header('Content-Type: application/json');

        $role =
            strtolower(trim($_SESSION['Role'] ?? ''));


        if ($role !== 'admin') {

            echo json_encode([
                'success' => false,
                'message' => 'Unauthorized.'
            ]);

            exit;
        }


        global $conn;


        $sql = "
            SELECT DISTINCT branch
            FROM fire_extinguishers_tbl
            WHERE archived = 0
              AND branch IS NOT NULL
              AND TRIM(branch) != ''
            ORDER BY branch ASC
        ";


        $result = mysqli_query($conn, $sql);


        $branches = [];


        if ($result) {

            while ($row = mysqli_fetch_assoc($result)) {

                $branches[] = $row['branch'];
            }
        }


        echo json_encode([
            'success' => true,
            'data' => $branches
        ]);

        exit;
    }
}


// =====================================================
// ADD NEW EXTINGUISHER
// =====================================================

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

        $user_name =
            $_SESSION['EmployeeName']
            ?? 'error while getting employee name';


        createActivityLog(
            $user_name,
            "Add Fire Extinguisher",
            "Added fire extinguisher $code"
        );


        header(
            "Location: ../../list-extinguisher.php?success-add=1"
        );

        exit;
    }


    return $success;
}


// =====================================================
// DELETE FIRE EXTINGUISHER
// =====================================================

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $action = $_GET['action'] ?? '';


    if ($action === 'delete') {

        $ext_id = $_GET['id'] ?? null;


        if (!$ext_id) {

            header(
                "Location: ../../list-extinguisher.php?error=invalid_id"
            );

            exit;
        }


        $data =
            getFireExtinguisherById($ext_id);


        if (!$data) {

            header(
                "Location: ../../list-extinguisher.php?error=not_found"
            );

            exit;
        }


        $code =
            $data['extinguisher_code'];


        $success =
            deleteFireExtinguisherById($ext_id);


        if ($success) {

            $user_name =
                $_SESSION['EmployeeName']
                ?? 'error while getting employee name';


            createActivityLog(
                $user_name,
                "Delete Fire Extinguisher",
                "Deleted fire extinguisher $code"
            );


            header(
                "Location: ../../list-extinguisher.php?success-delete=1"
            );

            exit;
        }


        header(
            "Location: ../../list-extinguisher.php?error=delete_failed"
        );

        exit;
    }
}


// =====================================================
// GET NEXT CODE
// =====================================================

function getNextFireExtinguisherCode()
{
    return getNextFireExtinguisherCodeModel();
}


// =====================================================
// GET TOTAL FIRE EXTINGUISHERS
// =====================================================

function getTotalFireExtinguishers()
{
    return getTotalFireExtinguishersModel();
}


// =====================================================
// GET ARCHIVED FIRE EXTINGUISHERS
// =====================================================

function getAllArchiveFireExtinguishers(
    $limit = 10,
    $offset = 0
) {
    return getAllDeletedFireExtinguishers(
        $limit,
        $offset
    );
}


// =====================================================
// GET TOTAL DELETED FIRE EXTINGUISHERS
// =====================================================

function getTotalDeletedFireExtinguishers()
{
    return getAllDeletedFireExtinguishersModel();
}