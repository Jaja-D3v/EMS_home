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

      <a class="nav-link" href="list-extinguisher.php">
        <i class="fa-solid fa-fire-extinguisher"></i>
        Fire Extinguisher List
      </a>

      <!-- Inspection Approvals -->
    <li class="nav-group">

      <a
        class="nav-link nav-group-toggle"
        href="#">

        <i class="fa-solid fa-list-check"></i>
        Inspection Approvals

      </a>

      <ul class="nav-group-items">

        <!-- Pending Approval -->
        <li class="nav-item">

          <a
            class="nav-link"
            href="inspection-approvals.php?status=pending">

            <i class="bi bi-clock me-2"></i>
            Pending Approval

          </a>

        </li>

        <!-- Approved -->
        <li class="nav-item">

          <a
            class="nav-link"
            href="inspection-approvals.php?status=approved">

            <i class="bi bi-check-circle me-2"></i>
            Approved

          </a>

        </li>

        <!-- Rejected -->
        <li class="nav-item">

          <a
            class="nav-link"
            href="inspection-approvals.php?status=rejected">

            <i class="bi bi-x-circle me-2"></i>
            Rejected

          </a>

        </li>

      </ul>

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