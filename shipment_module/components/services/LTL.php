<?php $ltlService = 'ltl'; ?>

<div id="component-<?= $ltlService ?>-service-<?= $popupId ?>" class="service-input-container dnone">
    <h6>Data Koli</h6>
    <input id="<?= $ltlService ?>InputJumlahKoli" class="w-100 border-rounded" type="number" placeholder="Jumlah Koli">
    <input id="<?= $ltlService ?>InputBeratAsli" class="w-100 border-rounded" type="number" placeholder="Berat asli keseluruhan">
    <input id="<?= $ltlService ?>InputNamaBarang" class="nama-barang-input w-100 border-rounded" data-parent="component-<?= $ltlService ?>-service-<?= $popupId ?>" type="text" placeholder="Nama Barang">
    <?php 
        // $parentID = "component-$ltlService-service-$popupId";
        // include('./shipment_module/components/master/product_dropdown.php')
    ?> 

    <div class="vol-matrix-checkbox dflex align-item-c mt-3 mb-3" data-service='<?= $ltlService ?>' data-id="<?= $popupId ?>">
        <input type="checkbox" id="<?= $ltlService ?>VolMatrix<?= $popupId ?>">
        <label class="mt-2 ml-3" for="<?= $ltlService ?>VolMatrix<?= $popupId ?>"> Vol Matrix </label>
    </div>

    <div class="matrix-dimension-<?= $ltlService ?>-input-wrapper-<?= $popupId ?> dnone">
        <div class="matrix-dimension-item-0 matrix-dimension-container">
            <div class="vol-matrix-input dflex">
                <input id="<?= $ltlService ?>VolMatrixJumlahKoli0" type="number" data-id="<?= $popupId ?>" data-service="<?= $ltlService ?>" data-increment="0" placeholder="Jmlh Koli">
                <input id="<?= $ltlService ?>VolMatrixBeratAsli0" type="number" data-id="<?= $popupId ?>" data-service="<?= $ltlService ?>" data-increment="0" placeholder="Berat Asli" disabled>
            </div>
        
            <div class="dimension-input dflex align-item-c">
                <input id="<?= $ltlService ?>VolMatrixPanjang0" type="number" data-id="<?= $popupId ?>" data-service="<?= $ltlService ?>" data-increment="0" placeholder="P"><span> X </span>
                <input id="<?= $ltlService ?>VolMatrixLebar0" type="number" data-id="<?= $popupId ?>" data-service="<?= $ltlService ?>" data-increment="0" placeholder="L"><span> X </span>
                <input id="<?= $ltlService ?>VolMatrixTinggi0" type="number" data-id="<?= $popupId ?>" data-service="<?= $ltlService ?>" data-increment="0" placeholder="T">
            </div>
        </div>
    
        <a id="tambahVolMatrix" href="#" style="align-items:end; display:block; text-align:right;" data-service="<?= $ltlService ?>" data-id="<?= $popupId ?>">+ Tambah Vol Matrix Lain</a>
        <a id="kurangVolMatrix" href="#" style="align-items:end; display:block; text-align:right; color:#DB1010;" data-service="<?= $ltlService ?>" data-id="<?= $popupId ?>">- Kurang Vol Matrix Lain</a>
        
        <button data-service='<?= $ltlService ?>' data-id='<?= $popupId ?>' class='btn-simpan-vol-matrix-trigger ljr-custom-btn bg-dark-blu mt-2 mb-3'>Simpan Vol Matrix</button>
    </div>

    <button data-service='<?= $ltlService ?>' data-id='<?= $popupId ?>' class='btn-simpan-service-trigger ljr-custom-btn bg-dark-blu'>Simpan</button>
</div>