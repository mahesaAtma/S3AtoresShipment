/**
 * Save values inserted in service component
 * 
 * @param {Object} serviceData
 * @param {Boolean} isServiceFilled
 * @param {Object} volMatrixData
 */
function saveServiceComponent(serviceData, isServiceFilled, volMatrixData, allowMultiple = false, saveToDB = false){
    $('.btn-simpan-service-trigger').off('click').click(function() {
        let serviceType = $(this).attr('data-service');
        let serviceID = $(this).attr('data-id');

        let capitalizeServiceType = serviceType.charAt(0).toUpperCase() + serviceType.slice(1);
       
        if (!isServiceFilled[serviceID] || allowMultiple) {
            let serviceComponent = `#component-${serviceType}-service-${serviceID}`;

            
            if (['reguler', 'express', 'primex', 'ltl'].includes(serviceType)) {
                currentVolMatrix = volMatrixData[serviceType + serviceID] ? volMatrixData[serviceType + serviceID] : [];
                isVolMatriChecked = $(serviceComponent + ` input#${serviceType}VolMatrix${serviceID}`).prop('checked');
                
                if (currentVolMatrix.length == 0 && isVolMatriChecked) {
                    popupSwalFireError(['Mohon simpan vol matrix terlebih dahulu!']);
                    return;
                }

                chargeKG = 0;
                for (const item of currentVolMatrix) {
                    chargeKG += parseFloat(item.berat_asli);
                }

                chargeKG = Math.ceil(chargeKG);

                serviceData[serviceID] = {
                    type: serviceType,
                    jumlah_koli: $(serviceComponent).find(`input#${serviceType}InputJumlahKoli`).val(),
                    berat_asli: $(serviceComponent).find(`input#${serviceType}InputBeratAsli`).val(),
                    nama_barang: $(serviceComponent).find(`input#${serviceType}InputNamaBarang`).val(),
                    vol_matrix: currentVolMatrix
                };

                $('h6#jumlahKoliServiceResult' + serviceID + ' span').text(serviceData[serviceID].jumlah_koli);
                $('h6#chargeKGServiceResult' + serviceID + ' span').text(chargeKG);
            }else if (['fcl', 'ftl'].includes(serviceType)) {
                serviceData[serviceID] = {
                    type: serviceType,
                    jumlah_unit: $(serviceComponent).find(`input#${serviceType}InputJumlahUnit`).val(),
                    jumlah_kg: $(serviceComponent).find(`input#${serviceType}InputJumlahKG`).val(),
                    jumlah_kubik: $(serviceComponent).find(`input#${serviceType}InputJumlahKubik`).val(),
                    jumlah_koli: $(serviceComponent).find(`input#${serviceType}InputJumlahKoli`).val() 
                }
            }else if (['lcl'].includes(serviceType)) {
                serviceData[serviceID] = {
                    type: serviceType,
                    panjang: $(serviceComponent).find(`input#${serviceType}InputPanjang`).val(),
                    lebal: $(serviceComponent).find(`input#${serviceType}InputLebar`).val(),
                    tinggi: $(serviceComponent).find(`input#${serviceType}InputTinggi`).val(),
                    nama_barang: $(serviceComponent).find(`input#${serviceType}InputNamaBarang`).val() 
                }
            }

            $('.service-result-wrapper-' + serviceID).addClass('dblock');
            $('#serviceResultMainContent' + serviceID).append(serviceResultComponent(serviceData[serviceID], serviceType));
            
            $('#tipeServiceResult' + serviceID + ' span').text(capitalizeServiceType);

            isServiceFilled[serviceID] = true;   

            if (saveToDB) {
                Swal.fire({
                    title: "Simpan Edit Shipment?",
                    showDenyButton: false,
                    showCancelButton: true,
                    confirmButtonText: "Simpan",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "shipment-master/validation/edit_sttb.php",
                            method: "POST",
                            data: {
                                service_data: serviceData['00'],
                                shipment_detail_id: $('input#shipmentDetailID').val(),
                            },
                            success: function(response){
                                response = JSON.parse(response);
                                
                                if (response.success) {
                                    popupSwalFireSuccess(response.messages);
                                }else{
                                    popupSwalFireError(response.messages);
                                }
                            }
                        });
                    }
                });
            }else{
                popupSwalFireInfo(['Service data berhasil disimpan!']);
            }
        }
    });
}

/**
 * Save value from vol matrix & dimension input for each services
 * !NOTE : used in services files (reguler,express,primex)
 * 
 * @param {Object} volMatrixData 
 * @param {Object} indexVolMatrix 
 */
function saveVolMatrix(volMatrixData, indexVolMatrix) {
    $('.btn-simpan-vol-matrix-trigger').off('click').click(function() {
        let volMatrixType = $(this).attr('data-service');
        let VolMatrixID = $(this).attr('data-id');

        let currentVolMatrixID = indexVolMatrix[volMatrixType + VolMatrixID] ? indexVolMatrix[volMatrixType + VolMatrixID] : 1;
        let currMatrixPositionElement = `.matrix-dimension-${volMatrixType}-input-wrapper-${VolMatrixID}`;

        volMatrixData[volMatrixType + VolMatrixID] = [];

        for (let volMatrixI = 0; volMatrixI < currentVolMatrixID; volMatrixI++) {
            volMatrixData[volMatrixType + VolMatrixID].push({
                dimension: {
                    p:$(currMatrixPositionElement + ` #${volMatrixType}VolMatrixPanjang` + volMatrixI).val(),
                    l:$(currMatrixPositionElement + ` #${volMatrixType}VolMatrixLebar` + volMatrixI).val(),
                    t:$(currMatrixPositionElement + ` #${volMatrixType}VolMatrixTinggi` + volMatrixI).val()
                },
                jumlah_koli: $(currMatrixPositionElement + ` #${volMatrixType}VolMatrixJumlahKoli` + volMatrixI).val(),
                berat_asli: $(currMatrixPositionElement + ` #${volMatrixType}VolMatrixBeratAsli` + volMatrixI).val()
            })
        }

        popupSwalFireInfo(['Vol Matrix data berhasil disimpan!']);
    });
}

/**
 * Append Vol Matrix Component & Save Values
 * 
 * @param {Object} volMatrixData 
 * @param {Object} indexVolMatrix 
 */
function appendVolMatrixComponent(volMatrixData, indexVolMatrix){
    $(".service-input-container > .vol-matrix-checkbox").off('click').click(function () {
        let volMatrixID = $(this).attr('data-id');
        let volMatrixType = $(this).attr('data-service');

        let matrixDimensionPositionID = `.matrix-dimension-${volMatrixType}-input-wrapper-${volMatrixID}`;
        
        indexVolMatrix[volMatrixType + volMatrixID] = 1;

        if ($(`.service-input-container input#${volMatrixType}VolMatrix${volMatrixID}`).prop('checked')) {
            $(matrixDimensionPositionID).addClass('dblock');
        }
        
        if (!$(`.service-input-container input#${volMatrixType}VolMatrix${volMatrixID}`).is(':checked')) {
            $(matrixDimensionPositionID).removeClass('dblock');
        } 
    });
        
    $(".service-input-container #tambahVolMatrix").off('click').click(function() {
        let volMatrixID = $(this).attr('data-id');
        let volMatrixType = $(this).attr('data-service');
        
        let matrixDimensionPositionID = `.matrix-dimension-${volMatrixType}-input-wrapper-${volMatrixID}`;

        if (indexVolMatrix[volMatrixType + volMatrixID] == undefined) {
            indexVolMatrix[volMatrixType + volMatrixID] = 1;
        }

        if ($(`.service-input-container input#${volMatrixType}VolMatrix${volMatrixID}`).is(':checked')) {
            $(`${matrixDimensionPositionID} .matrix-dimension-item-${indexVolMatrix[volMatrixType + volMatrixID] - 1}`).append(
                addComponentVolMatrixService(indexVolMatrix[volMatrixType + volMatrixID], volMatrixType, volMatrixID)
            );
            calculateBeratAsliVolMatrix()
            saveVolMatrix(volMatrixData, indexVolMatrix);
            indexVolMatrix[volMatrixType + volMatrixID] += 1;
        }
    });

    $(".service-input-container #kurangVolMatrix").off('click').click(function() {
        let volMatrixID = $(this).attr('data-id');
        let volMatrixType = $(this).attr('data-service');

        let matrixDimensionPositionID = `.matrix-dimension-${volMatrixType}-input-wrapper-${volMatrixID}`;

        if ($(`.service-input-container input#${volMatrixType}VolMatrix${volMatrixID}`).is(':checked')) {
            if ((indexVolMatrix[volMatrixType + volMatrixID] != undefined) && (indexVolMatrix[volMatrixType + volMatrixID] - 1 > 0)) {
                indexVolMatrix[volMatrixType + volMatrixID] -= 1;
                $(`${matrixDimensionPositionID} .matrix-dimension-item-${indexVolMatrix[volMatrixType + volMatrixID]}`).remove();
            }
        }
    });
}

/**
 * Calculate berat asli in vol matrix
 * @return {Void}
 */
function calculateBeratAsliVolMatrix(){
    $('.matrix-dimension-container input').off('keyup').keyup(function() {
        inputID = $(this).attr('data-id');
        inputService = $(this).attr('data-service');
        inputIncrement = $(this).attr('data-increment');

        let matrixDimensionPositionID = `.matrix-dimension-${inputService}-input-wrapper-${inputID}`;
        
        servicePrice = ['reguler', 'express', 'primex'].includes(inputService) ? 4000 : 6000;

        volMatrixPanjang = parseInt($(`${matrixDimensionPositionID} input#${inputService}VolMatrixPanjang${inputIncrement}`).val());
        volMatrixLebar = parseInt($(`${matrixDimensionPositionID} input#${inputService}VolMatrixLebar${inputIncrement}`).val());
        volMatrixTinggi = parseInt($(`${matrixDimensionPositionID} input#${inputService}VolMatrixTinggi${inputIncrement}`).val());
    
        volMatrixPanjangInt = !isNaN(volMatrixPanjang) ? volMatrixPanjang : 0;
        volMatrixLebarInt = !isNaN(volMatrixLebar) ? volMatrixLebar : 0;
        volMatrixTinggiInt = !isNaN(volMatrixTinggi) ? volMatrixTinggi : 0;

        totalVolumeMatrix = volMatrixPanjangInt * volMatrixLebarInt * volMatrixTinggiInt;
        beratAsli = totalVolumeMatrix / servicePrice;

        $(`${matrixDimensionPositionID}`).find(`input#${inputService}VolMatrixBeratAsli${inputIncrement}`).val(beratAsli);
    });
}

/**
 * Remove generated service result component
 * 
 * @param {Object} serviceData 
 * @param {Object} isServiceFilled 
 * @return {Void}
 */
function removeServiceResultComponent(serviceData, isServiceFilled){
    $('.btn-trash-service-result-trigger').off('click').click(function() {
        trashServiceResultID = $(this).attr('data-id');
        serviceData[trashServiceResultID] = {};

        $('.service-result-wrapper-' + trashServiceResultID).removeClass('dblock');
        $('#serviceResultMainContent' + trashServiceResultID + ' p').remove();
        
        $('#tipeServiceResult' + trashServiceResultID + ' span').text('')
        $('h6#jumlahKoliServiceResult' + trashServiceResultID + ' span').text('-');
        $('h6#chargeKGServiceResult' + trashServiceResultID + ' span').text('-');

        isServiceFilled[trashServiceResultID] = false;
    });
}