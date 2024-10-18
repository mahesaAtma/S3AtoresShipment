<style>
    .dropdown{
        position: relative;
    }

    .dropdown .dropbtn {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        cursor: pointer;
        color: black;
        border-radius: 10px;
        padding: 14px 16px;
        margin: 10px 0;
        outline: none;
        background-color: white;
        font-weight: 700;
    }

    .service-dropdown-content {
        display: none;
        background-color: #f9f9f9;
        position: absolute;
        width: 100%;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 4;
    }

    .service-dropdown-content button {
        color: black;
        padding: 12px 16px;
        display: block;
        text-align: left;
        width: 100%;
        transition: 0.3s ease-in-out;
    }

    .service-dropdown-content button:hover {
        background-color: #ddd;
    }

    .service-content{
        padding: 20px;
        width: 100%;
    }
    
    @media only screen and (max-width: 600px) {
        .matrix-dimension-container > div{
            display: block !important;
        }

        .matrix-dimension-container input{
            width: 100% !important;
            display: block;
            border-radius: 10px;
            margin-top: 10px;
        }

        .matrix-dimension-container span{
            display: none;
        }
    }
</style>

<?php if(isset($dropdownTitle) && isset($dropdownChildrens)) {?>

<div class="dropdown">
    <button class="dropbtn box-shadow-with-thin-border btn-service-dropdown-trigger" id="serviceDropdownBtn_<?= $popupId ?>"><span><?php echo $dropdownTitle ?></span> <i class="fas fa-caret-down"></i></button>
    <div class="service-dropdown-content" id="serviceDropdownContent_<?= $popupId ?>">
        <?php foreach ($dropdownChildrens as $item) {
            echo "<button id='$popupId' type='button' value='$item'> $item </button>";
        } ?>
    </div>
</div>
    
<div class="service-content">
    <?php include('./shipment_module/components/services/REGULER.php') ?>
    <?php include('./shipment_module/components/services/EXPRESS.php') ?>
    <?php include('./shipment_module/components/services/PRIMEX.php') ?>
    <?php include('./shipment_module/components/services/LTL.php') ?>
    <?php include('./shipment_module/components/services/FTL.php') ?>
    <?php include('./shipment_module/components/services/FCL.php') ?>
    <?php include('./shipment_module/components/services/LCL.php') ?>
</div>

<?php }else{
    echo "<span style='color:red;'>Title or Children is not defined</span>";
}?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    currentServiceChoice = 'reguler';

    $(document).ready(function(){
        $(".btn-service-dropdown-trigger").off('click').on('click', function(){
            let dropdownServiceID = $(this).attr('id').split('_')[1];
            $('#serviceDropdownContent_' + dropdownServiceID).toggleClass('dblock');
        });

        $('.service-dropdown-content > button').click(function(){
            let dropdownContentID = $(this).attr('id');
            
            $('#serviceDropdownContent_' + dropdownContentID).removeClass('dblock');
            $(`#component-${currentServiceChoice}-service-${dropdownContentID} input`).val('');
            $(`#component-${currentServiceChoice}-service-${dropdownContentID}`).removeClass('dblock');

            currentServiceChoice = ($(this).val()).toLowerCase();

            dropdownText = currentServiceChoice.slice(0,1).toUpperCase() + currentServiceChoice.slice(1,currentServiceChoice.length);
            $(`#serviceDropdownBtn_${dropdownContentID} span`).text(dropdownText);
            $(`#component-${currentServiceChoice}-service-${dropdownContentID}`).addClass('dblock');
        })
    });
</script>
