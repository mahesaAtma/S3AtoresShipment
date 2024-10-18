<style>
    .entry-address-popup-container{
        position: absolute;
        left: 0;
        bottom: -100%;
        width: 100%;
        height: 100%;
        z-index: -1;
        opacity: 0;
        transition: 0.6s ease-in-out;
    }
    
    .entry-new-delivery-address-popup{
        font-size: 12px;
        background-color: #EBEBEB;
        position: absolute;
        height: 80%;
        width: 100%;
        left: 0;
        bottom: 0;
        z-index: 2;
        border-radius: 20px 20px 0px 0px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .entry-new-delivery-address-popup i{
        font-size: 20px;
        position: absolute;
        cursor: pointer;
        top: 30px;
        right: 40px;
    }

    .label-alamat-container button, .btn-address-entry-popup-trigger{
        padding: 10px 20px;
        border: 1px solid #2C4074;
        color: #2C4074;
        font-weight: 700;
        border-radius: 15px;
        opacity: 0.7;
    }

    .label-alamat-container button.active, .btn-address-entry-popup-trigger.active{
        background-color: #2C4074 !important;
        color: white !important;
        opacity: 1;
    }

    .entry-new-delivery-address-popup input{
        display: block;
        width: 100%;
        padding: 10px 0px 10px 20px;
        border-radius: 20px;
        margin: 7px 0;
    }

    .pop-over-address-show{
        opacity: 1;
        bottom: 0;
        z-index: 3;
    }

    .delivery-popup-container{
        position: relative;
    }
    
    .input-areas-container{
        width: 100%;
        position: absolute;
        background-color: white;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        padding: 10px;
        display: none;
    }

    .input-areas-container button{
        padding: 7px 15px;
        width: 100%;
        text-align: left;
        transition: 0.3s ease-in-out;
        font-size: 12px;
    }

    .input-areas-container button:hover{
        background-color: #ddd;
    }
</style>

<div class="entry-address-popup-container entry-address-popup-wrapper-<?= $popupId ?>" data-id="<?= $popupId ?>">
    <div class="entry-new-delivery-address-popup">
        <i id="closePopOverAddress" class="btn-close-address-popup-trigger fas fa-remove" data-id="<?= $popupId ?>"></i>
        <div class="delivery-popup-container">
            <h6>Label Alamat</h6>

            <div class="label-alamat-container label-alamat-wrapper-<?= $popupId ?> mb-2" data-id="<?= $popupId ?>">
                <button data-id="<?= $popupId ?>" class="active">Rumah</button>
                <button data-id="<?= $popupId ?>">Kantor</button>
                <button data-id="<?= $popupId ?>">Apartmen</button>
            </div>

            <input class="address-input-provinsi" id="addressEntryPopupProvinsi<?= $popupId ?>" data-id="<?= $popupId ?>" type="text" placeholder="Provinsi">
            <div class="input-areas-container nama-provinsi-container nama-provinsi-wrapper-<?= $popupId ?>"></div>

            <input class="address-input-kota" id="addressEntryPopupKota<?= $popupId ?>" data-id="<?= $popupId ?>" type="text" placeholder="Kota">
            <div class="input-areas-container nama-kota-container nama-kota-wrapper-<?= $popupId ?>"></div>

            <input class="address-input-kecamatan" id="addressEntryPopupKecamatan<?= $popupId ?>" data-id="<?= $popupId ?>" type="text" placeholder="Kecamatan">
            <div class="input-areas-container nama-kecamatan-container nama-kecamatan-wrapper-<?= $popupId ?>"></div>

            <input id="addressEntryPopupAlamatPenerima<?= $popupId ?>" type="text" placeholder="Alamat Penerima">
            <input id="addressEntryPopupNamaPenerima<?= $popupId ?>" type="text" placeholder="Nama Penerima">
            <input id="addressEntryPopupContactPerson<?= $popupId ?>" type="number" placeholder="Contact Person">

            <button class="btn-address-entry-popup-trigger mt-2 active" data-id="<?= $popupId ?>" style="width: 100%;">Simpan</button>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    var timer;

    $(document).ready(function(){
        $('.add-delivery-address, .add-delivery-address-picker').off('click').click(function(){
            addressContainerIndex = $(this).attr('data-id');
            $('.entry-address-popup-wrapper-' + addressContainerIndex).addClass('pop-over-address-show');
        });

        $('.label-alamat-container button').click(function(){
            labelAlamatContainerIndex = $(this).attr('data-id');
            $(`.label-alamat-wrapper-${labelAlamatContainerIndex} button`).removeClass('active');
            $(this).addClass('active');
        });

        $(".btn-close-address-popup-trigger").off('click').click(function(){
            closeAddressBtnIndex = $(this).attr('data-id');
            $('.entry-address-popup-wrapper-' + closeAddressBtnIndex).removeClass('pop-over-address-show');
        });

        $('.btn-address-entry-popup-trigger').off('click').click(function (){
            entryAddressPopupID = $(this).attr('data-id');
            entryAdressClass = '.entry-address-popup-wrapper-' + entryAddressPopupID;

            labelAlamat = $(entryAdressClass + ' .label-alamat-container button').filter((_,el) => el.className === 'active').text();

            Swal.fire({
                title: "Simpan Perubahan?",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: "Simpan",
            }).then((result) => {
                if (result.isConfirmed) {
                    dataEntry = {
                        label_alamat: labelAlamat.toLowerCase(),
                        provinsi: $(entryAdressClass + ' input#addressEntryPopupProvinsi' + entryAddressPopupID).val(),
                        kota: $(entryAdressClass + ' input#addressEntryPopupKota' + entryAddressPopupID).val(),
                        kecamatan: $(entryAdressClass + ' input#addressEntryPopupKecamatan' + entryAddressPopupID).val(),
                        alamat_penerima: $(entryAdressClass + ' input#addressEntryPopupAlamatPenerima' + entryAddressPopupID).val(),
                        nama_penerima: $(entryAdressClass + ' input#addressEntryPopupNamaPenerima' + entryAddressPopupID).val(),
                        contact_person: $(entryAdressClass + ' input#addressEntryPopupContactPerson' + entryAddressPopupID).val(),
                        customer_id: $('input#customerID' + entryAddressPopupID).val(),
                        trx_order_pickup_d1_id: $('input#orderPickupDetailID' + entryAddressPopupID).val(),
                    }
        
                    $.ajax({
                        url: "shipment-master/validation/address.php",
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

        $(".address-input-provinsi").off('keyup').on("keyup", function() {
            provinsiVal = $(this).val().toLowerCase();
            provinsiElID = $(this).attr('data-id');
            provinsiParentID = $(this).attr('id');
            provinsiWrapperID = '.nama-provinsi-wrapper-' + provinsiElID;
            
            clearTimeout(timer);
            timer = setTimeout(function() {
    
                $(provinsiWrapperID).empty();
                $(provinsiWrapperID).addClass('dblock');
    
                $.ajax({
                    type:"GET",
                    url: "shipment-master/get_provinsis.php",
                    data: {value: provinsiVal},
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            $(provinsiWrapperID).append(listProvinsiComponent(data.data, `#${provinsiParentID}`))
                            $('.nama-provinsi-container button').off('mousedown').mousedown(function() {
                                namaProvinsiParent = $(this).attr('data-parent');
                                $(namaProvinsiParent).val($(this).text());
                            });
                        }else{
                            $(provinsiWrapperID).append("<button>There's an error occured!</button>")
                        }
                    }
                })
            }, 1000);
        });

        $(".address-input-kota").off('keyup').on("keyup", function() {
            kotaVal = $(this).val().toLowerCase();
            kotaElID = $(this).attr('data-id');
            kotaParentID = $(this).attr('id');
            kotaWrapperID = '.nama-kota-wrapper-' + kotaElID;
            
            clearTimeout(timer);
            timer = setTimeout(function() {
                $(kotaWrapperID).empty();
                $(kotaWrapperID).addClass('dblock');
    
                $.ajax({
                    type:"GET",
                    url: "shipment-master/get_kotas.php",
                    data: {value: kotaVal},
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            $(kotaWrapperID).append(listKotaComponent(data.data, `#${kotaParentID}`))
                            $('.nama-kota-container button').off('mousedown').mousedown(function() {
                                namaKotaParent = $(this).attr('data-parent');
                                $(namaKotaParent).val($(this).text());
                            });
                        }else{
                            $(kotaWrapperID).append("<button>There's an error occured!</button>")
                        }
                    }
                })
            }, 1000);
        });

        $(".address-input-kecamatan").off('keyup').on("keyup", function() {
            kecamatanVal = $(this).val().toLowerCase();
            kecamatanElID = $(this).attr('data-id');
            kecamatanParentID = $(this).attr('id');
            kecamatanWrapperID = '.nama-kecamatan-wrapper-' + kecamatanElID;

            clearTimeout(timer);
            timer = setTimeout(function() {
                $(kecamatanWrapperID).empty();
                $(kecamatanWrapperID).addClass('dblock');
    
                $.ajax({
                    type:"GET",
                    url: "shipment-master/get_kecamatans.php",
                    data: {value: kecamatanVal},
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            $(kecamatanWrapperID).append(listKecamatanComponent(data.data, `#${kecamatanParentID}`))
                            $('.nama-kecamatan-container button').off('mousedown').mousedown(function() {
                                namaKecamatanParent = $(this).attr('data-parent');
                                $(namaKecamatanParent).val($(this).text());
                            });
                        }else{
                            $(kecamatanWrapperID).append("<button>There's an error occured!</button>")
                        }
                    }
                })
            }, 1000);
        });

        $(".address-input-provinsi").off('focusout').on("focusout", function() {
            provinsiElID = $(this).attr('data-id');
            provinsiWrapperID = '.nama-provinsi-wrapper-' + provinsiElID;
            $(provinsiWrapperID).empty();
            $(provinsiWrapperID).removeClass('dblock');

        });

        $(".address-input-kota").off('focusout').on("focusout", function() {
            kotaElID = $(this).attr('data-id');
            kotaWrapperID = '.nama-kota-wrapper-' + kotaElID;
            $(kotaWrapperID).empty();
            $(kotaWrapperID).removeClass('dblock');

        });

        $(".address-input-kecamatan").off('focusout').on("focusout", function() {
            kecamatanElID = $(this).attr('data-id');
            kecamatanWrapperID = '.nama-kecamatan-wrapper-' + kecamatanElID;
            $(kecamatanWrapperID).empty();
            $(kecamatanWrapperID).removeClass('dblock');
        });
        
    });
</script>