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

<div id="component-fcl-service-<?= $popupId ?>" class="service-input-container dnone">
    <h6>Data Barang</h6>
    <input id="fclInputJumlahUnit" class="w-100 border-rounded" type="number" placeholder="Jumlah Unit">
    <input id="fclInputJumlahKG" class="w-100 border-rounded" type="number" placeholder="Jumlah KG">
    <input id="fclInputJumlahKubik" class="w-100 border-rounded" type="number" placeholder="Jumlah Kubik">
    <input id="fclInputJumlahKoli" class="w-100 border-rounded" type="number" placeholder="Jumlah Koli">
    
    <button data-service='fcl' data-id='<?= $popupId ?>' class='btn-simpan-service-trigger ljr-custom-btn bg-dark-blu mt-3'>Simpan</button>
</div>