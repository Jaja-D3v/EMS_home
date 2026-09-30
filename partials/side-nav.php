<div class="sidebar sidebar-dark sidebar-fixed border-end" id="sidebar">
  <div class="sidebar-header border-bottom">
    <div class="sidebar-brand d-flex align-items-center gap-2">
      <i class="bi bi-fire text-danger fs-4"></i>

      <div class="d-flex flex-column">
        <span class="fw-bold fs-5 lh-1"
          style="letter-spacing: 1.5px; color: #ff3b30;">
          IMS
        </span>

        <small class="fw-semibold mt-1"
          style="font-size: 0.68rem; letter-spacing: 1.5px; color: #ffffff;">
          MANAGEMENT SYSTEM
        </small>
      </div>
    </div>
    <button class="btn-close d-lg-none" type="button" data-coreui-theme="dark" aria-label="Close" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"></button>
  </div>
  <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
    <li class="nav-title">Navigation</li>
    <li class="nav-item">

      <!-- side-bar navigation -->
      <a class="nav-link" href="dashboard.php">
        <!-- <i class="fa-solid fa-fire-extinguisher"></i> -->
        <i class="fa-solid fa-gauge"></i>
        Dashboard
      </a>

      <a class="nav-link" href="QR-code.php">
        <i class="fa-solid fa-qrcode"></i>
        Scan QR CODE
      </a>

      <a class="nav-link" href="QR-generator.php">
        <i class="fa-solid fa-qrcode"></i>
        Print QR Code
      </a>

      <!-- Fire Extinguisher List -->
    <li class="nav-item">

      <!-- Parent -->
      <a
        class="nav-link d-flex align-items-center"
        data-bs-toggle="collapse"
        href="#fireExtinguisherMenu"
        role="button"
        aria-expanded="false"
        aria-controls="fireExtinguisherMenu">

        <i class="fa-solid fa-fire-extinguisher me-2"></i>

        <span>Fire Extinguisher List</span>

        <i class="bi bi-chevron-down ms-auto submenu-chevron"></i>
      </a>


      <!-- Submenu -->
      <div
        class="collapse"
        id="fireExtinguisherMenu">

        <ul class="nav flex-column ms-3">

          <!-- Active Fire Extinguishers -->
          <li class="nav-item">

            <a
              class="nav-link"
              href="list-extinguisher.php">

              <i class="bi bi-fire me-2"></i>

              Active Fire Extinguishers

            </a>

          </li>


          <!-- Expiring Soon -->
          <li class="nav-item">

            <a
              class="nav-link"
              href="expiring-extinguisher.php">

              <i class="bi bi-hourglass-split me-2"></i>

              Expiring Soon

            </a>

          </li>


          <!-- Archived -->
          <li class="nav-item">

            <a
              class="nav-link"
              href="archived-extinguisher.php">

              <i class="bi bi-trash3 me-2"></i>

              Deleted

            </a>

          </li>

        </ul>

      </div>

    </li>
    <?php
    $currentStatus = $_GET['status'] ?? '';
    ?>

    <!-- Inspection Approvals -->
    <li class="nav-item">

      <a
        class="nav-link d-flex align-items-center"
        data-bs-toggle="collapse"
        href="#inspectionApprovalMenu"
        role="button"
        aria-expanded="false"
        aria-controls="inspectionApprovalMenu">

        <i class="bi bi-clipboard-check me-2"></i>

        <span>Inspection Approvals</span>

        <i class="bi bi-chevron-down ms-auto submenu-chevron"></i>
      </a>

      <div
        class="collapse"
        id="inspectionApprovalMenu">

        <ul class="nav flex-column ms-3">

          <li class="nav-item">
            <a class="nav-link" href="inspection-pending.php">
              <i class="bi bi-hourglass-split me-2"></i>
              Pending
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="inspection-approved.php">
              <i class="bi bi-check-circle me-2"></i>
              Approved
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="inspection-rejected.php">
              <i class="bi bi-x-circle me-2"></i>
              Rejected
            </a>
          </li>

        </ul>
      </div>
    </li>

    <!-- Activity Log -->
    <li class="nav-item">

      <a
        class="nav-link"
        href="activity-log.php">

        <i class="fa-solid fa-user-pen me-2"></i>
        Activity Log

      </a>

    </li>

    <!-- Maintenance -->
    <li class="nav-item">

      <a
        class="nav-link"
        href="maintenance.php">

        <i class="bi bi-tools me-2"></i>
        Maintenance

      </a>

    </li>
    <!-- end navigation -->
    </li>
    <li class="nav-divider"></li>
    <li class="nav-title">Extras</li>
  </ul>
  <div class="sidebar-footer border-top d-none d-md-flex">
    <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
  </div>
</div>