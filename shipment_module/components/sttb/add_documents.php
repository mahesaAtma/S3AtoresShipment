<style>
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

    .add-document-popup{
        display: none;
    }

    @media only screen and (max-width: 600px) {
        div#extraPackingResultList p{
            font-size: 13px;
        }
    }
</style>

<div class="add-document-popup add-document-popup-wrapper-<?= $popupId ?>">
    <h6 style="color:#555555;"><i class="fas fa-newspaper mr-2"></i> REQUEST</h6> 
    <hr class="line-boundaries">
    <div class="card">
        <div class="card-body">
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
    
            <div class="document-remark">
                <h6 class="ml-2 mt-4">Remark</h6>
                <input style="width:100%; background-color:rgba(218,218,218,0.4); padding:15px; border-radius:10px;" type="text" placeholder="Deksripsi...">
            </div>
    
            <button id="<?= $popupId ?>" class="btn-add-document-next-trigger ljr-custom-btn bg-dark-blue mt-4" style="border-radius: 10px;">Simpan Shipment Entry</button>
            <button id="<?= $popupId ?>" class="btn-add-document-previous-trigger ljr-custom-btn bg-dark-red mt-4" style="border-radius: 10px;">Sebelumnya</button>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function(){
        $('.btn-add-document-previous-trigger').off('click').on('click', function(){
            documentPreviousIndex = $(this).attr('id');
            $('.add-service-popup-wrapper-' + documentPreviousIndex).css("display","inline-block");
            $('.add-document-popup-wrapper-' + documentPreviousIndex).css("display","none");
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

        // Save Shipment Entry
        $('.btn-add-document-next-trigger').off('click').click(function(){
            simpanShipmentID = $(this).attr('id');

            errorMessages = [];

            if (paymentData[simpanShipmentID] == undefined) {
                errorMessages.push('Mohon isi data payment terlebih dahulu');
            }
            if (Object.keys(serviceData).length == 0) {
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
                payment_data: paymentData[simpanShipmentID],
                packing_data: packingData[simpanShipmentID],
                request_data: requestData[simpanShipmentID],
                document_data: documentData[simpanShipmentID],
                service_data: serviceData[simpanShipmentID],
                customer_id: $('input#customerID' + simpanShipmentID).val(),
                order_pickup_detail_id: $('input#orderPickupDetailID' + simpanShipmentID).val(),
                trx_task_id: $('input#trxTaskID' + simpanShipmentID).val(),
                no_sttb: $('.sttb-entry-container input#scanQRCodeInput' + simpanShipmentID).val(),
                sttb_input_type: $('.sttb-entry-container input#scanQRCodeInput' + simpanShipmentID).attr('data-sttb-type'),
                prioritas_pengiriman: $('input#prioritaspengirimanshipmentinformasiInput_' + simpanShipmentID).prop('checked') ? 'Y' : 'N',
                tgl_prioritas_pengiriman: $('input#modalSwitchCalendar' + simpanShipmentID).val(),
                barang_susulan: $('input#apakahadabarangsusulanInput_' + simpanShipmentID).prop('checked') ? 'Y' : 'N',
            };

            Swal.fire({
                title: "Simpan Shipment Entry?",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: "Simpan",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "shipment-master/validation/save_shipment.php",
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
