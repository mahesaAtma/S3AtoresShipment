<?php 
error_reporting(1);
session_start();
include_once('connection.php'); 

if(isset($_POST['email'])){

    // $hashpassword = md5($_POST['pwd']);
    // ms_karyawans harus di update dari HRM melalui api laravel

    $sql = "SELECT u.*, r.nama_roles, u.email, u.karyawan_id, k.cabang_id, c.nama_cabang AS cabang_name, k.unit_id
    FROM PUBLIC.users u
    LEFT JOIN ms_karyawans k ON k.id = u.karyawan_id
    LEFT JOIN roles r ON r.id = u.role_id
    LEFT JOIN ms_cabangs c ON c.id = k.cabang_id
    WHERE u.email = '".pg_escape_string($_POST['email'])."'";


    $data = pg_query($dbconn,$sql); 
    $obj = pg_fetch_object($data);

    $hash = crypt($_POST['pwd'], $obj->password);
    
    if (password_verify($_POST['pwd'], $obj->password)) {
        $_SESSION['id_user_login'] = $obj->id;
        $_SESSION['id_karyawan']   = $obj->karyawan_id;
        $_SESSION['name']          = $obj->name;
        $_SESSION['email']         = $obj->email;
        $_SESSION['role_id']       = $obj->role_id;
        $_SESSION['nama_roles']    = $obj->nama_roles;
        $_SESSION['unit_id']       = $obj->unit_id;
        $_SESSION['cabang_id']     = $obj->cabang_id;
        $_SESSION['cabang_name']   = $obj->cabang_name;
        echo 'true';
        
    } else {
        echo 'false';
    }
}


?>