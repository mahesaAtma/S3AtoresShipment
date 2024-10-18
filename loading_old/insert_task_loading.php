<?php

session_start();
require('../db_class.php');
$obj = new Db_Class();
include_once('../connection.php');

date_default_timezone_set('Asia/Jakarta');

$no_delivery    = $_POST['no_delivery'];
$bongkar_emp_id = isset($_POST['bongkar_emp_id']) ? $_POST['bongkar_emp_id'] : [];
$racking_emp_id = isset($_POST['racking_emp_id']) ? $_POST['racking_emp_id'] : [];
$scan_emp_id    = isset($_POST['scan_emp_id']) ? $_POST['scan_emp_id'] : [];
$longitude      = $_POST['longitude'];
$latitude       = $_POST['latitude'];
$cabang_id      = $_SESSION['cabang_id'];
$unit_id        = $_SESSION['unit_id'];
$created_by     = $_SESSION['id_karyawan'];
$tgl            = date('Y-m-d H:i:s');
$formData       = $_POST['formData'];
$formDataArray  = json_decode($formData, true);

// GENERATE LOADING
$sequence3      = "SELECT nextval('public.trx_loading_id_seq') as nxt FROM PUBLIC.trx_loading";
$sqlnoLO        = pg_query($sequence3);
while ($sql3 = pg_fetch_assoc($sqlnoLO)) {
    $NLO     = $sql3['nxt'];
}
$newResult3      = ($NLO == null) ? 1 : $NLO;
$no_loading      = "LOA" . date('ym') . str_pad($newResult3, 6, "0", STR_PAD_LEFT);

$loading = "INSERT INTO PUBLIC.trx_loading(no_loading, no_order_delivery, tanggal_loading, status, cabang_id, unit_id, created_at, updated_at, longitude, latitude, status_selesai) VALUES ('$no_loading', '$no_delivery', '$tgl', '5', '$cabang_id', '$unit_id', '$tgl', '$tgl', '$longitude', '$latitude', 'N')";

pg_query($loading);

if (!empty($scan_emp_id)) {

    foreach ($scan_emp_id as $emp_scan) {

        $users = pg_query("SELECT name FROM PUBLIC.users WHERE karyawan_id = '$emp_scan'");
        while ($user = pg_fetch_assoc($users)) {
            $name = $user['name'];
        }

        $loadingD2 = "INSERT INTO PUBLIC.trx_loading_d2 (no_loading, id_karyawan, tugas, unit_id, cabang_id, created_at, updated_at, nama_karyawan) VALUES ('$no_loading', '$emp_scan', 'SCAN', '$unit_id', '$cabang_id', '$tgl', '$tgl', '$name')";

        pg_query($loadingD2);
    }

    
}

if (!empty($bongkar_emp_id)) {

    foreach ($bongkar_emp_id as $emp_bongkar) {

        $users = pg_query("SELECT name FROM PUBLIC.users WHERE karyawan_id = '$emp_bongkar'");
        while ($user = pg_fetch_assoc($users)) {
            $name = $user['name'];
        }

        $loadingD2 = "INSERT INTO PUBLIC.trx_loading_d2 (no_loading, id_karyawan, tugas, unit_id, cabang_id, created_at, updated_at, nama_karyawan) VALUES ('$no_loading', '$emp_bongkar', 'BONGKAR', '$unit_id', '$cabang_id', '$tgl', '$tgl', '$name')";

        pg_query($loadingD2);
    }
    
}

if (!empty($racking_emp_id)) {

    foreach ($racking_emp_id as $emp_racking) {

        $users = pg_query("SELECT name FROM PUBLIC.users WHERE karyawan_id = '$emp_racking'");
        while ($user = pg_fetch_assoc($users)) {
            $name = $user['name'];
        }

        $loadingD2 = "INSERT INTO PUBLIC.trx_loading_d2 (no_loading, id_karyawan, tugas, unit_id, cabang_id, created_at, updated_at, nama_karyawan) VALUES ('$no_loading', '$emp_racking', 'RACKING', '$unit_id', '$cabang_id', '$tgl', '$tgl', '$name')";

        pg_query($loadingD2);
    }
    
}

if (!empty($formDataArray)) {

    foreach ($formDataArray as $item) {
        $nama_karyawan = $item['mobileb_no'];
        $nama_task     = $item['task_type'];

        if ($nama_karyawan !== '') {

            $loadingD2 = "INSERT INTO PUBLIC.trx_loading_d2 (no_loading, tugas, unit_id, cabang_id, created_at, updated_at, nama_karyawan) VALUES ('$no_loading', '$nama_task', '$unit_id', '$cabang_id', '$tgl', '$tgl', '$nama_karyawan')";

            pg_query($loadingD2);
        }
    
    }

}

if ($loadingD2) {
    $data = 1;
} else {
    $data = 0;
}


echo json_encode($data);

?>