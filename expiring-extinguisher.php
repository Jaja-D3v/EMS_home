<?php 
require_once 'backend/authentication/SessionChecker.php'; 
require_once 'backend/controller/FireExtinguisherController.php';

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

        <!-- CONTENT HERE -->
        <?php

        $limit = 10;

        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

        if ($page < 1) {
            $page = 1;
        }

        $offset = ($page - 1) * $limit;

        $info = getAllDeletedFireExtinguishers($limit, $offset);

        $totalRecords = getTotalDeletedFireExtinguishers();

        $totalPages = (int) ceil($totalRecords / $limit);
        ?>

        <div class="container-fluid py-0">

            <!-- ============================= -->
            <!-- PAGE HEADER -->
            <!-- ============================= -->

            <div
                class="card border-0 shadow-sm text-white mb-1 mt-0 overflow-hidden"
                style="background: linear-gradient(135deg, #343a40, #6c757d);">

                <div class="card-body p-4">

                    <div class="row align-items-center g-3">

                        <!-- Icon -->
                        <div class="col-auto">

                            <div class="bg-white bg-opacity-10 rounded-3 p-3 fs-3">

                                <i class="bi bi-trash3"></i>

                            </div>

                        </div>

                        <!-- Title & Description -->
                        <div class="col">

                            <h2 class="fw-bold mb-1">
                                Fire Extinguishers Expiring Soon
                            </h2>

                            <p class="mb-0 text-white-50">
                                View fire extinguishers expiring within the next two months.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="body flex-grow-1">

            <div class="container-fluid py-0">

                <div class="container py-4">

                    <!-- ============================= -->
                    <!-- SEARCH -->
                    <!-- ============================= -->

                    <div class="d-flex flex-column flex-md-row gap-2 mb-3">

                        <div class="input-group">

                            <span class="input-group-text bg-body border-end-0">

                                <i class="bi bi-search text-primary"></i>

                            </span>

                            <input
                                type="text"
                                id="searchDeletedExtinguisher"
                                class="form-control border-start-0 ps-1"
                                placeholder="Search FE code or location...">

                        </div>

                    </div>


                    <!-- ============================= -->
                    <!-- DELETED FIRE EXTINGUISHERS -->
                    <!-- ============================= -->

                    <div id="deletedExtinguisherContainer">

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

                            ?>

                            <div
                                class="card border border-secondary-subtle shadow-sm mb-2 extinguisher-card overflow-hidden">

                                <div class="card-body p-2 p-md-3">

                                    <!-- ============================= -->
                                    <!-- TOP SECTION -->
                                    <!-- ============================= -->

                                    <div class="d-flex align-items-center gap-3">

                                        <!-- Icon -->

                                        <div
                                            class="d-flex align-items-center justify-content-center
                                           bg-secondary bg-opacity-10 text-secondary
                                           rounded-3 flex-shrink-0"
                                            style="width: 50px; height: 50px;">

                                            <i class="bi bi-trash3 fs-4"></i>

                                        </div>


                                        <!-- Title -->

                                        <div class="flex-grow-1 min-width-0">

                                            <div class="mb-1">

                                                <span
                                                    class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1"
                                                    style="font-size: 0.65rem;">

                                                    <i class="bi bi-archive me-1"></i>

                                                    Deleted

                                                </span>

                                            </div>

                                            <div class="d-flex align-items-center gap-2 flex-wrap">

                                                <div class="fw-bold fs-5 lh-sm">

                                                    Fire Extinguisher

                                                </div>

                                                <span
                                                    class="text-body-secondary small extinguisher-code">

                                                    <?= htmlspecialchars($data['extinguisher_code']) ?>

                                                </span>

                                            </div>

                                        </div>


                                        <!-- Status -->

                                        <div
                                            class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-sm-end gap-2">

                                            <span
                                                class="badge <?= $badgeClass ?> rounded-pill px-3 py-2 text-nowrap">

                                                <i class="bi <?= $statusIcon ?> me-1"></i>

                                                <?= htmlspecialchars($condition) ?>

                                            </span>

                                        </div>

                                    </div>


                                    <!-- ============================= -->
                                    <!-- DETAILS -->
                                    <!-- ============================= -->

                                    <div class="row g-2 g-md-3 mt-2 align-items-center">


                                        <!-- FE Code -->

                                        <div class="col-6 col-md-2">

                                            <div class="d-flex align-items-center gap-2">

                                                <div
                                                    class="d-flex align-items-center justify-content-center
                                                   bg-secondary bg-opacity-10 text-secondary
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


                                        <!-- Capacity -->

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


                                        <!-- Type -->

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


                                        <!-- Location -->

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

                                                    <div
                                                        class="fw-semibold small text-truncate extinguisher-location"
                                                        title="<?= htmlspecialchars($data['location']) ?>">

                                                        <?= htmlspecialchars($data['location']) ?>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>


                                        <!-- Branch -->

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


                                        <!-- View -->

                                        <div class="col-12 col-md-2">

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-secondary w-100 px-3 view-extinguisher-btn"
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


                        <!-- ============================= -->
                        <!-- NO DATA -->
                        <!-- ============================= -->

                        <?php if (empty($info)): ?>

                            <div
                                class="border rounded-3 text-center text-body-secondary py-5 px-3 my-3 shadow-sm">

                                <div class="py-3">

                                    <i class="bi bi-trash3 fs-1 d-block mb-3"></i>

                                    <div class="fw-semibold fs-6">

                                        No deleted fire extinguishers.

                                    </div>

                                    <small class="text-body-secondary">

                                        Deleted fire extinguishers will appear here.

                                    </small>

                                </div>

                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- ============================= -->
                    <!-- SEARCH SCRIPT -->
                    <!-- ============================= -->

                    <script>
                        const deletedSearchInput =
                            document.getElementById('searchDeletedExtinguisher');

                        if (deletedSearchInput) {

                            deletedSearchInput.addEventListener('input', function() {

                                const searchValue =
                                    this.value.trim().toLowerCase();

                                const cards =
                                    document.querySelectorAll(
                                        '#deletedExtinguisherContainer .extinguisher-card'
                                    );

                                let visibleCount = 0;

                                cards.forEach(card => {

                                    const codeElement =
                                        card.querySelector('.extinguisher-code');

                                    const locationElement =
                                        card.querySelector('.extinguisher-location');

                                    const code = codeElement ?
                                        codeElement.textContent.trim().toLowerCase() :
                                        '';

                                    const location = locationElement ?
                                        locationElement.textContent.trim().toLowerCase() :
                                        '';

                                    const match =
                                        searchValue === '' ||
                                        code.includes(searchValue) ||
                                        location.includes(searchValue);

                                    if (match) {

                                        card.style.display = '';

                                        visibleCount++;

                                    } else {

                                        card.style.display = 'none';

                                    }

                                });

                            });

                        }
                    </script>


                    <!-- ============================= -->
                    <!-- PAGINATION -->
                    <!-- ============================= -->

                    <?php if ($totalPages > 1): ?>

                        <div
                            class="position-fixed bottom-0 start-0 end-0 bg-body border-top shadow py-2">

                            <div class="container-fluid px-2 px-sm-3 px-md-4">

                                <div
                                    class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">

                                    <!-- Showing -->

                                    <div
                                        class="text-body-secondary small text-center text-sm-start">

                                        Showing

                                        <strong>
                                            <?= min($offset + 1, $totalRecords) ?>
                                        </strong>

                                        -

                                        <strong>
                                            <?= min($offset + $limit, $totalRecords) ?>
                                        </strong>

                                        of

                                        <strong>
                                            <?= $totalRecords ?>
                                        </strong>

                                        deleted fire extinguishers

                                    </div>


                                    <!-- Pagination -->

                                    <nav aria-label="Deleted fire extinguisher pagination">

                                        <ul class="pagination pagination-sm mb-0">

                                            <!-- Previous -->

                                            <li
                                                class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">

                                                <a
                                                    class="page-link px-2 px-sm-3"
                                                    href="deleted-extinguisher.php?page=<?= max(1, $page - 1) ?>"
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
                                                        href="deleted-extinguisher.php?page=<?= $i ?>">

                                                        <?= $i ?>

                                                    </a>

                                                </li>

                                            <?php endfor; ?>


                                            <!-- Next -->

                                            <li
                                                class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">

                                                <a
                                                    class="page-link px-2 px-sm-3"
                                                    href="deleted-extinguisher.php?page=<?= min($totalPages, $page + 1) ?>"
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
    </script>
    <?php include_once 'notification/session_timeout.php'; ?>

</body>

</html>