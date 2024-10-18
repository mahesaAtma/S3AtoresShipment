<?php
error_reporting(0);
include_once('connection.php');
if (isset($_POST['submit']) and !empty($_POST['submit'])) {
    $ret_val = $obj->createLeadtime();
    if ($ret_val == 1) {
        echo '<script type="text/javascript">';
        echo 'alert("Record Saved Successfully");';
        echo 'location.href = "ms_rayon/rayon_index.php";';
        echo '</script>';
    }
}
?>
<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p25">
        <div class="container-fluid">
            &nbsp;&nbsp;
            <table class="" width="100%" style="padding:1px;">
                <tr>
                    <td class="pull-left" style="vertical-align:top;padding:20px;">
                        <h6><a href="index.php"><i class="fas fa-arrow-circle-left"></i>&nbsp;&nbsp;GO BACK</a></h6>
                    </td>
                    <td class="pull-left" style="vertical-align:top;padding:20px;">
                    </td>
                </tr>
            </table>
            <hr />
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Tambah Data Rayon</h4>
                        </div>
                        <div class="card-body">
                            <br>
                            <form id="form-rayon" class="form-horizontal" method="POST">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="control-label">Nama Rayon<span style='color:red'>*</span></label>
                                                <input id="nama_rayon" class="form-control" type="text" name="nama_rayon" required>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="control-label">Provinsi<span style='color:red'>*</span></label>
                                                <select id='provinsiselect' name='provinsiselect' class="form-control select2" data-validation="[NOTEMPTY]">
                                                    <option value="">-- Pilih Provinsi --</option>
                                                    <?php
                                                    $result = pg_query($dbconn, "SELECT * FROM public.ms_provinsis");
                                                    while ($row = pg_fetch_array($result)) {
                                                        echo "<option value='$row[id]'>" . $row['nama_provinsi'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6" id="add">
                                            <div class="form-group mb-2">
                                                <label class="control-label">Kota<span style='color:red'>*</span></label>
                                                <select id='kotaselect' name='kotaselect' class="form-control select2">
                                                    <option value="">-- Pilih Kota --</option>

                                                </select>
                                            </div>
                                            <h6>Kota yang dipilih :</h6>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="control-label">Keterangan</label>
                                                <input id="keterangan" class="form-control" type="text" name="keterangan">
                                            </div>
                                        </div>
                                        <!-- <div class="col-6">
                                            <a class="mt-2" id="tambah_kota" href="">+ Tambah Kota</a>
                                        </div> -->
                                        <div class="col-6">
                                            <ul class="list-group" id="drawer-list-kota">

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group float-right">
                                    <label class="control-label"> </label>
                                    <!-- <input type="submit" class="btn btn-primary" name="submit" value="Submit"> -->
                                    <a class="btn btn-primary" id="submit-rayon" style="color: white;">Submit</a>
                                </div>
                            </form>
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
<script type="text/javascript">
    let kota = document.getElementById('id_service');

    function isi_otomatis() {
        var id_customer = $("#id_customer").val();
        $.ajax({
            url: 'ledtime/autofill.php',
            data: "id_customer=" + id_customer,

            success: function(data) {
                var json = data;
                obj = JSON.parse(json);
                $('#id_provinsi').val(obj.id_provinsi);
                $('#provinsi_name').val(obj.provinsi_name);
                $('#id_kotakab').val(obj.id_kotakab);
                $('#kotakab_name').val(obj.kotakab_name);
            }
        });
    };
</script>