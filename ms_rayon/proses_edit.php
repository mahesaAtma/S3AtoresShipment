<?php
error_reporting(0);
if (isset($_POST['idPr'])) {

    $idProvinsi          = $_POST['idPr'];
    $idKota              = $_POST['idKt'];
    $id                  = $_POST['id'];
    $idR                 = $_POST['idRn'];
    $keterangan          = $_POST['ket'];
    $namaR               = $_POST['namaRay'];



    $sqlEdit = "update public.ms_rayons_d set id_provinsi='" . $idProvinsi . "', id_kota='" . $idKota . "', keterangan='" . $keterangan . "' where id = '" . $id . "' ";
    pg_affected_rows(pg_query($sqlEdit));

    $sqlEditNamaRayon = "update public.ms_rayons set nama_rayon='" . $namaR . "' WHERE id = '" . $idR . "' ";

    pg_affected_rows(pg_query($sqlEditNamaRayon));
}
