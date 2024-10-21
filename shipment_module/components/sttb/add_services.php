<style>
    .add-service{
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 20px;
        border: 1px solid rgba(144,146,148,0.7);
        border-radius: 10px;
    }

    .add-service i{
        font-size: 20px;
        cursor: pointer;
        transition: 0.3s ease-in-out;
    }

    .add-service i:hover{
        opacity: 0.7;
    }

    .add-service span{
        font-weight: 700;
    }

    .collapse-service{
        background-color: #EBEBEB;
        padding: 20px;
    }
    
    .service-list{
        margin: 10px 0;
        padding: 20px 30px;
        background-color: rgba(196,224,227,0.6);
        border-radius: 10px;
    }

    .service-list p{
        line-height: 7px;
    }

    .add-service-popup{
        display: none;
    }

    /* Style for each services component */
    .service-input-container{
        width: 100%;
        position: relative;
    }

    .service-input-container input {
        background-color: #FAFAFA;
        padding: 10px 20px;
        margin: 6px 0;
        width: auto;
    }

    .service-input-container input:disabled{
        cursor: not-allowed;
        background-color: #e0e0e0;
    }

    .service-input-container .vol-matrix-input input{
        width: 50%;
        margin-right: 7px;
    }

    .service-input-container .dimension-input input{
        width: 20%;
        margin-right: 7px;
    }

    .service-input-container .dimension-input span{
        margin-right: 7px;
        font-weight: 600;
    }

    .service-input-container a{
        margin: 10px 10px 10px auto;
    }

    .matrix-dimension-input{
        display: none;
    }
    
    @media only screen and (max-width: 600px) {
        .add-service{
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            border: 1px solid rgba(144,146,148,0.7);
            border-radius: 10px;
        }

        .add-service i{
            font-size: 20px;
        }

        .add-service i:hover{
            opacity: 0.7;
        }

        .add-service span{
            font-weight: 700;
        }

        .collapse-service{
            padding: 20px;
        }
        
        .service-list{
            margin: 10px 0;
            padding: 20px 30px;
            background-color: rgba(196,224,227,0.6);
            border-radius: 10px;
        }

        .service-list p{
            line-height: 7px;
        }

        .add-service-popup{
            display: none;
        }

        /* Style for each services component */
        .service-input-container{
            width: 100%;
            position: relative;
        }

        .service-input-container input {
            background-color: #FAFAFA;
            padding: 10px 20px;
            margin: 6px 0;
            width: auto;
        }

        .service-input-container .vol-matrix-input input{
            width: 50%;
            margin-right: 7px;
        }

        .service-input-container .dimension-input input{
            width: 40%;
            margin-right: 7px;
        }

        .service-input-container .dimension-input span{
            margin-right: 7px;
            font-weight: 600;
        }

        .service-input-container a{
            margin: 10px 10px 10px auto;
        }

        .matrix-dimension-input{
            display: none;
        }
    }
</style>

<div class="add-service-popup add-service-popup-wrapper-<?= $popupId ?>">
    <h6 style="color:#555555;"><i class="fas fa-truck mr-2"></i> SERVICE</h6> 
    <hr class="line-boundaries">
    <div class="card">
        <div class="card-body">
            <div class="add-service">
                <span>Pilih Service</span>
                <i class="fa fa-plus-circle"  id="collapseService<?= $popupId ?>" data-toggle="collapse" data-target="#serviceCollapse<?= $popupId ?>" aria-expanded="true" aria-controls="serviceCollapse<?= $popupId ?>"></i>
            </div>
    
            <div id="serviceCollapse<?= $popupId ?>" class="collapse-service collapse" aria-labelledby="collapseService<?= $popupId ?>" data-parent="#accordionExample">
                <div class="">
                    <?php 
                        $dropdownTitle = "Pilih Service";
                        $dropdownChildrens = ["Reguler","Express","LTL","Primex","FCL","FTL","LCL"];
                        include("./shipment_module/components/service_dropdown.php") 
                    ?>
                </div>
            </div>
    
            <div class="service-list dnone service-result-wrapper-<?= $popupId ?>">
                <h6>Informasi Shipment</h6>
                <div class="dflex justify-content-sb mt-4">
                    <div class="dflex align-item-s">
                        <i class="fas fa-box mr-3"></i>
                        <div id="serviceResultMainContent<?= $popupId ?>" style="list-style-type: none;"></div>
                    </div>
                    <i class="btn-trash-service-result-trigger fa fa-trash mt-4 mr-3" data-id="<?= $popupId ?>" style="color:red; cursor:pointer;"></i>
                </div>
    
                <hr class="line-boundaries"/>
                <div>
                    <p id="tipeServiceResult<?= $popupId ?>"><span>Reguler</span> Service</p>
                    <div class="dflex align-item-c justify-content-sb">
                        <h7 style="font-weight: 500;">Jumlah Koli</h7>
                        <h6 id="jumlahKoliServiceResult<?= $popupId ?>" style="font-weight: 700;"><span>-</span> Koli</h6>
                    </div>
                    <div class="dflex align-item-c justify-content-sb">
                        <h7 style="font-weight: 500;">Charge KG</h7>
                        <h6 id="chargeKGServiceResult<?= $popupId ?>" style="font-weight: 700;"><span>-</span> KG</h6>
                    </div>
                </div>
            </div>
    
            <?php 
                $modalSwitchItem = 'Prioritas pengiriman (shipment informasi)';
                include("./shipment_module/components/modal_switch_calendar.php");
                $modalSwitchItem = 'Apakah ada barang susulan?';
                include("./shipment_module/components/modal_switch.php");
            ?>
    
            <button id="<?= $popupId ?>" class="btn-add-service-next-trigger ljr-custom-btn bg-dark-blue mt-4" style="border-radius: 10px;">Berikutnya</button>
            <button id="<?= $popupId ?>" class="btn-add-service-previous-trigger ljr-custom-btn bg-dark-red mt-4" style="border-radius: 10px;">Sebelumnya</button>
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

        saveServiceComponent(serviceData, isServiceFilled, volMatrixData);
        saveVolMatrix(volMatrixData, indexVolMatrix);
        appendVolMatrixComponent(volMatrixData, indexVolMatrix);
        removeServiceResultComponent(serviceData, isServiceFilled);
        calculateBeratAsliVolMatrix();

        $('.btn-add-service-next-trigger').click(function(){
            serviceNextIndex = $(this).attr('id');
            $('.add-service-popup-wrapper-' + serviceNextIndex).css("display","none");
            $('.add-document-popup-wrapper-' + serviceNextIndex).css("display","inline-block");
        });

        $('.btn-add-service-previous-trigger').click(function(){
            servicePreviousIndex = $(this).attr('id');
            $('.add-service-popup-wrapper-' + servicePreviousIndex).css("display","none");
            $('.entry-sttb-popup-wrapper-' + servicePreviousIndex).css("display","inline-block");
        });
    });
</script>