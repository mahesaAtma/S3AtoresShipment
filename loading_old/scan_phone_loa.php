<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.rawgit.com/serratus/quaggaJS/0420d5e0/dist/quagga.min.js"></script>
    <style>
        canvas.drawing, canvas.drawingBuffer {
            position: absolute;
            left: 0;
            top: 0;
        }
    </style>

    <?php 
        $no_unloading = $_GET['no_unloading'];
    ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
    <!-- Div to show the scanner -->
    <div class="main-content">
        <div class="container-fluid">
            <table class="" width="100%" style="padding:1px;">
                <tr>
                    <td class="pull-left" style="vertical-align:top;padding:20px;">
                    <h6><a href="javascript:history.back()"><i class="fas fa-arrow-circle-left"></i>&nbsp;&nbsp;GO BACK</a>&nbsp;&nbsp;&nbsp; <i class="fas fa-check"></i>&nbsp;&nbsp;UNLOADING SCAN</h6>
                    </td>
                </tr>
            </table>
            <hr />

            <div class="row">
                <div style="position: relative;">
                    <div id="scanner-container" style="width: 100vw;"></div>
                    <button id="stopScannerButton" style="position: absolute; top: 10px; right: 10px; display: none; padding-right: 20px; color: white; font-size:25px;"><b>X</b></button>
                </div>
            </div>
            <hr/>
            <br/>

            <div class="row">
                <div class="col-lg-12">
                    <form id="form-save" action="cross_docks/save_scan.php" name="modal_popup" enctype="multipart/form-data" method="POST">
                        <div class="card" style="border-radius: 10px; margin-bottom: 5px;">
                            <div class=" card-body">
                                <div class="form-group">

                                    <input type="hidden" class="form-control" id="no_unloading" name="no_unloading" value="<?php echo $no_unloading ?>">

                                    <!--<h6>Type Delivery</h6>
                                    <div class="input-group mb-3">
                                        <select class="form-control" id="type_delivery" name="type_delivery" required>
                                            <option value=""></option>
                                            <option value="OB">OB</option>
                                            <option value="WC">WC</option>
                                        </select>
                                    </div>
                                    -->

                                    <h6 id="cab_tujuan" style="display: none;">Cabang Tujuan</h6>
                                    <div class="input-group mb-3" id="formCabang" style="display: none;">
                                        <select class="form-control" id="cabang_tujuan" name="cabang_tujuan">
                                            <option value=""></option>
                                            <?php 

                                            $cabang = pg_query("SELECT * FROM ms_cabangs ORDER BY nama_cabang ASC");
                                            while ($sql = pg_fetch_array($cabang)) {
                                                $idCabang   = $sql['id'];
                                                $namaCabang = $sql['nama_cabang'];

                                                echo "<option value='$idCabang'>$namaCabang</option>";
                                            }
                                            
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card" style="border-radius: 10px; margin-bottom: 5px;">
                            <div class="card-body">
                                <h6>STTB Sudah Terscan</h6><br>
                
                                <?php 

                                    $crossDocks = pg_query("SELECT a.no_cross_docks, a.no_sttb, COUNT(a.no_sub_sttb) as koli, b.jml_print_barcode 
                                    FROM trx_cross_docks_d1 a 
                                    LEFT JOIN trx_shipment_entry_d1 b ON b.no_sttb = a.no_sttb
                                    WHERE a.no_cross_docks = '$no_unloading' AND a.status_scan = 'Y'
                                    GROUP BY a.no_cross_docks, a.no_sttb, b.jml_print_barcode");

                                    while ($sql = pg_fetch_array($crossDocks)) {
                                        $noSttb            = $sql['no_sttb'];
                                        $koli              = $sql['koli'];
                                        $jml_print_barcode = $sql['jml_print_barcode'];

                                        echo "
                                        <ul style='margin-left: 5px;'>
                                            <li style='display: flex; justify-content: space-between;'>
                                                <span>$noSttb</span>
                                                <span style='color: red;'>Total : $jml_print_barcode Koli</span>
                                            </li>
                                        </ul><hr>";
                                    }
                                                
                                ?>
                                <br>
                            </div>
                        </div>

                        <div class="card" style="border-radius: 10px; margin-bottom: 5px;">
                            <div class="card-body">
                                <h6>Hasil STTB HU</h6>
                                    <ol id="ol-list" class="list-group list-group-numbered" style="overflow-y: auto;">
                                        
                                    </ol>
                                <br>
                            </div>
                            <div class="card-footer">
                                <div class="form-group" style="vertical-align:bottom; text-align: center;">
                                    <button type="submit" class="btn btn btn-primary" id="btn-simpan" style="color: white; display: block; width: 100%;">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="quagga.min.js"></script>

    <script>
            var _scannerIsRunning   = false;
            var _codebefore         = "";
            var apples              = 0;
            var scannedCodes        = [];
            var codeToSendViaEmail  = "";

        document.addEventListener("DOMContentLoaded", function () {
            startScanner();
        });

        $('#type_delivery').on('change', function() {
            hasil = $('#type_delivery').val();
            if (hasil == 'WC') {
                $('#formCabang').hide();
                $('#cab_tujuan').hide();
            } else {
                $('#formCabang').show();
                $('#cab_tujuan').show();
            }
        });

        function startScanner() {
            const scannerContainer  = document.querySelector('#scanner-container');
            const stopScannerButton = document.querySelector('#stopScannerButton');

            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: scannerContainer,
                    constraints: {
                        // width: 360,
                        // height: 600,
                        facingMode: "environment"
                    },
                },
                decoder: {
                    readers: [
                        "code_128_reader",
                        "ean_reader",
                        "ean_8_reader",
                        "code_39_reader",
                        "code_39_vin_reader",
                        "codabar_reader",
                        "upc_reader",
                        "upc_e_reader",
                        "i2of5_reader"
                    ],
                    debug: {
                        showCanvas: true,
                        showPatches: true,
                        showFoundPatches: true,
                        showSkeleton: true,
                        showLabels: true,
                        showPatchLabels: true,
                        showRemainingPatchLabels: true,
                        boxFromPatches: {
                            showTransformed: true,
                            showTransformedBox: true,
                            showBB: true
                        }
                    }
                },

            }, function (err) {
                if (err) {
                    console.log(err);
                    return
                }

                console.log("Initialization finished. Ready to start");
                Quagga.start();

                _scannerIsRunning = true;

                stopScannerButton.style.display = "block";
            });

            Quagga.onProcessed(function (result) {
                var drawingCtx = Quagga.canvas.ctx.overlay,
                drawingCanvas = Quagga.canvas.dom.overlay;

                if (result) {
                    if (result.boxes) {
                        drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
                        result.boxes.filter(function (box) {
                            return box !== result.box;
                        }).forEach(function (box) {
                            Quagga.ImageDebug.drawPath(box, { x: 0, y: 1 }, drawingCtx, { color: "green", lineWidth: 2 });
                        });
                    }

                    if (result.box) {
                        Quagga.ImageDebug.drawPath(result.box, { x: 0, y: 1 }, drawingCtx, { color: "#00F", lineWidth: 2 });
                    }

                    if (result.codeResult && result.codeResult.code) {
                        Quagga.ImageDebug.drawPath(result.line, { x: 'x', y: 'y' }, drawingCtx, { color: 'red', lineWidth: 3 });
                    }
                }
            });

            Quagga.onDetected(function (result) {
                
                if( _codebefore===result.codeResult.code){
                    
                }else{

                    var scannedCode = result.codeResult.code;

                    if (!scannedCodes.includes(scannedCode)) {

                        scannedCodes.push(scannedCode);

                        $.ajax({
                            url: 'sttb_scan/cek_shipment.php',
                            method: 'POST',
                            data: { code: scannedCode },
                            success: function(response) {
                                if (response == 1) {
                                    alert('Data Berhasil Di Scan.');
                                    addToResi(scannedCode);
                                } else {
                                    _codebefore = scannedCode;
                                    Quagga.start();
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Terjadi kesalahan saat melakukan permintaan AJAX:', error);
                            }
                        });
                    }
                 
                }

                _isScanned = true;           
            });

        }

        function addToResi(code) {
            var newListItem         = document.createElement("li");
            newListItem.className   = "list-group-item d-flex justify-content-between align-items-start";
            newListItem.innerHTML   = `
                <div class="ms-2">${code}</div>
                <button class="btn-delete btn-sm btn-outline-dark" type="button">X</button>
            `;
            document.getElementById("ol-list").appendChild(newListItem);

            showNotification("Data Masuk", "Data baru masuk ke detail resi");

            newListItem.querySelector('.btn-delete').addEventListener('click', function() {
                var idToDelete = this.parentNode.querySelector('.ms-2').textContent;
                DeleteList(idToDelete);
            });

            scannedCodes.push(code);
            updateCodeToSendViaEmail();
        }

        function DeleteList(code) {
            var elementsToRemove = document.querySelectorAll('.ms-2');
            elementsToRemove.forEach(function(element) {
                if (element.textContent === code) {
                    var listItemToRemove = element.parentNode;
                    listItemToRemove.parentNode.removeChild(listItemToRemove);
                }
            });

            scannedCodes = scannedCodes.filter(function(element) {
                return element !== code;
            });

            updateCodeToSendViaEmail();
        }

        function updateCodeToSendViaEmail() {
            codeToSendViaEmail = [];
            $('.ms-2').each(function() {
                codeToSendViaEmail.push($(this).text());
            });
        }

        function showNotification(title, body) {

            if ('Notification' in window && navigator.serviceWorker) {

                Notification.requestPermission().then(function(permission) {
                    if (permission === "granted") {
                        var notification = new Notification(title, {
                            body: body
                        });
                        notification.vibrate = [200, 100, 200];
                    }
                });
            }
        }

        function stopScanner() {
            Quagga.stop();
            stopScannerButton.style.display = "none";
        }
        
        stopScannerButton.addEventListener('click', stopScanner);

        $('#form-save').submit(function(event) {
            event.preventDefault();
            var form = $(this);
            ajaxAction(form, codeToSendViaEmail);
        });

        function ajaxAction(form, codeToSendViaEmail) {

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var latitude        = position.coords.latitude;
                    var longitude       = position.coords.longitude;

                    var formData = {
                        no_cross_docks: $('#no_cross_docks').val(),
                        type_delivery: $('#type_delivery').val(),
                        cabang_tujuan: $('#cabang_tujuan').val(),
                        latitude: latitude,
                        longitude: longitude,
                        scan_result: codeToSendViaEmail
                    };

                    $.ajax({
                        method: form.attr("method"),
                        url: form.attr("action"),
                        data: formData,
                        success: function(response) {
                        $("#modal-data").empty();
                        $("#modal-data").html(response.data);
                        if (response == 1) {
                            Swal.fire({
                                type: 'success',
                                title: 'Data Scan Berhasil Disimpan!',
                                text: 'Anda akan di arahkan dalam 2 Detik',
                                timer: 2000,
                                showCancelButton: false,
                                showConfirmButton: false
                            })
                            .then(function() {
                                location.reload(true);    
                            });
                        } else {
                            alert("Submit Gagal");
                            $('#submit_button').prop('disabled', false);
                        }
                    },
                    error: function(e) {

                    },
                    beforeSend: function(b) {

                    }
                    });
                }, function(error) {
                    console.error("Error getting geolocation:", error);
                });
            } else {
                console.error("Geolocation is not supported by this browser.");
            }
        }

        var _isScanned = false;

        function myFunction() {
            var x = document.getElementById("myDIV");
            if (x.style.display === "none") {
            x.style.display = "block";
            } else {
            x.style.display = "none";
            }
        }

    </script>

    <!-- Jquery JS-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js">
    </script>

</body>
</html>