<?php 

$no_loading = $_GET['id'];

?>

<style>
    .card-body {
        border-bottom: 1px solid #ccc;
        padding-bottom: 5px;
        margin-bottom: 5px;
    }

    .card-body:last-child {
        border-bottom: none; 
        margin-bottom: 0;
    }
</style>

<div class="main-content">
    <div class="container-fluid">
        <table class="" width="100%" style="padding:1px;">
            <tr>
                <td class="pull-left" style="vertical-align:top;padding:20px;">
                    <h6><a href="javascript:history.back()"><i class="fas fa-arrow-circle-left"></i>&nbsp;&nbsp;GO BACK</a>&nbsp;&nbsp;&nbsp; <i class="fas fa-check"></i>&nbsp;&nbsp;Loading</h6>
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
                        <div class="list-group">
                            <ul style="list-style: none;">
                                <?php
                                $queryNoLO = pg_query($dbconn, "SELECT a.no_loading, a.tanggal_loading, a.no_order_delivery, c.nama_routes
                                FROM trx_loading a
                                LEFT JOIN trx_delivery_sheet b ON b.no_delivery = a.no_order_delivery 
                                LEFT JOIN ms_routes c ON c.id = b.route_id
                                WHERE a.no_loading = '$no_loading'");

                                while ($row1 = pg_fetch_array($queryNoLO)) {
                                ?>
                                    <li>
                                        <a href="#" class="list-group-item list-group-item-action" aria-current="true" style="background-color: #d9e6f2;">
                                            <div class="d-flex w-100 justify-content-between">
                                                <b><p class="mb-1" style="font-size: 14px;"><?php echo $row1['no_loading'] ?></p></b>
                                                <b><p class="mb-1" style="font-size: 14px;"><?php echo $row1['no_order_delivery'] ?></p></b>
                                            </div>
                                            <div class="d-flex w-100 justify-content-between">
                                                <p style="font-size: 11px;">
                                                    <?php 
                                                    $dateString = $row1['tanggal_loading'];
                                                    $dateWithoutTime = date('Y-m-d', strtotime($dateString));
                                                    echo $dateWithoutTime; ?>
                                                </p>
                                                <p style="font-size: 11px;"><?php echo $row1['nama_routes'] ?></p>
                                            </div>
                                        </a>

                                        <div class="card">
                                            <?php 
                                                $queryResi = pg_query($dbconn, "WITH aggregated_koli AS (
                                                                SELECT b.no_sttb, SUM(COALESCE(CAST(e.jml_koli AS DECIMAL(10)), 0)) AS total_jml_koli
                                                                FROM trx_shipment_entry_d1 b
                                                                LEFT JOIN trx_shipment_entry_d1_service e ON e.trx_shipment_entry_d1_id = b.id
                                                                WHERE b.no_sttb IN (
                                                                    SELECT no_sttb 
                                                                    FROM trx_loading_d1 
                                                                    WHERE no_loading = '$no_loading'
                                                                )
                                                                GROUP BY b.no_sttb
                                                            )
                                                            SELECT a.no_loading, a.no_sttb, d.nama_kota_kab, f.nama_service_product, 
                                                            g.status,
                                                            b.nama_pic_penerima, b.no_resi, x.no_sttb as no_hu,
                                                            COUNT(DISTINCT CASE WHEN a.status_scan = 'Y' THEN a.no_sub_sttb END) AS koli_scan, 
                                                            COALESCE(CAST(aggr.total_jml_koli AS DECIMAL(10, 0)), 0) AS koli_total, 
                                                            MAX(a.updated_at) AS latest_updated_at
                                                            FROM trx_loading_d1 a
                                                            LEFT JOIN aggregated_koli aggr ON aggr.no_sttb = a.no_sttb
                                                            LEFT JOIN trx_shipment_entry_d1 b ON b.no_sttb = a.no_sttb
                                                            LEFT JOIN trx_status_sttb x on x.no_sttb = a.no_sttb
                                                            LEFT JOIN ms_cust_send_receipt c ON c.id = b.alamat_penerima
                                                            LEFT JOIN ms_kota_kabs d ON d.id = c.kotakab_id
                                                            LEFT JOIN trx_shipment_entry_d1_service e ON e.trx_shipment_entry_d1_id = b.id
                                                            LEFT JOIN ms_service_products f ON f.id = e.service_id
                                                            LEFT JOIN trx_loading h ON h.no_loading = a.no_loading
                                                            LEFT JOIN trx_delivery_sheet_d1 g ON g.no_delivery = h.no_order_delivery
                                                            WHERE a.no_loading = '$no_loading'
                                                            GROUP BY a.no_loading, a.no_sttb, d.nama_kota_kab, f.nama_service_product, 
                                                            b.nama_pic_penerima, g.status, b.no_resi, x.no_sttb, aggr.total_jml_koli
                                                            ORDER BY latest_updated_at DESC");
                                                while ($sql = pg_fetch_array($queryResi)) {

                                                $koli_total = $sql['koli_total'] === null ? 0 : $sql['koli_total'];
                                                $koli_scan  = $sql['koli_scan'] === null ? 0 : $sql['koli_scan'];
                                                $color      = intval($koli_scan) === intval($koli_total) ? 'green' : 'red';

                                            ?>

                                            <div class="card-body d-flex justify-content-between align-items-center">
                                                <div>
                                                    <p style="margin: 5px 0; font-size: 13px;"><?php echo $sql['no_sttb'] ?></p>
                                                    <p style="margin: 5px 0; font-size: 13px;">[<?php echo $sql['status'] ?>]</p>
                                                    <p style="margin: 5px 0; font-size: 13px;"><?php echo $sql['nama_kota_kab'] ?></p>
                                                    <p style="margin: 5px 0; font-size: 13px;"><?php echo $sql['nama_pic_penerima'] ?></p>
                                                </div>
                                                <div>
                                                    <b><p style="margin: 5px 0; font-size: 13px; color: <?php echo $color ?>;">Scan : <?php echo $koli_scan ?>/<?php echo $koli_total ?> Koli</p></b>
                                                </div>
                                            </div>

                                            <?php }
                                            ?>
                                        </div>
                                    </li><hr>
                                <?php
                                    
                                } ?>
                            </ul>

                            <?php 

                            $cd     = pg_query("SELECT status_selesai FROM trx_loading WHERE no_loading = '$no_loading'");
                            $hasil  = pg_fetch_assoc($cd);
                            $status = $hasil['status_selesai'];

                            if ($status == 'N' && $_SESSION['role_id'] == '109')
                            { ?>

                                <form id="form-save" action="loading_old/update_scan.php" method="POST">
                                    <input type="hidden" name="no_loading" id="no_loading" value="<?php echo $no_loading ?>">
                                    <button type="submit" class="btn btn btn-primary" id="btn-simpan" style="color: white; display: block; width: 100%;">
                                        Simpan
                                    </button>
                                </form>

                            <?php } ?>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#form-save").on('submit', function(e) {

        e.preventDefault();

        var form = $(this);

        $.ajax({
            method: form.attr("method"),
            url: form.attr("action"),
            data: {
                no_loading: $('#no_loading').val()
            },
            success: function(response) {
                $("#modal-data").empty();
                $("#modal-data").html(response.data);
                if (response == 1) {
                    Swal.fire({
                        type: 'success',
                        title: 'Berhasil Disimpan!',
                        text: 'Anda akan di arahkan dalam 2 Detik',
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: false
                    })
                    .then(function() {
                        location.reload(true);    
                    });
                } else {
                    alert("Submit Gagal");
                }
            },
            error: function(e) {

            },
            beforeSend: function(b) {

            }
        })
        .done(function(d) {

        });

        });
    });
</script>