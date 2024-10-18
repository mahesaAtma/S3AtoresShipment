<?php

session_start();

include_once('../../connection.php');

function updateAddress($data, $dbconn) {
    if (!isset($data['custsendreceipt_id']) || !isset($data['order_pickup_d1_id']) ) {
        return ['success' => false, 'message' => 'Field yang dibutuhkan tidak valid!'];
    }

    $userQuery = "SELECT id,name FROM users WHERE id = " . $_SESSION['id_user_login'] . " AND role_id = '119';";
    $user = pg_fetch_object(pg_query($dbconn, $userQuery));
    if (!$user) {
        return ['success' => false, 'message' => "Tidak ada otorisasi!"];
    }

    $orderPickupD1ID = $data['order_pickup_d1_id'];

    $querySelect = "SELECT id FROM trx_order_pickup_d1 WHERE id = " . pg_escape_string($orderPickupD1ID) . ";";
    $orderPickupD1Record = pg_fetch_object(pg_query($dbconn, $querySelect));
    if (!$orderPickupD1Record) {
        return ['success' => false, 'message' => "Data order pickup tidak dapat ditemukan!"];
    }

    $queryUpdate = "UPDATE trx_order_pickup_d1 SET custsentreceipt_id = " . pg_escape_string($data['custsendreceipt_id']) . "WHERE id = " . pg_escape_string($orderPickupD1ID) . ";";
    pg_query($dbconn, $queryUpdate);

    return ['success' => true, 'message' => "Alamat berhasil diubah!"];
}

$res = updateAddress($_POST, $dbconn);

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