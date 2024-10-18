<?php 

session_start();
require('../db_class.php');
$obj = new Db_Class();
include_once('../connection.php');

header('Content-Type: application/json');
date_default_timezone_set('Asia/Jakarta');

$no_loading     = $_POST['no_loading'];
$type_delivery  = $_POST['type_delivery'];
$cabang_tujuan  = $_POST['cabang_tujuan'];
$resultScan     = $_POST['resultScan'];
$latitude       = $_POST['latitude'];
$longitude      = $_POST['longitude'];
$cabang_id      = $_SESSION['cabang_id'];
$unit_id        = $_SESSION['unit_id'];
$id_karyawan    = $_SESSION['id_karyawan'];
$tgl            = date('Y-m-d H:i:s');

if (strpos($resultScan, 'BAG') === 0) { // BARCODE BAGGING
    $data           = explode("_", $resultScan);
    $no_sttb        = $data[1];
    $jml_koli       = $data[2];
    $sub_sttb       = 1;

} else { // BARCODE STTB/DELAMI
    $data           = explode("_", $resultScan);
    $count          = count($data);
    $no_sttb        = $data[0];

    if ($count == 3) {
        $sub_sttb   = $data[2];
    } else if ($count == 1) {
        $sub_sttb   = 1;
    } else {
        echo json_encode(["status" => "formatnotfound", "no_sttb" => $no_sttb]);
        exit;
    }
}

$cekShipment    = pg_query("SELECT a.no_sttb, b.service_id, c.customer_id, a.no_shipment_entry,
COALESCE(CAST(SUM(COALESCE(CAST(b.jml_koli AS DECIMAL(10)), 0)) AS DECIMAL(10, 0)), 0) AS koli_total
FROM trx_shipment_entry_d1 a
LEFT JOIN trx_shipment_entry_d1_service b ON b.trx_shipment_entry_d1_id = a.id
LEFT JOIN trx_shipment_entry c ON c.no_shipment_entry = a.no_shipment_entry
WHERE a.no_sttb = '$no_sttb'
GROUP BY a.no_sttb, b.service_id, c.customer_id, a.no_shipment_entry LIMIT 1");

$resultShipment = pg_fetch_assoc($cekShipment);
$koli           = $resultShipment['koli_total'];
$service_id     = $resultShipment['service_id'];

$loadingD1 = pg_query("SELECT a.no_loading, a.no_sttb, a.service, a.no_sub_sttb
FROM trx_loading_d1 a
WHERE a.no_loading = '$no_loading' AND a.no_sttb = '$no_sttb' AND a.service = '$service_id' AND a.no_sub_sttb = '$sub_sttb'
");
$resultLoadingD1 = pg_num_rows($loadingD1);

if ($resultLoadingD1 < 1) {
    $routeQuery = pg_query("SELECT d.kota_kab_id
    FROM trx_loading a
    LEFT JOIN trx_delivery_sheet b ON b.no_delivery = a.no_order_delivery
    LEFT JOIN ms_routes c ON c.id = b.route_id
    LEFT JOIN ms_routes_d d ON d.route_id = c.id
    WHERE a.no_loading = '$no_loading'");

    $routeSenderQuery = pg_query("SELECT b.kotakab_id
    FROM trx_shipment_entry_d1 a
    LEFT JOIN ms_cust_send_receipt b ON b.id = a.alamat_penerima
    WHERE a.no_sttb = '$no_sttb'");

    $cekRoute = [];
    while ($row = pg_fetch_assoc($routeQuery)) {
        $cekRoute[] = $row['kota_kab_id'];
    }

    $cekRouteSender = [];
    while ($row = pg_fetch_assoc($routeSenderQuery)) {
        $cekRouteSender[] = $row['kotakab_id'];
    }

    $found = false;
    foreach ($cekRouteSender as $sender) {
        if (in_array($sender, $cekRoute)) {
            $found = true;
            break;
        }
    }

    // Lanjutkan proses jika ada kecocokan
    if (!$found) {
        echo json_encode(["status" => "rutenotfound", "no_sttb" => $no_sttb]);
        exit;
    } else {
        
        // GENERATE LOADING
        $unloadingD1 = pg_query("SELECT a.no_sttb 
        FROM trx_unloading_d1 a 
        WHERE a.no_sttb = '$no_sttb' AND a.service = '$service_id'");
        $resultUnloadingD1 = pg_num_rows($unloadingD1);

        if ($resultUnloadingD1 < 1) {
            echo json_encode(["status" => "unloadnotfound", "no_sttb" => $no_sttb]);
            exit;
        }

        for($i = 0; $i < $koli; $i++) {

            if (strpos($resultScan, 'BAG') === 0) {
                $status_scan = 'Y';
            } else {
                $status_scan = 'N';
            }

            if ($type_delivery == 'OB') {

                // INSERT LOADING D1
                $insertLO = "INSERT INTO PUBLIC.trx_loading_d1(no_loading, no_sttb, no_sub_sttb, cabang_id, unit_id, created_at, updated_at, status_scan, id_karyawan, service, is_crossdock, type_delivery, cabang_tujuan) VALUES ('$no_loading', '$no_sttb', '".($i + 1)."', '$cabang_id', '$unit_id', '$tgl', '$tgl', '$status_scan', '$id_karyawan', '$service_id', 'Y', '$type_delivery', '$cabang_tujuan')";
                pg_query($insertLO);
        
            } else {
    
                // INSERT LOADING D1
                $insertLO = "INSERT INTO PUBLIC.trx_loading_d1(no_loading, no_sttb, no_sub_sttb, cabang_id, unit_id, created_at, updated_at, status_scan, id_karyawan, service, is_crossdock, type_delivery) VALUES ('$no_loading', '$no_sttb', '".($i + 1)."', '$cabang_id', '$unit_id', '$tgl', '$tgl', '$status_scan', '$id_karyawan', '$service_id', 'Y', '$type_delivery')";
                pg_query($insertLO);
    
            }

        }
        
        $subSttbLO = "UPDATE trx_loading_d1 SET status_scan = 'Y', updated_at = '$tgl' WHERE no_loading = '$no_loading' AND no_sttb = '$no_sttb' AND no_sub_sttb = '$sub_sttb'";
        pg_query($subSttbLO);

        $delivery           = pg_query("SELECT a.no_order_delivery FROM trx_loading a WHERE a.no_loading = '$no_loading' LIMIT 1");
        $resultDelivery     = pg_fetch_assoc($delivery);
        $no_delivery        = $resultDelivery['no_order_delivery'];

        $shipmentCek        = pg_query("SELECT b.customer_id FROM trx_shipment_entry_d1 a LEFT JOIN trx_shipment_entry b ON b.no_shipment_entry = a.no_shipment_entry WHERE a.no_sttb = '$no_sttb'");
        $resultShipmentCek  = pg_fetch_assoc($shipmentCek);
        $customer_id        = $resultShipmentCek['customer_id'];

        if ($type_delivery == 'OB') {

            // INSERT DELIVERY SHEET D1
            $deliveryD1 = "INSERT INTO PUBLIC.trx_delivery_sheet_d1(no_delivery, customer_id, cabang_id, unit_id, created_at, updated_at, no_sttb, status, cabang_id_tujuan) VALUES ('$no_delivery', '$customer_id', '$cabang_id', '$unit_id', '$tgl', '$tgl', '$no_sttb', '$type_delivery', '$cabang_tujuan')";
            pg_query($deliveryD1);

            // INSERT STATUS STTB LOADING
            $statusSttbLo = "INSERT INTO PUBLIC.trx_status_sttb(no_ref_process, nama_process, no_sttb, status_sttb, cabang_id, unit_id, status_record, created_at, updated_at) VALUES ('$no_delivery', 'LOADING', '$no_sttb', '102', '$cabang_id', '$unit_id', '1', '$tgl', '$tgl')";
            pg_query($statusSttbLo);

        } else {

            // INSERT DELIVERY SHEET D1
            $deliveryD1 = "INSERT INTO PUBLIC.trx_delivery_sheet_d1(no_delivery, customer_id, cabang_id, unit_id, created_at, updated_at, no_sttb, status) VALUES ('$no_delivery', '$customer_id', '$cabang_id', '$unit_id', '$tgl', '$tgl', '$no_sttb', '$type_delivery')";
            pg_query($deliveryD1);

            // INSERT STATUS STTB LOADING
            $statusSttbLo = "INSERT INTO PUBLIC.trx_status_sttb(no_ref_process, nama_process, no_sttb, status_sttb, cabang_id, unit_id, status_record, created_at, updated_at) VALUES ('$no_delivery', 'LOADING', '$no_sttb', '104', '$cabang_id', '$unit_id', '1', '$tgl', '$tgl')";
            pg_query($statusSttbLo);

        }

        if ($statusSttbLo) {
            echo json_encode(["status" => 1]);
        }

    }

} else {

    $updateLO = "UPDATE trx_loading_d1 SET status_scan = 'Y', updated_at = '$tgl' WHERE no_loading = '$no_loading' AND no_sttb = '$no_sttb' AND no_sub_sttb = '$sub_sttb'";
    pg_query($updateLO);

    echo json_encode(["status" => 1]);

}

?>