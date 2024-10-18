<?php

error_reporting(0);

?>

<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p25">
        <div class="container-fluid">
            <table class="" width="100%" style="padding:1px;">
                <tr>
                    <td class="pull-left" style="vertical-align:top;padding:20px;">
                        <h6><a href="index.php?page=ops_loading"><i class="fas fa-arrow-circle-left"></i>&nbsp;&nbsp;GO BACK</a></h6>
                    </td>

                    <td class="pull-left" style="vertical-align:top;padding:20px;">
                        <h6><i class="fas fa-copy"></i>&nbsp;&nbsp;LOADING</h5>
                    </td>
                </tr>
            </table>
            <hr />
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="card-body" style="background-color: #ABBAEA;">
                            <ul style="padding-left:10px;">
                                <li>Tambah staff untuk membuat task loading.</li>
                                <li>Admin dapat menambah beberapa no loading baru.</li>
                                <li>Staff yang sudah memiliki task dapat ditambahkan untuk task baru.</li>
                            </ul>   
                        </div>
                       
                        
                        <div class="card-body">
                            <div class="table-responsive table-responsive-data2">
                                <br />
                                <table id="example2" class="table" width="100%" style="font-size:16px;">
                                    <?php
                                    $loading = $obj->getLoading($_SESSION['cabang_id']);
                                    ?>
                                    <thead>
                                        <tr class="active">
                                            <th>Information</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($t = pg_fetch_object($loading)) : ?>

                                            <?php 
                                                $i=$i++;
                                                
                                                $petugas = $obj->getLoadingD2($_SESSION['cabang_id'],$t->no_loading); ?>

                                            <tr align="left" class="tr-shadow">
                                                <td>
                                                    <?= '<b>'.$t->no_loading.'</b><br/>'.$t->tanggal_loading ?>
                                                    <br>[ <?= $t->no_order_delivery ?> ]
                                                    <br><?= $t->nama_routes ?>
                                                    <br><?= $t->no_polisi ?? '-' ?>
                                                    <br><?= $t->keterangan ?? '-' ?>
                                                    <br>
                                                    <?php if ($t->status_selesai == 'N') {
                                                        echo '<span class = "badge badge-warning">On Process</span>';
                                                    } else if ($t->status_selesai == 'Y') {
                                                        echo '<span class = "badge badge-primary">Done</span>';
                                                    } 
                                                    ?>
                                                    <hr/>
                                                    
                                                    <?php while ($p = pg_fetch_object($petugas)) : ?>
                                                        <small><?= $p->name.' ['.$p->tugas.'] /' ?></small>
                                                    <?php endwhile; ?>
                                                
                                                </td>
                                              
                                                <td>

                                                    <!-- <a type="button" class="btn btn-primary btn-sm" href="index.php?page=tiket_edit&id=<?php echo $t->no_loading ?>"><i class="zmdi zmdi-edit"></i></a>
                                                    &nbsp; -->

                                                    <a type="button" class="btn btn-warning btn-sm" href="index.php?page=task_loading_d&id=<?php echo $t->no_loading ?>"><i class="zmdi zmdi-eye"></i></a>

                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br/><br/>
                        <div class="card-header">
                            <table class="" width="100%">
                                <tr>
                                    <td class="" style="vertical-align:top:padding:20px;" width="50%">
                                        <a href="index.php?page=ops_loading" class="btn btn-secondary" style="width:100%;"> <i class="zmdi zmdi-close"></i> Cancel</a>
                                    </td>
                                    <td class="" style="vertical-align:top;padding:20px;" width="50%">
                                        <a href="index.php?page=all_delivery" class="btn btn-primary" style="width:100%;"> <i class="zmdi zmdi-plus"></i>  Tambah Data</a>
                                    </td> 
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>