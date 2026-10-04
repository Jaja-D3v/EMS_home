<?php
include 'backend/controller/QRCodeGeneratorController.php';
include 'backend/controller/DropdownBranchController.php';
require_once 'backend/authentication/SessionChecker.php';

$result = getAllFireExtinguishersCode();


?>


<!DOCTYPE html>
<html lang="en">
<?php include 'partials/header.php'; ?>

<body>
  <?php include 'partials/side-nav.php'; ?>

  <div class="wrapper d-flex flex-column min-vh-100">
    <?php include 'partials/header-nav.php'; ?>

    <div class="main-content flex-grow-1">
      <main class="container-fluid px-3 px-md-4 py-3">

        <!-- PAGE HEADER -->
        <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden">
          <div class="card-body p-3 p-md-4 bg-light">

            <div class="d-flex flex-column flex-md-row
                        align-items-md-center
                        justify-content-between
                        gap-3">

              <div class="d-flex align-items-center gap-3">

                <div class="bg-primary-subtle text-primary
                            rounded-4 d-flex align-items-center
                            justify-content-center flex-shrink-0"
                     style="width:56px;height:56px;">
                  <i class="bi bi-qr-code-scan fs-3"></i>
                </div>

                <div>
                  <h4 class="fw-bold mb-1 text-dark">
                    Print QR Codes
                  </h4>

                  <p class="text-body-secondary mb-0 small">
                    Select fire extinguishers to print their QR codes.
                  </p>
                </div>

              </div>

              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary
                             rounded-pill px-3 py-2">
                  <i class="bi bi-fire me-1"></i>
                  Fire Extinguisher
                </span>
              </div>

            </div>

          </div>
        </div>


        <!-- MAIN CARD -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

          <!-- TOOLBAR -->
          <div class="card-body border-bottom bg-white p-3">

            <div class="row align-items-center g-2">

              <!-- SEARCH -->
              <div class="col-12 col-lg">

                <div class="input-group">

                  <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-body-secondary"></i>
                  </span>

                  <input
                    type="text"
                    class="form-control border-start-0 ps-0"
                    placeholder="Search code or location..."
                    id="qrSearch">

                </div>

              </div>


              <!-- BRANCH FILTER - ADMIN ONLY -->
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

                  <select
                    class="form-select"
                    id="branchFilter"
                    style="min-width:190px;">

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

                  const branchFilter =
                    document.getElementById('branchFilter');

                  if (!branchFilter) {
                    return;
                  }

                  branchFilter.addEventListener('change', function() {

                    const selectedBranch = this.value;

                    const url =
                      new URL(window.location.href);

                    url.searchParams.set(
                      'branch',
                      selectedBranch
                    );

                    window.location.href =
                      url.toString();

                  });

                });
              </script>

            </div>

          </div>


          <!-- TABLE -->
          <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

              <thead class="table-light">

                <tr>

                  <th width="55" class="ps-3">

                    <div class="form-check mb-0">

                      <input
                        class="form-check-input"
                        type="checkbox"
                        id="selectAll"
                        onchange="toggleSelectAll(this)">

                    </div>

                  </th>

                  <th class="text-uppercase small text-body-secondary">
                    Extinguisher Code
                  </th>

                  <th class="text-uppercase small text-body-secondary">
                    Type
                  </th>

                  <th class="text-uppercase small text-body-secondary">
                    Location
                  </th>

                  <th class="text-uppercase small text-body-secondary">
                    Branch
                  </th>

                  <th width="50"></th>

                </tr>

              </thead>


              <tbody>

                <?php if ($result->num_rows > 0): ?>

                  <?php while ($row = $result->fetch_assoc()): ?>

                    <tr>

                      <!-- CHECKBOX -->
                      <td class="ps-3">

                        <input
                          class="form-check-input extinguisher-checkbox"
                          type="checkbox"
                          value="<?= htmlspecialchars($row['extinguisher_code']) ?>"
                          onchange="updateSelection()">

                      </td>


                      <!-- CODE -->
                      <td>

                        <div class="d-flex align-items-center gap-2">

                          <div class="bg-danger-subtle text-danger
                                      rounded-3 d-flex
                                      align-items-center
                                      justify-content-center"
                               style="width:38px;height:38px;">

                            <i class="bi bi-fire"></i>

                          </div>

                          <span class="fw-semibold">
                            <?= htmlspecialchars($row['extinguisher_code']) ?>
                          </span>

                        </div>

                      </td>


                      <!-- TYPE -->
                      <td>

                        <span class="badge
                                     bg-primary-subtle
                                     text-primary
                                     rounded-pill px-3 py-2">

                          <?= htmlspecialchars($row['type']) ?>

                        </span>

                      </td>


                      <!-- LOCATION -->
                      <td>

                        <span class="text-body-secondary">

                          <i class="bi bi-geo-alt-fill
                                    text-primary me-1"></i>

                          <?= htmlspecialchars($row['location']) ?>

                        </span>

                      </td>


                      <!-- BRANCH -->
                      <td>

                        <span class="text-body-secondary">

                          <i class="bi bi-building
                                    text-primary me-1"></i>

                          <?= htmlspecialchars($row['branch']) ?>

                        </span>

                      </td>


                      <!-- ACTION -->
                      <td></td>

                    </tr>

                  <?php endwhile; ?>

                <?php else: ?>

                  <tr>

                    <td
                      colspan="6"
                      class="text-center py-5">

                      <div class="bg-light rounded-circle
                                  d-flex align-items-center
                                  justify-content-center mx-auto mb-3"
                           style="width:60px;height:60px;">

                        <i class="bi bi-fire
                                  text-body-secondary fs-4"></i>

                      </div>

                      <div class="fw-semibold text-dark">
                        No fire extinguishers found
                      </div>

                      <div class="small text-body-secondary mt-1">
                        There are no fire extinguishers available.
                      </div>

                    </td>

                  </tr>

                <?php endif; ?>

              </tbody>

            </table>

          </div>


          <!-- SELECTION FOOTER -->
          <div
            id="selectionFooter"
            class="bg-primary-subtle border-top p-3 d-none">

            <div class="d-flex flex-column
                        flex-md-row
                        justify-content-between
                        align-items-center
                        gap-3">

              <div class="text-primary fw-semibold">

                <i class="bi bi-check-circle-fill me-1"></i>

                <span id="selectedCount">0</span>
                item(s) selected

              </div>


              <div class="d-flex flex-wrap gap-2">

                <button
                  type="button"
                  class="btn btn-light border rounded-3 px-3"
                  onclick="clearSelection()">

                  <i class="bi bi-x-lg me-1"></i>
                  Clear

                </button>


                <button
                  type="button"
                  class="btn btn-primary rounded-3 px-3"
                  onclick="printSelected()">

                  <i class="bi bi-printer me-1"></i>
                  Print Selected

                </button>

              </div>

            </div>

          </div>

        </div>

      </main>
    </div>


    <!-- SEARCH / SELECTION JS -->
    <script>

      // ==============================
    // SEARCH
    // ==============================
    const qrSearch = document.getElementById('qrSearch');

    if (qrSearch) {

      qrSearch.addEventListener('input', function () {

        const searchValue =
          this.value.trim().toLowerCase();

        const tbody =
          document.querySelector('table tbody');

        const rows =
          tbody.querySelectorAll(
            'tr:not(#qrNoResults)'
          );

        let hasResults = false;

        rows.forEach(row => {

          const checkbox =
            row.querySelector(
              '.extinguisher-checkbox'
            );

          if (!checkbox) {
            return;
          }

          const code =
            checkbox.value.toLowerCase();

          const location =
            row.cells[3]
              ? row.cells[3].textContent
                  .trim()
                  .toLowerCase()
              : '';

          const match =
            searchValue === '' ||
            code.includes(searchValue) ||
            location.includes(searchValue);

          if (match) {

            row.classList.remove('d-none');

            hasResults = true;

          } else {

            row.classList.add('d-none');

            // Uncheck hidden items
            checkbox.checked = false;

          }

        });


        // Reset Select All state
        const selectAll =
          document.getElementById('selectAll');

        if (selectAll) {

          selectAll.checked = false;
          selectAll.indeterminate = false;

        }


        // ==============================
        // NO RESULTS
        // ==============================
        let noResultsRow =
          document.getElementById('qrNoResults');

        if (!hasResults && searchValue !== '') {

          if (!noResultsRow) {

            noResultsRow =
              document.createElement('tr');

            noResultsRow.id =
              'qrNoResults';

            noResultsRow.innerHTML = `
              <td colspan="6"
                  class="text-center py-5">

                <div class="bg-light rounded-circle
                            d-flex align-items-center
                            justify-content-center
                            mx-auto mb-3"
                     style="width:60px;height:60px;">

                  <i class="bi bi-search
                            text-body-secondary fs-4"></i>

                </div>

                <div class="fw-semibold text-dark">
                  No results found
                </div>

                <div class="small text-body-secondary mt-1">
                  No fire extinguisher matches your search.
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


        updateSelection();

      });

    }


    // ==============================
    // SELECT ALL
    // ==============================
    function toggleSelectAll(source) {

      const rows =
        document.querySelectorAll(
          'table tbody tr:not(#qrNoResults)'
        );

      rows.forEach(row => {

        if (
          row.classList.contains('d-none')
        ) {
          return;
        }

        const checkbox =
          row.querySelector(
            '.extinguisher-checkbox'
          );

        if (checkbox) {

          checkbox.checked =
            source.checked;

        }

      });

      updateSelection();

    }


    // ==============================
    // UPDATE SELECTION
    // ==============================
    function updateSelection() {

      const checkboxes =
        document.querySelectorAll(
          '.extinguisher-checkbox'
        );

      const selected =
        document.querySelectorAll(
          '.extinguisher-checkbox:checked'
        ).length;

      const visibleCheckboxes =
        Array.from(checkboxes)
          .filter(cb => {

            const row =
              cb.closest('tr');

            return row &&
              !row.classList.contains('d-none');

          });

      const visibleSelected =
        visibleCheckboxes.filter(
          cb => cb.checked
        ).length;


      const selectedCount =
        document.getElementById(
          'selectedCount'
        );

      const selectionFooter =
        document.getElementById(
          'selectionFooter'
        );

      const selectAll =
        document.getElementById(
          'selectAll'
        );


      selectedCount.textContent =
        selected;


      if (selected > 0) {

        selectionFooter.classList.remove(
          'd-none'
        );

      } else {

        selectionFooter.classList.add(
          'd-none'
        );

      }


      if (
        visibleCheckboxes.length > 0 &&
        visibleSelected ===
          visibleCheckboxes.length
      ) {

        selectAll.checked = true;
        selectAll.indeterminate = false;

      } else if (
        visibleSelected > 0
      ) {

        selectAll.checked = false;
        selectAll.indeterminate = true;

      } else {

        selectAll.checked = false;
        selectAll.indeterminate = false;

      }

    }


    // ==============================
    // CLEAR SELECTION
    // ==============================
    function clearSelection() {

      document
        .querySelectorAll(
          '.extinguisher-checkbox'
        )
        .forEach(cb => {

          cb.checked = false;

        });


      const selectAll =
        document.getElementById(
          'selectAll'
        );

      selectAll.checked = false;
      selectAll.indeterminate = false;


      updateSelection();

    }


    // ==============================
    // PRINT SELECTED
    // ==============================
    function printSelected() {

      const selected = [
        ...document.querySelectorAll(
          '.extinguisher-checkbox:checked'
        )
      ].map(cb => cb.value);


      if (!selected.length) {

        alert(
          'Please select at least one fire extinguisher.'
        );

        return;

      }


      window.location.href =
        'helpers/print-qr.php?codes=' +
        encodeURIComponent(
          selected.join(',')
        );

    }

    </script>


    <?php include 'partials/footer.php'; ?>

  </div>


  <!-- CoreUI -->
  <script src="vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>

  <script src="vendors/simplebar/js/simplebar.min.js"></script>


  <script>

    const header =
      document.querySelector(
        "header.header"
      );

    document.addEventListener(
      "scroll",
      () => {

        if (header) {

          header.classList.toggle(
            "shadow-sm",
            document.documentElement.scrollTop > 0
          );

        }

      }
    );

  </script>


  <?php include_once 'notification/session_timeout.php'; ?>

</body>
</html>
