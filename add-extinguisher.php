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
<?php include 'partials/header.php'; ?>

<body>
  <?php include 'partials/side-nav.php'; ?>
  <div class="wrapper d-flex flex-column min-vh-100">
    <?php include 'partials/header-nav.php'; ?>

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

                  <?php include_once 'notification/added_fe_success.php' ?>
                  <!-- form for add new extinguisher -->
                  <form
                    class="row g-3"
                    action="backend/controller/FireExtinguisherController.php"
                    method="post">

                    <!-- tell the controller that this is update -->
                    <input type="hidden" name="action" value="add">

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
                        <option value="" selected disabled>Select type</option>
                        <option value="Dry Chemical">Dry Chemical</option>
                        <option value="CO2">CO2</option>
                        <option value="Water">Water</option>
                        <option value="Foam">Foam</option>
                        <option value="Wet Chemical">Wet Chemical</option>
                        <option value="HCFC-123">HCFC-123</option>
                      </select>
                    </div>

                    <!-- Capacity -->
                    <div class="col-md-4">
                      <label for="capacity" class="form-label">
                        Capacity
                      </label>

                      <input
                        type="text"
                        class="form-control"
                        id="capacity"
                        name="capacity"
                        list="capacityOptions"
                        placeholder="Select or type capacity"
                        required>

                      <datalist id="capacityOptions">
                        <option value="10 lbs">
                        <option value="20 lbs">
                        <option value="50 lbs">
                      </datalist>
                    </div>

                    <!-- Class -->
                    <div class="col-md-4">
                      <label for="fireClass" class="form-label">
                        Fire Class
                      </label>
                      <select
                        id="fireClass"
                        name="class"
                        class="form-select"
                        required>
                        <option value="" selected disabled>Select class</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="AB">AB</option>
                        <option value="ABC">ABC</option>
                        <option value="BC">BC</option>
                      </select>
                    </div>

                    <!-- Placement -->
                    <div class="col-md-4">
                      <label for="placement" class="form-label">
                        Placement
                      </label>

                      <input
                        type="text"
                        id="placement"
                        name="placement"
                        class="form-control"
                        list="placementOptions"
                        placeholder="Select or type placement"
                        required>

                      <datalist id="placementOptions">
                        <option value="Wall Mounted">
                        <option value="Floor Standing">
                        <option value="Cabinet">
                        <option value="Vehicle">
                      </datalist>
                    </div>

                    <!-- Location -->
                    <div class="col-md-8">
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

                    <!-- Condition Status -->
                    <div class="col-md-4">
                      <label for="conditionStatus" class="form-label">
                        Condition
                      </label>
                      <select
                        id="conditionStatus"
                        name="condition_status"
                        class="form-select"
                        required>
                        <option value="" selected disabled>Select condition</option>
                        <option value="Good">Good</option>
                        <option value="Not Good">Not Good</option>
                      </select>
                    </div>

                    <!-- Manufactured Date -->
                    <div class="col-md-6">
                      <label for="manufacturedDate" class="form-label">
                        Manufactured Date
                      </label>

                      <input
                        type="date"
                        class="form-control"
                        id="manufacturedDate"
                        name="manufactured_date">
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
                        required
                        readonly>
                    </div>
                    <div class="text-end">
                      <small class="text-body-secondary">
                        Automatically computed as 3 years from the manufactured date.
                      </small>
                    </div>

                    <!-- Remarks -->
                    <div class="col-12">
                      <label for="remarks" class="form-label">
                        Remarks
                      </label>
                      <textarea
                        class="form-control"
                        id="remarks"
                        name="remarks"
                        rows="2"
                        placeholder="Additional remarks (optional)"></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="col-12">
                      <div class="d-flex justify-content-end gap-2 pt-1">
                        <button
                          type="reset"
                          class="btn btn-light border">
                          Clear
                        </button>

                        <button
                          type="submit"
                          class="btn btn-primary px-4">
                          <i class="bi bi-plus-lg me-1"></i>
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



    // LOGIC FOR EXPIRATION DATE AUTO FILL BASED ON MANUFACTURE DATE
    const manufacturedDate = document.getElementById('manufacturedDate');
    const expirationDate = document.getElementById('expirationDate');

    manufacturedDate.addEventListener('change', function() {

      if (!this.value) {
        expirationDate.value = '';
        return;
      }

      const date = new Date(this.value + 'T00:00:00');

      // Add 3 years
      date.setFullYear(date.getFullYear() + 3);

      // Format to YYYY-MM-DD
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');

      expirationDate.value = `${year}-${month}-${day}`;
    });
  </script>
</body>

</html>