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
 * Make delay after any input
 * Eg: keyup, change, etc
 * 
 * @param {Integer} ms 
 * @returns {Function}
 */
function makeDelay(ms) {
    var timer = 0;
    return function(callback){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
};