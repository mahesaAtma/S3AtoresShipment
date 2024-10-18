<style>
    .dropdown-pickup-container{
        position: relative;
        font-weight: 600;
    }

    .dropdown-pickup-button{
        width: 100%;
        padding: 5px 25px;
        border: solid 1px #909294;
        border-radius: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
    }

    .dropdown-pickup-button:focus{
        outline: none;
    }

    .dropdown-pickup-button i{
        margin-left: 6px;
    }

    .dropdown-pickup-item{
        border: 1px solid #909294;
        border-radius: 10px;
        width: 250px;
        position: absolute;
        background-color: white;
        z-index: 1;
        display: none;
    }

    .dropdown-pickup-item button{
        display: block;
        padding: 7px 7px 7px 25px;
        color: black;
        transition: 0.3s ease-in-out;
        outline: none;
        width: 100%;
        text-align: left;
    }

    .dropdown-pickup-item button:hover{
        background-color: rgb(232, 232, 232);
    }
    
    @media only screen and (max-width: 600px) {
        .dropdown-pickup-button{
            font-size: 12px;
            padding: 5px 10px;
        }

        .dropdown-pickup-button i{
            margin-left: 2px;
        }

        .dropdown-pickup-item{
            width: 150px;
        }

        .dropdown-pickup-item button{
            font-size: 12px;
        }

        .btn-after-dropdown{
            font-size: 12px;
            display: flex;
            margin: 0 3px;
        }

        .pick-order-item-records p{
            font-size: 12px;
        }
    }
</style>

<div class="main-content">
    <div class="section__content section__content--p0 overflow-y-hidden">
        <div class="container-fluid" style="min-height:80vh; max-height:80vh; overflow-y:scroll;">
            <h5 class="mb-3">Pickup List</h5>
           <?php
                $statuses = ['115','116'];

                $totalPagination = $shipmentObj->countRequestPickup($statuses)[0]['count'];
                $linkParent = "index.php?page=pickup_list";
                include('./shipment_module/components/pagination.php');

                $pickupListData = []; 
                $listPickups = $shipmentObj->listRequestPickup($statuses, $currentPage);

                foreach ($listPickups as $parentKey => $item) {
                    $collapseFlag = 'collapseFlagProduct' . $parentKey;
                    $collapseChildFlag = 'collapseChildFlagProduct' . $parentKey;
            ?>
                <div class="card">
                    <div class="card-header onHover bg-blue-tosca" id="<?= $collapseFlag ?>" style="padding: auto 20px;">
                        <div class="dflex align-item-c justify-content-sb" type="button" data-toggle="collapse" data-target="#<?= $collapseChildFlag ?>" aria-expanded="true" aria-controls="<?= $collapseChildFlag ?>">
                            <h6> <?php echo $item['no_order_pickup']; ?></h6>
                            <?php
                                if ($item['status_task'] == '115') {
                                    echo "<h6 class='shipment-list-status shipment-waiting-status'> " . $item['nama_status'] . "</h6>";
                                }else if ($item['status_task'] == '116') {
                                    echo "<h6 class='shipment-list-status shipment-on-progress-status'> " . $item['nama_status'] . "</h6>";
                                }
                            ?>
                        </div>
                    </div>

                    <div id="<?= $collapseChildFlag ?>" class="collapse" aria-labelledby="<?= $collapseFlag ?>" data-parent="#accordionExample">
                        <?php 
                            $detailPickups = $shipmentObj->detailRequestPickup($item['no_order_pickup']);
                            foreach ($detailPickups as $index => $detailItem) {
                                $collapseDocumentPickup = "collapseDocumentPickup" . $parentKey . $index;
                                $collapseChildDocumentPickup = 'collapseChildDocumentPickup' . $parentKey . $index;

                                $pickupListData[$parentKey] = [
                                    'customer_id' => $item['customer_id'],
                                    'customer_name' => $item['customer_name'],
                                    'no_order_pickup' => $item['no_order_pickup'],
                                    'task_no' => $detailItem['task_no'],
                                    'custsentreceipt_id' => $detailItem['custsentreceipt_id'],
                                    'popupId' => (string) $parentKey . (string) $index,
                                    'trx_order_pickup_id' => $item['id']
                                ];
                        ?>
                    
                            <div class="card-body pick-order-item-records">
                                <input id="customerID<?= $parentKey ?><?= $index ?>" type="hidden" value="<?= $item['customer_id'] ?>">
                                <input id="customerName<?= $parentKey ?><?= $index ?>" type="hidden" value="<?= $item['customer_name'] ?>">
                                <input id="orderPickupID<?= $parentKey ?><?= $index ?>" type="hidden" value="<?= $item['id'] ?>">
                                <input id="orderPickupDetailID<?= $parentKey ?><?= $index ?>" type="hidden" value="<?= $detailItem['id'] ?>">
                                <input id="trxTaskID<?= $parentKey ?><?= $index ?>" type="hidden" value="<?= $detailItem['task_id'] ?>">
                                <input id="noShipmentEntry<?= $parentKey ?><?= $index ?>" type="hidden" value="<?= is_null($item['no_shipment_entry']) ? 0 : $item['no_shipment_entry'] ?>">

                                <div class="dflex align-item-c justify-content-sb">
                                    <h6><span class="opacity-50">Alamat <?php echo $index + 1 ?> </span> <span class="express-tag">Express</span></h6>
                                    <p><span class="opacity-50">Tgl Pickup :</span> <?= $detailItem['tgl_pickup'] ?>, <?= $detailItem['jam_pickup'] ?></p>
                                </div>
                        
                                <div style="line-height: 11px;">
                                    <p><?= $detailItem['lokasi_pickup'] ?></p>
                                    <p style="font-size: 14px;"><?= $item['customer_name'] ?> (<?= is_null($item['customer_phone']) ? '-' : $item['customer_phone'] ?>)</p>
                                </div>
                                <p class="opacity-50">Detail Informasi</p>
                                <div class="dflex">
                                    <div style="padding-right: 20px;">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div style="line-height: 10px;">
                                        <p><?= $detailItem['nama_barang'] ?></p>
                                        <p><?= is_null($detailItem['berat']) ? '0' : $detailItem['berat'] ?>KG</p>
                                        <p><?= $detailItem['keterangan'] ?></p>
                                        <div class="mt-3">
                                            <div id="<?= $collapseDocumentPickup ?>">
                                                <a href="#" data-toggle="collapse" data-target="#<?= $collapseChildDocumentPickup ?>" aria-expanded="true" aria-controls="<?= $collapseChildDocumentPickup ?>">
                                                    View Dokumen
                                                </a href="#">
                                            </div>
                                            <div id="<?= $collapseChildDocumentPickup ?>" class="collapse" aria-labelledby="<?= $collapseDocumentPickup ?>" data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <ol class="ml-4" style="line-height: normal;">
                                                        <?php 
                                                            $documents = $shipmentObj->getRequestPickupDocument($item['no_order_pickup']);
                                                            if ($documents) {
                                                                foreach ($documents as $document) {
                                                                    echo "<li><a href='#'> " . $document['nama_document'] . " </a></li>";
                                                                }
                                                            }else{
                                                                echo "<a style='color:red;'> No Document Added</a>";
                                                            }
                                                        ?>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        
                                <?php if($item['status_task'] == '116') {?>
                                    <div class="mt-4 dflex align-item-c justify-content-sb">
                                        <div class="dropdown-pickup-container">
                                            <button class="dropdown-pickup-button" id="dropdownPickupBtn_<?= $parentKey ?><?= $index ?>" >
                                                <span><?= $detailItem['nama_status_driver'] ?></span> <i class="fas fa-angle-down ml-2"></i>
                                            </button>
                        
                                            <div class="dropdown-pickup-item" data-id="<?= $parentKey ?><?= $index ?>" id="dropdownPickupItem<?= $parentKey ?><?= $index ?>">
                                                <?php
                                                    $allowedStatus = $shipmentObj->getRequestPickupDetailStatus();
                                                    foreach ($allowedStatus as $status) {
                                                        echo "<button id='pickBtnStatus" . $parentKey . $index . "' data-id='" . $parentKey . $index . "'  data-status-id='" . $status['id'] . "'>" . $status['nama_status'] . "</button>";
                                                    }
                                                ?>
                                            </div>
                                        </div>
                        
                                        <div class="btn-after-dropdown">
                                            <button class="btn-konfirmasi-change-status" data-id="<?= $parentKey . $index ?>" style="background-color:#0066FF; color:white; border-radius:12px; padding:7px 25px;">Konfirmasi</button>
                                            <?php
                                                if ($item['driver_status_task'] == '133') {
                                            ?>
                                                <button class="btn-show-sttb-popup" id="showSTTBPopup_<?= $parentKey . $index ?>" style="background-color:rgba(102,21,104,0.65); color:white; border-radius:12px; padding:7px 25px;">Buat STTB</button>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                            <?= ($index != count($detailPickups) - 1) ? "<hr class='line-boundaries'/>" : "";?>
                            
                        <?php
                            }
                        ?>
                    </div>

                    <?php if ($item['status_task'] == '115') { ?>
                        <hr class="line-boundaries"/>
                        <div class="card-body">
                            <h6 class="opacity-50">Remark</h6>
                            <h6>Deskripsi remark dari bagian OPS untuk driver & krani</h6>
                            <div class="dflex item-align-c justify-content-c mt-3">
                                <button data-id="<?= $parentKey . $index ?>" class="btn-reject-pickup-trigger ljr-custom-btn bg-dark-red" style="border-radius: 10px;">Tolak</button>
                                <button data-id="<?= $parentKey . $index ?>" class="btn-accept-pickup-trigger ljr-custom-btn bg-dark-blue ml-2" style="border-radius: 10px;">Terima</button>
                            </div>
                        </div>
                    <?php }?>
                </div>
            <?php      
                }
            ?>
            <!-- Popup Section -->
            <?php
                foreach ($pickupListData as $pickupData) {
                    $popupId = $pickupData['popupId'];
            ?>
                    <div class="sttb-section-container-<?= $popupId ?>">
                        <?php include('./shipment_module/sttb_popup.php'); ?>
                    </div>
                    <div class="sttb-address-entry-container-<?= $popupId ?>">
                        <?php include('./shipment_module/components/address_entry_popup.php'); ?>
                    </div>
                    <div class="sttb-popup-address-picker-<?= $popupId ?>">
                        <?php include('./shipment_module/components/new_address_picker.php'); ?>
                    </div>
                    <?php include('./shipment_module/components/pickup_reject.php') ?>
            <?php
                }
            ?>
            <?php include('./shipment_module/components/qr_code_scanner.php'); ?>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function(){
        $(".dropdown-pickup-button").click(function(){
            dropdownPickupIndex = $(this).attr('id').split('_')[1];
            $('#dropdownPickupItem' + dropdownPickupIndex).toggleClass('dblock');
        });

        $('.dropdown-pickup-item button').off('mousedown').mousedown(function() {
            dropdownPickupID = $(this).attr('data-id');
            $('.dropdown-pickup-container #dropdownPickupBtn_' + dropdownPickupID + ' span').text($(this).text());
            $('.dropdown-pickup-container #dropdownPickupItem' + dropdownPickupID).removeClass('dblock');
        });

        // Btn for sttb popup to emerge
        $('.btn-show-sttb-popup').click(function() {
            showSttbIndex = $(this).attr('id').split('_')[1];

            noShipment = $('input#noShipmentEntry' + showSttbIndex).val();
            customerName = $('input#customerName' + showSttbIndex).val();

            if (noShipment !== '0') {
                window.location.href = "index.php?page=shipment_detail_sttb&no_shipment_entry=" + noShipment;
            }else{
                if (customerName !== null && typeof(customerName) === 'string' && customerName.toLowerCase().includes('delami')) {
                    $('.entry-sttb-popup-wrapper-' + showSttbIndex).css("display","inline-block");
                    $('.choose-payment-wrapper-' + showSttbIndex).css("display","none");
                }
                $('.sttb-section-container-' + showSttbIndex).find('.sttb-popup-black-cover').addClass('sttb-popup-show');

            }
        });

        $('.btn-remove-sttb-popup').click(function() {
            removeSttbIndex = $(this).attr('id').split('_')[1];
            $('.sttb-section-container-' + removeSttbIndex).find('.sttb-popup-black-cover').removeClass('sttb-popup-show');
        });

        // Btn for reject popup to emerge
        $('.btn-reject-pickup-trigger').click(function(){
            rejectShowPickupIndex = $(this).attr('data-id');
            $('.pickup-reject-black-cover-wrapper-' + rejectShowPickupIndex).addClass('reject-pop-up-show');
        });
        
        $('.btn-reject-pickup-close-trigger').click(function(){
            rejectClosePickupIndex = $(this).attr('data-id');
            $('.pickup-reject-black-cover-wrapper-' + rejectShowPickupIndex).removeClass('reject-pop-up-show');
        });

        // Btn 'Konfirmasi' clicked, Change & Save status
        $('.btn-konfirmasi-change-status').off('click').click(function() {
            konfirmasStatusID = $(this).attr('data-id');

            data = {
                task_id: $('input#trxTaskID' + konfirmasStatusID).val(),
                nama_status: $('.dropdown-pickup-container button#dropdownPickupBtn_' + konfirmasStatusID + ' span').text()
            }

            Swal.fire({
                title: "Update Status?",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: "simpan",
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "shipment-master/validation/update_task_status.php",
                        method: "POST",
                        data: data,
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

        // Button 'Terima' clicked, Change & Save status
        $('.btn-accept-pickup-trigger').off('click').click(function() {
            accceptStatusID = $(this).attr('data-id');

            Swal.fire({
                title: "Terima Pickup?",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: "Simpan",
            }).then((result) => {
                if (result.isConfirmed) {
                    orderPickupID = $('input#orderPickupID' + accceptStatusID).val();

                    $.ajax({
                        url: "shipment-master/validation/pickup_accept.php",
                        method: "POST",
                        data: {order_pickup_id: orderPickupID},
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