<?php
error_reporting(0);

include_once('../connection.php');

if (isset($_POST['namaR'])) {

    $postR = $_POST['namaR'];
    $postK = $_POST['ketR'];
    $postidProv = $_POST['provR'];
    $jumKota = count($_POST['arrKota']);

    $insertMSRayon = "INSERT INTO PUBLIC.ms_rayons(nama_rayon,keterangan)VALUES('$_POST[namaR]','$_POST[ketR]')";
    pg_query($insertMSRayon);

    $selectIdRayon = "SELECT id FROM ms_rayons ORDER BY id DESC LIMIT 1";

    $sqlIdRayon      = pg_query($selectIdRayon);

    while ($sqlidR = pg_fetch_assoc($sqlIdRayon)) {
        $idR = $sqlidR['id'];
    }

    for ($i = 0; $i < $jumKota; $i++) {
        $idKot = $_POST['arrKota'][$i]['id_kota'];
        $insertMSDRayon = "INSERT INTO PUBLIC.ms_rayons_d(id_rayon,id_provinsi,id_kota)VALUES('$idR','$postidProv','$idKot')";
        pg_query($insertMSDRayon);
    }
}
