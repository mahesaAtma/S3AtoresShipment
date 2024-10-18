<?php

include_once('../../connection.php');

$noSTTBLike = 'E' . date('Y');

$query = "SELECT no_sttb FROM trx_shipment_entry_d1
    WHERE no_sttb LIKE '%" . $noSTTBLike . "%'
    ORDER BY no_sttb DESC LIMIT 1";
$noSTTB = pg_fetch_object(pg_query($dbconn, $query));

if (!$noSTTB) {
    echo json_encode([
        'success' => true,
        'status' => 200,
        'message' => 'Terjadi kesalahan saat mengambil nomor sttb fisik, mohon coba lagi!'
    ]);
}else{
    $format = substr($noSTTB->no_sttb, 0, 5);
    $number =  substr($noSTTB->no_sttb, 5);
    
    $newNoSTTB = $format . (((int) $number) + 1);
    
    echo json_encode([
        'success' => true,
        'status' => 200,
        'new_no_sttb' => $newNoSTTB
    ]);
}

