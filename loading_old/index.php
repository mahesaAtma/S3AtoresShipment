<?php
$tiket = $obj->getTiket();
error_reporting(0);

if(isset($_POST['submit'])){
    $ret_val = $obj->updateStatusTiket();
    if($ret_val==1){
        echo '<script type="text/javascript">'; 
        echo 'alert("Record Updated Successfully");'; 
        echo 'window.location.href = "index.php?page=delivery_delays_index";';
        echo '</script>';
    }
}

?>

<?php while ($t = pg_fetch_object($tiket)) : ?>
<div class="modal fade" id="exampleModal<?php echo $t->id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label">Status</label>
                    <select id='status' name='status' class="form-control" data-validation="[NOTEMPTY]">
                        <option value="0" <?= ($t->status_d == 0) ? 'selected' : '' ?>>-- Pilih Status --</option>
                        <option value="151" <?= ($t->status_d == 151) ? 'selected' : '' ?>>Open</option>
                        <option value="152" <?= ($t->status_d == 152) ? 'selected' : '' ?>>On Process</option>
                        <option value="153" <?= ($t->status_d == 153) ? 'selected' : '' ?>>Closed</option>

                    </select>
                </div>
                <input type="hidden" name="no_ticket" value="<?= $t->no_ticket ?>">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>
<?php endwhile; ?>
<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p25">
        <div class="container-fluid">
            <table class="" width="100%" style="padding:1px;">
                <tr>
                    <td class="pull-left" style="vertical-align:top;padding:20px;">
                        <h6><a href="index.php"><i class="fas fa-arrow-circle-left"></i>&nbsp;&nbsp;GO BACK</a></h6>
                    </td>

                    <td class="pull-left" style="vertical-align:top;padding:20px;">
                        <h6><i class="fas fa-copy"></i>&nbsp;&nbsp;UNLOADING</h5>
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
                                        <a href="" class="btn btn-primary"> <i class="zmdi zmdi-swap-vertical"></i> Filter</a>
                                    </td>
                                    <td class="pull-right mr-2" style="vertical-align:top">
                                        <a href="index.php?page=tiket_insert" class="btn btn-success"> <i class="zmdi zmdi-plus"></i> Add Data</a>
                                    </td>
                                    
                                </tr>
                            </table>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-responsive-data2">
                                <br />
                                <table id="example2" class="table" width="100%" style="font-size:16px;">
                                    <?php
                                    $tiket = $obj->getTiket();
                                    ?>
                                    <thead>
                                        <tr class="active">
                                            <th>Date</th>
                                            <th>No Tiket</th>
                                            <th>Customer</th>
                                            <th>Sender</th>
                                            <th>Email</th>
                                            <th>Subject</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($t = pg_fetch_object($tiket)) : ?>
                                            <tr align="left" class="tr-shadow">
                                                <td width="10%"><?= date('d-m-Y', strtotime($t->created_at)) ?></td>
                                                <td><?= $t->no_ticket ?></td>
                                                <td><?= $t->customer_name ?></td>
                                                <td><?= $t->nama_send_receipt ?></td>
                                                <td><?= $t->email ?></td>
                                                <td><?= $t->subject_d ?></td>
                                                <td>
                                                    <?php if ($t->status_d == 151) {
                                                        echo '<span class = "badge badge-primary"> Open </span>';
                                                    } else if ($t->status_d == 152) {
                                                        echo '<span class = "badge badge-warning"> On Process </span>';
                                                    } elseif ($t->status_d == 153) {
                                                        echo '<span class = "badge badge-success"> Closed </span>';
                                                    } ?></td>
                                                <td>
                                                    <!-- <a type="button" class="btn btn-primary" href="index.php?page=tiket_detail&id=<?php echo $t->id ?>"><i class="zmdi zmdi-edit"></i></a>
                                    <a type="button" class="btn btn-warning" href=""><i class="zmdi zmdi-alert-circle-o"></i></a> -->

                                                    <table class="table" width="100%">
                                                        <tr>
                                                            <td>
                                                                <!-- <form action="index.php" method="post">
                                                <button type="submit" class="btn btn-primary btn-sm" name="edit"><i class="zmdi zmdi-edit"></i></button>
                                                </form> -->
                                                                <a type="button" class="btn btn-primary btn-sm" href="index.php?page=tiket_edit&id=<?php echo $t->id ?>"><i class="zmdi zmdi-edit"></i></a>
                                                            </td>
                                                            <!-- <td>
                                                                <a type="button" class="btn btn-warning btn-sm" href="index.php?page=tiket_detail&id=<?php echo $t->id ?>"><i class="zmdi zmdi-alert-circle-o"></i></a>
                                                            </td> -->
                                                            <td>
                                                                <!-- <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#exampleModal<?php echo $t->id ?>">
                                                                    <i class="zmdi zmdi-block-alt"></i>
                                                                </button> -->

                                                                <a type="button" class="btn btn-danger btn-sm" href="index.php?page=tiket_detail&id=<?php echo $t->id ?>"><i class="zmdi zmdi-alert-circle-o"></i></a>
                                                            </td>
                                                        </tr>
                                                    </table>
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