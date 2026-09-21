<?php

require_once './backend/config/db.php';

function getExtinguisher()
{
    global $conn;
    $sql = "SELECT extinguisher_id, extinguisher_code, type, location
        FROM fire_extinguishers_tbl
        ORDER BY extinguisher_code ASC";

    $result = $conn->query($sql);

    return $result;
}
