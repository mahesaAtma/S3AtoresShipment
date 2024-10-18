<style>
    .qr-code-scanner-black-cover{
        position: absolute;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 3;
        top: 0;
        left: 0;
    }

	.qr-scan-container {
		width: 100%;
		max-width: 500px;
        max-height: 400px;
		margin: 5px;
		position: absolute;
		transform: translate(-50%, -50%);
		left: 50%;
		top: 40%;
	}

	.qr-scan-section {
		background-color: #ffffff;
		padding: 50px 30px;
		border: 1.5px solid #b2b2b2;
		border-radius: 0.25em;
		box-shadow: 0 20px 25px rgba(0, 0, 0, 0.25);
	}

    .qr-scan-section i{
        position: absolute;
        right: 20px;
        top: 20px;
        cursor: pointer;
    }

	.qr-scan-container #shipment-qr-code-reader img[alt="Info icon"] {
		display: none;
	}

	.qr-scan-container button {
		padding: 10px 20px;
		outline: none;
		border-radius: 0.25em;
		color: white;
		font-size: 15px;
		cursor: pointer;
		margin-top: 15px;
		margin-bottom: 10px;
        background-color: #2C4074;
		transition: 0.3s background-color;
	}

	.qr-scan-container button:hover {
        background-color: #3c58a1;
	}

	.qr-scan-container video {
		width: 100% !important;
        max-height: 300px;
		border: 1px solid #b2b2b2 !important;
		border-radius: 0.25em;
	}
</style>

<div class="qr-code-scanner-black-cover dnone">
    <div class="qr-scan-container">
        <div class="qr-scan-section">
            <i id="closeShipmentScanQRCode<?= $popupId ?>" class="fas fa-remove btn-remove-scan-qr-code-popup"></i>
            <div id="shipment-qr-code-reader"></div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    let qrCodeScanner = null;

    $(document).ready(function() {
        $('.btn-remove-scan-qr-code-popup').click(function() {
            console.log('adasdasdas');
            
            $('.qr-code-scanner-black-cover').removeClass('dblock');
            if (qrCodeScanner) {
                qrCodeScanner.clear();
                qrCodeScanner = null;
            }
        });
    });

    /**
     * QR CODE Functionality depends on https://unpkg.com/html5-qrcode
     * CAREFULL! If the link is broken or deprecated or changed
     * 
     * @param {String} elToAppend | Element location (id/class) to append
     */
    function shipmentScanQRCode(elToAppend) {
        function onScanSuccess(decodeText, decodeResult) {        
            $.ajax({
                url: "shipment-master/validation/no_sttb.php",
                method: "POST",
                data: {no_sttb: decodeText},
                success: function(response){
                    response = JSON.parse(response);
                    if (response.success) {
                        $(elToAppend).val(decodeText);
                        qrCodeScanner.clear();
                        $('.qr-code-scanner-black-cover').removeClass('dblock');
                        popupSwalFireSuccess(response.messages, false);
                    }else{
                        qrCodeScanner.clear();
                        $('.qr-code-scanner-black-cover').removeClass('dblock');
                        popupSwalFireError(response.messages);
                    }
                }
            });
        }

        if (qrCodeScanner == null) {
            qrCodeScanner = new Html5QrcodeScanner("shipment-qr-code-reader",
                { fps: 5, qrbos: 250 }
            );
    
            qrCodeScanner.render(onScanSuccess);
        }
    };
</script>