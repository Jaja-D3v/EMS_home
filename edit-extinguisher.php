<?php
$pageTitle = "Edit Fire Extinguisher Information";
include 'backend/controller/FireExtinguisherController.php';

$id = $_GET['id'];
$data = getFireExtinguisherById($id);

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
<?php include 'header.php'; ?>

<body>
    <?php include 'side-nav.php'; ?>
    <div class="wrapper d-flex flex-column min-vh-100">
        <?php include 'header-nav.php'; ?>

        <div class="row justify-content-center">
            <div class="col-12 col-lg-9 col-xl-8">
                <!-- here -->
                <div class="card shadow-sm">
                    <!-- Header -->
                    <div class="card-header">
                        <h5 class="mb-0">Edit Fire Extinguisher</h5>
                        <small class="text-body-secondary">
                            Edit the fire extinguisher information below.
                        </small>
                    </div>

                    <div class="card-body">
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
                                    value="<?= htmlspecialchars($data['extinguisher_code']) ?>"
                                    readonly
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
                                    <option value="<?= htmlspecialchars($data['type']) ?>" selected disabled>
                                        <?= htmlspecialchars($data['type']) ?>
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
                                    value="<?= htmlspecialchars($data['capacity']) ?>"
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
                                    value="<?= htmlspecialchars($data['location']) ?>"
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
                                    value="<?= htmlspecialchars($data['date_acquired']) ?>"
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
                                    value="<?= htmlspecialchars($data['expiration_date']) ?>"
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

                                    <option value="<?= htmlspecialchars($data['status']) ?>" selected disabled>
                                        <?= htmlspecialchars($data['status']) ?>
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
                                        Update Info
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>






        <!-- CONTENT HERE -->



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