<style>
    .popup-address-picker-wrapper{
        position: absolute;
        left: 0;
        bottom: -100%;
        width: 100%;
        height: 100%;
        z-index: -1;
        opacity: 0;
        background-color: rgba(0, 0, 0, 0.2);
        transition: 0.6s ease-in-out;
    }
    
    .popup-address-picker{
        background-color: #EBEBEB;
        position: absolute;
        min-height: auto;
        max-height: 80%;
        height: auto;
        width: 100%;
        left: 0;
        bottom: 0;
        z-index: 2;
        border-radius: 20px 20px 0px 0px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        transition: 0.3s ease-in-out;
    }
    
    .popup-address-picker > i{
        position: absolute;
        top: 25px;
        right: 25px;
        font-size: 20px;
        cursor: pointer;
        z-index: 3;
    }

    .popup-address-container{
        position: relative;
        bottom: 0;
        width: 90%;
        height: 100%;
        font-size: 12px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .popup-address-cari-alamat{
        background-color: #FFFFFF;
        padding: 20px 30px 20px 30px;
        display: block;
        margin: 50px 0px 10px 0;
        border-radius: 20px;
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 17px;
    }

    .popup-address-cari-alamat input{
        width: 100%;
        display: block;
    }

    .popup-address-item-container{
        overflow-y: scroll;
        max-height: 300px;
        width: 100%;
        margin-bottom: 20px;
    }

    .popup-address-item{
        padding: 20px;
        background-color: #FFFFFF;
        cursor: pointer;
        transition: 0.3s ease-in-out;
        display: none;
        font-size: 15px;
    }

    .popup-address-item:hover{
        background-color: #e6e6e6;
    }

    .popup-address-item img{
        margin: 0px 15px 0px 5px;        
        width: 30px;
    }

    .popup-address-item p{
        margin-bottom: 3px;
        word-wrap: break-word;
    }

    .popup-address-picker-show{
        opacity: 1;
        bottom: 0;
        z-index: 2;
    }

    button.add-delivery-address-picker{
        text-align: center;
        border: none;
        width: 100%;
        padding: 10px 0;
        transition: 0.3s ease-in-out;
        font-weight: 700;
        color: #FFFFFF;
        background-color: #2C4074;
        border-radius: 10px;
        font-size: 17px;
    }
</style>

<div class="popup-address-picker-wrapper popup-address-picker-id-<?= $popupId ?>" data-id="<?= $popupId ?>">
    <div class="popup-address-picker">
        <i id="closePopupAddressPicker" class="btn-close-address-popup-picker-trigger fas fa-remove" data-id="<?= $popupId ?>"></i>
        <div class="popup-address-container">
            <div class="popup-address-cari-alamat">
                <input type="text" class="address-picker-input" placeholder="Cari Alamat" data-id="<?= $popupId ?>" data-parent="list-address-item-<?= $popupId ?>">
                <i class="fas fa-search"></i>
            </div>
    
            <div class="popup-address-item-container list-address-item-<?= $popupId ?>">
                <?php
                    $custSentReceipt = $shipmentObj->getListCustomerSendReceipt(7100480);
                    if ($custSentReceipt) {
                        foreach ($custSentReceipt as $csrItem) {
                ?>
                    <div class="popup-address-item mt-2 box-shadow-with-thin-border dflex align-item-c" data-id="<?= $popupId ?>">
                        <img src="../images/icon/shipment/gmap-icon.png">
                        <div>
                            <input id="addressInputID<?= $popupId ?>" type="hidden" value="<?= $csrItem['id'] ?>">
                            <p id="addressInputNama<?= $popupId ?>"><?= is_null($csrItem['nama_send_receipt']) ? '-' : $csrItem['nama_send_receipt'] ?></p>
                            <p id="addressInputAlamat<?= $popupId ?>"><?= is_null($csrItem['alamat']) ? '-' : $csrItem['alamat'] ?></p>
                            <p id="addressInputPhone<?= $popupId ?>"><?= is_null($csrItem['phone_pic']) ? '-' : $csrItem['phone_pic'] ?></p>
                        </div>
                    </div>
                <?php
                        }
                    }else{
                ?>
                        <div id="addressCollapse<?= $popupId ?>" class="sttb-entry-address-container onHover collapse" aria-labelledby="collapseAddress<?= $popupId ?>" data-parent="#accordionExample" data-id="<?= $popupId ?>" style="cursor: pointer;">
                            <p class="mt-3" style="color:red; text-align:center;">Alamat tidak ditemukan, mohon tambah terlebih dahulu!</p>
                        </div>
                <?php
                    }
                ?>
            </div>

            <!-- Button "id" used in components/address_entry_popup.php -->
            <button class="add-delivery-address-picker mb-4" data-id="<?= $popupId ?>">
                <img src="../../../images/icon/shipment/dark-blue-gmap-icon.png"> Tambah Alamat Delivery
            </button>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function(){
        custSendData = {};
        isAddressChoosen = {};
    });

    var delay = makeDelay(1000);

    $('.btn-close-address-popup-picker-trigger').off('click').click(function() {
        addressPickerID = $(this).attr('data-id');
        $('.popup-address-picker-id-' + addressPickerID).removeClass('popup-address-picker-show');
    });

    $('input.address-picker-input').off('keyup').keyup(function(){
        currentValue = (this.value).toLowerCase();
        dataParent = $(this).attr('data-parent');
        dataID = $(this).attr('data-id');
        
        delay(function(){
            $.ajax({
                type:"GET",
                url: "shipment-master/validation/address-search.php",
                data: {value: currentValue, customer_id: 7100480},
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        console.log(response);
                        $(`.${dataParent}`).empty();
                        $(`.${dataParent}`).append(listAdressPickerComponent(response.data, dataID))
                        $(`.${dataParent}` + ' .popup-address-item').off('mousedown').mousedown(function() {
                            let dataID = $(this).attr('data-id');
                            updateCustSendReceipt(
                                $(this).find('input#addressInputID' + dataID).val(), 
                                $(this).find('p#addressInputNama' + dataID).text(), 
                                $(this).find('p#addressInputAlamat' + dataID).text(), 
                                $(this).find('p#addressInputPhone' + dataID).text(), 
                                dataID
                            )
                        });
                    }else{
                        $(`.${dataParent}`).append("<button>There's an error occured!</button>")
                    }
                }
            })
        })
    })
    
    $('.popup-address-item-container .popup-address-item').off('mousedown').mousedown(function() {
        let dataID = $(this).attr('data-id');

        updateCustSendReceipt(
            $(this).find('input#addressInputID' + dataID).val(), 
            $(this).find('p#addressInputNama' + dataID).text(), 
            $(this).find('p#addressInputAlamat' + dataID).text(), 
            $(this).find('p#addressInputPhone' + dataID).text(), 
            dataID
        )
    });

    function updateCustSendReceipt(id, label, alamat, nomor, popupId) {
        Swal.fire({
            title: "Yakin pilih alamat penerima?",
            showDenyButton: true,
            confirmButtonText: "Ya",
            denyButtonText : 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('ul#addressPickerResult' + popupId + ' input').val(id);
                $('ul#addressPickerResult' + popupId + ' li#addressLabelID span').text(label);
                $('ul#addressPickerResult' + popupId + ' li#addressAlamatID span').text(alamat);
                $('ul#addressPickerResult' + popupId + ' li#addressNomorID span').text(nomor);
                $('.popup-address-picker-id-' + popupId).removeClass('popup-address-picker-show');
            }
        });
    }
</script>