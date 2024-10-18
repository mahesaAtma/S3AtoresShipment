<?php

session_start();
require('../db_class.php');
$obj = new Db_Class();
include_once('../connection.php');

$resultScan     = $_POST['resultScan'];
$no_unloading   = $_POST['no_unloading'];
$created_by     = $_SESSION['id_karyawan'];
$tgl            = date('Y-m-d H:i:s');

if (strpos($resultScan, 'BAG') === 0) { // jika pada scan terbaca BAG
    
    $parts      = explode("_", $resultScan);
    $no_sttb    = $parts[1]; 
    $jml_koli   = $parts[2];

    $shipmentEntryD1 = pg_query("SELECT b.task_no FROM trx_shipment_entry_d1 a
    LEFT JOIN trx_shipment_entry b ON b.no_shipment_entry = a.no_shipment_entry
    LEFT JOIN trx_task c ON c.task_no = b.task_no
    WHERE a.no_sttb = '$no_sttb'");
    $a          = pg_fetch_assoc($shipmentEntryD1);
    $task_no    = $a['task_no'];

    if (pg_num_rows($shipmentEntryD1) == 0) {
        $data = 0; // Tidak ada shipment entry dengan no.sttb
    }

    $tasks = pg_query("SELECT * FROM trx_task a
    LEFT JOIN trx_shipment_entry b ON b.task_no = a.task_no
    LEFT JOIN trx_shipment_entry_d1 c ON c.no_shipment_entry = b.no_shipment_entry
    LEFT JOIN trx_shipment_entry_d1_service d ON d.trx_shipment_entry_d1_id = c.id
    WHERE a.task_no = '$task_no'");
    $b = pg_fetch_assoc($tasks);

    if (pg_num_rows($task) == 0) {
        $data = 2; // Tidak ada trx task dengan no sttb
    }

    foreach($tasks as $task) {
        if ($task['status_task'] != 130) {
            $data = 3; // Status task belum arrival origin
        }
    }

    for ($i = 0; $i < $jml_koli; $i++) {

        $insertUnloading = "INSERT INTO trx_unloading_d1 (no_unloading, no_sttb, no_sub_sttb, no_shipment_entry, unit_id, cabang_id, created_at, updated_at, status_scan, id_karyawan, service, status_bongkar)";

        $cekInsert = pg_query($insertUnloading);

    }

} 

echo json_encode($data);


?>