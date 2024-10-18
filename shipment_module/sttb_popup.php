<style>
    .sttb-popup-black-cover{
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: -1;
        opacity: 0;
        transition: 0.6s ease-in-out;
    }

    .sttb-popup-page{
        padding: 40px 40px 40px 30px;
        background-color: white;
        left: 0;
        width: 80%;
        height: 80%;
        position: absolute;
        transform: translate(-50%,-50%);
        top: 50%;
        left: 50%;
        overflow-y: scroll;
    }

    .sttb-popup-black-cover .sttb-popup-page > i{
        position: absolute;
        right: 20px;
        top: 20px;
        font-size: 30px;
        cursor: pointer;
    }

    .sttb-popup-show{
        opacity: 1;
        z-index: 2;
    }

    .sttb-popup-page .sttb-popup-item-container{
        min-width: 100%;
        white-space: nowrap;
        transition: 0.5s ease-in-out;
    }

    .sttb-popup-item-container > div{
        width: 100%;
        white-space: normal;
    }
    
    @media only screen and (max-width: 600px) {
        .sttb-popup-page{
            padding: 30px 30px 30px 30px;
            width: 95%;
            height: 95%;
        }
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="sttb-popup-black-cover">
    <div class="sttb-popup-page regular-box-shadow-with-thin-border">
        <i id="closeSTTBPopup_<?= $popupId ?>" class="fas fa-remove btn-remove-sttb-popup"></i>
        <div class="sttb-popup-wrapper">
            <div class="sttb-popup-item-container">
                <?php
                    $posStr = strpos(strtolower($pickupData['customer_name']), 'delami');
                    if ($posStr === false) {
                ?>
                    <?php include('./shipment_module/components/sttb/payment.php') ?>
                <?php
                    }
                ?>
                <?php include('./shipment_module/components/sttb/entry_sttb.php') ?>
                <?php include('./shipment_module/components/sttb/add_services.php') ?>
                <?php include('./shipment_module/components/sttb/add_documents.php') ?>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>