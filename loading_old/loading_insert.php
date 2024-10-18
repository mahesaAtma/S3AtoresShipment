<?php
$no_delivery = $_GET['no_delivery'];
?>

<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p25">
        <div class="container-fluid">
            &nbsp;&nbsp;
            <table class="" width="100%" style="padding:1px;">
                <tr>
                    <td class="pull-left" style="vertical-align:top;padding:20px;">
                        <h6><a href="index.php?page=all_delivery"><i class="fas fa-arrow-circle-left"></i>&nbsp;&nbsp;GO BACK</a></h6>
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
                            <h4>Tambah Loading</h4>
                        </div>

                        <div class="card-body">
                            <br>

                            <form id="form-loading" action="loading_old/insert_task_loading.php" name="modal_popup" enctype="multipart/form-data" method="POST">

                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                            <label class="control-label">No Delivery</span></label>
                                                <input type="text" name="no_delivery" id="no_delivery" value="<?php echo $no_delivery ?>" class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                        <hr/>
                                        <b>Tambah Petugas</b>
                                        <hr/>

                                            <div class="form-group">
                                                <label class="control-label">Petugas Scan</span></label>
                                                <select name='scan_emp_id[]' id="scan_emp_id" class="form-control select2" data-validation="[NOTEMPTY]" multiple="multiple">
                                                    <?php
                                                    $result = pg_query($dbconn, "SELECT * FROM public.users WHERE role_id = '111'");
                                                    while ($row = pg_fetch_array($result)) {
                                                        echo "<option value='$row[karyawan_id]'>" . $row['name'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="control-label">Petugas Bongkar</span></label>
                                                <select  name='bongkar_emp_id[]' id="bongkar_emp_id" class="form-control select2" data-validation="[NOTEMPTY]" multiple="multiple">
                                                    <?php
                                                    $result = pg_query($dbconn, "SELECT * FROM public.users WHERE role_id = '111'");
                                                    while ($row = pg_fetch_array($result)) {
                                                        echo "<option value='$row[karyawan_id]'>" . $row['name'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="control-label">Petugas Racking</span></label>
                                                <select name='racking_emp_id[]' id="racking_emp_id" class="form-control select2" data-validation="[NOTEMPTY]" multiple="multiple">
                                                    <?php
                                                    $result = pg_query($dbconn, "SELECT * FROM public.users WHERE role_id = '111'");
                                                    while ($row = pg_fetch_array($result)) {
                                                        echo "<option value='$row[karyawan_id]'>" . $row['name'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <br/><br/>
                                        <br/><br/>

                                        <div class="col-12">
                                               
                                            <div class="col-md-12 form_sec_outer_task border">
                                                <div class="row">
                                                    <div class="col-md-12 bg-light p-2 mb-3">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <h6 class="frm_section_n">Tambah TKL (Jika Ada)</h6>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 p-0">
                                                    <div class="col-md-12 form_field_outer p-0">
                                                        <div class="row form_field_outer_row">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control w_90" name="mobileb_no[]" id="mobileb_no_1" placeholder="Nama TKL" />
                                                            </div>

                                                            <div class="form-group">
                                                                <select name="task_type[]" id="task_type_1" class="form-control">
                                                                    <option>--Select Task--</option>
                                                                    <option>Scan</option>
                                                                    <option>Bongkar</option>
                                                                    <option>Racking</option>
                                                                </select>
                                                            </div>   

                                                            <div class="form-group" style="padding-left:10px;padding-top:10px;">
                                                                <i class="btn_round add_node_btn_frm_field" title="Copy or clone this row">
                                                                    <i class="fas fa-copy"></i>
                                                                </i>

                                                                <i class="btn_round remove_node_btn_frm_field" disabled>
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br/>
                                            <i class="fas fa-plus add_icon add_new_frm_field_btn"> Add TKL</i> 
                                        </div>

                                        <div class="col-12">
                                            <br/><br/><br/><br/>
                                            <div class="form-group">
                                                <label class="control-label"> </label>
                                                <input type="submit" class="btn btn-primary" name="submit" value="Submit">
                                            </div>
                                        </div>
                                    </div>
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

<script>
    ///======Clone method TKL TAMBAH
    $(document).ready(function () {

        $("body").on("click", ".add_node_btn_frm_field", function (e) {
            var index = $(e.target).closest(".form_field_outer").find(".form_field_outer_row").length + 1;
            var cloned_el = $(e.target).closest(".form_field_outer_row").clone(true);

            $(e.target).closest(".form_field_outer").last().append(cloned_el).find(".remove_node_btn_frm_field:not(:first)").prop("disabled", false);
            $(e.target).closest(".form_field_outer").find(".remove_node_btn_frm_field").first().prop("disabled", true);


            //change id
            $(e.target)
            .closest(".form_field_outer")
            .find(".form_field_outer_row")
            .last()
            .find("input[type='text']")
            .attr("id", "mobileb_no_" + index);

            $(e.target)
            .closest(".form_field_outer")
            .find(".form_field_outer_row")
            .last()
            .find("select")
            .attr("id", "no_type_" + index);

            console.log(cloned_el);
            //count++;
        });

    });


    $(document).ready(function(){ $("body").on("click",".add_new_frm_field_btn", function (){ console.log("clicked"); 
        var index = $(".form_field_outer").find(".form_field_outer_row").length + 1; $(".form_field_outer").append(`
            <div class="row form_field_outer_row">
                <div class="form-group">
                    <input type="text" class="form-control w_90" name="mobileb_no[]" id="mobileb_no_${index}" placeholder="Nama TKL" />
                </div>
                <div class="form-group">
                    <select name="task_type[]" id="task_type_${index}" class="form-control">
                    <option>--Select Task--</option>
                    <option>Scan</option>
                    <option>Bongkar</option>
                    <option>Racking</option>
                    </select>
                </div>
                <div class="form-group" style="padding-left:10px;padding-top:10px;">
                    <i class="btn_round add_node_btn_frm_field" title="Copy or clone this row">
                        <i class="fas fa-copy"></i>
                    </i>

                    <i class="btn_round remove_node_btn_frm_field" disabled>
                        <i class="fas fa-trash-alt"></i>
                    </i>
                </div>
            </div>
        `); $(".form_field_outer").find(".remove_node_btn_frm_field:not(:first)").prop("disabled", false); $(".form_field_outer").find(".remove_node_btn_frm_field").first().prop("disabled", true); }); 
    });


    $(document).ready(function () {
    //===== delete the form fieed row
        $("body").on("click", ".remove_node_btn_frm_field", function () {
            $(this).closest(".form_field_outer_row").remove();
            console.log("success");
        });
    });

</script>

<script>
    $(document).ready(function() {

        var formData = {};

        $("#form-loading").on('submit', function(e) {

        e.preventDefault();

        var form = $(this);

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;
                
                formData = {};

                $('.form_field_outer_row').each(function(index) {
                    formData[index] = {
                        mobileb_no: $(this).find('input[name="mobileb_no[]"]').val(),
                        task_type: $(this).find('select[name="task_type[]"]').val()
                    };
                });

                // var data = {
                //     latitude: latitude,
                //     longitude: longitude,
                //     no_armada: $("#no_armada").val(),
                //     scan_emp_id: $("#scan_emp_id").val(),
                //     bongkar_emp_id: $("#bongkar_emp_id").val(),
                //     racking_emp_id: $("#racking_emp_id").val(),
                //     formData: formData
                // };

                // console.log("DATA", data);

                var formDataTask = formData;
                var jsonData     = JSON.stringify(formDataTask);

                $.ajax({
                    method: form.attr("method"),
                    url: form.attr("action"),
                    data: {
                        latitude: latitude,
                        longitude: longitude,
                        no_delivery: $("#no_delivery").val(),
                        scan_emp_id: $("#scan_emp_id").val(),
                        bongkar_emp_id: $("#bongkar_emp_id").val(),
                        racking_emp_id: $("#racking_emp_id").val(),
                        formData: jsonData
                    },
                    success: function(response) {
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
                });
            });
        }
        });
    });
</script>