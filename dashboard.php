<?php $pageTitle = "Dashboard"; ?>

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

        <div class="card mt-3">
          <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Fire Extinguisher: E01-106</strong>
            <span class="badge bg-success">Active</span>
          </div>

          <div class="card-body">

            <div class="row g-3">

              <div class="col-6 col-md-4">
                <small class="text-body-secondary">Type</small>
                <div class="fw-semibold">ABC</div>
              </div>

              <div class="col-6 col-md-4">
                <small class="text-body-secondary">Capacity</small>
                <div class="fw-semibold">4.5 kg</div>
              </div>

              <div class="col-6 col-md-4">
                <small class="text-body-secondary">Location</small>
                <div class="fw-semibold">Building 1 - 2nd Floor</div>
              </div>

              <div class="col-6 col-md-4">
                <small class="text-body-secondary">Last Inspection</small>
                <div class="fw-semibold">September 10, 2026</div>
              </div>

              <div class="col-6 col-md-4">
                <small class="text-body-secondary">Next Inspection</small>
                <div class="fw-semibold">October 10, 2026</div>
              </div>

              <div class="col-6 col-md-4">
                <small class="text-body-secondary">Condition</small>
                <div class="fw-semibold">Good</div>
              </div>

            </div>

            <hr>

            <div class="d-flex justify-content-end gap-2">
              <a href="view-extinguisher.php?id=E01-106" class="btn btn-primary">
                View
              </a>

              <a href="edit-extinguisher.php?id=E01-106" class="btn btn-warning">
                Edit
              </a>

              <a href="delete-extinguisher.php?id=E01-106"
                class="btn btn-danger"
                onclick="return confirm('Are you sure you want to delete this fire extinguisher?');">
                Delete
              </a>
            </div>

          </div>
        </div>

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