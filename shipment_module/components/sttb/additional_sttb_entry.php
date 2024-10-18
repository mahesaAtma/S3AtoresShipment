<style>
    .sttb-entry-choice{
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .sttb-entry-choice button{
        color: white;
        width: 45%;
        padding: 10px 20px;
        border-radius: 15px;
        margin: 0 6px;
        font-weight: 600;
        font-size: 16px;
        transition: 0.3s ease-in-out;
    }

    .sttb-entry-choice button img{
        margin-right: 10px;
    }

    .sttb-entry-input{
        background-color: rgba(218, 218, 218, 0.34);
        padding: 7px 10px;
        display: flex;
        border-radius: 10px;
    }

    .sttb-entry-input input[type="text"]{
        width: 100%;
        padding: 10px 0px 10px 15px;
        border-radius: 10px;
        cursor: not-allowed;
    }

    .sttb-entry-input div{
        border-radius: 10px;
        margin-left: 10px;
        display: block;
        width: 50px;
        background-color: #367BF5;
        position: relative;
        cursor: pointer;
        transition: 0.3s ease-in-out;
    }

    .sttb-entry-input div:hover{
        opacity: 0.8;
    }

    .sttb-entry-input img{
        width: 30px;
        position: absolute;
        left: 60%;
        top: 50%;
        transform: translate(-50%,-50%);
    }

    button.add-delivery-address{
        text-align: center;
        border: 1px solid #2C4074;
        width: 100%;
        padding: 10px 0;
        margin-top: 30px;
        transition: 0.3s ease-in-out;
        font-weight: 700;
        color: #2C4074;
        border-radius: 10px;
        font-size: 17px;
    }

    button.add-delivery-address:hover{
        background-color: rgba(70,87,133,0.1);
    }
    
    .add-service{
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 20px;
        border: 1px solid rgba(144,146,148,0.7);
        border-radius: 10px;
    }

    .add-service i{
        font-size: 20px;
        cursor: pointer;
        transition: 0.3s ease-in-out;
    }

    .add-service i:hover{
        opacity: 0.7;
    }

    .add-service span{
        font-weight: 700;
    }

    .collapse-service{
        background-color: #EBEBEB;
        padding: 20px;
    }
    
    .service-list{
        margin: 10px 0;
        padding: 20px 30px;
        background-color: rgba(196,224,227,0.6);
        border-radius: 10px;
    }

    .service-list p{
        line-height: 7px;
    }

    /* Style for each services component */
    .service-input-container{
        width: 100%;
        position: relative;
    }

    .service-input-container input {
        background-color: #FAFAFA;
        padding: 10px 20px;
        margin: 6px 0;
        width: auto;
    }

    .service-input-container input:disabled{
        cursor: not-allowed;
        background-color: #e0e0e0;
    }

    .service-input-container .vol-matrix-input input{
        width: 50%;
        margin-right: 7px;
    }

    .service-input-container .dimension-input input{
        width: 20%;
        margin-right: 7px;
    }

    .service-input-container .dimension-input span{
        margin-right: 7px;
        font-weight: 600;
    }

    .service-input-container a{
        margin: 10px 10px 10px auto;
    }

    .matrix-dimension-input{
        display: none;
    }
    
    .collapse-parent{
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 20px;
        border: 1px solid rgba(144,146,148,0.7);
        border-radius: 10px;
        margin-top: 10px;
    }

    .collapse-parent i{
        font-size: 20px;
        cursor: pointer;
        transition: 0.3s ease-in-out;
    }

    .collapse-parent i:hover{
        opacity: 0.7;
    }

    .collapse-parent span{
        font-weight: 700;
    }

    .collapse-container{
        background-color: #EBEBEB;
        padding: 20px;
    }

    .collapse-info-container{
        margin: 10px 0;
        padding: 20px 30px;
        background-color: rgba(196,224,227,0.6);
        border-radius: 10px;
    }

    .collapse-info-container p{
        line-height: 7px;
    }
    
    @media only screen and (max-width: 600px) {
        .sttb-entry-choice{
            display: block;
        }

        .sttb-entry-choice button{
            width: 100%;
            padding: 10px 20px;
            margin: 4px 0px;
            font-size: 12px;
        }

        .sttb-entry-choice button img{
            width: 14px;
            height: auto;
            margin-right: 10px;
        }

        .add-delivery-address{
            font-size: 14px;
        }
    }
</style>

<div class="card">
    <div class="card-body">
        <div class="entry-sttb-popup entry-sttb-popup-wrapper-<?= $popupId ?>">
            <h6 style="color:#555555;"><i class="fas fa-warehouse mr-2"></i> STTB & SERVICE</h6> 
            <hr class="line-boundaries">
            <div class="sttb-entry-choice">
                <button class="input-sttb-fisik bg-light-purple-gradient-radiant" data-id="<?= $popupId ?>"><img src="../images/icon/shipment/pencil-alt.png">Input STTB Fisik</button>
                <button class="input-elektronik-sttb bg-light-blue-gradient-radiant" data-id="<?= $popupId ?>"><img src="../images/icon/shipment/bill.png">E-STTB</button>
            </div>
            <hr/>
            <div class="sttb-entry-container">
                <div class="sttb-entry-input">
                    <input id="scanQRCodeInput<?= $popupId ?>" data-sttb-type=""type="text" placeholder="Input No STTB" disabled>
                    <div class="scan-qr-code-shipment" data-id="<?= $popupId ?>">
                        <img src="../images/icon/shipment/scan.png">
                    </div>
                </div>
            </div>
        </div>

        <div class="add-service-popup add-service-popup-wrapper-<?= $popupId ?> mt-4">
            <div class="add-service">
                <span>Pilih Service</span>
                <i class="fa fa-plus-circle"  id="collapseService<?= $popupId ?>" data-toggle="collapse" data-target="#serviceCollapse<?= $popupId ?>" aria-expanded="true" aria-controls="serviceCollapse<?= $popupId ?>"></i>
            </div>

            <div id="serviceCollapse<?= $popupId ?>" class="collapse-service collapse" aria-labelledby="collapseService<?= $popupId ?>" data-parent="#accordionExample">
                <div class="">
                    <?php 
                        $dropdownTitle = "Pilih Service";
                        $dropdownChildrens = ["Reguler","Express","LTL","Primex","FCL","FTL","LCL"];
                        include("./shipment_module/components/service_dropdown.php") 
                    ?>
                </div>
            </div>

            <div class="service-list dnone service-result-wrapper-<?= $popupId ?>">
                <h6>Informasi Shipment</h6>
                <div class="dflex justify-content-sb mt-4">
                    <div class="dflex align-item-s">
                        <i class="fas fa-box mr-3"></i>
                        <div id="serviceResultMainContent<?= $popupId ?>"></div>
                    </div>
                    <i class="btn-trash-service-result-trigger fa fa-trash mt-4 mr-3" data-id="<?= $popupId ?>" style="color:red; cursor:pointer;"></i>
                </div>

                <hr class="line-boundaries"/>
                <div>
                    <p id="tipeServiceResult<?= $popupId ?>"><span>Reguler</span> Service</p>
                    <div class="dflex align-item-c justify-content-sb">
                        <h7 style="font-weight: 500;">Jumlah Koli</h7>
                        <h6 id="jumlahKoliServiceResult<?= $popupId ?>" style="font-weight: 700;"><span>-</span> Koli</h6>
                    </div>
                    <div class="dflex align-item-c justify-content-sb">
                        <h7 style="font-weight: 500;">Charge KG</h7>
                        <h6 id="chargeKGServiceResult<?= $popupId ?>" style="font-weight: 700;"><span>-</span> KG</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="add-document-popup add-document-popup-wrapper-<?= $popupId ?>">
            <div class="collapse-parent">
                <span>Extra Packing</span>
                <i class="fa fa-plus-circle"  id="collapseExtraPacking<?= $popupId ?>" data-toggle="collapse" data-target="#extraPackingCollapse<?= $popupId ?>" aria-expanded="true" aria-controls="extraPackingCollapse<?= $popupId ?>"></i>
            </div>
    
            <div id="extraPackingCollapse<?= $popupId ?>" class="collapse-container collapse" aria-labelledby="collapseExtraPacking<?= $popupId ?>" data-parent="#accordionExample">
                <?php 
                    $modalSwitchItem = 'Packing Standard';
                    include("./shipment_module/components/modal_switch.php"); 
                    include("./shipment_module/components/extra_packing/packing_standart.php");
                    $modalSwitchItem = 'Packing Special';
                    include("./shipment_module/components/modal_switch.php"); 
                    include("./shipment_module/components/extra_packing/packing_special.php");
                ?>
            </div>
    
            <div class="collapse-info-container dnone extra-packing-result-wrapper-<?= $popupId ?>">
                <h6>Informasi Extra Packing</h6>
                <ul class="ml-4">
                    <li>
                        <div class="dflex justify-content-sb mt-2">
                            <div id="extraPackingResultList"></div>
                            <i style="color:red; cursor:pointer;" class="btn-extra-packing-trash-trigger fa fa-trash mt-4 mr-3" data-id="<?= $popupId ?>"></i>
                        </div>
                    </li>
                </ul>
            </div>
    
            <div class="collapse-parent">
                <span>Add Request</span>
                <i class="fa fa-plus-circle"  id="collaseAdditionalRequest<?= $popupId ?>" data-toggle="collapse" data-target="#additionalRequestCollapse<?= $popupId ?>" aria-expanded="true" aria-controls="additionalRequestCollapse<?= $popupId ?>"></i>
            </div>
    
            <div id="additionalRequestCollapse<?= $popupId ?>" class="collapse-container collapse" aria-labelledby="collaseAdditionalRequest<?= $popupId ?>" data-parent="#accordionExample">
                <?php 
                    $requests = ['Document FTZ', 'Document MSDS', 'Surcharge Heavy Cargo', 'Forklift Heavy Cargo', 'Asuransi'];
                    foreach ($requests as $modalSwitchItem) {
                        include("./shipment_module/components/modal_switch.php");
                    }
                ?>
                <button id="<?= $popupId ?>" class="btn-add-request-simpan-trigger ljr-custom-btn bg-dark-blue mt-4">Simpan</button>
            </div>

            <div class="collapse-info-container dnone request-result-wrapper-<?= $popupId ?>">
                <h6>Informasi Add Request</h6>
                <div class="dflex align-item-c justify-content-sb mt-2 ml-4">
                    <div>
                        <ul id="requestResultList<?= $popupId ?>"></ul>
                    </div>
                    <i id="<?= $popupId ?>" class="btn-add-request-trash-trigger fa fa-trash mr-3" style="color:red; cursor:pointer;"></i>
                </div>
            </div>
        
            <div class="collapse-parent">
                <span>Lampirkan Dokumen</span>
                <i class="fa fa-plus-circle"  id="lampirkanDokumenCollapse<?= $popupId ?>" data-toggle="collapse" data-target="#collapseLampirkanDokumen<?= $popupId ?>" aria-expanded="true" aria-controls="collapseLampirkanDokumen<?= $popupId ?>"></i>
            </div>
    
            <div id="collapseLampirkanDokumen<?= $popupId ?>" class="collapse-container collapse lampirkan-dokumen-collapse-<?= $popupId ?>" aria-labelledby="lampirkanDokumenCollapse<?= $popupId ?>" data-parent="#accordionExample">
                <?php
                    $documentsRecord = $shipmentObj->getShipmentDocumentsByCustomer($pickupData['customer_id']);
                    if ($documentsRecord) {
                        $documents = [];
                        foreach ($documentsRecord as $record) {
                            $documents[] = [
                                'id' => $record['document_id'],
                                'name' => $record['nama_dokumen'],
                                'nomor' => $record['no_dokumen'],
                                'wajib' => $record['jenis'] == 'WAJIB' ? 1 : 0
                            ];
                        }
                        include("./shipment_module/components/modal_checkbox.php"); 
                    }else{
                        echo "<p style='color:red;'>No document available</p>";
                    }
                ?>

                <button id="<?= $popupId ?>" class="btn-add-document-simpan-trigger ljr-custom-btn bg-dark-blue mt-4">Simpan</button>
            </div>
    
            <div class="collapse-info-container dnone document-result-wrapper-<?= $popupId ?>">
                <h6>Informasi Shipment</h6>
                <div class="dflex align-item-c justify-content-sb mt-2 ml-4">
                    <div>
                        <ul id="documentResultList<?= $popupId ?>"></ul>
                    </div>
                    <i id="<?= $popupId ?>" class="btn-add-document-trash-trigger fa fa-trash mr-3" style="color:red; cursor:pointer;"></i>
                </div>
            </div>
    
            <?php 
                $modalSwitchItem = 'Prioritas pengiriman (shipment informasi)';
                include("./shipment_module/components/modal_switch_calendar.php");
                $modalSwitchItem = 'Apakah ada barang susulan?';
                include("./shipment_module/components/modal_switch.php");
            ?>
        </div>

        <button id="<?= $popupId ?>" class="btn-additional-sttb-trigger ljr-custom-btn bg-dark-blue mt-4" style="border-radius: 10px;">Simpan Shipment Entry</button>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function(){
        // Service Section
        volMatrixData = {};
        indexVolMatrix = {};

        serviceData = {};
        isServiceFilled = {};

        saveServiceComponent(serviceData, isServiceFilled, volMatrixData);
        saveVolMatrix(volMatrixData, indexVolMatrix);
        appendVolMatrixComponent(volMatrixData, indexVolMatrix);
        removeServiceResultComponent(serviceData, isServiceFilled);
        calculateBeratAsliVolMatrix();

        $('.scan-qr-code-shipment').off('click').click(function(){
            qrCodeScanID = $(this).attr('data-id');
            inputToInsert = '#scanQRCodeInput' + qrCodeScanID;
            $('.sttb-entry-container input#scanQRCodeInput' + qrCodeScanID).val('');
            
            $('.sttb-entry-container input#scanQRCodeInput' + qrCodeScanID).attr('data-sttb-type', 'input');
            $('.sttb-entry-container input#scanQRCodeInput' + qrCodeScanID).prop('disabled', true);
            $('.sttb-entry-container input#scanQRCodeInput' + qrCodeScanID).css('cursor', 'not-allowed');
            
            $('.qr-code-scanner-black-cover').addClass('dblock');
            if (window.shipmentScanQRCode) {
                shipmentScanQRCode(inputToInsert);
            }
        });

        $('.entry-sttb-popup button.input-sttb-fisik').off('click').click(function() {
            sttbInputFisikID = $(this).attr('data-id');
            $('.sttb-entry-container input#scanQRCodeInput' + sttbInputFisikID).val('');
            $('.sttb-entry-container input#scanQRCodeInput' + sttbInputFisikID).attr('data-sttb-type', 'FISIK');
            $('.sttb-entry-container input#scanQRCodeInput' + sttbInputFisikID).prop('disabled', false);
            $('.sttb-entry-container input#scanQRCodeInput' + sttbInputFisikID).css('cursor', 'auto');
        });

        $('.entry-sttb-popup button.input-elektronik-sttb').off('click').click(function() {
            sttbInputElID = $(this).attr('data-id');
            $('.sttb-entry-container input#scanQRCodeInput' + sttbInputElID).val('');
            $('.sttb-entry-container input#scanQRCodeInput' + sttbInputElID).attr('data-sttb-type', 'e-STTB');
            $('.sttb-entry-container input#scanQRCodeInput' + sttbInputElID).prop('disabled', true);
            $('.sttb-entry-container input#scanQRCodeInput' + sttbInputElID).css('cursor', 'not-allowed');

            $.ajax({
                url: "shipment-master/validation/new_no_sttb_fisik.php",
                method: "POST",
                data: {},
                success: function(response){
                    response = JSON.parse(response);
                    
                    if (response.success) {
                        $('input#scanQRCodeInput' + sttbInputElID).val(response.new_no_sttb);
                    }else{
                        popupSwalFireError(response.messages);
                    }
                }
            });
        });

        // Extra Packing Section 
        packingData = {};
        isExtraPackingFilled = {};

        isPackingStandardChecked = {};
        isPackingSpecialChecked = {};

        indexDimension = {};

        switchActionPackingStandard(isPackingStandardChecked);
        switchActionPackingSpecial(isPackingSpecialChecked);
        savePackingStandard(packingData, isExtraPackingFilled, isPackingStandardChecked);
        savePackingSpecial(packingData, isExtraPackingFilled, isPackingSpecialChecked);
        removeExtraPackingComponentResult(packingData, isExtraPackingFilled);
        appendPackingComponent(packingData, indexDimension);
        calculatePacking('standard');
        calculatePacking('special');

        // Add Request Section
        isRequestFilled = {};
        requestData = {};

        saveRequestChoice(requestData, isRequestFilled);
        removeRequestResultComponent(requestData, isRequestFilled);

        // Document Section
        isDocumentFilled = {};
        documentData = {};

        saveDocumentChoice(documentData, isDocumentFilled);
        removeDocumentResultComponent(documentData, isDocumentFilled);

        $('.btn-additional-sttb-trigger').click(function() {
            simpanShipmentID = $(this).attr('id');
            noSTTB = $('input#scanQRCodeInput' + simpanShipmentID).val()

            errorMessages = [];

            if (noSTTB == '') {
                errorMessages.push('Mohon isi data sttb terlebih dahulu');
            }
            if (Object.keys(serviceData).length == 0) {
                errorMessages.push('Mohon isi data service terlebih dahulu');
            }
            if (Object.keys(packingData).length == 0) {
                errorMessages.push('Mohon isi data service terlebih dahulu');
            }
            if (Object.keys(requestData).length == 0) {
                errorMessages.push('Mohon isi data request terlebih dahulu');
            }
            if (Object.keys(documentData).length == 0) {
                errorMessages.push('Mohon isi data dokumen terlebih dahulu');
            }

            if (errorMessages.length > 0) {
                popupSwalFireError(errorMessages);
            }

            dataEntry = {
                service_data: serviceData[simpanShipmentID],
                document_data: documentData[simpanShipmentID],
                request_data: requestData[simpanShipmentID],
                packing_data: packingData[simpanShipmentID],
                no_shipment_entry: $('input#noShipmentEntryCode').val(),
                no_sttb: noSTTB,
                sttb_input_type: $('.sttb-entry-container input#scanQRCodeInput' + simpanShipmentID).attr('data-sttb-type'),
                customer_id: $('input#customerID' + simpanShipmentID).val(),
                prioritas_pengiriman: $('input#prioritaspengirimanshipmentinformasiInput_' + simpanShipmentID).prop('checked') ? 'Y' : 'N',
                tgl_prioritas_pengiriman: $('input#modalSwitchCalendar' + simpanShipmentID).val(),
                barang_susulan: $('input#apakahadabarangsusulanInput_' + simpanShipmentID).prop('checked') ? 'Y' : 'N',
            };

            console.log(dataEntry);
            
            Swal.fire({
                title: "Simpan Shipment Entry?",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: "Simpan",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "shipment-master/validation/additional_sttb.php",
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
        });
    });
</script>
