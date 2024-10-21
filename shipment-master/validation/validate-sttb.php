<?php

session_start();

include_once('../../connection.php');

require('./helper.php');
$helper = new Helper($dbconn);

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

function validateSTTB($data, $dbconn, $helper) {
    $validatedInput = validateInput($data);
    if (!$validatedInput['status']) {
        return ['success' => false, 'message' => $validatedInput['message']];
    }

    $noSTTB = $helper->validateNoSTTB($data, $dbconn);
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
    $res = validateSTTB($_POST, $dbconn, $helper);
    
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
