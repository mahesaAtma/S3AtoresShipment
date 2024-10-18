<?php

session_start();
require('db_class.php');
$obj = new Db_Class();

require('shipment_module/query.php');
$shipmentObj = new ShipmentQuery($dbconn);

if ($_SESSION['id_user_login'] == "") {
    header('location:login.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Dashboard</title>

    <!-- Fontfaces CSS-->
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Edited By Mahesa -->
    <link href="shipment_module/style.css" rel="stylesheet" media="all">
    <!-- Edited By Mahesa -->

    <!-- Vendor CSS-->
    <link href="vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">
    <link href="vendor/vector-map/jqvmap.min.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">


    <!-- NEW ASSETS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" rel="stylesheet" media="all">
    <link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css" rel="stylesheet" media="all">
    <link href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.bootstrap4.min.css" rel="stylesheet" media="all">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">

    <link rel="stylesheet" type="text/css" href="vendor/signature/asset/css/jquery.signature.css">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">

    <link type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet">

    <style>
        a {
            text-decoration: none !important;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>




</head>

<body class="animsition">
    <div class="page-wrapper">
        <?php
        include('sidebar2.php');
        ?>

        <!-- Edited By Mahesa -->
        <div class="nama-barang-list dnone">
            <?php
                $jenisBarangs = $shipmentObj->getJenisBarang();
                foreach ($jenisBarangs as $barang) {
                    $namaBarang = $barang['nama_barang'];
                    echo "<p class='nama-barang-item'>$namaBarang</p>";
                }
            ?>
        </div>
        <!-- Edited By Mahesa -->

        <!-- PAGE CONTAINER-->
        <div class="page-container2">
            <?php
            include('header_desktop2.php');
            ?>
            <?php
            //include('breadcrump2.php');
            ?>

            <?php
            //include('main_content2.php');
            ?>

            <?php

            //echo '<script type="text/javascript">'; 
            //echo 'alert("'.$_POST['page'].'");'; 
            //echo '</script>';


            if (isset($_GET['page']) && $_GET['page'] == 'rayon_delete') {
                include('ms_rayon/rayon_delete.php');
            } elseif (isset($_GET['page']) && $_GET['page'] == 'rayon_edit') {
                include('ms_rayon/rayon_edit.php');
            } elseif (isset($_GET['page']) && $_GET['page'] == 'rayon_excel') {
                include('ms_rayon/rayon_excel.php');
            } elseif (isset($_GET['page']) && $_GET['page'] == 'rayon_insert') {
                include('ms_rayon/rayon_insert.php');
            } elseif (isset($_GET['page']) && $_GET['page'] == 'rayon_index') {
                include('ms_rayon/rayon_index.php');
            }

            //loading

            elseif (isset($_GET['page']) && $_GET['page'] == 'ops_loading') {
                include('loading_old/loading_menu.php');
            }elseif (isset($_GET['page']) && $_GET['page'] == 'loading_task') {
                include('loading_old/loading_task.php');
            }elseif (isset($_GET['page']) && $_GET['page'] == 'task_loading_d') {
                include('loading_old/loading_detail.php');
            }elseif (isset($_GET['page']) && $_GET['page'] == 'loading_insert') {
                include('loading_old/loading_insert.php');
            }elseif (isset($_GET['page']) && $_GET['page'] == 'loading') {
                include('loading/loading.php');
            }elseif (isset($_GET['page']) && $_GET['page'] == 'scan_loading') {
                include('loading/scan_loading.php');
            }elseif(isset($_GET['page']) && $_GET['page'] == 'scan_mesin_loa') {
                include('loading_old/scan_mesin_loa.php');
            } elseif(isset($_GET['page']) && $_GET['page'] == 'scan_phone_loa') {
                include('loading/scan_phone_unl.php');
            } elseif(isset($_GET['page']) && $_GET['page'] == 'scan_loading_menu') {
                include('loading/scan_loading_menu.php');
            } elseif(isset($_GET['page']) && $_GET['page'] == 'task_scan_loading') {
                include('loading/task_scan_loading.php');
            }

            elseif(isset($_GET['page']) && $_GET['page'] == 'loading_menu_staf') {
                include('loading_old/loading_menu_staf.php');
            } elseif(isset($_GET['page']) && $_GET['page'] == 'loading_task_staf') {
                include('loading_old/loading_task_staf.php');
            } elseif(isset($_GET['page']) && $_GET['page'] == 'all_delivery') {
                include('loading_old/data_delivery.php');
            } elseif(isset($_GET['page']) && $_GET['page'] == 'loading_scan_menu') {
                include('loading/scan_loadings.php');
            }elseif(isset($_GET['page']) && $_GET['page'] == 'scan_mesin_loading') {
                include('loading/scan_mesin_unl.php');
            } elseif(isset($_GET['page']) && $_GET['page'] == 'scan_phone_loading') {
                include('loading/scan_phone_unl.php');
            }

            elseif(isset($_GET['page']) && $_GET['page'] == 'task_history_loading') {
                include('loading_old/task_history.php');
            } elseif(isset($_GET['page']) && $_GET['page'] == 'task_history_loading_detail') {
                include('loading/task_history_detail.php'); 
            }

            // Edited By Mahesa
            elseif(isset($_GET['page']) && $_GET['page'] == 'pickup_list') {
                include('shipment_module/pickup_list.php');
            }elseif(isset($_GET['page']) && $_GET['page'] == 'history_barang') {
                include('shipment_module/pickup_history.php');
            }elseif(isset($_GET['page']) && $_GET['page'] == 'shipment_detail_sttb') {
                include('shipment_module/sttb/detail_sttb.php');
            }elseif(isset($_GET['page']) && $_GET['page'] == 'shipment_edit_sttb') {
                include('shipment_module/sttb/edit_sttb.php');
            }elseif(isset($_GET['page']) && $_GET['page'] == 'shipment_print_sttb') {
                include('shipment_module/sttb/print_sttb.php');
            }elseif(isset($_GET['page']) && $_GET['page'] == 'shipment_barcode_list') {
                include('shipment_module/barcode_list.php');
            }elseif(isset($_GET['page']) && $_GET['page'] == 'cobain_shipment') {
                include('shipment_module/cobain.php');
            }
            // Edited By Mahesa
            
            else {
              include("main_content.php");
            }

            ?>


            <?php
            //include('footer2.php');
            ?>



            <!-- END PAGE CONTAINER-->
        </div>
    </div>

    <!-- Jquery JS-->
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script src="vendor/slick/slick.min.js">
    </script>
    <script src="vendor/wow/wow.min.js"></script>
    <script src="vendor/animsition/animsition.min.js"></script>
    <script src="vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
    </script>
    <script src="vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="vendor/counter-up/jquery.counterup.min.js">
    </script>
    <script src="vendor/circle-progress/circle-progress.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="vendor/select2/select2.min.js">
    </script>
    <script src="vendor/vector-map/jquery.vmap.js"></script>
    <script src="vendor/vector-map/jquery.vmap.min.js"></script>
    <script src="vendor/vector-map/jquery.vmap.sampledata.js"></script>
    <script src="vendor/vector-map/jquery.vmap.world.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Main JS-->
    <script src="js/main.js"></script>


    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>

    <!-- NEW ASSETS -->
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.colVis.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script type="text/javascript" src="vendor/signature/asset/js/jquery.signature.min.js"></script>
    <script type="text/javascript" src="vendor/signature/asset/js/jquery.ui.touch-punch.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Scripts for Shipment Module -->
    <script src="./shipment_module/scripts/general.js"></script>
    <script src="./shipment_module/scripts/add_service.js"></script>
    <script src="./shipment_module/scripts/add_document.js"></script>
    <script src="./shipment_module/scripts/append_elements.js"></script>
    <!-- Scripts for Shipment Module -->

    <script type="text/javascript">
        $(document).ready(function() {
            $(".select2").select2();
            $("#multipleSelect").select2();
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#example').DataTable({
                lengthChange: false,
                order: [[0, 'desc']],
                
                buttons: [{
                        extend: 'copy',
                        className: 'btn-success',
                    },
                    {
                        extend: 'excel',
                        className: 'btn-success',
                    },
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        className: 'btn-success'
                    }
                ]
            });


            var tables = $('#example2').DataTable({
                lengthChange: false,
                order: [[0, 'desc']]
                /*
                buttons: [{
                        extend: 'copy',
                        className: 'btn-success',
                    },
                    {
                        extend: 'excel',
                        className: 'btn-success',
                    },
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        className: 'btn-success'
                    }
                ]*/
            });


            table.buttons().container()
                .appendTo('#example_wrapper .col-md-6:eq(0)');
            let dataSelect = [];
            let RENDER_DATA = "render-data";

            var arrayButton = ['#signout', '#signoutmob']
            var logout = false;
            for (let index = 0; index < arrayButton.length; index++) {
                $(arrayButton[index]).on('click', function() {
                    logout = true;
                    if (logout === true) {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You will be off this page!!",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, Logout!',
                            timer: 6000
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "logout.php",
                                    method: "POST",
                                    success: function(response) {
                                        console.log(response);
                                        if (response === 'true') {
                                            Swal.fire({
                                                position: 'center',
                                                icon: 'success',
                                                title: 'You are logout!',
                                                text: 'See you again',
                                                showConfirmButton: false,
                                                timer: 1000
                                            }).then(function() {
                                                window.location.href = 'login.php';
                                            })
                                        }
                                    }

                                })

                            }
                        })
                    }
                });
            }
            $.ajax({
                url: "dashboard_component.php",
                method: "GET",
                dataType: "JSON",
                success: function(response) {
                    console.log(response.lastm);
                    console.log(response);
                    $('#no_shipment').html(response.no_shipment_entry);
                    $('#no_sttb').html(response.no_sttb);
                    if (response.lastd > 0) {
                        var menit = response.lastd + " Detik";
                        $('#menit').html(menit);
                        if (response.lastm > 0) {
                            var menit = response.lastm + " Menit";
                            $('#menit').html(menit);
                            if (response.lasth > 0) {
                                var menit = response.lasth + " Jam";
                                $('#menit').html(menit);
                                if (response.lastdy > 0) {
                                    var menit = "bebarapa hari";
                                    $('#menit').html(menit);
                                }
                            }
                        }
                    }
                    $('#count').html(response.count)
                }
            })
        });
    </script>
    </script>



    

    






</body>

</html>
<!-- end document-->