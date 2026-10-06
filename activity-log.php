<?php

require_once 'backend/authentication/SessionChecker.php';
require_once 'backend/controller/ActivityLogController.php';

$limit = 10;

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$date = isset($_GET['date']) ? $_GET['date'] : '';

$offset = ($page - 1) * $limit;

$activityLogs = getActivityLogsPaginated(
    $limit,
    $offset,
    '',
    $date
);

$totalRecords = getActivityLogsTotal(
    '',
    $date
);

$totalPages = (int) ceil($totalRecords / $limit);

?>

<!DOCTYPE html>
<html lang="en">

<?php include 'partials/header.php'; ?>

<body>

<?php include 'partials/side-nav.php'; ?>

<div class="wrapper d-flex flex-column min-vh-100">

    <?php include 'partials/header-nav.php'; ?>

    <style>
        .activity-page {
            background: #f8fafc;
        }

        .activity-header {
            background: #ffffff;
            border: 1px solid #e9eef3;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
        }

        .activity-header-icon {
            width: 58px;
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            background: #edf8f4;
            color: #198754;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .activity-title {
            color: #172033;
            font-size: 1.45rem;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .activity-subtitle {
            color: #718096;
            margin-bottom: 0;
            font-size: 0.92rem;
        }

        .activity-date {
            min-width: 205px;
            height: 48px;
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 0 16px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 13px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);
        }

        .activity-date i {
            color: #0d6efd;
        }

        .activity-date input {
            width: 100%;
            cursor: pointer;
        }

        .activity-total {
            height: 48px;
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 0 16px;
            background: #f8fafc;
            border: 1px solid #edf0f3;
            border-radius: 13px;
            color: #64748b;
            white-space: nowrap;
        }

        .activity-total i {
            color: #198754;
        }

        .activity-card {
            position: relative;
            display: flex;
            align-items: center;
            gap: 20px;
            min-height: 132px;
            padding: 20px 24px;
            margin-bottom: 14px;
            background: #ffffff;
            border: 1px solid #e3e8ee;
            border-radius: 17px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, 0.035);
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }

        .activity-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 7px 20px rgba(15, 23, 42, 0.07);
        }

        .activity-icon {
            width: 58px;
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            flex-shrink: 0;
            font-size: 1.25rem;
        }

        .activity-content {
            min-width: 0;
            flex: 1;
        }

        .activity-title-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 4px;
        }

        .activity-item-title {
            color: #172033;
            font-size: 1rem;
            font-weight: 650;
        }

        .activity-badge {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 5px 9px;
            border-radius: 999px;
        }

        .activity-description {
            color: #718096;
            font-size: 0.88rem;
            margin-bottom: 8px;
        }

        .activity-user {
            color: #64748b;
            font-size: 0.82rem;
        }

        .activity-time {
            min-width: 205px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            color: #64748b;
            font-size: 0.82rem;
            white-space: nowrap;
        }

        .activity-time i {
            font-size: 0.95rem;
        }

        .activity-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-top: 22px;
            margin-bottom: 24px;
            padding: 13px 16px;
            background: #ffffff;
            border: 1px solid #e7ebef;
            border-radius: 14px;
        }

        .activity-showing {
            color: #64748b;
            font-size: 0.82rem;
        }

        .activity-showing strong {
            color: #334155;
        }

        .activity-pagination {
            margin: 0;
            gap: 6px;
        }

        .activity-pagination .page-item {
            margin: 0;
        }

        .activity-pagination .page-link {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border: 1px solid #e2e8f0;
            border-radius: 9px !important;
            color: #475569;
            background: #ffffff;
            box-shadow: none;
            font-size: 0.85rem;
        }

        .activity-pagination .page-link:hover {
            background: #f1f5f9;
            color: #0d6efd;
            border-color: #d9e2ec;
        }

        .activity-pagination .page-item.active .page-link {
            background: #0d6efd;
            border-color: #0d6efd;
            color: #ffffff;
        }

        .activity-pagination .page-item.disabled .page-link {
            color: #a0aec0;
            background: #f8fafc;
            border-color: #edf0f3;
        }

        .activity-empty {
            padding: 55px 20px;
            text-align: center;
            color: #94a3b8;
            background: #ffffff;
            border: 1px solid #e3e8ee;
            border-radius: 17px;
        }

        .activity-empty i {
            display: block;
            margin-bottom: 8px;
            font-size: 2rem;
        }

        .activity-page {
            min-width: 0;
            overflow-x: hidden;
        }

        .activity-page .container-lg {
            width: 100%;
            max-width: 100%;
        }

        @media (max-width: 767.98px) {
            .activity-page .container-lg {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }

            .activity-header {
                padding: 16px;
                border-radius: 15px;
                margin-bottom: 16px !important;
            }

            .activity-header > .d-flex {
                gap: 18px !important;
            }

            .activity-header-icon {
                width: 46px;
                height: 46px;
                border-radius: 13px;
                font-size: 1.2rem;
            }

            .activity-title {
                font-size: 1.2rem;
            }

            .activity-subtitle {
                font-size: 0.8rem;
                line-height: 1.4;
            }

            .activity-header-tools {
                width: 100%;
                gap: 8px !important;
            }

            .activity-header-tools form {
                width: 100%;
            }

            .activity-date,
            .activity-total {
                width: 100%;
                min-width: 0;
                height: 44px;
            }

            .activity-date {
                padding: 0 13px;
            }

            .activity-date input {
                min-width: 0;
                font-size: 0.82rem;
            }

            .activity-total {
                justify-content: flex-start;
                font-size: 0.82rem;
            }

            .activity-card {
                display: grid;
                grid-template-columns: 48px minmax(0, 1fr);
                align-items: start;
                gap: 0 12px;
                min-height: 0;
                padding: 16px;
                margin-bottom: 10px;
                border-radius: 15px;
            }

            .activity-icon {
                grid-column: 1;
                grid-row: 1;
                width: 48px;
                height: 48px;
                font-size: 1.05rem;
            }

            .activity-content {
                grid-column: 2;
                grid-row: 1;
                width: 100%;
                min-width: 0;
            }

            .activity-title-row {
                gap: 5px 7px;
                margin-bottom: 5px;
            }

            .activity-item-title {
                font-size: 0.9rem;
                line-height: 1.3;
            }

            .activity-badge {
                font-size: 0.65rem;
                padding: 4px 7px;
            }

            .activity-description {
                font-size: 0.78rem;
                line-height: 1.4;
                margin-bottom: 6px;
                overflow-wrap: anywhere;
            }

            .activity-user {
                font-size: 0.75rem;
            }

            .activity-time {
                grid-column: 2;
                grid-row: 2;
                width: 100%;
                min-width: 0;
                margin: 8px 0 0;
                padding-top: 8px;
                border-top: 1px solid #f0f2f5;
                justify-content: flex-start;
                font-size: 0.72rem;
                white-space: normal;
            }

            .activity-time span {
                overflow-wrap: anywhere;
            }

            .activity-footer {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
                margin-top: 16px;
                margin-bottom: 18px;
                padding: 12px;
                border-radius: 13px;
            }

            .activity-showing {
                text-align: center;
                font-size: 0.75rem;
            }

            .activity-footer nav {
                width: 100%;
                overflow-x: auto;
                overflow-y: hidden;
                scrollbar-width: none;
            }

            .activity-footer nav::-webkit-scrollbar {
                display: none;
            }

            .activity-pagination {
                justify-content: center;
                flex-wrap: nowrap;
                min-width: max-content;
                padding: 0 2px;
            }

            .activity-pagination .page-link {
                width: 36px;
                height: 36px;
                font-size: 0.78rem;
            }

            .activity-empty {
                padding: 40px 15px;
                border-radius: 15px;
            }
        }

        @media (max-width: 380px) {
            .activity-page .container-lg {
                padding-left: 8px !important;
                padding-right: 8px !important;
            }

            .activity-header {
                padding: 14px;
            }

            .activity-card {
                grid-template-columns: 42px minmax(0, 1fr);
                gap: 0 10px;
                padding: 14px;
            }

            .activity-icon {
                width: 42px;
                height: 42px;
            }

            .activity-title {
                font-size: 1.1rem;
            }

            .activity-item-title {
                font-size: 0.85rem;
            }

            .activity-time {
                font-size: 0.68rem;
            }
        }
    </style>

    <div class="body flex-grow-1 activity-page">

        <div class="container-lg px-4 py-4">

            <!-- Activity Header -->
            <div class="activity-header mb-4">

                <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-4">

                    <div class="d-flex align-items-center gap-3">

                        <div class="activity-header-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>

                        <div>
                            <h2 class="activity-title">Activity Logs</h2>
                            <p class="activity-subtitle">
                                Track system activities, user actions, and recent fire extinguisher updates.
                            </p>
                        </div>

                    </div>

                    <div class="activity-header-tools d-flex flex-column flex-sm-row align-items-stretch gap-2">

                        <!-- Date Filter -->
                        <form method="GET" class="d-flex">
                            <div class="activity-date">
                                <i class="bi bi-calendar3"></i>

                                <input
                                    type="date"
                                    name="date"
                                    value="<?= htmlspecialchars($date) ?>"
                                    class="form-control form-control-sm border-0 shadow-none bg-transparent fw-semibold px-0"
                                    onchange="this.form.submit()"
                                >
                            </div>
                        </form>

                        <!-- Total Activities -->
                        <div class="activity-total">
                            <i class="bi bi-list-ul"></i>

                            <span>
                                <?php if (!empty($date)): ?>
                                    <?= $totalRecords ?> activities
                                <?php else: ?>
                                    <?= $totalRecords ?> total activities
                                <?php endif; ?>
                            </span>
                        </div>

                    </div>

                </div>

                <?php if (!empty($date)): ?>
                    <div class="small text-body-secondary mt-3">
                        Showing activities for
                        <strong><?= date('F d, Y', strtotime($date)) ?></strong>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Activity List -->
            <?php if ($activityLogs && mysqli_num_rows($activityLogs) > 0): ?>

                <?php while ($log = mysqli_fetch_assoc($activityLogs)): ?>

                    <?php
                    $action = strtolower($log['action']);

                    if ($action === 'add fire extinguisher') {
                        $icon = 'bi-plus-lg';
                        $color = 'success';
                        $title = 'Fire Extinguisher Added';
                    } elseif ($action === 'update fire extinguisher') {
                        $icon = 'bi-pencil';
                        $color = 'warning';
                        $title = 'Fire Extinguisher Updated';
                    } elseif ($action === 'delete fire extinguisher') {
                        $icon = 'bi-trash';
                        $color = 'danger';
                        $title = 'Fire Extinguisher Deleted';
                    } elseif ($action === 'inspect fire extinguisher') {
                        $icon = 'bi-clipboard-check';
                        $color = 'info';
                        $title = 'Inspection Completed';
                    } else {
                        $icon = 'bi-activity';
                        $color = 'secondary';
                        $title = $log['action'];
                    }
                    ?>

                    <div class="activity-card">

                        <!-- Icon -->
                        <div class="activity-icon bg-<?= $color ?> bg-opacity-10 text-<?= $color ?>">
                            <i class="bi <?= $icon ?>"></i>
                        </div>

                        <!-- Activity Content -->
                        <div class="activity-content">

                            <div class="activity-title-row">

                                <span class="activity-item-title">
                                    <?= htmlspecialchars($title) ?>
                                </span>

                                <span class="badge bg-<?= $color ?>-subtle text-<?= $color ?> activity-badge">
                                    <?= htmlspecialchars($log['action']) ?>
                                </span>

                            </div>

                            <div class="activity-description text-break">
                                <?= htmlspecialchars($log['description']) ?>
                            </div>

                            <div class="activity-user">
                                <i class="bi bi-person me-1"></i>
                                <?= htmlspecialchars($log['user_name']) ?>
                            </div>

                        </div>

                        <!-- Time -->
                        <div class="activity-time">
                            <i class="bi bi-clock"></i>
                            <span>
                                <?= date('M d, Y • h:i A', strtotime($log['created_at'])) ?>
                            </span>
                        </div>

                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="activity-empty">
                    <i class="bi bi-activity"></i>
                    No activity found.
                </div>

            <?php endif; ?>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>

                <div class="activity-footer">

                    <!-- Showing -->
                    <div class="activity-showing">
                        Showing
                        <strong><?= min($offset + 1, $totalRecords) ?></strong>
                        -
                        <strong><?= min($offset + $limit, $totalRecords) ?></strong>
                        of
                        <strong><?= $totalRecords ?></strong>
                        activities
                    </div>

                    <!-- Pagination -->
                    <nav aria-label="Activity log pagination">

                        <ul class="pagination activity-pagination">

                            <!-- Previous -->
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                <a
                                    class="page-link"
                                    href="activity-log.php?date=<?= urlencode($date) ?>&page=<?= max(1, $page - 1) ?>"
                                    aria-label="Previous"
                                >
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>

                            <?php
                            $startPage = max(1, $page - 2);
                            $endPage = min($totalPages, $page + 2);
                            ?>

                            <!-- First Page -->
                            <?php if ($startPage > 1): ?>

                                <li class="page-item">
                                    <a
                                        class="page-link"
                                        href="activity-log.php?date=<?= urlencode($date) ?>&page=1"
                                    >
                                        1
                                    </a>
                                </li>

                                <?php if ($startPage > 2): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>

                            <?php endif; ?>

                            <!-- Page Numbers -->
                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>

                                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">

                                    <a
                                        class="page-link"
                                        href="activity-log.php?date=<?= urlencode($date) ?>&page=<?= $i ?>"
                                    >
                                        <?= $i ?>
                                    </a>

                                </li>

                            <?php endfor; ?>

                            <!-- Last Page -->
                            <?php if ($endPage < $totalPages): ?>

                                <?php if ($endPage < $totalPages - 1): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>

                                <li class="page-item">
                                    <a
                                        class="page-link"
                                        href="activity-log.php?date=<?= urlencode($date) ?>&page=<?= $totalPages ?>"
                                    >
                                        <?= $totalPages ?>
                                    </a>
                                </li>

                            <?php endif; ?>

                            <!-- Next -->
                            <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                                <a
                                    class="page-link"
                                    href="activity-log.php?date=<?= urlencode($date) ?>&page=<?= min($totalPages, $page + 1) ?>"
                                    aria-label="Next"
                                >
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>

                        </ul>

                    </nav>

                </div>

            <?php endif; ?>

        </div>

    </div>

    <?php include 'partials/footer.php'; ?>

</div>

<!-- CoreUI and necessary plugins -->
<script src="vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
<script src="vendors/simplebar/js/simplebar.min.js"></script>

<script>
    const header = document.querySelector("header.header");

    document.addEventListener("scroll", () => {
        if (header) {
            header.classList.toggle(
                "shadow-sm",
                document.documentElement.scrollTop > 0
            );
        }
    });
</script>

<?php include_once 'notification/session_timeout.php'; ?>

</body>
</html>
