<style>
    .service-input-container{
        width: 100%;
    }

    .service-input-container input {
        background-color: white;
        padding: 10px 20px;
        margin: 6px 0;
        width: auto;
    }
</style>

<div id="component-lcl-service-<?= $popupId ?>" class="service-input-container dnone">
    <h6 class="ml-3">Ukuran PxLxT</h6>
    <div class="dflex align-item-c justify-content-c w-100">
        <input id="lclInputPanjang" class="w-100 border-rounded mr-2" type="number" placeholder="P">
        <input id="lclInputLebar" class="w-100 border-rounded mr-2" type="number" placeholder="L">
        <input id="lclInputTinggi" class="w-100 border-rounded" type="number" placeholder="T">
    </div>

    <input id="lclInputNamaBarang" class="nama-barang-input w-100 border-rounded" data-parent="component-lcl-service-<?= $popupId ?>" type="text" placeholder="Nama Barang">
    <?php 
        // $parentID = "component-lcl-service-$popupId";
        // include('./shipment_module/components/master/product_dropdown.php')
    ?>

    <button data-service='lcl' data-id='<?= $popupId ?>' class='btn-simpan-service-trigger ljr-custom-btn bg-dark-blu mt-3'>Simpan</button>
</div>