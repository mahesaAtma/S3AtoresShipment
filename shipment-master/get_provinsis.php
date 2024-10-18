<?php

include_once('../connection.php'); 

if (isset($_GET['value'])) {
    $sql = "SELECT id,nama_provinsi FROM ms_provinsis WHERE LOWER(nama_provinsi) LIKE '%" . pg_escape_string($_GET['value']) ."%' LIMIT 10";
    $data = pg_fetch_all(pg_query($dbconn, $sql));

    echo json_encode([
        'success' => true,
        'status' => 200,
        'data' => $data
    ]);
}else{
    echo json_encode([
        'success' => false,
        'status' => 422,
        'data' => []
    ]);
}