PACKING_STANDARD = '.packing-standard-wrapper-';
PACKING_SPECIAL = '.packing-special-wrapper-';

INPUT_PACKING_STANDARD = '.packing-standard-checkbox-input';
INPUT_PACKING_SPECIAL = '.packing-special-checkbox-input';

ID_INPUT_PACKING_STANDARD = '#packingStandardInput_';
ID_INPUT_PACKING_SPECIAL = '#packingSpecialInput_';

PACKING_STANDARD_VALUE = 700000
PACKING_SPECIAL_VALUE = 1500000

/**
 * Switch on change action for packing standard
 * 
 * @param {Object} isPackingStandardChecked 
 * @return {Void}
 */
function switchActionPackingStandard(isPackingStandardChecked) {
    $(INPUT_PACKING_STANDARD).off('change').change(function(){
        packingID = $(this).attr('id').split('_')[1];
        isPackingStandardChecked[packingID] = $(this).prop('checked');
        
        if (isPackingStandardChecked[packingID]) {
            $(PACKING_STANDARD + packingID).addClass('dblock');
            $(PACKING_SPECIAL + packingID).removeClass('dblock');
            $(ID_INPUT_PACKING_SPECIAL + packingID).prop('checked', false);
        }else{
            $(PACKING_STANDARD + packingID).removeClass('dblock');
        }
    });
}

/**
 * Switch on change action for packing special  
 * 
 * @param {Object} isPackingStandardChecked 
 * @return {Void}
 */
function switchActionPackingSpecial(isPackingSpecialChecked) {
    $(INPUT_PACKING_SPECIAL).off('change').change(function(){
        packingID = $(this).attr('id').split('_')[1];
        isPackingSpecialChecked[packingID] = $(this).prop('checked');

        if (isPackingSpecialChecked[packingID]) {
            $(PACKING_SPECIAL + packingID).addClass('dblock');
            $(PACKING_STANDARD + packingID).removeClass('dblock');
            $(ID_INPUT_PACKING_STANDARD + packingID).prop('checked', false);
        }else{
            $(PACKING_SPECIAL + packingID).removeClass('dblock');
        }
    });
}

/**
 * Save packing standard input values
 * 
 * @param {Object} packingData 
 * @param {Object} isExtraPackingFilled 
 * @param {Object} isPackingStandardChecked 
 */
function savePackingStandard(packingData, isExtraPackingFilled, isPackingStandardChecked) {
    $('.btn-packing-standard-trigger').off('click').click(function () {
        let packingStandardID = $(this).attr('id');

        let standardInputClass = PACKING_STANDARD + packingStandardID;

        if (isPackingStandardChecked[packingStandardID] && (isExtraPackingFilled[packingStandardID] === undefined || isExtraPackingFilled[packingStandardID] === false)) {
            packingData[packingStandardID] = {
                jenis_packing : 'standart',
                dimensi: savePackingDimensions(packingStandardID ,'standard'),
                harga_packing : $(standardInputClass).find('#standardHargaPacking').val(),
                berat_packing : $(standardInputClass).find('#standardBeratPacking').val(),
            };

            $('.extra-packing-result-wrapper-' + packingStandardID).addClass('dblock');
            $('.extra-packing-result-wrapper-' + packingStandardID + ' div#extraPackingResultList').append(extraPackingResultComponent(packingData[packingStandardID]));
            
            isExtraPackingFilled[packingStandardID] = true

            popupSwalFireInfo(['Packing data standard berhasil disimpan!']);
        }
    })
}

/**
 * Save packing special input values
 * 
 * @param {Object} packingData 
 * @param {Object} isExtraPackingFilled 
 * @param {Boolean} isPackingSpecialChecked 
 */
function savePackingSpecial(packingData, isExtraPackingFilled, isPackingSpecialChecked) {
    $('.btn-packing-special-trigger').off('click').click(function(){
        packingSpecialID = $(this).attr('id');

        specialInputClass = PACKING_SPECIAL + packingSpecialID;

        if (isPackingSpecialChecked[packingSpecialID]  && (isExtraPackingFilled[packingSpecialID] === undefined || isExtraPackingFilled[packingSpecialID] === false)) {
            packingData[packingSpecialID] = {
                jenis_packing : 'special',
                dimensi: savePackingDimensions(packingSpecialID ,'special'),
                harga_packing : $(specialInputClass).find('#specialHargaPacking').val(),
                berat_packing : $(specialInputClass).find('#specialBeratPacking').val(),
            };

            $('.extra-packing-result-wrapper-' + packingSpecialID).addClass('dblock');
            $('.extra-packing-result-wrapper-' + packingSpecialID + ' div#extraPackingResultList').append(extraPackingResultComponent(packingData[packingSpecialID]));

            isExtraPackingFilled[packingSpecialID] = true

            popupSwalFireInfo(['Packing data special berhasil disimpan!']);
        }
    });
}


/**
 * Save dimension packings
 * 
 * @param {String} packingID 
 * @param {String} packingType 
 * @returns 
 */
function savePackingDimensions(packingID, packingType){
    let dimension = [];

    let dimensionParentID = `.packing-${packingType}-wrapper-${packingID}`;

    let totalDimensionInput = $(`.packing-${packingType}-wrapper-${packingID} .${packingType}-dimension > div`).get().length;
    for (let i = totalDimensionInput; i > 0; i--) {
        let index = i - 1;
        let valuePanjang = parseInt($(dimensionParentID + ` .packing-${packingType}-dimension-${index} input#${packingType}Panjang`).val());
        let valueLebar = parseInt($(dimensionParentID + ` .packing-${packingType}-dimension-${index} input#${packingType}Lebar`).val());
        let valueTinggi = parseInt($(dimensionParentID + ` .packing-${packingType}-dimension-${index} input#${packingType}Tinggi`).val());
        let jumlahKoli = parseInt($(dimensionParentID + ` .packing-${packingType}-dimension-${index} input#${packingType}JumlahKoli`).val());
        let valueBerat = parseInt($(dimensionParentID + ` .packing-${packingType}-dimension-${index} input#${packingType}Berat`).val());
        let valueHarga = parseInt($(dimensionParentID + ` .packing-${packingType}-dimension-${index} input#${packingType}Harga`).val());

        valuePanjang = isNaN(valuePanjang) ? 0 : valuePanjang;
        valueLebar = isNaN(valueLebar) ? 0 : valueLebar;
        valueBerat = isNaN(valueBerat) ? 0 : valueBerat;
        jumlahKoli = isNaN(jumlahKoli) ? 0 : jumlahKoli;
        valueHarga = isNaN(valueHarga) ? 0 : valueHarga;

        dimension.push({
            jumlah_koli: jumlahKoli,
            panjang: valuePanjang,
            lebar: valueLebar,
            tinggi: valueTinggi,
            berat: valueBerat,
            harga: valueHarga
        });
    }
    return dimension;
}

/**
 * Append Additional input for packing
 * 
 * @param {Object} packingData 
 * @param {Object} indexDimension 
 */
function appendPackingComponent(packingData, indexDimension){
    $(".packing-container #tambahPacking").off('click').click(function() {
        let packingID = $(this).attr('data-id');
        let packingType = $(this).attr('data-packing');

        let dimensionPositionID = `.packing-${packingType}-wrapper-${packingID}`;
        
        if (indexDimension[packingType + packingID] == undefined) {
            indexDimension[packingType + packingID] = 1;
        }

        $(`${dimensionPositionID} .packing-${packingType}-dimension-${indexDimension[packingType + packingID] - 1}`).after(
            addPackingComponent(indexDimension[packingType + packingID], packingType, packingID)
        );
        calculatePacking('standard')
        calculatePacking('special')

        indexDimension[packingType + packingID] += 1;
    });

    $(".packing-container #kurangPacking").off('click').click(function() {
        let packingID = $(this).attr('data-id');
        let packingType = $(this).attr('data-packing');

        let dimensionPositionID = `.packing-${packingType}-wrapper-${packingID}`;

        if ((indexDimension[packingType + packingID] != undefined) && (indexDimension[packingType + packingID] - 1 > 0)) {
            indexDimension[packingType + packingID] -= 1;
            $(`${dimensionPositionID} .packing-${packingType}-dimension-${indexDimension[packingType + packingID]}`).remove();
        }

        calculateTotalHargaBeratPacking(packingType, packingID);
    });
}

/**
 * Calculate packing and update the input  value
 * 
 * @returns {Void}
 */
function calculatePacking(type){
    $(`.packing-${type} .${type}-container input`).off('keyup').on('keyup', (function(){
        let packingID = $(this).attr('data-id');
        let packingType = $(this).attr('data-packing-type');
        let packingIncrement = $(this).attr('data-increment');
        
        let dimensionPositionID = `.packing-${packingType}-wrapper-${packingID}`;
        let {harga, berat} = calculateHargaBeratPacking(dimensionPositionID, packingType, packingIncrement);
        
        $(dimensionPositionID + ` .packing-${packingType}-dimension-${packingIncrement} input#${packingType}Berat`).val(berat);
        $(dimensionPositionID + ` .packing-${packingType}-dimension-${packingIncrement} input#${packingType}Harga`).val(harga);
        
        calculateTotalHargaBeratPacking(packingType, packingID);
    }));
}

/**
 * Calculate total harga and berat packing
 * 
 * @param {String} packingType 
 * @param {String} packingID 
 * @returns {Void}
 */
function calculateTotalHargaBeratPacking(packingType, packingID){
    let hargaTotal = 0
    let beratTotal = 0
    
    let dimensionPositionID = `.packing-${packingType}-wrapper-${packingID}`;
    let totalDimensionInput = $(`.packing-${packingType}-wrapper-${packingID} .${packingType}-dimension > div`).get().length;
    
    for (let i = totalDimensionInput; i > 0; i--) {
        let { harga, berat } = calculateHargaBeratPacking(dimensionPositionID, packingType, i - 1);

        hargaTotal += harga;
        beratTotal += berat;            
    }
    
    $(dimensionPositionID + ` input#${packingType}HargaPacking`).val(hargaTotal);
    $(dimensionPositionID + ` input#${packingType}BeratPacking`).val(beratTotal);
}

/**
 * Calculate for single harga and berat packing
 * 
 * @param {String} dimensionParent 
 * @param {String} packingType 
 * @param {Integer} index 
 * @returns {Object}
 */
function calculateHargaBeratPacking(dimensionParent, packingType, index){
    let valuePanjang = parseInt($(dimensionParent + ` .packing-${packingType}-dimension-${index} input#${packingType}Panjang`).val());
    let valueLebar = parseInt($(dimensionParent + ` .packing-${packingType}-dimension-${index} input#${packingType}Lebar`).val());
    let valueTinggi = parseInt($(dimensionParent + ` .packing-${packingType}-dimension-${index} input#${packingType}Tinggi`).val());
    let jumlahKoli = parseInt($(dimensionParent + ` .packing-${packingType}-dimension-${index} input#${packingType}JumlahKoli`).val());

    valuePanjang = isNaN(valuePanjang) ? 0 : valuePanjang;
    valueLebar = isNaN(valueLebar) ? 0 : valueLebar;
    valueTinggi = isNaN(valueTinggi) ? 0 : valueTinggi;
    jumlahKoli = isNaN(jumlahKoli) ? 0 : jumlahKoli;

    let dimension = valuePanjang * valueLebar * valueTinggi;

    let timesValue = packingType == 'standard' ? PACKING_STANDARD_VALUE : PACKING_SPECIAL_VALUE

    return { 
        harga: Math.ceil(dimension / 1000000 * timesValue),
        berat: Math.ceil(dimension / 3 * jumlahKoli)
    }
}

/**
 * Remove extra packing result component
 * 
 * @param {Object} packingData 
 * @param {Boolean} isExtraPackingFilled 
 */
function removeExtraPackingComponentResult(packingData, isExtraPackingFilled) {
    $('.btn-extra-packing-trash-trigger').off('click').click(function() {
        extraPackingTrashID = $(this).attr('data-id');
        
        $('.extra-packing-result-wrapper-' + extraPackingTrashID).removeClass('dblock');
        $('.extra-packing-result-wrapper-' + extraPackingTrashID + ' div#extraPackingResultList').empty();

        isExtraPackingFilled[extraPackingTrashID] = false;
        packingData[extraPackingTrashID] = {};
    });
}

/**
 * Save request choice input values
 * 
 * @param {Object} requestData 
 * @param {Object} isRequestFilled 
 */
function saveRequestChoice(requestData, isRequestFilled) {
    $('.btn-add-request-simpan-trigger').off('click').click(function () {    
        requestSaveID = $(this).attr('id');
        
        if (isRequestFilled[requestSaveID]) {
            return;
        }
        requestData[requestSaveID] = {}
        requestData[requestSaveID].document_FTZ = $('#documentFTZInput_' + requestSaveID).prop('checked') == 1; 
        requestData[requestSaveID].document_MSDS = $('#documentMSDSInput_' + requestSaveID).prop('checked') == 1; 
        requestData[requestSaveID].Surcharge_Heavy_Cargo = $('#surchargeHeavyCargoInput_' + requestSaveID).prop('checked') == 1; 
        requestData[requestSaveID].Forklift_Heavy_Cargo = $('#forkliftHeavyCargoInput_' + requestSaveID).prop('checked') == 1; 
        requestData[requestSaveID].Asuransi = $('#asuransiInput_' + requestSaveID).prop('checked') == 1; 
        
        if (Object.keys(requestData[requestSaveID]).length !== 0) {
            isRequestFilled[requestSaveID] = true
        }

        if (isRequestFilled[requestSaveID]) {
            $('.request-result-wrapper-' + requestSaveID).addClass('dblock');
            $('ul#requestResultList' + requestSaveID).append(requestResultComponent(requestData[requestSaveID]));
        }

        popupSwalFireInfo(['Request data berhasil disimpan!']);
    })
}

/**
 * Remove request result component
 * 
 * @param {Object} requestData 
 * @param {Object} isRequestFilled 
 */
function removeRequestResultComponent(requestData, isRequestFilled) {
    $('.btn-add-request-trash-trigger').off('click').click(function(){
        requestTrashID = $(this).attr('id');

        $('.request-result-wrapper-'  + requestTrashID).removeClass('dblock');
        $('ul#requestResultList' + requestTrashID + ' li').remove();

        isRequestFilled[requestTrashID] = false;
        requestData[requestTrashID] = {};
    });
}

/**
 * Save document choice input values
 * 
 * @param {Object} documentData 
 * @param {Object} isDocumentFilled 
 */
function saveDocumentChoice(documentData, isDocumentFilled) {
    $('.btn-add-document-simpan-trigger').off('click').click(function(){ 
        documentSimpanIndex = $(this).attr('id');
        documentData[documentSimpanIndex] = [];

        documentItem = {};

        if (isDocumentFilled[documentSimpanIndex]) {
            return;
        }

        documentChildrens = $('.lampirkan-dokumen-collapse-' + documentSimpanIndex).children('.modal-checkbox');
        for (const children of documentChildrens) {
            if ($(children).find('.document-wajib-flag-' + documentSimpanIndex).prop('checked')) {
                documentItem.id = $(children).find('#documentIdFlag' + documentSimpanIndex).val();
                documentItem.nama = $(children).find('.document-name-flag-' + documentSimpanIndex).text();
                documentItem.nomor = $(children).find('.document-nomor-flag-' + documentSimpanIndex).text();
            }

            if (Object.keys(documentItem).length !== 0) {
                documentData[documentSimpanIndex].push(documentItem);
                isDocumentFilled[documentSimpanIndex] = true;
            }

            documentItem = {};
        }

        if (isDocumentFilled[documentSimpanIndex]) {
            $('.document-result-wrapper-' + documentSimpanIndex).addClass('dblock');
            $('ul#documentResultList' + documentSimpanIndex).append(documentResultComponent(documentData[documentSimpanIndex]));
        }

        popupSwalFireInfo(['Document data berhasil disimpan!']);
    });
    
}

/**
 * Remove document result component
 * 
 * @param {Object} documentData 
 * @param {Object} isDocumentFilled 
 */
function removeDocumentResultComponent(documentData, isDocumentFilled) {
    $('.btn-add-document-trash-trigger').off('click').click(function(){ 
        documentTrashID = $(this).attr('id');

        $('.document-result-wrapper-' + documentTrashID).removeClass('dblock');
        $('ul#documentResultList' + documentTrashID + ' li').remove();

        documentData[documentTrashID] = [];
        isDocumentFilled[documentTrashID] = false;
    });
}



