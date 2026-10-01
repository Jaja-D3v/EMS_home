<?php
require_once __DIR__ . '/../config/db.php';


function getAllBranchDropdown()
{
    global $conn;

    $sql = "SELECT * FROM branches_tbl ORDER BY branch_name ASC";

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
