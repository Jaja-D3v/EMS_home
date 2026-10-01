<?php
require_once __DIR__ . '/../config/db.php';


function getAllBranchDropdown()
{
    global $conn;

    $sql = "SELECT * FROM maintenance_dropdown_tbl WHERE category = 'branch'";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        return [];
    }

    $branches = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $branches[] = $row;
    }

    return $branches;
}
