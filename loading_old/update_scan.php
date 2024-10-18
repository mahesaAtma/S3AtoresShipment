<?php 

session_start();
include_once('../connection.php');
require('../db_class.php');
$obj = new Db_Class();

date_default_timezone_set('Asia/Jakarta');

$no_loading     = $_POST['no_loading'];
$user_login     = $_SESSION['id_karyawan'];
$tgl            = date('Y-m-d H:i:s');

$updateStatusLO = "UPDATE PUBLIC.trx_loading SET status_selesai = 'Y', updated_at = '$tgl' WHERE no_loading = '$no_loading'";
pg_query($updateStatusLO);

if ($updateStatusLO) {
    $data = 1;
}

echo json_encode($data);

?>