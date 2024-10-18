<?php
include_once('../connection.php');
$query = pg_query($dbconn, "SELECT b.id AS id_provinsi, c.id as id_kotakab, b.nama_provinsi AS provinsi_name, c.nama_kota_kab AS kotakab_name 
FROM ms_customers a 
LEFT OUTER JOIN ms_provinsis b ON a.provinsi_id = b.id
LEFT OUTER JOIN ms_kota_kabs c ON a.kotakab_id = c.id
WHERE a.id = '$_GET[id_customer]'");
$r = pg_fetch_array($query);
$data = array(
    'id_provinsi'    => $r['id_provinsi'],
    'id_kotakab'     => $r['id_kotakab'],
    'provinsi_name'  => $r['provinsi_name'],
    'kotakab_name'   => $r['kotakab_name']);
echo json_encode($data);
