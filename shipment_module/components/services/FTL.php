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

<div id="component-ftl-service-<?= $popupId ?>" class="service-input-container dnone">
    <h6>Data Barang</h6>
    <input id="ftlInputJumlahUnit" class="w-100 border-rounded" type="number" placeholder="Jumlah Unit">
    <input id="ftlInputJumlahKG" class="w-100 border-rounded" type="number" placeholder="Jumlah KG">
    <input id="ftlInputJumlahKubik" class="w-100 border-rounded" type="number" placeholder="Jumlah Kubik">
    <input id="ftlInputJumlahKoli" class="w-100 border-rounded" type="number" placeholder="Jumlah Koli">
    
    <button data-service='ftl' data-id='<?= $popupId ?>' class='btn-simpan-service-trigger ljr-custom-btn bg-dark-blu mt-3'>Simpan</button>
</div>