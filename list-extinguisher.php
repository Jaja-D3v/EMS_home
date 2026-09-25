<?php
$pageTitle = "Add New Fire Extinguisher";
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
<?php include 'partials/header.php'; ?>

<body>
  <?php include 'partials/side-nav.php'; ?>
  <div class="wrapper d-flex flex-column min-vh-100">
    <?php
    include 'partials/header-nav.php';
    include_once 'notification/updated_fe_success.php';
    include_once 'notification/delete_fe_success.php';
    include_once 'notification/added_fe_success.php';
    ?>


    <div class="body flex-grow-1">
      <div class="container-lg px-4">
        <div class="container py-4">
          <div class="row justify-content-center">

            <!-- Content -->

            <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-3 mb-3">

              <!-- Sort - Left -->
              <div>
                <select id="sort" class="form-select">
                  <option value="newest">Sort by</option>
                  <option value="newest">Newest</option>
                  <option value="oldest">Oldest</option>
                  <option value="name-asc">Name A-Z</option>
                  <option value="name-desc">Name Z-A</option>
                </select>
              </div>

              <!-- Add + Search - Right -->
              <div class="d-flex flex-column flex-md-row gap-2 ms-lg-auto">

                <button
                  type="button"
                  class="btn btn-primary text-nowrap"
                  data-bs-toggle="modal"
                  data-bs-target="#addFireExtinguisherModal">
                  <i class="bi bi-plus-lg me-1"></i>
                  Add Fire Extinguisher
                </button>

                <div class="input-group">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Search...">

                  <button
                    class="btn btn-primary"
                    type="button">
                    Search
                  </button>
                </div>

                <script>
                  // ========================================
                  // SEARCH BY CODE OR LOCATION
                  // ========================================

                  const searchInput = document.querySelector(
                    'input[placeholder="Search..."]'
                  );

                  if (searchInput) {

                    searchInput.addEventListener('input', function() {

                      const searchValue = this.value
                        .trim()
                        .toLowerCase();

                      const cards = document.querySelectorAll(
                        '.card.border-1.shadow-sm.mb-1'
                      );

                      let visibleCount = 0;

                      cards.forEach(card => {

                        // FE Code
                        const codeElement = card.querySelector(
                          '.card-header small'
                        );

                        const code = codeElement ?
                          codeElement.textContent.trim().toLowerCase() :
                          '';

                        // Location
                        const locationElement = card.querySelector(
                          '.bi-geo-alt'
                        );

                        const location = locationElement ?
                          locationElement.parentElement.textContent
                          .trim()
                          .toLowerCase() :
                          '';

                        // Check match
                        const match =
                          code.includes(searchValue) ||
                          location.includes(searchValue);

                        if (match) {
                          card.style.display = '';
                          visibleCount++;
                        } else {
                          card.style.display = 'none';
                        }

                      });

                      // ========================================
                      // NO RESULT FOUND
                      // ========================================

                      let noResult = document.getElementById(
                        'noSearchResult'
                      );

                      if (visibleCount === 0 && searchValue !== '') {

                        if (!noResult) {

                          noResult = document.createElement('div');

                          noResult.id = 'noSearchResult';

                          noResult.className =
                            'border rounded-3 text-center text-body-secondary py-5 px-3 my-3 shadow-sm';

                          noResult.innerHTML = `
                            <div class="py-3">
                                <i class="bi bi-search fs-1 d-block mb-3"></i>

                                <div class="fw-semibold fs-6">
                                    No fire extinguishers found.
                                </div>

                                <small class="text-body-secondary">
                                    No results match your search.
                                </small>
                            </div>
                            `;

                          searchInput
                            .closest('.d-flex.flex-column.flex-md-row')
                            .parentElement
                            .parentElement
                            .after(noResult);
                        }

                        noResult.style.display = '';

                      } else if (noResult) {

                        noResult.style.display = 'none';

                      }

                    });

                  }
                </script>

              </div>

            </div>

            <!-- Content here -->
            <?php foreach ($info as $data): ?>

              <div class="card border-1 shadow-sm mb-1 ">

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

                    <button
                      type="button"
                      class="btn btn-sm btn-outline-warning edit-extinguisher-btn"
                      data-id="<?= htmlspecialchars($data['extinguisher_id']) ?>"
                      data-bs-toggle="modal"
                      data-bs-target="#editFireExtinguisherModal">

                      <i class="bi bi-pencil me-1"></i>
                      Edit

                    </button>

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

            <!-- this is for edit form extinguisher -->


            <div
              class="modal fade"
              id="editFireExtinguisherModal"
              tabindex="-1"
              aria-labelledby="editFireExtinguisherModalLabel"
              aria-hidden="true">

              <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

                <div class="modal-content rounded-4 border-0 shadow">

                  <!-- Header -->
                  <div class="modal-header px-4 py-3">

                    <div>
                      <h5
                        class="modal-title fw-semibold mb-1"
                        id="editFireExtinguisherModalLabel">
                        Edit Fire Extinguisher
                      </h5>

                      <small class="text-body-secondary">
                        Update the fire extinguisher information below.
                      </small>
                    </div>

                    <button
                      type="button"
                      class="btn-close"
                      data-bs-dismiss="modal"
                      aria-label="Close">
                    </button>

                  </div>


                  <!-- Body -->
                  <div class="modal-body px-4 py-4">

                    <form
                      id="editFireExtinguisherForm"
                      class="row g-3"
                      action="backend/controller/FireExtinguisherController.php"
                      method="post">

                      <input
                        type="hidden"
                        name="action"
                        value="update">

                      <input
                        type="hidden"
                        id="editExtinguisherId"
                        name="extinguisher_id">



                      <!-- Fire Extinguisher Code -->
                      <div class="col-md-6">

                        <label
                          for="editExtinguisherCode"
                          class="form-label">
                          Fire Extinguisher Code
                        </label>

                        <input
                          type="text"
                          class="form-control"
                          id="editExtinguisherCode"
                          name="extinguisher_code"
                          value=" "
                          readonly
                          required>

                      </div>


                      <!-- Type -->
                      <div class="col-md-6">

                        <label
                          for="editType"
                          class="form-label">
                          Type
                        </label>

                        <select
                          id="editType"
                          name="type"
                          class="form-select"
                          required>

                          <option
                            value=""
                            selected
                            readonly>

                          </option>

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

                        <label
                          for="editCapacity"
                          class="form-label">
                          Capacity
                        </label>

                        <input
                          type="text"
                          class="form-control"
                          id="editCapacity"
                          name="capacity"
                          list="editCapacityOptions"
                          value="  "
                          required>

                        <datalist id="editCapacityOptions">
                          <option value="10 lbs">
                          <option value="20 lbs">
                          <option value="50 lbs">
                        </datalist>

                      </div>


                      <!-- Class -->
                      <div class="col-md-4">

                        <label
                          for="editClass"
                          class="form-label">
                          Fire Class
                        </label>

                        <select
                          id="editClass"
                          name="class"
                          class="form-select"
                          required>

                          <option
                            value=" "
                            selected>

                          </option>

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

                        <label
                          for="editPlacement"
                          class="form-label">
                          Placement
                        </label>

                        <input
                          type="text"
                          class="form-control"
                          id="editPlacement"
                          name="placement"
                          list="editPlacementOptions"
                          value=" "
                          required>

                        <datalist id="editPlacementOptions">
                          <option value="Wall Mounted">
                          <option value="Floor Standing">
                          <option value="Cabinet">
                          <option value="Vehicle">
                        </datalist>

                      </div>


                      <!-- Location -->
                      <div class="col-md-8">

                        <label
                          for="editLocation"
                          class="form-label">
                          Location
                        </label>

                        <input
                          type="text"
                          class="form-control"
                          id="editLocation"
                          name="location"
                          value=" "
                          required>

                      </div>


                      <!-- Condition -->
                      <div class="col-md-4">

                        <label
                          for="editConditionStatus"
                          class="form-label">
                          Condition
                        </label>

                        <select
                          id="editConditionStatus"
                          name="condition_status"
                          class="form-select"
                          required>

                          <option
                            value=" "
                            selected>

                          </option>

                          <option value="Good">Good</option>
                          <option value="Not Good">Not Good</option>

                        </select>

                      </div>


                      <!-- Manufactured Date -->
                      <div class="col-md-6">

                        <label
                          for="editManufacturedDate"
                          class="form-label">
                          Manufactured Date
                        </label>

                        <input
                          type="date"
                          class="form-control"
                          id="editManufacturedDate"
                          name="manufactured_date"
                          value=""
                          readonly>


                      </div>


                      <!-- Expiration Date -->
                      <div class="col-md-6">

                        <label
                          for="editExpirationDate"
                          class="form-label">
                          Expiration Date
                        </label>

                        <input
                          type="date"
                          class="form-control"
                          id="editExpirationDate"
                          name="expiration_date"
                          value=" "
                          readonly
                          required>

                        <small class="text-body-secondary">
                          Automatically calculated as 3 years from manufactured date.
                        </small>

                      </div>


                      <!-- Remarks -->
                      <div class="col-12">

                        <label
                          for="editRemarks"
                          class="form-label">
                          Remarks
                        </label>

                        <textarea
                          class="form-control"
                          id="editRemarks"
                          name="remarks"
                          rows="2"
                          placeholder="Additional remarks (optional)"> </textarea>

                      </div>

                    </form>

                  </div>


                  <!-- Footer -->
                  <div class="modal-footer px-4 py-3">

                    <button
                      type="button"
                      class="btn btn-light border"
                      data-bs-dismiss="modal">
                      Cancel
                    </button>

                    <button
                      type="submit"
                      form="editFireExtinguisherForm"
                      class="btn btn-warning px-4">
                      <i class="bi bi-check-lg me-1"></i>
                      Update Info
                    </button>

                  </div>

                </div>

              </div>

            </div>

            <!-- this is for edit extinguisher end -->


            <!-- this form is for add new extinguisher -->
            <div
              class="modal fade"
              id="addFireExtinguisherModal"
              tabindex="-1"
              aria-labelledby="addFireExtinguisherModalLabel"
              aria-hidden="true">
              <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

                <div class="modal-content rounded-4 border-0 shadow">

                  <!-- Header -->
                  <div class="modal-header px-4 py-3">
                    <div>
                      <h5 class="modal-title fw-semibold mb-1" id="addFireExtinguisherModalLabel">
                        Add Fire Extinguisher
                      </h5>

                      <small class="text-body-secondary">
                        Register a new fire extinguisher
                      </small>
                    </div>

                    <button
                      type="button"
                      class="btn-close"
                      data-bs-dismiss="modal"
                      aria-label="Close"></button>
                  </div>


                  <!-- Body -->
                  <div class="modal-body px-4 py-4">

                    <form
                      class="row g-3"
                      action="backend/controller/FireExtinguisherController.php"
                      method="post">

                      <input
                        type="hidden"
                        name="action"
                        value="add">


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
                          placeholder="e.g. FE-001"
                          required>

                        <div id="extinguisherCodeFeedback" class="small mt-1"></div>
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
                          <option value="" selected disabled>
                            Select class
                          </option>
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

                      <!-- Condition -->
                      <div class="col-md-4">
                        <label for="conditionStatus" class="form-label">
                          Condition
                        </label>

                        <select
                          id="conditionStatus"
                          name="condition_status"
                          class="form-select"
                          required>
                          <option value="" selected disabled>
                            Select condition
                          </option>
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

                      <div class="col-12 text-end">
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

                      <!-- Footer -->
                      <div class="modal-footer px-4 py-3">

                        <button
                          type="button"
                          class="btn btn-light border"
                          data-bs-dismiss="modal">
                          Cancel
                        </button>

                        <button
                          type="submit"
                          id="addFireExtinguisherBtn"
                          class="btn btn-primary px-4">
                          <i class="bi bi-plus-lg me-1"></i>
                          Add Fire Extinguisher
                        </button>

                      </div>

                    </form>

                  </div>




                </div>
              </div>
            </div>
            <script>
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
            <!-- end form is for add new extinguisher -->


            <!-- End content -->

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


    // this js is for edit form 
    document.querySelectorAll('.edit-extinguisher-btn').forEach(button => {

      button.addEventListener('click', function() {

        const id = this.dataset.id;

        fetch(`backend/controller/FireExtinguisherController.php?action=get&id=${id}`)
          .then(response => response.json())
          .then(result => {

            if (!result.success) {
              alert('Failed to load fire extinguisher.');
              return;
            }

            const data = result.data;

            document.getElementById('editExtinguisherId').value =
              data.extinguisher_id;

            document.getElementById('editExtinguisherCode').value =
              data.extinguisher_code;

            document.getElementById('editType').value =
              data.type;

            document.getElementById('editCapacity').value =
              data.capacity;

            document.getElementById('editClass').value =
              data.class;

            document.getElementById('editPlacement').value =
              data.placement;

            document.getElementById('editLocation').value =
              data.location;

            document.getElementById('editConditionStatus').value =
              data.condition_status;

            document.getElementById('editManufacturedDate').value =
              data.manufactured_date;

            document.getElementById('editExpirationDate').value =
              data.expiration_date;

            document.getElementById('editRemarks').value =
              data.remarks ?? '';

          })
          .catch(error => {
            console.error(error);
            alert('Something went wrong.');
          });

      });

    });


    // ========================================
    // FIRE EXTINGUISHER CODE
    // ========================================

    const codeInput = document.getElementById('extinguisherCode');
    const codeFeedback = document.getElementById('extinguisherCodeFeedback');
    const addFireExtinguisherBtn = document.getElementById('addFireExtinguisherBtn');

    let codeCheckTimeout;
    let isCodeDuplicate = false;


    // ========================================
    // CHECK IF CODE EXISTS
    // ========================================

    function checkFireExtinguisherCode(code) {

      clearTimeout(codeCheckTimeout);

      codeFeedback.textContent = '';
      codeFeedback.className = 'small mt-1';

      // Default: allow submit
      isCodeDuplicate = false;
      addFireExtinguisherBtn.disabled = false;

      // Empty code
      if (code === '') {

        codeInput.classList.remove(
          'is-valid',
          'is-invalid'
        );

        return;
      }


      // Delay checking
      codeCheckTimeout = setTimeout(() => {

        fetch(
            `backend/controller/FireExtinguisherController.php?action=checkCode&code=${encodeURIComponent(code)}`
          )
          .then(response => {

            if (!response.ok) {
              throw new Error(
                `HTTP error: ${response.status}`
              );
            }

            return response.json();

          })
          .then(result => {

            console.log('Code check result:', result);


            // ========================================
            // DUPLICATE
            // ========================================

            if (result.exists === true) {

              isCodeDuplicate = true;

              codeFeedback.textContent =
                'This fire extinguisher code already exists.';

              codeFeedback.className =
                'small mt-1 text-danger';

              codeInput.classList.add(
                'is-invalid'
              );

              codeInput.classList.remove(
                'is-valid'
              );

              // DISABLE ADD BUTTON
              addFireExtinguisherBtn.disabled = true;

            }


            // ========================================
            // AVAILABLE
            // ========================================
            else {

              isCodeDuplicate = false;

              codeFeedback.textContent =
                'Fire extinguisher code is available.';

              codeFeedback.className =
                'small mt-1 text-success';

              codeInput.classList.remove(
                'is-invalid'
              );

              codeInput.classList.add(
                'is-valid'
              );

              // ENABLE ADD BUTTON
              addFireExtinguisherBtn.disabled = false;

            }

          })
          .catch(error => {

            console.error(
              'Code check error:',
              error
            );

            isCodeDuplicate = false;

            codeFeedback.textContent =
              'Unable to check fire extinguisher code.';

            codeFeedback.className =
              'small mt-1 text-warning';

            codeInput.classList.remove(
              'is-valid',
              'is-invalid'
            );

            // Disable while checking has failed
            addFireExtinguisherBtn.disabled = true;

          });

      }, 400);
    }


    // ========================================
    // MANUAL CODE INPUT
    // ========================================

    codeInput.addEventListener('input', function() {

      const code = this.value.trim();

      checkFireExtinguisherCode(code);

    });


    // ========================================
    // AUTO-GENERATE FIRE EXTINGUISHER CODE
    // ========================================

    function generateFireExtinguisherCode() {

      fetch(
          'backend/controller/FireExtinguisherController.php?action=getNextCode'
        )
        .then(response => {

          if (!response.ok) {

            throw new Error(
              `HTTP error: ${response.status}`
            );

          }

          return response.json();

        })
        .then(result => {

          console.log(
            'Generated code:',
            result
          );


          if (result.success) {

            // Put generated code into input
            codeInput.value = result.code;

            // Check generated code
            checkFireExtinguisherCode(
              result.code
            );

          } else {

            console.error(
              'Failed to generate fire extinguisher code.'
            );

            addFireExtinguisherBtn.disabled = true;

          }

        })
        .catch(error => {

          console.error(
            'Generate code error:',
            error
          );

          addFireExtinguisherBtn.disabled = true;

        });

    }


    // ========================================
    // AUTO-GENERATE WHEN ADD MODAL OPENS
    // ========================================

    const addFireExtinguisherModal =
      document.getElementById(
        'addFireExtinguisherModal'
      );


    if (addFireExtinguisherModal) {

      addFireExtinguisherModal.addEventListener(
        'shown.bs.modal',
        function() {

          // Generate only if empty
          if (codeInput.value.trim() === '') {

            // Disable while generating/checking
            addFireExtinguisherBtn.disabled = true;

            generateFireExtinguisherCode();

          } else {

            checkFireExtinguisherCode(
              codeInput.value.trim()
            );

          }

        }
      );

    }

    // ========================================
    // PREVENT SUBMIT IF DUPLICATE
    // ========================================

    const addForm = addFireExtinguisherBtn.closest('form');

    if (addForm) {

      addForm.addEventListener('submit', function(event) {

        const code = codeInput.value.trim();


        // Empty code
        if (code === '') {

          event.preventDefault();

          codeFeedback.textContent =
            'Fire extinguisher code is required.';

          codeFeedback.className =
            'small mt-1 text-danger';

          codeInput.classList.add(
            'is-invalid'
          );

          return;
        }


        // Duplicate code
        if (isCodeDuplicate) {

          event.preventDefault();

          codeFeedback.textContent =
            'This fire extinguisher code already exists.';

          codeFeedback.className =
            'small mt-1 text-danger';

          codeInput.classList.add(
            'is-invalid'
          );

          addFireExtinguisherBtn.disabled = true;

          return;
        }

      });

    }
  </script>
</body>

</html>