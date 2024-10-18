<div class="main-content">
    <div class="container-fluid">
        <table class="" width="100%" style="padding:1px;">
            <tr>
                <td class="pull-left" style="vertical-align:top;padding:20px;">
                    <h6><a href="javascript:history.back()"><i class="fas fa-arrow-circle-left"></i>&nbsp;&nbsp;GO BACK</a>&nbsp;&nbsp;&nbsp; <i class="fas fa-check"></i>&nbsp;&nbsp;History Delivery Barang</h6>
                </td>
            </tr>
        </table>
        <hr />

        <div class="row">
            <div class="col-lg-12">
            <?php 

                $sqlTaskDeliv = pg_query($dbconn, "SELECT a.task_no, a.no_ref_process, a.status_task, b.nama_status, a.created_at
                FROM trx_task a 
                LEFT JOIN ms_status b ON b.id = CAST(a.status_task AS INT)
                WHERE a.tipe_process = 'delivery' 
                AND a.driver_id = '" . $_SESSION['id_karyawan'] . "'
                AND a.status_task = '105' OR a.status_task = '106' OR a.status_task = '107' OR a.status_task = '108' ORDER BY a.created_at DESC
                ");

                while ($row = pg_fetch_array($sqlTaskDeliv)) {

                    $no_delivery = $row['no_ref_process'];
                    $created_at  = $row['created_at'];
                    $status_task = $row['status_task'];

                    echo "<div class='card' style='border-radius: 10px; margin-bottom: 5px;'>";
                    echo "<div class='card-body'>";
                    echo "<label class='control-label'><b>$no_delivery</b></label>";
                    echo "<label class='control-label' style='float: right; font-size: 13px;'>" . date('d-m-Y', strtotime($created_at)) . "</label><br>";
                    echo "<label class='control-label'>Alamat 1</label>";
                    echo "<span class='badge badge-pill badge-primary' style='float: right;'>DONE</span><br>";
                    
                    $taskDelivD = pg_query($dbconn, "SELECT b.alamat_penerima, c.nama_send_receipt
                    FROM trx_delivery_sheet_d1 a 
                    LEFT JOIN trx_shipment_entry_d1 b ON b.no_sttb = a.no_sttb
                    LEFT JOIN ms_cust_send_receipt c ON c.id = b.alamat_penerima
                    WHERE a.no_delivery = '$no_delivery' LIMIT 1");

                    while ($row2 = pg_fetch_array($taskDelivD)) {

                        $nama_send_receipt = $row2['nama_send_receipt'];

                        echo "<label class='control-label' style='font-size: 14px;'><b>$nama_send_receipt</b></label>";
                        echo "<a href='index.php?page=history_detail&no_deliv=$no_delivery' style='float: right;'><i class='fas fa-solid fa-chevron-right'></i></a><br></div></div>";

                    }
                }

                ?>
                
            </div>
        </div>
    </div>
</div>