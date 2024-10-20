<?php

session_start();

include_once('../../connection.php');

function validateInput($data) {
    $message = null;

    if (!isset($data['no_sttb']) || $data['no_sttb'] == '') {
        $message = "Mohon isi nomor sttb terlebih dahulu!";
    }

    if (!isset($data['sttb_input_type'])) {
        $message = "Mohon isi tipe sttb terlebih dahulu!";
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

function validateSTTB($data, $dbconn) {
    $validatedInput = validateInput($data);
    if (!$validatedInput['status']) {
        return ['success' => false, 'message' => $validatedInput['message']];
    }

    $noSTTB = validateNoSTTB($data, $dbconn);
    if (!$noSTTB['success']) {
        return ['success' => false, 'message' => $noSTTB['message']];
    }

    return ['success' => true, 'message' => "Data nomor sttb " . $data['no_sttb'] . " dapat dipakai!"];
}

if ($_SESSION['id_user_login'] == "") {
    echo json_encode([
        'status' => 401,
        'success' => false,
        'messages' => ['Unauthorized user!']
    ]);
}else{
    $res = validateSTTB($_POST, $dbconn);
    
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
