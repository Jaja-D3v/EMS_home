<?php
$pageTitle = "Dashboard";
include './backend/controller/FireExtinguisherController.php';

$totalFE = getTotalFireExtinguishers();
$totalSpareFE = getTotalSpareFireExtinguishers();
$info = getAllFireExtinguishers();
$goodSpareFE = getTotalGoodSpareFireExtinguishers();
$notGoodSpareFE = getTotalNotGoodSpareFireExtinguishers();
$totalInstalledFE = getTotalInstalledFireExtinguishers();
$goodInstalledFE = getTotalGoodInstalledFireExtinguishers();
$notGoodInstalledFE = getTotalNotGoodInstalledFireExtinguishers();
$allGoodCondition = getGoodCondition();
$allNotGoodCondition = getNotGoodCondition();

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


    <!-- this is for dashboard totals -->

    <div class="main-content flex-grow-1">
      <div class="container-lg px-4">

        <?php include_once 'notification/fe-expiration-notice.php'; ?>

        <div class="row g-5">
          <!-- Analytics Header -->
          <div class="d-flex justify-content-between align-items-center mb-0">

            <div>
              <h4 class="fw-bold mb-1">
                Fire Extinguisher Analytics
              </h4>

              <small class="text-body-secondary">
                Overview of your fire extinguisher inventory and condition
              </small>
            </div>

            <div class="badge bg-light text-dark border px-3 py-2">
              <i class="bi bi-calendar3 me-2"></i>
              As of <span id="analyticsDate"></span>
            </div>

            <script>
              document.addEventListener('DOMContentLoaded', function() {

                const today = new Date();

                const formattedDate = today.toLocaleDateString('en-US', {
                  month: 'long',
                  day: 'numeric',
                  year: 'numeric'
                });

                document.getElementById('analyticsDate').textContent = formattedDate;

              });
            </script>

          </div>




          <!-- end of stats -->

          <div class="container-fluid px-4 py-4 mb-1">
            <!-- Expiring -->
            <div class="row">

              <!-- Expiring Soon -->
              <div class="col-12 col-md-3 mt-1 mb-4">
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
                        <?= $expiringCount ?>
                      </h3>

                      <small class="text-muted">
                        Within 2 months
                      </small>
                    </div>

                  </div>

                </div>
              </div>


              <!-- Total Fire Extinguishers -->
              <div class="col-12 col-md-3 mt-1 mb-4">

                <div class="card border-1 shadow-sm h-100">

                  <div class="card-body d-flex align-items-center">

                    <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                      <i class="fa-solid fa-layer-group text-primary fs-3"></i>
                    </div>

                    <div>
                      <h6 class="text-muted mb-1">
                        Total Fire Extinguishers
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
              <div class="col-12 col-md-3 mt-1 mb-4">

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
              <div class="col-12 col-md-3 mt-1 mb-4">

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


              <!-- Condition Overview -->
              <div class="col-12 col-md-6 col-xl-3">

                <div class="card border shadow-sm h-100">

                  <div class="card-body">

                    <div class="d-flex align-items-center gap-2 mb-3">

                      <div class="rounded-3 bg-primary-subtle
                                    text-primary d-flex
                                    align-items-center
                                    justify-content-center"
                        style="width:40px;height:40px;">

                        <i class="bi bi-shield-check"></i>

                      </div>

                      <div>
                        <h6 class="fw-bold mb-0">
                          Condition Overview
                        </h6>

                        <small class="text-body-secondary">
                          Overall status
                        </small>
                      </div>

                    </div>

                    <div style="height:230px;">
                      <canvas id="conditionChart"></canvas>
                    </div>

                    <div class="d-flex justify-content-center gap-3 mt-2">

                      <small>
                        <span class="text-success">●</span>
                        Good (4)
                      </small>

                      <small>
                        <span class="text-danger">●</span>
                        Not Good (4)
                      </small>

                    </div>

                  </div>

                </div>

              </div>


              <!-- Expiring Soon -->
              <div class="col-12 col-md-6 col-xl-3">

                <div class="card border shadow-sm h-100">

                  <div class="card-body">

                    <div class="d-flex align-items-center gap-2 mb-3">

                      <div class="rounded-3 bg-primary-subtle
                                    text-primary d-flex
                                    align-items-center
                                    justify-content-center"
                        style="width:40px;height:40px;">

                        <i class="bi bi-clock"></i>

                      </div>

                      <div>
                        <h6 class="fw-bold mb-0">
                          Expiring Soon
                        </h6>

                        <small class="text-body-secondary">
                          Units that need attention
                        </small>
                      </div>

                    </div>

                    <div style="height:230px;">
                      <canvas id="expiringChart"></canvas>
                    </div>

                    <div class="d-flex justify-content-center gap-3 mt-2">

                      <small>
                        <span class="text-danger">●</span>
                        Expiring Soon (2)
                      </small>

                      <small>
                        <span class="text-secondary">●</span>
                        Not Expiring (6)
                      </small>

                    </div>

                  </div>

                </div>

              </div>




              <!-- By Type -->
              <div class="col-12 col-md-6 col-xl-3">

                <div class="card border shadow-sm h-100">

                  <div class="card-body">

                    <div class="d-flex align-items-center gap-2 mb-3">

                      <div class="rounded-3 bg-primary-subtle
                                    text-primary d-flex
                                    align-items-center
                                    justify-content-center"
                        style="width:40px;height:40px;">

                        <i class="bi bi-pie-chart-fill"></i>

                      </div>

                      <div>
                        <h6 class="fw-bold mb-0">
                          By Type
                        </h6>

                        <small class="text-body-secondary">
                          Fire extinguisher types
                        </small>
                      </div>

                    </div>


                    <!-- Type 1 -->
                    <div class="mb-3">

                      <div class="d-flex justify-content-between mb-1">

                        <small>Dry Chemical</small>

                        <small class="fw-bold">2</small>

                      </div>

                      <div class="progress" style="height:6px;">
                        <div
                          class="progress-bar"
                          style="width:25%">
                        </div>
                      </div>

                    </div>


                    <!-- Type 2 -->
                    <div class="mb-3">

                      <div class="d-flex justify-content-between mb-1">

                        <small>CO2</small>

                        <small class="fw-bold">1</small>

                      </div>

                      <div class="progress" style="height:6px;">
                        <div
                          class="progress-bar"
                          style="width:12.5%">
                        </div>
                      </div>

                    </div>


                    <!-- Type 3 -->
                    <div class="mb-3">

                      <div class="d-flex justify-content-between mb-1">

                        <small>Water</small>

                        <small class="fw-bold">1</small>

                      </div>

                      <div class="progress" style="height:6px;">
                        <div
                          class="progress-bar"
                          style="width:12.5%">
                        </div>
                      </div>

                    </div>


                    <!-- Type 4 -->
                    <div class="mb-3">

                      <div class="d-flex justify-content-between mb-1">

                        <small>Foam</small>

                        <small class="fw-bold">1</small>

                      </div>

                      <div class="progress" style="height:6px;">
                        <div
                          class="progress-bar"
                          style="width:12.5%">
                        </div>
                      </div>

                    </div>


                    <!-- Type 5 -->
                    <div class="mb-3">

                      <div class="d-flex justify-content-between mb-1">

                        <small>Wet Chemical</small>

                        <small class="fw-bold">1</small>

                      </div>

                      <div class="progress" style="height:6px;">
                        <div
                          class="progress-bar"
                          style="width:12.5%">
                        </div>
                      </div>

                    </div>


                    <!-- Type 6 -->
                    <div>

                      <div class="d-flex justify-content-between mb-1">

                        <small>HCFC-123</small>

                        <small class="fw-bold">2</small>

                      </div>

                      <div class="progress" style="height:6px;">
                        <div
                          class="progress-bar"
                          style="width:25%">
                        </div>
                      </div>

                    </div>

                  </div>

                </div>

              </div>


              <!-- Quick Summary -->
              <div class="col-12 col-md-6 col-xl-3">

                <div class="card border shadow-sm h-100">

                  <div class="card-body">

                    <div class="d-flex align-items-center gap-2 mb-3">

                      <div class="rounded-3 bg-primary-subtle
                                    text-primary d-flex
                                    align-items-center
                                    justify-content-center"
                        style="width:40px;height:40px;">

                        <i class="bi bi-list-columns"></i>

                      </div>

                      <h6 class="fw-bold mb-0">
                        Quick Summary
                      </h6>

                    </div>


                    <div class="d-flex justify-content-between
                                align-items-center border-bottom py-2">

                      <small>Total Fire Extinguishers</small>

                      <span class="badge bg-light text-dark">
                        <?= $totalFE ?>
                      </span>

                    </div>


                    <div class="d-flex justify-content-between
                                align-items-center border-bottom py-2">

                      <small>Installed Units</small>

                      <span class="badge bg-light text-dark">
                        <?= $totalInstalledFE ?>
                      </span>

                    </div>


                    <div class="d-flex justify-content-between
                                align-items-center border-bottom py-2">

                      <small>Spare Units</small>

                      <span class="badge bg-light text-dark">
                        <?= $totalSpareFE  ?>
                      </span>

                    </div>


                    <div class="d-flex justify-content-between
                                align-items-center border-bottom py-2">

                      <small>Good Units</small>

                      <span class="badge bg-success-subtle text-success">
                       <?=$allGoodCondition  ?>
                      </span>

                    </div>


                    <div class="d-flex justify-content-between
                                align-items-center border-bottom py-2">

                      <small>Not Good Units</small>

                      <span class="badge bg-danger-subtle text-danger">
                       <?=$allNotGoodCondition  ?>
                        
                      </span>

                    </div>


                    <div class="d-flex justify-content-between
                                align-items-center py-2">

                      <small>Expiring Soon</small>

                      <span class="badge bg-warning-subtle text-warning-emphasis">
                        <?= $expiringCount ?>
                      </span>

                    </div>


                    <div class="alert alert-primary mt-3 mb-0 small">

                      <i class="bi bi-info-circle me-1"></i>

                      Keep track of expiring units and conduct
                      regular inspections to ensure safety and compliance.

                    </div>

                  </div>

                </div>

              </div>

            </div>

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

    <!-- for chart -->

    <script>
      // ============================================
      // INSTALLED VS SPARE
      // ============================================

      new Chart(
        document.getElementById('installedSpareChart'), {
          type: 'bar',

          data: {
            labels: [
              'Installed FE',
              'Spare FE'
            ],

            datasets: [{
                label: 'Good',
                data: [2, 2],
                backgroundColor: '#20b978',
                borderRadius: 3
              },
              {
                label: 'Not Good',
                data: [2, 2],
                backgroundColor: '#ff5b61',
                borderRadius: 3
              }
            ]
          },

          options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {
              legend: {
                position: 'bottom'
              }
            },

            scales: {

              x: {
                stacked: true,

                grid: {
                  display: false
                }
              },

              y: {
                stacked: true,

                beginAtZero: true,

                ticks: {
                  stepSize: 1
                }
              }

            }

          }
        }
      );


      // ============================================
      // CONDITION OVERVIEW
      // ============================================

      new Chart(
        document.getElementById('conditionChart'), {
          type: 'doughnut',

          data: {

            labels: [
              'Good',
              'Not Good'
            ],

            datasets: [{
              data: [
                4,
                4
              ],

              backgroundColor: [
                '#20b978',
                '#ff5b61'
              ],

              borderWidth: 0
            }]

          },

          options: {

            responsive: true,

            maintainAspectRatio: false,

            cutout: '65%',

            plugins: {

              legend: {
                display: false
              }

            }

          }

        }
      );


      // ============================================
      // EXPIRING SOON
      // ============================================

      new Chart(
        document.getElementById('expiringChart'), {
          type: 'doughnut',

          data: {

            labels: [
              'Expiring Soon',
              'Not Expiring'
            ],

            datasets: [{
              data: [
                2,
                6
              ],

              backgroundColor: [
                '#ff5b61',
                '#dce1e7'
              ],

              borderWidth: 0
            }]

          },

          options: {

            responsive: true,

            maintainAspectRatio: false,

            cutout: '65%',

            plugins: {

              legend: {
                display: false
              }

            }

          }

        }
      );


      // ============================================
      // INSPECTION TREND
      // ============================================

      new Chart(
        document.getElementById('inspectionTrendChart'), {
          type: 'bar',

          data: {

            labels: [
              'Apr 2026',
              'May 2026',
              'Jun 2026',
              'Jul 2026',
              'Aug 2026',
              'Sep 2026'
            ],

            datasets: [

              {
                type: 'bar',
                label: 'Good',
                data: [
                  4,
                  5,
                  5,
                  6,
                  4,
                  6
                ],
                backgroundColor: '#20b978',
                borderRadius: 3
              },

              {
                type: 'bar',
                label: 'Not Good',
                data: [
                  2,
                  3,
                  2,
                  3,
                  2,
                  3
                ],
                backgroundColor: '#ff5b61',
                borderRadius: 3
              },

              {
                type: 'line',
                label: 'Total Inspected',
                data: [
                  6,
                  8,
                  7,
                  8,
                  6,
                  8
                ],

                borderColor: '#f5a623',

                backgroundColor: '#f5a623',

                borderWidth: 2,

                pointRadius: 4,

                pointHoverRadius: 6,

                tension: 0.3
              }

            ]

          },

          options: {

            responsive: true,

            maintainAspectRatio: false,

            interaction: {
              mode: 'index',
              intersect: false
            },

            plugins: {

              legend: {
                position: 'bottom'
              }

            },

            scales: {

              x: {
                grid: {
                  display: false
                }
              },

              y: {

                beginAtZero: true,

                ticks: {
                  stepSize: 2
                }

              }

            }

          }

        }
      );
    </script>


</body>

</html>