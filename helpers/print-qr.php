<?php

require_once __DIR__ . '/../vendor/autoload.php';

$codes = [];

if (isset($_GET['codes']) && !empty($_GET['codes'])) {
    $codes = array_filter(
        array_map('trim', explode(',', $_GET['codes']))
    );
}

if (empty($codes)) {
    die('No QR codes selected.');
}


/*
|--------------------------------------------------------------------------
| TCPDF
|--------------------------------------------------------------------------
*/

$pdf = new TCPDF(
    'L',              // Landscape
    'mm',              // Unit
    [64, 36],         // Page size: 64mm x 36mm
    true,
    'UTF-8',
    false
);


/*
|--------------------------------------------------------------------------
| PDF Settings
|--------------------------------------------------------------------------
*/

$pdf->SetCreator('Fire Extinguisher Management System');
$pdf->SetAuthor('Fire Extinguisher Management System');
$pdf->SetTitle('Fire Extinguisher QR Codes');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->SetMargins(0, 0, 0);
$pdf->SetAutoPageBreak(false, 0);


/*
|--------------------------------------------------------------------------
| Generate each QR
|--------------------------------------------------------------------------
*/

foreach ($codes as $code) {

    $pdf->AddPage();

    // Border ng buong 64mm × 36mm label
    $pdf->SetLineWidth(0.3);
    $pdf->SetDrawColor(0, 0, 0);

    $pdf->Rect(
        1,
        1,
        62,
        34
    );

    /*
    |--------------------------------------------------------------------------
    | QR CODE
    |--------------------------------------------------------------------------
    */

    $qrX = 2;
    $qrY = 3;

    $qrSize = 30;

    $style = [
        'border' => false,
        'padding' => 0,
        'fgcolor' => [0, 0, 0],
        'bgcolor' => [255, 255, 255],
        'module_width' => 1,
        'module_height' => 1
    ];

    $pdf->write2DBarcode(
        $code,
        'QRCODE',
        $qrX,
        $qrY,
        $qrSize,
        $qrSize,
        $style,
        'N'
    );


    /*
    |--------------------------------------------------------------------------
    | INFORMATION
    |--------------------------------------------------------------------------
    */

    $infoX = 35;
    $infoY = 9;
    $infoWidth = 27;

    $pdf->SetXY($infoX, $infoY);

    $pdf->SetFont(
        'helvetica',
        'B',
        8
    );

    $pdf->MultiCell(
        $infoWidth,
        4,
        "Fire Extinguisher",
        0,
        'L',
        false,
        1
    );

    $pdf->SetX($infoX);

    $pdf->SetFont(
        'helvetica',
        'B',
        8
    );

    $pdf->MultiCell(
        $infoWidth,
        4,
        $code,
        0,
        'L',
        false,
        1
    );

    $pdf->SetX($infoX);

    $pdf->SetFont(
        'helvetica',
        '',
        6
    );

    $pdf->MultiCell(
        $infoWidth,
        4,
        "Scan QR Code",
        0,
        'L',
        false,
        1
    );
}


/*
|--------------------------------------------------------------------------
| Output PDF
|--------------------------------------------------------------------------
*/

$pdf->Output(
    'fire-extinguisher-qr.pdf',
    'I'
);
