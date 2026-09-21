<?php

require_once __DIR__ . '/../model/FireExtinguisherModel.php';


// Get all fire extinguishers
function getAllFireExtinguishers()
{
    return getAll();
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

// controller function for creating/adding new fire extinguisher

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ext_code = $_POST['extinguisher_code'];
    $ext_type = $_POST['type'];
    $ext_capacity = $_POST['capacity'];
    $ext_location = $_POST['location'];
    $ext_date_acquired = $_POST['date_acquired'];
    $ext_expiration_date = $_POST['expiration_date'];
    $ext_status = $_POST['status'];

    addNewExtinguisher($ext_code, $ext_type, $ext_capacity, $ext_location, $ext_date_acquired, $ext_expiration_date, $ext_status);
}

function addNewExtinguisher($code, $type, $capacity, $location, $date_acquired, $expiration_date, $status)
{
    $success = addNewFireExtinguisherModel($code, $type, $capacity, $location, $date_acquired, $expiration_date, $status);

    if ($success) {
        header("Location: ../../add-extinguisher.php?success=1");
        exit;
    }
    return $success;
}

// This is for delete fire Extinguisher by id
// function deleteFireExtinguisherById($id) {
    
// }
