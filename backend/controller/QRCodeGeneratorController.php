<?php
require_once __DIR__ . '/../model/QRCodeGeneratorModel.php';

function getAllFireExtinguishersCode()
{
    $role = strtolower(trim($_SESSION['Role'] ?? ''));
    $sessionBranch = $_SESSION['Branch'] ?? null;

    // ADMIN
    if ($role === 'admin') {

        $selectedBranch = $_GET['branch'] ?? 'all';

        if (
            $selectedBranch === null ||
            trim($selectedBranch) === ''
        ) {
            $selectedBranch = 'all';
        }

        return getExtinguisher(trim($selectedBranch));
    }


    // INSPECTOR
    if ($role === 'inspector') {

        if (empty($sessionBranch)) {
            return false;
        }

        return getExtinguisher($sessionBranch);
    }


    return false;
}


function getAllBranches()
{
    return getBranches();
}