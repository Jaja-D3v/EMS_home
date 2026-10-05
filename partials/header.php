 <head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>IMS | Management System</title>

    <link rel="icon" type="image/png" sizes="192x192" href="assets/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" rel="stylesheet">
    <link rel="manifest" href="assets/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- Vendors styles-->
    <link rel="stylesheet" href="vendors/simplebar/css/simplebar.css">
    <link rel="stylesheet" href="css/vendors/simplebar.css">
    <!-- Main styles for this application-->
    <link href="css/style.css" rel="stylesheet">
    <script src="js/color-modes.js"></script>
    <!-- We use those styles to show code examples, you should remove them in your application.-->
    <!-- <link href="css/examples.css" rel="stylesheet">
    <script src="js/config.js"></script>
   -->

    <!-- added: Jaja.Dev -->

    <script src="https://unpkg.com/html5-qrcode"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 </head>

 <style>
    /* for inspection approve module */

    .inspection-page {
       padding-bottom: 80px;
    }

    .inspection-header {
       border-radius: .75rem;
    }

    .inspection-header-icon {
       width: 64px;
       height: 64px;
       display: flex;
       align-items: center;
       justify-content: center;
       flex-shrink: 0;
    }

    .inspection-filter-card {
       border-radius: .75rem;
    }

    .inspection-filter-card .card-body {
       padding: 1rem;
    }

    .inspection-filter-card .input-group,
    .inspection-filter-card .form-select {
       min-height: 42px;
    }

    .inspection-filter-card .input-group-text {
       min-width: 44px;
       justify-content: center;
    }

    .inspection-table-card {
       border-radius: .75rem;
       overflow: hidden;
    }

    .inspection-table-wrapper {
       width: 100%;
       overflow-x: auto;
       -webkit-overflow-scrolling: touch;
    }

    .inspection-table {
       min-width: 900px;
       margin-bottom: 0 !important;
    }

    .inspection-table th {
       font-size: .82rem;
       font-weight: 600;
       white-space: nowrap;
       vertical-align: middle;
    }

    .inspection-table td {
       font-size: .9rem;
       vertical-align: middle;
    }

    .inspection-table tbody tr {
       height: 64px;
    }

    .inspection-table .status-badge {
       white-space: nowrap;
    }

    .inspection-table .inspection-actions {
       display: flex;
       justify-content: flex-end;
       align-items: center;
       flex-wrap: wrap;
       gap: .35rem;
    }

    .inspection-table .inspection-actions .btn {
       white-space: nowrap;
    }

    .inspection-empty-state {
       min-height: 220px;
       display: flex;
       align-items: center;
       justify-content: center;
    }

    .inspection-pagination {
       z-index: 1020;
    }

    .inspection-pagination-inner {
       width: 100%;
    }

    .inspection-pagination .pagination {
       margin-bottom: 0;
    }

    .inspection-content {
       padding-bottom: 90px;
    }

    @media (max-width:991.98px) {
       .inspection-header .card-body {
          padding: 1.25rem;
       }

       .inspection-header-icon {
          width: 56px;
          height: 56px;
          font-size: 1.6rem !important;
       }

       .inspection-header h2 {
          font-size: 1.4rem;
       }
    }

    @media (max-width:767.98px) {
       .inspection-page {
          padding-left: .5rem;
          padding-right: .5rem;
       }

       .inspection-header .card-body {
          padding: 1rem;
       }

       .inspection-header .row {
          align-items: center;
       }

       .inspection-header-icon {
          width: 52px;
          height: 52px;
          padding: .75rem !important;
          font-size: 1.4rem !important;
       }

       .inspection-header h2 {
          font-size: 1.2rem;
       }

       .inspection-header p {
          font-size: .85rem;
       }

       .inspection-filter-card .card-body {
          padding: .85rem;
       }

       .inspection-table {
          min-width: 900px;
       }

       .inspection-table th,
       .inspection-table td {
          padding-top: .75rem;
          padding-bottom: .75rem;
       }

       .inspection-table .inspection-actions {
          justify-content: flex-end;
       }

       .inspection-table .inspection-actions .btn {
          font-size: .78rem;
          padding: .3rem .55rem;
       }

       .inspection-pagination {
          padding-top: .5rem !important;
          padding-bottom: .5rem !important;
       }

       .inspection-pagination .container-fluid {
          padding-left: .75rem !important;
          padding-right: .75rem !important;
       }
    }

    @media (max-width:575.98px) {
       .inspection-page {
          padding-left: .25rem;
          padding-right: .25rem;
       }

       .inspection-header {
          margin-bottom: .75rem !important;
       }

       .inspection-header .row {
          flex-wrap: nowrap;
       }

       .inspection-header-icon {
          width: 46px;
          height: 46px;
          padding: .6rem !important;
       }

       .inspection-header h2 {
          font-size: 1.05rem;
       }

       .inspection-header p {
          font-size: .78rem;
       }

       .inspection-filter-card {
          margin-bottom: .75rem !important;
       }

       .inspection-filter-card .card-body {
          padding: .75rem;
       }

       .inspection-table-card {
          border-radius: .6rem;
       }

       .inspection-table {
          min-width: 880px;
       }

       .inspection-pagination-inner {
          gap: .5rem !important;
       }

       .inspection-pagination .text-body-secondary {
          font-size: .72rem;
       }

       .inspection-pagination .page-link {
          padding: .3rem .55rem;
       }
    }
 </style>