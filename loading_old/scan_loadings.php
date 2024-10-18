<?php 

$no_unloading = $_GET['no_unloading'];

?>

<div class="main-content">
    <div class="container-fluid">
        <table class="" width="100%" style="padding:1px;">
            <tr>
                <td class="pull-left" style="vertical-align:top;padding:20px;">
                    <h6><a href="javascript:history.back()"><i class="fas fa-arrow-circle-left"></i>&nbsp;&nbsp;GO BACK</a>&nbsp;&nbsp;&nbsp; <i class="fas fa-check"></i>&nbsp;&nbsp;Unloading</h6>
                </td>
            </tr>
        </table>
        <hr />

        <div class="row">
            <div class="col-lg-12">
                <div class="card" style="border-radius: 10px; margin-bottom: 5px;">
                    <div class="card-body">
                        <table class="" width="100%">
                            <tr>
                                <td style="vertical-align:top; text-align: center;">
                                    <div style="display: inline-block;">
                                        <a href="index.php?page=scan_mesin_unloading&no_unloading=<?php echo $no_unloading;?>" style="width: 150px; height: 40px;" class="btn btn-primary">Scan Mesin</a>
                                    </div>
                                    <div style="display: inline-block; margin-left: 5px;">
                                        <a href="index.php?page=scan_phone_unloading&no_unloading=<?php echo $no_unloading;?>" style="width: 150px; height: 40px;" class="btn btn-primary">Scan Phone</a>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- <div class="card" style="border-radius: 10px; margin-bottom: 5px;">
                    <div class="card-body">
                        <h6>Data Scan</h6><br>
        
                        <?php 

                            $crossDocks = pg_query("SELECT a.no_cross_docks, a.no_sttb, COUNT(a.no_sub_sttb) as koli, b.jml_print_barcode 
                            FROM trx_cross_docks_d1 a 
                            LEFT JOIN trx_shipment_entry_d1 b ON b.no_sttb = a.no_sttb
                            WHERE a.no_cross_docks = '$no_cross_docks' AND a.status_scan = 'Y'
                            GROUP BY a.no_cross_docks, a.no_sttb, b.jml_print_barcode");

                            while ($sql = pg_fetch_array($crossDocks)) {
                                $noSttb            = $sql['no_sttb'];
                                $koli              = $sql['koli'];
                                $jml_print_barcode = $sql['jml_print_barcode'];

                                echo "
                                <ul style='margin-left: 5px;'>
                                    <li style='display: flex; justify-content: space-between;'>
                                        <span>$noSttb</span>
                                        <span style='color: red;'>Total : $jml_print_barcode Koli</span>
                                    </li>
                                </ul><hr>";
                            }
                                        
                        ?>
                        <br>
                    </div>
                </div> -->
            </div>
        </div>

    </div>
</div>