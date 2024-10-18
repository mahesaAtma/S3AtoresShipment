<?php

session_start();

include_once('../../connection.php');

function cancelSTTB($data, $dbconn) {
    if (!isset($data['no_shipment_entry'])) {
        return ['success' => false, 'message' => 'Field yang dibutuhkan tidak valid!'];
    }

    $userQuery = "SELECT id,name FROM users WHERE id = " . $_SESSION['id_user_login'] . " AND role_id = '119';";
    $user = pg_fetch_object(pg_query($dbconn, $userQuery));
    if (!$user) {
        return ['success' => false, 'message' => "Tidak ada otorisasi!"];
    }
    $noShipmentEntry = $data['no_shipment_entry'];

    $querySelect = "SELECT id FROM trx_shipment_entry_d1 
        WHERE no_shipment_entry = '" . pg_escape_string($noShipmentEntry) . "';";
    $shipmentRecord = pg_fetch_all(pg_query($dbconn, $querySelect));
    if (!$shipmentRecord) {
        return ['success' => false, 'message' => "Data nomor shipment tidak dapat ditemukan!"];
    }

    $shipmentDetailD1Ids = array_map(function($itm) {
        return $itm['id'];
    }, $shipmentRecord);

    $deleteQuery = [];
    $deleteQuery[] = "DELETE FROM trx_status_sttb WHERE no_ref_process = '" . pg_escape_string($noShipmentEntry) . "';";
    $deleteQuery[] = "DELETE FROM trx_shipment_entry_d3 WHERE no_shipment_entry = '" . pg_escape_string($noShipmentEntry) . "';";
    $deleteQuery[] = "DELETE FROM trx_shipment_entry_d2 WHERE no_shipment_entry = '" . pg_escape_string($noShipmentEntry) . "';";
    $deleteQuery[] = "DELETE FROM trx_shipment_entry_d1_service WHERE trx_shipment_entry_d1_id IN (" . implode(',', $shipmentDetailD1Ids) . ");";
    $deleteQuery[] = "DELETE FROM trx_shipment_entry_d1 WHERE no_shipment_entry = '" . pg_escape_string($noShipmentEntry) . "';";
    $deleteQuery[] = "DELETE FROM trx_shipment_entry WHERE no_shipment_entry = '" . pg_escape_string($noShipmentEntry) . "';";
    
    foreach ($deleteQuery as $query) {
        pg_query($dbconn, $query);
    }

    return ['success' => true, 'message' => "Nomor shipment " . $noShipmentEntry . " berhasil dihapus!"];
}

$res = cancelSTTB($_POST, $dbconn);

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