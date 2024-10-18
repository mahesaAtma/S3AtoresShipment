<style>
    .qrcode-container{
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
    }

    .qrcode-item-section{
        width: 200px;
        height: auto;
        margin: 10px 15px;
    }

</style>

<?php
    $shipmentD1ServiceID = isset($_GET['shipment_detail_id']) ? $_GET['shipment_detail_id'] : '999999999';
    $shipmentDetailService = $shipmentObj->getShipmentDetail($shipmentD1ServiceID);
    if ($shipmentDetailService) {
?>
    <div class="main-content">
        <div class="section__content section__content--p25">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header bg-blue-tosca" style="padding: auto 20px; border-radius:10px;">
                            <div class="dflex align-item-c justify-content-sb mt-2">
                                <div class="dflex align-item-c justify-content-sb w-100">
                                    <div class="dflex align-item-c">
                                        <h6 style="font-weight: 700; color:#2C4074;">1.</h6>
                                        <div class="ml-3" style="line-height:5px">
                                            <h6 style="font-weight: 700; text-decoration:underline; color:#2C4074;"><?= $shipmentDetailService->no_shipment_entry ?> - <?= $shipmentDetailService->no_sttb ?> (<?= $shipmentDetailService->jml_koli ?> koli)</h6>
                                            <p><?= $shipmentDetailService->nama_service_product ?> (<?= $shipmentDetailService->prefix ?>)</p>
                                        </div>
                                    </div>
                                    <div style="font-size: 25px;" class="mr-4">
                                        <i class="fa fa-arrow-circle-down mr-3" style="color:#2C4074; cursor:pointer;" type="button" data-toggle="collapse" data-target="#printSTTBCollapse" aria-expanded="true" aria-controls="printSTTBCollapse"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="printSTTBCollapse" class="collapse" aria-labelledby="collapsePrintSTTB" data-parent="#accordionExample">
                            <div class="card-body qrcode-container">
                                <?php
                                    for ($index=0; $index < $shipmentDetailService->jml_koli; $index++) { 
                                ?>
                                    <label for="qrCodeInput<?= ($index + 1) ?>">
                                        <div id="qrcode<?= ($index + 1) ?>" class="qrcode-item-section">
                                            <div class="mb-3 mt-2">
                                                <input id="qrCodeInput<?= ($index + 1) ?>" type="checkbox">
                                                <span class="ml-2"><?= $shipmentDetailService->no_sttb ?>_<?= $shipmentDetailService->prefix ?>_<?= ($index + 1) ?></span>
                                            </div>
                                        </div>
                                    </label>
                                <?php
                                    }
                                ?>
                            </div>
                        </div>

                        <button style="border-radius: 10px;" class="fa fa-print ljr-custom-btn bg-dark-blue mt-4">PRINT BARCODE</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    }else{
        include('./shipment_module/components/go_back_page.php');
    }
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    $(document).ready(function(){
        totalKoli = $('.qrcode-container label').get().length;
        
        for (let index = 0; index < totalKoli; index++) {
            let qrCodeIndex = index + 1;
            let qrText = $('.qrcode-container label #qrcode' + qrCodeIndex + " span").text();
            new QRCode(document.getElementById("qrcode" + qrCodeIndex), qrText);
        }

        $('.fa-arrow-circle-down').off('click').click(function() {
            $(this).toggleClass('rotate-180-deg');
        });
    });
</script>