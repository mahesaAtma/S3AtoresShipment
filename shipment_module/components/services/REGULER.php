<?php $regulerService = 'reguler'; ?>

<div id="component-<?= $regulerService ?>-service-<?= $popupId ?>" class="service-input-container dnone">
    <h6>Data Koli</h6>
    <input id="<?= $regulerService ?>InputJumlahKoli" class="w-100 border-rounded" type="number" placeholder="Jumlah Koli">
    <input id="<?= $regulerService ?>InputBeratAsli" class="w-100 border-rounded" type="number" placeholder="Berat asli keseluruhan">
    <input id="<?= $regulerService ?>InputNamaBarang" class="nama-barang-input w-100 border-rounded" data-parent="component-<?= $regulerService ?>-service-<?= $popupId ?>" type="text" placeholder="Nama Barang">
    <?php 
        // $parentID = "component-$regulerService-service-$popupId";
        // include('./shipment_module/components/master/product_dropdown.php')
    ?> 

    <div class="vol-matrix-checkbox dflex align-item-c mt-3 mb-3" data-service='<?= $regulerService ?>' data-id="<?= $popupId ?>">
        <input type="checkbox" id="<?= $regulerService ?>VolMatrix<?= $popupId ?>">
        <label class="mt-2 ml-3" for="<?= $regulerService ?>VolMatrix<?= $popupId ?>"> Vol Matrix </label>
    </div>

    <div class="matrix-dimension-<?= $regulerService ?>-input-wrapper-<?= $popupId ?> dnone">
        <div class="matrix-dimension-item-0 matrix-dimension-container">
            <div class="vol-matrix-input dflex">
                <input id="<?= $regulerService ?>VolMatrixJumlahKoli0" type="number" data-id="<?= $popupId ?>" data-service="<?= $regulerService ?>" data-increment="0" placeholder="Jmlh Koli">
                <input id="<?= $regulerService ?>VolMatrixBeratAsli0" type="number" data-id="<?= $popupId ?>" data-service="<?= $regulerService ?>" data-increment="0" placeholder="Berat Asli" disabled>
            </div>
        
            <div class="dimension-input dflex align-item-c">
                <input id="<?= $regulerService ?>VolMatrixPanjang0" type="number" data-id="<?= $popupId ?>" data-service="<?= $regulerService ?>" data-increment="0" placeholder="P"><span> X </span>
                <input id="<?= $regulerService ?>VolMatrixLebar0" type="number" data-id="<?= $popupId ?>" data-service="<?= $regulerService ?>" data-increment="0" placeholder="L"><span> X </span>
                <input id="<?= $regulerService ?>VolMatrixTinggi0" type="number" data-id="<?= $popupId ?>" data-service="<?= $regulerService ?>" data-increment="0" placeholder="T">
            </div>
            
            <hr class="line-boundaries">
        </div>
        
    
        <a id="tambahVolMatrix" href="#" style="align-items:end; display:block; text-align:right;" data-service="<?= $regulerService ?>" data-id="<?= $popupId ?>">+ Tambah Vol Matrix Lain</a>
        <a id="kurangVolMatrix" href="#" style="align-items:end; display:block; text-align:right; color:#DB1010;" data-service="<?= $regulerService ?>" data-id="<?= $popupId ?>">- Kurang Vol Matrix Lain</a>
        
        <button data-service='<?= $regulerService ?>' data-id='<?= $popupId ?>' class='btn-simpan-vol-matrix-trigger ljr-custom-btn bg-dark-blu mt-2 mb-3'>Simpan Vol Matrix</button>
    </div>

    <button data-service='<?= $regulerService ?>' data-id='<?= $popupId ?>' class='btn-simpan-service-trigger ljr-custom-btn bg-dark-blu'>Simpan</button>
</div>