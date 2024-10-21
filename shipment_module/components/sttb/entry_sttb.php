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
    }

    button.add-delivery-address:hover{
        background-color: rgba(70,87,133,0.1);
    }

    .entry-sttb-popup{
        display: none;
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

<div class="entry-sttb-popup entry-sttb-popup-wrapper-<?= $popupId ?>">
    <h6 style="color:#555555;"><i class="fas fa-warehouse mr-2"></i> STTB & ADDRESS</h6> 
    <hr class="line-boundaries">
    <div class="card">
        <div class="card-body">
            <div class="sttb-entry-choice">
                <button class="input-sttb-fisik bg-light-purple-gradient-radiant" data-id="<?= $popupId ?>"><img src="../images/icon/shipment/pencil-alt.png">Input STTB Fisik</button>
                <button class="input-elektronik-sttb bg-light-blue-gradient-radiant" data-id="<?= $popupId ?>"><img src="../images/icon/shipment/bill.png">E-STTB</button>
            </div>
            <hr/>
            <div class="sttb-entry-container">
                <div class="sttb-entry-input">
                    <input id="scanQRCodeInput<?= $popupId ?>" class="input-no-sttb" data-sttb-type="" type="text" placeholder="Input No STTB" disabled>
                    <div class="scan-qr-code-shipment" data-id="<?= $popupId ?>">
                        <img src="../images/icon/shipment/scan.png">
                    </div>
                </div>
    
                <h6 class="mt-4 ml-4">Alamat Penerima</h6>
                <?php include('./shipment_module/components/address_picker.php') ?>
            </div>
    
            <!-- Button "id" used in components/address_entry_popup.php -->
            <button class="add-delivery-address" data-id="<?= $popupId ?>">
                <img src="../../../images/icon/shipment/dark-blue-gmap-icon.png"> Tambah Alamat Delivery
            </button>
    
            <button id="<?= $popupId ?>" class="btn-sttb-next-trigger ljr-custom-btn bg-dark-blue mt-4" style="border-radius: 10px;">Berikutnya</button>
            <button id="<?= $popupId ?>" class="btn-sttb-previous-trigger ljr-custom-btn bg-dark-red mt-4" style="border-radius: 10px;">Sebelumnya</button>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function(){
        sttbPressEnter();
        
        $('.btn-sttb-next-trigger').off('click').click(function(){
            sttbNextIndex = $(this).attr('id');
            $('.entry-sttb-popup-wrapper-' + sttbNextIndex).css("display","none");
            $('.add-service-popup-wrapper-' + sttbNextIndex).css("display","inline-block");
        });

        $('.btn-sttb-previous-trigger').off('click').click(function(){
            sttbNextIndex = $(this).attr('id');
            $('.entry-sttb-popup-wrapper-' + sttbNextIndex).css("display","none");
            $('.choose-payment-wrapper-' + sttbNextIndex).css("display","inline-block");
        });

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


    });
</script>
