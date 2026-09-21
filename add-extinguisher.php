<?php $pageTitle = "Add New Fire Extinguisher"; ?>

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

    <div class="body flex-grow-1">
      <div class="container-lg px-4">
        <div class="container py-4">
          <div class="row justify-content-center">
            <div class="col-12 col-lg-9 col-xl-8">
              <div class="card shadow-sm">
                <!-- Header -->
                <div class="card-header">
                  <h5 class="mb-0">Add Fire Extinguisher</h5>
                  <small class="text-body-secondary">
                    Enter the fire extinguisher information below.
                  </small>
                </div>


                <!-- Form -->
                <div class="card-body">

                  <?php if (isset($_GET['success'])): ?>
                    <div id="successAlert"
                      class="alert alert-success alert-dismissible fade show position-fixed end-0 m-4 shadow"
                      style="top: 20px; z-index: 9999;"
                      role="alert">

                      <strong>Success!</strong>
                      Fire extinguisher added successfully.

                      <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                      </button>

                      <div class="progress mt-2" style="height: 3px;">
                        <div id="successProgress"
                          class="progress-bar"
                          role="progressbar"
                          style="width: 100%;">
                        </div>
                      </div>
                    </div>

                    <script>
                      const progress = document.getElementById('successProgress');
                      const alert = document.getElementById('successAlert');

                      let width = 100;

                      const timer = setInterval(function() {
                        width -= 1;
                        progress.style.width = width + '%';

                        if (width <= 0) {
                          clearInterval(timer);

                          // Close Bootstrap alert
                          const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                          bsAlert.close();

                          // Remove ?success=1
                          window.history.replaceState({},
                            document.title,
                            window.location.pathname
                          );
                        }
                      }, 50);
                    </script>

                  <?php endif; ?>

                  <form class="row g-3" action="backend/controller/FireExtinguisherController.php" method="post">

                    <!-- Fire Extinguisher Code -->
                    <div class="col-md-6">

                      <label for="extinguisherCode" class="form-label">
                        Fire Extinguisher Code
                      </label>

                      <input
                        type="text"
                        class="form-control"
                        id="extinguisherCode"
                        name="extinguisher_code"
                        placeholder="e.g. E01-106"
                        required>
                    </div>


                    <!-- Type -->
                    <div class="col-md-6">

                      <label for="type" class="form-label">
                        Type
                      </label>

                      <select
                        id="type"
                        name="type"
                        class="form-select"
                        required>
                        <option value="" selected disabled>
                          Select type
                        </option>

                        <option value="ABC">ABC</option>
                        <option value="BC">BC</option>
                        <option value="CO2">CO2</option>
                        <option value="Water">Water</option>
                        <option value="Foam">Foam</option>

                      </select>

                    </div>


                    <!-- Capacity -->
                    <div class="col-md-6">

                      <label for="capacity" class="form-label">
                        Capacity
                      </label>

                      <input
                        type="text"
                        class="form-control"
                        id="capacity"
                        name="capacity"
                        placeholder="e.g. 4.5 kg"
                        required>
                    </div>


                    <!-- Location -->
                    <div class="col-md-6">

                      <label for="location" class="form-label">
                        Location
                      </label>

                      <input
                        type="text"
                        class="form-control"
                        id="location"
                        name="location"
                        placeholder="e.g. Building 1 - 2nd Floor"
                        required>

                    </div>


                    <!-- Date Acquired -->
                    <div class="col-md-6">

                      <label for="dateAcquired" class="form-label">
                        Date Acquired
                      </label>

                      <input
                        type="date"
                        class="form-control"
                        id="dateAcquired"
                        name="date_acquired"
                        required>

                    </div>


                    <!-- Expiration Date -->
                    <div class="col-md-6">

                      <label for="expirationDate" class="form-label">
                        Expiration Date
                      </label>

                      <input
                        type="date"
                        class="form-control"
                        id="expirationDate"
                        name="expiration_date"
                        required>

                    </div>


                    <!-- Status -->
                    <div class="col-md-6">

                      <label for="status" class="form-label">
                        Status
                      </label>

                      <select
                        id="status"
                        name="status"
                        class="form-select"
                        required>

                        <option value="" selected disabled>
                          Select status
                        </option>

                        <option value="active">
                          Active
                        </option>

                        <option value="For Inspection">
                          For Inspection
                        </option>

                        <option value="For Maintenance">
                          For Maintenance
                        </option>

                        <option value="Expired">
                          Expired
                        </option>

                        <option value="Retired">
                          Retired
                        </option>

                        <option value="Missing">
                          Missing
                        </option>

                      </select>

                    </div>


                    <!-- Buttons -->
                    <div class="col-12">
                      <hr class="my-2">
                      <div class="d-flex justify-content-end gap-2">

                        <button
                          type="submit"
                          class="btn btn-primary">
                          Add Fire Extinguisher
                        </button>

                      </div>
                    </div>
                  </form>

                  
                </div>
              </div>
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