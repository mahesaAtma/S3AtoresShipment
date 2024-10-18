<?php
error_reporting(0);
include_once('../connection.php');
$idprov         = $_POST['id_provinsi'];
$sqlSelect      = "SELECT * FROM public.ms_kota_kabs WHERE provinsi_id = " . $idprov;
$sqlfetch       = pg_query($sqlSelect);
while ($row = pg_fetch_assoc($sqlfetch)) {
    echo "<option value='$row[id]'>$row[nama_kota_kab]</option>";
}
