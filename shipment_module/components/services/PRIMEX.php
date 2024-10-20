<?php $primexService = 'primex'; ?>

<div id="component-<?= $primexService ?>-service-<?= $popupId ?>" class="service-input-container dnone">
    <h6>Data Koli</h6>
    <input id="<?= $primexService ?>InputJumlahKoli" class="w-100 border-rounded" type="number" placeholder="Jumlah Koli">
    <input id="<?= $primexService ?>InputBeratAsli" class="w-100 border-rounded" type="number" placeholder="Berat asli keseluruhan">
    <input id="<?= $primexService ?>InputNamaBarang" class="nama-barang-input w-100 border-rounded" data-parent="component-<?= $primexService ?>-service-<?= $popupId ?>" type="text" placeholder="Nama Barang">
    <?php 
        // $parentID = "component-$primexService-service-$popupId";
        // include('./shipment_module/components/master/product_dropdown.php')
    ?> 

    <div class="vol-matrix-checkbox dflex align-item-c mt-3 mb-3" data-service='<?= $primexService ?>' data-id="<?= $popupId ?>">
        <input type="checkbox" id="<?= $primexService ?>VolMatrix<?= $popupId ?>">
        <label class="mt-2 ml-3" for="<?= $primexService ?>VolMatrix<?= $popupId ?>"> Vol Matrix </label>
    </div>

    <div class="matrix-dimension-<?= $primexService ?>-input-wrapper-<?= $popupId ?> dnone">
        <div class="matrix-dimension-item-0 matrix-dimension-container">
            <div class="vol-matrix-input dflex">
                <input id="<?= $primexService ?>VolMatrixJumlahKoli0" type="number" data-id="<?= $popupId ?>" data-service="<?= $primexService ?>" data-increment="0" placeholder="Jmlh Koli">
                <input id="<?= $primexService ?>VolMatrixBeratAsli0" type="number" data-id="<?= $popupId ?>" data-service="<?= $primexService ?>" data-increment="0" placeholder="Berat Asli" disabled>
            </div>
        
            <div class="dimension-input dflex align-item-c">
                <input id="<?= $primexService ?>VolMatrixPanjang0" type="number" data-id="<?= $popupId ?>" data-service="<?= $primexService ?>" data-increment="0" placeholder="P"><span> X </span>
                <input id="<?= $primexService ?>VolMatrixLebar0" type="number" data-id="<?= $popupId ?>" data-service="<?= $primexService ?>" data-increment="0" placeholder="L"><span> X </span>
                <input id="<?= $primexService ?>VolMatrixTinggi0" type="number" data-id="<?= $popupId ?>" data-service="<?= $primexService ?>" data-increment="0" placeholder="T">
            </div>
            
            <hr class="line-boundaries">
        </div>
    
        <a id="tambahVolMatrix" href="#" style="align-items:end; display:block; text-align:right;" data-service="<?= $primexService ?>" data-id="<?= $popupId ?>">+ Tambah Vol Matrix Lain</a>
        <a id="kurangVolMatrix" href="#" style="align-items:end; display:block; text-align:right; color:#DB1010;" data-service="<?= $primexService ?>" data-id="<?= $popupId ?>">- Kurang Vol Matrix Lain</a>
        
        <button data-service='<?= $primexService ?>' data-id='<?= $popupId ?>' class='btn-simpan-vol-matrix-trigger ljr-custom-btn bg-dark-blu mt-2 mb-3'>Simpan Vol Matrix</button>
    </div>

    <button data-service='<?= $primexService ?>' data-id='<?= $popupId ?>' class='btn-simpan-service-trigger ljr-custom-btn bg-dark-blu'>Simpan</button>
</div>