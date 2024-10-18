<style>
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
        background-color: rgba(230,237,250,0.6);
        padding: 20px;
        border-radius: 15px 15px 0px 0px;
    }
</style>


<div class="choose-payment-container choose-payment-wrapper-<?= $popupId ?>">
    <h6 style="color:#555555;"><i class="fas fa-warehouse mr-2"></i> Pilih Pembayaran</h6> 
    <hr class="line-boundaries">
    <div class="card">
        <div class="card-body">
            <form class="mt-4">
                <?php
                    $caraBayars = $shipmentObj->listCaraPembayaran();
            
                    foreach ($caraBayars as $item) {
                        $namaCaraBayar = $item["nama_cara_bayar"];
                ?>
                            <label class='dblock' for='<?= $namaCaraBayar ?>'>
                                <div class='shipment-checkbox-container box-shadow-with-thin-border'>
                                    <div class='shipment-img-checkbox-container'>
                                        <img class='img-padding' src="../images/icon/shipment/<?= $namaCaraBayar ?>.png">
                                        <span><?= $namaCaraBayar ?></span>
                                    </div>
            
                                    <input type='radio' id='<?= $namaCaraBayar ?>' name='fav_language' value='<?= $namaCaraBayar ?>'>
                                </div>
                            </label>
                <?php
                    }
                ?>
            </form>

            <button data-id='<?= $popupId ?>' class='btn-simpan-payment-trigger ljr-custom-btn bg-dark-blu mt-4'>Simpan</button>

            <button id="<?= $popupId ?>" class="btn-payment-next-trigger ljr-custom-btn bg-dark-blue mt-4" style="border-radius: 10px;">Berikutnya</button>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function(){
        paymentData = {};

        $('.btn-payment-next-trigger').off('click').click(function(){
            paymentNextID = $(this).attr('id');
            $('.entry-sttb-popup-wrapper-' + paymentNextID).css("display","inline-block");
            $('.choose-payment-wrapper-' + paymentNextID).css("display","none");
        });

        $('.btn-simpan-payment-trigger').off('click').click(function(){
            paymentSimpanID = $(this).attr('data-id');
            paymentData[paymentSimpanID] = $('.choose-payment-wrapper-' + paymentSimpanID + ' input:checked').val();
            Swal.fire("Saved!", "", "success");
        });
    });
</script>