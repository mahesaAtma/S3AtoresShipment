<?php
error_reporting(0);
include_once('../connection.php');
$hasil = array();

// MENAMPILKAN NAMA KOTA

if (isset($_POST["select_id"])) {
    $id = $_POST["select_id"];
    $sqlSelect = "SELECT * FROM public.ms_kota_kabs WHERE id =" . $id;
    $sqlfetch       = pg_query($sqlSelect);

    while ($row = pg_fetch_assoc($sqlfetch)) {
        echo $row['nama_kota_kab'];
    }
}
