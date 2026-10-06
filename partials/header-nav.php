<header class="header header-sticky p-0 mb-2">
  <div class="container-fluid border-bottom px-4">

    <button
      class="header-toggler"
      type="button"
      onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"
      style="margin-inline-start: -14px">

      <svg
        class="icon icon-lg"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 512 512">

        <path
          fill="var(--ci-primary-color, currentcolor)"
          d="M80 96h352v32H80zm0 144h352v32H80zm0 144h352v32H80z"
          class="ci-primary" />

      </svg>

    </button>


    <ul class="header-nav ms-auto">
    </ul>


    <ul class="header-nav">

      <!-- PROFILE -->
      <li class="nav-item dropdown">

        <?php
        $employeeName = trim($_SESSION['EmployeeName'] ?? 'User');

        $nameParts = preg_split('/\s+/', $employeeName);

        if (count($nameParts) >= 2) {
          $initials = strtoupper(
            substr($nameParts[0], 0, 1) .
              substr($nameParts[count($nameParts) - 1], 0, 1)
          );
        } elseif (!empty($nameParts[0])) {
          $initials = strtoupper(substr($nameParts[0], 0, 2));
        } else {
          $initials = 'U';
        }
        ?>

        <a
          class="nav-link d-flex align-items-center py-1 px-2"
          data-coreui-toggle="dropdown"
          href="#"
          role="button"
          aria-haspopup="true"
          aria-expanded="false">

          <!-- PROFILE PILL -->
          <div
            class="d-flex align-items-center rounded-pill px-2 py-1"
                  style="
                    background: #ffffff;
                    min-height: 44px;
                    border: 1px solid #e9ecef;
                    box-shadow:
                    0 3px 8px rgba(0, 0, 0, 0.10),
                    0 1px 2px rgba(0, 0, 0, 0.06),
                    inset 0 1px 0 rgba(255, 255, 255, 0.9);
                  ">

            <!-- AVATAR -->
            <div
              class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0"
              style="
                width: 36px;
                height: 36px;
                font-size: 13px;
                font-weight: 600;
                box-shadow:
                0 2px 5px rgba(0, 0, 0, 0.20),
                inset 0 1px 1px rgba(255, 255, 255, 0.25);
               ">

              <?= htmlspecialchars($initials) ?>

            </div>

            <!-- NAME -->
            <span
              class="fw-semibold ms-2 text-dark"
              style="
                    max-width: 150px;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                ">

              <?= htmlspecialchars($employeeName) ?>

            </span>

            <!-- ARROW -->
            <i
              class="bi bi-chevron-down ms-2 me-1 text-dark"
              style="font-size: 12px;">
            </i>

          </div>
        </a>


        <!-- DROPDOWN -->
        <div class="dropdown-menu dropdown-menu-end shadow">

          <a
            class="dropdown-item"
            href="#"
            id="logoutBtn">

            <svg
              class="icon me-2"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 512 512">

              <path
                fill="currentColor"
                d="M77.155 272.034H351.75v-32.001H77.155l75.053-75.053v-.001l-22.628-22.626-113.681 113.68.001.001h-.001L129.58 369.715l22.628-22.627v-.001z" />

              <path
                fill="currentColor"
                d="M160 16v32h304v416H160v32h336V16z" />

            </svg>

            Logout

          </a>

        </div>

      </li>

    </ul>

  </div>
</header>


<script>
  document.getElementById('logoutBtn').addEventListener('click', function(e) {
    e.preventDefault();

    Swal.fire({
      title: 'Ready to leave?',
      text: 'Your current session will be securely logged out.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, Logout',
      cancelButtonText: 'Stay Logged In',
      reverseButtons: true,
      buttonsStyling: false,
      customClass: {
        popup: 'shadow-lg rounded-4',
        confirmButton: 'btn btn-danger px-4 ms-2',
        cancelButton: 'btn btn-light border px-4'
      }
    }).then((result) => {

      if (result.isConfirmed) {

        Swal.fire({
          title: 'Logging out...',
          text: 'Please wait a moment.',
          icon: 'info',
          showConfirmButton: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        // Small delay for a smoother logout experience
        setTimeout(() => {
          window.location.href = 'backend/authentication/logout.php';
        }, 1200);
      }
    });
  });
</script>