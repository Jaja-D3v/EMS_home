<?php

require_once 'backend/authentication/SessionChecker.php';
require_once 'backend/controller/InspectionController.php';
require_once 'backend/controller/DropdownBranchController.php';

$limit = 10;

$page = isset($_GET['page'])
    ? (int) $_GET['page']
    : 1;

if ($page < 1) {
    $page = 1;
}

$date = $_GET['date'] ?? null;

if ($date === '') {
    $date = null;
}

$totalRecords = getRejectedApprovalTotal($date);

$totalPages = (int) ceil($totalRecords / $limit);

if ($totalPages > 0 && $page > $totalPages) {
    $page = $totalPages;
}

$offset = ($page - 1) * $limit;

$result = getRejectedApprovals(
    $limit,
    $offset,
    $date
);

$inspectionChecklists = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $inspectionChecklists[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?php include 'partials/header.php'; ?>

    <style>
        /* =========================================================
           Inspection Rejected Page
           Responsive Layout
        ========================================================= */

        .inspection-page {
            padding-bottom: 80px;
        }

        /* Page Header */
        .inspection-header {
            border-radius: 0.75rem;
        }

        .inspection-header-icon {
            width: 64px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Filter Card */
        .inspection-filter-card {
            border-radius: 0.75rem;
        }

        .inspection-filter-card .card-body {
            padding: 1rem;
        }

        .inspection-filter-card .input-group,
        .inspection-filter-card .form-select {
            min-height: 42px;
        }

        .inspection-filter-card .input-group-text {
            min-width: 44px;
            justify-content: center;
        }

        /* Table */
        .inspection-table-card {
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .inspection-table-wrapper {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .inspection-table {
            min-width: 900px;
            margin-bottom: 0 !important;
        }

        .inspection-table th {
            font-size: 0.82rem;
            font-weight: 600;
            white-space: nowrap;
            vertical-align: middle;
        }

        .inspection-table td {
            font-size: 0.9rem;
            vertical-align: middle;
        }

        .inspection-table tbody tr {
            height: 64px;
        }

        .inspection-table .status-badge {
            white-space: nowrap;
        }

        .inspection-table .inspection-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.35rem;
        }

        .inspection-table .inspection-actions .btn {
            white-space: nowrap;
        }

        /* Empty/Search State */
        .inspection-empty-state {
            min-height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Pagination */
        .inspection-pagination {
            z-index: 1020;
        }

        .inspection-pagination-inner {
            width: 100%;
        }

        .inspection-pagination .pagination {
            margin-bottom: 0;
        }

        /* Prevent content from hiding behind fixed pagination */
        .inspection-content {
            padding-bottom: 90px;
        }

        /* Mobile */
        @media (max-width: 991.98px) {

            .inspection-header .card-body {
                padding: 1.25rem;
            }

            .inspection-header-icon {
                width: 56px;
                height: 56px;
                font-size: 1.6rem !important;
            }

            .inspection-header h2 {
                font-size: 1.4rem;
            }
        }

        @media (max-width: 767.98px) {

            .inspection-page {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            .inspection-header .card-body {
                padding: 1rem;
            }

            .inspection-header .row {
                align-items: center;
            }

            .inspection-header-icon {
                width: 52px;
                height: 52px;
                padding: 0.75rem !important;
                font-size: 1.4rem !important;
            }

            .inspection-header h2 {
                font-size: 1.2rem;
            }

            .inspection-header p {
                font-size: 0.85rem;
            }

            .inspection-filter-card .card-body {
                padding: 0.85rem;
            }

            .inspection-table {
                min-width: 900px;
            }

            .inspection-table th,
            .inspection-table td {
                padding-top: 0.75rem;
                padding-bottom: 0.75rem;
            }

            .inspection-table .inspection-actions {
                justify-content: flex-end;
            }

            .inspection-table .inspection-actions .btn {
                font-size: 0.78rem;
                padding: 0.3rem 0.55rem;
            }

            .inspection-pagination {
                padding-top: 0.5rem !important;
                padding-bottom: 0.5rem !important;
            }

            .inspection-pagination .container-fluid {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
        }

        @media (max-width: 575.98px) {

            .inspection-page {
                padding-left: 0.25rem;
                padding-right: 0.25rem;
            }

            .inspection-header {
                margin-bottom: 0.75rem !important;
            }

            .inspection-header .row {
                flex-wrap: nowrap;
            }

            .inspection-header-icon {
                width: 46px;
                height: 46px;
                padding: 0.6rem !important;
            }

            .inspection-header h2 {
                font-size: 1.05rem;
            }

            .inspection-header p {
                font-size: 0.78rem;
            }

            .inspection-filter-card {
                margin-bottom: 0.75rem !important;
            }

            .inspection-filter-card .card-body {
                padding: 0.75rem;
            }

            .inspection-table-card {
                border-radius: 0.6rem;
            }

            .inspection-table {
                min-width: 880px;
            }

            .inspection-pagination-inner {
                gap: 0.5rem !important;
            }

            .inspection-pagination .text-body-secondary {
                font-size: 0.72rem;
            }

            .inspection-pagination .page-link {
                padding: 0.3rem 0.55rem;
            }
        }
    </style>

</head>

<body>

    <?php include 'partials/side-nav.php'; ?>

    <div class="wrapper d-flex flex-column min-vh-100">

        <?php include 'partials/header-nav.php'; ?>

        <!-- CONTENT -->
        <div class="container-fluid py-0 inspection-content">

            <div class="inspection-page">

                <!-- Page Header -->
                <div
                    class="card border-0 shadow-sm text-white mb-3 mt-0 overflow-hidden inspection-header"
                    style="background: linear-gradient(135deg, #1e3dc8, #57f9ff);">

                    <div class="card-body p-4">

                        <div class="row align-items-center g-3">

                            <div class="col-auto">

                                <div
                                    class="bg-white bg-opacity-25 rounded-3 p-3 fs-2 inspection-header-icon">

                                    <i class="bi bi-clipboard2-check"></i>

                                </div>

                            </div>

                            <div class="col">

                                <h2 class="fw-bold mb-1">
                                    Inspection Record
                                </h2>

                                <p class="mb-0 text-white-50">
                                    Review and approve inspection reports.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Filters -->
                <div class="card border-0 shadow-sm mb-3 inspection-filter-card">

                    <div class="card-body">

                        <div class="row g-3 align-items-center">

                            <!-- Search -->
                            <div class="col-12 col-lg-6">

                                <div class="input-group">

                                    <span class="input-group-text bg-white">

                                        <i class="bi bi-search"></i>

                                    </span>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="inspectionSearch"
                                        placeholder="Search by FE code or location...">

                                </div>

                            </div>

                            <!-- Date -->
                            <div class="col-12 col-md-6 col-lg-3">

                                <div class="input-group">

                                    <span class="input-group-text bg-white">

                                        <i class="bi bi-calendar3"></i>

                                    </span>

                                    <input
                                        type="date"
                                        class="form-control"
                                        id="inspectionDateFilter"
                                        value="<?= htmlspecialchars($date ?? '') ?>"
                                        onchange="
                                            const selectedDate = this.value;

                                            if (selectedDate) {
                                                window.location.href =
                                                    'inspection-rejected.php?page=1&date=' +
                                                    encodeURIComponent(selectedDate);
                                            } else {
                                                window.location.href =
                                                    'inspection-rejected.php?page=1';
                                            }
                                        ">

                                </div>

                            </div>

                            <!-- Branch Filter - ADMIN ONLY -->
                            <?php if (strtolower(trim($_SESSION['Role'] ?? '')) === 'admin'): ?>

                                <?php

                                // Branch ng naka-login na Admin
                                $adminBranch = trim($_SESSION['Branch'] ?? '');

                                // Kung may branch sa URL, iyon ang gagamitin.
                                // Kung wala, gamitin ang branch ng Admin.
                                $selectedBranch = $_GET['branch'] ?? $adminBranch;

                                // Kung walang branch sa session at wala rin sa URL,
                                // default sa All Branches
                                if (empty($selectedBranch)) {
                                    $selectedBranch = 'all';
                                }

                                // Get all branches
                                $branches = getAllDropdownBranches();

                                ?>

                                <div class="col-12 col-md-6 col-lg-3">

                                    <select
                                        class="form-select w-100"
                                        id="branchFilter">

                                        <!-- All Branches -->
                                        <option
                                            value="all"
                                            <?= $selectedBranch === 'all' ? 'selected' : '' ?>>
                                            All Branches
                                        </option>

                                        <?php if (!empty($branches)): ?>

                                            <?php foreach ($branches as $branch): ?>

                                                <option
                                                    value="<?= htmlspecialchars($branch['value']) ?>"
                                                    <?= $selectedBranch === $branch['value'] ? 'selected' : '' ?>>

                                                    <?= htmlspecialchars($branch['value']) ?>

                                                </option>

                                            <?php endforeach; ?>

                                        <?php endif; ?>

                                    </select>

                                </div>

                            <?php endif; ?>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {

                                    const branchFilter =
                                        document.getElementById('branchFilter');

                                    if (!branchFilter) {
                                        return;
                                    }

                                    branchFilter.addEventListener('change', function() {

                                        const selectedBranch = this.value;

                                        const url = new URL(window.location.href);

                                        // Always set the branch parameter.
                                        // This allows "all" to remain selected after reload.
                                        url.searchParams.set('branch', selectedBranch);

                                        // Reload page with selected branch
                                        window.location.href = url.toString();

                                    });

                                });
                            </script>

                        </div>

                    </div>

                </div>

                <!-- Inspection Table -->
                <div class="card border-0 shadow-sm inspection-table-card">

                    <!-- Table -->
                    <div class="card-body p-0">

                        <div class="table-responsive inspection-table-wrapper">

                            <table class="table table-hover align-middle inspection-table">

                                <thead class="table-light">

                                    <tr>

                                        <th class="ps-4 py-3 text-nowrap">
                                            FE Code
                                        </th>

                                        <th class="py-3 text-nowrap">
                                            Location
                                        </th>

                                        <th class="py-3 text-nowrap">
                                            Date Inspected
                                        </th>

                                        <th class="py-3 text-nowrap">
                                            Branch
                                        </th>

                                        <th class="py-3 text-nowrap">
                                            Status
                                        </th>

                                        <th class="py-3 text-nowrap text-end pe-4">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>

                                <tbody id="inspectionList">

                                    <?php if (!empty($inspectionChecklists)): ?>

                                        <?php foreach ($inspectionChecklists as $inspection): ?>

                                            <?php

                                            $status = $inspection['evaluation_status'] ?? 'Pending';

                                            switch ($status) {

                                                case 'Approved':

                                                    $statusClass =
                                                        'bg-success-subtle text-success';

                                                    $statusIcon =
                                                        'bi-check-circle-fill';

                                                    $statusFilterValue =
                                                        'approved';

                                                    $statusLabel =
                                                        'Approved';

                                                    break;

                                                case 'Rejected':

                                                    $statusClass =
                                                        'bg-danger-subtle text-danger';

                                                    $statusIcon =
                                                        'bi-x-circle-fill';

                                                    $statusFilterValue =
                                                        'rejected';

                                                    $statusLabel =
                                                        'Rejected';

                                                    break;

                                                case 'Pending':
                                                default:

                                                    $statusClass =
                                                        'bg-warning-subtle text-warning-emphasis';

                                                    $statusIcon =
                                                        'bi-clock';

                                                    $statusFilterValue =
                                                        'pending';

                                                    $statusLabel =
                                                        'Pending Approval';

                                                    break;
                                            }

                                            $inspectionDate = '';

                                            if (!empty($inspection['date_inspected'])) {

                                                $timestamp = strtotime(
                                                    $inspection['date_inspected']
                                                );

                                                if ($timestamp !== false) {

                                                    $inspectionDate = date(
                                                        'Y-m-d',
                                                        $timestamp
                                                    );

                                                }
                                            }

                                            ?>

                                            <tr
                                                class="inspection-row"

                                                data-fe-code="<?= htmlspecialchars(
                                                    strtolower(
                                                        $inspection['extinguisher_code'] ?? ''
                                                    )
                                                ) ?>"

                                                data-location="<?= htmlspecialchars(
                                                    strtolower(
                                                        $inspection['location'] ?? ''
                                                    )
                                                ) ?>"

                                                data-status="<?= htmlspecialchars(
                                                    $statusFilterValue
                                                ) ?>"

                                                data-date="<?= htmlspecialchars(
                                                    $inspectionDate
                                                ) ?>">

                                                <!-- FE Code -->
                                                <td class="ps-4">

                                                    <span class="text-primary fw-semibold">

                                                        <?= htmlspecialchars(
                                                            $inspection['extinguisher_code'] ?? '—'
                                                        ) ?>

                                                    </span>

                                                </td>

                                                <!-- Location -->
                                                <td>

                                                    <?= htmlspecialchars(
                                                        $inspection['location'] ?? '—'
                                                    ) ?>

                                                </td>

                                                <!-- Date Inspected -->
                                                <td class="text-nowrap">

                                                    <?php if (!empty($inspection['date_inspected'])): ?>

                                                        <?php

                                                        $timestamp = strtotime(
                                                            $inspection['date_inspected']
                                                        );

                                                        ?>

                                                        <?php if ($timestamp !== false): ?>

                                                            <?= date(
                                                                'M d, Y',
                                                                $timestamp
                                                            ) ?>

                                                            <small class="text-body-secondary d-block">

                                                                <?= date(
                                                                    'h:i A',
                                                                    $timestamp
                                                                ) ?>

                                                            </small>

                                                        <?php else: ?>

                                                            —

                                                        <?php endif; ?>

                                                    <?php else: ?>

                                                        —

                                                    <?php endif; ?>

                                                </td>

                                                <!-- Branch -->
                                                <td>

                                                    <?= htmlspecialchars(
                                                        $inspection['branch'] ?? '—'
                                                    ) ?>

                                                </td>

                                                <!-- Status -->
                                                <td>

                                                    <span
                                                        class="badge rounded-pill <?= $statusClass ?> px-3 py-2 status-badge">

                                                        <i
                                                            class="bi <?= $statusIcon ?> me-1">
                                                        </i>

                                                        <?= htmlspecialchars(
                                                            $statusLabel
                                                        ) ?>

                                                    </span>

                                                </td>

                                                <!-- Actions -->
                                                <td class="text-end pe-4">

                                                    <div class="inspection-actions">

                                                        <button
                                                            type="button"
                                                            class="btn btn-sm btn-primary"
                                                            onclick="viewInspection(<?= (int) $inspection['inspect_id'] ?>)">

                                                            <i class="bi bi-eye me-1"></i>

                                                            View

                                                        </button>

                                                        <?php if ($status === 'Pending'): ?>

                                                            <!-- Approve -->
                                                            <button
                                                                type="button"
                                                                class="btn btn-sm btn-success"
                                                                onclick="approveInspection(<?= (int) $inspection['inspect_id'] ?>)">

                                                                <i class="bi bi-check-lg me-1"></i>

                                                                Approve

                                                            </button>

                                                            <!-- Reject -->
                                                            <button
                                                                type="button"
                                                                class="btn btn-sm btn-danger"
                                                                onclick="rejectInspection(<?= (int) $inspection['inspect_id'] ?>)">

                                                                <i class="bi bi-x-lg me-1"></i>

                                                                Reject

                                                            </button>

                                                        <?php endif; ?>

                                                    </div>

                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                        <!-- No Search Result -->
                                        <tr
                                            id="noSearchResult"
                                            style="display: none;">

                                            <td
                                                colspan="6"
                                                class="text-center py-5">

                                                <div class="text-body-secondary inspection-empty-state">

                                                    <div>

                                                        <i
                                                            class="bi bi-search fs-1 d-block mb-2">
                                                        </i>

                                                        No inspection records found.

                                                    </div>

                                                </div>

                                            </td>

                                        </tr>

                                    <?php else: ?>

                                        <!-- No Database Records -->

                                        <tr>

                                            <td
                                                colspan="6"
                                                class="text-center py-5">

                                                <div class="text-body-secondary inspection-empty-state">

                                                    <div>

                                                        <i
                                                            class="bi bi-inbox fs-1 d-block mb-2">
                                                        </i>

                                                        No inspection records found.

                                                    </div>

                                                </div>

                                            </td>

                                        </tr>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                    <!-- PAGINATION FOOTER -->
                    <div
                        class="position-fixed bottom-0 start-0 end-0 bg-body border-top shadow-sm py-2 inspection-pagination"
                        style="z-index: 1020;">

                        <div class="container-fluid px-3 px-md-4">

                            <div
                                class="inspection-pagination-inner d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">

                                <!-- Showing -->
                                <div class="text-body-secondary small text-center text-md-start">

                                    Showing

                                    <strong>
                                        <?= $totalRecords > 0 ? $offset + 1 : 0 ?>
                                    </strong>

                                    -

                                    <strong>
                                        <?= min($offset + $limit, $totalRecords) ?>
                                    </strong>

                                    of

                                    <strong>
                                        <?= $totalRecords ?>
                                    </strong>

                                    inspections

                                </div>

                                <!-- Pagination -->
                                <?php if ($totalPages > 1): ?>

                                    <nav aria-label="Inspection pagination">

                                        <ul class="pagination pagination-sm mb-0">

                                            <!-- Previous -->
                                            <li
                                                class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">

                                                <a
                                                    class="page-link"
                                                    href="inspection-rejected.php?page=<?= max(1, $page - 1) ?><?= !empty($date) ? '&date=' . urlencode($date) : '' ?>"
                                                    aria-label="Previous">

                                                    <i class="bi bi-chevron-left"></i>

                                                    <span class="d-none d-lg-inline ms-1">
                                                        Previous
                                                    </span>

                                                </a>

                                            </li>

                                            <!-- Page Numbers -->
                                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>

                                                <li
                                                    class="page-item d-none d-md-block <?= ($i == $page) ? 'active' : '' ?>">

                                                    <a
                                                        class="page-link"
                                                        href="inspection-rejected.php?page=<?= $i ?><?= !empty($date) ? '&date=' . urlencode($date) : '' ?>">

                                                        <?= $i ?>

                                                    </a>

                                                </li>

                                            <?php endfor; ?>

                                            <!-- Mobile Current Page -->
                                            <li class="page-item d-md-none active">

                                                <span class="page-link">

                                                    <?= $page ?> / <?= $totalPages ?>

                                                </span>

                                            </li>

                                            <!-- Next -->
                                            <li
                                                class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">

                                                <a
                                                    class="page-link"
                                                    href="inspection-rejected.php?page=<?= min($totalPages, $page + 1) ?><?= !empty($date) ? '&date=' . urlencode($date) : '' ?>"
                                                    aria-label="Next">

                                                    <span class="d-none d-lg-inline me-1">
                                                        Next
                                                    </span>

                                                    <i class="bi bi-chevron-right"></i>

                                                </a>

                                            </li>

                                        </ul>

                                    </nav>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <?php include './partials/view-inspection-modal.php'; ?>

    <!-- CoreUI and necessary plugins -->
    <script src="vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>

    <script src="./js/inspection-approvals.js"></script>

    <script src="vendors/simplebar/js/simplebar.min.js"></script>

    <?php include_once 'notification/session_timeout.php'; ?>

</body>

</html>