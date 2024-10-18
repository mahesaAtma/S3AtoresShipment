<?php

session_start();

include_once('../../connection.php');

$nowDatetime = date("Y-m-d H:i:s");
$nowDate = date("Y-m-d");

function validateInput($data) {
    $message = null;

    // if (!isset($data['packing_data'])) return false;
    // if (!isset($data['request_data'])) return false;

    if (!isset($data['payment_data'])) {
        $message = "Mohon isi data payment terlebih dahulu!";
    }

    // if (!isset($data['document_data'])) {
    //     $message = "Mohon isi data dokumen terlebih dahulu!";
    // }

    if (!isset($data['service_data'])) {
        $message = "Mohon isi data service terlebih dahulu!";
    }

    if (!isset($data['customer_id']) || !isset($data['order_pickup_detail_id']) ||
        !isset($data['trx_task_id']) || !isset($data['no_sttb']) || !isset($data['sttb_input_type'])) {
        $message = "Data utama harus ada terlebih dahulu!";
    }

    if (isset($data['prioritas_pengiriman']) && !in_array($data['prioritas_pengiriman'], ['Y', 'N'])) {
        $message = "Nilai prioritas pengiriman hanya 0 atau 1!";
    }

    if (isset($data['barang_susulan']) && !in_array($data['barang_susulan'], ['Y', 'N'])) {
        $message = "Nilai barang susulan hanya 0 atau 1!";
    }

    if (isset($data['prioritas_pengiriman']) && isset($data['tgl_prioritas_pengiriman']) && 
        $data['prioritas_pengiriman'] == "Y" && $data['tgl_prioritas_pengiriman'] == "") {
        $message = "Tolong isi tanggal terlebih dahulu untuk prioritas pengiriman!";
    }

    if (!is_null($message)) {
        return [
            "status" => false,
            "message" => $message
        ];
    }

    return [
        "status" => true,
        "message" => $message
    ];
}

function validateNoSTTB($data, $dbconn){
    $noSTTBQuery = "SELECT id FROM trx_order_sttb_d WHERE no_sttb_fisik = '" . $data['no_sttb'] . "';";
    $noSTTB = pg_fetch_object(pg_query($dbconn, $noSTTBQuery));

    $shipmentSTTBQuery = "SELECT id FROM trx_shipment_entry_d1 WHERE no_sttb = '" . $data['no_sttb'] . "';";
    $shipmentSTTB = pg_fetch_object(pg_query($dbconn, $shipmentSTTBQuery));

    // 'input' is equal to scan sttb barcode
    if ($data['sttb_input_type'] == 'e-STTB') {
        if ($noSTTB) {
            return ['success' => false, 'message' => "Nomor E-STTB " . $data['no_sttb'] . " sudah ada dalam database, mohon buat E-STTB baru!"];
        }
    }else if ($data['sttb_input_type'] == 'input'){
        if (!$noSTTB) {
            return ['success' => false, 'message' => "Nomor STTB " . $data['no_sttb'] . " tidak dapat ditemukan!"];
        }
    
        if ($shipmentSTTB) {
            return ['success' => false, 'message' => "Nomor STTB " . $data['no_sttb'] . " telah digunakan untuk shipment!"];
        }
    }else if ($data['sttb_input_type'] == 'FISIK'){
        if (!$noSTTB && !$shipmentSTTB) {
            return ['success' => false, 'message' => "Nomor STTB " . $data['no_sttb'] . " tidak dapat ditemukan!"];
        }
    }else{
        return ['success' => false, 'message' => "Tipe input sttb tidak valid!"];
    }
    
    return ['success' => true];
}

function validateTrxTask($data, $dbconn){
    $trxTaskQuery = "SELECT id, status_task, task_no FROM trx_task WHERE id = " . $data['trx_task_id'] . ";";
    $trxTask = pg_fetch_object(pg_query($dbconn, $trxTaskQuery));

    if (!$trxTask) {
        return ['success' => false, 'message' => "Data task " . $data['trx_task_id'] . " tidak dapat ditemukan!"];
    }

    if ($trxTask->status_task !== '133') {
        return ['success' => false, 'message' => "Status task " . $trxTask->task_no . " tidak valid!"];
    }
    
    return ['success' => true, 'data' => $trxTask];
}

function validateOrderPickup($data, $dbconn){
    $orderPickupQuery = "SELECT 
            detail.id, 
            master.status_task, 
            detail.no_order_pickup, 
            detail.task_no,
            detail.custsentreceipt_id,
            master.cabang_id,
            master.unit_id
        FROM trx_order_pickup_d1 AS detail
        JOIN trx_order_pickup AS master ON detail.no_order_pickup = master.no_order_pickup
        JOIN trx_task AS task ON detail.no_order_pickup = task.no_ref_process
        WHERE detail.id = " . $data['order_pickup_detail_id'] . ";";
    $orderPickup = pg_fetch_object(pg_query($dbconn, $orderPickupQuery));

    if (!$orderPickup) {
        return ['success' => false, 'message' => "Data order pickup tidak dapat ditemukan!"];
    }

    if ($orderPickup->status_task !== '116') {
        return ['success' => false, 'message' => "Status order pickup tidak valid!"];
    }
    
    return ['success' => true, 'data' => $orderPickup];

}

function insertTrxShipmentEntry($nowDatetime, $noShipment, $payment, $orderPickup, $customer, $user){    
    $queryInsert = ["INSERT INTO trx_shipment_entry (
        no_shipment_entry,
        customer_id,
        no_order_pickup,
        task_no,
        keterangan,
        cabang_id,
        unit_id,
        tgl_shipment_entry,
        jml_aktivitas_perbaikan_data,
        status_record,
        created_by,
        updated_by,
        created_at,
        updated_at,
        sign_cust,
        cara_bayar_id,
        longitude,
        latitude,
        walk_in
    ) VALUES (
        '" . $noShipment . "',
        " . $customer->id . ",
        '" . $orderPickup->no_order_pickup . "',
        '" . $orderPickup->task_no . "',
        NULL,
        '" . $orderPickup->cabang_id . "',
        '" . $orderPickup->unit_id . "',
        '" . $nowDatetime . "',
        0,
        1,
        '" . $user->name . "',
        NULL,
        '" . $nowDatetime . "',
        '" . $nowDatetime . "',
        NULL,
        " . $payment->id . ",
        NULL,
        NULL,
        NULL
    );"];

    return ['success' => true, 'queries' => $queryInsert];
}

function insertTrxShipmentEntryD1($data, $dbconn, $nowDate, $nowDatetime, $noShipment, $orderPickup, $customer, $user){
    $serviceData = isset($data['service_data']) ? $data['service_data'] : [];

    if (is_null($orderPickup->custsentreceipt_id)) {
        return ['success' => false, 'message' => "Harap memasukan data alamat penerima terlebih dahulu!"];
    }
    
    $sendReceiptQuery = "SELECT id, alamat, nama_pic, phone_pic FROM ms_cust_send_receipt WHERE id = " . $orderPickup->custsentreceipt_id . ";";
    $sendReceipt = pg_fetch_object(pg_query($dbconn, $sendReceiptQuery));
    if (!$sendReceipt) {
        return ['success' => false, 'message' => "Alamat penerima tidak dapat ditemukan!"];
    }

    $jmlPrintBarcode = isset($serviceData['jumlah_koli']) ? $serviceData['jumlah_koli'] : 0;
    $tglPrioritas = !isset($data['tgl_prioritas_pengiriman']) || $data['tgl_prioritas_pengiriman'] == '' ? 'NULL' : "'" . $data['tgl_prioritas_pengiriman'] . "'";

    $queryInsert = ["INSERT INTO trx_shipment_entry_d1 (
        no_shipment_entry,
        no_sttb,
        jenis_sttb,
        no_sub_sttb,
        alamat_pengirim,
        nama_pic_pengirim,
        tlp_pic_pengirim,
        alamat_penerima,
        nama_pic_penerima,
        tlp_pic_penerima,
        price,
        jml_print_barcode,
        status_unloading,
        catatan_customer,
        no_ba,
        tgl_entry,
        unit_id,
        cabang_id,
        status_record,
        created_by,
        updated_by,
        created_at,
        updated_at,
        prioritas,
        tgl_prioritas,
        no_resi,
        is_counter,
        susulan
    ) VALUES (
        '" . $noShipment . "',
        '" . $data['no_sttb'] . "',
        '" . $data['sttb_input_type'] . "',
        NULL,
        '" . $customer->id . "',
        '" . $customer->name_pic . "',
        '" . $customer->phone_pic . "',
        '" . $sendReceipt->id . "',
        '" . $sendReceipt->nama_pic . "',
        '" . $sendReceipt->phone_pic . "',
        0,
        " . $jmlPrintBarcode . ",
        'N',
        NULL,
        NULL,
        '" . $nowDate . "',
        '" . $orderPickup->unit_id . "',
        '" . $orderPickup->cabang_id . "',
        1,
        '" . $user->name . "',
        NULL,
        '" . $nowDatetime . "',
        '" . $nowDatetime . "',
        '" . $data['prioritas_pengiriman'] . "',
        " . $tglPrioritas . ",
        NULL,
        NULL,
        '" . $data['barang_susulan'] . "'
    ) RETURNING id;"];

    return ['success' => true, 'queries' => $queryInsert];
}

function insertStatusSTTB($data, $noShipment, $nowDatetime, $orderPickup, $user){
    $serviceData = isset($data['service_data']) ? $data['service_data'] : [];

    $jmlKoli = isset($serviceData['jumlah_koli']) ? $serviceData['jumlah_koli'] : 'NULL'; 
    $jmlKg = isset($serviceData['jumlah_kg']) ? $serviceData['jumlah_kg'] : 'NULL'; 

    $queryInsert = ["INSERT INTO trx_status_sttb (
        no_ref_process,
        nama_process,
        no_sttb,
        status_sttb,
        cabang_id,
        unit_id,
        status_record,
        created_by,
        updated_by,
        created_at,
        updated_at,
        keterangan,
        koli,
        kg,
        volume,
        penerima,
        foto
    ) VALUES (
        '" . $noShipment . "',
        'SHIPMENT_ENTRY',
        '" . $data['no_sttb'] . "',
        100,
        '" . $orderPickup->cabang_id . "',
        '" . $orderPickup->unit_id . "',
        1,
        '" . $user->name . "',
        NULL,
        '" . $nowDatetime . "',
        '" . $nowDatetime . "',
        NULL,
        " . $jmlKoli . ",
        " . $jmlKg . ",
        NULL,
        NULL,
        NULL
    );"];

    return ['success' => true, 'queries' => $queryInsert];
}

function insertTrxShipmentEntryD1Service($data, $dbconn, $nowDatetime, $orderPickup, $shipmentEntryD1ID, $user){
    $serviceData = isset($data['service_data']) ? $data['service_data'] : [];
    
    $queryInsert = [];

    if (empty($serviceData)) {
        return ['success' => false, 'message' => "Mohon mengisi data service terlebih dahulu"];
    }
    if (!isset($serviceData['nama_barang'])) {
        return ['success' => false, 'message' => "Mohon mengisi data nama barang terlebih dahulu"];
    }
    if (!isset($serviceData['jumlah_koli'])) {
        return ['success' => false, 'message' => "Mohon mengisi data jumlah koli terlebih dahulu"];
    }
    if (!isset($serviceData['berat_asli'])) {
        return ['success' => false, 'message' => "Mohon mengisi data berat asli terlebih dahulu"];
    }


    $serviceQuery = "SELECT id FROM ms_service_products WHERE nama_service_product = '" . strtoupper($serviceData['type']) . "';";
    $service = pg_fetch_object(pg_query($dbconn, $serviceQuery));
    if (!$service) {
        return ['success' => false, 'message' => "Tipe service " . $serviceData['type'] . " tidak dapat ditemukan!"];
    }

    // if (isset($serviceData['nama_barang'])) {
    //     $jenisBarangQuery = "SELECT id FROM ms_jenis_barang WHERE nama_barang = '" . strtoupper($serviceData['nama_barang']) . "';";
    //     $jenisBarang = pg_fetch_object(pg_query($dbconn, $jenisBarangQuery));
    //     if (!$jenisBarang) {
    //         return ['success' => false, 'message' => "Nama barang " . $serviceData['nama_barang'] . " tidak dapat ditemukan!"];
    //     }
    // }

    $columns = implode(',', [
        'trx_shipment_entry_d1_id',
        'service_id',
        'jml_koli',
        'berat_asli',
        'volume',
        'pallet',
        'tgl_entry',
        'unit_id',
        'cabang_id',
        'status_record',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'status',
        'volume_desc',
        'berat_volume',
        'tipe_armada',
        'jumlah_unit',
        'nama_barang',
        'tipe_kontainer',
        'jumlah_kubik',
        'jumlah_kg',
        'berat_total',
        'total_berat_volume'
    ]);
    
    $queryInsert[] = "INSERT INTO trx_shipment_entry_d1_service (
        " . $columns . "
    ) VALUES (
        '" . $shipmentEntryD1ID . "',
        " . $service->id . ",
        " . $serviceData['jumlah_koli'] . ",
        " . $serviceData['berat_asli'] . ",
        NULL,
        NULL,
        NULL,
        '" . $orderPickup->unit_id . "',
        '" . $orderPickup->cabang_id . "',
        1,
        '" . $user->name . "',
        NULL,
        '" . $nowDatetime . "',
        '" . $nowDatetime . "',
        NULL,
        NULL,
        NULL,
        NULL,
        NULL,
        '" . $serviceData['nama_barang'] . "',
        NULL,
        NULL,
        NULL,
        NULL,
        NULL
    );";

    $volMatrixArray = isset($serviceData['vol_matrix']) ? $serviceData['vol_matrix'] : [];

    if (in_array($serviceData['type'], ['reguler', 'express', 'primex', 'ltl'])) {
        if (count($volMatrixArray) > 0) {
            foreach ($volMatrixArray as $volMatrix) {
                $volume = ((int) $volMatrix['dimension']['p']) * ((int) $volMatrix['dimension']['l']) * ((int) $volMatrix['dimension']['t']);
                $volumeDesc = $volMatrix['dimension']['p'] . "x" . $volMatrix['dimension']['l'] . "x" . $volMatrix['dimension']['t'] . " (cm)";
    
                $queryInsert[] = "INSERT INTO trx_shipment_entry_d1_service (
                    " . $columns . "
                ) VALUES (
                    '" . $shipmentEntryD1ID . "',
                    " . $service->id . ",
                    " . $volMatrix['jumlah_koli'] . ",
                    NULL,
                    " . $volume . ",
                    NULL,
                    NULL,
                    '" . $orderPickup->unit_id . "',
                    '" . $orderPickup->cabang_id . "',
                    1,
                    '" . $user->name . "',
                    NULL,
                    '" . $nowDatetime . "',
                    '" . $nowDatetime . "',
                    NULL,
                    '" . $volumeDesc . "',
                    '" . $volMatrix['berat_asli'] . "',
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NULL
                );";
            }
        }else{
            $queryInsert[] = "INSERT INTO trx_shipment_entry_d1_service (
                " . $columns . "
            ) VALUES (
                '" . $shipmentEntryD1ID . "',
                " . $service->id . ",
                " . $serviceData['jumlah_koli'] . ",
                " . $serviceData['berat_asli'] . ",
                NULL,
                NULL,
                NULL,
                '" . $orderPickup->unit_id . "',
                '" . $orderPickup->cabang_id . "',
                1,
                '" . $user->name . "',
                NULL,
                '" . $nowDatetime . "',
                '" . $nowDatetime . "',
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                '" . $serviceData['nama_barang'] . "',
                NULL,
                NULL,
                NULL,
                NULL,
                NULL
            );";
        }
    }else if (in_array($serviceData['type'], ['fcl', 'ftl'])) {
        $queryInsert[] = "INSERT INTO trx_shipment_entry_d1_service (
            " . $columns . "
        ) VALUES (
            '" . $shipmentEntryD1ID . "',
            " . $service->id . ",
            " . $serviceData['jumlah_koli'] . ",
            NULL,
            NULL,
            NULL,
            NULL,
            '" . $orderPickup->unit_id . "',
            '" . $orderPickup->cabang_id . "',
            1,
            '" . $user->name . "',
            NULL,
            '" . $nowDatetime . "',
            '" . $nowDatetime . "',
            NULL,
            NULL,
            NULL,
            NULL,
            " . $serviceData['jumlah_unit'] . ",
            NULL,
            NULL,
            " . $serviceData['jumlah_kubik'] . ",
            " . $serviceData['jumlah_kg'] . ",
            NULL,
            NULL
        );";
    }else if (in_array($serviceData['type'], ['lcl'])) {
        $queryInsert[] = "INSERT INTO trx_shipment_entry_d1_service (
            " . $columns . "
        ) VALUES (
            '" . $shipmentEntryD1ID . "',
            " . $service->id . ",
            'NULL',
            'NULL',
            'NULL',
            'NULL',
            'NULL',
            '" . $orderPickup->unit_id . "',
            '" . $orderPickup->cabang_id . "',
            1,
            '" . $user->name . "',
            'NULL',
            '" . $nowDatetime . "',
            '" . $nowDatetime . "',
            'NULL',
            'NULL',
            'NULL',
            'NULL',
            'NULL',
            '" . $serviceData['nama_barang'] . "',
            'NULL',
            'NULL',
            'NULL',
            'NULL',
            'NULL'
        );";
    }  

    return ['success' => true, 'queries' => $queryInsert];
}

function insertTrxShipmentEntryD2($data, $dbconn, $nowDatetime, $noShipment, $orderPickup, $user){
    $documentData = isset($data['document_data']) ? $data['document_data'] : [];
    $queryInsert = [];

    if (count($documentData) <= 0) {
        return ['success' => true, 'queries' => $queryInsert];
    }

    $documentIds = array_map(function($doc){
        return $doc['id'];
    }, $documentData);

    $selectQuery = "SELECT id,no_dokumen FROM ms_documents WHERE id IN (" . implode(',', $documentIds) . ")";
    $documents = pg_fetch_all(pg_query($dbconn,$selectQuery));
    if (!$documents) {
        return ['success' => false, 'message' => "Pilihan dokumen tidak dapat ditemukan!"];
    }

    foreach ($documents as $document) {
        $queryInsert[] = "INSERT INTO trx_shipment_entry_d2 (
            no_shipment_entry,
            no_sttb,
            document_id,
            no_dokumen,
            status_available,
            cabang_id,
            unit_id,
            status_record,
            created_by,
            updated_by,
            created_at,
            updated_at
        ) VALUES (
            '" . $noShipment . "',
            '" . $data['no_sttb'] . "',
            " . $document['id'] . ",
            '" . $document['no_dokumen'] . "',
            'true',
            '" . $orderPickup->cabang_id . "',
            '" . $orderPickup->unit_id . "',
            1,
            '" . $user->name . "',
            NULL,
            '" . $nowDatetime . "',
            '" . $nowDatetime . "'
        );";
    }

    return ['success' => true, 'queries' => $queryInsert];
}

function insertTrxShipmentEntryD3($data, $dbconn, $nowDatetime, $noShipment, $orderPickup, $user){
    $requestData = isset($data['request_data']) ? $data['request_data'] : [];
    
    $queryInsert = [];

    if (empty($requestData)) {
        return ['success' => true, 'queries' => $queryInsert];
    }

    foreach ($requestData as $key => $value) {
        if ($value == 'false') {
            continue;
        }

        $featureText = strtoupper(implode(' ', explode('_',$key)));

        $featureQuery = "SELECT id FROM ms_features WHERE nama_features = '" . $featureText . "';";
        $feature = pg_fetch_object(pg_query($dbconn, $featureQuery));
        if (!$feature) {
            return ['success' => false, 'message' => "Feature atau request " . $featureText . " tidak dapat ditemukan!"];
        }
        
        $queryInsert[] = "INSERT INTO trx_shipment_entry_d3 (
            no_shipment_entry,
            no_sttb,
            features_id,
            jumlah_features,
            jml_koli,
            dimensi_panjang,
            dimensi_lebar,
            dimensi_tinggi,
            biaya,
            berat_total,
            status_ambil,
            unit_id,
            cabang_id,
            status_record,
            created_by,
            updated_by,
            created_at,
            updated_at,
            id_harga_packing
        ) VALUES (
            '" . $noShipment . "',
            '" . $data['no_sttb'] . "',
            " . $feature->id . ",
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            '" . $orderPickup->unit_id . "',
            '" . $orderPickup->cabang_id . "',
            1,
            '" . $user->name . "',
            NULL,
            '" . $nowDatetime . "',
            '" . $nowDatetime . "',
            NULL
        );";
    }

    return ['success' => true, 'queries' => $queryInsert];
}

function insertTrxShipmentEntryD3Packing($data, $dbconn, $nowDatetime, $noShipment, $orderPickup, $user){
    $packingData = isset($data['packing_data']) ? $data['packing_data'] : [];

    $queryInsert = [];

    if (empty($packingData)) {
        return ['success' => true, 'queries' => $queryInsert];
    }

    if (empty($packingData['dimensi'])) {
        return ['success' => false, 'message' => "Mohon mengisi dimensi extra packing terlebih dahulu!"];
    }

    $namaPacking = strtoupper("packing " . $packingData['jenis_packing']);
    $packingQuery = "SELECT id FROM ms_harga_packing WHERE nama_packing = '" . pg_escape_string($namaPacking) . "';";
    $packing = pg_fetch_object(pg_query($dbconn, $packingQuery));
    if (!$packing) {
        return ['success' => false, 'message' => "Data " . $namaPacking . " tidak dapat ditemukan!"];
    }

    foreach ($packingData['dimensi'] as $item) {
        $queryInsert[] = "INSERT INTO trx_shipment_entry_d3 (
            no_shipment_entry,
            no_sttb,
            features_id,
            jumlah_features,
            jml_koli,
            dimensi_panjang,
            dimensi_lebar,
            dimensi_tinggi,
            biaya,
            berat_total,
            status_ambil,
            unit_id,
            cabang_id,
            status_record,
            created_by,
            updated_by,
            created_at,
            updated_at,
            id_harga_packing
        ) VALUES (
            '" . $noShipment . "',
            '" . $data['no_sttb'] . "',
            NULL,
            NULL,
            " . $item['jumlah_koli'] . ",
            " . $item['panjang'] . ",
            " . $item['lebar'] . ",
            " . $item['tinggi'] . ",
            " . $item['harga'] . ",
            " . $item['berat'] . ",
            NULL,
            '" . $orderPickup->unit_id . "',
            '" . $orderPickup->cabang_id . "',
            1,
            '" . $user->name . "',
            NULL,
            '" . $nowDatetime . "',
            '" . $nowDatetime . "',
            $packing->id
        );";
    }

    return ['success' => true, 'queries' => $queryInsert];
}

function insertQueries($dbconn, $queries, $returnId = false){
    $lastId = 0;

    foreach ($queries as $query) {
        $insert = pg_query($dbconn, $query);
        if ($returnId) {
            $lastId = pg_fetch_array($insert, 0, PGSQL_ASSOC)['id'];
        }
    }

    return ['success' => true, 'lastId' => $lastId];
}

function saveShipment($data, $dbconn, $nowDate, $nowDatetime) {
    $userQuery = "SELECT id,name FROM users WHERE id = " . $_SESSION['id_user_login'] . " AND role_id = '119';";
    $user = pg_fetch_object(pg_query($dbconn, $userQuery));
    if (!$user) {
        return ['success' => false, 'message' => "Data driver tidak dapat ditemukan!"];
    }

    $validatedInput = validateInput($data);

    if (!$validatedInput['status']) {
        return ['success' => false, 'message' => $validatedInput['message']];
    }

    $customerQuery = "SELECT id,name,address,phone,name_pic,phone_pic FROM ms_customers WHERE id = " . $data['customer_id'] . ";";
    $customer = pg_fetch_object(pg_query($dbconn, $customerQuery));
    if (!$customer) {
        return ['success' => false, 'message' => "Data customer " . $data['customer_id'] . " tidak dapat ditemukan!"];
    }

    $paymentQuery = "SELECT id, nama_cara_bayar FROM ms_cara_bayars WHERE nama_cara_bayar = '" . $data['payment_data'] . "';";
    $payment = pg_fetch_object(pg_query($dbconn, $paymentQuery));
    if (!$payment) {
        return ['success' => false, 'message' => "Data cara bayar " . $data['payment'] . " tidak dapat ditemukan!"];
    }

    $noSTTB = validateNoSTTB($data, $dbconn);
    if (!$noSTTB['success']) {
        return ['success' => false, 'message' => $noSTTB['message']];
    }

    $trxTask = validateTrxTask($data, $dbconn);
    if (!$trxTask['success']) {
        return ['success' => false, 'message' => $trxTask['message']];
    }

    $orderPickup = validateOrderPickup($data, $dbconn);
    if (!$orderPickup['success']) {
        return ['success' => false, 'message' => $orderPickup['message']];
    }

    $cabangQuery = "SELECT id FROM ms_cabangs WHERE id = '" . $orderPickup['data']->cabang_id . "';"; 
    $cabang = pg_fetch_object(pg_query($dbconn, $cabangQuery));
    if (!$cabang) {
        return ['success' => false, 'message' => "Data cabang " . $orderPickup['data']->cabang_id . " dari order pickup tidak dapat ditemukan!"];
    }

    $unitQuery = "SELECT id FROM ms_units WHERE id = '" . $orderPickup['data']->unit_id . "';"; 
    $unit = pg_fetch_object(pg_query($dbconn, $unitQuery));
    if (!$unit) {
        return ['success' => false, 'message' => "Data unit " . $orderPickup['data']->unit_id . " dari order pickup tidak dapat ditemukan!"];
    }

    // Validate all input first and ignore query returned & ignored no shipment & last detail 1 id
    $detail1Validate = insertTrxShipmentEntryD1($data, $dbconn, $nowDate, $nowDatetime, 'XX-TESTING-XX', $orderPickup['data'], $customer, $user);
    if (!$detail1Validate['success']) {
        return ['success' => false, 'message' => $detail1Validate['message']];
    }
    
    $detail1ServiceValidate = insertTrxShipmentEntryD1Service($data, $dbconn, $nowDatetime, $orderPickup['data'], 9999999999, $user);
    if (!$detail1ServiceValidate['success']) {
        return ['success' => false, 'message' => $detail1ServiceValidate['message']];
    }
    
    $detail2Validate = insertTrxShipmentEntryD2($data, $dbconn, $nowDatetime, 'XX-TESTING-XX', $orderPickup['data'], $user);
    if (!$detail2Validate['success']) {
        return ['success' => false, 'message' => $detail2Validate['message']];
    }

    $detail3Validate = insertTrxShipmentEntryD3($data, $dbconn, $nowDatetime, 'XX-TESTING-XX', $orderPickup['data'], $user);
    if (!$detail3Validate['success']) {
        return ['success' => false, 'message' => $detail3Validate['message']];
    }

    $detail3PackingValidate = insertTrxShipmentEntryD3Packing($data, $dbconn, $nowDatetime, 'XX-TESTING-XX', $orderPickup['data'], $user);
    if (!$detail3PackingValidate['success']) {
        return ['success' => false, 'message' => $detail3PackingValidate['message']];
    }

    // Now get the queries and set real shipment data
    $lastIDShipmentQuery = "SELECT id FROM trx_shipment_entry ORDER BY id DESC LIMIT 1";
    $lastIDShipment = pg_fetch_object(pg_query($dbconn, $lastIDShipmentQuery));
    if (!$lastIDShipment) {
        return ['success' => false, 'message' => "Terjadi kesalahan saat menyimpan shipment!"];
    }

    $noShipment = "SPE" . date('y') . date('m') . $lastIDShipment->id;
    
    $shipmentEntryQueries = insertTrxShipmentEntry($nowDatetime, $noShipment, $payment, $orderPickup['data'], $customer, $user);
    insertQueries($dbconn, $shipmentEntryQueries['queries']);
    
    $shipmentEntryQueriesD1 = insertTrxShipmentEntryD1($data, $dbconn, $nowDate, $nowDatetime, $noShipment, $orderPickup['data'], $customer, $user);
    $shipmentEntryD1ID = insertQueries($dbconn, $shipmentEntryQueriesD1['queries'], true)['lastId'];
    
    $statusSTTBQueries = insertStatusSTTB($data, $noShipment, $nowDatetime, $orderPickup['data'], $user);
    insertQueries($dbconn, $statusSTTBQueries['queries']);

    $shipmentEntryQueriesD1Service = insertTrxShipmentEntryD1Service($data, $dbconn, $nowDatetime, $orderPickup['data'], $shipmentEntryD1ID, $user);
    insertQueries($dbconn, $shipmentEntryQueriesD1Service['queries']);

    $shipmentEntryQueriesD2 = insertTrxShipmentEntryD2($data, $dbconn, $nowDatetime, $noShipment, $orderPickup['data'], $user);
    insertQueries($dbconn, $shipmentEntryQueriesD2['queries']);

    $shipmentEntryQueriesD3 = insertTrxShipmentEntryD3($data, $dbconn, $nowDatetime, $noShipment, $orderPickup['data'], $user);
    insertQueries($dbconn, $shipmentEntryQueriesD3['queries']);

    $shipmentEntryQueriesD3Packing = insertTrxShipmentEntryD3Packing($data, $dbconn, $nowDatetime, $noShipment, $orderPickup['data'], $user);
    insertQueries($dbconn, $shipmentEntryQueriesD3Packing['queries']);

    return ['success' => true, 'message' => "Data shipment " . $noShipment . " berhasil disimpan!"];
}

if ($_SESSION['id_user_login'] == "") {
    echo json_encode([
        'status' => 401,
        'success' => false,
        'messages' => ['Unauthorized user!']
    ]);
}else{
    $res = saveShipment($_POST, $dbconn, $nowDate, $nowDatetime);
    
    if ($res['success']) {
        echo json_encode([
            'status' => 200,
            'success' => true,
            'messages' => [$res['message']]
        ]);
    }else{
        echo json_encode([
            'status' => 422,
            'success' => false,
            'messages' => [$res['message']]
        ]);
    }
}
