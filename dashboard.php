<?php
require_once 'backend/authentication/SessionChecker.php';

include './backend/controller/DashboardController.php';
include './backend/controller/DropdownBranchController.php';
include './backend/controller/QRCodeGeneratorController.php';
$totalFE = getTotalFireExtinguishersDashboard();
$totalSpareFE = getTotalSpareFireExtinguishers();
$goodSpareFE = getTotalGoodSpareFireExtinguishers();
$notGoodSpareFE = getTotalNotGoodSpareFireExtinguishers();

$totalInstalledFE = getTotalInstalledFireExtinguishers();
$goodInstalledFE = getTotalGoodInstalledFireExtinguishers();
$notGoodInstalledFE = getTotalNotGoodInstalledFireExtinguishers();

$allGoodCondition = getGoodCondition();
$allNotGoodCondition = getNotGoodCondition();

$typeCounts = getFireExtinguisherTypeCountsController();

$monthlyInspectionData = getInspectLastThreeMonths();

$expirySoon = getAllExpiringCount();


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

    <div class="container-fluid py-0">

      <div
        class="card border-0 shadow-sm text-white mb-1 mt-0 overflow-hidden"
        style="background: linear-gradient(135deg, #1d0870, #2408f5);">

        <div class="card-body p-4">

          <div class="row align-items-center g-3">

            <!-- Icon -->
            <div class="col-auto">

              <div class="bg-white bg-opacity-10 rounded-3 p-3 fs-3">
                <i class="bi bi-speedometer2"></i>
              </div>

            </div>

            <!-- Title & Description -->
            <div class="col">

              <h2 class="fw-bold mb-1">
                Fire Extinguisher Dashboard
              </h2>

              <p class="mb-0 text-white-50">
                Overview of fire extinguishers, their conditions, and important safety information.
              </p>

            </div>

          </div>

        </div>

      </div>

    </div>


    <!-- this is for dashboard totals -->

    <div class="main-content flex-grow-1">
      <div class="container-lg px-4">

        <?php
        include_once 'notification/fe-expiration-notice.php';
        ?>

        <div class="row g-5">
          <!-- Analytics Header -->
          <form method="POST" action="">

            <div class="d-flex justify-content-between align-items-center mb-0 flex-wrap gap-2">

              <!-- Date -->
              <div class="fs-5 fw-semibold text-body mt-3">
                <i class="bi bi-calendar3 me-2 text-primary"></i>
                As of <span id="analyticsDate"></span>
              </div>

              <!-- Branch Filter - ADMIN ONLY -->
              <?php if (strtolower(trim($_SESSION['Role'] ?? '')) === 'admin'): ?>

                <div class="col-12 col-md-auto">

                  <select
                    class="form-select"
                    id="branchFilter"
                    style="min-width: 190px;">

                    <option value="all">All Branches</option>

                    <?php
                    $branches = getAllDropdownBranches();

                    if (!empty($branches)):
                      foreach ($branches as $branch):
                    ?>

                        <option
                          value="<?= htmlspecialchars($branch['value']) ?>"
                          <?= (
                            ($_GET['branch'] ?? 'all') === $branch['value']
                          ) ? 'selected' : '' ?>>

                          <?= htmlspecialchars($branch['value']) ?>

                        </option>

                    <?php
                      endforeach;
                    endif;
                    ?>

                  </select>

                </div>

              <?php endif; ?>
              <script>
                document.addEventListener('DOMContentLoaded', function() {

                  const branchFilter = document.getElementById('branchFilter');

                  if (!branchFilter) {
                    return;
                  }

                  branchFilter.addEventListener('change', function() {

                    const selectedBranch = this.value;

                    const url = new URL(window.location.href);

                    if (selectedBranch === 'all' || selectedBranch === '') {

                      url.searchParams.delete('branch');

                    } else {

                      url.searchParams.set('branch', selectedBranch);

                    }

                    window.location.href = url.toString();

                  });

                });
              </script>

            </div>

          </form>

          <!-- end of header -->

          <div class="container-fluid px-4 py-4 mb-1">
            <!-- totals -->
            <div class="row g-3">

              <!-- Expiring Soon -->
              <div class="col-12 col-sm-6 col-md-6 col-xl-3">
                <div class="card border-1 shadow-sm h-100">
                  <div class="card-body d-flex align-items-center">
                    <div class="bg-danger bg-opacity-10 rounded-3 p-3 me-3">
                      <i class="bi bi-calendar-x-fill text-danger fs-3"></i>
                    </div>

                    <div>
                      <h6 class="text-muted mb-1">
                        Expiring Soon
                      </h6>

                      <h3 class="fw-bold mb-0">
                        <?= $expirySoon  ?>
                      </h3>

                      <small class="text-muted">
                        Within 2 months
                      </small>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Total Fire Extinguishers -->
              <div class="col-12 col-sm-6 col-md-6 col-xl-3">
                <div class="card border-1 shadow-sm h-100">
                  <div class="card-body d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                      <i class="fa-solid fa-layer-group text-primary fs-3"></i>
                    </div>

                    <div>
                      <h6 class="text-muted mb-1">
                        Total Active Fire Extinguishers
                      </h6>

                      <h3 class="fw-bold mb-0">
                        <?= $totalFE ?>
                      </h3>

                      <small class="text-muted">
                        Registered in system
                      </small>
                    </div>
                  </div>
                </div>
              </div>


              <!-- Total Spare Fire Extinguishers -->
              <div class="col-12 col-sm-6 col-md-6 col-xl-3">
                <div class="card border-1 shadow-sm h-100">
                  <div class="card-body">

                    <div class="d-flex align-items-center">
                      <div class="bg-secondary bg-opacity-10 rounded-3 p-3 me-3">
                        <i class="fa-solid fa-boxes-stacked text-secondary fs-3"></i>
                      </div>

                      <div>
                        <h6 class="text-muted mb-1">
                          Total Spare FE
                        </h6>

                        <h3 class="fw-bold mb-0">
                          <?= $totalSpareFE ?>
                        </h3>

                        <small class="text-muted">
                          Spare units
                        </small>
                      </div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                      <span class="badge bg-success-subtle text-success">
                        Good: <?= $goodSpareFE ?>
                      </span>

                      <span class="badge bg-danger-subtle text-danger">
                        Not Good: <?= $notGoodSpareFE ?>
                      </span>
                    </div>

                  </div>
                </div>
              </div>

              <!-- Total Installed Fire Extinguishers -->
              <div class="col-12 col-sm-6 col-md-6 col-xl-3">
                <div class="card border-1 shadow-sm h-100">
                  <div class="card-body">

                    <div class="d-flex align-items-center">
                      <div class="bg-secondary bg-opacity-10 rounded-3 p-3 me-3">
                        <i class="fa-solid fa-fire-extinguisher text-primary fs-3"></i>
                      </div>

                      <div>
                        <h6 class="text-muted mb-1">
                          Total Installed FE
                        </h6>

                        <h3 class="fw-bold mb-0">
                          <?= $totalInstalledFE ?>
                        </h3>

                        <small class="text-muted">
                          Installed units
                        </small>
                      </div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                      <span class="badge bg-success-subtle text-success">
                        Good: <?= $goodInstalledFE ?>
                      </span>

                      <span class="badge bg-danger-subtle text-danger">
                        Not Good: <?= $notGoodInstalledFE ?>
                      </span>
                    </div>

                  </div>
                </div>
              </div>

            </div>

            <!-- MAIN ANALYTICS -->
            <div class="row g-3 mt-0">

              <?php
              // ==========================================
              // MONTHLY INSPECTION DATA - LATEST 3 MONTHS
              // ==========================================

              $inspectionData = [];

              for ($i = 2; $i >= 0; $i--) {

                $date = new DateTime();
                $date->modify("-{$i} month");

                $monthKey = $date->format('Y-m');

                $inspectionData[$monthKey] = [
                  'label' => $date->format('M'),
                  'total' => 0
                ];
              }

              foreach ($monthlyInspectionData as $row) {

                if (isset($inspectionData[$row['inspection_month']])) {

                  $inspectionData[$row['inspection_month']]['total'] =
                    (int) $row['total_inspected'];
                }
              }

              $chartLabels = [];
              $chartValues = [];

              foreach ($inspectionData as $data) {

                $chartLabels[] = $data['label'];
                $chartValues[] = $data['total'];
              }

              $totalInspected3Months = array_sum($chartValues);

              $previousMonth = $chartValues[1];
              $currentMonth  = $chartValues[2];

              if ($currentMonth > $previousMonth) {

                $trendIcon = 'bi-arrow-up';
                $trendText = 'Up from last month';
                $trendClass = 'text-success';
              } elseif ($currentMonth < $previousMonth) {

                $trendIcon = 'bi-arrow-down';
                $trendText = 'Down from last month';
                $trendClass = 'text-danger';
              } else {

                $trendIcon = 'bi-dash';
                $trendText = 'No change';
                $trendClass = 'text-body-secondary';
              }
              ?>


              <!-- ==========================================
         MONTHLY INSPECTIONS
    =========================================== -->
              <div class="col-12 col-lg-6">

                <div class="card border shadow-sm h-100">

                  <div class="card-body pb-2">

                    <!-- Header -->
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start gap-3">

                      <!-- Title -->
                      <div class="d-flex align-items-center gap-2">

                        <div
                          class="rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center flex-shrink-0"
                          style="width:42px;height:42px;">
                          <i class="bi bi-bar-chart-fill fs-5"></i>
                        </div>

                        <div class="min-w-0">

                          <h5 class="fw-semibold mb-0 text-truncate">
                            Monthly Inspections
                          </h5>

                          <small class="text-body-secondary">
                            Latest 3 months
                          </small>

                        </div>

                      </div>


                      <!-- Total -->
                      <div class="text-start text-sm-end flex-shrink-0">

                        <div class="small text-body-secondary">
                          Total
                        </div>

                        <div class="fs-3 fw-bold lh-1">
                          <?= number_format($totalInspected3Months) ?>
                        </div>

                        <div class="small <?= $trendClass ?> mt-1">

                          <i class="bi <?= $trendIcon ?>"></i>

                          <?= htmlspecialchars($trendText) ?>

                        </div>

                      </div>

                    </div>


                    <!-- Chart -->
                    <div
                      class="position-relative mt-4"
                      style="height:clamp(180px, 28vw, 250px);">
                      <canvas id="monthlyInspectionChart"></canvas>
                    </div>

                  </div>

                </div>

              </div>


              <?php
              // ==========================================
              // FIRE EXTINGUISHER TYPE DATA
              // ==========================================


              $totalExtinguishers = 0;

              foreach ($typeCounts as $typeData) {

                $totalExtinguishers += (int) $typeData['total'];
              }
              ?>


              <!-- ==========================================
         BY TYPE
    =========================================== -->
              <div class="col-12 col-md-6 col-lg-3">

                <div class="card border shadow-sm h-100">

                  <div class="card-body">

                    <!-- Header -->
                    <div class="d-flex align-items-center gap-2 mb-3">

                      <div
                        class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:40px;height:40px;">
                        <i class="bi bi-pie-chart-fill"></i>
                      </div>

                      <div class="min-w-0">

                        <h6 class="fw-bold mb-0">
                          By Type
                        </h6>

                        <small class="text-body-secondary">
                          Fire extinguisher types
                        </small>

                      </div>

                    </div>


                    <?php if (!empty($typeCounts)): ?>

                      <?php foreach ($typeCounts as $typeData): ?>

                        <?php

                        $type = $typeData['type'];

                        $count = (int) $typeData['total'];

                        $percentage = $totalExtinguishers > 0
                          ? ($count / $totalExtinguishers) * 100
                          : 0;

                        ?>

                        <div class="mb-3">

                          <div class="d-flex justify-content-between align-items-center mb-1">

                            <small class="text-truncate me-2">
                              <?= htmlspecialchars($type) ?>
                            </small>

                            <small class="fw-bold flex-shrink-0">
                              <?= $count ?>
                            </small>

                          </div>

                          <div
                            class="progress"
                            style="height:6px;"
                            role="progressbar"
                            aria-valuenow="<?= round($percentage, 1) ?>"
                            aria-valuemin="0"
                            aria-valuemax="100">

                            <div
                              class="progress-bar"
                              style="width:<?= $percentage ?>%;"></div>

                          </div>

                        </div>

                      <?php endforeach; ?>

                    <?php else: ?>

                      <div class="text-center py-4">

                        <i class="bi bi-fire fs-3 text-body-secondary"></i>

                        <div class="mt-2">

                          <small class="text-body-secondary">
                            No fire extinguisher data available.
                          </small>

                        </div>

                      </div>

                    <?php endif; ?>

                  </div>

                </div>

              </div>


              <!-- ==========================================
         QUICK SUMMARY
    =========================================== -->
              <div class="col-12 col-md-6 col-lg-3">

                <div class="card border shadow-sm h-100">

                  <div class="card-body">

                    <!-- Header -->
                    <div class="d-flex align-items-center gap-2 mb-3">

                      <div
                        class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:40px;height:40px;">
                        <i class="bi bi-list-columns"></i>
                      </div>

                      <h6 class="fw-bold mb-0">
                        Quick Summary
                      </h6>

                    </div>


                    <!-- Total FE -->
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2 gap-2">

                      <small class="text-truncate">
                        Total Fire Extinguishers
                      </small>

                      <span class="badge bg-light text-dark flex-shrink-0">
                        <?= $totalFE ?>
                      </span>

                    </div>


                    <!-- Installed -->
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2 gap-2">

                      <small>
                        Installed Units
                      </small>

                      <span class="badge bg-light text-dark flex-shrink-0">
                        <?= $totalInstalledFE ?>
                      </span>

                    </div>


                    <!-- Spare -->
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2 gap-2">

                      <small>
                        Spare Units
                      </small>

                      <span class="badge bg-light text-dark flex-shrink-0">
                        <?= $totalSpareFE ?>
                      </span>

                    </div>


                    <!-- Good -->
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2 gap-2">

                      <small>
                        Good Units
                      </small>

                      <span class="badge bg-success-subtle text-success flex-shrink-0">
                        <?= $allGoodCondition ?>
                      </span>

                    </div>


                    <!-- Not Good -->
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2 gap-2">

                      <small>
                        Not Good Units
                      </small>

                      <span class="badge bg-danger-subtle text-danger flex-shrink-0">
                        <?= $allNotGoodCondition ?>
                      </span>

                    </div>


                    <!-- Expiring -->
                    <div class="d-flex justify-content-between align-items-center py-2 gap-2">

                      <small>
                        Expiring Soon
                      </small>

                      <span class="badge bg-warning-subtle text-warning-emphasis flex-shrink-0">
                        <?= $expirySoon ?>
                      </span>

                    </div>


                    <!-- Information -->
                    <div class="alert alert-primary mt-3 mb-0 small">

                      <i class="bi bi-info-circle me-1"></i>

                      Keep track of expiring units and conduct
                      regular inspections to ensure safety and compliance.

                    </div>

                  </div>

                </div>

              </div>

            </div>


            <!-- Chart.js Data -->
            <script>
              const labels = <?= json_encode($chartLabels) ?>;
              const values = <?= json_encode($chartValues) ?>;
            </script>

            <script src="js/dashboard.js"></script>

          </div>

          <!-- content end -->
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