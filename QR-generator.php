<?php
include 'backend/controller/QRCodeGeneratorController.php';
include 'backend/controller/DropdownBranchController.php';
require_once 'backend/authentication/SessionChecker.php';

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

    <div class="container-fluid py-0">

      <!-- Hero -->
      <div class="card border-0 shadow-sm text-white mb-1 mt-0 overflow-hidden "
        style="background: linear-gradient(135deg, #6d6d6d, #f4f3f3);">
        <div class="card-body p-4">
          <div class="row align-items-center g-3">
            <div class="col-auto">
              <div class="bg-secondary rounded-3 p-3 fs-3">
                <i class="bi bi-qr-code-scan"></i>
              </div>
            </div>
            <div class="col">
              <h2 class="fw-bold mb-1">
                Print QR Code
              </h2>
              <p class="mb-0 text-white-50">
                Select fire extinguishers to print their QR codes.
              </p>
            </div>
            <div class="col-12 col-md-auto">
            </div>
          </div>
        </div>
      </div>


      <!-- Table Card -->
      <div class="card border-0 shadow-sm">

        <!-- Toolbar -->
        <div class="card-header bg-white border-0 p-3">

          <div class="row align-items-center g-3">

            <!-- Search -->
            <div class="col-12 col-lg">

              <div class="input-group">

                <span class="input-group-text bg-white">
                  <i class="bi bi-search"></i>
                </span>

                <input
                  type="text"
                  class="form-control"
                  placeholder="Search code or location..."
                  id="qrSearch">

              </div>

            </div>


            <!-- Branch Filter - ADMIN ONLY -->
            <?php if (strtolower(trim($_SESSION['Role'] ?? '')) === 'admin'): ?>

              <div class="col-12 col-md-auto">

                <select
                  class="form-select"
                  id="branchFilter"
                  style="min-width: 190px;">

                  <option value="all">All Branches</option>

                  <?php
                  $branches = getAllDropdownBranches();

                  if (!empty($branches)):
                    foreach ($branches as $branch):
                  ?>

                      <option
                        value="<?= htmlspecialchars($branch['value']) ?>"
                        <?= (
                          ($_GET['branch'] ?? 'all') === $branch['value']
                        ) ? 'selected' : '' ?>>

                        <?= htmlspecialchars($branch['value']) ?>

                      </option>

                  <?php
                    endforeach;
                  endif;
                  ?>

                </select>

              </div>

            <?php endif; ?>

          </div>

        </div>


        <script>
          // SEARCH BY CODE OR LOCATION
          const qrSearch = document.getElementById('qrSearch');

          if (qrSearch) {

            qrSearch.addEventListener('input', function() {

              const searchValue = this.value
                .trim()
                .toLowerCase();

              const tbody = document.querySelector(
                'table tbody'
              );

              const rows = tbody.querySelectorAll('tr');

              let hasResults = false;

              rows.forEach(row => {

                const checkbox = row.querySelector(
                  '.extinguisher-checkbox'
                );

                // Skip empty/default rows
                if (!checkbox) {
                  return;
                }

                // Extinguisher code
                const code = checkbox.value
                  .toLowerCase();

                // Location
                const location = row.cells[3] ?
                  row.cells[3].textContent
                  .trim()
                  .toLowerCase() :
                  '';

                const match =
                  code.includes(searchValue) ||
                  location.includes(searchValue);

                if (match) {

                  row.style.display = '';
                  hasResults = true;

                } else {

                  row.style.display = 'none';

                }

              });


              // NO RESULTS
              let noResultsRow = document.getElementById(
                'qrNoResults'
              );

              if (!hasResults && searchValue !== '') {

                if (!noResultsRow) {

                  noResultsRow = document.createElement('tr');

                  noResultsRow.id = 'qrNoResults';

                  noResultsRow.innerHTML = `
                    <td
                        colspan="5"
                        class="text-center py-5 text-muted">

                        <i class="bi bi-search fs-3 d-block mb-2"></i>

                        <div class="fw-semibold">
                            No results found
                        </div>

                        <div class="small">
                            No fire extinguisher matches
                            your search.
                        </div>

                    </td>
                `;

                  tbody.appendChild(noResultsRow);

                }

              } else {

                if (noResultsRow) {
                  noResultsRow.remove();
                }

              }

            });

          }
        </script>
        <script>
          document.addEventListener('DOMContentLoaded', function() {

            const branchFilter = document.getElementById('branchFilter');

            if (!branchFilter) {
              return;
            }

            branchFilter.addEventListener('change', function() {

              const selectedBranch = this.value;

              const url = new URL(window.location.href);

              if (selectedBranch === 'all' || selectedBranch === '') {

                url.searchParams.delete('branch');

              } else {

                url.searchParams.set('branch', selectedBranch);

              }

              window.location.href = url.toString();

            });

          });
        </script>

        <!-- Table -->
        <div class="table-responsive">

          <table class="table table-hover align-middle mb-0">

            <thead class="table-light">

              <tr>

                <th width="50">

                  <div class="form-check mb-0">
                    <input
                      class="form-check-input border border-secondary"
                      type="checkbox"
                      id="selectAll"
                      onchange="toggleSelectAll(this)">
                  </div>

                </th>

                <th> EXTINGUISHER CODE </th>

                <th> TYPE </th>

                <th> LOCATION </th>

                <th> BRANCH </th>

                <th width="80"></th>

              </tr>

            </thead>

            <tbody>

              <?php if ($result->num_rows > 0): ?>

                <?php while ($row = $result->fetch_assoc()): ?>

                  <tr>

                    <!-- Checkbox -->
                    <td>

                      <input
                        class="form-check-input border border-secondary extinguisher-checkbox"
                        type="checkbox"
                        value="<?= htmlspecialchars($row['extinguisher_code']) ?>"
                        onchange="updateSelection()">

                    </td>

                    <!-- Extinguisher Code -->
                    <td>

                      <div class="d-flex align-items-center gap-2">

                        <div class="bg-danger-subtle text-danger rounded-3 p-2">
                          <i class="bi bi-fire"></i>
                        </div>

                        <strong>
                          <?= htmlspecialchars($row['extinguisher_code']) ?>
                        </strong>

                      </div>

                    </td>


                    <!-- Type -->
                    <td>

                      <span class="badge rounded-pill bg-primary-subtle text-primary">

                        <?= htmlspecialchars($row['type']) ?>

                      </span>

                    </td>

                    <!-- Location -->
                    <td>

                      <i class="bi bi-geo-alt-fill text-secondary me-1"></i>

                      <?= htmlspecialchars($row['location']) ?>

                    </td>

                    <!-- Location -->
                    <td>

                      <i class="bi bi-geo-alt-fill text-secondary me-1"></i>

                      <?= htmlspecialchars($row['branch']) ?>

                    </td>

                    <!-- Actions -->
                    <td class="text-end">

                    </td>

                  </tr>

                <?php endwhile; ?>

              <?php else: ?>

                <tr>

                  <td
                    colspan="5"
                    class="text-center py-5 text-muted">

                    No fire extinguishers found.

                  </td>

                </tr>

              <?php endif; ?>

            </tbody>

          </table>

        </div>


        <!-- Selection Footer -->
        <div id="selectionFooter" class="card-footer border-0 bg-primary-subtle rounded selection-footer">

          <div class="d-flex flex-column flex-md-row
                justify-content-between
                align-items-center
                gap-3">

            <div class="text-primary fw-semibold text-center text-md-start">
              <i class="bi bi-check-circle-fill me-1"></i>
              <span id="selectedCount">0</span>
              item(s) selected
            </div>

            <div class="d-flex flex-wrap gap-2 justify-content-center flex-shrink-0">

              <button
                type="button"
                class="btn btn-light"
                onclick="clearSelection()">
                <i class="bi bi-x-lg me-1"></i>
                Clear
              </button>

              <button
                type="button"
                class="btn btn-primary"
                onclick="printSelected()">
                <i class="bi bi-printer me-1"></i>
                Print Selected
              </button>

            </div>

          </div>

        </div>

      </div>

    </div>


    <script>
      function toggleSelectAll(source) {

        document
          .querySelectorAll('.extinguisher-checkbox')
          .forEach(cb => {
            cb.checked = source.checked;
          });

        updateSelection();
      }


      function updateSelection() {

        const selected =
          document.querySelectorAll(
            '.extinguisher-checkbox:checked'
          ).length;

        const selectedCount =
          document.getElementById('selectedCount');

        const selectionFooter =
          document.getElementById('selectionFooter');

        selectedCount.textContent = selected;

        if (selected > 0) {

          selectionFooter.classList.add('show');

        } else {

          selectionFooter.classList.remove('show');

        }
      }

      function clearSelection() {

        document
          .querySelectorAll('.extinguisher-checkbox')
          .forEach(cb => {
            cb.checked = false;
          });

        document.getElementById('selectAll').checked = false;

        updateSelection();
      }


      function printSelected() {

        const selected = [...document.querySelectorAll(
          '.extinguisher-checkbox:checked'
        )].map(cb => cb.value);

        if (!selected.length) {
          alert('Please select at least one fire extinguisher.');
          return;
        }

        window.location.href =
          'helpers/print-qr.php?codes=' +
          encodeURIComponent(selected.join(','));
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
  <?php include_once 'notification/session_timeout.php'; ?>

</body>

</html>