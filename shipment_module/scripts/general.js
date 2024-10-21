/**
 * Show popup from swal fire when success
 * 
 * @param {Array} messages 
 * @param {Boolean} redirect 
 * @return {Void}
 */
function popupSwalFireSuccess(messages, redirect = true){
    let successMessages = messages.map((itm) => `<p class='success-message-shipment'>${itm}</p>`).join('');
    Swal.fire({
        title: "Saved!",
        icon: "success",
        html: successMessages,
        showCloseButton: true,
        cancelButtonText: `OK`,
    }).then(function(){
        if (redirect) {
            window.location.reload();
        }
    });
}

/**
 * Show popup from swal fire when success
 * 
 * @param {Array} messages 
 * @return {Void}
 */
function popupSwalFireError(messages){
    let errorMessages = messages.map((itm) => `<p class='error-message-shipment'>${itm}</p>`).join('');
    Swal.fire({
        title: "Oops...",
        icon: "error",
        html: errorMessages,
        showCloseButton: true,
        cancelButtonText: `OK`,
    });
}

/**
 * Show popup from swal fire when info
 * 
 * @param {Array} messages 
 * @return {Void}
 */
function popupSwalFireInfo(messages){
    let infoMessages = messages.map((itm) => `<p class='info-message-shipment'>${itm}</p>`).join('');
    Swal.fire({
        title: "Saved",
        icon: "info",
        html: infoMessages,
        showCloseButton: true,
        cancelButtonText: `OK`,
    });
}

/**
 * Trigger when enter pressed in input sttb fisik
 */
function sttbPressEnter(){
    $('.input-no-sttb').off('keypress').on('keypress', function(e) {
        if (e.which == '13') {
            sttbType = $(this).attr('data-sttb-type');
            noSTTB = $(this).val();

            if (sttbType === '') {
                popupSwalFireInfo(['Mohon pilih tipe input sttb terlebih dahulu!'])
            }else if (noSTTB === '') {
                popupSwalFireInfo(['Nomor STTB tidak boleh kosong!'])
            }else{
                $.ajax({
                    url: "shipment-master/validation/validate-sttb.php",
                    method: "POST",
                    data: {no_sttb: noSTTB, sttb_input_type: sttbType},
                    success: function(response){
                        response = JSON.parse(response);
                        
                        if (response.success) {
                            popupSwalFireSuccess(response.messages, false);
                        }else{
                            popupSwalFireError(response.messages);
                        }
                    }
                });
            }
        }
    });
}