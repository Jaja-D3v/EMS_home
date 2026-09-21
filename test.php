<?php
// include './backend/controller/FireExtinguisherController.php';
// $code = $_GET['code'] ?? null;

// $extinguisher = getFireExtinguisherByCode($code);

// echo '<pre>';
// print_r($extinguisher);
// echo '</pre>';

$temp_pass = "0919-701";
$hashed = md5($temp_pass);

$db_pass = "6260a6510f8881edaaa79b52a198c44b";
$user_input = "22-004";

$is_true = md5($user_input, $db_pass);

if($is_true) {
    echo 'match';
}else {
    echo 'not match';
}

echo $hashed;