<div class="main-content">
    <div class="section__content section__content--p25">
        <div class="container-fluid">
            <table class="" width="100%" style="padding:1px;">
                <tr>
                    <td class="pull-left" style="vertical-align:top;padding:20px;">
                        <h6><a href="index.php?page=loading_task"><i class="fas fa-arrow-circle-left"></i>&nbsp;&nbsp;GO BACK</a></h6>
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
                                    $delivery = $obj->getDelivery($_SESSION['cabang_id']);
                                    ?>
                                    <thead>
                                        <tr class="active">
                                            <th>Information</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($t = pg_fetch_object($delivery)) : ?>
                                            <tr align="left" class="tr-shadow">

                                                <td>
                                                    <?= $t->no_delivery ?><br/>
                                                    <?= $t->nama_routes ?><br/>
                                                    <?= $t->no_polisi ?? '-'?><br/>
                                                    <?= $t->keterangan ?? '-' ?>
                                                </td>
                                              
                                                <td>
                                                    <a href="index.php?page=loading_insert&no_delivery=<?= $t->no_delivery ?>" class="btn btn-sm btn-primary" style="float: center;">Buat Loading</a>
                                                </td>
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