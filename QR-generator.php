<?php 
$pageTitle = "QR Code Generator"; 
include 'backend/controller/QRCodeGeneratorController.php';
$result = getAllFireExtinguishersCode();
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

    <div class="container py-4">

      <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
          <h3 class="mb-1">QR Codes</h3>
          <p class="text-muted mb-0">
            Select fire extinguishers to print their QR codes.
          </p>
        </div>

        <button type="button" class="btn btn-primary" onclick="printSelected()">
          Print Selected
        </button>
      </div>

      <!-- Select All -->
      <div class="card">
        <div class="card-header">
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              id="selectAll"
              onchange="toggleSelectAll(this)">

            <label class="form-check-label fw-semibold" for="selectAll">
              Select All
            </label>
          </div>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

              <thead>
                <tr>
                  <th width="50"></th>
                  <th>Extinguisher Code</th>
                  <th>Type</th>
                  <th>Location</th>
                </tr>
              </thead>

              <tbody>

                <?php if ($result->num_rows > 0): ?>
                  <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>

                      <td>
                        <div class="form-check">
                          <input
                            class="form-check-input extinguisher-checkbox"
                            type="checkbox"
                            value="<?= htmlspecialchars($row['extinguisher_code']) ?>">
                        </div>
                      </td>

                      <td>
                        <strong>
                          <?= htmlspecialchars($row['extinguisher_code']) ?>
                        </strong>
                      </td>

                      <td>
                        <?= htmlspecialchars($row['type']) ?>
                      </td>

                      <td>
                        <?= htmlspecialchars($row['location']) ?>
                      </td>

                    </tr>

                  <?php endwhile; ?>

                <?php else: ?>

                  <tr>
                    <td colspan="4" class="text-center py-4">
                      No fire extinguishers found.
                    </td>
                  </tr>

                <?php endif; ?>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>


    <script>
      function toggleSelectAll(selectAllCheckbox) {

        const checkboxes = document.querySelectorAll('.extinguisher-checkbox');

        checkboxes.forEach(function(checkbox) {
          checkbox.checked = selectAllCheckbox.checked;
        });
      }


      function printSelected() {

        const selected = [];

        document.querySelectorAll('.extinguisher-checkbox:checked')
          .forEach(function(checkbox) {
            selected.push(checkbox.value);
          });


        if (selected.length === 0) {
          alert('Please select at least one fire extinguisher.');
          return;
        }


        // Pass selected QR codes to print page
        const codes = encodeURIComponent(selected.join(','));

        window.open(
          'helpers/print-qr.php?codes=' + codes,
          '_blank'
        );
      }
    </script>



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
</body>

</html>