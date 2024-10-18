<?php

session_start();
require('../db_class.php');
$obj = new Db_Class();
include_once('../connection.php');

$no_loading = $_POST['no_loading'];

$sttbBG = pg_query($dbconn, "WITH aggregated_koli AS (
            SELECT b.no_sttb, SUM(COALESCE(CAST(e.jml_koli AS DECIMAL(10)), 0)) AS total_jml_koli
            FROM trx_shipment_entry_d1 b
            LEFT JOIN trx_shipment_entry_d1_service e ON e.trx_shipment_entry_d1_id = b.id
            WHERE b.no_sttb IN (
                SELECT no_sttb 
                FROM trx_loading_d1 
                WHERE no_loading = '$no_loading'
            )
            GROUP BY b.no_sttb
        )
        SELECT a.no_loading, a.no_sttb, d.nama_kota_kab, f.nama_service_product, 
        g.status,
        b.nama_pic_penerima, b.no_resi, x.no_sttb as no_hu,
        COUNT(DISTINCT CASE WHEN a.status_scan = 'Y' THEN a.no_sub_sttb END) AS koli_scan, 
        COALESCE(CAST(aggr.total_jml_koli AS DECIMAL(10, 0)), 0) AS koli_total, 
        MAX(a.updated_at) AS latest_updated_at
        FROM trx_loading_d1 a
        LEFT JOIN aggregated_koli aggr ON aggr.no_sttb = a.no_sttb
        LEFT JOIN trx_shipment_entry_d1 b ON b.no_sttb = a.no_sttb
        LEFT JOIN trx_status_sttb x on x.no_sttb = a.no_sttb
        LEFT JOIN ms_cust_send_receipt c ON c.id = b.alamat_penerima
        LEFT JOIN ms_kota_kabs d ON d.id = c.kotakab_id
        LEFT JOIN trx_shipment_entry_d1_service e ON e.trx_shipment_entry_d1_id = b.id
        LEFT JOIN ms_service_products f ON f.id = e.service_id
        LEFT JOIN trx_loading h ON h.no_loading = a.no_loading
        LEFT JOIN trx_delivery_sheet_d1 g ON g.no_delivery = h.no_order_delivery
        WHERE a.no_loading = '$no_loading'
        GROUP BY a.no_loading, a.no_sttb, d.nama_kota_kab, f.nama_service_product, 
        b.nama_pic_penerima, g.status, b.no_resi, x.no_sttb, aggr.total_jml_koli
        ORDER BY latest_updated_at DESC");


$data = array();

while($result = pg_fetch_assoc($sttbBG)) {
    $data[] = $result;
}

echo json_encode($data);

?>