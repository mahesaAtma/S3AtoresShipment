<style>
    .container {
        overflow: auto;
    }

    img {
        float: left;
        margin-right: 15px;
    }

    .text {
        overflow: hidden;
    }
</style>

<div class="main-content">
    <div class="container-fluid">
        <table class="" width="100%" style="padding:1px;">
            <tr>
                <td class="pull-left" style="vertical-align:top;padding:20px;">
                    <h6><a href="javascript:history.back()"><i class="fas fa-arrow-circle-left"></i>&nbsp;&nbsp;GO BACK</a>&nbsp;&nbsp;&nbsp; <i class="fas fa-check"></i>&nbsp;&nbsp;Bagging</h6>
                </td>
            </tr>
        </table>
        <hr />

        <?php
        $delivery = pg_query("SELECT a.no_unloading, a.tanggal_unloading, a.no_armada, a.status_selesai
        FROM trx_unloading a
        LEFT JOIN trx_unloading_d2 b ON b.no_unloading = a.no_unloading
        WHERE b.id_karyawan = '".$_SESSION['id_karyawan']."' AND a.status_selesai = 'N'
        ORDER BY a.created_at DESC");

        while ($sql = pg_fetch_array($delivery)) {
            $tgl_unloading  = $sql['tanggal_unloading'];
            $no_unloading   = $sql['no_unloading'];
            $no_armada      = $sql['no_armada'];
            $status_selesai = $sql['status_selesai'];
        ?>

        <div class="row">
            <div class="col-lg-12">
                <div class="card" style="border-radius: 10px; margin-bottom: 5px;">
                    <div class="card-body">
                        <p><a href="index.php?page=scan_unloading&no_unloading=<?php echo $no_unloading ?>"><?php echo $no_unloading ?></a> 
                            <?php 
                                if ($status_selesai == 'N') {
                                    echo '<span class = "badge badge-warning"> On Process </span>';
                                } else {
                                    echo '<span class = "badge badge-primary"> Done </span>';
                                }
                            
                            ?>
                        </p>
                        <a href="javascript:void(0);" style="float: right;" class="btn-detail"><i class="fas fa-solid fa-chevron-right" style="float: right;"></i></a>
                        <p><?php echo $no_armada ?></p>
                        <p><?php 
                            $dateString      = $tgl_unloading;
                            $dateWithoutTime = $dateString ? date('Y-m-d', strtotime($dateString)) : '-';
                            echo $dateWithoutTime; ?></p>
                    </div>

                    <div class="card-body detail" style="display: none;">
                        <p><b>Detail Staff :</b></p>
                        <?php 

                        $staff = pg_query("SELECT a.no_unloading, b.id_karyawan, c.name, b.tugas 
                        FROM trx_unloading a 
                        LEFT JOIN trx_unloading_d2 b ON b.no_unloading = a.no_unloading
                        LEFT JOIN users c ON c.karyawan_id = b.id_karyawan
                        WHERE a.no_unloading = '$no_unloading'");
                        $no = 1;
                        while ($row = pg_fetch_array($staff)) {
                        ?>
                            <div class="container">
                                <img src="images/profilIcon.png" style="width: 60px;">
                                <div class="text">
                                    <p><?php echo $row['name'] ?></p>
                                    <p><?php echo $row['tugas'] ?></p>
                                </div>
                            </div><hr>
                        <?php
                            $no++;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <?php } ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous" type="text/javascript"></script>

<script type="text/javascript">

    $('.btn-detail').on('click', function() {

        var detailDiv = $(this).closest('.card').find('.detail');

        detailDiv.toggle();

        if (detailDiv.is(':visible')) {
            $(this).find('i').removeClass('fa-chevron-right').addClass('fa-chevron-down');
        } else {
            $(this).find('i').removeClass('fa-chevron-down').addClass('fa-chevron-right');
        }
    });
</script>
