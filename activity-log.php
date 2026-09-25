<?php $pageTitle = '<div class="d-flex align-items-center gap-2 mb-1">
                <i class="bi bi-activity text-primary"></i>

                <h5 class="fw-semibold mb-0">
                  Activity Log
                </h5>
              </div>'; ?>

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

          <div class="d-flex align-items-center gap-2 mt-2">

            <div class="d-flex align-items-center gap-2 
                bg-body border rounded-pill 
                shadow-sm px-3 py-1">

              <i class="bi bi-calendar3 text-primary"></i>

              <select
                id="activityMonth"
                class="form-select form-select-sm border-0 shadow-none bg-transparent fw-semibold px-1"
                style="width: 130px; cursor: pointer;">

                <option value="">All Months</option>

                <option value="0">January</option>
                <option value="1">February</option>
                <option value="2">March</option>
                <option value="3">April</option>
                <option value="4">May</option>
                <option value="5">June</option>
                <option value="6">July</option>
                <option value="7">August</option>
                <option value="8">September</option>
                <option value="9">October</option>
                <option value="10">November</option>
                <option value="11">December</option>

              </select>

            </div>

          </div>
        </div>

        <div id="activityLogs">
          <div class="text-center text-body-secondary py-4">
            Loading activities...
          </div>
        </div>

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


    // for fetching all the activity logs data 


    document.addEventListener('DOMContentLoaded', function() {

      const activityLogs = document.getElementById('activityLogs');
      const activityMonth = document.getElementById('activityMonth');

      if (!activityLogs || !activityMonth) return;

      let allActivityLogs = [];


      // ==========================================
      // Activity Style
      // ==========================================

      function getActivityStyle(action) {

        switch (action.toLowerCase()) {

          case 'add fire extinguisher':
            return {
              icon: 'bi-plus-lg',
                color: 'success'
            };

          case 'update fire extinguisher':
            return {
              icon: 'bi-pencil',
                color: 'warning'
            };

          case 'delete fire extinguisher':
            return {
              icon: 'bi-trash',
                color: 'danger'
            };

          case 'inspect fire extinguisher':
            return {
              icon: 'bi-clipboard-check',
                color: 'info'
            };

          default:
            return {
              icon: 'bi-activity',
                color: 'secondary'
            };
        }
      }


      // ==========================================
      // Activity Title
      // ==========================================

      function getActivityTitle(action) {

        switch (action.toLowerCase()) {

          case 'add fire extinguisher':
            return 'Fire Extinguisher Added';

          case 'update fire extinguisher':
            return 'Fire Extinguisher Updated';

          case 'delete fire extinguisher':
            return 'Fire Extinguisher Deleted';

          case 'inspect fire extinguisher':
            return 'Inspection Completed';

          default:
            return action;
        }
      }


      // ==========================================
      // Format Time Ago
      // ==========================================

      function formatTimeAgo(dateString) {

        const date = new Date(
          dateString.replace(' ', 'T')
        );

        const now = new Date();

        const seconds = Math.floor(
          (now - date) / 1000
        );


        // Less than 1 minute
        if (seconds < 60) {
          return 'Just now';
        }


        // Minutes
        const minutes = Math.floor(seconds / 60);

        if (minutes < 60) {
          return `${minutes} min${minutes !== 1 ? 's' : ''} ago`;
        }


        // Hours
        const hours = Math.floor(minutes / 60);

        if (hours < 24) {
          return `${hours} hour${hours !== 1 ? 's' : ''} ago`;
        }


        // Days
        const days = Math.floor(hours / 24);

        if (days < 7) {
          return `${days} day${days !== 1 ? 's' : ''} ago`;
        }


        // Weeks
        const weeks = Math.floor(days / 7);

        if (weeks < 4) {
          return `${weeks} week${weeks !== 1 ? 's' : ''} ago`;
        }


        // Months
        const months = Math.floor(days / 30);

        if (months < 12) {
          return `${months} month${months !== 1 ? 's' : ''} ago`;
        }


        // Years
        const years = Math.floor(days / 365);

        return `${years} year${years !== 1 ? 's' : ''} ago`;
      }


      // ==========================================
      // Render Activity Logs
      // ==========================================

      function renderActivityLogs(logs) {

        if (!logs.length) {

          activityLogs.innerHTML = `
                <div class="text-center text-body-secondary py-5">

                    <i class="bi bi-clock-history fs-3 d-block mb-2"></i>

                    No activity logs found.

                </div>
            `;

          return;
        }


        activityLogs.innerHTML = logs.map(log => {

          const style = getActivityStyle(log.action);

          const title = getActivityTitle(log.action);


          return `
                <div class="bg-white rounded-4 px-3 py-3 mb-2 border border-secondary-subtle shadow-sm">

                    <div class="d-flex align-items-center gap-3">

                        <!-- Icon -->

                        <div class="bg-${style.color} bg-opacity-10 text-${style.color} rounded-circle p-3">

                            <i class="bi ${style.icon}"></i>

                        </div>


                        <!-- Activity Information -->

                        <div class="flex-grow-1">

                            <div class="d-flex align-items-center gap-2">

                                <span class="fw-semibold">

                                    ${title}

                                </span>


                                <span class="badge rounded-pill bg-${style.color}-subtle text-${style.color}">

                                    ${log.action}

                                </span>

                            </div>


                            <div class="small text-body-secondary mt-1">

                                ${log.description}

                            </div>


                            <div class="small text-body-secondary mt-2">

                                <i class="bi bi-person me-1"></i>

                                ${log.user_name}

                            </div>

                        </div>


                        <!-- Time -->

                        <div class="text-end">

                            <small class="text-body-secondary text-nowrap">

                                ${formatTimeAgo(log.created_at)}

                            </small>

                        </div>

                    </div>

                </div>
            `;

        }).join('');
      }


      // ==========================================
      // Filter By Month
      // ==========================================

      activityMonth.addEventListener('change', function() {

        const selectedMonth = this.value;


        // All months
        if (selectedMonth === '') {

          renderActivityLogs(allActivityLogs);

          return;
        }


        // Selected month
        const filteredLogs = allActivityLogs.filter(log => {

          const date = new Date(
            log.created_at.replace(' ', 'T')
          );

          return date.getMonth() === Number(selectedMonth);
        });


        renderActivityLogs(filteredLogs);
      });


      // ==========================================
      // Fetch Activity Logs
      // ==========================================

      function fetchActivityLogs() {

        fetch(
            'backend/controller/ActivityLogController.php?action=getAll'
          )

          .then(response => {

            if (!response.ok) {

              throw new Error(
                'Failed to fetch activity logs.'
              );

            }

            return response.json();

          })

          .then(result => {

            if (!result.success) {

              throw new Error(
                result.message ||
                'Failed to load activity logs.'
              );

            }


            // Store all logs
            allActivityLogs = result.data;


            // Sort newest activity first
            allActivityLogs.sort((a, b) => {

              return new Date(
                b.created_at.replace(' ', 'T')
              ) - new Date(
                a.created_at.replace(' ', 'T')
              );

            });


            // Display all logs
            renderActivityLogs(allActivityLogs);

          })

          .catch(error => {

            console.error(error);


            activityLogs.innerHTML = `
                <div class="text-center text-danger py-4">

                    <i class="bi bi-exclamation-circle me-1"></i>

                    Failed to load activity logs.

                </div>
            `;

          });
      }


      // ==========================================
      // Initial Fetch
      // ==========================================

      fetchActivityLogs();

    });
  </script>
</body>

</html>