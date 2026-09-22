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

    <?php
    include_once 'notification/updated_fe_success.php';
    include_once 'notification/delete_fe_success.php';

    ?>

    <!-- this is for dashboard totals -->

    <div class="main-content flex-grow-1">
      <div class="container-lg px-4">

        <?php include_once 'notification/fe-expiration-notice.php'; ?>

        <div class="row g-3">


          <!-- Expiring -->
          <div class="row">

            <!-- Expiring Soon -->
            <div class="col-12 col-md-3 mt-3">
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
                     <?=  $expiringCount ?>  
                    </h3>

                    <small class="text-muted">
                      Within 2 months
                    </small>
                  </div>

                </div>

              </div>
            </div>


            <!-- Total Fire Extinguishers -->
            <div class="col-12 col-md-3 mt-3">
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
            <div class="col-12 col-md-3 mt-3">
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
                       <?=  $totalSpareFE ?>  
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
            <div class="col-12 col-md-3 mt-3">
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

          <!-- end of stats -->



          <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3 mt-3">
            <!-- Sorting -->
            <div class="d-flex align-items-center gap-2">
              <!-- <label for="sort" class="mb-0">Sort by:</label> -->

              <select id="sort" class="form-select" style="width: 180px;">
                <option value="newest">Sort by</option>
                <option value="newest">Newest</option>
                <option value="oldest">Oldest</option>
                <option value="name-asc">Name A-Z</option>
                <option value="name-desc">Name Z-A</option>
              </select>
            </div>

            <!-- Search -->
            <div class="input-group" style="max-width: 300px;">
              <input
                type="text"
                class="form-control"
                placeholder="Search...">
              <button class="btn btn-primary" type="button">
                Search
              </button>
            </div>
          </div>

          <!-- Content here -->
          <?php foreach ($info as $data): ?>

            <div class="card border-1 shadow-sm mb-0">

              <!-- Header -->
              <div class="card-header bg-white border-0 py-2 px-3">
                <div class="d-flex justify-content-between align-items-center">
                  <!-- Title -->
                  <div class="d-flex align-items-center gap-2">

                    <div class="bg-primary bg-opacity-10 text-primary rounded p-2">
                      <i class="bi bi-fire"></i>
                    </div>

                    <div>
                      <div class="fw-semibold">
                        Fire Extinguisher
                      </div>

                      <small class="text-body-secondary">
                        <?= htmlspecialchars($data['extinguisher_code']) ?>
                      </small>
                    </div>
                  </div>


                  <!-- Status -->
                  <?php
                  $condition = $data['condition_status'];

                  if ($condition === 'Good') {
                    $badgeClass = 'bg-success-subtle text-success';
                  } else {
                    $badgeClass = 'bg-danger-subtle text-danger';
                  }
                  ?>

                  <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-2">
                    <?= htmlspecialchars($condition) ?>
                  </span>
                </div>
              </div>


              <!-- Body -->
              <div class="card-body px-3 py-2">
                <div class="row g-2">
                  <!-- FE Code -->
                  <div class="col-6 col-md-3">
                    <div class="text-body-secondary small">
                      FE Code
                    </div>

                    <div class="fw-semibold text-truncate">

                      <i class="bi bi-qr-code me-1 text-primary"></i>

                      <?= htmlspecialchars($data['extinguisher_code']) ?>

                    </div>
                  </div>


                  <!-- Capacity -->
                  <div class="col-6 col-md-3">
                    <div class="text-body-secondary small">
                      Capacity
                    </div>

                    <div class="fw-semibold">

                      <i class="bi bi-box-seam me-1 text-primary"></i>

                      <?= htmlspecialchars($data['capacity']) ?>

                    </div>
                  </div>


                  <!-- Type -->
                  <div class="col-6 col-md-3">
                    <div class="text-body-secondary small">
                      Type
                    </div>

                    <div class="fw-semibold text-truncate">
                      <i class="bi bi-fire me-1 text-primary"></i>

                      <?= htmlspecialchars($data['type']) ?>

                    </div>
                  </div>


                  <!-- Location -->
                  <div class="col-6 col-md-3">
                    <div class="text-body-secondary small">
                      Location
                    </div>
                    <div class="fw-semibold text-truncate">

                      <i class="bi bi-geo-alt me-1 text-primary"></i>

                      <?= htmlspecialchars($data['location']) ?>

                    </div>
                  </div>
                </div>
              </div>


              <!-- Footer / Actions -->
              <div class="card-footer bg-white border-0 px-3 pb-3 pt-1">

                <div class="d-flex justify-content-end gap-2">

                  <a
                    href="view-extinguisher.php?id=<?= urlencode($data['extinguisher_id']) ?>"
                    class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye me-1"></i>
                    View
                  </a>

                  <a
                    href="edit-extinguisher.php?id=<?= urlencode($data['extinguisher_id']) ?>"
                    class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-pencil me-1"></i>
                    Edit
                  </a>

                  <a
                    href="backend/controller/FireExtinguisherController.php?action=delete&id=<?= urlencode($data['extinguisher_id']) ?>"
                    class="btn btn-sm btn-outline-danger"
                    onclick="return confirm('Are you sure you want to delete this fire extinguisher?');">
                    <i class="bi bi-trash me-1"></i>
                    Delete
                  </a>

                </div>

              </div>

            </div>

          <?php endforeach; ?>

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
</body>

</html>