<?php

require_once __DIR__ . '/../model/ScanQRCodeModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $action = $_GET['action'] ?? null;


    if ($action === 'getFeInfo') {
        header('Content-Type: application/json');

        $code = trim($_GET['code'] ?? '');

        if ($code === '') {
            echo json_encode([
                'success' => false,
                'message' => 'No QR code was provided.'
            ]);
            exit;
        }

        // Kunin ang FE kasama ang archived status.
        $data = getFeInfoByCodeController($code);

        if (!$data) {
            echo json_encode([
                'success' => false,
                'message' => 'Fire extinguisher not found. Please verify the QR code.'
            ]);
            exit;
        }

        // I-check kung archived.
        if ((int)($data['archived'] ?? 0) === 1) {
            echo json_encode([
                'success' => false,
                'archived' => true,
                'message' => 'This fire extinguisher has been archived and cannot be inspected. Please contact your admin or safety officer for assistance.'
            ]);
            exit;
        }

        echo json_encode([
            'success' => true,
            'data' => $data
        ]);

        exit;
    }
}

// Get fire extinguisher by code
function getFeInfoByCodeController($code)
{
    return getFeInfoByCode($code);
}
