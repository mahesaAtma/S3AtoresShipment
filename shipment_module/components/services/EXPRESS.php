<?php $expressService = 'express'; ?>

<div id="component-<?= $expressService ?>-service-<?= $popupId ?>" class="service-input-container dnone">
    <h6>Data Koli</h6>
    <input id="<?= $expressService ?>InputJumlahKoli" class="w-100 border-rounded" type="number" placeholder="Jumlah Koli">
    <input id="<?= $expressService ?>InputBeratAsli" class="w-100 border-rounded" type="number" placeholder="Berat asli keseluruhan">
    <input id="<?= $expressService ?>InputNamaBarang" class="nama-barang-input w-100 border-rounded" data-parent="component-<?= $expressService ?>-service-<?= $popupId ?>" type="text" placeholder="Nama Barang">
    <?php 
        // $parentID = "component-$expressService-service-$popupId";
        // include('./shipment_module/components/master/product_dropdown.php')
    ?> 

    <div class="vol-matrix-checkbox dflex align-item-c mt-3 mb-3" data-service='<?= $expressService ?>' data-id="<?= $popupId ?>">
        <input type="checkbox" id="<?= $expressService ?>VolMatrix<?= $popupId ?>">
        <label class="mt-2 ml-3" for="<?= $expressService ?>VolMatrix<?= $popupId ?>"> Vol Matrix </label>
    </div>

    <div class="matrix-dimension-<?= $expressService ?>-input-wrapper-<?= $popupId ?> dnone">
        <div class="matrix-dimension-item-0 matrix-dimension-container">
            <div class="vol-matrix-input dflex">
                <input id="<?= $expressService ?>VolMatrixJumlahKoli0" type="number" data-id="<?= $popupId ?>" data-service="<?= $expressService ?>" data-increment="0" placeholder="Jmlh Koli">
                <input id="<?= $expressService ?>VolMatrixBeratAsli0" type="number" data-id="<?= $popupId ?>" data-service="<?= $expressService ?>" data-increment="0" placeholder="Berat Asli" disabled>
            </div>
        
            <div class="dimension-input dflex align-item-c">
                <input id="<?= $expressService ?>VolMatrixPanjang0" type="number" data-id="<?= $popupId ?>" data-service="<?= $expressService ?>" data-increment="0" placeholder="P"><span> X </span>
                <input id="<?= $expressService ?>VolMatrixLebar0" type="number" data-id="<?= $popupId ?>" data-service="<?= $expressService ?>" data-increment="0" placeholder="L"><span> X </span>
                <input id="<?= $expressService ?>VolMatrixTinggi0" type="number" data-id="<?= $popupId ?>" data-service="<?= $expressService ?>" data-increment="0" placeholder="T">
            </div>
            
            <hr class="line-boundaries">
        </div>
    
        <a id="tambahVolMatrix" href="#" style="align-items:end; display:block; text-align:right;" data-service="<?= $expressService ?>" data-id="<?= $popupId ?>">+ Tambah Vol Matrix Lain</a>
        <a id="kurangVolMatrix" href="#" style="align-items:end; display:block; text-align:right; color:#DB1010;" data-service="<?= $expressService ?>" data-id="<?= $popupId ?>">- Kurang Vol Matrix Lain</a>
        
        <button data-service='<?= $expressService ?>' data-id='<?= $popupId ?>' class='btn-simpan-vol-matrix-trigger ljr-custom-btn bg-dark-blu mt-2 mb-3'>Simpan Vol Matrix</button>
    </div>

    <button data-service='<?= $expressService ?>' data-id='<?= $popupId ?>' class='btn-simpan-service-trigger ljr-custom-btn bg-dark-blu'>Simpan</button>
</div>