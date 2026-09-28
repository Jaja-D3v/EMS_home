<?php

require_once 'backend/authentication/SessionChecker.php';
require_once 'backend/controller/InspectionController.php';

$inspectionChecklists = getAllInspected();

?>

<!DOCTYPE html>

<!--
* CoreUI - Free Bootstrap Admin Template
* @version v5.5.0
* @link https://coreui.io/product/free-bootstrap-admin-template/
* Copyright (c) 2026 creativeLabs Łukasz Holeczek
* Licensed under MIT
-->

<html lang="en">

<?php include 'partials/header.php'; ?>

<body>

  <?php include 'partials/side-nav.php'; ?>

  <div class="wrapper d-flex flex-column min-vh-100">

    <?php include 'partials/header-nav.php'; ?>

    <!-- CONTENT -->
    <div class="container-fluid py-0">

      <!-- Page Header -->
      <div
        class="card border-0 shadow-sm text-white mb-1 mt-0 overflow-hidden"
        style="background: linear-gradient(135deg, #c81e3a, #ff7657);">

        <div class="card-body p-4">

          <div class="row align-items-center g-3">

            <div class="col-auto">
              <div class="bg-white bg-opacity-25 rounded-3 p-3 fs-2">
                <i class="bi bi-clipboard2-check"></i>
              </div>
            </div>

            <div class="col">

              <h2 class="fw-bold mb-1">
                Inspection List
              </h2>

              <p class="mb-0 text-white-50">
                Review and approve inspection reports.
              </p>

            </div>

          </div>

        </div>

      </div>


      <!-- Filters -->
      <div class="card border-0 shadow-sm mb-3">

        <div class="card-body p-3">

          <div class="row g-2">

            <!-- Search -->
            <div class="col-12 col-md-6 col-lg-6">

              <div class="input-group">

                <span class="input-group-text bg-white">
                  <i class="bi bi-search"></i>
                </span>

                <input
                  type="text"
                  class="form-control"
                  id="inspectionSearch"
                  placeholder="Search by FE code or location...">

              </div>

            </div>


            <!-- Status -->
            <div class="col-12 col-md-3 col-lg-3">

              <select
                class="form-select"
                id="inspectionStatusFilter">

                <option value="all">
                  All Status
                </option>

                <option value="pending">
                  Pending Approval
                </option>

                <option value="approved">
                  Approved
                </option>

                <option value="rejected">
                  Rejected
                </option>

              </select>

            </div>


            <!-- Date -->
            <div class="col-12 col-md-3 col-lg-3">

              <div class="input-group">

                <span class="input-group-text bg-white">
                  <i class="bi bi-calendar3"></i>
                </span>

                <input
                  type="date"
                  class="form-control"
                  id="inspectionDateFilter">

              </div>

            </div>

          </div>

        </div>

      </div>


      <!-- Inspection Table -->
      <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

          <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

              <thead class="table-light">

                <tr>

                  <th class="ps-4 py-3 text-nowrap">
                    FE Code
                  </th>

                  <th class="py-3 text-nowrap">
                    Location
                  </th>

                  <th class="py-3 text-nowrap">
                    Date Inspected
                  </th>

                  <th class="py-3 text-nowrap">
                    Status
                  </th>

                  <th class="py-3 text-nowrap text-end pe-3">
                    Actions
                  </th>

                </tr>

              </thead>


              <tbody id="inspectionList">

                <?php if (!empty($inspectionChecklists)): ?>

                  <?php foreach ($inspectionChecklists as $inspection): ?>

                    <?php

                    /*
                                        |--------------------------------------------------------------------------
                                        | Evaluation Status
                                        |--------------------------------------------------------------------------
                                        */

                    $status = $inspection['evaluation_status'] ?? 'Pending';

                    switch ($status) {

                      case 'Approved':

                        $statusClass = 'bg-success-subtle text-success';
                        $statusIcon = 'bi-check-circle-fill';
                        $statusFilterValue = 'approved';
                        $statusLabel = 'Approved';

                        break;


                      case 'Rejected':

                        $statusClass = 'bg-danger-subtle text-danger';
                        $statusIcon = 'bi-x-circle-fill';
                        $statusFilterValue = 'rejected';
                        $statusLabel = 'Rejected';

                        break;


                      case 'Pending':

                      default:

                        $statusClass = 'bg-warning-subtle text-warning-emphasis';
                        $statusIcon = 'bi-clock';
                        $statusFilterValue = 'pending';
                        $statusLabel = 'Pending Approval';

                        break;
                    }


                    /*
                                        |--------------------------------------------------------------------------
                                        | Inspection Date
                                        |--------------------------------------------------------------------------
                                        | Normalize the database date to YYYY-MM-DD
                                        | so it matches the HTML date input value.
                                        */

                    $inspectionDate = '';

                    if (!empty($inspection['date_inspected'])) {

                      $timestamp = strtotime(
                        $inspection['date_inspected']
                      );

                      if ($timestamp !== false) {

                        $inspectionDate = date(
                          'Y-m-d',
                          $timestamp
                        );
                      }
                    }

                    ?>

                    <tr
                      class="inspection-row"
                      data-fe-code="<?= htmlspecialchars(strtolower($inspection['extinguisher_code'] ?? '')) ?>"
                      data-location="<?= htmlspecialchars(strtolower($inspection['location'] ?? '')) ?>"
                      data-status="<?= htmlspecialchars($statusFilterValue) ?>"
                      data-date="<?= htmlspecialchars($inspectionDate) ?>">

                      <!-- FE Code -->
                      <td class="ps-4">

                        <span class="text-primary fw-semibold">

                          <?= htmlspecialchars(
                            $inspection['extinguisher_code'] ?? '—'
                          ) ?>

                        </span>

                      </td>


                      <!-- Location -->
                      <td>

                        <?= htmlspecialchars(
                          $inspection['location'] ?? '—'
                        ) ?>

                      </td>


                      <!-- Date Inspected -->
                      <td class="text-nowrap">

                        <?php if (!empty($inspection['date_inspected'])): ?>

                          <?php

                          $timestamp = strtotime(
                            $inspection['date_inspected']
                          );

                          ?>

                          <?php if ($timestamp !== false): ?>

                            <?= date(
                              'M d, Y',
                              $timestamp
                            ) ?>

                            <small class="text-body-secondary d-block">

                              <?= date(
                                'h:i A',
                                $timestamp
                              ) ?>

                            </small>

                          <?php else: ?>

                            —

                          <?php endif; ?>

                        <?php else: ?>

                          —

                        <?php endif; ?>

                      </td>


                      <!-- Status -->
                      <td>

                        <span
                          class="badge rounded-pill <?= $statusClass ?> px-3 py-2">

                          <i
                            class="bi <?= $statusIcon ?> me-1">
                          </i>

                          <?= htmlspecialchars($statusLabel) ?>

                        </span>

                      </td>


                      <!-- Actions -->
                      <td class="text-end pe-3">

                        <div class="d-flex justify-content-end gap-1">

                          <button
                            type="button"
                            class="btn btn-sm btn-primary"
                            onclick="viewInspection(<?= (int)$inspection['inspect_id'] ?>)">

                            <i class="bi bi-eye me-1"></i>
                            View

                          </button>


                          <?php if ($status === 'Pending'): ?>

                            <!-- Approve -->
                            <button
                              type="button"
                              class="btn btn-sm btn-success"
                              onclick="approveInspection(<?= (int)$inspection['inspect_id'] ?>)">

                              <i class="bi bi-check-lg me-1"></i>

                              Approve

                            </button>


                            <!-- Reject -->
                            <button
                              type="button"
                              class="btn btn-sm btn-danger"
                              onclick="rejectInspection(<?= (int)$inspection['inspect_id'] ?>)">

                              <i class="bi bi-x-lg me-1"></i>

                              Reject

                            </button>


                          <?php else: ?>

                            <!-- Details -->
                            <a
                              href="view-inspection.php?id=<?= urlencode($inspection['inspect_id']) ?>"
                              class="btn btn-sm btn-light border">

                              <i class="bi bi-file-text me-1"></i>

                              Details

                            </a>

                          <?php endif; ?>

                        </div>

                      </td>

                    </tr>

                  <?php endforeach; ?>


                <?php else: ?>

                  <!-- No Database Records -->
                  <tr>

                    <td
                      colspan="5"
                      class="text-center py-5">

                      <div class="text-body-secondary">

                        <i
                          class="bi bi-clipboard-x fs-1 d-block mb-2">
                        </i>

                        No inspection records found.

                      </div>

                    </td>

                  </tr>

                <?php endif; ?>

              </tbody>

            </table>

          </div>

        </div>

      </div>

    </div>

    <!-- modalfor view info of selected fe inspection -->
    <!-- =========================================================
     VIEW INSPECTION MODAL
========================================================== -->

    <div
      class="modal fade"
      id="viewInspectionModal"
      tabindex="-1"
      aria-labelledby="viewInspectionModalLabel"
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
                  id="viewInspectionModalLabel">

                  Inspection Details

                </h5>

                <small class="text-body-secondary">

                  View inspection report and checklist details.

                </small>

              </div>

            </div>


            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close">
            </button>

          </div>


          <!-- =========================
                 MODAL BODY
            ========================== -->

          <div class="modal-body px-4 py-4">


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

                  <label class="form-label fw-semibold">

                    Fire Extinguisher Code

                  </label>

                  <div class="input-group">

                    <span class="input-group-text bg-body">

                      <i class="bi bi-qr-code"></i>

                    </span>

                    <input
                      type="text"
                      class="form-control"
                      id="viewExtinguisherCode"
                      readonly>

                  </div>

                </div>


                <!-- LOCATION -->
                <div class="col-12 col-md-6 col-lg-4">

                  <label class="form-label fw-semibold">

                    Location

                  </label>

                  <div class="input-group">

                    <span class="input-group-text bg-body">

                      <i class="bi bi-geo-alt"></i>

                    </span>

                    <input
                      type="text"
                      class="form-control"
                      id="viewInspectionLocation"
                      readonly>

                  </div>

                </div>


                <!-- CAPACITY -->
                <div class="col-12 col-md-6 col-lg-4">

                  <label class="form-label fw-semibold">

                    Capacity

                  </label>

                  <input
                    type="text"
                    class="form-control"
                    id="viewInspectionCapacity"
                    readonly>

                </div>


                <!-- TYPE -->
                <div class="col-12 col-md-6 col-lg-4">

                  <label class="form-label fw-semibold">

                    Type

                  </label>

                  <input
                    type="text"
                    class="form-control"
                    id="viewInspectionType"
                    readonly>

                </div>


                <!-- CLASS -->
                <div class="col-12 col-md-6 col-lg-4">

                  <label class="form-label fw-semibold">

                    Class

                  </label>

                  <input
                    type="text"
                    class="form-control"
                    id="viewInspectionClass"
                    readonly>

                </div>


                <!-- DATE INSPECTED -->
                <div class="col-12 col-md-6 col-lg-4">

                  <label class="form-label fw-semibold">

                    Date of Inspection

                  </label>

                  <input
                    type="text"
                    class="form-control"
                    id="viewDateInspected"
                    readonly>

                </div>


                <!-- INSPECTED BY -->
                <div class="col-12 col-md-6 col-lg-4">

                  <label class="form-label fw-semibold">

                    Inspected By

                  </label>

                  <input
                    type="text"
                    class="form-control"
                    id="viewInspectedBy"
                    readonly>

                </div>


                <!-- VERIFIED AND APPROVED BY -->
                <div class="col-12 col-md-6 col-lg-4">

                  <label class="form-label fw-semibold">

                    Verified & Approved By

                  </label>

                  <input
                    type="text"
                    class="form-control"
                    id="viewVerifiedAndApprovedBy"
                    readonly>

                </div>


                <!-- EVALUATION STATUS -->
                <div class="col-12 col-md-6 col-lg-4">

                  <label class="form-label fw-semibold">

                    Evaluation Status

                  </label>

                  <div id="viewEvaluationStatus">

                    <span class="badge bg-secondary px-3 py-2">

                      Pending

                    </span>

                  </div>

                </div>


                <!-- ACTION TAKEN -->
                <div class="col-12 col-md-6 col-lg-4">

                  <label class="form-label fw-semibold">

                    Action Taken

                  </label>

                  <input
                    type="text"
                    class="form-control"
                    id="viewActionTaken"
                    readonly>

                </div>


                <!-- TARGET DATE -->
                <div class="col-12 col-md-6 col-lg-4">

                  <label class="form-label fw-semibold">

                    Target Date of Implementation

                  </label>

                  <input
                    type="text"
                    class="form-control"
                    id="viewTargetDate"
                    readonly>

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

                    Inspection results for each component.

                  </small>

                </div>


                <!-- OVERALL CONDITION -->
                <span
                  id="viewInspectionConditionStatus"
                  class="badge bg-success ms-auto">

                  Good

                </span>

              </div>


              <!-- NOTICE -->

              <div
                class="alert alert-light border rounded-3 mt-3 mb-4">

                <div class="d-flex gap-2">

                  <i class="bi bi-info-circle-fill text-primary mt-1"></i>

                  <div>

                    <div class="fw-semibold">

                      Inspection Result

                    </div>

                    <small class="text-body-secondary">

                      The checklist below shows the condition recorded during the inspection.

                    </small>

                  </div>

                </div>

              </div>


              <div class="row g-3">


                <!-- SEAL -->
                <div class="col-12 col-md-6 col-lg-4">

                  <div
                    class="border rounded-4 p-3 shadow-sm h-100">

                    <div class="d-flex align-items-center gap-3">

                      <div
                        class="bg-primary-subtle text-primary rounded-3 p-2 flex-shrink-0">

                        <i class="bi bi-tag fs-5"></i>

                      </div>


                      <div class="flex-grow-1">

                        <div class="fw-semibold">
                          Seal
                        </div>

                        <small
                          id="viewSealText"
                          class="text-success fw-semibold">

                          Good

                        </small>

                      </div>


                      <div
                        id="viewSealStatus"
                        class="fs-4 text-success">

                        <i class="bi bi-check-circle-fill"></i>

                      </div>

                    </div>

                  </div>

                </div>



                <!-- PIN -->
                <div class="col-12 col-md-6 col-lg-4">

                  <div
                    class="border rounded-4 p-3 shadow-sm h-100">

                    <div class="d-flex align-items-center gap-3">

                      <div
                        class="bg-primary-subtle text-primary rounded-3 p-2 flex-shrink-0">

                        <i class="bi bi-pin-angle fs-5"></i>

                      </div>


                      <div class="flex-grow-1">

                        <div class="fw-semibold">
                          Pin
                        </div>

                        <small
                          id="viewPinText"
                          class="text-success fw-semibold">

                          Good

                        </small>

                      </div>


                      <div
                        id="viewPinStatus"
                        class="fs-4 text-success">

                        <i class="bi bi-check-circle-fill"></i>

                      </div>

                    </div>

                  </div>

                </div>



                <!-- PRESSURE -->
                <div class="col-12 col-md-6 col-lg-4">

                  <div
                    class="border rounded-4 p-3 shadow-sm h-100">

                    <div class="d-flex align-items-center gap-3">

                      <div
                        class="bg-danger-subtle text-danger rounded-3 p-2 flex-shrink-0">

                        <i class="bi bi-speedometer2 fs-5"></i>

                      </div>


                      <div class="flex-grow-1">

                        <div class="fw-semibold">
                          Pressure
                        </div>

                        <small
                          id="viewPressureText"
                          class="text-success fw-semibold">

                          Good

                        </small>

                      </div>


                      <div
                        id="viewPressureStatus"
                        class="fs-4 text-success">

                        <i class="bi bi-check-circle-fill"></i>

                      </div>

                    </div>

                  </div>

                </div>



                <!-- HOSE -->
                <div class="col-12 col-md-6 col-lg-4">

                  <div
                    class="border rounded-4 p-3 shadow-sm h-100">

                    <div class="d-flex align-items-center gap-3">

                      <div
                        class="bg-danger-subtle text-danger rounded-3 p-2 flex-shrink-0">

                        <i class="bi bi-arrow-repeat fs-5"></i>

                      </div>


                      <div class="flex-grow-1">

                        <div class="fw-semibold">
                          Hose
                        </div>

                        <small
                          id="viewHoseText"
                          class="text-success fw-semibold">

                          Good

                        </small>

                      </div>


                      <div
                        id="viewHoseStatus"
                        class="fs-4 text-success">

                        <i class="bi bi-check-circle-fill"></i>

                      </div>

                    </div>

                  </div>

                </div>



                <!-- NOZZLE -->
                <div class="col-12 col-md-6 col-lg-4">

                  <div
                    class="border rounded-4 p-3 shadow-sm h-100">

                    <div class="d-flex align-items-center gap-3">

                      <div
                        class="bg-danger-subtle text-danger rounded-3 p-2 flex-shrink-0">

                        <i class="bi bi-send fs-5"></i>

                      </div>


                      <div class="flex-grow-1">

                        <div class="fw-semibold">
                          Nozzle
                        </div>

                        <small
                          id="viewNozzleText"
                          class="text-success fw-semibold">

                          Good

                        </small>

                      </div>


                      <div
                        id="viewNozzleStatus"
                        class="fs-4 text-success">

                        <i class="bi bi-check-circle-fill"></i>

                      </div>

                    </div>

                  </div>

                </div>



                <!-- BELT -->
                <div class="col-12 col-md-6 col-lg-4">

                  <div
                    class="border rounded-4 p-3 shadow-sm h-100">

                    <div class="d-flex align-items-center gap-3">

                      <div
                        class="bg-primary-subtle text-primary rounded-3 p-2 flex-shrink-0">

                        <i class="bi bi-link-45deg fs-5"></i>

                      </div>


                      <div class="flex-grow-1">

                        <div class="fw-semibold">
                          Belt
                        </div>

                        <small
                          id="viewBeltText"
                          class="text-success fw-semibold">

                          Good

                        </small>

                      </div>


                      <div
                        id="viewBeltStatus"
                        class="fs-4 text-success">

                        <i class="bi bi-check-circle-fill"></i>

                      </div>

                    </div>

                  </div>

                </div>



                <!-- CYLINDER BODY -->
                <div class="col-12 col-md-6 col-lg-4">

                  <div
                    class="border rounded-4 p-3 shadow-sm h-100">

                    <div class="d-flex align-items-center gap-3">

                      <div
                        class="bg-danger-subtle text-danger rounded-3 p-2 flex-shrink-0">

                        <i class="bi bi-fire fs-5"></i>

                      </div>


                      <div class="flex-grow-1">

                        <div class="fw-semibold">
                          Cylinder Body
                        </div>

                        <small
                          id="viewCylinderBodyText"
                          class="text-success fw-semibold">

                          Good

                        </small>

                      </div>


                      <div
                        id="viewCylinderBodyStatus"
                        class="fs-4 text-success">

                        <i class="bi bi-check-circle-fill"></i>

                      </div>

                    </div>

                  </div>

                </div>



                <!-- DEMARCATION LINE -->
                <div class="col-12 col-md-6 col-lg-4">

                  <div
                    class="border rounded-4 p-3 shadow-sm h-100">

                    <div class="d-flex align-items-center gap-3">

                      <div
                        class="bg-primary-subtle text-primary rounded-3 p-2 flex-shrink-0">

                        <i class="bi bi-sign-turn-slight-right fs-5"></i>

                      </div>


                      <div class="flex-grow-1">

                        <div class="fw-semibold">
                          Demarcation Line
                        </div>

                        <small
                          id="viewDemarcationLineText"
                          class="text-success fw-semibold">

                          Good

                        </small>

                      </div>


                      <div
                        id="viewDemarcationLineStatus"
                        class="fs-4 text-success">

                        <i class="bi bi-check-circle-fill"></i>

                      </div>

                    </div>

                  </div>

                </div>



                <!-- SIGNAGE -->
                <div class="col-12 col-md-6 col-lg-4">

                  <div
                    class="border rounded-4 p-3 shadow-sm h-100">

                    <div class="d-flex align-items-center gap-3">

                      <div
                        class="bg-success-subtle text-success rounded-3 p-2 flex-shrink-0">

                        <i class="bi bi-signpost-2 fs-5"></i>

                      </div>


                      <div class="flex-grow-1">

                        <div class="fw-semibold">
                          Signage
                        </div>

                        <small
                          id="viewSignageText"
                          class="text-success fw-semibold">

                          Good

                        </small>

                      </div>


                      <div
                        id="viewSignageStatus"
                        class="fs-4 text-success">

                        <i class="bi bi-check-circle-fill"></i>

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

                    Inspection notes

                  </small>

                </div>

              </div>


              <textarea
                class="form-control"
                id="viewRemarks"
                rows="4"
                readonly></textarea>

            </div>


          </div>


          <!-- =========================
                 MODAL FOOTER
            ========================== -->

          <div class="modal-footer px-4 py-3 border-top">

            <button
              type="button"
              class="btn btn-light border px-4"
              data-bs-dismiss="modal">

              <i class="bi bi-x-lg me-1"></i>

              Close

            </button>

          </div>


        </div>

      </div>

    </div>


  </div>

  <?php include 'partials/footer.php'; ?>


  <!-- CoreUI and necessary plugins -->
  <script src="vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
  <script src="./js/inspection-approvals.js"></script>

  <script src="vendors/simplebar/js/simplebar.min.js"></script>





  <?php include_once 'notification/session_timeout.php'; ?>

</body>

</html>