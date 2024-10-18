<?php
$rayon = $obj->getRayon();
?>

<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p25">
        <div class="container-fluid">
            <!--<a href="index.php?page=sttb_upload" class="au-btn au-btn-icon au-btn--blue pull-right" style='margin-top:-30px;'> <i class="zmdi zmdi-zoom-out"></i> View Record</a>-->
            <table class="" width="100%" style="padding:1px;">
                <tr>
                    <td class="pull-left" style="vertical-align:top;padding:20px;">
                        <h6><a href="index.php"><i class="fas fa-arrow-circle-left"></i>&nbsp;&nbsp;GO BACK</a></h6>
                    </td>
                    <td class="pull-left" style="vertical-align:top;padding:20px;">
                        <h6><i class="fas fa-copy"></i>&nbsp;&nbsp;MASTER RAYON</h5>
                    </td>
                </tr>
            </table>
            <hr />
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <table class="" width="100%">
                                <tr>
                                    <td class="pull-left" style="vertical-align:top">

                                    </td>
                                    <td class="pull-right" style="vertical-align:top">
                                        <a href="index.php?page=rayon_insert" class="btn btn-primary btn-sm"> <i class="zmdi zmdi-plus"></i> Add Data</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive table-responsive-data2">
                                <br />
                                <table id="example" class="table" width="100%" style="font-size:16px;">
                                    <thead>
                                        <tr class="active">
                                            <th>Date</th>
                                            <th>Nama</th>
                                            <th>Provinsi</th>
                                            <th>Detail Kota</th>
                                            <th>Keterangan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($r = pg_fetch_object($rayon)) : ?>
                                            <tr align="left" class="tr-shadow">
                                                <td style="text-align:center ;" width="10%"><?= $r->created_at ? date('Y-m-d', strtotime($r->created_at)) : "-" ?></td>
                                                <td><?= $r->nama_rayon ?></td>
                                                <td><?= $r->nama_provinsi ?></td>
                                                <td><?= $r->nama_kota_kab ?></td>
                                                <td><?= $r->keterangan ?></td>
                                                <td>
                                                    <a type="button" class="btn btn-warning" href="index.php?page=rayon_edit&idrayonsd=<?php echo $r->id_rayon_d ?>">
                                                        Edit
                                                    </a>
                                                </td>
                                                <!-- <td>
                                                    <table class="table" width="100%">
                                                        <tr>
                                                            <td>
                                                                <form action="index.php" method="post">
                                                                    <input type="hidden" value="<?= $user->id ?>" name="id">
                                                                    <input type="hidden" value="sttb_edit" name="page">
                                                                    <button type="submit" class="btn btn-success btn-sm" name="edit"><i class="zmdi zmdi-border-color"></i></button>
                                                                </form>
                                                            </td>
                                                            <td>
                                                                <form action="index.php" method="post">
                                                                    <input type="hidden" value="<?= $user->id ?>" name="id">
                                                                    <input type="hidden" value="sttb_delete" name="page">
                                                                    <button type="submit" onClick="return confirm('Please confirm deletion ?');" class="btn btn-danger btn-sm" name="delete" value="Delete"><i class="zmdi zmdi-delete"></i></button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td> -->
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>