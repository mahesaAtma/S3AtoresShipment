<div class="main-content">
    <div class="container-fluid">
        <table class="" width="100%" style="padding:1px;">
            <tr>
                <td class="pull-left" style="vertical-align:top;padding:20px;">
                    <h6><a href="javascript:history.back()"><i class="fas fa-arrow-circle-left"></i>&nbsp;&nbsp;GO BACK</a>&nbsp;&nbsp;&nbsp; <i class="fas fa-check"></i>&nbsp;&nbsp;History Delivery Barang</h6>
                </td>
                <td class="pull-right" style="vertical-align:top;padding:20px;">
                    <h6></h6>
                </td>
            </tr>
        </table>
        <hr />
        <div class="row">
            <div class="col-lg-12">
                <div class="card" style="border-radius: 10px; margin-bottom: 5px;">
                    <div class="card-body">
                        <div class="list-group mb-2">
                            <a href="#" class="list-group-item list-group-item-action" aria-current="true">
                                <div class="d-flex w-100 justify-content-between">
                                    <?php
                                    $no_delivery = $_GET['no_delivery'];
                                    $Delivery = pg_query($dbconn, "SELECT * FROM trx_delivery_sheet a
                                    WHERE a.no_delivery = '$no_delivery'");
                                    while ($row = pg_fetch_array($Delivery)) {
                                        $keterangan = $row['keterangan'] ? $row['keterangan'] : '-';
                                    ?>
                                        <p class="mb-1" style="font-weight: bold; font-size:0.9rem;"><?php echo $no_delivery ?></p>
                                        <div style="display: flex; flex-direction:column;">
                                            <small><?php
                                                    $dateString = $row['updated_at'];
                                                    $dateWithoutTime = $dateString ? date('Y-m-d', strtotime($dateString)) : '-';
                                                    echo $dateWithoutTime;
                                                    ?></small>
                                        </div>
                                    <?php } ?>
                                </div>
                            </a>
                        </div>
                        <div class="list-group">
                            <ul style="list-style: none;">
                                <?php
                                $queryNoSttb = pg_query($dbconn, "SELECT trx_delivery_sheet_d1.task_no, ms_cust_send_receipt.nama_send_receipt,ms_cust_send_receipt.alamat,ms_cust_send_receipt.phone_pic
                                FROM trx_delivery_sheet_d1 
                                LEFT JOIN trx_shipment_entry_d1 ON trx_shipment_entry_d1.no_sttb = trx_delivery_sheet_d1.no_sttb
                                LEFT JOIN ms_cust_send_receipt ON ms_cust_send_receipt.id = trx_shipment_entry_d1.alamat_penerima
                                WHERE trx_delivery_sheet_d1.no_delivery =  '$no_delivery'
                                GROUP BY trx_delivery_sheet_d1.task_no, ms_cust_send_receipt.nama_send_receipt,ms_cust_send_receipt.alamat,ms_cust_send_receipt.phone_pic");
                                $noAlamat = 1;
                                while ($row1 = pg_fetch_array($queryNoSttb)) {
                                ?>
                                    <li>
                                        <a href="#" class="list-group-item list-group-item-action" aria-current="true">
                                            <small>Alamat <?php echo $noAlamat ?></small>
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1" style="font-size: 1rem;"><?php echo $row1['nama_send_receipt'] ?></h5>
                                            </div>
                                            <ul style="list-style: none;">
                                                <li style="font-size: 0.8rem;"><?php echo $row1['alamat'] ?></li>
                                                <li><?php echo $row1['phone_pic'] ? $row1['phone_pic'] : "-"  ?></li>
                                            </ul>
                                        </a>
                                        <div class="card">
                                            <div class="card-body">
                                                <small>No HU :</small>
                                                <!-- <p style="font-size: 0.9rem; font-weight:bold"><?php echo $row1['no_sttb'] ?></p> -->
                                                <?php 
                                                    $queryResi = pg_query($dbconn, "SELECT a.no_sttb
                                                    FROM trx_delivery_sheet_d1 a 
                                                    WHERE a.task_no = '".$row1['task_no']."'");
                                                    while ($sql = pg_fetch_array($queryResi)) {
                                                        echo "<p style='font-size: 0.9rem; font-weight:bold'>".$sql['no_sttb']."</p>";
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </li>
                                <?php $noAlamat++;
                                } ?>
                                <div class="card">
                                    <div class="card-body">
                                        <small>Remark :</small>
                                        <p style="font-size: 0.9rem; font-weight:bold"><?php echo $keterangan ?></p>
                                    </div>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>