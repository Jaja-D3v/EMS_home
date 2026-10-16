
<?php
require_once __DIR__ . '/../config/db.php';


function getFireExtinguisherHistoryDetails( $code)
{
    global $conn;

    $sql = "
        SELECT *
        FROM fire_extinguishers_tbl
        WHERE extinguisher_code = ?
          AND archived = 0
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $code);
    $stmt->execute();

    $result = $stmt->get_result();
    $details = $result->fetch_assoc();
    

    $stmt->close();

    return $details ?: null;
}


function getFireExtinguisherInspectionHistory(
    $code,
    $dateFrom,
    $dateTo
) {
    global $conn;

    $sql = "
        SELECT *
        FROM inspection_checklist_tbl
        WHERE extinguisher_code = ?
          AND created_at >= ?
          AND created_at < DATE_ADD(?, INTERVAL 1 DAY)
        ORDER BY created_at ASC
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sss",
        $code,
        $dateFrom,
        $dateTo
    );

    $stmt->execute();

    $result = $stmt->get_result();
    $records = [];

    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }

    $stmt->close();

    return $records;
}


function getFireExtinguisherHistoryTimeline($records)
{
    $timeline = [];

    foreach ($records as $record) {
        $inspectionStatus = strtolower(
            trim($record['inspection_status'] ?? '')
        );

        $evaluationStatus = strtolower(
            trim($record['evaluation_status'] ?? '')
        );

        $createdAt = $record['created_at'] ?? null;
        $inspector = $record['inspected_by'] ?? 'Unknown';
        $remarks = $record['remarks'] ?? '';

        // Inspection completed
        $timeline[] = [
            'date' => $createdAt,
            'title' => 'Inspection Completed',
            'description' => 'Inspected by: ' . $inspector,
            'status' => 'completed',
            'reason' => $remarks
        ];

        // Rejected
        if ($evaluationStatus === 'rejected') {
            $timeline[] = [
                'date' => $record['rejection_date'] ?? $createdAt,
                'title' => 'Inspection Rejected',
                'description' => 'Inspection was rejected.',
                'status' => 'rejected',
                'reason' => $record['reject_reason'] ?? ''
            ];
        }

        // Re-inspected
        if (in_array($inspectionStatus, [
            're-inspected',
            'reinspected',
            'resubmitted'
        ], true)) {
            $timeline[] = [
                'date' => $createdAt,
                'title' => 'Re-Inspected',
                'description' => 'Inspection submitted again.',
                'status' => 'reinspected',
                'reason' => $remarks
            ];
        }

        // Pending approval
        if (in_array($inspectionStatus, [
            'pending',
            'pending approval'
        ], true)) {
            $timeline[] = [
                'date' => $createdAt,
                'title' => 'Pending Approval',
                'description' => 'Waiting for approval.',
                'status' => 'pending',
                'reason' => $remarks
            ];
        }

        // Approved
        if ($evaluationStatus === 'approved') {
            $timeline[] = [
                'date' => $createdAt,
                'title' => 'Inspection Approved',
                'description' => 'Inspection approved.',
                'status' => 'approved',
                'reason' => $remarks
            ];
        }
    }

    usort($timeline, function ($a, $b) {
        return strtotime($a['date'] ?? '')
            <=> strtotime($b['date'] ?? '');
    });

    return $timeline;
}


function getAllFireExtinguisherCodes()
{
    global $conn;

    $sql = "
        SELECT extinguisher_code
        FROM fire_extinguishers_tbl
        WHERE archived = 0
        ORDER BY extinguisher_code ASC
    ";

    $result = $conn->query($sql);
    $codes = [];

    while ($row = $result->fetch_assoc()) {
        $codes[] = $row['extinguisher_code'];
    }

    return $codes;
}

