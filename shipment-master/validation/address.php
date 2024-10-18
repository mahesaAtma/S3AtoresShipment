<?php

include_once('../../connection.php'); 

$nowDatetime = date("Y-m-d H:i:s");

function canAddressEntry($data, $dbconn) {
    $status = true;
    $messages = [];

    $labelAlamat = $data['label_alamat'];
    if (!in_array($labelAlamat, ['rumah', 'kantor', 'apartment'])) {
        $status = false;
        $messages[] = "Pilihan label alamat tidak valid";
    }

    // var_dump("=============================================");
    // var_dump($data);
    // var_dump("=============================================");

    $alamatPenerimaResponse = validateBasicAddress($data, 'alamat_penerima');
    $namaPenerimaResponse = validateBasicAddress($data, 'nama_penerima');
    $contactPersonResponse = validateBasicAddress($data, 'contact_person');

    $provinsiResponse = findMasterAreas('ms_provinsis', 'nama_provinsi', $data['provinsi'], 'provinsi', $dbconn);
    $kotaResponse = findMasterAreas('ms_kota_kabs', 'nama_kota_kab', $data['kota'], 'kota', $dbconn);
    $kecamatanResponse = findMasterAreas('ms_kecamatans', 'nama_kecamatan', $data['kecamatan'], 'kecamatan', $dbconn);

    if ((!$alamatPenerimaResponse['status'] || !$namaPenerimaResponse['status'] || !$contactPersonResponse['status']) || 
        (!$provinsiResponse['status'] || !$kotaResponse['status'] || !$kecamatanResponse['status'])) {
        $status = false;

        if (isset($provinsiResponse['message'])) {
            array_push($messages, $provinsiResponse['message']);
        }

        if (isset($kotaResponse['message'])) {
            array_push($messages, $kotaResponse['message']);
        }

        if (isset($kecamatanResponse['message'])) {
            array_push($messages, $kecamatanResponse['message']);
        }

        foreach ($alamatPenerimaResponse['messages'] as $message) {
            array_push($messages, $message);
        }

        foreach ($namaPenerimaResponse['messages'] as $message) {
            array_push($messages, $message);
        }

        foreach ($contactPersonResponse['messages'] as $message) {
            array_push($messages, $message);
        }
    }

    if (!$status) {
        return compact('status', 'messages');
    }

    $res = [
        'label_alamat' => $labelAlamat,
        'alamat_penerima' => $alamatPenerimaResponse['value'],
        'nama_penerima' => $namaPenerimaResponse['value'],
        'contact_person' => $contactPersonResponse['value'],
        'provinsi_id' => $provinsiResponse['id'],
        'kota_id' => $kotaResponse['id'],
        'kecamatan_id' => $kecamatanResponse['id'],
        'customer_id' => $_POST['customer_id'],
        'trx_order_pickup_d1_id' => $_POST['trx_order_pickup_d1_id'],
    ];

    return compact('status', 'messages', 'res');
}

function validateBasicAddress($data, $label){
    $status = true;
    $messages = [];
    $value = $data[$label];

    $allowedNull = ['alamat_penerima', 'contact_person'];

    if (strlen($value) <= 0 && in_array($label, $allowedNull)) {
        $value = null;
        return compact("status", "messages", "value");
    }

    if (strlen($value) <= 0) {
        $status = false; 
        array_push($messages, "Isian $label tidak boleh kosong!");
    }

    if ($label === 'contact_person') {
        if (strlen($value) < 10 || strlen($value) > 13) {
            $status = false; 
            array_push($messages, "Maksimum nomor hp adalah 13 dan minimum 10!");
        }
    }

    return compact("status", "messages", "value");
}

function findMasterAreas($table, $column, $columnValue, $label, $dbconn){
    $allowedNull = ['kecamatan'];
    if (strlen($columnValue) <= 0 && in_array($label, $allowedNull)) {
        return [
            'status' => true,
            'id' => null
        ];
    }

    $sql = "SELECT id FROM $table WHERE $column = '$columnValue'";
    $query = pg_query($dbconn, $sql); 
    $obj = pg_fetch_object($query);

    if (!$obj) {
        return [
            'status' => false,
            'message' => "Data $label tidak ditemukan!"
        ];
    }

    return [
        'status' => true,
        'id' => $obj->id
    ];
}

$canInsert = canAddressEntry($_POST, $dbconn);

if ($canInsert['status']) {
    $data = $canInsert['res'];

    // value to insert nama_send_receipt? below code still on discuss
    $queryInsert = "INSERT INTO ms_cust_send_receipt (
            customer_id,
            nama_send_receipt,
            alamat,
            nama_pic,
            phone_pic,
            provinsi_id,
            kotakab_id,
            kecamatan_id,
            jenis_alamat,
            label_alamat,
            status_record,
            created_at,
            updated_at
        ) VALUES (
            " . pg_escape_string($data['customer_id']) . ",
            " . (is_null($data['nama_penerima']) ? 'NULL' : "'" . pg_escape_string($data['nama_penerima']) . "'") .",
            " . (is_null($data['alamat_penerima']) ? 'NULL' : "'" . pg_escape_string($data['alamat_penerima']) . "'") .",
            " . (is_null($data['nama_penerima']) ? 'NULL' : "'" . pg_escape_string($data['nama_penerima']) . "'") .",
            " . (is_null($data['contact_person']) ? 'NULL' : "'" . pg_escape_string($data['contact_person']) . "'") .",
            " . (is_null($data['provinsi_id']) ? 'NULL' : "'" . pg_escape_string($data['provinsi_id']) . "'") .",
            " . (is_null($data['kota_id']) ? 'NULL' : "'" . pg_escape_string($data['kota_id']) . "'") .",
            " . (is_null($data['kecamatan_id']) ? 'NULL' : "'" . pg_escape_string($data['kecamatan_id']) . "'") .",
            'RECEIPT',
            '" . pg_escape_string($data['label_alamat']) . "',
            1,
            '" . $nowDatetime . "',
            '" . $nowDatetime . "'
        ) RETURNING id;";

    $insert = pg_query($dbconn, $queryInsert);
    if (pg_num_rows($insert) <= 0) {
        $lastId = pg_fetch_array($insert,0,PGSQL_ASSOC);

        $queryUpdate = "UPDATE trx_order_pickup_d1
            SET custsentreceipt_id = " . $lastId['id'] . "
            WHERE id = " . $data['trx_order_pickup_d1_id'] . ";";
        pg_query($dbconn, $queryUpdate);
    }
    
    echo json_encode([
        'status' => 200,
        'success' => true,
        'messages' => ['Data telah terinput dan dirubah!']
    ]);
}else{
    echo json_encode([
        'status' => 422,
        'success' => false,
        'messages' => $canInsert['messages']
    ]);
}