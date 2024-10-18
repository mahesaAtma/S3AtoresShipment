<?php

include_once('connection.php');
$getDetail      = $obj->detailTiket($_GET['id']);
$header         = $obj->detailTiket($_GET['id']);
$getDetails     = $obj->detailTikets($_GET['id']);
$user           = $_SESSION['user'];
if (isset($_POST['submit'])) {
    $ret_val = $obj->updateStatusTiket();
    if ($ret_val == 1) {
        echo '<script type="text/javascript">';
        echo 'alert("Record Saved Successfully");';
        echo 'window.location.href = "index.php?page=tiket_index";';
        echo '</script>';
    }
    
    if ($ukuran > 2097152) {
        echo 'Ukuran Foto Maksimal 2 MB';
    } else {
        if($attachment_file != "") {
            $ekstensi_diperbolehkan = array('jpg', 'jpeg', 'png');
            $attachment_file        = $_FILES['attachment_file']['name'];
            $x                      = explode('.', $attachment_file);
            $ekstensi               = strtolower(end($x));
            $ukuran                 = $_FILES['attachment_file']['size'];
            $file_tmp               = $_FILES['attachment_file']['tmp_name'];
    
            if(in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
                move_uploaded_file($file_tmp, 'file/'.$attachment_file);
                $ret_val = $obj->createTiketD();
                
                if ($ret_val == 1) {
                    echo '<script type="text/javascript">';
                    echo 'alert("Record Saved Successfully");';
                    echo 'window.location.href = "index.php?page=tiket_index";';
                    echo '</script>';
                } else {
                    echo 'File Gagal Diupload';
                }
            } else {
                echo 'Ekstensi JPG, JPEG dan PNG';
            }
        } else {
            $ret_val = $obj->createTiketD();
            
                if ($ret_val == 1) {
                    echo '<script type="text/javascript">';
                    echo 'alert("Record Saved Successfully");';
                    echo 'window.location.href = "index.php?page=tiket_index";';
                    echo '</script>';
                }
        }
    }
}

?>

<style>
    .hide {
        display: none;
    }

    .flex {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .flex>div:nth-child(odd) {
        align-self: flex-end;
    }
</style>

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
                        <h6><i class="fas fa-copy"></i>&nbsp;&nbsp;DETAIL DATA TIKET</h5>
                    </td>
                </tr>
            </table>
            <hr />
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Informasi Tiket</h4>
                        </div>
                        <?php
                        while ($t = pg_fetch_object($header)) {
                        ?>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="control-label">No Ticket</label>
                                        <input class="form-control" type="text" name="no_ticket" id="no_ticket" value="<?= $t->no_ticket ?>" readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Date</label>
                                        <input class="form-control" type="date" name="created_at" value="<?php echo date('Y-m-d', strtotime($t->created_at)) ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Customer</label>
                                        <input class="form-control" type="text" name="customer_id" id="customer_id" value="<?= $t->customer_name ?>" readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Nama Proses</label>
                                        <input class="form-control" type="text" name="nama_process" value="<?= $t->nama_process ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Sender</label>
                                        <input class="form-control" type="text" name="customer_send_receipt_id" value="<?= $t->nama_send_receipt ?>" readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Status</label>
                                        <input class="form-control" type="text" name="status" value="<?php if ($t->status_d == 151) {
                                            echo 'Open';
                                            } else if ($t->status_d == 152) {
                                            echo 'On Process';
                                            } elseif ($t->status_d == 153) {
                                            echo 'Closed';
                                            } ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label">CP</label>
                                        <input class="form-control" type="text" name="cp" id="cp" value="<?= $t->nama_send_receipt ?>" readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label">No STTB</label>
                                        <input class="form-control" type="text" name="no_sttb" value="<?= $t->no_sttb ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Telpon</label>
                                        <input class="form-control" type="text" name="telpon" id="telpon" value="<?= $t->phone ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Email</label>
                                        <input class="form-control" type="text" name="email" id="email" value="<?= $t->email ?>" readonly>
                                    </div>
                                </div>   
                            </div>
                        <?php } ?>
                        <hr>
                        
                        
                        <div class="card-body flex">
                        <label class="form-label"><b>Balasan CS dan Customer</b></label><br>
                            <?php
                            while ($t = pg_fetch_object($getDetails)) {
                            ?>
                                <div class="col-5">
                                    <input class="form-control" type="text" name="content" value="<?= $t->content_d ?>" readonly>
                                </div>
                            <?php } ?>
                            <br>
                            <?php
                            while ($t = pg_fetch_object($getDetail)) {
                            ?>
                                <form class="form-horizontal" method="post" enctype="multipart/form-data">

                                <div class="panel-body hide">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="control-label">No Ticket</label>
                                                    <input class="form-control" type="text" name="no_ticket" id="no_ticket" value="<?= $t->no_ticket ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="form-label">Date</label>
                                                    <input class="form-control" type="date" name="created_at" value="<?php echo date('Y-m-d', strtotime($t->created_at)) ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="form-label">Customer</label>
                                                    <input class="form-control" type="text" name="customer_id" id="customer_id" value="<?= $t->customer_name ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="control-label">No Process:</label>
                                                    <input class="form-control" type="text" name="nama_process" value="<?= $t->nama_process ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="form-label">Sender</label>
                                                    <input class="form-control" type="text" name="customer_send_receipt_id" value="<?= $t->nama_send_receipt ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="control-label">Status</label>
                                                    <input class="form-control" type="text" name="status" value="<?= $t->status_d ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="control-label">CP</label>
                                                    <input class="form-control" type="text" name="cp" value="<?= $t->name_pic ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="control-label">No STTB</label>
                                                    <input class="form-control" type="text" name="no_sttb" value="<?= $t->no_sttb ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="form-label">Telpon</label>
                                                    <input class="form-control" type="text" name="phone" value="<?= $t->phone ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="form-label">Email</label>
                                                    <input class="form-control" type="text" name="email" value="<?= $t->email ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label">Content</label>
                                                    <input class="form-control" type="text" name="content" value="<?= $t->content_d ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                        </div> 
                        <hr>
                            <input type="hidden" name="subject" id="subject" value="<?= $t->subject_d ?>">
                            <input type="hidden" name="status" id="status" value="152">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Subject</label>
                                            <input type="text" name="subject" id="subject" value="<?= $t->subject_d ?>" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Message Content</label>
                                            <textarea class="form-control" name="content" rows="2" cols="30"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Attachment File</label>
                                            <input type="file" name="attachment_file" id="attachment_file" class="form-control" id="formFile">
                                        </div>
                                    </div>
                            </div>

                            <div class="form-group float-right">
                                <label class="control-label"> </label>
                                <a href="index.php?page=tiket_index" class="btn btn-primary"><i class="fas fa-check"></i> Tutup</a>
                                <button type="submit" class="btn btn-primary" name="submit"><i class="fas fa-reply"></i> Balas</button>
                            </div>
                        </div>
                    </form>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2();
    });
</script>