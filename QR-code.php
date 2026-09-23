<?php $pageTitle = "Scan QR Code"; ?>
<?php include_once 'notification/inspect_fe_success.php'; ?>


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
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-12 col-md-8 col-lg-6 col-xl-5">

            <div class="card">
              <div class="card-header">
                <strong>Scan Fire Extinguisher QR Code</strong>
              </div>

              <div class="card-body">

                <div id="qr-reader" class="w-100 overflow-hidden"></div>

                <div id="scan-result" class="mt-3"></div>

              </div>
            </div>

          </div>
        </div>
      </div>

      <script>
        const scanner = new Html5Qrcode("qr-reader");

        function startScanner() {

          Html5Qrcode.getCameras()
            .then(cameras => {

              if (!cameras || cameras.length === 0) {

                document.getElementById("scan-result").innerHTML = `
                        <div class="alert alert-danger">
                            No camera found on this device.
                        </div>
                    `;

                return;
              }

              const cameraId = cameras[0].id;

              scanner.start(
                cameraId, {
                  fps: 10,
                  qrbox: {
                    width: 350,
                    height: 250
                  }
                },

                qrCodeMessage => {

                  if (qrCodeMessage) {

                    fetch(
                        `backend/controller/FireExtinguisherController.php?action=getByCode&code=${encodeURIComponent(qrCodeMessage)}`
                      )
                      .then(response => response.json())
                      .then(result => {

                        if (!result.success) {

                          document.getElementById("scan-result").innerHTML = `
                            <div class="alert alert-danger">
                                <div class="fw-bold">
                                    QR Code Validation
                                </div>

                                <div class="small text-body-secondary">
                                    Invalid QR code. Fire extinguisher not found.
                                </div>
                            </div>
                        `;

                          return;
                        }

                        // Valid QR
                        const data = result.data;

                        document.getElementById("extinguisherCode").value = data.extinguisher_code;
                        document.getElementById("inspectionLocation").value = data.location;
                        document.getElementById("inspectionCapacity").value = data.capacity;
                        document.getElementById("inspectionType").value = data.type;
                        document.getElementById("inspectionClass").value = data.class;

                        const inspectionModal = new bootstrap.Modal(
                          document.getElementById("inspectionChecklistModal")
                        );

                        inspectionModal.show();

                        // scanner.stop();
                      })
                      .catch(error => {

                        console.error("Error:", error);

                        document.getElementById("scan-result").innerHTML = `
                            <div class="alert alert-danger">
                                <div class="fw-bold">
                                    QR Code Validation
                                </div>

                                <div class="small text-body-secondary">
                                    Unable to validate QR code. Please try again.
                                </div>
                            </div>
                        `;
                      });
                  }
                },

                errorMessage => {
                  // QR not detected
                }
              );

            })

            .catch(() => {

              document.getElementById("scan-result").innerHTML = `
                    <div class="alert alert-danger">
                        Camera access was denied or unavailable.
                    </div>
                `;

            });
        }

        startScanner();
      </script>

      <!-- =========================
            INSPECTION CHECKLIST MODAL
        ========================== -->

      <div
        class="modal fade"
        id="inspectionChecklistModal"
        tabindex="-1"
        aria-labelledby="inspectionChecklistModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

          <div class="modal-content border-0 rounded-4 shadow">


            <!-- =========================
                        MODAL HEADER
                    ========================== -->

            <div class="modal-header px-4 py-3 border-bottom">

              <div class="d-flex align-items-center gap-3">

                <div class="bg-danger-subtle text-danger rounded-3 p-3">
                  <i class="bi bi-clipboard2-check fs-4"></i>
                </div>

                <div>

                  <h5
                    class="modal-title fw-bold mb-1"
                    id="inspectionChecklistModalLabel">
                    Inspection Checklist
                  </h5>

                  <small class="text-body-secondary">
                    Inspect the fire extinguisher and record the condition of each item.
                  </small>

                </div>

              </div>

              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
                onclick="location.reload()"></button>

            </div>


            <!-- =========================
                        MODAL BODY
                    ========================== -->

            <div class="modal-body px-4 py-4">

              <form
                id="inspectionChecklistForm"
                action="backend/controller/InspectionController.php"
                method="post">

                <input
                  type="hidden"
                  name="action"
                  value="add">


                <!-- =========================
                                SECTION 1
                                BASIC INFORMATION
                            ========================== -->

                <div class="border rounded-4 p-4 mb-4">

                  <div class="d-flex align-items-center gap-3 mb-4">

                    <div
                      class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                      style="width: 34px; height: 34px;">
                      1
                    </div>

                    <div>

                      <h6 class="fw-bold mb-0">
                        Basic Information
                      </h6>

                      <small class="text-body-secondary">
                        Fire extinguisher details
                      </small>



                    </div>

                  </div>


                  <div class="row g-3">


                    <!-- FE CODE -->

                    <div class="col-12 col-md-6 col-lg-4">

                      <label
                        for="extinguisherCode"
                        class="form-label fw-semibold">
                        Fire Extinguisher Code
                      </label>

                      <div class="input-group">

                        <span class="input-group-text bg-body">
                          <i class="bi bi-qr-code"></i>
                        </span>

                        <input
                          type="text"
                          class="form-control"
                          id="extinguisherCode"
                          name="extinguisher_code"
                          placeholder="FE-0001"
                          readonly
                          required>

                      </div>

                    </div>


                    <!-- LOCATION -->

                    <div class="col-12 col-md-6 col-lg-4">

                      <label
                        for="inspectionLocation"
                        class="form-label fw-semibold">
                        Location
                      </label>

                      <div class="input-group">

                        <span class="input-group-text bg-body">
                          <i class="bi bi-geo-alt"></i>
                        </span>

                        <input
                          type="text"
                          class="form-control"
                          id="inspectionLocation"
                          name="location"
                          placeholder="Building A - 1st Floor"
                          readonly
                          required>

                      </div>

                    </div>


                    <!-- CAPACITY -->

                    <div class="col-12 col-md-6 col-lg-4">

                      <label
                        for="inspectionCapacity"
                        class="form-label fw-semibold">
                        Capacity
                      </label>

                      <input
                        type="text"
                        class="form-control"
                        id="inspectionCapacity"
                        name="capacity"
                        placeholder="capacity"
                        readonly
                        required>



                    </div>


                    <!-- TYPE -->

                    <div class="col-12 col-md-6 col-lg-4">

                      <label
                        for="inspectionType"
                        class="form-label fw-semibold">
                        Type
                      </label>

                      <input
                        type="text"
                        class="form-control"
                        id="inspectionType"
                        name="type"
                        placeholder="Type"
                        readonly
                        required>

                    </div>


                    <!-- CLASS -->

                    <div class="col-12 col-md-6 col-lg-4">

                      <label
                        for="inspectionClass"
                        class="form-label fw-semibold">
                        Class
                      </label>

                      <input
                        type="text"
                        class="form-control"
                        id="inspectionClass"
                        name="class"
                        placeholder="Class"
                        readonly
                        required>
                    </div>


                    <!-- DATE INSPECTED -->

                    <div class="col-12 col-md-6 col-lg-4">

                      <label
                        for="dateInspected"
                        class="form-label fw-semibold">
                        Date of Inspection
                      </label>

                      <input
                        type="date"
                        class="form-control"
                        id="dateInspected"
                        name="date_inspected"
                        readonly
                        required>

                    </div>


                    <!-- INSPECTED BY -->

                    <div class="col-12 col-md-6 col-lg-4">

                      <label
                        for="inspectedBy"
                        class="form-label fw-semibold">
                        Inspected By
                      </label>

                      <input
                        type="text"
                        class="form-control"
                        id="inspectedBy"
                        name="inspected_by"
                        placeholder="Inspector name"
                        required>

                    </div>


                    <!-- VERIFIED AND APPROVED -->

                    <div class="col-12 col-md-6 col-lg-4">

                      <label
                        for="verifiedAndApprovedBy"
                        class="form-label fw-semibold">
                        Verified & Approved By
                      </label>

                      <input
                        type="text"
                        class="form-control"
                        id="verifiedAndApprovedBy"
                        name="verified_and_approved_by"
                        placeholder="Approver name"
                        required>

                    </div>


                    <!-- ACTION TAKEN -->

                    <div class="col-12 col-md-6 col-lg-4">

                      <label
                        for="actionTaken"
                        class="form-label fw-semibold">
                        Action Taken
                      </label>

                      <select
                        class="form-select"
                        id="actionTaken"
                        name="action_taken"
                        required>

                        <option
                          value=""
                          selected
                          disabled>
                          Select action
                        </option>

                        <option value="No Action">
                          No Action
                        </option>

                        <option value="Refilled">
                          Refilled
                        </option>

                        <option value="Repaired">
                          Repaired
                        </option>

                        <option value="Replaced">
                          Replaced
                        </option>

                        <option value="For Monitoring">
                          For Monitoring
                        </option>

                      </select>

                    </div>


                    <!-- TARGET DATE -->

                    <div class="col-12 col-md-6 col-lg-4">

                      <label
                        for="targetDate"
                        class="form-label fw-semibold">
                        Target Date of Implementation
                      </label>

                      <input
                        type="date"
                        class="form-control"
                        id="targetDate"
                        name="target_date_of_implementation"
                        required>

                    </div>

                  </div>

                </div>


                <!-- =========================
                                    SECTION 2
                                    INSPECTION CHECKLIST
                                ========================== -->

                <div class="border rounded-4 p-4 mb-4">

                  <div class="d-flex align-items-center gap-3 mb-2">

                    <div
                      class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                      style="width: 34px; height: 34px;">
                      2
                    </div>

                    <div>

                      <h6 class="fw-bold mb-0">
                        Inspection Checklist
                      </h6>



                      <small class="text-body-secondary">
                        Check the box if the item is Not Good.
                      </small>




                    </div>
                    <span
                      id="inspectionConditionStatus"
                      class="badge bg-success ms-auto">
                      Good
                    </span>

                  </div>


                  <!-- NOTICE -->

                  <div class="alert alert-warning border-0 rounded-3 mt-3 mb-4">

                    <div class="d-flex gap-2">

                      <i class="bi bi-info-circle-fill mt-1"></i>

                      <div>

                        <div class="fw-semibold">
                          Inspection Reminder
                        </div>

                        <small>
                          Leave the box unchecked if the item is Not Good.
                          Check the box if the item is Good.
                        </small>

                      </div>

                    </div>

                  </div>


                  <div class="row g-3">


                    <!-- SEAL -->

                    <div class="col-12 col-md-6 col-lg-4">

                      <div class="border rounded-4 p-3 shadow-sm h-100">

                        <div class="d-flex align-items-center gap-3">

                          <div class="bg-primary-subtle text-primary rounded-3 p-2 flex-shrink-0">

                            <i class="bi bi-tag fs-5"></i>

                          </div>

                          <div class="flex-grow-1">

                            <div class="fw-semibold">
                              Seal
                            </div>

                            <small class="text-body-secondary">
                              Check if Good
                            </small>

                          </div>

                          <div class="form-check">

                            <input
                              class="form-check-input"
                              type="checkbox"
                              id="sealCheck"
                              name="is_seal_ok"
                              value="1"
                              style="width: 1.35rem; height: 1.35rem;">

                          </div>

                        </div>

                      </div>

                    </div>


                    <!-- PIN -->

                    <div class="col-12 col-md-6 col-lg-4">

                      <div class="border rounded-4 p-3 shadow-sm h-100">

                        <div class="d-flex align-items-center gap-3">

                          <div class="bg-primary-subtle text-primary rounded-3 p-2 flex-shrink-0">

                            <i class="bi bi-pin-angle fs-5"></i>

                          </div>

                          <div class="flex-grow-1">

                            <div class="fw-semibold">
                              Pin
                            </div>

                            <small class="text-body-secondary">
                              Check if Good
                            </small>

                          </div>

                          <div class="form-check">

                            <input
                              class="form-check-input"
                              type="checkbox"
                              id="pinCheck"
                              name="is_pin_ok"
                              value="1"
                              style="width: 1.35rem; height: 1.35rem;">

                          </div>

                        </div>

                      </div>

                    </div>


                    <!-- PRESSURE -->

                    <div class="col-12 col-md-6 col-lg-4">

                      <div class="border rounded-4 p-3 shadow-sm h-100">

                        <div class="d-flex align-items-center gap-3">

                          <div class="bg-danger-subtle text-danger rounded-3 p-2 flex-shrink-0">

                            <i class="bi bi-speedometer2 fs-5"></i>

                          </div>

                          <div class="flex-grow-1">

                            <div class="fw-semibold">
                              Pressure
                            </div>

                            <small class="text-body-secondary">
                              Check if Good
                            </small>

                          </div>

                          <div class="form-check">

                            <input
                              class="form-check-input"
                              type="checkbox"
                              id="pressureCheck"
                              name="is_pressure_ok"
                              value="1"
                              style="width: 1.35rem; height: 1.35rem;">

                          </div>

                        </div>

                      </div>

                    </div>


                    <!-- HOSE -->

                    <div class="col-12 col-md-6 col-lg-4">

                      <div class="border rounded-4 p-3 shadow-sm h-100">

                        <div class="d-flex align-items-center gap-3">

                          <div class="bg-danger-subtle text-danger rounded-3 p-2 flex-shrink-0">

                            <i class="bi bi-arrow-repeat fs-5"></i>

                          </div>

                          <div class="flex-grow-1">

                            <div class="fw-semibold">
                              Hose
                            </div>

                            <small class="text-body-secondary">
                              Check if Good
                            </small>

                          </div>

                          <div class="form-check">

                            <input
                              class="form-check-input"
                              type="checkbox"
                              id="hoseCheck"
                              name="is_hose_ok"
                              value="1"
                              style="width: 1.35rem; height: 1.35rem;">

                          </div>

                        </div>

                      </div>

                    </div>


                    <!-- NOZZLE -->

                    <div class="col-12 col-md-6 col-lg-4">

                      <div class="border rounded-4 p-3 shadow-sm h-100">

                        <div class="d-flex align-items-center gap-3">

                          <div class="bg-danger-subtle text-danger rounded-3 p-2 flex-shrink-0">

                            <i class="bi bi-send fs-5"></i>

                          </div>

                          <div class="flex-grow-1">

                            <div class="fw-semibold">
                              Nozzle
                            </div>

                            <small class="text-body-secondary">
                              Check if Good
                            </small>

                          </div>

                          <div class="form-check">

                            <input
                              class="form-check-input"
                              type="checkbox"
                              id="nozzleCheck"
                              name="is_nozzle_ok"
                              value="1"
                              style="width: 1.35rem; height: 1.35rem;">

                          </div>

                        </div>

                      </div>

                    </div>


                    <!-- BELT -->

                    <div class="col-12 col-md-6 col-lg-4">

                      <div class="border rounded-4 p-3 shadow-sm h-100">

                        <div class="d-flex align-items-center gap-3">

                          <div class="bg-primary-subtle text-primary rounded-3 p-2 flex-shrink-0">

                            <i class="bi bi-link-45deg fs-5"></i>

                          </div>

                          <div class="flex-grow-1">

                            <div class="fw-semibold">
                              Belt
                            </div>

                            <small class="text-body-secondary">
                              Check if Good
                            </small>

                          </div>

                          <div class="form-check">

                            <input
                              class="form-check-input"
                              type="checkbox"
                              id="beltCheck"
                              name="is_belt_ok"
                              value="1"
                              style="width: 1.35rem; height: 1.35rem;">

                          </div>

                        </div>

                      </div>

                    </div>


                    <!-- CYLINDER BODY -->

                    <div class="col-12 col-md-6 col-lg-4">

                      <div class="border rounded-4 p-3 shadow-sm h-100">

                        <div class="d-flex align-items-center gap-3">

                          <div class="bg-danger-subtle text-danger rounded-3 p-2 flex-shrink-0">

                            <i class="bi bi-fire fs-5"></i>

                          </div>

                          <div class="flex-grow-1">

                            <div class="fw-semibold">
                              Cylinder Body
                            </div>

                            <small class="text-body-secondary">
                              Check if Good
                            </small>

                          </div>

                          <div class="form-check">

                            <input
                              class="form-check-input"
                              type="checkbox"
                              id="cylinderBodyCheck"
                              name="is_cylinder_body_ok"
                              value="1"
                              style="width: 1.35rem; height: 1.35rem;">

                          </div>

                        </div>
                      </div>
                    </div>


                    <!-- DEMARCATION LINE -->

                    <div class="col-12 col-md-6 col-lg-4">
                      <div class="border rounded-4 p-3 shadow-sm h-100">
                        <div class="d-flex align-items-center gap-3">
                          <div class="bg-primary-subtle text-primary rounded-3 p-2 flex-shrink-0">

                            <i class="bi bi-sign-turn-slight-right fs-5"></i>

                          </div>

                          <div class="flex-grow-1">

                            <div class="fw-semibold">
                              Demarcation Line
                            </div>

                            <small class="text-body-secondary">
                              Check if Good
                            </small>

                          </div>

                          <div class="form-check">

                            <input
                              class="form-check-input"
                              type="checkbox"
                              id="demarcationLineCheck"
                              name="is_demarcation_line_ok"
                              value="1"
                              style="width: 1.35rem; height: 1.35rem;">

                          </div>
                        </div>
                      </div>
                    </div>


                    <!-- SIGNAGE -->

                    <div class="col-12 col-md-6 col-lg-4">

                      <div class="border rounded-4 p-3 shadow-sm h-100">

                        <div class="d-flex align-items-center gap-3">

                          <div class="bg-success-subtle text-success rounded-3 p-2 flex-shrink-0">

                            <i class="bi bi-signpost-2 fs-5"></i>

                          </div>

                          <div class="flex-grow-1">

                            <div class="fw-semibold">
                              Signage
                            </div>

                            <small class="text-body-secondary">
                              Check if Good
                            </small>

                          </div>

                          <div class="form-check">

                            <input
                              class="form-check-input"
                              type="checkbox"
                              id="signageCheck"
                              name="is_signage_ok"
                              value="1"
                              style="width: 1.35rem; height: 1.35rem;">

                          </div>

                        </div>

                      </div>

                    </div>

                  </div>

                </div>


                <!-- =========================
                         SECTION 3
                         REMARKS
                    ========================== -->

                <div class="border rounded-4 p-4">

                  <div class="d-flex align-items-center gap-3 mb-4">

                    <div
                      class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                      style="width: 34px; height: 34px;">
                      3
                    </div>

                    <div>

                      <h6 class="fw-bold mb-0">
                        Remarks / Comments
                      </h6>

                      <small class="text-body-secondary">
                        Add additional inspection notes
                      </small>

                    </div>

                  </div>

                  <textarea
                    class="form-control"
                    id="remarks"
                    name="remarks"
                    rows="4"
                    placeholder="Put N/A if not applicable"></textarea>

                </div>

              </form>

            </div>


            <!-- =========================
                 MODAL FOOTER
            ========================== -->

            <div class="modal-footer px-4 py-3 border-top">

              <button
                type="button"
                class="btn btn-light border px-4"
                data-bs-dismiss="modal"
                onclick="location.reload()">
                <i class="bi bi-x-lg me-1"></i>
                Cancel
              </button>

              <button
                type="submit"
                form="inspectionChecklistForm"
                class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i>
                Save Inspection
              </button>

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

    // to auto filled the date
    const inspectionModalElement = document.getElementById('inspectionChecklistModal');

    inspectionModalElement.addEventListener('show.bs.modal', function() {

      const today = new Date().toISOString().split('T')[0];

      document.getElementById('dateInspected').value = today;

    });

    // this is for logic for condition status
    document.addEventListener('DOMContentLoaded', function() {

      const form = document.getElementById('inspectionChecklistForm');
      const statusBadge = document.getElementById('inspectionConditionStatus');

      if (!form || !statusBadge) {
        return;
      }

      function updateInspectionStatus() {

        const pressure = document.getElementById('pressureCheck');
        const hose = document.getElementById('hoseCheck');
        const nozzle = document.getElementById('nozzleCheck');
        const cylinderBody = document.getElementById('cylinderBodyCheck');

        const items = [
          pressure,
          hose,
          nozzle,
          cylinderBody
        ];

        // Safety check
        if (items.some(item => !item)) {
          return;
        }

        // Check kung may kahit isang nasagutan
        const hasAnswer = items.some(item => item.checked);

        // Wala pang nasasagutan
        if (!hasAnswer) {

          statusBadge.textContent = 'Status';
          statusBadge.className = 'badge bg-secondary ms-auto';

          return;
        }

        // Lahat checked = Good
        const allGood = items.every(item => item.checked);

        if (allGood) {

          statusBadge.textContent = 'Good';
          statusBadge.className = 'badge bg-success ms-auto';

        } else {

          statusBadge.textContent = 'Not Good';
          statusBadge.className = 'badge bg-danger ms-auto';

        }
      }

      // Detect checkbox changes kahit nasa loob ng modal
      form.addEventListener('change', function(event) {

        if (
          event.target.id === 'pressureCheck' ||
          event.target.id === 'hoseCheck' ||
          event.target.id === 'nozzleCheck' ||
          event.target.id === 'cylinderBodyCheck'
        ) {
          updateInspectionStatus();
        }

      });

      // Initial status
      updateInspectionStatus();

    });


    // Status update kapag nag-change ang apat na checklist
    document.querySelectorAll(
      'input[name="is_pressure_ok"], ' +
      'input[name="is_hose_ok"], ' +
      'input[name="is_nozzle_ok"], ' +
      'input[name="is_cylinder_body_ok"]'
    ).forEach(checkbox => {

      checkbox.addEventListener('change', updateInspectionStatus);

    });
  </script>
</body>

</html>