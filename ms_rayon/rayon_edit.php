<?php
$getEdit = $obj->editRayon($_GET['idrayonsd']);

?>

<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p25">
        <div class="container-fluid">
            &nbsp;&nbsp;
            <table class="" width="100%" style="padding:1px;">
                <tr>
                    <td class="pull-left" style="vertical-align:top;padding:20px;">
                        <h6><a href="index.php?page=rayon_index"><i class="fas fa-arrow-circle-left"></i>&nbsp;&nbsp;GO BACK</a></h6>
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
                            <h4>Edit Data Rayon</h4>
                        </div>
                        <div class="card-body">
                            <br>
                            <?php
                            while ($r = pg_fetch_object($getEdit)) {
                            ?>
                                <form id="form-rayon-edit" class="form-horizontal" method="POST">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="control-label">Nama Rayon<span style='color:red'>*</span></label>
                                                    <input id="idrd" class="form-control" type="hidden" name="idrd" value="<?php echo $_GET['idrayonsd'] ?>" required>
                                                    <input id="idr" class="form-control" type="hidden" name="idr" value="<?php echo $r->id_rayon ?>" required>
                                                    <input id="nama_rayon" class="form-control" type="text" name="nama_rayon" value="<?php echo $r->nama_rayon; ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="control-label">Provinsi<span style='color:red'>*</span></label>
                                                    <select id='provinsiedit' name='provinsiedit' class="form-control" data-validation="[NOTEMPTY]">
                                                        <option value="<?php echo $r->id_provinsi ?>"><?php echo $r->nama_provinsi ?></option>
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
                                                    <select id='kotaedit' name='kotaedit' class="form-control">
                                                        <option value="<?php echo $r->id_kota ?>"><?php echo $r->nama_kota_kab ?></option>
                                                        <?php
                                                        // $result = pg_query($dbconn, "SELECT * FROM public.ms_kota_kabs");
                                                        // while ($row = pg_fetch_array($result)) {
                                                        //     echo "<option value='$row[id]'>" . $row['nama_kota_kab'] . "</option>";
                                                        // }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="control-label">Keterangan</label>
                                                    <input id="keterangan" class="form-control" type="text" name="keterangan">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group float-right">
                                        <label class="control-label"> </label>
                                        <input type="submit" class="btn btn-primary" name="submit" value="Submit">
                                        <!-- <a class="btn btn-primary" id="submit-rayon" style="color: white;">Submit</a> -->
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