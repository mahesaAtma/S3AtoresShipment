<style>
    .card-icons-container i{
        margin-right: 20px;
        cursor: pointer;
    }

    .shipment-checkbox-container{
        margin: 10px 0;
        padding: 10px 20px 10px 15px;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.3s ease-in-out;
        display: flex;
        justify-content: space-between;
    }

    .shipment-checkbox-container:hover{
        background-color: rgba(144,146,148,0.1);
    }

    .shipment-checkbox-container img{
        height: auto;
        width: 55px;
        border: 1px solid rgba(144,146,148,0.4);
        border-radius: 10px;
    }

    .shipment-checkbox-container .img-padding{
        padding: 3px 14px;
    }

    .shipment-bottom-section{
        background-color: rgba(230, 237, 252,0.8);
        padding: 20px;
        border-radius: 15px 15px 0px 0px;
    }

    .detail-sttb-container i{
        font-size: 20px;
        transition: 0.3s ease-in-out;
    }
    
    .credit-tag, .priority-tag{
        height: auto;
        color: red;
        border-radius: 20px;
        padding: 10px 20px;
        box-shadow: 4px 6px 6px -1px rgba(0,0,0,0.4);
        -webkit-box-shadow: 4px 6px 6px -1px rgba(0,0,0,0.4);
        -moz-box-shadow: 4px 6px 6px -1px rgba(0,0,0,0.4);
    }

    .sign-black-cover{
        position: absolute;
        transform: translate(-50%, -50%);
        top: 50%;
        left: 50%;
        min-height: 70vh;
        background-color: rgba(0, 0, 0, 0.7);
        padding: 30px 5px;
        width: 100%;
        height: 100%;
        transition: 0.3s ease-in-out;
        z-index: -1;
        opacity: 0;
    }

    .sign-container{
        margin: 0 auto;
        width: 90%;
        height: 95%;
        display: flex;
        flex-direction: column;
        text-align: center;
        justify-content: center;
        background-color: #FFFFFF;
        padding: 20px 15px;
        border-radius: 0.5rem;
        position: relative;
    }

    .sign-black-cover img{
        margin: 10px auto;
        width: 30px;
        height: auto;
    }

    .sign-black-cover h5{
        font-weight: 700;
        margin: 10px 0;
    }

    .sign-black-cover i{
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 25px;
        cursor: pointer;
    }

    .signature-canvas {
        width: 100%;
        height: 100%;
        background-color: #C4C4C4;
    }

    .save-button {
        background-color: #292163;
        color: white;
        margin-top: 10px;
        padding: 10px;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
        width: 100%;
        transition: 0.3s ease-in-out;
    }

    .save-button:hover{
        background-color: #3F3492;
    }

    .sign-black-cover-popup{
        z-index: 2;
        opacity: 1;
    }

    @media only screen and (max-width: 600px) {
        .card-icons-container i{
            margin-right: 5px;
            font-size: 16px;
        }

        .card-headers-container{
            font-size: 14px;
        }
    
        .credit-tag, .priority-tag{
            padding: 10px 20px;
            font-size: 12px;
        }
    }
</style>

<?php
    $popupId = '00';
    $pickupData = [];
    $totalSTTB = 0;
?>

<div class="main-content">
    <div class="section__content section__content--p0">
        <div class="container-fluid detail-sttb-container" style="min-height:70vh;">
            <?php
                $noShipmentEntry = isset($_GET['no_shipment_entry']) ? $_GET['no_shipment_entry'] : 'just-a-text-for-shipment-return-null';
                $shipmentOrderPickups = $shipmentObj->getShipmentPickupOrder($noShipmentEntry);
                if ($shipmentOrderPickups) {
                    $totalSTTB = count($shipmentOrderPickups)
            ?>
                <h5 class="mb-3">Detail STTB (<?= $noShipmentEntry ?>)</h5>
                <div style="max-height: 50vh; overflow-y: scroll;">
                    <?php
                        foreach ($shipmentOrderPickups as $index => $record) {
                            $pickupData = [
                                'no_order_pickup' => $record['no_order_pickup'],
                                'task_no' => $record['task_no'],
                                'custsentreceipt_id' => $record['custsentreceipt_id'],
                                'customer_id' => $record['customer_id'],
                                'task_id' => $record['task_id'],
                                'order_pickup_detail_id' => $record['order_pickup_detail_id'],
                                'no_shipment_entry' => $record['no_shipment_entry'],
                                'shipment_detail_id' => $record['shipment_detail_id'],
                                'sign_cust' => $record['sign_cust']
                            ];

                            $shipmentDetail = $shipmentObj->getShipmentDetail($record['shipment_detail_id']);
                            $serviceName = ucwords(strtolower($shipmentDetail->nama_service_product));
                    ?>
                            <div class="card">
                                <input id="shipmentDetailId<?= $index ?>" type="hidden" value="<?= $pickupData['shipment_detail_id'] ?>">

                                <div class="card-header bg-blue-tosca" id="detailSTTBCollapse<?= $index ?>" style="padding: auto 20px;">
                                    <div class="dflex align-item-c justify-content-sb">
                                        <div class="card-headers-container dflex align-item-c mt-2">
                                            <h6 class="mr-4"><?= $index + 1 ?>.</h6>
                                            <div style="line-height: 10px;">
                                                <h6><?= $record['no_sttb'] ?> <?= $shipmentDetail->jml_koli ?> Koli</h6>
                                                <p><?= $serviceName ?></p>
                                            </div>
                                        </div>
                                        <div class="card-icons-container">
                                            <a href="index.php?page=shipment_edit_sttb&shipment_detail_id=<?= $record['shipment_detail_id'] ?>"><i id="editSTTBIcon" class="fa fa-plus-square" style="color:#2C4074;"></i></a>
                                            <a href="index.php?page=shipment_print_sttb&shipment_detail_id=<?= $record['shipment_detail_id'] ?>"><i id="printSTTBIcon" class="fa fa-print" style="color:#2C4074;"></i></a>
                                            <i id="deleteSTTBIcon" class="shipment-detail-trash-trigger fa fa-trash" data-id="<?= $index ?>" style="color:red;"></i>
                                            <i class="fa fa-arrow-circle-down" style="color:#2C4074;" type="button" data-toggle="collapse" data-target="#collapseDetailSTTB<?= $index ?>" aria-expanded="true" aria-controls="collapseDetailSTTB<?= $index ?>"></i>
                                        </div>
                                    </div>
                                </div>
                
                                <div id="collapseDetailSTTB<?= $index ?>" class="collapse" aria-labelledby="detailSTTBCollapse<?= $index ?>" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <h6 class="opacity-50">Penerima</h6>
                                        <h6><?= $record['nama_pic_penerima'] ?> - <?= $record['tlp_pic_penerima'] ?></h6>
                                        <h6 class="opacity-50"><?= $record['alamat'] ?></h6>
                                        <hr class="line-boundaries"/>

                                        <div class="dflex align-item-s justify-content-sb">
                                            <div>
                                                <h6>Informasi Barang</h6>
                                                <div class="dflex">
                                                    <div style="padding-right: 20px;">
                                                        <i class="fas fa-box"></i>
                                                    </div>
                                                    <div style="line-height: 10px;">
                                                        <p>Service <?= $serviceName ?></p>
                                                        <p><?= $shipmentDetail->berat_asli ?>KG</p>
                                                        <p><?= $shipmentDetail->nama_barang ?></p>
                                                    </div>
                                                </div>
                                            </div>

                                            <span class="credit-tag mr-3 mt-1"><?= strtoupper($record['nama_cara_bayar']) ?></span>
                                        </div>
                                        <hr class="line-boundaries"/>
                                        <div class="dflex justify-content-sb">
                                            <div style="line-height: 15px;">
                                                <h6>Informasi Lainnya</h6>
                                                <p>Features : </p>
                                                <ol class="ml-4" style="line-height: 25px;">
                                                    <?php
                                                        $features = $shipmentObj->getShipmentFeatures($record['no_shipment_entry'] ,$record['no_sttb']);
                                                        if (!$features) {
                                                    ?>
                                                        <li>-</li>
                                                    <?php

                                                        }else{
                                                            foreach ($features as $feature) {
                                                    ?>
                                                            <li><?= $feature['nama_features'] ?></li>
                                                    <?php
                                                            }
                                                        }
                                                    ?>
                                                </ol>
                                                <p>Dokumen pendukung : </p>
                                                <ol class="ml-4" style="line-height: 25px;">
                                                    <?php
                                                        $docs = $shipmentObj->getShipmentDocuments($record['no_shipment_entry'] ,$record['no_sttb']);
                                                        if (!$docs) {
                                                    ?>
                                                        <li>-</li>
                                                    <?php

                                                        }else{
                                                            foreach ($docs as $doc) {
                                                    ?>
                                                            <li><?= $doc['nama_dokumen'] ?> - <?= $doc['no_dokumen'] ?></li>
                                                    <?php
                                                            }
                                                        }
                                                    ?>
                                                </ol>
                                                <p>Extra Packing : </p>
                                                    <?php
                                                        $packings = $shipmentObj->getShipmentExtraPacking($record['no_shipment_entry'] ,$record['no_sttb']);
                                                        if (!$packings) {
                                                    ?>
                                                        <li>-</li>
                                                    <?php

                                                        }else{
                                                            foreach ($packings as $packing) {
                                                    ?>
                                                            <li> <?= $packing['nama_packing'] ?>
                                                                <p class="ml-3 mt-3">dimensi : <?= $packing['dimensi_panjang'] ?>(cm)x<?= $packing['dimensi_lebar'] ?>(cm)x<?= $packing['dimensi_tinggi'] ?>(cm)</p>
                                                                <p class="ml-3">harga : <?= $packing['biaya'] ?></p>
                                                                <p class="ml-3 mb-3">berat : <?= $packing['berat_total'] ?></p>
                                                            </li>
                                                    <?php
                                                            }
                                                        }
                                                    ?>
                                                </ol>
                                            </div>
                                            <div>
                                                <?php
                                                    if ($shipmentDetail->prioritas == 'Y') {
                                                ?>
                                                        <div class="priority-tag mr-3 mt-1">
                                                            <i class="fas fa-check"></i>
                                                            <span> PRIORITAS</span>
                                                        </div>
                                                <?php
                                                    }
                                                ?>

                                                <?php
                                                    if ($shipmentDetail->susulan == 'Y') {
                                                ?>
                                                        <div class="priority-tag mr-3 mt-1">
                                                            <i class="fas fa-check"></i>
                                                            <span> SUSULAN</span>
                                                        </div>
                                                <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    ?>
                </div>
                
                <input id="customerID<?= $popupId ?>" type="hidden" value="<?= $pickupData['customer_id'] ?>">
                <input id="orderPickupDetailID<?= $popupId ?>" type="hidden" value="<?= $pickupData['order_pickup_detail_id'] ?>">
                <input id="trxTaskID<?= $popupId ?>" type="hidden" value="<?= $pickupData['task_id'] ?>">
                <input id="noShipmentEntryCode" type="hidden" value="<?= $pickupData['no_shipment_entry'] ?>">
                
                <div class="shipment-bottom-section mt-5">
                    <h6 style="font-weight: 700;" class="ml-3">Total STTB yang sudah terinput : <span style="color:#EFB530;"><?= $totalSTTB ?> STTB</span></h6>
                    <?php
                        if (is_null($pickupData['sign_cust'])) {
                    ?>
                        <button style="border-radius: 10px;" class="btn-show-sttb-popup ljr-custom-btn bg-light-blue-gradient-radiant mt-3">Tambah STTB</button>
                        <div class="dflex align-item-c justify-content-c mt-2">
                            <button style="border-radius: 10px;" class="cancel-sttb-btn ljr-custom-btn bg-dark-red mr-3">Batalkan Transaksi</button>
                            <button style="border-radius: 10px;" class="approve-sttb-btn ljr-custom-btn bg-dark-blue">Setuju</button>
                        </div>
                    <?php
                        }
                    ?>
                </div>

                <?php include('./shipment_module/components/qr_code_scanner.php'); ?>
                
                <div class="sttb-popup-container">
                    <?php include('./shipment_module/sttb/additional_sttb_popup.php'); ?>
                </div>

                <div class="sttb-popup-address-picker-<?= $popupId ?>">
                    <?php include('./shipment_module/components/new_address_picker.php'); ?>
                </div>

                <!-- <div class="sttb-address-entry-container-<?= $popupId ?>">
                    <?php // include('./shipment_module/components/address_entry_popup.php'); ?>
                </div> -->

                <div class="sign-black-cover">
                    <div class="sign-container">
                        <i id="closeSignPopup" class="fas fa-remove"></i>
                        <img src="../../images/icon/shipment/sign_lock.png">
                        <h5>DIGITAL SIGNATURE</h5>
                        <canvas id="signatureCanvas" class="signature-canvas"></canvas>
                        <button id="saveSignButton" class="save-button">Submit</button>
                    </div>
                </div>
            <?php
                }else{
                    include('./shipment_module/components/go_back_page.php');
                }
            ?>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    const signatureImage = null

    $(document).ready(function() {
        $('.fa-arrow-circle-down').off('click').click(function() {
            $(this).toggleClass('rotate-180-deg');
        });

        const canvas = document.querySelector('canvas');
        const signaturePad = new SignaturePad(canvas);

        function resizeCanvas() {
            const ratio =  Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear(); // otherwise isEmpty() might return incorrect value
        }

        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        $('#saveSignButton').click(function() {
            const shipmentCode = $('input#noShipmentEntryCode').val();

            // Check if the signature pad is empty and display an error message if it is.
            if (signaturePad.isEmpty()) {
                popupSwalFireError(['Mohon mengisi tanda tangan terlebih dahulu!']);
            }else{
                // Get the signature image as a Base64-encoded data URL.
                const signatureImage = signaturePad.toDataURL();
                const encodedString = signatureImage.replace('data:image/png;base64,', '');  
                
                dataEntry = {
                    no_shipment_entry: shipmentCode,
                    sign_image_encoded: encodedString
                };

                Swal.fire({
                    title: "Simpan Shipment Entry?",
                    showDenyButton: false,
                    showCancelButton: true,
                    confirmButtonText: "Simpan",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "shipment-master/validation/save_sign_cust.php",
                            method: "POST",
                            data: dataEntry,
                            success: function(response){
                                response = JSON.parse(response);
                                
                                if (response.success) {
                                    popupSwalFireSuccess(response.messages);
                                }else{
                                    popupSwalFireError(response.messages);
                                }
                            }
                        });
                    }
                });
            }
        });

        $('#editSTTBIcon').off('click').click(function() {
            window.location = 'index.php?page=shipment_edit_sttb&shipment_detail_id=' + $('input#shipmentDetailId').val()
        });

        $('.shipment-detail-trash-trigger').off('click').click(function() {
            trashID = $(this).attr('data-id');
            shipmentDetailID = $('input#shipmentDetailId' + trashID).val()
            
            Swal.fire({
                    title: "Yakin Hapus?",
                    showDenyButton: true,
                    confirmButtonText: "Ya",
                    denyButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "shipment-master/validation/remove_a_sttb.php",
                            method: "POST",
                            data: {shipment_detail_id: shipmentDetailID},
                            success: function(response){
                                response = JSON.parse(response);
                                
                                if (response.success) {
                                    popupSwalFireSuccess(response.messages);
                                }else{
                                    popupSwalFireError(response.messages);
                                }
                            }
                        });
                    }
                });
        });

        $('.cancel-sttb-btn').click(function() {
            noShipmentEntry = $('input#noShipmentEntryCode').val()

            Swal.fire({
                    title: "Yakin Batalkan?",
                    showDenyButton: true,
                    confirmButtonText: "Ya",
                    denyButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "shipment-master/validation/cancel_sttb.php",
                            method: "POST",
                            data: {no_shipment_entry: noShipmentEntry},
                            success: function(response){
                                response = JSON.parse(response);
                                
                                if (response.success) {
                                    popupSwalFireSuccess(response.messages);
                                }else{
                                    popupSwalFireError(response.messages);
                                }
                            }
                        });
                    }
                });
        });

        $('.approve-sttb-btn').click(function() {
            $('.sign-black-cover').addClass('sign-black-cover-popup');
        });

        $('#closeSignPopup').click(function(){
            $('.sign-black-cover').removeClass('sign-black-cover-popup');
            if (signaturePad !== null) {
                signaturePad.clear();
            }
        });

        // Btn for sttb popup to emerge
        $('.btn-show-sttb-popup').click(function() {
            $('.sttb-popup-container').find('.sttb-popup-black-cover').addClass('sttb-popup-show');
        });

        $('.btn-remove-sttb-popup').click(function() {
            $('.sttb-popup-container').find('.sttb-popup-black-cover').removeClass('sttb-popup-show');
        });
    });
</script>