<?php

session_start();

include_once('../../connection.php');

require('./helper.php');
$helper = new Helper($dbconn);

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

    if (!isset($data['address_receipt_id'])) {
        $message = "Mohon isi data alamat penerima terlebih dahulu!";
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

function saveShipment($data, $dbconn, $nowDate, $helper, $nowDatetime) {
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

    $noSTTB = $helper->validateNoSTTB($data);
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
    $detail1Validate = $helper->insertTrxShipmentEntryD1($data, $nowDate, $nowDatetime, 'XX-TESTING-XX', $orderPickup['data'], $customer, $user);
    if (!$detail1Validate['success']) {
        return ['success' => false, 'message' => $detail1Validate['message']];
    }
    
    $detail1ServiceValidate = $helper->insertTrxShipmentEntryD1Service($data, $nowDatetime, $orderPickup['data'], 9999999999, $user);
    if (!$detail1ServiceValidate['success']) {
        return ['success' => false, 'message' => $detail1ServiceValidate['message']];
    }
    
    $detail2Validate = $helper->insertTrxShipmentEntryD2($data, $nowDatetime, 'XX-TESTING-XX', $orderPickup['data'], $user);
    if (!$detail2Validate['success']) {
        return ['success' => false, 'message' => $detail2Validate['message']];
    }

    $detail3Validate = $helper->insertTrxShipmentEntryD3($data, $nowDatetime, 'XX-TESTING-XX', $orderPickup['data'], $user);
    if (!$detail3Validate['success']) {
        return ['success' => false, 'message' => $detail3Validate['message']];
    }

    $detail3PackingValidate = $helper->insertTrxShipmentEntryD3Packing($data, $nowDatetime, 'XX-TESTING-XX', $orderPickup['data'], $user);
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
    
    $shipmentEntryQueriesD1 = $helper->insertTrxShipmentEntryD1($data, $nowDate, $nowDatetime, $noShipment, $orderPickup['data'], $customer, $user);
    $shipmentEntryD1ID = insertQueries($dbconn, $shipmentEntryQueriesD1['queries'], true)['lastId'];
    
    $statusSTTBQueries = $helper->insertStatusSTTB($data, $noShipment, $nowDatetime, $orderPickup['data'], $user);
    insertQueries($dbconn, $statusSTTBQueries['queries']);

    $shipmentEntryQueriesD1Service = $helper->insertTrxShipmentEntryD1Service($data, $nowDatetime, $orderPickup['data'], $shipmentEntryD1ID, $user);
    insertQueries($dbconn, $shipmentEntryQueriesD1Service['queries']);

    $shipmentEntryQueriesD2 = $helper->insertTrxShipmentEntryD2($data, $nowDatetime, $noShipment, $orderPickup['data'], $user);
    insertQueries($dbconn, $shipmentEntryQueriesD2['queries']);

    $shipmentEntryQueriesD3 = $helper->insertTrxShipmentEntryD3($data, $nowDatetime, $noShipment, $orderPickup['data'], $user);
    insertQueries($dbconn, $shipmentEntryQueriesD3['queries']);

    $shipmentEntryQueriesD3Packing = $helper->insertTrxShipmentEntryD3Packing($data, $nowDatetime, $noShipment, $orderPickup['data'], $user);
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
    $res = saveShipment($_POST, $dbconn, $nowDate, $helper, $nowDatetime);
    
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
