<?php

require_once 'backend/authentication/SessionChecker.php';
require_once 'backend/controller/RejectedInspectionController.php';

$perPage = 6;

$currentPage = isset($_GET['page'])
    ? max(1, (int) $_GET['page'])
    : 1;

$totalRejected = count($rejectedInspections);

$totalPages = max(
    1,
    (int) ceil($totalRejected / $perPage)
);

if ($currentPage > $totalPages) {
    $currentPage = $totalPages;
}

$offset = ($currentPage - 1) * $perPage;

$paginatedInspections = array_slice(
    $rejectedInspections,
    $offset,
    $perPage
);

?>

<!DOCTYPE html>
<html lang="en">

<?php include 'partials/header.php'; ?>

<body>

    <?php include 'partials/side-nav.php'; ?>

    <div class="wrapper d-flex flex-column min-vh-100">

        <?php include 'partials/header-nav.php'; ?>

        <main class="body flex-grow-1 px-3 py-4">

            <div class="container-fluid">

                <!-- FILTER -->
                <form method="GET" class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-3">

                        <div class="row g-2">

                            <div class="col-12 col-lg">

                                <input
                                    type="text"
                                    name="search"
                                    class="form-control"
                                    placeholder="Search code, location, or reason..."
                                    value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"
                                    style="font-size: 12px;">

                            </div>

                            <div class="col-12 col-sm-6 col-lg-auto">

                                <select
                                    name="date"
                                    class="form-select"
                                    style="font-size: 12px;">

                                    <?php foreach (
                                        [
                                            'all' => 'All Inspection Dates',
                                            'today' => 'Today',
                                            'yesterday' => 'Yesterday',
                                            '7' => 'Last 7 Days',
                                            '30' => 'Last 30 Days'
                                        ] as $value => $label
                                    ): ?>

                                        <option
                                            value="<?= $value ?>"
                                            <?= $dateFilter === $value ? 'selected' : '' ?>>
                                            <?= $label ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                            <div class="col-6 col-sm-auto">

                                <button
                                    type="submit"
                                    class="btn btn-primary w-100"
                                    style="font-size: 12px;">
                                    Filter
                                </button>

                            </div>

                            <div class="col-6 col-sm-auto">

                                <a
                                    href="rejected-inspection.php"
                                    class="btn btn-light border w-100"
                                    style="font-size: 12px;">
                                    Clear
                                </a>

                            </div>

                        </div>

                    </div>

                </form>

                <!-- MAIN CARD -->
                <div class="card border-0 shadow-sm">

                    <!-- CARD HEADER -->
                    <div class="card-header bg-body border-bottom py-3">

                        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">

                            <div>

                                <h6
                                    class="fw-semibold mb-1"
                                    style="font-size: 13px;">
                                    Rejected Inspections
                                </h6>

                                <small
                                    class="text-body-secondary"
                                    style="font-size: 11px;">
                                    Inspections that require correction or re-inspection.
                                </small>

                            </div>

                            <span
                                class="badge bg-danger-subtle text-danger"
                                style="font-size: 11px;">
                                <?= $totalRejected ?> Rejected
                            </span>

                        </div>

                    </div>

                    <!-- DESKTOP TABLE -->
                    <div
                        class="table-responsive d-none d-md-block"
                        style="min-height: 390px;">

                        <table
                            class="table table-hover align-middle mb-0"
                            id="rejectedInspectionTable">

                            <thead class="table-light">

                                <tr>

                                    <th
                                        class="px-3"
                                        style="font-size: 12px;">
                                        Date Inspected
                                    </th>

                                    <th style="font-size: 12px;">
                                        Extinguisher Code
                                    </th>

                                    <th style="font-size: 12px;">
                                        Location
                                    </th>

                                    <th style="font-size: 12px;">
                                        Rejection Reason
                                    </th>

                                    <th style="font-size: 12px;">
                                        Rejection Date
                                    </th>

                                    <th
                                        class="text-end px-3"
                                        style="font-size: 12px;">
                                        Action
                                    </th>

                                </tr>

                            </thead>

                            <tbody id="rejectedInspectionTableBody">

                                <?php foreach ($paginatedInspections as $inspection): ?>

                                    <?php
                                    $inspectionDate = strtotime(
                                        $inspection['date_inspected']
                                    );

                                    $reason = $inspection['reject_reason']
                                        ?: 'No reason provided.';
                                    ?>

                                    <tr>

                                        <td class="px-3">

                                            <div
                                                class="fw-semibold"
                                                style="font-size: 12px;">
                                                <?= date(
                                                    'M d, Y',
                                                    $inspectionDate
                                                ) ?>
                                            </div>

                                            <small class="text-body-secondary">
                                                <?= date(
                                                    'h:i A',
                                                    $inspectionDate
                                                ) ?>
                                            </small>

                                        </td>

                                        <td
                                            class="fw-semibold"
                                            style="font-size: 12px;">

                                            <?= htmlspecialchars(
                                                $inspection['extinguisher_code'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </td>

                                        <td style="font-size: 12px;">

                                            <?= htmlspecialchars(
                                                $inspection['location'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </td>

                                        <td style="font-size: 12px;">

                                            <span
                                                class="badge bg-danger-subtle text-danger fw-normal text-wrap text-start">

                                                <?= htmlspecialchars(
                                                    $reason,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </span>

                                        </td>

                                        <td style="font-size: 12px;">

                                            <span class="text-body-secondary">
                                                Not recorded
                                            </span>

                                        </td>

                                        <td class="text-end px-3">

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rejectionModal<?= (int) $inspection['inspect_id'] ?>"
                                                style="font-size: 12px;">
                                                View
                                            </button>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                    <!-- MOBILE LIST -->
                    <div
                        class="d-md-none"
                        id="mobileRejectedInspectionList">

                        <?php foreach ($paginatedInspections as $inspection): ?>

                            <?php
                            $inspectionDate = strtotime(
                                $inspection['date_inspected']
                            );

                            $reason = $inspection['reject_reason']
                                ?: 'No reason provided.';
                            ?>

                            <div class="border-bottom p-3">

                                <div
                                    class="d-flex justify-content-between align-items-start gap-2 mb-3">

                                    <div>

                                        <div
                                            class="fw-semibold"
                                            style="font-size: 13px;">

                                            <?= htmlspecialchars(
                                                $inspection['extinguisher_code'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </div>

                                        <small class="text-body-secondary">

                                            <?= date(
                                                'M d, Y h:i A',
                                                $inspectionDate
                                            ) ?>

                                        </small>

                                    </div>

                                    <span class="badge bg-danger">
                                        Rejected
                                    </span>

                                </div>

                                <div class="mb-3">

                                    <small
                                        class="text-body-secondary d-block mb-1">
                                        LOCATION
                                    </small>

                                    <div style="font-size: 12px;">

                                        <?= htmlspecialchars(
                                            $inspection['location'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </div>

                                </div>

                                <div
                                    class="d-flex align-items-center justify-content-between gap-2">

                                    <span
                                        class="badge bg-danger-subtle text-danger fw-normal text-wrap text-start">

                                        <?= htmlspecialchars(
                                            $reason,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </span>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rejectionModal<?= (int) $inspection['inspect_id'] ?>"
                                        style="font-size: 12px;">
                                        View
                                    </button>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                    <!-- EMPTY STATE -->
                    <?php if (empty($rejectedInspections)): ?>

                        <div
                            id="noRejectedInspections"
                            class="text-center p-5">

                            <div
                                class="text-body-secondary mb-2"
                                style="font-size: 30px;">
                                ✓
                            </div>

                            <div
                                class="fw-semibold mb-1"
                                style="font-size: 13px;">
                                No rejected inspections found
                            </div>

                            <div
                                class="text-body-secondary"
                                style="font-size: 12px;">
                                Try changing your search or filter.
                            </div>

                        </div>

                    <?php endif; ?>

                    <!-- PAGINATION -->
                    <div class="card-footer bg-body border-top">

                        <div
                            class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">

                            <small
                                class="text-body-secondary"
                                style="font-size: 12px;">

                                <?php if ($totalRejected > 0): ?>

                                    Showing
                                    <?= $offset + 1 ?>
                                    to
                                    <?= min(
                                        $offset + $perPage,
                                        $totalRejected
                                    ) ?>
                                    of
                                    <?= $totalRejected ?>
                                    entries

                                <?php else: ?>

                                    Showing 0 of 0 entries

                                <?php endif; ?>

                            </small>

                            <?php if ($totalPages > 1): ?>

                                <?php
                                $currentPageUrl = basename(
                                    $_SERVER['PHP_SELF']
                                );
                                ?>

                                <nav
                                    aria-label="Rejected inspection pagination">

                                    <ul class="pagination pagination-sm mb-0">

                                        <!-- PREVIOUS -->

                                        <li
                                            class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">

                                            <?php if ($currentPage > 1): ?>

                                                <a
                                                    class="page-link"
                                                    href="<?= htmlspecialchars($currentPageUrl, ENT_QUOTES, 'UTF-8') ?>?<?= http_build_query([
                                                                                                                            'page' => $currentPage - 1,
                                                                                                                            'search' => $search,
                                                                                                                            'date' => $dateFilter
                                                                                                                        ]) ?>">
                                                    Previous
                                                </a>

                                            <?php else: ?>

                                                <span class="page-link">
                                                    Previous
                                                </span>

                                            <?php endif; ?>

                                        </li>

                                        <!-- PAGE NUMBERS -->

                                        <?php for (
                                            $page = 1;
                                            $page <= $totalPages;
                                            $page++
                                        ): ?>

                                            <li
                                                class="page-item <?= $page === $currentPage ? 'active' : '' ?>">

                                                <a
                                                    class="page-link"
                                                    href="<?= htmlspecialchars($currentPageUrl, ENT_QUOTES, 'UTF-8') ?>?<?= http_build_query([
                                                                                                                            'page' => $page,
                                                                                                                            'search' => $search,
                                                                                                                            'date' => $dateFilter
                                                                                                                        ]) ?>">

                                                    <?= $page ?>

                                                </a>

                                            </li>

                                        <?php endfor; ?>

                                        <!-- NEXT -->

                                        <li
                                            class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">

                                            <?php if ($currentPage < $totalPages): ?>

                                                <a
                                                    class="page-link"
                                                    href="<?= htmlspecialchars($currentPageUrl, ENT_QUOTES, 'UTF-8') ?>?<?= http_build_query([
                                                                                                                            'page' => $currentPage + 1,
                                                                                                                            'search' => $search,
                                                                                                                            'date' => $dateFilter
                                                                                                                        ]) ?>">
                                                    Next
                                                </a>

                                            <?php else: ?>

                                                <span class="page-link">
                                                    Next
                                                </span>

                                            <?php endif; ?>

                                        </li>

                                    </ul>

                                </nav>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </div>

        </main>

        <!-- =========================================================
             REJECTION MODALS
             ========================================================= -->

        <?php foreach ($paginatedInspections as $inspection): ?>

            <?php
            $inspectionDate = strtotime(
                $inspection['date_inspected']
            );

            $reason = $inspection['reject_reason']
                ?: 'No reason provided.';

            $rejectedBy = $inspection['rejected_by']
                ?? 'Not recorded';
            ?>

            <div
                class="modal fade"
                id="rejectionModal<?= (int) $inspection['inspect_id'] ?>"
                tabindex="-1"
                aria-labelledby="rejectionModalLabel<?= (int) $inspection['inspect_id'] ?>"
                aria-hidden="true">

                <div
                    class="modal-dialog modal-dialog-centered modal-lg">

                    <div class="modal-content border-0 shadow">

                        <!-- MODAL HEADER -->

                        <div class="modal-header border-bottom">

                            <div>

                                <h5
                                    class="modal-title fw-semibold mb-1"
                                    id="rejectionModalLabel<?= (int) $inspection['inspect_id'] ?>"
                                    style="font-size: 16px;">

                                    Rejection Details

                                </h5>

                                <small
                                    class="text-body-secondary"
                                    style="font-size: 11px;">

                                    Review the rejection and inspection information.

                                </small>

                            </div>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                            </button>

                        </div>

                        <!-- MODAL BODY -->

                        <div class="modal-body">

                            <!-- REJECTION SUMMARY -->

                            <div class="alert alert-danger mb-3">

                                <div class="d-flex align-items-center gap-3">

                                    <div class="fs-4">
                                        ✕
                                    </div>

                                    <div class="flex-grow-1">

                                        <div class="text-body-secondary" style="font-size: 10px;">
                                            Rejected by
                                        </div>

                                        <div
                                            class="fw-semibold"
                                            style="font-size: 12px;">

                                            <?= htmlspecialchars(
                                                $rejectedBy,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </div>

                                    </div>

                                    <div class="text-end">

                                        <div
                                            class="text-body-secondary"
                                            style="font-size: 10px;">
                                            Inspection Date
                                        </div>

                                        <div
                                            class="fw-semibold"
                                            style="font-size: 12px;">

                                            <?= date(
                                                'M d, Y',
                                                $inspectionDate
                                            ) ?>

                                        </div>

                                        <div
                                            class="text-body-secondary"
                                            style="font-size: 10px;">

                                            <?= date(
                                                'h:i A',
                                                $inspectionDate
                                            ) ?>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- REJECTION REASON -->

                            <div class="card border mb-3">

                                <div class="card-body">

                                    <div
                                        class="d-flex align-items-center gap-2 mb-2">

                                        <span class="text-danger">
                                            💬
                                        </span>

                                        <span
                                            class="fw-semibold"
                                            style="font-size: 12px;">
                                            Rejection Reason
                                        </span>

                                    </div>

                                    <div
                                        class="bg-danger-subtle rounded-3 p-3">

                                        <p
                                            class="mb-0"
                                            style="font-size: 12px;">

                                            <?= htmlspecialchars(
                                                $reason,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </p>

                                    </div>

                                </div>

                            </div>

                            <!-- INSPECTION INFORMATION -->

                            <div class="card border mb-3">

                                <div class="card-header bg-body">

                                    <div
                                        class="d-flex align-items-center gap-2">

                                        <span class="text-primary">
                                            ▣
                                        </span>

                                        <span
                                            class="fw-semibold"
                                            style="font-size: 12px;">
                                            Inspection Information
                                        </span>

                                    </div>

                                </div>

                                <div class="table-responsive">

                                    <table
                                        class="table table-sm mb-0">

                                        <tbody>

                                            <tr>

                                                <td
                                                    class="text-body-secondary"
                                                    style="font-size: 12px;">
                                                    Extinguisher Code
                                                </td>

                                                <td
                                                    class="fw-semibold"
                                                    style="font-size: 12px;">

                                                    <?= htmlspecialchars(
                                                        $inspection['extinguisher_code'],
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>

                                                </td>

                                            </tr>

                                            <tr>

                                                <td
                                                    class="text-body-secondary"
                                                    style="font-size: 12px;">
                                                    Location
                                                </td>

                                                <td style="font-size: 12px;">

                                                    <?= htmlspecialchars(
                                                        $inspection['location'],
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>

                                                </td>

                                            </tr>

                                            <tr>

                                                <td
                                                    class="text-body-secondary"
                                                    style="font-size: 12px;">
                                                    Type
                                                </td>

                                                <td style="font-size: 12px;">

                                                    <?= htmlspecialchars(
                                                        $inspection['type'],
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>

                                                </td>

                                            </tr>

                                            <tr>

                                                <td
                                                    class="text-body-secondary"
                                                    style="font-size: 12px;">
                                                    Capacity
                                                </td>

                                                <td style="font-size: 12px;">

                                                    <?= htmlspecialchars(
                                                        $inspection['capacity'],
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>

                                                </td>

                                            </tr>

                                            <tr>

                                                <td
                                                    class="text-body-secondary"
                                                    style="font-size: 12px;">
                                                    Date Inspected
                                                </td>

                                                <td style="font-size: 12px;">

                                                    <?= date(
                                                        'M d, Y h:i A',
                                                        $inspectionDate
                                                    ) ?>

                                                </td>

                                            </tr>

                                            <tr>

                                                <td
                                                    class="text-body-secondary"
                                                    style="font-size: 12px;">
                                                    Action Taken
                                                </td>

                                                <td style="font-size: 12px;">

                                                    <?= !empty($inspection['action_taken'])
                                                        ? htmlspecialchars(
                                                            $inspection['action_taken'],
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        )
                                                        : 'Not recorded'
                                                    ?>

                                                </td>

                                            </tr>

                                            <tr>

                                                <td
                                                    class="text-body-secondary"
                                                    style="font-size: 12px;">
                                                    Target Date of Implementation
                                                </td>

                                                <td style="font-size: 12px;">

                                                    <?= !empty($inspection['target_date_of_implementation'])
                                                        ? date(
                                                            'M d, Y',
                                                            strtotime($inspection['target_date_of_implementation'])
                                                        )
                                                        : 'Not recorded'
                                                    ?>

                                                </td>

                                            </tr>

                                            <tr>

                                                <td class="text-body-secondary" style="font-size: 12px;">
                                                    Rejection Date
                                                </td>

                                                <td style="font-size: 12px;">

                                                    <?= !empty($inspection['rejection_date'])
                                                        ? date(
                                                            'M d, Y',
                                                            strtotime($inspection['rejection_date'])
                                                        )
                                                        : 'Not recorded'
                                                    ?>

                                                </td>

                                            </tr>
                                            <tr>

                                                <td
                                                    class="text-body-secondary"
                                                    style="font-size: 12px;">
                                                    Status
                                                </td>

                                                <td>

                                                    <span
                                                        class="badge bg-danger"
                                                        style="font-size: 10px;">
                                                        Rejected
                                                    </span>

                                                </td>

                                            </tr>

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                        <!-- MODAL FOOTER -->

                        <div class="modal-footer border-top">

                            <button
                                type="button"
                                class="btn btn-light border"
                                data-bs-dismiss="modal"
                                style="font-size: 12px;">
                                Close
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

        <?php include 'partials/footer.php'; ?>

    </div>

    <!-- COREUI / BOOTSTRAP -->

    <script src="vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>

    <script src="vendors/simplebar/js/simplebar.min.js"></script>

    <?php include_once 'notification/session_timeout.php'; ?>

</body>

</html>