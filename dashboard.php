<?php
$pageTitle = "Dashboard";
include './backend/controller/FireExtinguisherController.php';
$info = getAllFireExtinguishers();

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
<?php include 'header.php'; ?>

<body>
  <?php include 'side-nav.php'; ?>
  <div class="wrapper d-flex flex-column min-vh-100">
    <?php include 'header-nav.php'; ?>

    <!-- this is for dashboard totals -->

    <div class="main-content flex-grow-1">
      <div class="container-lg px-4">

        <div class="row g-3">

          <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="card border-top-primary border-top-3 h-100">
              <div class="card-header">Total Number of fire extinguisher</div>
              <div class="card-body text-primary text-center">
                <h3 class="card-title">230</h3>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="card border-top-primary border-top-3 h-100">
              <div class="card-header">Total inspected this month</div>
              <div class="card-body text-primary text-center">
                <h3 class="card-title">230</h3>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="card border-top-warning border-top-3 h-100">
              <div class="card-header">Expiring within 2 months</div>
              <div class="card-body text-primary text-center">
                <h3 class="card-title">54</h3>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="card border-top-danger border-top-3 h-100">
              <div class="card-header">For maintenance</div>
              <div class="card-body text-primary text-center">
                <h3 class="card-title">54</h3>
              </div>
            </div>
          </div>
        </div>

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
          <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
              <strong>Fire Extinguisher: <?= htmlspecialchars($data['extinguisher_code']) ?></strong>
              <span class="badge bg-success">Active</span>
            </div>

            <div class="card-body">

              <div class="row g-3">

                <div class="col-6 col-md-4">
                  <small class="text-body-secondary">Type</small>
                  <div class="fw-semibold"> <?= htmlspecialchars($data['type']) ?></div>
                </div>

                <div class="col-6 col-md-4">
                  <small class="text-body-secondary">Capacity</small>
                  <div class="fw-semibold"> <?= htmlspecialchars($data['capacity']) ?></div>
                </div>

                <div class="col-6 col-md-4">
                  <small class="text-body-secondary">Location</small>
                  <div class="fw-semibold"> <?= htmlspecialchars($data['location']) ?></div>
                </div>

                <div class="col-6 col-md-4">
                  <small class="text-body-secondary">Last Inspection</small>
                  <div class="fw-semibold"> <?= htmlspecialchars($data['date_acquired']) ?></div>
                </div>

                <div class="col-6 col-md-4">
                  <small class="text-body-secondary">Expiration Date</small>
                  <div class="fw-semibold"> <?= htmlspecialchars($data['expiration_date']) ?></div>
                </div>

                <div class="col-6 col-md-4">
                  <small class="text-body-secondary">Status</small>
                  <div class="fw-semibold"> <?= htmlspecialchars($data['status']) ?></div>
                </div>

              </div>

              <hr>

              <div class="d-flex justify-content-end gap-2">
                <a href="view-extinguisher.php?id=<?= urlencode($data['extinguisher_id']) ?>" class="btn btn-primary">
                  View
                </a>

                <a href="edit-extinguisher.php?id=<?= urlencode($data['extinguisher_id']) ?>" class="btn btn-warning">
                  Edit
                </a>

                <a href="delete-extinguisher.php?id=<?= urlencode($data['extinguisher_id']) ?>"
                  class="btn btn-danger"
                  onclick="return confirm('Are you sure you want to delete this fire extinguisher?');">
                  Delete
                </a>
              </div>

            </div>
          </div>

        <?php endforeach; ?>

      </div>
    </div>
    <?php include 'footer.php'; ?>
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