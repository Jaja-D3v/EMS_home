<?php
require_once 'backend/authentication/SessionChecker.php';
require_once 'backend/controller/ActivityLogController.php';

$limit = 10;

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

if ($page < 1) {
  $page = 1;
}

$date = isset($_GET['date']) ? $_GET['date'] : '';

$offset = ($page - 1) * $limit;

$activityLogs = getActivityLogsPaginated(
  $limit,
  $offset,
  '',
  $date
);

$totalRecords = getActivityLogsTotal(
  '',
  $date
);

$totalPages = (int) ceil($totalRecords / $limit);

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
    <?php include 'partials/header-nav.php'; ?>
    <div class="container-fluid py-0">

      <div
        class="card border-0 shadow-sm text-white mb-1 mt-0 overflow-hidden"
        style="background: linear-gradient(135deg, #1d0870, #2408f5);">

        <div class="card-body p-4">

          <div class="row align-items-center g-3">

            <!-- Icon -->
            <div class="col-auto">

              <div
                class="bg-white bg-opacity-10 rounded-3 p-3 fs-3">

                <i class="bi bi-clock-history"></i>

              </div>

            </div>

            <!-- Title & Description -->
            <div class="col">

              <h2 class="fw-bold mb-1">
                Activity Logs
              </h2>

              <p class="mb-0 text-white-50">
                Track system activities, user actions, and recent fire extinguisher updates.
              </p>

            </div>

          </div>

        </div>

      </div>

    </div>

    <div class="body flex-grow-1">
      <div class="container-lg px-4">
        <!-- content -->
        <div class="d-flex justify-content-between align-items-center mb-3"> 
          <!-- Date Filter -->
          <form method="GET" class="d-flex align-items-center">
            <div class="d-flex align-items-center gap-2 bg-body border rounded-pill shadow-sm mt-2 px-3 py-1">
              <i class="bi bi-calendar3 text-primary"></i>
              <input type="date" name="date" value="<?= htmlspecialchars($date) ?>" class="form-control form-control-sm border-0 shadow-none bg-transparent fw-semibold px-1" style="width: 160px; cursor: pointer;" onchange="this.form.submit()">
            </div>
          </form> <!-- Total Activities -->
          <div class="text-body-secondary small text-end">
            <i class="bi bi-info-circle me-1"></i>
            <?php
            if (!empty($date)): ?>
              <?= $totalRecords ?> activities found for <strong><?= date('F d, Y', strtotime($date)) ?></strong> <?php else: ?>
              <?= $totalRecords ?> total activities
            <?php endif; ?>
          </div>
        </div>

        <!-- content here -->

        <?php if ($activityLogs && mysqli_num_rows($activityLogs) > 0): ?>

          <?php while ($log = mysqli_fetch_assoc($activityLogs)): ?>

            <?php
            $action = strtolower($log['action']);

            if ($action === 'add fire extinguisher') {
              $icon = 'bi-plus-lg';
              $color = 'success';
              $title = 'Fire Extinguisher Added';
            } elseif ($action === 'update fire extinguisher') {
              $icon = 'bi-pencil';
              $color = 'warning';
              $title = 'Fire Extinguisher Updated';
            } elseif ($action === 'delete fire extinguisher') {
              $icon = 'bi-trash';
              $color = 'danger';
              $title = 'Fire Extinguisher Deleted';
            } elseif ($action === 'inspect fire extinguisher') {
              $icon = 'bi-clipboard-check';
              $color = 'info';
              $title = 'Inspection Completed';
            } else {
              $icon = 'bi-activity';
              $color = 'secondary';
              $title = $log['action'];
            }
            ?>

            <div class="bg-white rounded-4 px-3 py-3 mb-2
                border border-secondary-subtle shadow-sm">

              <div class="d-flex flex-column flex-md-row
                  align-items-start gap-3">

                <!-- Icon -->
                <div class="bg-<?= $color ?> bg-opacity-10
                    text-<?= $color ?>
                    rounded-circle
                    p-3
                    flex-shrink-0">

                  <i class="bi <?= $icon ?>"></i>

                </div>

                <!-- Activity Content -->
                <div class="flex-grow-1 min-width-0 w-100">

                  <!-- Title + Badge -->
                  <div class="d-flex
                      flex-column flex-sm-row
                      align-items-start
                      gap-1 gap-sm-2">

                    <span class="fw-semibold">
                      <?= htmlspecialchars($title) ?>
                    </span>

                    <span class="badge rounded-pill
                         bg-<?= $color ?>-subtle
                         text-<?= $color ?>">

                      <?= htmlspecialchars($log['action']) ?>

                    </span>

                  </div>

                  <!-- Description -->
                  <div class="small text-body-secondary mt-1 text-break">

                    <?= htmlspecialchars($log['description']) ?>

                  </div>

                  <!-- User -->
                  <div class="small text-body-secondary mt-2">

                    <i class="bi bi-person me-1"></i>

                    <?= htmlspecialchars($log['user_name']) ?>

                  </div>

                  <!-- Time -->
                  <div class="small text-body-secondary mt-2">

                    <i class="bi bi-clock me-1"></i>

                    <?= date('M d, Y • h:i A', strtotime($log['created_at'])) ?>

                  </div>

                </div>

              </div>

            </div>

          <?php endwhile; ?>

        <?php else: ?>

          <div class="text-center text-body-secondary py-4">

            <i class="bi bi-activity fs-3 d-block mb-2"></i>

            No activity found.

          </div>

        <?php endif; ?>

        <!-- pagination -->
        <?php if ($totalPages > 1): ?>

          <div
            class="position-fixed bottom-0 start-0 end-0 bg-body border-top shadow-sm py-2"
            style="z-index: 1020;">

            <div class="container-fluid px-3 px-md-4">

              <div class="d-flex flex-column flex-sm-row
                  justify-content-between
                  align-items-center
                  gap-2">

                <!-- Showing -->
                <div class="text-body-secondary small
                    text-center text-sm-start">

                  Showing
                  <strong><?= min($offset + 1, $totalRecords) ?></strong>
                  -
                  <strong><?= min($offset + $limit, $totalRecords) ?></strong>
                  of
                  <strong><?= $totalRecords ?></strong>
                  activities

                </div>


                <!-- Pagination -->
                <nav
                  aria-label="Activity log pagination"
                  class="w-100 w-sm-auto">

                  <ul class="pagination pagination-sm
                     justify-content-center
                     flex-wrap
                     mb-0">

                    <!-- Previous -->
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">

                      <a
                        class="page-link px-2 px-sm-3"
                        href="activity-log.php?date=<?= urlencode($date) ?>&page=<?= max(1, $page - 1) ?>"
                        aria-label="Previous">

                        <i class="bi bi-chevron-left"></i>

                        <span class="d-none d-sm-inline ms-1">
                          Previous
                        </span>

                      </a>

                    </li>


                    <!-- Page Numbers -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>

                      <li
                        class="page-item <?= ($i == $page) ? 'active' : '' ?>">

                        <a
                          class="page-link px-2 px-sm-3"
                          href="activity-log.php?date=<?= urlencode($date) ?>&page=<?= $i ?>">

                          <?= $i ?>

                        </a>

                      </li>

                    <?php endfor; ?>


                    <!-- Next -->
                    <li
                      class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">

                      <a
                        class="page-link px-2 px-sm-3"
                        href="activity-log.php?date=<?= urlencode($date) ?>&page=<?= min($totalPages, $page + 1) ?>"
                        aria-label="Next">

                        <span class="d-none d-sm-inline me-1">
                          Next
                        </span>

                        <i class="bi bi-chevron-right"></i>

                      </a>

                    </li>

                  </ul>

                </nav>

              </div>

            </div>

          </div>

        <?php endif; ?>

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

  <?php include_once 'notification/session_timeout.php'; ?>
</body>

</html>