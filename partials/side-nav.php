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
        QR Code Generator
      </a>

      <a class="nav-link" href="list-extinguisher.php">
        <i class="fa-solid fa-fire-extinguisher"></i>
        Fire Extinguisher List
      </a>

      <a class="nav-link" href="audit-log.php">
        <i class="fa-solid fa-user-pen"></i>
        Activity Log
      </a>

      <a class="nav-link" href="notification.php">
        <i class="fa-solid fa-bell"></i>
        Notification
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