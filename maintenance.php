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
                                class="bg-white bg-opacity-10 rounded-3 p-3 d-flex align-items-center justify-content-center"
                                style="width: 64px; height: 64px;">

                                <i class="bi bi-tools fs-2"></i>

                            </div>
                        </div>

                        <!-- Title & Description -->
                        <div class="col">

                            <h2 class="fw-bold mb-1">
                                Maintenance
                            </h2>

                            <p class="mb-0 text-white-50">
                                Keep your fire extinguishers in top condition
                                with proper maintenance and service records.
                            </p>

                        </div>

                    </div>

                </div>

            </div>
        </div>

        <!-- CONTENT HERE -->

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