<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '../../backend/config/db.php'; // palitan kung iba ang path ng DB mo

// ============================================================
// GET SELECTED INSPECTION IDS
// Example:
// generate-inspection-report.php?ids=1,2,3
// ============================================================

$ids = [];

if (isset($_GET['ids']) && !empty($_GET['ids'])) {

    $ids = array_filter(
        array_map(
            'trim',
            explode(',', $_GET['ids'])
        )
    );
}

if (empty($ids)) {
    die('No inspection records selected.');
}


// ============================================================
// GET APPROVED INSPECTION RECORDS
// ============================================================

global $conn;

$placeholders = implode(
    ',',
    array_fill(
        0,
        count($ids),
        '?'
    )
);

$sql = "
    SELECT
        inspect_id,
        extinguisher_code,
        location,
        capacity,
        type,
        class,
        date_inspected,
        inspected_by,
        verified_and_approved_by,

        action_taken,
        target_date_of_implementation,

        is_seal_ok,
        is_pin_ok,
        is_pressure_ok,
        is_hose_ok,
        is_nozzle_ok,
        is_belt_ok,
        is_cylinder_body_ok,
        is_demarcation_line_ok,
        is_signage_ok,

        status,
        evaluation_status,
        branch

    FROM inspection_checklist_tbl

    WHERE inspect_id IN ($placeholders)
      AND evaluation_status = 'Approved'

    ORDER BY inspect_id ASC
";


$stmt = mysqli_prepare(
    $conn,
    $sql
);

if (!$stmt) {
    die('Database query failed.');
}


$types = str_repeat(
    'i',
    count($ids)
);

$ids = array_map(
    'intval',
    $ids
);

mysqli_stmt_bind_param(
    $stmt,
    $types,
    ...$ids
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$inspections = [];

while ($row = mysqli_fetch_assoc($result)) {

    $inspections[] = $row;
}

mysqli_stmt_close($stmt);


if (empty($inspections)) {

    die('No approved inspection records found.');
}


// ============================================================
// TCPDF
// ============================================================

$pdf = new TCPDF(

    'L',              // Landscape

    'mm',             // Unit

    'A4',             // Page size

    true,

    'UTF-8',

    false

);


$pdf->SetCreator(
    'IMS Safety Management System'
);

$pdf->SetAuthor(
    'KanePackage Philippine Inc.'
);

$pdf->SetTitle(
    'Fire Extinguisher Inspection Checksheet'
);

$pdf->SetSubject(
    'Approved Fire Extinguisher Inspection Report'
);


$pdf->setPrintHeader(false);

$pdf->setPrintFooter(false);


$pdf->SetMargins(
    8,
    8,
    8
);

$pdf->SetAutoPageBreak(
    true,
    8
);


// ============================================================
// ADD PAGE
// ============================================================

$pdf->AddPage();


// ============================================================
// HEADER
// ============================================================


// Company

$pdf->SetFont(
    'helvetica',
    'B',
    9
);

$pdf->SetXY(
    10,
    10
);

$pdf->Cell(
    55,
    5,
    'KANE PACKAGE PHILIPPINE INC.',
    0,
    0,
    'L'
);


// ============================================================
// TITLE
// ============================================================

$pdf->SetFont(
    'helvetica',
    'B',
    18
);

$pdf->SetXY(
    65,
    8
);

$pdf->Cell(
    150,
    8,
    'FIRE EXTINGUISHER',
    0,
    1,
    'C'
);


$pdf->SetFont(
    'helvetica',
    'B',
    16
);

$pdf->SetX(
    65
);

$pdf->Cell(
    150,
    8,
    'INSPECTION CHECKSHEET',
    0,
    1,
    'C'
);


// ============================================================
// REPORT INFORMATION
// ============================================================

$firstInspection = $inspections[0];

$dateInspected =
    $firstInspection['date_inspected']
    ?? '';

$inspectedBy =
    $firstInspection['inspected_by']
    ?? '';

$approvedBy =
    $firstInspection['verified_and_approved_by']
    ?? '';


// Date inspected

$pdf->SetFont(
    'helvetica',
    '',
    7
);

$pdf->SetXY(
    220,
    8
);

$pdf->Cell(
    28,
    5,
    'Date Inspected',
    1,
    0,
    'C'
);

$pdf->Cell(
    28,
    5,
    $dateInspected,
    1,
    1,
    'C'
);


// Inspected by

$pdf->SetXY(
    220,
    13
);

$pdf->Cell(
    28,
    5,
    'Inspected by',
    1,
    0,
    'C'
);

$pdf->Cell(
    28,
    5,
    $inspectedBy,
    1,
    1,
    'C'
);


// Approved by

$pdf->SetXY(
    220,
    18
);

$pdf->Cell(
    28,
    5,
    'Verified & Approved',
    1,
    0,
    'C'
);

$pdf->Cell(
    28,
    5,
    $approvedBy,
    1,
    1,
    'C'
);


// ============================================================
// TABLE
// ============================================================

$pdf->SetXY(
    8,
    35
);


$pdf->SetFont(
    'helvetica',
    'B',
    5.5
);


// ============================================================
// COLUMN WIDTHS
// ============================================================

$width = [

    'no' => 9,

    'location' => 28,

    'capacity' => 12,

    'type' => 12,

    'class' => 10,

    'seal' => 9,

    'pin' => 9,

    'pressure' => 13,

    'hose' => 9,

    'nozzle' => 10,

    'belt' => 9,

    'cylinder' => 12,

    'demarcation' => 13,

    'signage' => 11,

    'comments' => 32,

    'status' => 12,

    'action' => 30,

    'target' => 27
];


// ============================================================
// TABLE HEADER
// ============================================================

$pdf->SetFillColor(
    220,
    220,
    220
);


$headers = [

    'No.' => 'no',

    'Location' => 'location',

    'Capacity' => 'capacity',

    'Type' => 'type',

    'Class' => 'class',

    'Seal' => 'seal',

    'Pin' => 'pin',

    'Pressure' => 'pressure',

    'Hose' => 'hose',

    'Nozzle' => 'nozzle',

    'Belt' => 'belt',

    'Cylinder
(Body)' => 'cylinder',

    'Demarcation
Line' => 'demarcation',

    'Signage' => 'signage',

    'Comments' => 'comments',

    'Status
("√" or "X")' => 'status',

    'Action Taken' => 'action',

    'Target Date of
Implementation' => 'target'
];


foreach ($headers as $label => $key) {

    $pdf->MultiCell(

        $width[$key],

        13,

        $label,

        1,

        'C',

        true,

        0

    );
}

$pdf->Ln();


// ============================================================
// DATA
// ============================================================

$pdf->SetFont(
    'helvetica',
    '',
    6
);


$no = 1;


foreach ($inspections as $data) {


    // --------------------------------------------------------
    // ROW HEIGHT
    // --------------------------------------------------------

    $rowHeight = 18;


    // --------------------------------------------------------
    // BASIC INFORMATION
    // --------------------------------------------------------

    $pdf->Cell(
        $width['no'],
        $rowHeight,
        $no,
        1,
        0,
        'C'
    );


    $pdf->Cell(
        $width['location'],
        $rowHeight,
        $data['location'] ?? '',
        1,
        0,
        'L'
    );


    $pdf->Cell(
        $width['capacity'],
        $rowHeight,
        $data['capacity'] ?? '',
        1,
        0,
        'C'
    );


    $pdf->Cell(
        $width['type'],
        $rowHeight,
        $data['type'] ?? '',
        1,
        0,
        'C'
    );


    $pdf->Cell(
        $width['class'],
        $rowHeight,
        $data['class'] ?? '',
        1,
        0,
        'C'
    );


    // --------------------------------------------------------
    // CHECKPOINTS
    // --------------------------------------------------------

    $checkpoints = [

        'seal'
        => 'is_seal_ok',

        'pin'
        => 'is_pin_ok',

        'pressure'
        => 'is_pressure_ok',

        'hose'
        => 'is_hose_ok',

        'nozzle'
        => 'is_nozzle_ok',

        'belt'
        => 'is_belt_ok',

        'cylinder'
        => 'is_cylinder_body_ok',

        'demarcation'
        => 'is_demarcation_line_ok',

        'signage'
        => 'is_signage_ok'
    ];


    foreach ($checkpoints as $column => $field) {

        $value =
            $data[$field]
            ?? 0;


        /*
        |--------------------------------------------------------------------------
        | 1 = GOOD
        | 0 = NOT GOOD
        |--------------------------------------------------------------------------
        */

        $symbol =
            ((int) $value === 1)
            ? '✓'
            : 'X';


        $pdf->Cell(

            $width[$column],

            $rowHeight,

            $symbol,

            1,

            0,

            'C'

        );
    }


    // --------------------------------------------------------
    // COMMENTS
    // --------------------------------------------------------

    /*
     * Wala pang dedicated comments column
     * sa addInspectionChecklist() mo.
     *
     * Kaya blank muna ito.
     */

    $pdf->Cell(

        $width['comments'],

        $rowHeight,

        '',

        1,

        0,

        'L'

    );


    // --------------------------------------------------------
    // STATUS
    // --------------------------------------------------------

    $status =
        strtolower(
            trim(
                $data['status'] ?? ''
            )
        );


    $statusSymbol =
        ($status === 'good')
        ? '✓'
        : 'X';


    $pdf->Cell(

        $width['status'],

        $rowHeight,

        $statusSymbol,

        1,

        0,

        'C'

    );


    // --------------------------------------------------------
    // ACTION TAKEN
    // --------------------------------------------------------

    $pdf->MultiCell(

        $width['action'],

        $rowHeight,

        $data['action_taken'] ?? '',

        1,

        'L',

        false,

        0

    );


    // --------------------------------------------------------
    // TARGET DATE
    // --------------------------------------------------------

    $pdf->Cell(

        $width['target'],

        $rowHeight,

        $data['target_date_of_implementation'] ?? '',

        1,

        1,

        'C'

    );


    $no++;
}


// ============================================================
// NOTE
// ============================================================

$pdf->Ln(3);


$pdf->SetFont(
    'helvetica',
    'I',
    7
);


$pdf->Cell(
    0,
    5,
    'NOTE: Put "√" if GOOD; "X" if NOT GOOD.',
    0,
    1,
    'L'
);


// ============================================================
// APPROVAL INFORMATION
// ============================================================

$pdf->Ln(2);


$pdf->SetFont(
    'helvetica',
    '',
    7
);


$pdf->Cell(
    0,
    5,
    'Evaluation Status: APPROVED',
    0,
    1,
    'L'
);


// ============================================================
// OUTPUT
// ============================================================

$pdf->Output(
    'fire-extinguisher-inspection-report.pdf',
    'I'
);
