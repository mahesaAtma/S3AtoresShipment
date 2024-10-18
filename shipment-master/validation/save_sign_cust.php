<?php

session_start();

include_once('../../connection.php');

function saveSignCust($data, $dbconn) {
    if (!isset($data['sign_image_encoded']) && !isset($data['no_shipment_entry'])) {
        return ['success' => true, 'message' => 'Field yang dibutuhkan tidak valid!'];
    }

    $userQuery = "SELECT id,name FROM users WHERE id = " . $_SESSION['id_user_login'] . " AND role_id = '119';";
    $user = pg_fetch_object(pg_query($dbconn, $userQuery));
    if (!$user) {
        return ['success' => false, 'message' => "Tidak ada otorisasi!"];
    }

    $signImageEncoded = $data['sign_image_encoded'];
    $noShipmentEntry = $data['no_shipment_entry'];

    $querySelect = "SELECT id FROM trx_shipment_entry 
        WHERE no_shipment_entry = '" . pg_escape_string($noShipmentEntry) . "';";
    $shipmentRecord = pg_fetch_object(pg_query($dbconn, $querySelect));
    if (!$shipmentRecord) {
        return ['success' => false, 'message' => "Data nomor shipment tidak dapat ditemukan!"];
    }
    
    $queryUpdate = "UPDATE trx_shipment_entry
        SET sign_cust = '" . pg_escape_string($signImageEncoded) . "'
        WHERE no_shipment_entry = '" . pg_escape_string($noShipmentEntry) . "';";
    pg_query($dbconn, $queryUpdate);
    
    return ['success' => true, 'message' => "Nomor shipment " . $noShipmentEntry . " berhasil dibuat!"];
}

$res = saveSignCust($_POST, $dbconn);

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