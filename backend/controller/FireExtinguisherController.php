<?php

require_once './backend/model/FireExtinguisherModel.php';


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
