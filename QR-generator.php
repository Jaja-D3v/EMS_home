<?php

include 'backend/controller/QRCodeGeneratorController.php';
include 'backend/controller/DropdownController.php';
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
      <main class="container-fluid px-3 px-md-4 py-3 qr-page">

        <!-- PAGE HEADER -->
        <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden qr-header">
          <div class="card-body p-3 p-md-4 bg-light">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
              <div class="d-flex align-items-center gap-3">
                <div class="bg-primary-subtle text-primary rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width:56px;height:56px;">
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
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                  <i class="bi bi-fire me-1"></i>
                  Fire Extinguisher
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- MAIN CARD -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden qr-main-card">

          <!-- TOOLBAR -->
          <div class="card-body border-bottom bg-white p-3 qr-toolbar">
            <div class="row align-items-center g-2">

              <!-- SEARCH -->
              <div class="col-12 col-lg">
                <div class="input-group">
                  <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-body-secondary"></i>
                  </span>
                  <input type="text" class="form-control border-start-0 ps-0" placeholder="Search code or location..." id="qrSearch">
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
                  <select class="form-select" id="branchFilter" style="min-width:190px;">
                    <option value="all" <?= $selectedBranch === 'all' ? 'selected' : '' ?>>
                      All Branches
                    </option>

                    <?php if (!empty($branches)): ?>
                      <?php foreach ($branches as $branch): ?>
                        <option value="<?= htmlspecialchars($branch['value']) ?>" <?= $selectedBranch === $branch['value'] ? 'selected' : '' ?>>

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
                    url.searchParams.set('branch', selectedBranch);
                    window.location.href = url.toString();

                  });

                });
              </script>
            </div>
          </div>

          <!-- TABLE -->
          <div class="table-responsive qr-table-wrapper" style="min-height: 520px;">
            <table class="table table-hover align-middle mb-0 qr-table">
              <thead class="table-light">
                <tr>
                  <th width="55" class="ps-3">
                    <div class="form-check mb-0">
                      <input class="form-check-input" type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
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
              <tbody class="shadow-sm">
                <?php if ($result->num_rows > 0): ?>
                  <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>

                      <!-- CHECKBOX -->
                      <td class="ps-3">
                        <input class="form-check-input extinguisher-checkbox" type="checkbox" value="<?= htmlspecialchars($row['extinguisher_code']) ?>" onchange="updateSelection()">
                      </td>

                      <!-- CODE -->
                      <td>
                        <div class="d-flex align-items-center gap-2">
                          <div class="bg-danger-subtle text-danger rounded-3 d-flex align-items-center justify-content-center" style="width:38px;height:38px;">
                            <i class="bi bi-fire"></i>
                          </div>
                          <span class="fw-semibold">
                            <?= htmlspecialchars($row['extinguisher_code']) ?>
                          </span>
                        </div>
                      </td>

                      <!-- TYPE -->
                      <td>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                          <?= htmlspecialchars($row['type']) ?>
                        </span>
                      </td>

                      <!-- LOCATION -->
                      <td>
                        <span class="text-body-secondary">
                          <i class="bi bi-geo-alt-fill text-primary me-1"></i>
                          <?= htmlspecialchars($row['location']) ?>
                        </span>
                      </td>

                      <!-- BRANCH -->
                      <td>
                        <span class="text-body-secondary">
                          <i class="bi bi-building text-primary me-1"></i>
                          <?= htmlspecialchars($row['branch']) ?>
                        </span>
                      </td>

                      <!-- ACTION -->
                      <td></td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>

                  <tr>
                    <td colspan="6" class="text-center py-5">
                      <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:60px;height:60px;">
                        <i class="bi bi-fire text-body-secondary fs-4"></i>
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

          <div id="selectionFooter" class="bg-primary-subtle border-top p-3 d-none">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
              <div class="text-primary fw-semibold">
                <i class="bi bi-check-circle-fill me-1"></i>
                <span id="selectedCount">0</span>
                item(s) selected
              </div>
              <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-light border rounded-3 px-3" onclick="clearSelection()">
                  <i class="bi bi-x-lg me-1"></i>
                  Clear
                </button>
                <button type="button" class="btn btn-primary rounded-3 px-3" onclick="printSelected()">
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
      // SEARCH + PAGINATION
      // ==============================

      const qrSearch =
        document.getElementById('qrSearch');

      const tbody =
        document.querySelector('table tbody');

      const rowsPerPage = 10;

      let currentPage = 1;


      // ==============================
      // GET DATA ROWS
      // ==============================

      function getDataRows() {

        return Array.from(
          tbody.querySelectorAll(
            'tr:not(#qrNoResults)'
          )
        ).filter(row => {

          return row.querySelector(
            '.extinguisher-checkbox'
          );

        });
      }


      // ==============================
      // GET FILTERED ROWS
      // ==============================

      function getFilteredRows() {

        const searchValue =
          qrSearch ?
          qrSearch.value
          .trim()
          .toLowerCase() :
          '';

        const rows =
          getDataRows();

        return rows.filter(row => {

          const checkbox =
            row.querySelector(
              '.extinguisher-checkbox'
            );

          if (!checkbox) {
            return false;
          }

          const code =
            checkbox.value
            .toLowerCase();

          const location =
            row.cells[3] ?
            row.cells[3]
            .textContent
            .trim()
            .toLowerCase() :
            '';

          return (
            searchValue === '' ||
            code.includes(searchValue) ||
            location.includes(searchValue)
          );
        });
      }


      // ==============================
      // DISPLAY ROWS
      // ==============================

      function displayRows() {

        const rows =
          getDataRows();

        const filteredRows =
          getFilteredRows();

        const totalPages =
          Math.ceil(
            filteredRows.length /
            rowsPerPage
          );


        // Make sure current page
        // is still valid

        if (
          totalPages > 0 &&
          currentPage > totalPages
        ) {
          currentPage = totalPages;
        }

        if (filteredRows.length === 0) {
          currentPage = 1;
        }


        // Hide all rows first

        rows.forEach(row => {

          row.classList.add(
            'd-none'
          );

        });


        // ==============================
        // SHOW CURRENT PAGE
        // ==============================

        const start =
          (currentPage - 1) *
          rowsPerPage;

        const end =
          start + rowsPerPage;

        filteredRows
          .slice(start, end)
          .forEach(row => {

            row.classList.remove(
              'd-none'
            );

          });


        // ==============================
        // NO RESULTS
        // ==============================

        let noResultsRow =
          document.getElementById(
            'qrNoResults'
          );


        if (
          filteredRows.length === 0 &&
          qrSearch &&
          qrSearch.value.trim() !== ''
        ) {

          if (!noResultsRow) {

            noResultsRow =
              document.createElement(
                'tr'
              );

            noResultsRow.id =
              'qrNoResults';

            noResultsRow.innerHTML = `
                    <td colspan="6"
                        class="text-center py-5">

                        <div
                            class="bg-light rounded-circle
                                   d-flex align-items-center
                                   justify-content-center
                                   mx-auto mb-3"
                            style="width:60px;height:60px;">

                            <i
                                class="bi bi-search
                                       text-body-secondary fs-4">
                            </i>

                        </div>

                        <div
                            class="fw-semibold text-dark">

                            No results found

                        </div>

                        <div
                            class="small text-body-secondary mt-1">

                            No fire extinguisher
                            matches your search.

                        </div>

                    </td>
                `;

            tbody.appendChild(
              noResultsRow
            );
          }

        } else {

          if (noResultsRow) {

            noResultsRow.remove();

          }
        }


        // ==============================
        // PAGINATION
        // ==============================

        renderPagination(
          totalPages
        );


        // ==============================
        // UPDATE SELECTION
        // ==============================

        updateSelection();
      }


      // PAGINATION

      // ==============================
      // PAGINATION + ITEM COUNT
      // ==============================

      function renderPagination(totalPages) {

        let paginationContainer =
          document.getElementById('qrPagination');

        // Create pagination container
        if (!paginationContainer) {

          paginationContainer =
            document.createElement('div');

          paginationContainer.id =
            'qrPagination';

          paginationContainer.className =
            'card-footer bg-white border-top';

          const card =
            document.querySelector('.qr-main-card');

          if (card) {
            card.appendChild(
              paginationContainer
            );
          }
        }

        paginationContainer.innerHTML = '';


        // ==============================
        // GET FILTERED DATA
        // ==============================

        const filteredRows =
          getFilteredRows();

        const totalItems =
          filteredRows.length;


        // ==============================
        // ITEM RANGE
        // ==============================

        let startItem = 0;
        let endItem = 0;

        if (totalItems > 0) {

          startItem =
            ((currentPage - 1) *
              rowsPerPage) + 1;

          endItem =
            Math.min(
              currentPage * rowsPerPage,
              totalItems
            );
        }


        // ==============================
        // FOOTER WRAPPER
        // ==============================

        const footerWrapper =
          document.createElement('div');

        footerWrapper.className =
          'd-flex flex-column flex-sm-row ' +
          'justify-content-between ' +
          'align-items-center gap-2';


        // ==============================
        // ITEM COUNT
        // ==============================

        const itemCount =
          document.createElement('small');

        itemCount.className =
          'text-body-secondary';


        if (totalItems > 0) {

          itemCount.textContent =
            `Showing ${startItem}–${endItem} ` +
            `of ${totalItems} items`;

        } else {

          itemCount.textContent =
            'Showing 0 items';
        }


        // ==============================
        // PAGINATION
        // ==============================

        const paginationWrapper =
          document.createElement('div');


        if (totalPages > 1) {

          const nav =
            document.createElement('nav');


          const ul =
            document.createElement('ul');

          ul.className =
            'pagination pagination-sm mb-0';


          // ==============================
          // PREVIOUS
          // ==============================

          const prevLi =
            document.createElement('li');

          prevLi.className =
            'page-item' +
            (
              currentPage === 1 ?
              ' disabled' :
              ''
            );


          const prevButton =
            document.createElement('button');

          prevButton.type =
            'button';

          prevButton.className =
            'page-link';

          prevButton.innerHTML =
            '<i class="bi bi-chevron-left"></i>';


          prevButton.addEventListener(
            'click',
            function() {

              if (currentPage > 1) {

                currentPage--;

                displayRows();
              }
            }
          );


          prevLi.appendChild(
            prevButton
          );

          ul.appendChild(
            prevLi
          );


          // ==============================
          // PAGE NUMBERS
          // ==============================

          let pages = [];


          if (totalPages <= 5) {

            for (
              let page = 1; page <= totalPages; page++
            ) {

              pages.push(page);
            }

          } else {

            let startPage;

            if (currentPage <= 3) {

              startPage = 1;

            } else if (
              currentPage >=
              totalPages - 2
            ) {

              startPage =
                totalPages - 4;

            } else {

              startPage =
                currentPage - 2;
            }


            for (
              let page = startPage; page < startPage + 5; page++
            ) {

              pages.push(page);
            }
          }


          // ==============================
          // FIRST DOT
          // ==============================

          if (
            totalPages > 5 &&
            pages[0] > 1
          ) {

            const dotsLi =
              document.createElement('li');

            dotsLi.className =
              'page-item disabled';

            dotsLi.innerHTML = `
                <span class="page-link border-0">
                    ...
                </span>
            `;

            ul.appendChild(
              dotsLi
            );
          }


          // ==============================
          // PAGE BUTTONS
          // ==============================

          pages.forEach(page => {

            const li =
              document.createElement('li');

            li.className =
              'page-item' +
              (
                page === currentPage ?
                ' active' :
                ''
              );


            const button =
              document.createElement('button');

            button.type =
              'button';

            button.className =
              'page-link';

            button.textContent =
              page;


            button.addEventListener(
              'click',
              function() {

                currentPage =
                  page;

                displayRows();
              }
            );


            li.appendChild(
              button
            );

            ul.appendChild(
              li
            );
          });


          // ==============================
          // LAST DOT
          // ==============================

          if (
            totalPages > 5 &&
            pages[pages.length - 1] <
            totalPages
          ) {

            const dotsLi =
              document.createElement('li');

            dotsLi.className =
              'page-item disabled';

            dotsLi.innerHTML = `
                <span class="page-link border-0">
                    ...
                </span>
            `;

            ul.appendChild(
              dotsLi
            );
          }


          // ==============================
          // NEXT
          // ==============================

          const nextLi =
            document.createElement('li');

          nextLi.className =
            'page-item' +
            (
              currentPage === totalPages ?
              ' disabled' :
              ''
            );


          const nextButton =
            document.createElement('button');

          nextButton.type =
            'button';

          nextButton.className =
            'page-link';

          nextButton.innerHTML =
            '<i class="bi bi-chevron-right"></i>';


          nextButton.addEventListener(
            'click',
            function() {

              if (
                currentPage <
                totalPages
              ) {

                currentPage++;

                displayRows();
              }
            }
          );


          nextLi.appendChild(
            nextButton
          );

          ul.appendChild(
            nextLi
          );


          nav.appendChild(
            ul
          );

          paginationWrapper.appendChild(
            nav
          );
        }


        // ==============================
        // APPEND FOOTER
        // ==============================

        footerWrapper.appendChild(
          itemCount
        );

        footerWrapper.appendChild(
          paginationWrapper
        );

        paginationContainer.appendChild(
          footerWrapper
        );
      }



      // ==============================
      // SEARCH
      // ==============================

      if (qrSearch) {

        qrSearch.addEventListener(
          'input',
          function() {

            // Always return
            // to page 1 when searching

            currentPage = 1;


            // Uncheck hidden rows

            getDataRows()
              .forEach(row => {

                if (
                  row.classList.contains(
                    'd-none'
                  )
                ) {

                  const checkbox =
                    row.querySelector(
                      '.extinguisher-checkbox'
                    );

                  if (checkbox) {

                    checkbox.checked =
                      false;
                  }
                }
              });


            displayRows();
          }
        );
      }


      // ==============================
      // SELECT ALL
      // ==============================

      function toggleSelectAll(
        source
      ) {

        const filteredRows =
          getFilteredRows();


        const start =
          (currentPage - 1) *
          rowsPerPage;


        const currentPageRows =
          filteredRows.slice(
            start,
            start + rowsPerPage
          );


        currentPageRows.forEach(
          row => {

            const checkbox =
              row.querySelector(
                '.extinguisher-checkbox'
              );

            if (checkbox) {

              checkbox.checked =
                source.checked;
            }
          }
        );


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
          Array.from(
            checkboxes
          ).filter(cb => {

            const row =
              cb.closest('tr');

            return (
              row &&
              !row.classList.contains(
                'd-none'
              )
            );
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


        if (selectedCount) {

          selectedCount.textContent =
            selected;
        }


        if (selectionFooter) {

          if (selected > 0) {

            selectionFooter.classList.remove(
              'd-none'
            );

          } else {

            selectionFooter.classList.add(
              'd-none'
            );
          }
        }


        if (selectAll) {

          if (
            visibleCheckboxes.length > 0 &&
            visibleSelected ===
            visibleCheckboxes.length
          ) {

            selectAll.checked =
              true;

            selectAll.indeterminate =
              false;

          } else if (
            visibleSelected > 0
          ) {

            selectAll.checked =
              false;

            selectAll.indeterminate =
              true;

          } else {

            selectAll.checked =
              false;

            selectAll.indeterminate =
              false;
          }
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

            cb.checked =
              false;

          });


        const selectAll =
          document.getElementById(
            'selectAll'
          );


        if (selectAll) {

          selectAll.checked =
            false;

          selectAll.indeterminate =
            false;
        }


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

        ].map(
          cb => cb.value
        );


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


      // ==============================
      // INITIAL LOAD
      // ==============================

      document.addEventListener(
        'DOMContentLoaded',
        function() {

          displayRows();

        }
      );
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