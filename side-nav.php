<div class="sidebar sidebar-dark sidebar-fixed border-end" id="sidebar">
    <div class="sidebar-header border-bottom">
      <div class="sidebar-brand me-auto">
        <img
          src="assets/img/KPPI_Logo.png"
          alt="My Logo"
          class="sidebar-brand-full"
          width="220"
          height="32">
        <svg role="img" aria-label="CoreUI Logo Signet" class="sidebar-brand-narrow" width="88" height="32" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 102 115">
          <g style="fill: currentColor">
            <path d="M96 24.124 57 1.608a12 12 0 0 0-12 0L6 24.124a12.034 12.034 0 0 0-6 10.393V79.55a12.033 12.033 0 0 0 6 10.392l39 22.517a12 12 0 0 0 12 0l39-22.517a12.033 12.033 0 0 0 6-10.392V34.517a12.034 12.034 0 0 0-6-10.393ZM94 79.55a4 4 0 0 1-2 3.464l-39 22.517a4 4 0 0 1-4 0L10 83.014a4 4 0 0 1-2-3.464V34.517a4 4 0 0 1 2-3.464L49 8.536a4 4 0 0 1 4 0l39 22.517a4 4 0 0 1 2 3.464V79.55Z" />
            <path d="M74.022 70.071h-2.866a4 4 0 0 0-1.925.494L51.95 80.05 32 68.531V45.554l19.95-11.519 17.29 9.455a4 4 0 0 0 1.919.49h2.863a2 2 0 0 0 2-2v-2.71a2 2 0 0 0-1.04-1.756L55.793 27.02a8.04 8.04 0 0 0-7.843.09L28 38.626a8.025 8.025 0 0 0-4 6.929V68.53a8 8 0 0 0 4 6.928l19.95 11.519a8.043 8.043 0 0 0 7.843.088l19.19-10.532a2 2 0 0 0 1.038-1.753v-2.71a2 2 0 0 0-2-2Z" />
          </g>
        </svg>
      </div>
      <button class="btn-close d-lg-none" type="button" data-coreui-theme="dark" aria-label="Close" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"></button>
    </div>
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
      <li class="nav-title">Navigation</li>
      <li class="nav-item">

        <!-- side-bar navigation -->
        <a class="nav-link" href="dashboard.php">
          <i class="fa-solid fa-fire-extinguisher"></i>
          Dashboard
        </a>

        <a class="nav-link" href="QR-code.php">
          <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentcolor)" d="M105.361 398.32A195.891 195.891 0 0 1 343.42 91.125l23.256-23.255A227.875 227.875 0 0 0 82.733 420.948 228.03 228.03 0 0 0 366.24 452.1l-23.312-23.312c-75.028 43.98-173.271 33.829-237.567-30.468" class="ci-primary" />
            <path fill="var(--ci-primary-color, currentcolor)" d="M468.916 353.07a243.54 243.54 0 0 0 0-186.459 248 248 0 0 0-2.747-6.354 242.3 242.3 0 0 0-50.059-72.686L404.8 76.257l-11.317 11.314-172.27 172.269 172.63 172.631 10.957 10.953 11.31-11.314a242.2 242.2 0 0 0 49.452-71.358 249 249 0 0 0 3.354-7.682m-64.557-231.12a211.57 211.57 0 0 1 0 275.781L266.468 259.84Z" class="ci-primary" />
          </svg>
          Scan QR CODE
        </a>

        <a class="nav-link" href="add-extinguisher.php">
          <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentcolor)" d="M105.361 398.32A195.891 195.891 0 0 1 343.42 91.125l23.256-23.255A227.875 227.875 0 0 0 82.733 420.948 228.03 228.03 0 0 0 366.24 452.1l-23.312-23.312c-75.028 43.98-173.271 33.829-237.567-30.468" class="ci-primary" />
            <path fill="var(--ci-primary-color, currentcolor)" d="M468.916 353.07a243.54 243.54 0 0 0 0-186.459 248 248 0 0 0-2.747-6.354 242.3 242.3 0 0 0-50.059-72.686L404.8 76.257l-11.317 11.314-172.27 172.269 172.63 172.631 10.957 10.953 11.31-11.314a242.2 242.2 0 0 0 49.452-71.358 249 249 0 0 0 3.354-7.682m-64.557-231.12a211.57 211.57 0 0 1 0 275.781L266.468 259.84Z" class="ci-primary" />
          </svg>
          add new extinguisher
        </a>

        <a class="nav-link" href="audit-log.php">
          <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentcolor)" d="M105.361 398.32A195.891 195.891 0 0 1 343.42 91.125l23.256-23.255A227.875 227.875 0 0 0 82.733 420.948 228.03 228.03 0 0 0 366.24 452.1l-23.312-23.312c-75.028 43.98-173.271 33.829-237.567-30.468" class="ci-primary" />
            <path fill="var(--ci-primary-color, currentcolor)" d="M468.916 353.07a243.54 243.54 0 0 0 0-186.459 248 248 0 0 0-2.747-6.354 242.3 242.3 0 0 0-50.059-72.686L404.8 76.257l-11.317 11.314-172.27 172.269 172.63 172.631 10.957 10.953 11.31-11.314a242.2 242.2 0 0 0 49.452-71.358 249 249 0 0 0 3.354-7.682m-64.557-231.12a211.57 211.57 0 0 1 0 275.781L266.468 259.84Z" class="ci-primary" />
          </svg>
          Audit log
        </a>

        <a class="nav-link" href="notification.php">
          <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentcolor)" d="M105.361 398.32A195.891 195.891 0 0 1 343.42 91.125l23.256-23.255A227.875 227.875 0 0 0 82.733 420.948 228.03 228.03 0 0 0 366.24 452.1l-23.312-23.312c-75.028 43.98-173.271 33.829-237.567-30.468" class="ci-primary" />
            <path fill="var(--ci-primary-color, currentcolor)" d="M468.916 353.07a243.54 243.54 0 0 0 0-186.459 248 248 0 0 0-2.747-6.354 242.3 242.3 0 0 0-50.059-72.686L404.8 76.257l-11.317 11.314-172.27 172.269 172.63 172.631 10.957 10.953 11.31-11.314a242.2 242.2 0 0 0 49.452-71.358 249 249 0 0 0 3.354-7.682m-64.557-231.12a211.57 211.57 0 0 1 0 275.781L266.468 259.84Z" class="ci-primary" />
          </svg>
          notification
        </a>
        <!-- end navigation -->

      </li>
      <li class="nav-divider"></li>
      <li class="nav-title">Extras</li>
    </ul>
    <div class="sidebar-footer border-top d-none d-md-flex">
      <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
    </div>
  </div>