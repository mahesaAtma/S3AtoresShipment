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
                    <h6><a href="javascript:history.back()"><i class="fas fa-arrow-circle-left"></i>&nbsp;&nbsp;GO BACK</a>&nbsp;&nbsp;&nbsp; <i class="fas fa-check"></i>&nbsp;&nbsp;UNLOADING</h6>
                </td>
            </tr>
        </table>
        <hr />

        <?php
        $delivery = pg_query("SELECT a.no_cross_docks, a.no_order_delivery, a.created_at, b.id_karyawan, b.tugas, c.name 
        FROM trx_cross_docks a
        LEFT JOIN trx_cross_docks_d2 b ON a.no_cross_docks = b.no_cross_docks
        LEFT JOIN users c ON c.karyawan_id = b.id_karyawan
        WHERE b.id_karyawan = '".$_SESSION['id_karyawan']."'
        ORDER BY a.created_at DESC");

        while ($sql = pg_fetch_array($delivery)) {
            $no_cross_docks = $sql['no_cross_docks'];
            $no_delivery    = $sql['no_order_delivery'];
            $created_at     = $sql['created_at'];
            $tugas          = $sql['tugas'];
            $nama_karyawan  = $sql['name'];

        ?>

        <div class="row">
            <div class="col-lg-12">
                <div class="card" style="border-radius: 10px; margin-bottom: 5px;">
                    <div class="card-body">
                        <p><b><?php echo $no_cross_docks ?></b></p>
                        <a href="javascript:void(0);" style="float: right;" class="btn-detail"><i class="fas fa-solid fa-chevron-right" style="float: right;"></i></a>
                        <p><?php 
                            $dateWithoutTime = $created_at ? date('Y-m-d', strtotime($created_at)) : '-';
                            echo $dateWithoutTime; ?>
                        </p>
                        <p><?php echo $no_delivery ?></p>
                    </div>

                    <div class="card-body detail" style="display: none;">
                        <p>Detail Cross Docks :</p><hr>
                        <div class="container">
                            <img src="images/profilIcon.png" style="width: 60px;">
                            <div class="text">
                                <p><?php echo $nama_karyawan ?></p>
                                <p><?php echo $tugas ?></p>
                            </div>
                        </div><hr>
                        <a href="index.php?page=scan_unloading&cross_docks=<?php echo $no_cross_docks ?>" class="btn btn-primary" style="float: right;">Lakukan Scan</a>
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