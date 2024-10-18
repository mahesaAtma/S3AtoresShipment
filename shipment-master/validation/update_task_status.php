<?php

include_once('../../connection.php');

function updateTaskStatus($data, $dbconn){
    if (!isset($data['task_id']) && !isset($data['nama_status'])) {
        return ['success' => false, 'message' => "Field yang dibutuhkan tidak valid!"];
    }

    $taskID = $data['task_id'];
    $namaStatus = $data['nama_status'];

    $querySelectTask = "SELECT id,task_no FROM trx_task WHERE id = " . pg_escape_string($taskID) . ";";
    $trxTaskRecord = pg_fetch_object(pg_query($dbconn, $querySelectTask));
    if (!$trxTaskRecord) {
        return ['success' => false, 'message' => 'Data task tidak ditemukan!'];
    }

    $querySelectStatus = "SELECT id FROM ms_status WHERE nama_status = '" . pg_escape_string($namaStatus) . "';";
    $statusRecord = pg_fetch_object(pg_query($dbconn, $querySelectStatus));
    if (!$statusRecord) {
        return ['success' => false, 'message' => 'Pilihan status tidak valid!'];
    }

    $queryInsert = "INSERT INTO trx_task_d (
        task_no, 
        status_task, 
        status_record, 
        created_at, 
        updated_at
    ) VALUES (
        '" . $trxTaskRecord->task_no . "',
        '" . $statusRecord->id . "',
        1,
        NOW(),
        NOW()
    );";

    $insert = pg_query($dbconn, $queryInsert);
    if (pg_num_rows($insert)) {
        return ['success' => false, 'message' => 'Terjadi kesalahan saat insert!'];
    }

    $queryUpdate = "UPDATE trx_task 
        SET status_task = '" . $statusRecord->id . "', updated_at = NOW() 
        WHERE id = " . $taskID . ";";

    $rowUpdated = pg_query($dbconn, $queryUpdate);
    if (pg_affected_rows($rowUpdated) <= 0) {
        return ['success' => false, 'message' => 'Terjadi kesalahan saat update!'];
    }

    return ['success' => true, 'message' => 'Status pickup task berhasil diubah!'];
}

$res = updateTaskStatus($_POST, $dbconn);

if ($res['success']) {
    echo json_encode([
        'status' => 200,
        'success' => true,
        'messages' => [$res['message']]
    ]);
}else{
    echo json_encode([
        'status' => 422,
        'success' => false,
        'messages' => [$res['message']]
    ]);
}