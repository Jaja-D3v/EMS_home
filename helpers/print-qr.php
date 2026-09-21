<?php

$codes = [];

if (isset($_GET['codes']) && !empty($_GET['codes'])) {
    $codes = explode(',', $_GET['codes']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Print QR Codes</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- QR Code Library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>


    <style>
        .qr-item {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            page-break-inside: avoid;
            break-inside: avoid;
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 125px;
        }

        .qr-code {
            flex-shrink: 0;
        }

        .qr-info {
            flex: 1;
        }

        @media print {

            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
            }

            .container {
                max-width: 100% !important;
                width: 100% !important;
                padding: 0 !important;
            }

            .row {
                --bs-gutter-x: 8px;
                --bs-gutter-y: 8px;
            }

            .qr-item {
                border: 1px solid #000;
                padding: 6px;
                min-height: 115px;
            }

            .qr-code img {
                width: 100px !important;
                height: 100px !important;
            }

            .qr-info {
                font-size: 12px;
            }

        }
    </style>

</head>


<body>


    <div class="container py-4">


        <!-- HEADER / PRINT BUTTON -->

        <div class="no-print d-flex justify-content-between align-items-center mb-4">

            <h3 class="mb-0">
                Print QR Codes
            </h3>

            <button
                type="button"
                class="btn btn-primary"
                onclick="window.print()">
                Print
            </button>

        </div>


        <!-- QR CODES -->

        <div class="row">

            <?php foreach ($codes as $index => $code): ?>

                <div class="col-4">

                    <div class="qr-item">

                        <!-- QR CODE -->
                        <div
                            id="qr-<?= $index ?>"
                            class="qr-code"></div>

                        <!-- INFORMATION -->
                        <div class="qr-info">

                            <div class="fw-bold">
                                Fire Extinguisher
                            </div>

                            <div class="fw-semibold">
                                <?= htmlspecialchars($code) ?>
                            </div>

                            <div class="small text-muted">
                                Scan QR Code
                            </div>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>


    </div>


    <script>
        const codes = <?= json_encode(array_values($codes)) ?>;


        codes.forEach(function(code, index) {

            new QRCode(
                document.getElementById('qr-' + index), {
                    text: code,
                    width: 100,
                    height: 100
                }
            );

        });
    </script>


</body>

</html>