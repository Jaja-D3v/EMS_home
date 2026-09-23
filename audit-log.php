<?php $pageTitle = '<div class="d-flex align-items-center gap-2 mb-1">
                <i class="bi bi-activity text-primary"></i>

                <h5 class="fw-semibold mb-0">
                  Activity Log
                </h5>
              </div>'; ?>

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


    <div class="body flex-grow-1">
      <div class="container-lg px-4">
        <!-- content -->


        <!-- Header -->
        <div class="card-body px-4 pt-4 pb-3">

          <div class="d-flex align-items-center justify-content-between">

            <div>
              <small class="text-body-secondary">
                Recent system activity
              </small>
            </div>

            <span class="bg-white badge rounded-pill text-bg-light border px-3 py-2 shadow-sm">
              <i class="bi bi-clock me-1"></i>
              Recent
            </span>
          </div>
        </div>


        <!-- Activity -->


        <!-- Add -->
        <div class="bg-white rounded-4 px-3 py-3 mb-2 border border-secondary-subtle  shadow-sm">
          <div class="d-flex align-items-center gap-3">

            <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
              <i class="bi bi-plus-lg"></i>
            </div>

            <div class="flex-grow-1">

              <div class="d-flex align-items-center gap-2">
                <span class="fw-semibold">
                  Fire Extinguisher Added
                </span>

                <span class="badge rounded-pill bg-success-subtle text-success">
                  Add
                </span>
              </div>

              <div class="small text-body-secondary mt-1">
                FE-001 was added to the system
              </div>

              <div class="small text-body-secondary mt-2">
                <i class="bi bi-person me-1"></i>
                Jared
              </div>

            </div>

            <div class="text-end">
              <small class="text-body-secondary text-nowrap">
                5 min ago
              </small>
            </div>

          </div>
        </div>


        <!-- Update -->
        <div class="bg-white rounded-4 px-3 py-3 mb-2 border border-secondary-subtle  shadow-sm">

          <div class="d-flex align-items-center gap-3">

            <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3">
              <i class="bi bi-pencil"></i>
            </div>

            <div class="flex-grow-1">

              <div class="d-flex align-items-center gap-2">

                <span class="fw-semibold">
                  Fire Extinguisher Updated
                </span>

                <span class="badge rounded-pill bg-warning-subtle text-warning">
                  Update
                </span>

              </div>

              <div class="small text-body-secondary mt-1">
                FE-002 information was updated
              </div>

              <div class="small text-body-secondary mt-2">
                <i class="bi bi-person me-1"></i>
                Admin
              </div>

            </div>

            <div class="text-end">
              <small class="text-body-secondary text-nowrap">
                18 min ago
              </small>
            </div>

          </div>

        </div>


        <!-- Delete -->
        <div class="bg-white rounded-4 px-3 py-3 mb-2 border border-secondary-subtle  shadow-sm">

          <div class="d-flex align-items-center gap-3">

            <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-3">
              <i class="bi bi-trash"></i>
            </div>

            <div class="flex-grow-1">

              <div class="d-flex align-items-center gap-2">

                <span class="fw-semibold">
                  Fire Extinguisher Deleted
                </span>

                <span class="badge rounded-pill bg-danger-subtle text-danger">
                  Delete
                </span>

              </div>

              <div class="small text-body-secondary mt-1">
                FE-003 was removed from the system
              </div>

              <div class="small text-body-secondary mt-2">
                <i class="bi bi-person me-1"></i>
                Jared
              </div>

            </div>

            <div class="text-end">
              <small class="text-body-secondary text-nowrap">
                1 hour ago
              </small>
            </div>

          </div>

        </div>


        <!-- Inspection -->
        <div class="bg-white rounded-4 px-3 py-3 mb-2 border border-secondary-subtle  shadow-sm">

          <div class="d-flex align-items-center gap-3">

            <div class="bg-info bg-opacity-10 text-info rounded-circle p-3">
              <i class="bi bi-clipboard-check"></i>
            </div>

            <div class="flex-grow-1">

              <div class="d-flex align-items-center gap-2">

                <span class="fw-semibold">
                  Inspection Completed
                </span>

                <span class="badge rounded-pill bg-info-subtle text-info">
                  Inspection
                </span>

              </div>

              <div class="small text-body-secondary mt-1">
                FE-004 inspection was completed
              </div>

              <div class="small text-body-secondary mt-2">
                <i class="bi bi-person me-1"></i>
                Admin
              </div>

            </div>

            <div class="text-end">
              <small class="text-body-secondary text-nowrap">
                2 hours ago
              </small>
            </div>

          </div>

        </div>

        <!-- end content -->

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