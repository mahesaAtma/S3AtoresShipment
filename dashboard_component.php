<?php 
error_reporting(0);
session_start();
include_once('connection.php');
date_default_timezone_set('Asia/Jakarta'); 

$idLogin = $_SESSION['id_user_login'];

$sqlCount = "SELECT COUNT(*) FROM trx_shipment_entry_d1 
LEFT JOIN trx_pickup_upload ON awb_number = trx_shipment_entry_d1.no_sttb 
WHERE trx_pickup_upload.scan_by = '".$idLogin."'";

$count = pg_query($sqlCount);

while ($sqlfetchCount =  pg_fetch_assoc($count))
{
    $jumlahSTTB = $sqlfetchCount['count'];
}


$sqlQuery = "SELECT trx_shipment_entry_d1.no_shipment_entry,trx_shipment_entry_d1.no_sttb,trx_shipment_entry_d1.created_at FROM trx_shipment_entry_d1
LEFT JOIN trx_pickup_upload ON awb_number = trx_shipment_entry_d1.no_sttb 
WHERE trx_pickup_upload.scan_by = '".$idLogin."'ORDER BY trx_shipment_entry_d1.created_at DESC LIMIT 1";

$hasil = pg_query($sqlQuery);


while ($sqlfetch =  pg_fetch_assoc($hasil))
{

    $Date       = $sqlfetch['created_at'];
    $DateStart  = date_create($Date);
    $DateEnd    = date_create(date('Y-m-d H:i:s'));
    $diff       = date_diff($DateStart,$DateEnd);

    $sqlfetch['lastd']  = $diff->s;
    $sqlfetch['lastm']  = $diff->i;
    $sqlfetch['lasth']  = $diff->h;
    $sqlfetch['lastdy'] = $diff->d;

    $sqlfetch['count']  = $jumlahSTTB;

    echo json_encode($sqlfetch);                       
}







?>