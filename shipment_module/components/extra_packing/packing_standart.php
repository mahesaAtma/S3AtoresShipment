<style>
    .packing-standard{
        display: none;
    }

    .packing-standard .standard-dimension > div {
        display: flex;
        align-items: center;
        margin: 0 auto;
    }

    .packing-standard .standard-container{
        display: flex;
        flex-direction: column;
    }

    .packing-standard .standard-container h6, .packing-standard .standard-section-2 h6{
        margin: 0 auto 10px 10px;
    }

    .packing-standard .standard-container input ,.packing-standard .standard-section-2 input{
        margin: 0 7px;
        padding: 10px 20px;
        border-radius: 10px;
        background-color: white;
        width: 100%;
    }

    .packing-standard .standard-section-2 div{
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    @media only screen and (max-width: 600px) {
        .packing-standard .standard-dimension > div {
            display: block !important;
        }

        .packing-standard .standard-dimension > div h6 {
            margin-top: 10px;
        }
    }
</style>

<?php
    $packingType = 'standard';
?>

<div class="packing-container packing-<?= $packingType ?> packing-<?= $packingType ?>-wrapper-<?= $popupId ?> mt-5">
    <div class="<?= $packingType ?>-dimension">
        <div class="packing-<?= $packingType ?>-dimension-0 mb-3">
            <div class="<?= $packingType ?>-container <?= $packingType ?>-jumlah-koli mr-2">
                <h6>Jumlah Koli</h6>
                <input id="<?= $packingType ?>JumlahKoli" type="number" placeholder="0" data-packing-type="<?= $packingType ?>" data-id="<?= $popupId ?>" data-increment="0">
            </div>
            
            <div class="<?= $packingType ?>-container mr-2">
                <h6>Ukuran PxLxT (Total Vol)</h6>
                <div class="dflex packing-dimension-container">
                    <input id="<?= $packingType ?>Panjang" type="number" placeholder="P" data-packing-type="<?= $packingType ?>" data-id="<?= $popupId ?>" data-increment="0">
                    <input id="<?= $packingType ?>Lebar" type="number" placeholder="L" data-packing-type="<?= $packingType ?>" data-id="<?= $popupId ?>" data-increment="0">
                    <input id="<?= $packingType ?>Tinggi" type="number" placeholder="T" data-packing-type="<?= $packingType ?>" data-id="<?= $popupId ?>" data-increment="0">
                </div>
            </div>
            
            <div class="<?= $packingType ?>-container <?= $packingType ?>-weight-item">
                <h6>Berat</h6>
                <input id="<?= $packingType ?>Berat" type="number" placeholder="Berat" disabled>
                <input id="<?= $packingType ?>Harga" type="hidden">
            </div>
        </div>
    </div>

    <a id="tambahPacking" class="mt-3 mb-1" href="#" data-id="<?= $popupId ?>" data-packing="<?= $packingType ?>" style="width:100%; text-align:right;"> + Tambah</a>
    <a id="kurangPacking" class="mt-1 mb-3" href="#" data-id="<?= $popupId ?>" data-packing="<?= $packingType ?>" style="width:100%; text-align:right; color:#DB1010;"> - Tambah</a>

    <div class="<?= $packingType ?>-section-2">
        <div class="<?= $packingType ?>-price mr-2">
            <h6>Harga Packing Standart</h6>
            <input id="<?= $packingType ?>HargaPacking" type="number" placeholder="Harga" disabled>
        </div>
        
        <div class="<?= $packingType ?>-weight">
            <h6>Berat Packing</h6>
            <input id="<?= $packingType ?>BeratPacking" type="number" placeholder="Berat" disabled>
        </div>
    </div>

    <button id="<?= $popupId ?>" class="btn-packing-<?= $packingType ?>-trigger ljr-custom-btn bg-dark-blue mt-4">Tambah</button>
</div>