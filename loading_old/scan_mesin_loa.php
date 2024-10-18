<?php

$no_loading = $_GET['id'];

?>

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
    .card-body {
        border-bottom: 1px solid #ccc;
        padding-bottom: 5px;
        margin-bottom: 5px;
    }

    .card-body:last-child {
        border-bottom: none; 
        margin-bottom: 0;
    }
</style>

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
                            <h4>Loading</h4>
                        </div>
                        <div class="card-body">
                            <br>
                            <form class="form-horizontal" method="post">
                                <div class="panel-body" id="form-scan">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">

                                                <div class="input-group mb-3">
                                                    <select class="form-control" id="type_delivery" name="type_delivery" required>
                                                        <option value="">Type Kirim</option>
                                                        <option value="OB">OB</option>
                                                        <option value="WC">WC</option>
                                                    </select>
                                                </div>

                                                <div class="input-group mb-3" id="formCabang" style="display: none;">
                                                    <select class="form-control select2" id="cabang_tujuan" name="cabang_tujuan">
                                                        <option value="">Cabang Tujuan</option>
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

                                                <div class="input-wrapper" id="sttb_scan_wrapper" style="display: none;">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="sttb_scan" name="sttb_scan" style="width: 200px;">
                                                        <input type="hidden" class="form-control" id="no_loading" name="no_loading" value="<?php echo $no_loading ?>">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-primary" id="btn-scan">
                                                                <i class="fas fa-qrcode" style="font-size: 20px;"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="list-group">
                                <ul style="list-style: none;">
                                    <?php
                                        $queryNoLO = pg_query($dbconn, "SELECT a.no_loading, a.tanggal_loading, a.no_order_delivery, c.nama_routes
                                        FROM trx_loading a
                                        LEFT JOIN trx_delivery_sheet b ON b.no_delivery = a.no_order_delivery 
                                        LEFT JOIN ms_routes c ON c.id = b.route_id
                                        WHERE a.no_loading = '$no_loading'");
                                        while ($row1 = pg_fetch_array($queryNoLO)) {
                                    ?>
                                    <li>
                                        <a href="#" class="list-group-item list-group-item-action" aria-current="true" style="background-color: #d9e6f2;">
                                            <div class="d-flex w-100 justify-content-between">
                                                <b><p class="mb-1" style="font-size: 14px;"><?php echo $row1['no_loading'] ?></p></b>
                                                <b><p class="mb-1" style="font-size: 14px;"><?php echo $row1['no_order_delivery'] ?></p></b>
                                            </div>
                                            <div class="d-flex w-100 justify-content-between">
                                                <p style="font-size: 11px;">
                                                    <?php 
                                                    $dateString = $row1['tanggal_loading'];
                                                    $dateWithoutTime = date('Y-m-d', strtotime($dateString));
                                                    echo $dateWithoutTime; ?>
                                                </p>
                                                <p style="font-size: 11px;"><?php echo $row1['nama_routes'] ?></p>
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

    $('#type_delivery').on('change', function() {
        hasil = $('#type_delivery').val();
        if (hasil == 'WC') {
            $('#formCabang').hide();
        } else {
            $('#formCabang').show();
        }
    });

    document.getElementById('hiddenButton').style.display = 'none';
    document.getElementById('hiddenOptionCam').style.display = 'none';

    $(document).ready(function() {
    $.ajax({
        url: 'loading_old/cek_sttb_scan.php', 
        method: 'POST',
        data: { 
            no_loading: $("#no_loading").val()
        }, 
        dataType: 'json', 
        success: function(response) {
            if (response.length > 0) {
                $('#sttb-details').empty();

                var groupedData = {};

                response.forEach(function(item) {
                    var key = item.no_resi ? 'resi_' + item.no_resi : 'sttb_' + item.no_sttb;

                    if (!groupedData[key]) {
                        groupedData[key] = {
                            no_sttb: item.no_sttb,
                            no_resi: item.no_resi,
                            nama_kota_kab: item.nama_kota_kab,
                            nama_service: item.nama_service_product,
                            status: item.status,
                            nama_penerima: item.nama_pic_penerima,
                            koli_total: 0,
                            koli_scan: 0,
                            no_hu_list: []
                        };
                    }
                    groupedData[key].no_hu_list.push(item.no_hu);
                    groupedData[key].koli_total += item.koli_total === null ? 0 : parseInt(item.koli_total);
                    groupedData[key].koli_scan += item.koli_scan === null ? 0 : parseInt(item.koli_scan);
                });

                for (var key in groupedData) {
                    var item = groupedData[key];

                    var color = parseInt(item.koli_scan) === parseInt(item.koli_total) ? 'green' : 'red';

                    var detailSttbHTML = '<div class="card-body d-flex justify-content-between align-items-center">';
                    detailSttbHTML += '<div>';
                    if (!item.no_resi || item.no_resi === 'null') {
                        detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">No. STTB : ' + item.no_sttb + '</p>';
                    } else {
                        detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">No. DN : ' + item.no_resi + '</p>';
                        detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">No. HU:</p>';
                        item.no_hu_list.forEach(function(hu) {
                            detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">- ' + hu + '</p>';
                        });
                    }

                    detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">[' + item.status + ']</p>';
                    detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">' + item.nama_kota_kab + '</p>';
                    detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">' + item.nama_penerima + '</p>';
                    detailSttbHTML += '</div>';
                    detailSttbHTML += '<div class="right-aligned">';
                    detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">' + item.nama_service + '</p>';
                    detailSttbHTML += '<b><p style="margin: 5px 0; font-size: 13px; color:' + color + ';">Scan : ' + item.koli_scan + '/' + item.koli_total + ' Koli</p></b>';
                    detailSttbHTML += '</div></div>';

                    $('#sttb-details').append(detailSttbHTML);
                }
            } else {
            }
        },
    });
});



    $(window).keydown(function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

    $('#sttb_scan').keydown(function(event) {
        if (event.keyCode == 13) {
            let noAwb = $('#sttb_scan').val();

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;

                    $.ajax({
                        url: 'loading_old/save_scan.php', 
                        method: 'POST',
                        data: { 
                            no_loading: $("#no_loading").val(),
                            resultScan: $("#sttb_scan").val(),
                            type_delivery: $("#type_delivery").val(),
                            cabang_tujuan: $("#cabang_tujuan").val(),
                            latitude: latitude,
                            longitude: longitude
                        }, 
                        dataType: 'json', 
                        success: function(response) {
                            if (response.status == 1) {

                                navigator.vibrate(400);
        
                                $.ajax({
                                    url: 'loading_old/cek_sttb_scan.php', 
                                    method: 'POST',
                                    data: { 
                                        no_loading: $("#no_loading").val()
                                    }, 
                                    dataType: 'json', 
                                    success: function(response) {
                                    if (response.length > 0) {
        
                                        $('#sttb-details').empty();

                                        var groupedData = {};

                                        response.forEach(function(item) {
                                            var key = item.no_resi ? 'resi_' + item.no_resi : 'sttb_' + item.no_sttb;

                                            if (!groupedData[key]) {
                                                groupedData[key] = {
                                                    no_sttb: item.no_sttb,
                                                    no_resi: item.no_resi,
                                                    nama_kota_kab: item.nama_kota_kab,
                                                    nama_service: item.nama_service_product,
                                                    status: item.status,
                                                    nama_penerima: item.nama_pic_penerima,
                                                    koli_total: 0,
                                                    koli_scan: 0,
                                                    no_hu_list: []
                                                };
                                            }
                                            groupedData[key].no_hu_list.push(item.no_hu);
                                            groupedData[key].koli_total += item.koli_total === null ? 0 : parseInt(item.koli_total);
                                            groupedData[key].koli_scan += item.koli_scan === null ? 0 : parseInt(item.koli_scan);
                                        });

                                        for (var key in groupedData) {
                                            var item = groupedData[key];

                                            var color = parseInt(item.koli_scan) === parseInt(item.koli_total) ? 'green' : 'red';

                                            var detailSttbHTML = '<div class="card-body d-flex justify-content-between align-items-center">';
                                            detailSttbHTML += '<div>';
                                            if (!item.no_resi || item.no_resi === 'null') {
                                                detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">No. STTB : ' + item.no_sttb + '</p>';
                                            } else {
                                                detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">No. DN : ' + item.no_resi + '</p>';
                                                detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">No. HU:</p>';
                                                item.no_hu_list.forEach(function(hu) {
                                                    detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">- ' + hu + '</p>';
                                                });
                                            }

                                            detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">[' + item.status + ']</p>';
                                            detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">' + item.nama_kota_kab + '</p>';
                                            detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">' + item.nama_penerima + '</p>';
                                            detailSttbHTML += '</div>';
                                            detailSttbHTML += '<div class="right-aligned">';
                                            detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">' + item.nama_service + '</p>';
                                            detailSttbHTML += '<b><p style="margin: 5px 0; font-size: 13px; color:' + color + ';">Scan : ' + item.koli_scan + '/' + item.koli_total + ' Koli</p></b>';
                                            detailSttbHTML += '</div></div>';

                                            $('#sttb-details').append(detailSttbHTML);
                                        }
        
                                        $('#sttb_scan').val('');
        
                                    } else {
                                                                                                        
                                    }
                                    },
                                });
            
                            } else if (response.status == "formatnotfound") {
                                alert("Format Scan STTB Tidak Sesuai");
                            } else if (response.status == "rutenotfound") {
                                alert("Rute Loading dengan No. STTB " + response.no_sttb + " ini tidak termasuk dalam Rute yang didukung");
                            } else if (response.status == "unloadnotfound") {
                                alert("Tidak ada Unloading dengan No. STTB " + response.no_sttb);
                            } else {
                                alert("Gagal Scan");
                            }
                        }
                    });
                })
            }
        }
    });

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
                            $("#sttb_scan").val(result.text); 

                            if (navigator.geolocation) {
                                navigator.geolocation.getCurrentPosition(function(position) {
                                    var latitude = position.coords.latitude;
                                    var longitude = position.coords.longitude;

                                    $.ajax({
                                        url: 'loading_old/save_scan.php', 
                                        method: 'POST',
                                        data: { 
                                            no_loading: $("#no_loading").val(),
                                            resultScan: $("#sttb_scan").val(),
                                            type_delivery: $("#type_delivery").val(),
                                            cabang_tujuan: $("#cabang_tujuan").val(),
                                            latitude: latitude,
                                            longitude: longitude
                                        }, 
                                        dataType: 'json', 
                                        success: function(response) {
                                            if (response.status == 1) {

                                                navigator.vibrate(400);
        
                                                $.ajax({
                                                    url: 'loading_old/cek_sttb_scan.php', 
                                                    method: 'POST',
                                                    data: { 
                                                        no_loading: $("#no_loading").val()
                                                    }, 
                                                    dataType: 'json', 
                                                    success: function(response) {
                                                        if (response.length > 0) {
                                                            $('#sttb-details').empty();

                                                            var groupedData = {};

                                                            response.forEach(function(item) {
                                                                var key = item.no_resi ? 'resi_' + item.no_resi : 'sttb_' + item.no_sttb;

                                                                if (!groupedData[key]) {
                                                                    groupedData[key] = {
                                                                        no_sttb: item.no_sttb,
                                                                        no_resi: item.no_resi,
                                                                        nama_kota_kab: item.nama_kota_kab,
                                                                        nama_service: item.nama_service_product,
                                                                        status: item.status,
                                                                        nama_penerima: item.nama_pic_penerima,
                                                                        koli_total: 0,
                                                                        koli_scan: 0,
                                                                        no_hu_list: []
                                                                    };
                                                                }
                                                                groupedData[key].no_hu_list.push(item.no_hu);
                                                                groupedData[key].koli_total += item.koli_total === null ? 0 : parseInt(item.koli_total);
                                                                groupedData[key].koli_scan += item.koli_scan === null ? 0 : parseInt(item.koli_scan);
                                                            });

                                                            for (var key in groupedData) {
                                                                var item = groupedData[key];

                                                                var color = parseInt(item.koli_scan) === parseInt(item.koli_total) ? 'green' : 'red';

                                                                var detailSttbHTML = '<div class="card-body d-flex justify-content-between align-items-center">';
                                                                detailSttbHTML += '<div>';
                                                                if (!item.no_resi || item.no_resi === 'null') {
                                                                    detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">No. STTB : ' + item.no_sttb + '</p>';
                                                                } else {
                                                                    detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">No. DN : ' + item.no_resi + '</p>';
                                                                    detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">No. HU:</p>';
                                                                    item.no_hu_list.forEach(function(hu) {
                                                                        detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">- ' + hu + '</p>';
                                                                    });
                                                                }

                                                                detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">[' + item.status + ']</p>';
                                                                detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">' + item.nama_kota_kab + '</p>';
                                                                detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">' + item.nama_penerima + '</p>';
                                                                detailSttbHTML += '</div>';
                                                                detailSttbHTML += '<div class="right-aligned">';
                                                                detailSttbHTML += '<p style="margin: 5px 0; font-size: 13px;">' + item.nama_service + '</p>';
                                                                detailSttbHTML += '<b><p style="margin: 5px 0; font-size: 13px; color:' + color + ';">Scan : ' + item.koli_scan + '/' + item.koli_total + ' Koli</p></b>';
                                                                detailSttbHTML += '</div></div>';

                                                                $('#sttb-details').append(detailSttbHTML);
                                                            }

        
                                                            $('#sttb_scan').val('');
        
                                                        } else {
                                                                                                        
                                                        }
                                                    },
                                                });
        
                                            } else if (response.status == "formatnotfound") {
                                                alert("Format Scan STTB Tidak Sesuai");
                                            } else if (response.status == "rutenotfound") {
                                                alert("Rute Loading dengan No. STTB " + response.no_sttb + " ini tidak termasuk dalam Rute yang didukung");
                                            } else if (response.status == "unloadnotfound") {
                                                alert("Tidak ada Unloading dengan No. STTB " + response.no_sttb);
                                            } else {
                                                alert("Gagal Scan");
                                            }
                                        }
                                    });
                                })
                            }

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

    function startScanner() {
        codeReader.reset()
        initScanner()
        document.getElementById('hiddenButton').style.display = 'block';
        document.getElementById('hiddenOptionCam').style.display = 'block';
    }

    document.getElementById('btn-scan').addEventListener('click', startScanner);

    function stopScanner() {
        codeReader.reset()
        document.getElementById('hiddenButton').style.display = 'none';
        document.getElementById('hiddenOptionCam').style.display = 'none';
    }

    document.getElementById('stopScannerButton').addEventListener('click', stopScanner);

    document.addEventListener('DOMContentLoaded', function() {
    var typeDelivery = document.getElementById('type_delivery');
    var formCabang = document.getElementById('formCabang');
    var cabangTujuan = document.getElementById('cabang_tujuan');
    var sttbScanWrapper = document.getElementById('sttb_scan_wrapper');

    function checkSelections() {
        var typeDeliveryValue = typeDelivery.value;

        if (typeDeliveryValue) {
            sttbScanWrapper.style.display = 'block';
        } else {
            sttbScanWrapper.style.display = 'none';
        }
        
        if (typeDeliveryValue === 'WC' || typeDeliveryValue === '') {
            formCabang.style.display = 'none';
            cabangTujuan.value = ""; 
        } else {
            formCabang.style.display = 'block';
        } 
        
    }

    typeDelivery.addEventListener('change', function() {
        checkSelections();
    });

    checkSelections();
});

       
</script>