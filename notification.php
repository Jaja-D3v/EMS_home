<?php $pageTitle = "Notification"; ?>

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

        CONTENT HERE
        <!-- Notification Navigation -->
        <div class="container-fluid py-3">

          <!-- Page Header -->
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h3 class="fw-bold mb-1">
                <i class="bi bi-bell-fill text-danger me-2"></i>
                Notifications
              </h3>
              <p class="text-muted mb-0">
                Stay updated with your fire extinguisher management system.
              </p>
            </div>

            <button class="btn btn-outline-secondary">
              <i class="bi bi-check2-all me-1"></i>
              Mark all as read
            </button>
          </div>


          <!-- Notification Summary -->
          <div class="row g-3 mb-4">

            <!-- Expiring -->
            <div class="col-md-4">
              <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">

                  <div class="bg-danger bg-opacity-10 rounded-3 p-3 me-3">
                    <i class="bi bi-calendar-x-fill text-danger fs-3"></i>
                  </div>

                  <div>
                    <h6 class="text-muted mb-1">
                      Expiring Soon
                    </h6>
                    <h3 class="fw-bold mb-0">12</h3>
                    <small class="text-muted">
                      Within 2 months
                    </small>
                  </div>

                </div>
              </div>
            </div>


            <!-- Inspection -->
            <div class="col-md-4">
              <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">

                  <div class="bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                    <i class="bi bi-clipboard-check-fill text-warning fs-3"></i>
                  </div>

                  <div>
                    <h6 class="text-muted mb-1">
                      Inspections Due
                    </h6>
                    <h3 class="fw-bold mb-0">5</h3>
                    <small class="text-muted">
                      Need inspection
                    </small>
                  </div>

                </div>
              </div>
            </div>


            <!-- System -->
            <div class="col-md-4">
              <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">

                  <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                    <i class="bi bi-info-circle-fill text-primary fs-3"></i>
                  </div>

                  <div>
                    <h6 class="text-muted mb-1">
                      System Alerts
                    </h6>
                    <h3 class="fw-bold mb-0">3</h3>
                    <small class="text-muted">
                      New notifications
                    </small>
                  </div>

                </div>
              </div>
            </div>

          </div>


          <!-- Notifications Card -->
          <div class="card border-0 shadow-sm">

            <!-- Card Header -->
            <div class="card-header bg-white border-bottom py-3">

              <div class="d-flex justify-content-between align-items-center">

                <h5 class="fw-bold mb-0">
                  Recent Notifications
                </h5>

                <div class="dropdown">
                  <button
                    class="btn btn-sm btn-light border dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown">
                    All Notifications
                  </button>

                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        All Notifications
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        Expiring
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        Inspection
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        System
                      </a>
                    </li>
                  </ul>
                </div>

              </div>

            </div>


            <!-- Notification List -->
            <div class="list-group list-group-flush">


              <!-- Notification 1 -->
              <a href="#"
                class="list-group-item list-group-item-action py-3">

                <div class="d-flex align-items-start">

                  <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                    <i class="bi bi-fire text-danger"></i>
                  </div>

                  <div class="flex-grow-1">

                    <div class="d-flex justify-content-between">

                      <h6 class="fw-bold mb-1">
                        Fire Extinguisher Expiring Soon
                      </h6>

                      <small class="text-muted">
                        10 mins ago
                      </small>

                    </div>

                    <p class="text-muted mb-1">
                      Fire extinguisher
                      <strong>E01-106</strong>
                      will expire within 2 months.
                    </p>

                    <span class="badge bg-danger">
                      Expiring
                    </span>

                  </div>

                </div>

              </a>


              <!-- Notification 2 -->
              <a href="#"
                class="list-group-item list-group-item-action py-3">

                <div class="d-flex align-items-start">

                  <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                    <i class="bi bi-clipboard-check text-warning"></i>
                  </div>

                  <div class="flex-grow-1">

                    <div class="d-flex justify-content-between">

                      <h6 class="fw-bold mb-1">
                        Inspection Required
                      </h6>

                      <small class="text-muted">
                        1 hour ago
                      </small>

                    </div>

                    <p class="text-muted mb-1">
                      Fire extinguisher
                      <strong>E02-114</strong>
                      is due for inspection.
                    </p>

                    <span class="badge bg-warning text-dark">
                      Inspection
                    </span>

                  </div>

                </div>

              </a>


              <!-- Notification 3 -->
              <a href="#"
                class="list-group-item list-group-item-action py-3">

                <div class="d-flex align-items-start">

                  <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                    <i class="bi bi-check-circle-fill text-success"></i>
                  </div>

                  <div class="flex-grow-1">

                    <div class="d-flex justify-content-between">

                      <h6 class="fw-bold mb-1">
                        Inspection Completed
                      </h6>

                      <small class="text-muted">
                        3 hours ago
                      </small>

                    </div>

                    <p class="text-muted mb-1">
                      Fire extinguisher
                      <strong>E03-121</strong>
                      was successfully inspected.
                    </p>

                    <span class="badge bg-success">
                      Completed
                    </span>

                  </div>

                </div>

              </a>


              <!-- Notification 4 -->
              <a href="#"
                class="list-group-item list-group-item-action py-3">

                <div class="d-flex align-items-start">

                  <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                    <i class="bi bi-person-plus-fill text-primary"></i>
                  </div>

                  <div class="flex-grow-1">

                    <div class="d-flex justify-content-between">

                      <h6 class="fw-bold mb-1">
                        New Inspector Added
                      </h6>

                      <small class="text-muted">
                        Yesterday
                      </small>

                    </div>

                    <p class="text-muted mb-1">
                      A new inspector account was added to the system.
                    </p>

                    <span class="badge bg-primary">
                      System
                    </span>

                  </div>

                </div>

              </a>

            </div>


            <!-- Footer -->
            <div class="card-footer bg-white text-center py-3">

              <a href="#" class="text-decoration-none fw-semibold">
                View all notifications
                <i class="bi bi-arrow-right ms-1"></i>
              </a>

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