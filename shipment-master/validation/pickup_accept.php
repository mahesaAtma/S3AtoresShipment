<?php

include_once('../../connection.php'); 

function updateAcceptPickup($data, $dbconn){
    if (!isset($data['order_pickup_id'])) {
        return ['success' => false, 'message' => "Field yang dibutuhkan tidak valid!"];
    }
    
    $pickupOrderID = $data['order_pickup_id'];

    $querySelect = "SELECT status_task FROM trx_order_pickup WHERE id = " . pg_escape_string($_POST['order_pickup_id']) . ";";
    $orderPickupRecord = pg_fetch_object(pg_query($dbconn, $querySelect));
    if ($orderPickupRecord->{'status_task'} !== '115') {
        return ['success' => false, 'message' => "Saat penolakan status yang diperbolehkan hanya waiting!"];
    }
    
    $queryUpdate = "UPDATE trx_order_pickup 
        SET status_task = '116', updated_at = NOW() 
        WHERE id = " . $pickupOrderID . ";";

    $rowUpdated = pg_query($dbconn, $queryUpdate);
    if (pg_affected_rows($rowUpdated) <= 0) {
        return ['success' => false, 'message' => 'Terjadi kesalahan saat update!'];
    }

    return ['success' => true, 'message' => 'Pickup order berhasil diterima!'];
}


$res = updateAcceptPickup($_POST, $dbconn);

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