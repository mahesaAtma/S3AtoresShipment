<?php

include_once('../../connection.php'); 

if (isset($_GET['value']) && isset($_GET['customer_id'])) {
    if ($_GET['value'] == '') {
        $sql = "SELECT id, nama_send_receipt, alamat, phone_pic FROM ms_cust_send_receipt WHERE customer_id = " . pg_escape_string($_GET['customer_id']) . ";";
    }else{
        $sql = "SELECT id, nama_send_receipt, alamat, phone_pic FROM ms_cust_send_receipt 
            WHERE customer_id = " . pg_escape_string($_GET['customer_id']) . 
            "AND (LOWER(nama_send_receipt) LIKE '%" . pg_escape_string($_GET['value']) . "%'" .
            "OR LOWER(alamat) LIKE '%" . pg_escape_string($_GET['value']) . "%'" .
            "OR LOWER(phone_pic) LIKE '%" . pg_escape_string($_GET['value']) . "%'" . ");";
    }

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