<?php

include_once('../../connection.php');

function updatePickupReject($data ,$dbconn) {

    if (!(isset($data['reject_reason']) && isset($data['pickup_order_id']))) {
        return ['success' => false, 'message' => 'Pilihan alasan penolakan tidak valid!'];
    }

    $rejectReasonInput = $data['reject_reason'];
    $pickupOrderID = $data['pickup_order_id'];

    if (!in_array($rejectReasonInput, ["Armada sedang mengalami maintenance", "Armada sudah penuh", "Armada trouble"])) {
        return ['success' => false, 'message' => 'Pilihan alasan penolakan tidak valid!'];
    }

    $querySelect = "SELECT status_task FROM trx_order_pickup WHERE id = " . $pickupOrderID . ";";
    $orderPickupRecord = pg_fetch_object(pg_query($dbconn, $querySelect));
    if ($orderPickupRecord->{'status_task'} !== '115') {
        return ['success' => false, 'message' => "Saat penolakan status yang diperbolehkan hanya waiting!"];
    }
    
    $queryUpdate = "UPDATE trx_order_pickup 
        SET status_task = '143', keterangan = '" . $rejectReasonInput . "', updated_at = NOW() 
        WHERE id = " . $pickupOrderID . ";";

    $rowUpdated = pg_query($dbconn, $queryUpdate);
    if (pg_affected_rows($rowUpdated) <= 0) {
        return ['success' => false, 'message' => 'Terjadi kesalahan saat update!'];
    }

    return ['success' => true, 'message' => 'Pickup order berhasil ditolak!'];
}

$res = updatePickupReject($_POST, $dbconn);
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
