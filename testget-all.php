<?php

require_once './backend/controller/FireExtinguisherController.php';

$data = getAllFireExtinguishersController();

while ($row = mysqli_fetch_assoc($data)) {
    echo $row['extinguisher_code'];
    echo $row['type'];
    echo $row['capacity'];
    echo $row['location'];
    echo $row['date_acquired'];
    echo $row['expiration_date'];
    echo $row['status'];
    echo $row['create_at'];
    echo $row['updated_at']. '<br>';
}
