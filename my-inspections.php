<?php

require_once 'backend/authentication/SessionChecker.php';
require_once 'backend/controller/MyInspectionsController.php';

$pageData = getMyInspectionsPageData();

$myInspections = $pageData['myInspections'];
$summary = $pageData['summary'];

$totalInspections = $pageData['totalInspections'];
$totalPending = $pageData['totalPending'];
$totalApproved = $pageData['totalApproved'];

$perPage = $pageData['perPage'];
$currentPage = $pageData['currentPage'];
$totalPages = $pageData['totalPages'];
$offset = $pageData['offset'];

$search = $pageData['search'];
$dateFilter = $pageData['dateFilter'];
$statusFilter = $pageData['statusFilter'];

// Records already filtered and paginated by the backend.
$paginatedInspections = $myInspections;

?>

<!DOCTYPE html>
<html lang="en">

<?php include 'partials/header.php'; ?>

<body>

  <?php include 'partials/side-nav.php'; ?>

  <div class="wrapper d-flex flex-column min-vh-100">

    <?php include 'partials/header-nav.php'; ?>

    <main class="body flex-grow-1 px-3 py-4">

      <div class="container-fluid">

        <!-- SUMMARY CARDS -->
        <div class="row g-3 mb-4">

          <!-- TOTAL -->
          <div class="col-12 col-sm-6 col-xl-4">

            <div class="card border-0 shadow-sm h-100">

              <div class="card-body p-3">

                <div class="d-flex align-items-center
                                            justify-content-between">

                  <div>

                    <div class="text-body-secondary mb-2"
                      style="font-size: 12px;">
                      Total Inspections
                    </div>

                    <h4 class="fw-semibold mb-0">
                      <?= $summary['total'] ?>
                    </h4>

                  </div>

                  <div class="rounded-3 bg-primary-subtle
                                                text-primary d-flex
                                                align-items-center
                                                justify-content-center"
                    style="width: 44px; height: 44px;">

                    <svg xmlns="http://www.w3.org/2000/svg"
                      width="22" height="22"
                      fill="none" viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="1.8">
                      <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2M9 12h6m-6 4h6" />
                    </svg>

                  </div>

                </div>

              </div>

            </div>

          </div>

          <!-- PENDING -->
          <div class="col-12 col-sm-6 col-xl-4">

            <div class="card border-0 shadow-sm h-100">

              <div class="card-body p-3">

                <div class="d-flex align-items-center
                                            justify-content-between">

                  <div>

                    <div class="text-body-secondary mb-2"
                      style="font-size: 12px;">
                      Pending Approval
                    </div>

                    <h4 class="fw-semibold mb-0">
                      <?= $totalPending ?>
                    </h4>

                  </div>

                  <div class="rounded-3 bg-warning-subtle
                                                text-warning d-flex
                                                align-items-center
                                                justify-content-center"
                    style="width: 44px; height: 44px;">

                    <svg xmlns="http://www.w3.org/2000/svg"
                      width="22" height="22"
                      fill="none" viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="1.8">
                      <circle cx="12" cy="12" r="9" />
                      <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 7v5l3 2" />
                    </svg>

                  </div>

                </div>

              </div>

            </div>

          </div>

          <!-- APPROVED -->
          <div class="col-12 col-sm-6 col-xl-4">

            <div class="card border-0 shadow-sm h-100">

              <div class="card-body p-3">

                <div class="d-flex align-items-center
                                            justify-content-between">

                  <div>

                    <div class="text-body-secondary mb-2"
                      style="font-size: 12px;">
                      Approved
                    </div>

                    <h4 class="fw-semibold mb-0">
                      <?= $totalApproved ?>
                    </h4>

                  </div>

                  <div class="rounded-3 bg-success-subtle
                                                text-success d-flex
                                                align-items-center
                                                justify-content-center"
                    style="width: 44px; height: 44px;">

                    <svg xmlns="http://www.w3.org/2000/svg"
                      width="22" height="22"
                      fill="none" viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="1.8">
                      <circle cx="12" cy="12" r="9" />
                      <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m8 12 2.5 2.5L16 9" />
                    </svg>

                  </div>

                </div>

              </div>

            </div>

          </div>

        </div>

        <!-- MAIN TABLE CARD -->
        <div class="card border-0 shadow-sm">

          <!-- CARD HEADER -->
          <div class="card-header bg-body border-bottom py-3">

            <div class="d-flex flex-column flex-sm-row
                                    align-items-sm-center
                                    justify-content-between gap-2">

              <div>

                <h6 class="fw-semibold mb-1"
                  style="font-size: 13px;">
                  Inspection History
                </h6>

                <small class="text-body-secondary"
                  style="font-size: 11px;">
                  Review your inspection records and
                  approval status.
                </small>

              </div>

              <span class="badge bg-primary-subtle text-primary"
                style="font-size: 11px;">
                <?= $totalInspections ?> Records
              </span>

            </div>

          </div>

          <!-- SEARCH AND FILTER -->
          <form method="GET" class="card border-0 shadow-sm mb-2">

            <div class="card-body p-3">

              <div class="row g-2 align-items-end">

                <!-- SEARCH -->
                <div class="col-12 col-md-6 col-xl">

                  <label class="form-label text-body-secondary mb-1"
                    style="font-size: 11px;">
                    Search
                  </label>

                  <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search code, location, or type..."
                    value="<?= htmlspecialchars(
                              $search,
                              ENT_QUOTES,
                              'UTF-8'
                            ) ?>"
                    style="font-size: 12px;">

                </div>

                <!-- DATE FILTER -->
                <div class="col-12 col-sm-6 col-md-3 col-xl-3">

                  <label class="form-label text-body-secondary mb-1"
                    style="font-size: 11px;">
                    Inspection Date
                  </label>

                  <select name="date"
                    class="form-select"
                    style="font-size: 12px;">

                    <?php foreach (
                      [
                        'all' => 'All Inspection Dates',
                        'today' => 'Today',
                        'yesterday' => 'Yesterday',
                        '7' => 'Last 7 Days',
                        '30' => 'Last 30 Days',
                      ] as $value => $label
                    ): ?>

                      <option
                        value="<?= $value ?>"
                        <?= $dateFilter === $value ? 'selected' : '' ?>>

                        <?= $label ?>

                      </option>

                    <?php endforeach; ?>

                  </select>

                </div>

                <!-- STATUS FILTER -->
                <div class="col-12 col-sm-6 col-md-3 col-xl-2">

                  <label class="form-label text-body-secondary mb-1"
                    style="font-size: 11px;">
                    Status
                  </label>

                  <select name="status"
                    class="form-select"
                    style="font-size: 12px;">

                    <option value="all"
                      <?= $statusFilter === 'all' ? 'selected' : '' ?>>
                      All Statuses
                    </option>

                    <option value="Pending"
                      <?= $statusFilter === 'Pending' ? 'selected' : '' ?>>
                      Pending
                    </option>

                    <option value="Approved"
                      <?= $statusFilter === 'Approved' ? 'selected' : '' ?>>
                      Approved
                    </option>

                  </select>

                </div>

                <!-- ACTIONS -->
                <div class="col-6 col-sm-6 col-md-auto">

                  <button type="submit"
                    class="btn btn-primary w-100"
                    style="font-size: 12px;">
                    Filter
                  </button>

                </div>

                <div class="col-6 col-sm-6 col-md-auto">

                  <a href="<?= htmlspecialchars(
                              basename($_SERVER['PHP_SELF']),
                              ENT_QUOTES,
                              'UTF-8'
                            ) ?>"
                    class="btn btn-light border w-100"
                    style="font-size: 12px;">
                    Clear
                  </a>

                </div>

              </div>

            </div>

          </form>

          <!-- DESKTOP TABLE -->
          <div class="table-responsive d-none d-md-block"
            style="min-height: 390px;">

            <table class="table table-hover align-middle mb-0">

              <thead class="table-light">

                <tr>

                  <th class="px-3"
                    style="font-size: 12px;">
                    Date Inspected
                  </th>

                  <th style="font-size: 12px;">
                    Extinguisher Code
                  </th>

                  <th style="font-size: 12px;">
                    Location
                  </th>

                  <th style="font-size: 12px;">
                    Type
                  </th>

                  <th style="font-size: 12px;">
                    Status
                  </th>

                  <th class="text-end px-3"
                    style="font-size: 12px;">
                    Action
                  </th>

                </tr>

              </thead>

              <tbody>

                <?php if (!empty($paginatedInspections)): ?>

                  <?php foreach (
                    $paginatedInspections as $inspection
                  ): ?>

                    <?php
                    $inspectionDate = strtotime(
                      $inspection['date_inspected']
                    );

                    $isApproved =
                      $inspection['evaluation_status']
                      === 'Approved';
                    ?>

                    <tr>

                      <td class="px-3">

                        <div class="fw-semibold"
                          style="font-size: 12px;">

                          <?= date(
                            'M d, Y',
                            $inspectionDate
                          ) ?>

                        </div>

                        <small class="text-body-secondary">

                          <?= date(
                            'h:i A',
                            $inspectionDate
                          ) ?>

                        </small>

                      </td>

                      <td class="fw-semibold"
                        style="font-size: 12px;">

                        <?= htmlspecialchars(
                          $inspection['extinguisher_code'],
                          ENT_QUOTES,
                          'UTF-8'
                        ) ?>

                      </td>

                      <td style="font-size: 12px;">

                        <?= htmlspecialchars(
                          $inspection['location'],
                          ENT_QUOTES,
                          'UTF-8'
                        ) ?>

                      </td>

                      <td style="font-size: 12px;">

                        <div>
                          <?= htmlspecialchars(
                            $inspection['type'],
                            ENT_QUOTES,
                            'UTF-8'
                          ) ?>
                        </div>

                        <small class="text-body-secondary">
                          <?= htmlspecialchars(
                            $inspection['capacity'],
                            ENT_QUOTES,
                            'UTF-8'
                          ) ?>
                        </small>

                      </td>

                      <td>

                        <?php if ($isApproved): ?>

                          <span class="badge bg-success-subtle text-success fw-normal"
                            style="font-size: 11px;">

                            <span class="me-1">✓</span>
                            Approved

                          </span>

                        <?php else: ?>

                          <span class="badge bg-warning-subtle text-warning-emphasis fw-normal"
                            style="font-size: 11px;">

                            <span class="me-1">◷</span>
                            Pending

                          </span>

                        <?php endif; ?>

                      </td>

                      <td class="text-end px-3">

                        <button
                          type="button"
                          class="btn btn-sm btn-outline-primary"
                          style="font-size: 12px;"
                          onclick="document.getElementById('inspectionDetails<?= (int) $inspection['inspect_id'] ?>').showModal();">

                          View Details

                        </button>

                      </td>

                    </tr>

                  <?php endforeach; ?>

                <?php else: ?>

                  <tr>
                    <td colspan="6"
                      class="text-center py-5">

                      <div class="text-body-secondary mb-2"
                        style="font-size: 28px;">
                        ⌕
                      </div>

                      <div class="fw-semibold mb-1"
                        style="font-size: 13px;">
                        No inspections found
                      </div>

                      <div class="text-body-secondary" style="font-size: 12px;">
                        Try changing your search, date, or status filter.
                      </div>

                    </td>
                  </tr>

                <?php endif; ?>

              </tbody>

            </table>

          </div>

          <!-- MOBILE LIST -->
          <div class="d-md-none">

            <?php if (!empty($paginatedInspections)): ?>

              <?php foreach (
                $paginatedInspections as $inspection
              ): ?>

                <?php
                $inspectionDate = strtotime(
                  $inspection['date_inspected']
                );

                $isApproved =
                  $inspection['evaluation_status']
                  === 'Approved';
                ?>

                <div class="border-bottom p-3">

                  <div class="d-flex align-items-start
                                                justify-content-between
                                                gap-2 mb-3">

                    <div>

                      <div class="fw-semibold mb-1"
                        style="font-size: 13px;">

                        <?= htmlspecialchars(
                          $inspection['extinguisher_code'],
                          ENT_QUOTES,
                          'UTF-8'
                        ) ?>

                      </div>

                      <small class="text-body-secondary">

                        <?= date(
                          'M d, Y h:i A',
                          $inspectionDate
                        ) ?>

                      </small>

                    </div>

                    <?php if ($isApproved): ?>

                      <span class="badge bg-success-subtle text-success fw-normal">
                        Approved
                      </span>

                    <?php else: ?>

                      <span class="badge bg-warning-subtle text-warning-emphasis fw-normal">
                        Pending
                      </span>

                    <?php endif; ?>

                  </div>

                  <div class="mb-3">

                    <small class="text-body-secondary d-block mb-1">
                      LOCATION
                    </small>

                    <div style="font-size: 12px;">

                      <?= htmlspecialchars(
                        $inspection['location'],
                        ENT_QUOTES,
                        'UTF-8'
                      ) ?>

                    </div>

                  </div>

                  <div class="d-flex align-items-center
                                                justify-content-between gap-2">

                    <div>

                      <small class="text-body-secondary d-block mb-1">
                        TYPE / CAPACITY
                      </small>

                      <div style="font-size: 12px;">

                        <?= htmlspecialchars(
                          $inspection['type'],
                          ENT_QUOTES,
                          'UTF-8'
                        ) ?>

                        ·

                        <?= htmlspecialchars(
                          $inspection['capacity'],
                          ENT_QUOTES,
                          'UTF-8'
                        ) ?>

                      </div>

                    </div>

                    <button
                      type="button"
                      class="btn btn-sm btn-outline-primary flex-shrink-0"
                      style="font-size: 12px;"
                      onclick="document.getElementById('inspectionDetails<?= (int) $inspection['inspect_id'] ?>').showModal();">

                      View Details

                    </button>

                  </div>

                </div>

              <?php endforeach; ?>

            <?php else: ?>

              <div class="text-center p-5">

                <div class="text-body-secondary mb-2"
                  style="font-size: 28px;">
                  ⌕
                </div>

                <div class="fw-semibold mb-1"
                  style="font-size: 13px;">
                  No inspections found
                </div>

                <div class="text-body-secondary" style="font-size: 12px;">
                  Try changing your search, date, or status filter.
                </div>

              </div>

            <?php endif; ?>

          </div>

          <!-- PAGINATION -->
          <div class="card-footer bg-body border-top">

            <div class="d-flex flex-column flex-sm-row
                                    align-items-center
                                    justify-content-between gap-3">

              <small class="text-body-secondary"
                style="font-size: 12px;">

                <?php if ($totalInspections > 0): ?>

                  Showing <?= $offset + 1 ?>
                  to
                  <?= min(
                    $offset + $perPage,
                    $totalInspections
                  ) ?>
                  of <?= $totalInspections ?> entries

                <?php else: ?>

                  Showing 0 of 0 entries

                <?php endif; ?>

              </small>

              <?php if ($totalPages > 1): ?>

                <nav aria-label="Inspection pagination">

                  <ul class="pagination pagination-sm mb-0">

                    <?php
                    $pageUrl = basename(
                      $_SERVER['PHP_SELF']
                    );

                    $pageUrl = basename($_SERVER['PHP_SELF']);

                    $queryParams = [
                      'search' => $search,
                      'date' => $dateFilter,
                      'status' => $statusFilter,
                    ];
                    ?>

                    <!-- PREVIOUS -->
                    <li class="page-item <?= $currentPage <= 1
                                            ? 'disabled'
                                            : '' ?>">

                      <?php if ($currentPage > 1): ?>

                        <a class="page-link"
                          href="<?= htmlspecialchars(
                                  $pageUrl . '?' . http_build_query(
                                    array_merge(
                                      $queryParams,
                                      ['page' => $currentPage - 1]
                                    )
                                  ),
                                  ENT_QUOTES,
                                  'UTF-8'
                                ) ?>">
                          Previous
                        </a>

                      <?php else: ?>

                        <span class="page-link">
                          Previous
                        </span>

                      <?php endif; ?>

                    </li>

                    <!-- PAGE NUMBERS -->
                    <?php
                    if ($totalPages <= 5) {
                      $visiblePages = range(1, $totalPages);
                    } elseif ($currentPage <= 3) {
                      $visiblePages = [1, 2, 3, 4, '...', $totalPages];
                    } elseif ($currentPage >= $totalPages - 2) {
                      $visiblePages = [
                        1,
                        '...',
                        $totalPages - 3,
                        $totalPages - 2,
                        $totalPages - 1,
                        $totalPages
                      ];
                    } else {
                      $visiblePages = [
                        1,
                        '...',
                        $currentPage - 1,
                        $currentPage,
                        $currentPage + 1,
                        '...',
                        $totalPages
                      ];
                    }
                    ?>

                    <?php foreach ($visiblePages as $page): ?>

                      <?php if ($page === '...'): ?>

                        <li class="page-item disabled">
                          <span class="page-link">...</span>
                        </li>

                      <?php else: ?>

                        <li class="page-item <?= $page === $currentPage
                                                ? 'active'
                                                : '' ?>">

                          <a class="page-link"
                            href="<?= htmlspecialchars(
                                    $pageUrl . '?' . http_build_query(
                                      array_merge(
                                        $queryParams,
                                        ['page' => $page]
                                      )
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                  ) ?>">
                            <?= $page ?>
                          </a>

                        </li>

                      <?php endif; ?>

                    <?php endforeach; ?>
                    

                    <!-- NEXT -->
                    <li class="page-item <?= $currentPage >= $totalPages
                                            ? 'disabled'
                                            : '' ?>">

                      <?php if ($currentPage < $totalPages): ?>

                        <a class="page-link"
                          href="<?= htmlspecialchars(
                                  $pageUrl . '?' . http_build_query(
                                    array_merge(
                                      $queryParams,
                                      ['page' => $currentPage + 1]
                                    )
                                  ),
                                  ENT_QUOTES,
                                  'UTF-8'
                                ) ?>">
                          Next
                        </a>

                      <?php else: ?>

                        <span class="page-link">
                          Next
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

    </main>

    <!-- INSPECTION DETAILS DIALOGS -->
    <?php foreach ($paginatedInspections as $inspection): ?>

      <?php
      $inspectionDate = strtotime(
        $inspection['date_inspected']
      );

      $isApproved =
        $inspection['evaluation_status'] === 'Approved';
      ?>

      <dialog
        id="inspectionDetails<?= (int) $inspection['inspect_id'] ?>"
        style="
                    width: min(700px, calc(100% - 24px));
                    max-width: 700px;
                    max-height: 90vh;
                    border: 0;
                    border-radius: 14px;
                    padding: 0;
                    overflow: hidden;
                    box-shadow: 0 20px 60px rgba(0,0,0,.25);
                ">

        <!-- DIALOG HEADER -->
        <div class="bg-body border-bottom">

          <div class="d-flex align-items-center
                                justify-content-between p-4">

            <div>

              <h5 class="fw-semibold mb-1"
                style="font-size: 17px;">
                Inspection Details
              </h5>

              <div class="text-body-secondary"
                style="font-size: 11px;">
                Inspection record and evaluation status.
              </div>

            </div>

            <button type="button"
              class="btn btn-light border-0"
              style="font-size: 22px; line-height: 1;"
              aria-label="Close"
              onclick="this.closest('dialog').close();">
              ×
            </button>

          </div>

        </div>

        <!-- DIALOG BODY -->
        <div class="p-4 bg-body"
          style="max-height: calc(90vh - 140px); overflow-y: auto;">

          <!-- STATUS -->
          <div class="rounded-3 border p-3 mb-4
                        <?= $isApproved
                          ? 'border-success-subtle bg-success-subtle'
                          : 'border-warning-subtle bg-warning-subtle' ?>">

            <div class="text-body-secondary mb-2"
              style="font-size: 10px;">
              EVALUATION STATUS
            </div>

            <?php if ($isApproved): ?>

              <span class="badge bg-success">
                ✓ Approved
              </span>

            <?php else: ?>

              <span class="badge bg-warning text-dark">
                ◷ Pending Approval
              </span>

            <?php endif; ?>

          </div>

          <!-- DETAILS -->
          <div class="card border-0 mb-3">

            <div class="card-body p-0">

              <h6 class="fw-semibold mb-3"
                style="font-size: 13px;">
                Fire Extinguisher Information
              </h6>

              <div class="row g-3">

                <div class="col-12 col-sm-6">

                  <small class="text-body-secondary d-block mb-1">
                    INSPECTION ID
                  </small>

                  <div class="fw-semibold"
                    style="font-size: 12px;">
                    #<?= (int) $inspection['inspect_id'] ?>
                  </div>

                </div>

                <div class="col-12 col-sm-6">

                  <small class="text-body-secondary d-block mb-1">
                    EXTINGUISHER CODE
                  </small>

                  <div class="fw-semibold"
                    style="font-size: 12px;">

                    <?= htmlspecialchars(
                      $inspection['extinguisher_code'],
                      ENT_QUOTES,
                      'UTF-8'
                    ) ?>

                  </div>

                </div>

                <div class="col-12 col-sm-6">

                  <small class="text-body-secondary d-block mb-1">
                    LOCATION
                  </small>

                  <div style="font-size: 12px;">

                    <?= htmlspecialchars(
                      $inspection['location'],
                      ENT_QUOTES,
                      'UTF-8'
                    ) ?>

                  </div>

                </div>

                <div class="col-12 col-sm-6">

                  <small class="text-body-secondary d-block mb-1">
                    TYPE
                  </small>

                  <div style="font-size: 12px;">

                    <?= htmlspecialchars(
                      $inspection['type'],
                      ENT_QUOTES,
                      'UTF-8'
                    ) ?>

                  </div>

                </div>

                <div class="col-12 col-sm-6">

                  <small class="text-body-secondary d-block mb-1">
                    CAPACITY
                  </small>

                  <div style="font-size: 12px;">

                    <?= htmlspecialchars(
                      $inspection['capacity'],
                      ENT_QUOTES,
                      'UTF-8'
                    ) ?>

                  </div>

                </div>

                <div class="col-12 col-sm-6">

                  <small class="text-body-secondary d-block mb-1">
                    DATE INSPECTED
                  </small>

                  <div style="font-size: 12px;">

                    <?= date(
                      'M d, Y h:i A',
                      $inspectionDate
                    ) ?>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- DIALOG FOOTER -->
        <div class="border-top bg-body p-3 text-end">

          <button type="button"
            class="btn btn-secondary"
            style="font-size: 12px;"
            onclick="this.closest('dialog').close();">
            Close
          </button>

        </div>

      </dialog>

    <?php endforeach; ?>

    <?php include 'partials/footer.php'; ?>

  </div>

  <!-- COREUI -->
  <script src="vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
  <script src="vendors/simplebar/js/simplebar.min.js"></script>

  <script>
    const header = document.querySelector("header.header");

    document.addEventListener("scroll", () => {
      if (header) {
        header.classList.toggle(
          "shadow-sm",
          document.documentElement.scrollTop > 0
        );
      }
    });

    // Close dialog when clicking outside its content.
    document.querySelectorAll("dialog").forEach((dialog) => {
      dialog.addEventListener("click", (event) => {
        if (event.target === dialog) {
          dialog.close();
        }
      });
    });
  </script>

  <?php include_once 'notification/session_timeout.php'; ?>

</body>

</html>