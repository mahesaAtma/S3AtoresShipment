<?php

session_start();

include_once('../../connection.php');

$nowDatetime = date("Y-m-d H:i:s");

function validateInput($data) {
    if (!isset($data['service_data'])) return false;
    if (!isset($data['shipment_detail_id'])) return false;
    return true;
}

function insertTrxShipmentEntryD1Service($data, $dbconn, $nowDatetime, $shipmentDetail, $shipmentEntryD1ID, $user){
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

    if (isset($serviceData['nama_barang'])) {
        $jenisBarangQuery = "SELECT id FROM ms_jenis_barang WHERE nama_barang = '" . strtoupper($serviceData['nama_barang']) . "';";
        $jenisBarang = pg_fetch_object(pg_query($dbconn, $jenisBarangQuery));
        if (!$jenisBarang) {
            return ['success' => false, 'message' => "Nama barang " . $serviceData['nama_barang'] . " tidak dapat ditemukan!"];
        }
    }

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
        '" . $shipmentDetail->unit_id . "',
        '" . $shipmentDetail->cabang_id . "',
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
                    '" . $shipmentDetail->unit_id . "',
                    '" . $shipmentDetail->cabang_id . "',
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
            '" . $shipmentDetail->unit_id . "',
            '" . $shipmentDetail->cabang_id . "',
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
            '" . $shipmentDetail->unit_id . "',
            '" . $shipmentDetail->cabang_id . "',
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

function editNoSttb($data, $dbconn, $nowDatetime) {
    $userQuery = "SELECT id,name FROM users WHERE id = " . $_SESSION['id_user_login'] . " AND role_id = '119';";
    $user = pg_fetch_object(pg_query($dbconn, $userQuery));
    if (!$user) {
        return ['success' => false, 'message' => "Data driver tidak dapat ditemukan!"];
    }

    if (!isset($data['shipment_detail_id'])) {
        return ['success' => true, 'message' => 'Field yang dibutuhkan tidak valid!'];
    }

    if (!validateInput($data)) {
        return ['success' => false, 'message' => "Field yang dibutuhkan tidak valid!"];
    }

    $shipmentD1ID = $data['shipment_detail_id'];

    $selectQuery = "SELECT 
            detail.id AS shipment_d1_id,
            detail.cabang_id,
            detail.unit_id,
            shipment.no_shipment_entry,
            shipment.no_order_pickup
        FROM trx_shipment_entry_d1 AS detail
        LEFT JOIN trx_shipment_entry AS shipment ON detail.no_shipment_entry = shipment.no_shipment_entry
        WHERE detail.id = " . pg_escape_string($shipmentD1ID) . ";";

    $shipmentDetail = pg_fetch_object(pg_query($dbconn, $selectQuery));
    if (!$shipmentDetail) {
        return ['success' => false, 'message' => "Data nomor shipment detail tidak dapat ditemukan!"];
    }

    $deleteQuery = "DELETE FROM trx_shipment_entry_d1_service WHERE trx_shipment_entry_d1_id = " . pg_escape_string($shipmentD1ID) . ";";
    pg_query($dbconn, $deleteQuery);

    $insertQuery = insertTrxShipmentEntryD1Service($data, $dbconn, $nowDatetime, $shipmentDetail, $shipmentD1ID, $user);
    if (!$insertQuery['success']) {
        return ['success' => true, 'message' => $insertQuery['message']];
    }
    
    foreach ($insertQuery['queries'] as $query) {
        pg_query($dbconn, $query);
    }

    return ['success' => true, 'message' => "Data nomor shipment detail berhasil diubah!"];
}


if ($_SESSION['id_user_login'] == "") {
    echo json_encode([
        'status' => 401,
        'success' => false,
        'messages' => ['Unauthorized user!']
    ]);
}else{
    $res = editNoSttb($_POST, $dbconn, $nowDatetime);
    
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

