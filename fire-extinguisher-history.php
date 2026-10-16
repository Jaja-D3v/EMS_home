<?php
require_once 'backend/authentication/SessionChecker.php';
include 'backend/controller/FireExtinguisherHistoryController.php';


$history = getFireExtinguisherHistoryController();

$details = $history['details'];
$timeline = $history['timeline'];
$errors = $history['errors'];

$code = $history['code'];
$dateFrom = $history['dateFrom'];
$dateTo = $history['dateTo'];

?>


<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
* @version v5.5.0
* @link https://coreui.io/product/free-bootstrap-admin-template/
* Copyright (c) 2026 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://github.com/coreui/coreui-free-bootstrap-admin-template/blob/main/LICENSE)
-->

<html lang="en">
<?php include 'partials/header.php'; ?>

<body>
    <?php include 'partials/side-nav.php'; ?>
    <div class="wrapper d-flex flex-column min-vh-100">
        <?php include 'partials/header-nav.php'; ?>

        <!-- CONTENT HERE -->

        <style>
            body {
                background-color: #f1f5f9;
                color: #17243b;
                font-size: 14px;
            }

            .history-card {
                background: #fff;
                border: 1px solid #e5eaf1;
                border-radius: 7px;
                height: 100%;
            }

            .section-title {
                font-size: 15px;
                font-weight: 700;
                margin-bottom: 16px;
            }

            .form-label {
                font-size: 12px;
                font-weight: 600;
                margin-bottom: 5px;
            }

            .form-control,
            .form-select {
                font-size: 13px;
                border-color: #d6deea;
            }

            .form-control,
            .form-select,
            .btn {
                min-height: 36px;
            }

            .btn-primary {
                background-color: #0875df;
                border-color: #0875df;
            }

            .extinguisher-image {
                width: 100%;
                height: 205px;
                object-fit: contain;
                background-color: #f1f3f5;
                border-radius: 5px;
                padding: 10px;
            }

            .equipment-code {
                font-size: 23px;
                font-weight: 700;
            }

            .status-badge {
                font-size: 11px;
                padding: 5px 10px;
            }

            .detail-label {
                color: #536987;
                font-weight: 600;
            }

            .detail-value {
                color: #263b59;
            }

            /* Activity timeline */
            .timeline {
                position: relative;
                padding-left: 29px;
            }

            .timeline::before {
                content: "";
                position: absolute;
                left: 7px;
                top: 14px;
                bottom: 18px;
                width: 2px;
                background: #dce4ee;
            }

            .timeline-item {
                position: relative;
                display: flex;
                align-items: flex-start;
                gap: 12px;
                padding-bottom: 14px;
            }

            .timeline-item:last-child {
                padding-bottom: 0;
            }

            .timeline-dot {
                position: absolute;
                left: -27px;
                top: 13px;
                width: 10px;
                height: 10px;
                border-radius: 50%;
                border: 2px solid white;
                z-index: 1;
            }

            .timeline-icon {
                width: 40px;
                height: 40px;
                min-width: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                font-size: 18px;
                font-weight: 600;
            }

            .timeline-date {
                color: #536987;
                font-size: 12px;
            }

            .timeline-heading {
                font-size: 13px;
                font-weight: 700;
                margin-bottom: 2px;
            }

            .timeline-description {
                font-size: 12px;
                color: #536987;
                line-height: 1.4;
            }

            .timeline-content {
                flex: 1;
                min-width: 0;
            }

            .timeline-status {
                white-space: nowrap;
                font-size: 11px;
                font-weight: 600;
                padding: 5px 8px;
                border-radius: 20px;
            }

            .bg-soft-success {
                background: #d9f7e5;
                color: #128044;
            }

            .bg-soft-danger {
                background: #ffe0e3;
                color: #d92332;
            }

            .bg-soft-primary {
                background: #dceaff;
                color: #0965df;
            }

            .bg-soft-warning {
                background: #fff0cc;
                color: #bd7900;
            }

            .bg-success-dot {
                background: #20ad65;
            }

            .bg-danger-dot {
                background: #ed3344;
            }

            .bg-primary-dot {
                background: #0875ef;
            }

            .bg-warning-dot {
                background: #ffb400;
            }

            .icon-success {
                background: #20ad65;
            }

            .icon-danger {
                background: #ed3344;
            }

            .icon-primary {
                background: #0875ef;
            }

            .icon-warning {
                background: #ffb000;
            }

            @media (max-width: 767.98px) {
                .extinguisher-image {
                    height: 180px;
                }

                .equipment-code {
                    font-size: 21px;
                }

                .timeline-item {
                    gap: 8px;
                }

                .timeline-icon {
                    width: 34px;
                    height: 34px;
                    min-width: 34px;
                    font-size: 15px;
                }

                .timeline-status {
                    font-size: 10px;
                    padding: 4px 6px;
                }
            }
        </style>

        <div class="container-fluid p-3 p-lg-4">

            <!-- FILTER -->
            <div class="history-card p-3 mb-3">

                <h6 class="section-title mb-3">
                    <span class="me-2">&#9776;</span> Filter
                </h6>

                <form method="GET" action="">
                    <div class="row g-3 align-items-end">

                        <div class="col-12 col-md-4">
                            <label for="extinguisher_code" class="form-label">
                                Fire Extinguisher Code
                            </label>

                            <select name="extinguisher_code"
                                id="extinguisher_code"
                                class="form-select">

                                <option value="">Select Fire Extinguisher</option>

                                <?php foreach ($history['extinguisherCodes'] as $item): ?>
                                    <option
                                        value="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>"
                                        <?= $code === $item ? 'selected' : '' ?>>

                                        <?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>

                        <div class="col-6 col-md-2">
                            <label for="date_from" class="form-label">Date From</label>
                            <input type="date"
                                class="form-control"
                                name="date_from"
                                value="<?= htmlspecialchars($dateFrom, ENT_QUOTES, 'UTF-8') ?>">
                        </div>

                        <div class="col-6 col-md-2">
                            <label for="date_to" class="form-label">Date To</label>
                            <input type="date"
                                class="form-control"
                                name="date_to"
                                value="<?= htmlspecialchars($dateTo, ENT_QUOTES, 'UTF-8') ?>">
                        </div>

                        <div class="col-8 col-md-2 d-grid">
                            <button type="submit" class="btn btn-primary">
                                <span class="me-1">&#9906;</span> View History
                            </button>
                        </div>

                        <div class="col-4 col-md-2 d-grid">
                            <a href="fire-extinguisher-history.php" class="btn btn-outline-secondary">
                                Reset
                            </a>
                        </div>

                    </div>
                </form>
            </div>

            <!-- DETAILS AND TIMELINE -->
            <div class="row g-3">

                <!-- FIRE EXTINGUISHER DETAILS -->
                <div class="col-12 col-lg-6">

                    <div class="history-card p-3 p-lg-4">

                        <h6 class="section-title">
                            <span class="me-2">&#9776;</span>
                            Fire Extinguisher Details
                        </h6>

                        <div class="row g-3 align-items-start">

                            <div class="col-5 col-sm-4">
                                <!-- Palitan ang src sa actual image ng extinguisher -->
                                <img
                                    src="assets/img/abc-fire-extinguisher.png"
                                    alt="Fire Extinguisher"
                                    class="extinguisher-image">
                            </div>

                            <div class="col-7 col-sm-8">

                                <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                                    <span class="equipment-code">
                                        <?= htmlspecialchars($details['extinguisher_code'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                    <span class="badge rounded-pill bg-success status-badge">
                                        Active
                                    </span>
                                </div>



                                <div class="row g-2 small">
                                    <div class="col-6 detail-label">Type / Class</div>
                                    <div class="col-6 detail-value"><?= htmlspecialchars($details['type'] ?? '', ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars($details['class'] ?? '', ENT_QUOTES, 'UTF-8') ?>)</div>

                                    <div class="col-6 detail-label">Capacity</div>
                                    <div class="col-6 detail-value"><?= htmlspecialchars($details['capacity'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>

                                    <div class="col-6 detail-label">Location</div>
                                    <div class="col-6 detail-value"><?= htmlspecialchars($details['location'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>

                                    <div class="col-6 detail-label">Branch</div>
                                    <div class="col-6 detail-value"><?= htmlspecialchars($details['branch'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>

                                    <div class="col-6 detail-label">Manufactured Date</div>
                                    <div class="col-6 detail-value"><?= htmlspecialchars($details['manufactured_date'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>

                                    <div class="col-6 detail-label">Expiration Date</div>
                                    <div class="col-6 detail-value"><?= htmlspecialchars($details['expiration_date'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>

                                    <div class="col-6 detail-label">Current Condition</div>
                                    <div class="col-6">
                                        <?php if ($details['condition_status'] == 'Good'): ?>
                                            <span class="badge rounded-pill bg-soft-success">
                                                Good
                                            </span>
                                        <?php elseif ($details['condition_status'] == 'Not Good'): ?>
                                            <span class="badge rounded-pill bg-soft-danger">
                                                Not Good
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- ACTIVITY TIMELINE -->
                <div class="col-12 col-lg-6">

                    <div class="history-card p-3 p-lg-4">

                        <h6 class="section-title">
                            <span class="me-2">&#9719;</span> Activity Timeline
                        </h6>

                        <div class="timeline">

                            <?php if (!empty($timeline)): ?>

                                <?php foreach ($timeline as $item): ?>

                                    <div class="timeline-item">

                                        <div class="timeline-content">
                                            <div class="timeline-date">
                                                <?= htmlspecialchars(
                                                    $item['date'] ?? '',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </div>

                                            <div class="timeline-heading">
                                                <?= htmlspecialchars(
                                                    $item['title'] ?? '',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </div>

                                            <div class="timeline-description">
                                                <?= htmlspecialchars(
                                                    $item['description'] ?? '',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                                <?php if (!empty($item['reason'])): ?>
                                                    <div>
                                                        Reason:
                                                        <?= htmlspecialchars(
                                                            $item['reason'],
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                    </div>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <div class="alert alert-light border">
                                    No inspection history found for the selected
                                    fire extinguisher and date range.
                                </div>

                            <?php endif; ?>

                        </div>
                    </div>
                </div>

            </div>
        </div>





    </div>
    </div>
    <?php include 'partials/footer.php'; ?>
    </div>
    <!-- CoreUI and necessary plugins-->
    <script src="vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
    <script src="vendors/simplebar/js/simplebar.min.js"></script>
    <script>
        const header = document.querySelector("header.header");

        document.addEventListener("scroll", () => {
            if (header) {
                header.classList.toggle("shadow-sm", document.documentElement.scrollTop > 0);
            }
        });
    </script>
    <?php include_once 'notification/session_timeout.php'; ?>

</body>

</html>