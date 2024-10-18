<style>
    .pickup-reject-black-cover{
        background-color: rgba(0, 0, 0, 0.6);
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        opacity: 0;
        transition: 0.3s ease-in-out;
    }

    .reject-reason-pop-up{
        width: 400px;
        border-radius: 20px;
        position: absolute;
        background-color: white;
        top: 50%;
        left: 50%;
        z-index: 2;
        padding: 30px 30px;
        transform: translate(-50%,-50%);
    }

    .reject-pop-up-show{
        z-index: 1;
        opacity: 1;
    }

    .reject-reason-pop-up i{
        position: absolute;
        top: 20px;
        right: 30px;
        cursor: pointer;
        font-size: 20px;
    }
</style>

<?php
    /**
     * !NOTE: These reasons are also stated in pickup reject file  
     */
    $rejectReason1 = "Armada sedang mengalami maintenance";
    $rejectReason2 = "Armada sudah penuh";
    $rejectReason3 = "Armada trouble";
?>

<div class="pickup-reject-black-cover pickup-reject-black-cover-wrapper-<?= $popupId ?>">
    <div class="reject-reason-pop-up">
        <i class="btn-reject-pickup-close-trigger fas fa-remove"></i>

        <h6 style="font-weight: 700;" class="mb-2">Alasan menolak pickup barang</h6>
        <form style="flex-direction:column;" class="dflex align-item-s justify-content-c">
            <input id="rejectPickupOrderID" type="hidden" value="<?= $pickupData['trx_order_pickup_id'] ?>">
            <div class="dflex align-item-c justify-content-sb w-100">
                <label for="rejectReason1<?= $popupId ?>"><?= $rejectReason1 ?></label>
                <input id="rejectReason1<?= $popupId ?>" class="mb-1" name="reject-reason" type="radio" value="<?= $rejectReason1 ?>">
            </div>

            <div class="dflex align-item-c justify-content-sb w-100">
                <label for="rejectReason2<?= $popupId ?>"><?= $rejectReason2 ?></label>
                <input id="rejectReason2<?= $popupId ?>" class="mb-1" name="reject-reason" type="radio" value="<?= $rejectReason2 ?>">
            </div>

            <div class="dflex align-item-c justify-content-sb w-100">
                <label for="rejectReason3<?= $popupId ?>"><?= $rejectReason3 ?></label>
                <input id="rejectReason3<?= $popupId ?>" class="mb-1" name="reject-reason" type="radio" value="<?= $rejectReason3 ?>">
            </div>
        </form>

        <button style="border-radius: 10px;" class="ljr-konfirmasi-btn-reject-trigger ljr-custom-btn bg-dark-blue mt-2" data-id="<?= $popupId ?>">Konfirmasi</button>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.ljr-konfirmasi-btn-reject-trigger').off('click').click(function() {
            rejectBtnID = $(this).attr('data-id');
            
            data = {
                pickup_order_id: $('input#orderPickupID' + rejectBtnID).val(),
                reject_reason: $('.pickup-reject-black-cover-wrapper-' + rejectBtnID + ' input:checked').val()
            }
            
            Swal.fire({
                title: "Simpan Perubahan?",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: "Simpan",
            }).then((result) => {
                if (result.isConfirmed) {
                    if (data.reject_reason === undefined) {
                        Swal.fire("Mohon pilih alasan terlebih dahulu", "", "info");
                    }else{
                        $.ajax({
                            url: "shipment-master/validation/pickup_reject.php",
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
                }
            });
        });
    });
</script>