<style>
    .sttb-entry-address{
        padding: 20px 10px;
        border-radius: 10px;
        display: flex;
        justify-content: start;
        align-items: center;
        cursor: pointer;
    }

    .sttb-entry-address img{
        width: 50px;
        height: auto;
    }

    .sttb-entry-address-collapse img{
        margin-left: 40px;
        width: 30px;
    }

    .sttb-entry-address-collapse p{
        line-height: 14px;
        padding: 0px 10px;
        word-wrap: break-word;
    }

    .sttb-entry-address-collapse > div{
        padding-top: 20px;
        margin-left: 20px;
    }

    .collapse-info-container{
        margin: 10px 0;
        padding: 20px 30px;
        background-color: rgba(196,224,227,0.6);
        border-radius: 10px;
    }
    
    @media only screen and (max-width: 600px) {
        .sttb-entry-address-container p{
            font-size: 12px;
            line-height: 14px;
        }

        .sttb-entry-address-container img{
            width: 18px;
            height: auto;
            margin-right: 5px;
        }

        .sttb-entry-address-collapse > div{
            padding-top: 20px;
        }

        .collapse-info-container{
            margin: 10px 0;
            padding: 20px 30px;
            background-color: rgba(196,224,227,0.6);
            border-radius: 10px;
        }

        .collapse-info-container p{
            font-size: 12px;
            height: auto;
        }

        .collapse-info-container i{
            font-size: 12px;
        }
    }
</style>

<?php
    $custSendReceipt = $shipmentObj->getMasterCustomerSendReceipt($pickupData['custsentreceipt_id']);
?>

<div class="address-picker">
    <div class="sttb-entry-address box-shadow-with-thin-border mt-3" data-id="<?= $popupId ?>">
        <img class="ml-2" src="../images/icon/shipment/green-map.png">
        <h6 class="ml-4">Pilih Lokasi Kirim</h6>
    </div>
</div>

<div class="collapse-info-container address-picker-result-wrapper-<?= $popupId ?>">
    <h6>Informasi Alamat</h6>
    <div class="dflex align-item-c justify-content-sb mt-2 ml-4">
        <div>
            <ul id="addressPickerResult<?= $popupId ?>">
                <li>Nama Send Receipt : <?= $custSendReceipt ? $custSendReceipt->nama_send_receipt : '-' ?></li>
                <li>Alamat : <?= $custSendReceipt ? $custSendReceipt->alamat : '-' ?></li>
                <li>Nomor : <?= $custSendReceipt ? $custSendReceipt->phone_pic : '-' ?></li>
            </ul>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function(){
        custSendData = {};
        isAddressChoosen = {};
    });

    $('.sttb-entry-address').off('click').click(function() {
        entryAddressID = $(this).attr('data-id');
        $('.popup-address-picker-id-' + entryAddressID).addClass('popup-address-picker-show');
    });
</script>
