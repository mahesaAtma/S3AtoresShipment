<?php

/**
 * Note: 
 * This is almost a duplicate from save_shipment.php
 * The difference only took in payment, address, and no shipment 
 */

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

    // if (!isset($data['document_data'])) {
    //     $message = "Mohon isi data dokumen terlebih dahulu!";
    // }

    if (!isset($data['service_data'])) {
        $message = "Mohon isi data service terlebih dahulu!";
    }

    if (!isset($data['customer_id']) || !isset($data['no_shipment_entry']) ||
        !isset($data['no_sttb']) || !isset($data['sttb_input_type'])) {
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

    $noShipment = $data['no_shipment_entry'];

    $shipmentQuery = "SELECT id,task_no FROM trx_shipment_entry WHERE no_shipment_entry = '" . $noShipment . "';";
    $shipment = pg_fetch_object(pg_query($dbconn, $shipmentQuery));
    if (!$shipment) {
        return ['success' => false, 'message' => "Nomor Shipment " . $noShipment . " tidak dapat ditemukan!"];
    }

    $customerQuery = "SELECT id,name,address,phone,name_pic,phone_pic FROM ms_customers WHERE id = " . $data['customer_id'] . ";";
    $customer = pg_fetch_object(pg_query($dbconn, $customerQuery));
    if (!$customer) {
        return ['success' => false, 'message' => "Data customer " . $data['customer_id'] . " tidak dapat ditemukan!"];
    }

    $noSTTB = $helper->validateNoSTTB($data, $dbconn);
    if (!$noSTTB['success']) {
        return ['success' => false, 'message' => $noSTTB['message']];
    }

    $orderPickupQuery = "SELECT 
            detail.id,
            detail.custsentreceipt_id,
            master.cabang_id,
            master.unit_id
        FROM trx_order_pickup_d1 AS detail
        LEFT JOIN trx_order_pickup AS master ON detail.no_order_pickup = master.no_order_pickup 
        WHERE detail.task_no = '" . $shipment->task_no . "';";
    $orderPickup = pg_fetch_object(pg_query($dbconn, $orderPickupQuery));
    if (!$orderPickup) {
        return ['success' => false, 'message' => "Nomor task " . $shipment->task_no . " tidak dapat ditemukan!"];
    }

    $cabangQuery = "SELECT id FROM ms_cabangs WHERE id = '" . $orderPickup->cabang_id . "';"; 
    $cabang = pg_fetch_object(pg_query($dbconn, $cabangQuery));
    if (!$cabang) {
        return ['success' => false, 'message' => "Data cabang " . $orderPickup->cabang_id . " dari order pickup tidak dapat ditemukan!"];
    }

    $unitQuery = "SELECT id FROM ms_units WHERE id = '" . $orderPickup->unit_id . "';"; 
    $unit = pg_fetch_object(pg_query($dbconn, $unitQuery));
    if (!$unit) {
        return ['success' => false, 'message' => "Data unit " . $orderPickup->unit_id . " dari order pickup tidak dapat ditemukan!"];
    }
    
    // Validate all input first and ignore query returned & ignored no shipment & last detail 1 id
    $detail1Validate = $helper->insertTrxShipmentEntryD1($data, $nowDate, $nowDatetime, 'XX-TESTING-XX', $orderPickup, $customer, $user);
    if (!$detail1Validate['success']) {
        return ['success' => false, 'message' => $detail1Validate['message']];
    }
    
    $detail1ServiceValidate = $helper->insertTrxShipmentEntryD1Service($data, $nowDatetime, $orderPickup, 9999999999, $user);
    if (!$detail1ServiceValidate['success']) {
        return ['success' => false, 'message' => $detail1ServiceValidate['message']];
    }
    
    $detail2Validate = $helper->insertTrxShipmentEntryD2($data, $nowDatetime, 'XX-TESTING-XX', $orderPickup, $user);
    if (!$detail2Validate['success']) {
        return ['success' => false, 'message' => $detail2Validate['message']];
    }

    $detail3Validate = $helper->insertTrxShipmentEntryD3($data, $nowDatetime, 'XX-TESTING-XX', $orderPickup, $user);
    if (!$detail3Validate['success']) {
        return ['success' => false, 'message' => $detail3Validate['message']];
    }

    $detail3PackingValidate = $helper->insertTrxShipmentEntryD3Packing($data, $nowDatetime, 'XX-TESTING-XX', $orderPickup, $user);
    if (!$detail3PackingValidate['success']) {
        return ['success' => false, 'message' => $detail3PackingValidate['message']];
    }
    
    // Now get the queries and set real shipment data  
    $shipmentEntryQueriesD1 = $helper->insertTrxShipmentEntryD1($data, $nowDate, $nowDatetime, $noShipment, $orderPickup, $customer, $user);
    $shipmentEntryD1ID = insertQueries($dbconn, $shipmentEntryQueriesD1['queries'], true)['lastId'];
    
    $statusSTTBQueries = $helper->insertStatusSTTB($data, $noShipment, $nowDatetime, $orderPickup, $user);
    insertQueries($dbconn, $statusSTTBQueries['queries']);

    $shipmentEntryQueriesD1Service = $helper->insertTrxShipmentEntryD1Service($data, $nowDatetime, $orderPickup, $shipmentEntryD1ID, $user);
    insertQueries($dbconn, $shipmentEntryQueriesD1Service['queries']);

    $shipmentEntryQueriesD2 = $helper->insertTrxShipmentEntryD2($data, $nowDatetime, $noShipment, $orderPickup, $user);
    insertQueries($dbconn, $shipmentEntryQueriesD2['queries']);

    $shipmentEntryQueriesD3 = $helper->insertTrxShipmentEntryD3($data, $nowDatetime, $noShipment, $orderPickup, $user);
    insertQueries($dbconn, $shipmentEntryQueriesD3['queries']);

    $shipmentEntryQueriesD3Packing = $helper->insertTrxShipmentEntryD3Packing($data, $nowDatetime, $noShipment, $orderPickup, $user);
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
