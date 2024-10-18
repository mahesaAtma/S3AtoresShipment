<?php
    $service = 'reguler';
    $popupId = '00';
?>

<style>
    /* Style for each services component */
    #editSttbContainer{
        width: 100%;
        position: relative;
    }

    #editSttbContainer input {
        background-color: #FAFAFA;
        padding: 20px 20px;
        margin: 6px 0;
        width: auto;
        border-radius: 1.5rem;
    }

    #editSttbContainer input:disabled{
        cursor: not-allowed;
        background-color: #e0e0e0;
    }

    #editSttbContainer .vol-matrix-input input{
        width: 50%;
        margin-right: 7px;
    }

    #editSttbContainer .dimension-input input{
        width: 20%;
        margin-right: 7px;
    }

    #editSttbContainer .dimension-input span{
        margin-right: 7px;
        font-weight: 600;
    }
</style>

<div class="main-content">
    <div class="section__content section__content--p0">
        <div id="editSttbContainer" class="container-fluid">
            <?php 
                $shipmentDetail = null;
                $volMatrixes = null;
                if (isset($_GET['shipment_detail_id'])) {
                    $shipmentDetail = $shipmentObj->getShipmentDetailServiceMain($_GET['shipment_detail_id']);
                    $volMatrixes = $shipmentObj->getShipmentDetailServiceMatrix($_GET['shipment_detail_id']);
                }
                
                if ($shipmentDetail) {
            ?>
                    <input id="shipmentDetailID" type="hidden" value="<?= $_GET['shipment_detail_id'] ?>">
                    <h4 class="mb-4">Edit STTB (NO.STTB : <?= $shipmentDetail->no_sttb ?>)</h4>
                    <div class="card">
                        <div class="card-body">
                            <div id="component-<?= $service ?>-service-<?= $popupId ?>" class="service-input-container">
                                <h6>Data Koli</h6>
                                <input id="<?= $service ?>InputJumlahKoli" class="w-100 border-rounded" type="number" placeholder="Jumlah Koli" value="<?= $shipmentDetail->jml_koli ?>">
                                <input id="<?= $service ?>InputBeratAsli" class="w-100 border-rounded" type="number" placeholder="Berat asli keseluruhan" value="<?= $shipmentDetail->berat_asli ?>">
                                <input id="<?= $service ?>InputNamaBarang" class="nama-barang-input w-100 border-rounded" data-parent="component-<?= $service ?>-service-<?= $popupId ?>" type="text" placeholder="Nama Barang" value="<?= $shipmentDetail->nama_barang ?>">
                                <?php 
                                    $parentID = "component-$service-service-$popupId";
                                    include('./shipment_module/components/master/product_dropdown.php')
                                ?> 

                                <?php
                                    if (!$volMatrixes || count($volMatrixes) == 0) {
                                ?>
                                        <div class="vol-matrix-checkbox dflex align-item-c mt-3 mb-3" data-service='<?= $service ?>' data-id="<?= $popupId ?>">
                                            <input type="checkbox" id="<?= $service ?>VolMatrix<?= $popupId ?>">
                                            <label class="mt-2 ml-3" for="<?= $service ?>VolMatrix<?= $popupId ?>"> Vol Matrix </label>
                                        </div>

                                        <div class="matrix-dimension-<?= $service ?>-input-wrapper-<?= $popupId ?> dnone">
                                            <div class="matrix-dimension-item-0 matrix-dimension-container">
                                                <div class="vol-matrix-input dflex">
                                                    <input id="<?= $service ?>VolMatrixJumlahKoli0" type="number" data-id="<?= $popupId ?>" data-service="<?= $service ?>" data-increment="0" placeholder="Jmlh Koli">
                                                    <input id="<?= $service ?>VolMatrixBeratAsli0" type="number" data-id="<?= $popupId ?>" data-service="<?= $service ?>" data-increment="0" placeholder="Berat Asli" disabled>
                                                </div>
                                            
                                                <div class="dimension-input dflex align-item-c">
                                                    <input id="<?= $service ?>VolMatrixPanjang0" type="number" data-id="<?= $popupId ?>" data-service="<?= $service ?>" data-increment="0" placeholder="P"><span> X </span>
                                                    <input id="<?= $service ?>VolMatrixLebar0" type="number" data-id="<?= $popupId ?>" data-service="<?= $service ?>" data-increment="0" placeholder="L"><span> X </span>
                                                    <input id="<?= $service ?>VolMatrixTinggi0" type="number" data-id="<?= $popupId ?>" data-service="<?= $service ?>" data-increment="0" placeholder="T">
                                                </div>
                                            </div>
                                        
                                            <a id="tambahVolMatrix" href="#" style="align-items:end; display:block; text-align:right;" data-service="<?= $service ?>" data-id="<?= $popupId ?>">+ Tambah Vol Matrix Lain</a>
                                            <a id="kurangVolMatrix" href="#" style="align-items:end; display:block; text-align:right; color:#DB1010;" data-service="<?= $service ?>" data-id="<?= $popupId ?>">- Kurang Vol Matrix Lain</a>
                        
                                            <button data-service='<?= $service ?>' data-id='<?= $popupId ?>' class='btn-simpan-vol-matrix-trigger ljr-custom-btn bg-dark-blu mt-2 mb-3'>Simpan Vol Matrix</button>
                                        </div>
                                    <?php
                                    }else{
                                ?>
                                        <div class="vol-matrix-checkbox dflex align-item-c mt-3 mb-3" data-service='<?= $service ?>' data-id="<?= $popupId ?>">
                                            <input type="checkbox" id="<?= $service ?>VolMatrix<?= $popupId ?>" checked>
                                            <label class="mt-2 ml-3" for="<?= $service ?>VolMatrix<?= $popupId ?>"> Vol Matrix </label>
                                        </div>

                                        <div class="matrix-dimension-<?= $service ?>-input-wrapper-<?= $popupId ?> mt-2 mb-2">
                                            <?php
                                                foreach ($volMatrixes as $index => $volMatrix) {
                                                    $matrixDimension = explode('x', $volMatrix['volume_desc']);
                                                    $panjang = $matrixDimension[0];
                                                    $lebar = $matrixDimension[1];
                                                    $tinggi = explode(' ', $matrixDimension[2])[0];
                                            ?>
                                                <div class="matrix-dimension-item-0 matrix-dimension-container mt-3">
                                                    <div class="vol-matrix-input dflex">
                                                        <input id="<?= $service ?>VolMatrixJumlahKoli<?= $index ?>" type="number" value="<?= $volMatrix['jml_koli'] ?>" data-id="<?= $popupId ?>" data-service="<?= $service ?>" data-increment="<?= $index ?>" placeholder="Jmlh Koli">
                                                        <input id="<?= $service ?>VolMatrixBeratAsli<?= $index ?>" type="number" value="<?= $volMatrix['berat_volume'] ?>" data-id="<?= $popupId ?>" data-service="<?= $service ?>" data-increment="<?= $index ?>" placeholder="Berat Asli" disabled>
                                                    </div>
                                                
                                                    <div class="dimension-input dflex align-item-c">
                                                        <input id="<?= $service ?>VolMatrixPanjang<?= $index ?>" type="number" value="<?= $panjang ?>" data-id="<?= $popupId ?>" data-service="<?= $service ?>" data-increment="<?= $index ?>" placeholder="P"><span> X </span>
                                                        <input id="<?= $service ?>VolMatrixLebar<?= $index ?>" type="number" value="<?= $lebar ?>" data-id="<?= $popupId ?>" data-service="<?= $service ?>" data-increment="<?= $index ?>" placeholder="L"><span> X </span>
                                                        <input id="<?= $service ?>VolMatrixTinggi<?= $index ?>" type="number" value="<?= $tinggi ?>" data-id="<?= $popupId ?>" data-service="<?= $service ?>" data-increment="<?= $index ?>" placeholder="T">
                                                    </div>
                                                </div>
                                            
                                                
                                            <?php
                                                }
                                            ?>

                                            <a id="tambahVolMatrix" href="#" style="align-items:end; display:block; text-align:right;" data-service="<?= $service ?>" data-id="<?= $popupId ?>">+ Tambah Vol Matrix Lain</a>
                                            <a id="kurangVolMatrix" href="#" style="align-items:end; display:block; text-align:right; color:#DB1010;" data-service="<?= $service ?>" data-id="<?= $popupId ?>">- Kurang Vol Matrix Lain</a>
                                            <button data-service='<?= $service ?>' data-id='<?= $popupId ?>' class='btn-simpan-vol-matrix-trigger ljr-custom-btn bg-dark-blu mt-3 mb-3'>Simpan Vol Matrix</button>
                                        </div>
                                <?php
                                    }
                                ?>

                                <button data-service='<?= $service ?>' data-id='<?= $popupId ?>' class='btn-simpan-service-trigger ljr-custom-btn bg-dark-blu'>Simpan</button>
                            </div>
                        </div>
                    </div>
            <?php
                }else {
                    include('./shipment_module/components/go_back_page.php');
                }
            ?>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 
<script>
    $(document).ready(function(){
        volMatrixData = {};
        indexVolMatrix = {};

        serviceData = {};
        isServiceFilled = {};

        saveVolMatrix(volMatrixData, indexVolMatrix);
        appendVolMatrixComponent(volMatrixData, indexVolMatrix);
        
        calculateBeratAsliVolMatrix();

        saveServiceComponent(serviceData, isServiceFilled, volMatrixData, true, true);
    });
</script>