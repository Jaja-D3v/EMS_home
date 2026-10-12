<?php

require_once __DIR__ . '/../config/db.php';

$branch = $_SESSION['Branch'];

/**
 * Build shared filters for listing and counting.
 * All data comes from inspection_checklist_tbl only.
 */
function myInspectionsBuildFilters(
    string $inspectorName,
    string $search,
    string $dateFilter,
    string $statusFilter
): array {
    global $branch;

    $where = [
        'i.inspected_by = ?',
        'i.branch = ?'
    ];

    $params = [$inspectorName, $branch];
    $types = 'ss';

    if ($search !== '') {
        $where[] = "(
            i.extinguisher_code LIKE ?
            OR i.evaluation_status LIKE ?
            OR i.inspected_by LIKE ?
        )";

        $searchTerm = '%' . $search . '%';

        for ($x = 0; $x < 3; $x++) {
            $params[] = $searchTerm;
            $types .= 's';
        }
    }

    switch ($dateFilter) {
        case 'today':
            $where[] = 'DATE(i.date_inspected) = CURDATE()';
            break;

        case 'yesterday':
            $where[] = 'DATE(i.date_inspected) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)';
            break;

        case '7':
            $where[] = 'i.date_inspected >= DATE_SUB(NOW(), INTERVAL 7 DAY)';
            $where[] = 'i.date_inspected <= NOW()';
            break;

        case '30':
            $where[] = 'i.date_inspected >= DATE_SUB(NOW(), INTERVAL 30 DAY)';
            $where[] = 'i.date_inspected <= NOW()';
            break;
    }

    if ($statusFilter !== 'all') {
        $where[] = 'i.evaluation_status = ?';
        $params[] = $statusFilter;
        $types .= 's';
    }

    return [
        'sql' => implode(' AND ', $where),
        'types' => $types,
        'params' => $params,
    ];
}

/**
 * Bind parameters dynamically.
 */
function myInspectionsBindParams(
    mysqli_stmt $stmt,
    string $types,
    array &$params
): bool {
    if ($types === '' || empty($params)) {
        return true;
    }

    $bindValues = [$types];

    foreach ($params as &$value) {
        $bindValues[] = &$value;
    }

    unset($value);

    return call_user_func_array(
        [$stmt, 'bind_param'],
        $bindValues
    );
}

/**
 * Get paginated inspection records.
 */
function getMyInspectionsModel(
    string $inspectorName,
    string $search,
    string $dateFilter,
    string $statusFilter,
    int $limit,
    int $offset
): array {
    global $conn, $branch;

    $filters = myInspectionsBuildFilters(
        $inspectorName,
        $search,
        $dateFilter,
        $statusFilter
    );

    $sql = "
        SELECT i.*
        FROM inspection_checklist_tbl AS i
        INNER JOIN (
            SELECT
                extinguisher_code,
                MAX(inspect_id) AS latest_inspect_id
            FROM inspection_checklist_tbl
            WHERE branch = ?
            GROUP BY extinguisher_code
        ) AS latest
            ON latest.latest_inspect_id = i.inspect_id
        WHERE {$filters['sql']}
        ORDER BY i.date_inspected DESC, i.inspect_id DESC
        LIMIT ? OFFSET ?
    ";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        error_log(
            'My Inspections prepare failed: ' . mysqli_error($conn)
        );
        return [];
    }

    // Unang parameter ang branch para sa subquery.
    $params = array_merge([$branch], $filters['params']);
    $types = 's' . $filters['types'] . 'ii';

    $params[] = $limit;
    $params[] = $offset;

    if (!myInspectionsBindParams($stmt, $types, $params)) {
        error_log(
            'My Inspections bind failed: ' . mysqli_stmt_error($stmt)
        );
        mysqli_stmt_close($stmt);
        return [];
    }

    if (!mysqli_stmt_execute($stmt)) {
        error_log(
            'My Inspections query failed: ' . mysqli_stmt_error($stmt)
        );
        mysqli_stmt_close($stmt);
        return [];
    }

    $result = mysqli_stmt_get_result($stmt);

    $records = $result
        ? mysqli_fetch_all($result, MYSQLI_ASSOC)
        : [];

    mysqli_stmt_close($stmt);

    return $records;
}

/**
 * Count records using the same filters as the listing.
 */
function getMyInspectionsCountModel(
    string $inspectorName,
    string $search,
    string $dateFilter,
    string $statusFilter
): int {
    global $conn;

    $filters = myInspectionsBuildFilters(
        $inspectorName,
        $search,
        $dateFilter,
        $statusFilter
    );

    $sql = "
        SELECT COUNT(*) AS total
        FROM (
            SELECT i.inspect_id
            FROM inspection_checklist_tbl AS i
            INNER JOIN (
                SELECT
                    extinguisher_code,
                    MAX(inspect_id) AS latest_inspect_id
                FROM inspection_checklist_tbl
                GROUP BY extinguisher_code
            ) AS latest
                ON latest.latest_inspect_id = i.inspect_id
            WHERE {$filters['sql']}
        ) AS unique_inspections
    ";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        error_log(
            'My Inspections count prepare failed: ' . mysqli_error($conn)
        );
        return 0;
    }

    $params = $filters['params'];

    if (!myInspectionsBindParams($stmt, $filters['types'], $params)) {
        error_log(
            'My Inspections count bind failed: ' . mysqli_stmt_error($stmt)
        );
        mysqli_stmt_close($stmt);
        return 0;
    }

    if (!mysqli_stmt_execute($stmt)) {
        error_log(
            'My Inspections count failed: ' . mysqli_stmt_error($stmt)
        );
        mysqli_stmt_close($stmt);
        return 0;
    }

    $result = mysqli_stmt_get_result($stmt);
    $row = $result ? mysqli_fetch_assoc($result) : null;

    mysqli_stmt_close($stmt);

    return (int) ($row['total'] ?? 0);
}


/**
 * Summary counts for the logged-in inspector.
 */
function getMyInspectionsSummaryModel(string $inspectorName): array
{
    global $conn;

    $branch = $_SESSION['Branch'] ?? '';

    $summary = [
        'total' => 0,
        'pending' => 0,
        'approved' => 0,
    ];

    $sql = "
        SELECT
            COUNT(*) AS total,
            COALESCE(
                SUM(evaluation_status = 'Pending'), 0
            ) AS pending,
            COALESCE(
                SUM(evaluation_status = 'Approved'), 0
            ) AS approved
        FROM inspection_checklist_tbl
        WHERE inspected_by = ?
          AND branch = ?
    ";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        error_log(
            'My Inspections summary prepare failed: ' . mysqli_error($conn)
        );
        return $summary;
    }

    mysqli_stmt_bind_param($stmt, 'ss', $inspectorName, $branch);

    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $row = $result ? mysqli_fetch_assoc($result) : null;

        if ($row) {
            $summary = [
                'total' => (int) $row['total'],
                'pending' => (int) $row['pending'],
                'approved' => (int) $row['approved'],
            ];
        }
    } else {
        error_log(
            'My Inspections summary query failed: ' . mysqli_stmt_error($stmt)
        );
    }

    mysqli_stmt_close($stmt);

    return $summary;
}
