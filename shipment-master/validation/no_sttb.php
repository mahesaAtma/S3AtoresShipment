<?php

include_once('../../connection.php');

function validateNoSttb($data, $dbconn) {
    if (!isset($data['no_sttb'])) {
        return ['success' => true, 'message' => 'Field yang dibutuhkan tidak valid!'];
    }

    $noSTTB = $data['no_sttb'];

    $noSTTBQuery = "SELECT id FROM trx_order_sttb_d WHERE no_sttb_fisik = '" . pg_escape_string($noSTTB) . "';";
    $isSTTBExists = pg_fetch_object(pg_query($dbconn, $noSTTBQuery));
    if (!$isSTTBExists) {
        return ['success' => false, 'message' => "Data nomor sttb " . $noSTTB . " tidak dapat ditemukan!"];
    }
    
    $sttbShipmentQuery = "SELECT id FROM trx_shipment_entry_d1 WHERE no_sttb = '" . pg_escape_string($noSTTB) . "';";
    $isSTTBUsed = pg_fetch_object(pg_query($dbconn, $sttbShipmentQuery));
    if ($isSTTBUsed) {
        return ['success' => false, 'message' => "Data nomor sttb " . $noSTTB . " telah digunakan!"];
    }
    
    return ['success' => true, 'message' => "Data nomor sttb " . $noSTTB . " dapat digunakan!"];
}

$res = validateNoSttb($_POST, $dbconn);

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