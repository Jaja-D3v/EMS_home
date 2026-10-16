
<?php

require_once __DIR__ . '/../model/FireExtinguisherHistoryModel.php';

function getFireExtinguisherHistoryController()
{
    $code = trim($_GET['extinguisher_code'] ?? '');
    $dateFrom = $_GET['date_from'] ?? date('Y-m-01');
    $dateTo = $_GET['date_to'] ?? date('Y-m-d');

    $extinguisherCodes = getAllFireExtinguisherCodes();

    $details = null;
    $records = [];
    $timeline = [];
    $errors = [];

    $isValidDate = function ($date) {
        if (!is_string($date)) {
            return false;
        }

        $parsed = DateTime::createFromFormat('!Y-m-d', $date);

        return $parsed && $parsed->format('Y-m-d') === $date;
    };

    if ($code === '') {
        $errors[] = 'Please select a fire extinguisher.';
    }

    if (
        !$isValidDate($dateFrom) ||
        !$isValidDate($dateTo)
    ) {
        $errors[] = 'Please enter valid dates.';
    } elseif ($dateFrom > $dateTo) {
        $errors[] = 'Date From must not be later than Date To.';
    }

    if (empty($errors)) {
        try {
            $details = getFireExtinguisherHistoryDetails(
                $code
            );

            // var_dump($details);

            if (!$details) {
                $errors[] = 'Fire extinguisher not found.';
            } else {
                $records = getFireExtinguisherInspectionHistory(
                    $code,
                    $dateFrom,
                    $dateTo
                );

                $timeline = getFireExtinguisherHistoryTimeline(
                    $records
                );
            }
        } catch (Throwable $e) {
            error_log(
                'Fire Extinguisher History: ' . $e->getMessage()
            );

            $errors[] = 'Unable to load fire extinguisher history.';
        }
    }

    return [
        'code' => $code,
        'dateFrom' => $dateFrom,
        'dateTo' => $dateTo,
        'extinguisherCodes' => $extinguisherCodes,
        'details' => $details,
        'records' => $records,
        'timeline' => $timeline,
        'errors' => $errors
    ];
}
