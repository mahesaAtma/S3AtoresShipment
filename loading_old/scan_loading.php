<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Unloading</title>

    <style>
        canvas.drawing, canvas.drawingBuffer {
            position: absolute;
            left: 0;
            top: 0;
        }
        #sig canvas {
            width: 100% !important;
            height: auto;
        }
        .input-wrapper {
            display: flex;
            align-items: center;
        }
        .input-wrapper .form-control {
            flex: 1;
        }
        .input-wrapper .btn {
            margin-left: 8px;
        }
        .right-aligned {
            margin-left: auto;
            text-align: right;
        }
        .video-container {
            position: relative;
            width: 100%;
            max-width: 600px; 
        }
        #previewKamera {
            width: 100%;
            height: auto;
        }
        #stopScannerButton {
            position: absolute;
            top: 10px; 
            right: 10px;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            z-index: 10; 
            font-size: 30px;
        }
    </style>

    <?php 
        $no_unloading = $_GET['no_unloading'];
    ?>

</head>
<body>

    <div class="main-content">
        <div class="section__content section__content--p25">
            <div class="container-fluid">
                <table class="" width="100%" style="padding:1px;">
                    <tr>
                        <td class="pull-left" style="vertical-align:top;padding:20px;">
                            <h6><a href="javascript:history.back()"><i class="fas fa-arrow-circle-left"></i>&nbsp;&nbsp;GO BACK</a></h6>
                        </td>
                    </tr>
                </table>
                <hr />

                <div class="row" id="hiddenButton">
                    <div class="video-container">
                        <video id="previewKamera">
                        </video>
                        <button id="stopScannerButton"><b>X</b></button>
                    </div>
                </div>

                <div class="row" id="hiddenOptionCam">
                    <div class="col-lg-12">
                        <select id="pilihKamera" class="form-control" style="max-width:400px">
                        </select>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card" style="border-radius: 10px; margin-bottom: 5px;">
                            <div class="card-header">
                                <h4>Unloading</h4>
                            </div>
                            <div class="card-body">
                                <br>
                                <form class="form-horizontal" method="post">
                                    <div class="panel-body" id="form-scan">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="input-wrapper">
                                                        <input type="text" class="form-control" id="sttb_unloading" name="sttb_unloading">
                                                        <input type="hidden" class="form-control" id="no_unloading" name="no_unloading" value="<?php echo $no_unloading ?>">
                                                        <button type="button" class="btn btn-primary" id="btn-scan">
                                                            <i class='fas fa-qrcode' style='font-size:20px'></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class="list-group">
                                    <ul style="list-style: none;">
                                        <?php
                                            $queryNoUN = pg_query($dbconn, "SELECT a.no_unloading, a.tanggal_unloading, a.no_armada 
                                            FROM trx_unloading a WHERE a.no_unloading = '$no_unloading'");
                                            while ($row1 = pg_fetch_array($queryNoUN)) {
                                        ?>
                                        <li>
                                            <a href="#" class="list-group-item list-group-item-action" aria-current="true" style="background-color: #d9e6f2;">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h5 class="mb-1" style="font-size: 1rem;"><?php echo $row1['no_unloading'] ?></h5>
                                                    <h5 class="mb-1" style="font-size: 1rem;"><?php echo $row1['no_armada'] ?></h5>
                                                </div>
                                            </a>
                                            <div class="card" id="sttb-details">

                                            </div>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

      
     <script type="text/javascript" src="https://unpkg.com/@zxing/library@latest"></script>
     <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
 
     <script>
        let selectedDeviceId = null;
        const codeReader = new ZXing.BrowserMultiFormatReader();
        const sourceSelect = $("#pilihKamera");

        $(document).ready(function() {
            $.ajax({
                url: 'unloading/cek_sttb_scan.php', 
                method: 'POST',
                data: { 
                    no_unloading: $("#no_unloading").val()
                }, 
                dataType: 'json', 
                success: function(response) {
                    if (response.length > 0) {

                        $('#sttb-details').empty();

                        response.forEach(function(item) {

                            var no_sttb         = item.no_sttb;
                            var nama_kota_kab   = item.nama_kota_kab;
                            var nama_service    = item.nama_service_product;
                            var koli_total      = item.koli_total === null ? 0 : item.koli_total;
                            var koli_scan       = item.koli_scan === null ? 0 : item.koli_scan;

                            var detailSttbHTML  = '<div class="card-body d-flex justify-content-between align-items-center">';
                            detailSttbHTML      += '<div>';
                            detailSttbHTML      += '<p style="margin: 5px 0; font-size: 15px;">' + no_sttb + '</p>';
                            detailSttbHTML      += '<p style="margin: 5px 0; font-size: 15px;">' + nama_kota_kab + '</p>';
                            detailSttbHTML      += '</div>';
                            detailSttbHTML      += '<div class="right-aligned">';
                            detailSttbHTML      += '<p style="margin: 5px 0; font-size: 15px;">' + nama_service + '</p>';
                            detailSttbHTML      += '<p style="margin: 5px 0; font-size: 15px; color: green;">Total Scan : ' + koli_scan + '/' + koli_total + ' Koli</p>';
                            detailSttbHTML      += '</div></div>';

                            $('#sttb-details').append(detailSttbHTML);

                        });

                    } else {
                                                            
                    }
                },
            });
        })

        $(document).on('change', '#pilihKamera', function() {
            selectedDeviceId = $(this).val();
            if (codeReader) {
                codeReader.reset();
                initScanner();
            }
        });

        let previousScanResult = "";

        function initScanner() {
            
            codeReader.listVideoInputDevices()
                .then(videoInputDevices => {
                    videoInputDevices.forEach(device =>
                        console.log(`${device.label}, ${device.deviceId}`)
                    );

                    if (videoInputDevices.length > 0) {
                        if (selectedDeviceId == null) {
                            selectedDeviceId = videoInputDevices[0].deviceId;
                        }

                        // Mengisi opsi pilihan kamera
                        sourceSelect.empty(); 
                        videoInputDevices.forEach((element) => {
                            const sourceOption = $("<option></option>")
                                .text(element.label)
                                .val(element.deviceId);
                            if (element.deviceId == selectedDeviceId) {
                                sourceOption.prop("selected", true);
                            }
                            sourceSelect.append(sourceOption);
                        });

                        // Konfigurasi untuk mendekode QR code dan barcode
                        const hints = new Map();
                        hints.set(2, [ZXing.BarcodeFormat.QR_CODE]); // Mendukung QR code
                        hints.set(1, [ZXing.BarcodeFormat.CODE_128]); // Mendukung Code 128 barcode, bisa tambahkan format lainnya sesuai kebutuhan

                        const constraints = {
                            deviceId: selectedDeviceId ? { exact: selectedDeviceId } : undefined,
                            video: {
                                width: { ideal: 1920 },
                                height: { ideal: 1080 },
                                facingMode: "environment", 
                            },
                            frameRate: { ideal: 30 },
                        };

                        codeReader.decodeFromVideoDevice(selectedDeviceId, 'previewKamera', (result, err) => {
                            if (result) {
                                console.log(result.text); 

                                if (result.text !== previousScanResult) { // Periksa apakah hasil scan baru berbeda dengan yang sebelumnya
                                    previousScanResult = result.text;
                                    $("#sttb_unloading").val(result.text); 

                                    $.ajax({
                                        url: 'unloading/insert_scan_unloading.php', 
                                        method: 'POST',
                                        data: { 
                                            no_unloading: $("#no_unloading").val(),
                                            resultScan: $("#sttb_unloading").val()
                                        }, 
                                        dataType: 'json', 
                                        success: function(response) {
                                            if (response == 1) {

                                                $.ajax({
                                                    url: 'unloading/cek_sttb_scan.php', 
                                                    method: 'POST',
                                                    data: { 
                                                        no_unloading: $("#no_unloading").val()
                                                    }, 
                                                    dataType: 'json', 
                                                    success: function(response) {
                                                        if (response.length > 0) {

                                                            $('#sttb-details').empty();

                                                            response.forEach(function(item) {

                                                                var no_sttb         = item.no_sttb;
                                                                var nama_kota_kab   = item.nama_kota_kab;
                                                                var nama_service    = item.nama_service_product;
                                                                var koli_total      = item.koli_total === null ? 0 : item.koli_total;
                                                                var koli_scan       = item.koli_scan === null ? 0 : item.koli_scan;

                                                                var detailSttbHTML  = '<div class="card-body d-flex justify-content-between align-items-center">';
                                                                detailSttbHTML      += '<div>';
                                                                detailSttbHTML      += '<p style="margin: 5px 0; font-size: 15px;">' + no_sttb + '</p>';
                                                                detailSttbHTML      += '<p style="margin: 5px 0; font-size: 15px;">' + nama_kota_kab + '</p>';
                                                                detailSttbHTML      += '</div>';
                                                                detailSttbHTML      += '<div class="right-aligned">';
                                                                detailSttbHTML      += '<p style="margin: 5px 0; font-size: 15px;">' + nama_service + '</p>';
                                                                detailSttbHTML      += '<p style="margin: 5px 0; font-size: 15px; color: green;">Total Scan : ' + koli_scan + '/' + koli_total + ' Koli</p>';
                                                                detailSttbHTML      += '</div></div>';

                                                                $('#sttb-details').append(detailSttbHTML);


                                                            });

                                                            $('#sttb_unloading').val('');

                                                        } else {
                                                                                                
                                                        }
                                                    },
                                                });

                                            } else {

                                                alert("Gagal Scan");

                                            }
                                        }
                                    });
                                }
                            }
                            if (err && !(err instanceof ZXing.NotFoundException)) {
                                console.error(err);
                            }
                        }, constraints, hints);
                    } else {
                        alert("Camera not found!");
                    }
                })
                .catch(err => console.error(err));
        }
 
        if (navigator.mediaDevices) {
             
            initScanner()
             
        } else {
            alert('Cannot access camera.');
        }

        function stopScanner() {
            codeReader.reset()
            document.getElementById('hiddenButton').style.display = 'none';
            document.getElementById('hiddenOptionCam').style.display = 'none';
        }

        document.getElementById('stopScannerButton').addEventListener('click', stopScanner);
       
     </script>
</body>
</html>