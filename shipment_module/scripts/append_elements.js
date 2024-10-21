/**
 * Create HTML element to be appended for vol matrix each services
 * 
 * @param {String} index | '00','01','10'
 * @param {Integer} type | [reguler, express, primex]
 * @returns {String} DOM
 */
function addComponentVolMatrixService(index, type, dataID) {
    return `
        <div class="matrix-dimension-item-${index}">
            <div class="vol-matrix-input dflex">
                <input id="${type}VolMatrixJumlahKoli${index}" type="number" data-id="${dataID}" data-service="${type}" data-increment="${index}" placeholder="Jmlh Koli">
                <input id="${type}VolMatrixBeratAsli${index}" type="number" data-id="${dataID}" data-service="${type}" data-increment="${index}" placeholder="Berat Asli" disabled>
            </div>
        
            <div class="dimension-input dflex align-item-c">
                <input id="${type}VolMatrixPanjang${index}" type="number" data-id="${dataID}" data-service="${type}" data-increment="${index}" placeholder="P"><span>X</span>
                <input id="${type}VolMatrixLebar${index}" type="number" data-id="${dataID}" data-service="${type}" data-increment="${index}" placeholder="L"><span>X</span>
                <input id="${type}VolMatrixTinggi${index}" type="number" data-id="${dataID}" data-service="${type}" data-increment="${index}" placeholder="T">
            </div>
            
            <hr class="line-boundaries">
        </div>
    `;
}

/**
 * Create HTML element to be appended for service result
 * 
 * @param {Object} data 
 * @param {String} type 
 * @returns {String} DOM
 */
function serviceResultComponent(data, type){
    let componentString = "";

    if (['reguler', 'express', 'primex', 'ltl'].includes(type)) {
        componentString += `
            <li>${data.jumlah_koli} Koli</li>
            <li>Berat asli <span>${data.berat_asli} KG</span></li>
        `;
        
        for (const item of data.vol_matrix) {
            componentString += `
                <li>${item.jumlah_koli} Koli</li>
                <li>Berat Volume Metric <span>${item.berat_asli} KG </span></li>
            `;
        }
    }else if (['fcl', 'ftl'].includes(type)) {
        componentString += `
            <li>${data.jumlah_koli} Koli</li>
            <li>Jumlah KG ${data.jumlah_kg} KG</li><br>
            <li>Jumlah Unit ${data.jumlah_unit} KG</li><br>
            <li>Jumlah Kubik ${data.jumlah_kubik} KG</li><br>
        `;
    }else if (['lcl'].includes(type)) {
        componentString += `
            <li>Dimensi : (${data.panjang}cm, ${data.lebar}cm, ${data.tinggi}cm)</li>
            <li>${data.nama_barang}</li><br>
        `;
    }

    return componentString;
}

/**
 * Produce extra packing result component
 * Calculate dimension and koli for extra packing
 * 
 * @param object packingData
 * @return string DOM
 */
function extraPackingResultComponent(packingData){
    return `
        <h6>Packing Kayu ${packingData.jenis_packing.charAt(0).toUpperCase() + packingData.jenis_packing.slice(1)}</h6>
        <p>Biaya packing : ${packingData.harga_packing}</p>
        <p>Total tambahan berat : ${packingData.berat_packing}</p>
    `;
}

/**
 * Create HTML element to be appended for additional packing
 * 
 * @param {String} index | '0','1','2'
 * @param {Integer} type | [standard, special]
 * @returns {String} DOM
 */
function addPackingComponent(index, type, dataID) {
    return `
        <div class="packing-${type}-dimension-${index} mb-3">
            <div class="${type}-container ${type}-jumlah-koli mr-2">
                <h6>Jumlah Koli</h6>
                <input id="${type}JumlahKoli" type="number" placeholder="0" data-packing-type="${type}" data-id="${dataID}" data-increment="${index}">
            </div>
            
            <div class="${type}-container mr-2">
                <h6>Ukuran PxLxT (Total Vol)</h6>
                <div class="dflex">
                    <input id="${type}Panjang" type="number" placeholder="P" data-packing-type="${type}" data-id="${dataID}" data-increment="${index}">
                    <input id="${type}Lebar" type="number" placeholder="L" data-packing-type="${type}" data-id="${dataID}" data-increment="${index}">
                    <input id="${type}Tinggi" type="number" placeholder="T" data-packing-type="${type}" data-id="${dataID}" data-increment="${index}">
                </div>
            </div>
            
            <div class="${type}-container ${type}-weight-item">
                <h6>Berat</h6>
                <input id="${type}Berat" type="number" placeholder="Berat" disabled data-packing-type="${type}" data-id="${dataID}" data-increment="${index}">
            </div>
        </div>
    `;
}
/**
 * Produce document result component
 * 
 * @param object requestData
 * @return string DOM;
 */
function requestResultComponent(requestData){
    let componentString = "";

    for (const key in requestData) {
        if (requestData[key]) {
            const text = key.split('_').join(' ');
            componentString += `<li><h6> ${text} </h6></li>`;
        }
    }

    return componentString;
}

/**
 * Produce document result component
 * 
 * @param array documenData (eg : {nama: 'nama-dokumen', nomor: 'nomor-dokumen'})
 * @return string DOM;
 */
function documentResultComponent(documentData){
    let componentString = "";
    
    for (const item of documentData) {
        componentString += `<li><h6> ${item.nama} - ${item.nomor} </h6></li>`;
    }
    return componentString;
}

/**
 * Produce address result component
 * 
 * @param {Object} custSendData 
 * @returns {String} DOM
 */
function addressResultComponent(custSendData){
    return `
        <p>Nama : ${custSendData.nama_send_receipt}</p>
        <p>Alamat : ${custSendData.alamat}</p>
        <p>Phone : ${custSendData.phone}</p>
    `;
}

/**
 * Product address input for province component
 * 
 * @param {Array} provinsis 
 * @param {String} dataParent 
 * @returns {String} DOM
 */
function listProvinsiComponent(provinsis, dataParent){
    let componentString = '';

    for (const item of provinsis) {
        componentString += `<button data-provinsi-id="${item.id}" data-parent="${dataParent}">${item.nama_provinsi}</button>`;
    }

    return componentString;
}   

/**
 * Product address input for province component
 * 
 * @param {Array} kotas 
 * @param {String} dataParent 
 * @returns {String} DOM
 */
function listKotaComponent(kotas, dataParent){
    let componentString = '';

    for (const item of kotas) {
        componentString += `<button data-kota-id="${item.id}" data-parent="${dataParent}">${item.nama_kota_kab}</button>`;
    }

    return componentString;
}   

/**
 * Product address input for province component
 * 
 * @param {Array} kecamatans 
 * @param {String} dataParent 
 * @returns {String} DOM
 */
function listKecamatanComponent(kecamatans, dataParent){
    let componentString = '';

    for (const item of kecamatans) {
        componentString += `<button data-kecamatan-id="${item.id}" data-parent="${dataParent}">${item.nama_kecamatan}</button>`;
    }

    return componentString;
}   

/**
 * Component to append for list address picker
 * 
 * @param {Array} data
 * @param {String} popupId
 * @returns {String} component
 */
function listAdressPickerComponent(data, popupId){
    let component = '';

    for (const item of data) {
        component += `
            <div class="popup-address-item mt-2 box-shadow-with-thin-border dflex align-item-c" data-id="<?= $popupId ?>">
                <img src="../images/icon/shipment/gmap-icon.png">
                <div>
                    <input id="addressInputID${popupId}" type="hidden" value="${item.id}">
                    <p id="addressInputNama${popupId}">${item.nama_send_receipt == null ? '-' : item.nama_send_receipt}</p>
                    <p id="addressInputAlamat${popupId}">${item.alamat == null ? '-' : item.alamat}</p>
                    <p id="addressInputPhone${popupId}">${item.phone_pic == null ? '-' : item.phone_pic}</p>
                </div>
            </div>
        `;
    }

    return component;
}