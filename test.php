<?php
include './backend/controller/FireExtinguisherController.php';
$code = $_GET['code'] ?? null;

$extinguisher = getFireExtinguisherByCode($code);

echo '<pre>';
print_r($extinguisher);
echo '</pre>';