<?php
include './backend/controller/FireExtinguisherController.php';
include 'backend/controller/QRCodeGeneratorController.php';
include 'backend/controller/DropdownController.php';
require_once 'backend/authentication/SessionChecker.php';

$search = trim($_GET['search'] ?? '');

?>

<!DOCTYPE html>
<html lang="en">
<?php include 'partials/header.php'; ?>

<body>
  <?php include 'partials/side-nav.php'; ?>
  <div class="wrapper d-flex flex-column min-vh-100">
    <?php
    include 'partials/header-nav.php';
    ?>
    <div class="container-fluid py-0">

      <div class="card border-0 shadow-sm text-white mb-1 mt-0 overflow-hidden "
        style="background: linear-gradient(135deg, #704f08, #f59a08);">

        <div class="card-body p-4">

          <div class="row align-items-center g-3">

            <div class="col-auto">

              <div class="bg-white bg-opacity-10 rounded-3 p-3 fs-3">
                <i class="bi bi-fire"></i>
              </div>

            </div>

            <div class="col">

              <h2 class="fw-bold mb-1">
                Fire Extinguishers Expiring Soon
              </h2>

              <p class="mb-0 text-white-50">
                List of all fire extinguishers that are expiring soon.
              </p>

            </div>

          </div>

        </div>

      </div>
    </div>

    <div class="body flex-grow-1">
      <div class="container-fluid py-0">
        <div class="container py-4">

          <div class="row justify-content-center">

            <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-3 mb-3">

              <div>
                <div class="input-group">
                  <span class="input-group-text bg-body border-end-0">
                    <i class="bi bi-funnel text-primary"></i>
                  </span>

                  <?php
                  $selectedCondition = strtolower(
                    trim($_GET['condition'] ?? 'all')
                  );
                  ?>

                  <select
                    id="conditionFilter"
                    class="form-select border-start-0 ps-1"
                    aria-label="Filter by condition">

                    <option
                      value="all"
                      <?= $selectedCondition === 'all' ? 'selected' : '' ?>>
                      All Conditions
                    </option>

                    <option
                      value="good"
                      <?= $selectedCondition === 'good' ? 'selected' : '' ?>>
                      Good Condition
                    </option>

                    <option
                      value="not-good"
                      <?= $selectedCondition === 'not-good' ? 'selected' : '' ?>>
                      Not Good
                    </option>

                  </select>
                </div>
              </div>

              <?php if (strtolower(trim($_SESSION['Role'] ?? '')) === 'admin'): ?>

                <?php
                $adminBranch = trim($_SESSION['Branch'] ?? '');

                $selectedBranch = $_GET['branch'] ?? $adminBranch;

                if (empty($selectedBranch)) {
                  $selectedBranch = 'all';
                }

                $branches = getAllDropdownBranches();
                ?>

                <div class="col-12 col-md-auto">

                  <select class="form-select" id="branchFilter" style="min-width: 190px;">

                    <option
                      value="all"
                      <?= $selectedBranch === 'all' ? 'selected' : '' ?>>
                      All Branches
                    </option>

                    <?php if (!empty($branches)): ?>

                      <?php foreach ($branches as $branch): ?>

                        <option
                          value="<?= htmlspecialchars($branch['value']) ?>"
                          <?= $selectedBranch === $branch['value'] ? 'selected' : '' ?>>

                          <?= htmlspecialchars($branch['value']) ?>

                        </option>

                      <?php endforeach; ?>

                    <?php endif; ?>

                  </select>

                </div>

              <?php endif; ?>

              <script>
                document.addEventListener('DOMContentLoaded', function() {

                  const branchFilter = document.getElementById('branchFilter');

                  if (!branchFilter) {
                    return;
                  }

                  branchFilter.addEventListener('change', function() {

                    const selectedBranch = this.value;

                    const url = new URL(window.location.href);

                    // Update selected branch
                    url.searchParams.set('branch', selectedBranch);

                    // Reset pagination to page 1
                    url.searchParams.set('page', '1');

                    // Reload page using the new branch and page
                    window.location.href = url.toString();

                  });

                });
              </script>

              <script>
                const conditionFilter = document.getElementById('conditionFilter');

                if (conditionFilter) {

                  conditionFilter.addEventListener('change', function() {

                    const selectedCondition = this.value;

                    const url = new URL(window.location.href);

                    if (selectedCondition === 'all') {
                      url.searchParams.delete('condition');
                    } else {
                      url.searchParams.set('condition', selectedCondition);
                    }

                    url.searchParams.set('page', '1');

                    window.location.href = url.toString();

                  });

                }
              </script>

              <div class="d-flex flex-column flex-md-row gap-2 ms-lg-auto">

               
                <!-- searxh js -->

              </div>

            </div>
            <?php

            $limit = 10;

            $page = isset($_GET['page'])
              ? (int) $_GET['page']
              : 1;

            if ($page < 1) {
              $page = 1;
            }

            $offset = ($page - 1) * $limit;


            // Get expiring fire extinguishers
            $condition = strtolower(trim($_GET['condition'] ?? 'all'));

            $condition = strtolower(trim($_GET['condition'] ?? 'all'));

            $expiryResult = getExpiry(
              $limit,
              $offset,
              $search,
              $condition
            );

            $info = $expiryResult['data'];
            $totalRecords = $expiryResult['total'];

            $totalPages = (int) ceil($totalRecords / $limit);

            ?>

            <div id="expiringExtinguisherResults">

            <?php if ($totalRecords === 0): ?>

              <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                  <i class="bi bi-inbox fs-1 text-body-secondary"></i>
                  <h5 class="fw-semibold mt-3 mb-1">No Data Found</h5>
                  <p class="text-body-secondary mb-0">
                    No fire extinguishers are currently expiring soon.
                  </p>
                </div>
              </div>

            <?php else: ?>

              <?php foreach ($info as $data): ?>

                <?php
                $condition = $data['condition_status'];

                if ($condition === 'Good') {
                  $badgeClass = 'bg-success-subtle text-success';
                  $statusIcon = 'bi-check-circle-fill';
                } else {
                  $badgeClass = 'bg-danger-subtle text-danger';
                  $statusIcon = 'bi-exclamation-circle-fill';
                }

                $expirationBadge = null;
                $expirationBadgeClass = '';
                $expirationIcon = '';

                if (!empty($data['expiration_date'])) {

                  $today = new DateTime('today');
                  $expirationDate = new DateTime($data['expiration_date']);

                  $twoMonthsFromNow = (clone $today)->modify('+2 months');

                  if ($expirationDate < $today) {

                    $expiredDays = $today->diff($expirationDate)->days;

                    $expirationBadge = "Expired {$expiredDays} days ago";
                    $expirationBadgeClass = 'bg-danger-subtle text-danger';
                    $expirationIcon = 'bi-exclamation-triangle-fill';
                  } elseif ($expirationDate <= $twoMonthsFromNow) {

                    $remainingDays = $today->diff($expirationDate)->days;

                    $expirationBadge = "Expires in {$remainingDays} days";
                    $expirationBadgeClass = 'bg-warning-subtle text-warning-emphasis';
                    $expirationIcon = 'bi-hourglass-split';
                  }
                }
                ?>

                <div class="card border border-primary-subtle shadow-sm mb-2 extinguisher-card overflow-hidden">

                  <div class="card-body p-2 p-md-3">

                    <div class="d-flex align-items-center gap-3">

                      <div
                        class="d-flex align-items-center justify-content-center
                           bg-danger bg-opacity-10 text-danger
                           rounded-3 flex-shrink-0"
                        style="width: 50px; height: 50px;">

                        <i class="bi bi-fire fs-4"></i>

                      </div>

                      <div class="flex-grow-1 min-width-0">

                        <div class="mb-1">
                          <span
                            class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1"
                            style="font-size: 0.65rem;">

                            <i class="bi bi-shield-fill me-1"></i>
                            Fire Safety Equipment

                          </span>
                        </div>

                        <div class="d-flex align-items-center gap-2 flex-wrap">

                          <div class="fw-bold fs-5 lh-sm">
                            Fire Extinguisher
                          </div>

                          <span class="text-body-secondary small extinguisher-code">
                            <?= htmlspecialchars($data['extinguisher_code']) ?>
                          </span>

                        </div>

                      </div>

                      <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-sm-end gap-2">

                        <span
                          class="badge <?= $badgeClass ?> rounded-pill px-3 py-2 text-nowrap">

                          <i class="bi <?= $statusIcon ?> me-1"></i>

                          <?= htmlspecialchars($condition) ?>

                        </span>

                        <?php if ($expirationBadge !== null): ?>

                          <span
                            class="badge <?= $expirationBadgeClass ?> rounded-pill px-3 py-2 text-nowrap">

                            <i class="bi <?= $expirationIcon ?> me-1"></i>

                            <?= htmlspecialchars($expirationBadge) ?>

                          </span>

                        <?php endif; ?>

                      </div>

                    </div>

                    <div class="row g-2 g-md-3 mt-2 align-items-center">

                      <div class="col-6 col-md-2">
                        <div class="d-flex align-items-center gap-2">
                          <div
                            class="d-flex align-items-center justify-content-center
                            bg-danger bg-opacity-10 text-danger
                            rounded-3 flex-shrink-0"
                            style="width: 32px; height: 32px;">
                            <i class="bi bi-qr-code"></i>
                          </div>

                          <div class="min-width-0">
                            <div class="text-body-secondary small lh-1">
                              FE Code
                            </div>
                            <div class="fw-semibold small text-truncate">
                              <?= htmlspecialchars($data['extinguisher_code']) ?>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-6 col-md-2">
                        <div class="d-flex align-items-center gap-2">
                          <div
                            class="d-flex align-items-center justify-content-center
                                bg-primary bg-opacity-10 text-primary
                                rounded-3 flex-shrink-0"
                            style="width: 32px; height: 32px;">
                            <i class="bi bi-box-seam"></i>
                          </div>

                          <div class="min-width-0">
                            <div class="text-body-secondary small lh-1">
                              Capacity
                            </div>
                            <div class="fw-semibold small text-truncate">
                              <?= htmlspecialchars($data['capacity']) ?>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-6 col-md-2">
                        <div class="d-flex align-items-center gap-2">
                          <div
                            class="d-flex align-items-center justify-content-center
                            bg-warning bg-opacity-10 text-warning
                            rounded-3 flex-shrink-0"
                            style="width: 32px; height: 32px;">
                            <i class="bi bi-fire"></i>
                          </div>

                          <div class="min-width-0">
                            <div class="text-body-secondary small lh-1">
                              Type
                            </div>

                            <div
                              class="fw-semibold small text-truncate"
                              title="<?= htmlspecialchars($data['type']) ?>">
                              <?= htmlspecialchars($data['type']) ?>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-6 col-md-2">
                        <div class="d-flex align-items-center gap-2">
                          <div
                            class="d-flex align-items-center justify-content-center
                            bg-primary bg-opacity-10 text-primary
                            rounded-3 flex-shrink-0"
                            style="width: 32px; height: 32px;">
                            <i class="bi bi-geo-alt"></i>
                          </div>

                          <div class="min-width-0">
                            <div class="text-body-secondary small lh-1">
                              Location
                            </div>

                            <div class="fw-semibold small text-truncate extinguisher-location"
                              title="<?= htmlspecialchars($data['location']) ?>">
                              <?= htmlspecialchars($data['location']) ?>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-6 col-md-2">

                        <div class="d-flex align-items-center gap-2">

                          <div
                            class="d-flex align-items-center justify-content-center
                                bg-success bg-opacity-10 text-success
                                rounded-3 flex-shrink-0"
                            style="width: 32px; height: 32px;">

                            <i class="bi bi-building"></i>

                          </div>

                          <div class="min-width-0">

                            <div class="text-body-secondary small lh-1">
                              Branch
                            </div>

                            <div
                              class="fw-semibold small text-truncate"
                              title="<?= htmlspecialchars($data['branch']) ?>">

                              <?= htmlspecialchars($data['branch']) ?>

                            </div>

                          </div>

                        </div>

                      </div>

                      <div class="col-12 col-md-2">
                        <button
                          type="button"
                          class="btn btn-sm btn-primary w-100 px-3 view-extinguisher-btn"
                          data-id="<?= htmlspecialchars($data['extinguisher_id']) ?>"
                          data-bs-toggle="modal"
                          data-bs-target="#viewFireExtinguisherModal">

                          <i class="bi bi-eye me-1"></i>
                          View

                        </button>
                      </div>

                    </div>

                  </div>

                </div>

              <?php endforeach; ?>
            <?php endif; ?>

                        </div>

            <div id="expiringExtinguisherPagination">

            <!-- PAGINATION -->
            <div class="card border border-primary-subtle shadow-sm rounded-3 mt-3">

              <div class="card-footer bg-body border-0 px-3 py-3">

                <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">

                  <!-- ENTRY INFORMATION -->
                  <small class="text-body-secondary" style="font-size: 12px;">

                    <?php if ($totalRecords > 0): ?>

                      Showing
                      <strong class="text-body">
                        <?= $offset + 1 ?>
                      </strong>
                      to
                      <strong class="text-body">
                        <?= min($offset + $limit, $totalRecords) ?>
                      </strong>
                      of
                      <strong class="text-body">
                        <?= $totalRecords ?>
                      </strong>
                      entries

                    <?php else: ?>

                      Showing 0 of 0 entries

                    <?php endif; ?>

                  </small>


                  <!-- PAGINATION -->
                  <?php if ($totalPages > 1): ?>

                    <?php
                    $currentPageUrl = basename($_SERVER['PHP_SELF']);

                    $paginationParams = $_GET;
                    unset($paginationParams['page']);

                    $previousParams = $paginationParams;
                    $previousParams['page'] = max(1, $page - 1);

                    $nextParams = $paginationParams;
                    $nextParams['page'] = min($totalPages, $page + 1);
                    ?>

                    <nav aria-label="Fire extinguisher pagination">

                      <ul class="pagination pagination-sm mb-0">

                        <!-- PREVIOUS -->
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">

                          <?php if ($page > 1): ?>

                            <a
                              class="page-link"
                              href="<?= htmlspecialchars(
                                      $currentPageUrl,
                                      ENT_QUOTES,
                                      'UTF-8'
                                    ) ?>?<?= http_build_query($previousParams) ?>"
                              aria-label="Previous">

                              <i class="bi bi-chevron-left"></i>

                              <span class="d-none d-sm-inline ms-1">
                                Previous
                              </span>

                            </a>

                          <?php else: ?>

                            <span class="page-link">

                              <i class="bi bi-chevron-left"></i>

                              <span class="d-none d-sm-inline ms-1">
                                Previous
                              </span>

                            </span>

                          <?php endif; ?>

                        </li>


                        <!-- PAGE NUMBERS -->
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>

                          <?php
                          $pageParams = $paginationParams;
                          $pageParams['page'] = $i;
                          ?>

                          <li class="page-item <?= $i === $page ? 'active' : '' ?>">

                            <a
                              class="page-link"
                              href="<?= htmlspecialchars(
                                      $currentPageUrl,
                                      ENT_QUOTES,
                                      'UTF-8'
                                    ) ?>?<?= http_build_query($pageParams) ?>">

                              <?= $i ?>

                            </a>

                          </li>

                        <?php endfor; ?>


                        <!-- NEXT -->
                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">

                          <?php if ($page < $totalPages): ?>

                            <a
                              class="page-link"
                              href="<?= htmlspecialchars(
                                      $currentPageUrl,
                                      ENT_QUOTES,
                                      'UTF-8'
                                    ) ?>?<?= http_build_query($nextParams) ?>"
                              aria-label="Next">

                              <span class="d-none d-sm-inline me-1">
                                Next
                              </span>

                              <i class="bi bi-chevron-right"></i>

                            </a>

                          <?php else: ?>

                            <span class="page-link">

                              <span class="d-none d-sm-inline me-1">
                                Next
                              </span>

                              <i class="bi bi-chevron-right"></i>

                            </span>

                          <?php endif; ?>

                        </li>

                      </ul>

                    </nav>

                  <?php endif; ?>

                </div>

              </div>

            </div>

            </div>

       

            <div
              class="modal fade"
              id="addFireExtinguisherModal"
              tabindex="-1"
              aria-labelledby="addFireExtinguisherModalLabel"
              aria-hidden="true">
              <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

                <div class="modal-content rounded-4 border-0 shadow">

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

                  <div class="modal-body px-4 py-4">

                    <form
                      class="row g-3"
                      action="backend/controller/FireExtinguisherController.php"
                      method="post">

                      <input
                        type="hidden"
                        name="action"
                        value="add">

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
                          <option value="AFFF">AFFF</option>
                          <option value="HCFC">HCFC</option>
                        </select>
                      </div>

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
                          <option value="AB">AB</option>
                          <option value="ABC">ABC</option>
                          <option value="BC">BC</option>
                          <option value="A">A</option>
                          <option value="B">B</option>
                          <option value="C">C</option>
                          <option value="D">D</option>

                        </select>
                      </div>

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

                      <div class="col-12 col-md-8">
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

                      <div class="col-12 col-md-4">
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

            <div
              class="modal fade"
              id="viewFireExtinguisherModal"
              tabindex="-1"
              aria-labelledby="viewFireExtinguisherModalLabel"
              aria-hidden="true">

              <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

                <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">

                  <div class="modal-header border-0 bg-danger-subtle px-4 py-3">

                    <div class="d-flex align-items-center gap-3">

                      <div
                        class="d-flex align-items-center justify-content-center
                   bg-danger text-white rounded-3 flex-shrink-0"
                        style="width: 46px; height: 46px;">
                        <i class="bi bi-fire fs-4"></i>
                      </div>

                      <div>
                        <h5
                          class="modal-title fw-bold mb-1"
                          id="viewFireExtinguisherModalLabel">
                          Fire Extinguisher Details
                        </h5>

                        <small class="text-body-secondary">
                          View complete fire extinguisher information.
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

                  <div class="modal-body p-3 p-md-4">

                    <div class="card border-0 bg-light-subtle rounded-4 mb-3">

                      <div class="card-body p-3">

                        <div class="row align-items-center g-3">

                          <div class="col-12 col-md-7">

                            <div class="text-body-secondary small mb-1">
                              Fire Extinguisher Code
                            </div>

                            <div class="d-flex align-items-center gap-2">

                              <div
                                class="d-flex align-items-center justify-content-center
                           bg-danger bg-opacity-10 text-danger rounded-3"
                                style="width: 36px; height: 36px;">
                                <i class="bi bi-qr-code"></i>
                              </div>

                              <input
                                type="text"
                                class="form-control-plaintext fw-bold fs-4 p-0"
                                id="viewExtinguisherCode"
                                readonly>

                            </div>

                          </div>

                          <div class="col-12 col-md-5">

                            <div class="text-body-secondary small mb-1">
                              Current Condition
                            </div>

                            <div class="d-flex align-items-center">

                              <input
                                type="text"
                                class="form-control-plaintext fw-bold p-0"
                                id="viewConditionStatus"
                                readonly>

                            </div>

                          </div>

                        </div>

                      </div>

                    </div>

                    <div class="mb-3">

                      <div class="d-flex align-items-center gap-2 mb-2">

                        <div
                          class="d-flex align-items-center justify-content-center
                     bg-primary bg-opacity-10 text-primary rounded-3"
                          style="width: 32px; height: 32px;">
                          <i class="bi bi-info-circle"></i>
                        </div>

                        <div>
                          <div class="fw-bold">
                            Basic Information
                          </div>

                          <small class="text-body-secondary">
                            Equipment specifications and placement
                          </small>
                        </div>

                      </div>

                      <div class="row g-2">

                        <div class="col-12 col-md-6">

                          <div class="border rounded-3 p-3 h-100">

                            <label class="form-label small text-body-secondary mb-1">
                              <i class="bi bi-fire me-1 text-warning"></i>
                              Type
                            </label>

                            <input
                              type="text"
                              class="form-control-plaintext fw-semibold p-0"
                              id="viewType"
                              readonly>

                          </div>

                        </div>

                        <div class="col-6 col-md-3">

                          <div class="border rounded-3 p-3 h-100">

                            <label class="form-label small text-body-secondary mb-1">
                              <i class="bi bi-box-seam me-1 text-primary"></i>
                              Capacity
                            </label>

                            <input
                              type="text"
                              class="form-control-plaintext fw-semibold p-0"
                              id="viewCapacity"
                              readonly>

                          </div>

                        </div>

                        <div class="col-6 col-md-3">

                          <div class="border rounded-3 p-3 h-100">

                            <label class="form-label small text-body-secondary mb-1">
                              <i class="bi bi-shield-check me-1 text-success"></i>
                              Fire Class
                            </label>

                            <input
                              type="text"
                              class="form-control-plaintext fw-semibold p-0"
                              id="viewClass"
                              readonly>

                          </div>

                        </div>

                        <div class="col-12 col-md-6">

                          <div class="border rounded-3 p-3 h-100">

                            <label class="form-label small text-body-secondary mb-1">
                              <i class="bi bi-pin-map me-1 text-danger"></i>
                              Placement
                            </label>

                            <input
                              type="text"
                              class="form-control-plaintext fw-semibold p-0"
                              id="viewPlacement"
                              readonly>

                          </div>

                        </div>

                        <div class="col-12 col-md-6">

                          <div class="border rounded-3 p-3 h-100">

                            <label class="form-label small text-body-secondary mb-1">
                              <i class="bi bi-geo-alt me-1 text-primary"></i>
                              Location
                            </label>

                            <input
                              type="text"
                              class="form-control-plaintext fw-semibold p-0"
                              id="viewLocation"
                              readonly>

                          </div>

                        </div>

                      </div>

                    </div>

                    <div class="mb-3">

                      <div class="d-flex align-items-center gap-2 mb-2">

                        <div
                          class="d-flex align-items-center justify-content-center
                     bg-warning bg-opacity-10 text-warning rounded-3"
                          style="width: 32px; height: 32px;">
                          <i class="bi bi-calendar3"></i>
                        </div>

                        <div>
                          <div class="fw-bold">
                            Important Dates
                          </div>

                          <small class="text-body-secondary">
                            Manufacturing and expiration information
                          </small>
                        </div>

                      </div>

                      <div class="row g-2">

                        <div class="col-12 col-md-6">
                          <div class="border rounded-3 p-3">
                            <label class="form-label small text-body-secondary mb-1">
                              <i class="bi bi-calendar-event me-1 text-secondary"></i>
                              Manufactured Date
                            </label>
                            <input
                              type="date"
                              class="form-control-plaintext fw-semibold p-0"
                              id="viewManufacturedDate"
                              readonly>
                          </div>
                        </div>

                        <div class="col-12 col-md-6">
                          <div class="border rounded-3 p-3">
                            <label class="form-label small text-body-secondary mb-1">
                              <i class="bi bi-arrow-repeat me-1 text-secondary"></i>
                              Last Refilled Date
                            </label>
                            <input
                              type="date"
                              class="form-control-plaintext fw-semibold p-0"
                              id="viewLastRefilledDate"
                              readonly>
                          </div>
                        </div>

                        <div class="col-12 col-md-6">

                          <div class="border rounded-3 p-3">

                            <label class="form-label small text-body-secondary mb-1">
                              <i class="bi bi-calendar-x me-1 text-danger"></i>
                              Expiration Date
                            </label>

                            <input
                              type="date"
                              class="form-control-plaintext fw-semibold p-0"
                              id="viewExpirationDate"
                              readonly>

                            <small class="text-body-secondary d-block mt-1">
                              Automatically calculated as 3 years from the manufacturing or refill date.
                            </small>

                          </div>

                        </div>

                      </div>

                    </div>

                    <div>

                      <div class="d-flex align-items-center gap-2 mb-2">

                        <div
                          class="d-flex align-items-center justify-content-center
                     bg-secondary bg-opacity-10 text-secondary rounded-3"
                          style="width: 32px; height: 32px;">
                          <i class="bi bi-chat-left-text"></i>
                        </div>

                        <div>
                          <div class="fw-bold">
                            Remarks
                          </div>

                          <small class="text-body-secondary">
                            Additional information
                          </small>
                        </div>

                      </div>

                      <div class="border rounded-3 p-3 bg-light-subtle">

                        <textarea class="form-control-plaintext text-body-secondary p-0" id="viewRemarks" rows="2" readonly></textarea>

                      </div>
                    </div>
                  </div>

                  <div class="modal-footer border-0 bg-light-subtle px-3 px-md-4 py-3">

                    <button
                      type="button"
                      class="btn btn-light border px-3"
                      data-bs-dismiss="modal">
                      Close
                    </button>

                  </div>

                </div>

              </div>

            </div>
            <script>
              const manufacturedDate = document.getElementById('manufacturedDate');
              const expirationDate = document.getElementById('expirationDate');

              manufacturedDate.addEventListener('change', function() {

                if (!this.value) {
                  expirationDate.value = '';
                  return;
                }

                const date = new Date(this.value + 'T00:00:00');

                date.setFullYear(date.getFullYear() + 3);

                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');

                expirationDate.value = `${year}-${month}-${day}`;
              });
            </script>

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

    const codeInput = document.getElementById('extinguisherCode');
    const codeFeedback = document.getElementById('extinguisherCodeFeedback');
    const addFireExtinguisherBtn = document.getElementById('addFireExtinguisherBtn');

    let codeCheckTimeout;
    let isCodeDuplicate = false;

    function checkFireExtinguisherCode(code) {

      clearTimeout(codeCheckTimeout);

      codeFeedback.textContent = '';
      codeFeedback.className = 'small mt-1';

      isCodeDuplicate = false;
      addFireExtinguisherBtn.disabled = false;

      if (code === '') {

        codeInput.classList.remove(
          'is-valid',
          'is-invalid'
        );

        return;
      }

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

              addFireExtinguisherBtn.disabled = true;

            } else {

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

            addFireExtinguisherBtn.disabled = true;

          });

      }, 400);
    }

    codeInput.addEventListener('input', function() {

      const code = this.value.trim();

      checkFireExtinguisherCode(code);

    });

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

            codeInput.value = result.code;

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

    const addFireExtinguisherModal =
      document.getElementById(
        'addFireExtinguisherModal'
      );

    if (addFireExtinguisherModal) {

      addFireExtinguisherModal.addEventListener(
        'shown.bs.modal',
        function() {

          if (codeInput.value.trim() === '') {

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

    const addForm = addFireExtinguisherBtn.closest('form');

    if (addForm) {

      addForm.addEventListener('submit', function(event) {

        const code = codeInput.value.trim();

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

    const expiringExtinguisherResults =
      document.getElementById('expiringExtinguisherResults');

    if (expiringExtinguisherResults) {

      expiringExtinguisherResults.addEventListener(
        'click',
        function(event) {

          const button =
            event.target.closest('.view-extinguisher-btn');

          if (!button) {
            return;
          }

          const id = button.dataset.id;

          fetch(
              `backend/controller/FireExtinguisherController.php?action=get&id=${id}`
            )

            .then(response => {

              if (!response.ok) {
                throw new Error(
                  'Failed to fetch fire extinguisher.'
                );
              }

              return response.json();

            })

            .then(result => {

              if (!result.success) {

                alert(
                  result.message ||
                  'Failed to load fire extinguisher.'
                );

                return;
              }

              const data = result.data;

              document.getElementById(
                'viewExtinguisherCode'
              ).value = data.extinguisher_code ?? '';

              document.getElementById(
                'viewType'
              ).value = data.type ?? '';

              document.getElementById(
                'viewCapacity'
              ).value = data.capacity ?? '';

              document.getElementById(
                'viewClass'
              ).value = data.class ?? '';

              document.getElementById(
                'viewPlacement'
              ).value = data.placement ?? '';

              document.getElementById(
                'viewLocation'
              ).value = data.location ?? '';

              document.getElementById(
                'viewConditionStatus'
              ).value = data.condition_status ?? '';

              document.getElementById(
                'viewManufacturedDate'
              ).value = data.manufactured_date ?? '';

              document.getElementById(
                'viewLastRefilledDate'
              ).value = data.refilled_date ?? '';

              document.getElementById(
                'viewExpirationDate'
              ).value = data.expiration_date ?? '';

              document.getElementById(
                'viewRemarks'
              ).value = data.remarks ?? '';

             

              
            })

            .catch(error => {

              console.error(error);

              alert(
                'Something went wrong while loading fire extinguisher.'
              );

            });

        }
      );

    }
  </script>
  <?php include_once 'notification/session_timeout.php'; ?>

</body>

</html>