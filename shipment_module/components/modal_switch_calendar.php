<style>
    .modal-switch-calendar .switch {
        position: relative;
        display: inline-block;
        width: 53px;
        height: 20px;
    }

    .modal-switch-calendar .switch input { 
        opacity: 0;
        width: 0px;
        height: 0px; 
    }

    .modal-switch-calendar .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgb(204, 204, 204);
        -webkit-transition: .4s;
        transition: .4s;
    }

    .modal-switch-calendar .slider:before {
        position: absolute;
        content: "";
        height: 28px;
        width: 28px;
        left: 0px;
        bottom: -4px;
        -webkit-transition: .4s;
        transition: .4s;
        background-color: white;
        border: 1px solid rgba(144,146,148,0.5);
        box-shadow: 4px 8px 6px -1px rgba(0,0,0,0.1);
        -webkit-box-shadow: 4px 8px 6px -1px rgba(0,0,0,0.1);
        -moz-box-shadow: 4px 8px 6px -1px rgba(0,0,0,0.1);
    }

    .modal-switch-calendar input:checked + .slider {
        background-color: rgb(161,160,224);
    }

    .modal-switch-calendar input:focus + .slider {
        box-shadow: 0 0 1px rgb(161,160,224);
    }

    .modal-switch-calendar input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
        background-color: rgb(88,86,214);
    }

    /* Rounded sliders */
    .modal-switch-calendar .slider.round {
        border-radius: 34px;
    }

    .modal-switch-calendar .slider.round:before {
        border-radius: 50%;
    }

    .modal-switch-calendar{
        margin-top: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 25px 20px 15px 20px;
        background-color: white;
        border-radius: 10px;
    }

    input[type="datetime-local"] {
        width: 100%;
        margin-top: 15px;
        padding: 20px 20px 20px 20px;
        border-radius: 10px;
        display: none;
    }
    
    @media only screen and (max-width: 600px) {
        .modal-switch-calendar h6{
            font-size: 13px;
        }

        .modal-switch-calendar .switch {
            width: 40px;
            height: 16px;
        }

        .modal-switch-calendar .slider:before {
            height: 22px;
            width: 22px;
            bottom: -2px;
        }

        .modal-switch-calendar input:checked + .slider:before {
            -webkit-transform: translateX(18px);
            -ms-transform: translateX(18px);
            transform: translateX(18px);
        }
    }
</style>

<?php
    /**
     * Due to jquery unable to receive any symbols in select function
     * All symbol are replaced with empty quote
     */
    if (isset($modalSwitchItem) && is_string($modalSwitchItem)) {
        $switchName = preg_replace("/[()?]/i", '', $modalSwitchItem);
        $itemClass = implode("-",explode(" ", strtolower($switchName)));
        $itemID = lcfirst(implode("",explode(" ", $switchName)));
?>
    <div class="<?= $itemClass ?>-modal modal-switch-calendar box-shadow-with-thin-border">
        <h6> <?= $modalSwitchItem ?> </h6>    
    
        <label class="<?= $itemClass ?>-switch switch">
            <input id="<?= $itemID ?>Input_<?= $popupId ?>" class="<?= $itemClass ?>-checkbox-input" data-id="<?= $popupId ?>" type="checkbox">
            <span class="slider round"></span>
        </label>
    </div>
    <input id="modalSwitchCalendar<?= $popupId ?>" class="box-shadow-with-thin-border" type="datetime-local">
<?php
    }else{
        echo "<span style='color:red;'>Switch item is not defined</span>";
    }
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $('.prioritas-pengiriman-shipment-informasi-checkbox-input').off('change').change(function(){
        calendarID = $(this).attr('data-id');
        
        if ($(this).prop('checked')) {
            $('input#modalSwitchCalendar' + calendarID).addClass('dline-block');
        }else{
            $('input#modalSwitchCalendar' + calendarID).removeClass('dline-block');
        }
    });
</script>

