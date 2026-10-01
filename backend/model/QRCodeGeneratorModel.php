<?php

require_once './backend/config/db.php';

function getExtinguisher()
{
    global $conn;

    $branch = 'KPLaguna';

    if (empty($branch)) {
        return false;
    }

    $sql = "
        SELECT
            extinguisher_id,
            extinguisher_code,
            type,
            location
        FROM fire_extinguishers_tbl
        WHERE branch = ?
        ORDER BY extinguisher_code ASC
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("s", $branch);

    $stmt->execute();

    return $stmt->get_result();
}