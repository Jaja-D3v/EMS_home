<?php

ob_start();

require_once __DIR__ . '/../backend/authentication/SessionChecker.php';
require_once __DIR__ . '/../backend/config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

/*  Helpers */

function formatChecklistValue($value)
{
    if ($value === null || $value === '') {
        return 'N/A';
    }

    return ((int) $value === 1) ? '✔' : '✘';
}

function hasValue($value)
{
    if ($value === null || $value === '') {
        return 'N/A';
    }

    return ((int) $value === 1) ? '✔' : '✘';
}


function formatReportDate($value)
{
    if (
        $value === null ||
        trim((string) $value) === '' ||
        strpos((string) $value, '0000-00-00') === 0
    ) {
        return 'N/A';
    }

    return date('M d, Y');
}


function reportError($message, $statusCode)
{
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    http_response_code($statusCode);
    exit($message);
}

/* Validate inspection IDs */
$rawIds = isset($_GET['ids']) ? $_GET['ids'] : '';
$ids = array();

foreach (explode(',', $rawIds) as $id) {
    $id = trim($id);

    if ($id !== '' && ctype_digit($id)) {
        $id = (int) $id;

        if ($id > 0) {
            $ids[] = $id;
        }
    }
}

$ids = array_values(array_unique($ids));

if (empty($ids)) {
    reportError('No inspection records selected.', 400);
}

/* Fetch approved inspections */
$placeholders = implode(',', array_fill(0, count($ids), '?'));

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
        remarks,
        is_seal_ok,
        is_pin_ok,
        is_pressure_ok,
        is_hose_ok,
        is_nozzle_ok,
        is_belt_ok,
        is_cylinder_body_ok,
        is_demarcation_line_ok,
        is_signage_ok,
        is_cleaning_of_unit_ok,
        status,
        evaluation_status,
        branch
    FROM inspection_checklist_tbl
    WHERE inspect_id IN ($placeholders)
      AND evaluation_status = ?
    ORDER BY date_inspected DESC
";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    error_log('Excel report prepare error: ' . mysqli_error($conn));
    reportError('Unable to prepare inspection report.', 500);
}

$types = str_repeat('i', count($ids)) . 's';
$params = array_merge($ids, array('Approved'));
$bindParams = array($types);

foreach ($params as $key => $value) {
    $bindParams[] = &$params[$key];
}

if (!call_user_func_array(
    'mysqli_stmt_bind_param',
    array_merge(array($stmt), $bindParams)
)) {
    error_log('Excel report bind error: ' . mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    reportError('Unable to bind inspection report parameters.', 500);
}

if (!mysqli_stmt_execute($stmt)) {
    error_log('Excel report execute error: ' . mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    reportError('Unable to retrieve inspection records.', 500);
}

$result = mysqli_stmt_get_result($stmt);

if ($result === false) {
    error_log('Excel report result error: ' . mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    reportError('Unable to retrieve inspection records.', 500);
}

if (mysqli_num_rows($result) === 0) {
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    reportError('No approved inspection records found.', 404);
}


/*  Spreadsheet setup */
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Inspection Report');

/*  Report title  */
$sheet->mergeCells('A1:X1');
$sheet->setCellValue(
    'A1',
    'FIRE EXTINGUISHER INSPECTION REPORT'
);

$sheet->getStyle('A1:X1')->applyFromArray(array(
    'font' => array(
        'bold' => true,
        'size' => 16,
        'color' => array('rgb' => 'FFFFFF')
    ),
    'fill' => array(
        'fillType' => Fill::FILL_SOLID,
        'startColor' => array('rgb' => '17365D')
    ),
    'alignment' => array(
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
    )
));

$sheet->getRowDimension(1)->setRowHeight(34);
$sheet->mergeCells('A2:X2');
$sheet->setCellValue( 'A2', 'Approved Inspections | Generated: ' . date('F d, Y') );

$sheet->getStyle('A2:X2')->applyFromArray(array(
    'font' => array(
        'italic' => true,
        'size' => 10,
        'color' => array('rgb' => '44546A')
    ),
    'fill' => array(
        'fillType' => Fill::FILL_SOLID,
        'startColor' => array('rgb' => 'D9EAF7')
    ),
    'alignment' => array(
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
    )
));

$sheet->getRowDimension(2)->setRowHeight(23);

/* Column headers */
$headers = array(
    'FE Code',
    'Location',
    'Capacity',
    'Type',
    'Class',
    'Verified and Approved By',
    'Action Taken',
    'Target Date of Implementation',
    'Comments',
    'Seal',
    'Pin',
    'Pressure',
    'Hose',
    'Nozzle',
    'Belt',
    'Cylinder Body',
    'Demarcation Line',
    'Signage',
    'Cleaning of Unit',
    'Condition Status',
    'Evaluation Status',
    'Branch',
    'Inspected By',
    'Date Inspected'

);

$sheet->fromArray($headers, null, 'A3');
$sheet->getStyle('A3:X3')->applyFromArray(array(
    'font' => array(
        'bold' => true,
        'color' => array('rgb' => 'FFFFFF'),
        'size' => 10
    ),
    'fill' => array(
        'fillType' => Fill::FILL_SOLID,
        'startColor' => array('rgb' => '2874A6')
    ),
    'alignment' => array(
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true
    ),
    'borders' => array(
        'bottom' => array(
            'borderStyle' => Border::BORDER_MEDIUM,
            'color' => array('rgb' => '17365D')
        )
    )
));

$sheet->getRowDimension(3)->setRowHeight(42);

/*  Write inspection data */
$checklistColumns = array(
    'J',
    'K',
    'L',
    'M',
    'N',
    'O',
    'P',
    'Q',
    'R',
    'S'
);

$rowNumber = 4;

while ($row = mysqli_fetch_assoc($result)) {
    $checklistFields = array(
        $row['is_seal_ok'],
        $row['is_pin_ok'],
        $row['is_pressure_ok'],
        $row['is_hose_ok'],
        $row['is_nozzle_ok'],
        $row['is_belt_ok'],
        $row['is_cylinder_body_ok'],
        $row['is_demarcation_line_ok'],
        $row['is_signage_ok'],
        $row['is_cleaning_of_unit_ok']
    );

    $allGood = true;

    foreach ($checklistFields as $value) {
        if (
            $value === null ||
            $value === '' ||
            (int) $value !== 1
        ) {
            $allGood = false;
            break;
        }
    }

    $conditionStatus = $allGood ? 'Good' : 'Not Good';

    $data = array(
        $row['extinguisher_code'],
        $row['location'],
        $row['capacity'],
        $row['type'],
        $row['class'],
        $row['verified_and_approved_by'],
        hasValue($row['action_taken']),
        formatReportDate($row['target_date_of_implementation']),
        isset($row['remarks']) ? $row['remarks'] : '',
        formatChecklistValue($row['is_seal_ok']),
        formatChecklistValue($row['is_pin_ok']),
        formatChecklistValue($row['is_pressure_ok']),
        formatChecklistValue($row['is_hose_ok']),
        formatChecklistValue($row['is_nozzle_ok']),
        formatChecklistValue($row['is_belt_ok']),
        formatChecklistValue($row['is_cylinder_body_ok']),
        formatChecklistValue($row['is_demarcation_line_ok']),
        formatChecklistValue($row['is_signage_ok']),
        formatChecklistValue($row['is_cleaning_of_unit_ok']),
        $conditionStatus,
        $row['evaluation_status'],
        $row['branch'],
        $row['inspected_by'],
        formatReportDate($row['date_inspected'])

    );

    /*
     * Explicit string cells help prevent exported text from being
     * interpreted as an Excel formula.
     */
    foreach ($data as $index => $value) {
        $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
            $index + 1
        );

        $sheet->setCellValueExplicit(
            $column . $rowNumber,
            $value === null ? '' : (string) $value,
            DataType::TYPE_STRING
        );
    }

    /*
     * Alternate row backgrounds.
     */
    if ($rowNumber % 2 === 0) {
        $sheet->getStyle('A' . $rowNumber . ':X' . $rowNumber)
            ->getFill()
            ->setFillType(Fill::FILL_SOLID);

        $sheet->getStyle('A' . $rowNumber . ':X' . $rowNumber)
            ->getFill()
            ->getStartColor()
            ->setRGB('F2F6FA');
    }

    /*
     * Good / Not Good status colors.
     */
    $statusColor = $allGood ? 'E2F0D9' : 'FCE4D6';
    $statusFont = $allGood ? '38761D' : 'C00000';

    $sheet->getStyle('V' . $rowNumber)->applyFromArray(array(
        'fill' => array(
            'fillType' => Fill::FILL_SOLID,
            'startColor' => array('rgb' => 'c4efff')
        ),
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '0040ff')
        ),
        'alignment' => array(
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER
        )
    ));

    $sheet->getStyle('W' . $rowNumber)->applyFromArray(array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '1F4E78')
        ),
        'alignment' => array(
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER
        )
    ));

    /* Checklist cells. */
    foreach ($checklistColumns as $column) {
        $cell = $column . $rowNumber;
        $cellValue = $sheet->getCell($cell)->getValue();

        if ($cellValue === '✔') {
            $sheet->getStyle($cell)->applyFromArray(array(
                'font' => array(
                    'bold' => true,
                    'color' => array('rgb' => '008000')
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                )
            ));
        } elseif ($cellValue === '✘' || $cellValue === 'X') {
            $sheet->getStyle($cell)->applyFromArray(array(
                'font' => array(
                    'bold' => true,
                    'color' => array('rgb' => 'FF0000')
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                )
            ));
        }
    }


    $rowNumber++;
}

$lastRow = $rowNumber - 1;

/*  Borders, alignment, and row sizing */
$sheet->getStyle('A3:X' . $lastRow)->applyFromArray(array(
    'borders' => array(
        'allBorders' => array(
            'borderStyle' => Border::BORDER_THIN,
            'color' => array('rgb' => 'D9E2F3')
        )
    )
));

$sheet->getStyle('A4:X' . $lastRow)
    ->getAlignment()
    ->setVertical(Alignment::VERTICAL_CENTER);

$sheet->getStyle('A4:X' . $lastRow)
    ->getAlignment()
    ->setWrapText(true);

$sheet->getStyle('C4:F' . $lastRow)
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->getStyle('J4:S' . $lastRow)
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

for ($r = 4; $r <= $lastRow; $r++) {
    $sheet->getRowDimension($r)->setRowHeight(30);
}

/* Automatic column widths with sensible limits */
foreach (range('A', 'X') as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

/* Calculate the automatic widths before applying maximum widths. */
$sheet->calculateColumnWidths();

$maxWidths = array(
    'A' => 18,
    'B' => 24,
    'C' => 12,
    'D' => 18,
    'E' => 12,
    'F' => 18,
    'G' => 24,
    'H' => 28,
    'I' => 32,
    'J' => 24,
    'K' => 38,
    'L' => 10,
    'M' => 10,
    'N' => 12,
    'O' => 10,
    'P' => 10,
    'Q' => 10,
    'R' => 18,
    'S' => 20,
    'T' => 12,
    'U' => 18,
    'V' => 18,
    'W' => 20,
    'X' => 18
);

foreach ($maxWidths as $column => $maxWidth) {
    $currentWidth = $sheet->getColumnDimension($column)->getWidth();

    if ($currentWidth > $maxWidth) {
        $sheet->getColumnDimension($column)->setAutoSize(false);
        $sheet->getColumnDimension($column)->setWidth($maxWidth);
    }
}

/*  Keep checklist columns compact. */
foreach ($checklistColumns as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(false);
    $sheet->getColumnDimension($column)->setWidth(11);
}

/* Filters and frozen header */
$sheet->setAutoFilter('A3:X' . $lastRow);
$sheet->freezePane('A4');

/* Print settings */
$sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A3);
$sheet->getPageSetup()->setFitToWidth(1);
$sheet->getPageSetup()->setFitToHeight(0);
$sheet->getPageSetup()->setFitToPage(true);
$sheet->getPageMargins()->setTop(0.35);
$sheet->getPageMargins()->setBottom(0.35);
$sheet->getPageMargins()->setLeft(0.25);
$sheet->getPageMargins()->setRight(0.25);

$sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 3);

/* Download XLSX */
mysqli_free_result($result);
mysqli_stmt_close($stmt);

while (ob_get_level() > 0) {
    ob_end_clean();
}

if (headers_sent()) {
    exit('Cannot generate Excel report because output has already started.');
}

$filename = 'Inspection Report ' . date('Y-m-d') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');
header('Pragma: public');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

$spreadsheet->disconnectWorksheets();
unset($spreadsheet);

exit;
