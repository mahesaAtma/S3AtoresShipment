<?php

session_start();

include_once('../../connection.php');

function removeSTTB($data, $dbconn) {
    if (!isset($data['shipment_detail_id'])) {
        return ['success' => false, 'message' => 'Field yang dibutuhkan tidak valid!'];
    }

    $userQuery = "SELECT id,name FROM users WHERE id = " . $_SESSION['id_user_login'] . " AND role_id = '119';";
    $user = pg_fetch_object(pg_query($dbconn, $userQuery));
    if (!$user) {
        return ['success' => false, 'message' => "Tidak ada otorisasi!"];
    }

    $shipmentDetailID = $data['shipment_detail_id'];

    $querySelect = "SELECT id,no_sttb,no_shipment_entry FROM trx_shipment_entry_d1 
        WHERE id = " . pg_escape_string($shipmentDetailID) . ";";
    $shipmentDetailRecord = pg_fetch_object(pg_query($dbconn, $querySelect));
    if (!$shipmentDetailRecord) {
        return ['success' => false, 'message' => "Data nomor shipment tidak dapat ditemukan!"];
    }

    $totalSTTBQuery = "SELECT id FROM trx_shipment_entry_d1 WHERE no_shipment_entry = '" . $shipmentDetailRecord->no_shipment_entry .  "';";
    $totalSTTBRecord = pg_fetch_all(pg_query($dbconn, $totalSTTBQuery));
    if (!$totalSTTBRecord) {
        return ['success' => false, 'message' => "Data nomor shipment tidak dapat ditemukan!"];
    }
    
    if (count($totalSTTBRecord) <= 1) {
        return ['success' => false, 'message' => "Untuk nomor sttb terakhir, mohon gunakan fitur batalkan transaksi!"];
    }

    $deleteQuery = [];
    $deleteQuery[] = "DELETE FROM trx_status_sttb WHERE no_sttb = '" . $shipmentDetailRecord->no_sttb . "';";
    $deleteQuery[] = "DELETE FROM trx_shipment_entry_d1_service WHERE trx_shipment_entry_d1_id = " . $shipmentDetailRecord->id . ";";
    $deleteQuery[] = "DELETE FROM trx_shipment_entry_d1 WHERE id = " . $shipmentDetailRecord->id . ";";
    $deleteQuery[] = "DELETE FROM trx_shipment_entry_d2 WHERE no_sttb = '" . $shipmentDetailRecord->no_sttb . "';";
    $deleteQuery[] = "DELETE FROM trx_shipment_entry_d3 WHERE no_sttb = '" . $shipmentDetailRecord->no_sttb . "';";
    
    foreach ($deleteQuery as $query) {
        pg_query($dbconn, $query);
    }

    return ['success' => true, 'message' => "Nomor sttb " . $shipmentDetailRecord->no_sttb . " berhasil dihapus!"];
}

$res = removeSTTB($_POST, $dbconn);

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