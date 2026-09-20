<?php $pageTitle = "HOME"; ?>

<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
* @version v5.5.0
* @link https://coreui.io/product/free-bootstrap-admin-template/
* Copyright (c) 2026 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://github.com/coreui/coreui-free-bootstrap-admin-template/blob/main/LICENSE)
-->

<html lang="en">
<?php include 'header.php'; ?>

<body>
  <?php include 'side-nav.php'; ?>
  <div class="wrapper d-flex flex-column min-vh-100">
    <?php include 'header-nav.php'; ?>


    <div class="body flex-grow-1">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-12 col-md-8 col-lg-6 col-xl-5">

            <div class="card">
              <div class="card-header">
                <strong>Scan Fire Extinguisher QR Code</strong>
              </div>

              <div class="card-body">

                <div id="qr-reader" class="w-100 overflow-hidden"></div>

                <div id="scan-result" class="mt-3"></div>

              </div>
            </div>

          </div>
        </div>
      </div>

      <script>
        const scanner = new Html5Qrcode("qr-reader");

        function startScanner() {

          Html5Qrcode.getCameras()
            .then(cameras => {

              if (!cameras || cameras.length === 0) {
                document.getElementById("scan-result").innerHTML =
                  `<div class="alert alert-danger">
                        No camera found on this device.
                    </div>`;
                return;
              }

              const cameraId = cameras[0].id;

              scanner.start(
                cameraId, {
                  fps: 10,
                  qrbox: {
                    width: 350,
                    height: 250
                  }
                },
                qrCodeMessage => {

                  // scanner.stop().then(() => {
                  //   window.location.href =
                  //     "view-extinguisher.php?id=" +
                  //     encodeURIComponent(qrCodeMessage);
                  // });

                  if (qrCodeMessage) {
                    document.getElementById("scan-result").innerHTML = `
                        <div class="alert alert-success">
                            QR Code detected: <strong>${qrCodeMessage}</strong>
                        </div>
                    `;

                    window.location.href =
                      `http://localhost/KKPI/QR-code-fire-extinguisher-inventory/test.php?code=${encodeURIComponent(qrCodeMessage)}`;
                  }

                },
                errorMessage => {
                  // QR not detected
                }
              );

            })
            .catch(() => {

              document.getElementById("scan-result").innerHTML =
                `<div class="alert alert-danger">
                    Camera access was denied or unavailable.
                </div>`;

            });
        }

        startScanner();
      </script>


    </div>
  </div>
  <?php include 'footer.php'; ?>
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
</body>

</html>