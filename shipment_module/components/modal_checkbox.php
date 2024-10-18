<style>
    .modal-checkbox{
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: white;
        border-radius: 15px;
        padding: 15px 20px;
        margin: 12px 0;
    }

    .modal-checkbox .red-circle{
        width: 8px;
        height: 8px;
        background-color: #FF7675;
        border-radius: 50%;
    }

    .modal-document-wrapper{
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-checkbox span{
        color: #0066FF;
        font-weight: 600;
        padding: 7px 30px;
        border-radius: 20px;
        font-size: 12px;
    }

    .modal-checkbox input{
        margin: 0px 20px 0px 0px;
        width: 25px;
        height: 25px;
    }

    @media only screen and (max-width: 600px) {
        .modal-checkbox{
            padding: 15px 5px;
        }

        .modal-document-wrapper h6{
            font-size: 12px;
        }

        .modal-checkbox span{
            padding: 4px 10px;
            font-size: 10px;
        }

        .modal-checkbox input{
            width: 15px;
            height: 15px;
        }
    }
</style>

<h6>Checklist dokumen jika dokumen tersedia</h6>
<?php
    if (isset($documents) && count($documents) > 0) {
        foreach ($documents as $document) {
?>
            <div class="modal-checkbox document-<?= $document['nomor'] ?>-container">
                <div class="modal-document-wrapper">
                    <div class="red-circle ml-3"></div>
                    <div class="modal-text ml-4">
                        <input id="documentIdFlag<?= $popupId ?>" type="hidden" value="<?= $document['id'] ?>">
                        <h6 class="document-name-flag-<?= $popupId ?>" style="font-weight: 700;"><?= $document['name'] ?></h6>
                        <h6 class="document-nomor-flag-<?= $popupId ?>" style="font-weight: 600; color:rgba(0, 0, 0, 0.53);"><?= $document['nomor'] ?></h6>
                    </div>
                </div>
                <span class="regular-box-shadow-with-thin-border">WAJIB</span>
                <input class="document-wajib-flag-<?= $popupId ?> box-shadow-with-thin-border" type="checkbox">
            </div>
<?php
        }
    } else{
        echo "<span style='color:red;'>Switch item is not defined</span>";
    }
    
?>