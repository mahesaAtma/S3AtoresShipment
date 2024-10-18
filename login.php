<?php
// session_start();
// include_once('connection.php'); 



// if(isset($_POST['email'])){

//     // $hashpassword = md5($_POST['pwd']);
//     $sql = "SELECT * FROM public.users WHERE email = '".pg_escape_string($_POST['email'])."'";

//     $data = pg_query($dbconn,$sql); 
//     $obj = pg_fetch_object($data);

//     $hash = crypt($_POST['pwd'], $obj->password);
    
//     if (password_verify($_POST['pwd'], $obj->password)) {
//         $_SESSION['id_user_login'] = $obj->id;
//         $_SESSION['name']          = $obj->name;
//         $_SESSION['email']         = $obj->email;
//         $_SESSION['role_id']       = $obj->role_id;

        // // echo $_SESSION['email'] ;
        // echo '<script type="text/javascript">'; 
        // echo 'alert("Login Anda Berhasil !");'; 
        //echo 'myFunction();'; 
        // echo 'window.location.href = "index.php";';
        // echo '</script>';
    // } else {
        // echo '<script type="text/javascript">'; 
        // echo 'alert("Login Gagal!");'; 
        // echo 'window.location.href = "login.php";';
        // echo '</script>';
    // }




    // $login_check = pg_num_rows($data);
    // if($login_check > 0){ 

    //     while($user = pg_fetch_object($data)){
    //         $_SESSION['id_user_login']    = $user->id;
    //         $_SESSION['name']       = $user->name;
    //         $_SESSION['email']      = $user->email;
    //         $_SESSION['role_id']      = $user->role_id;
    //     }
    //     echo $_SESSION['email'] ;
    //     echo '<script type="text/javascript">'; 
    //     echo 'alert("Login Anda Berhasil !");'; 
    //     //echo 'myFunction();'; 
    //     echo 'window.location.href = "index.php";';
    //     echo '</script>';

    // }else{
    //     echo '<script type="text/javascript">'; 
    //     echo 'alert("Login Gagal!");'; 
    //     echo 'window.location.href = "login.php";';
    //     echo '</script>';

    // }
// }





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
    <title>Login</title>

    <!-- Fontfaces CSS-->
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">

     <!-- <script src="jquery-3.3.1.min.js"></script>-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</head>

<body class="animsition">


    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="#">
                                <img src="images/icon/LJR-FAV-ICON.png" width="120px;" alt="CoolAdmin">
                            </a>
                            <h4>S3 APPS TOOLS & REPORTS</h4>
                        </div>
                        <div class="login-form">
                            
                        <form action="" method="post" enctype="multipart/form-data" id="loginform">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input class="au-input au-input--full" type="email" name="email" id="email" placeholder="Email">
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="au-input au-input--full" type="password" name="pwd" placeholder="Password">
                                </div>
                                <!--
                                <div class="login-checkbox">
                                    <label>
                                        <input type="checkbox" name="remember">Remember Me
                                    </label>
                                    <label>
                                        <a href="#">Forgotten Password?</a>
                                    </label>
                                </div>
                                -->
                                <br/>
                               
                                <button class="au-btn au-btn--block au-btn--blue m-b-20" type="submit" value="submit">sign in</button>
                                <!--
                                <div class="social-login-content">
                                    <div class="social-button">
                                        <button class="au-btn au-btn--block au-btn--blue m-b-20">sign in with facebook</button>
                                        <button class="au-btn au-btn--block au-btn--blue2">sign in with twitter</button>
                                    </div>
                                </div>
                                -->
                            </form>
                            <!--
                            <div class="register-link">
                                <p>
                                    Don't you have account?
                                    <a href="#">Sign Up Here</a>
                                </p>
                            </div>
                            -->
                        </div>
                    </div>
                    <hr/>
                    <center>
                        <br/><br/>
                    <b>OTHER MENUS</b>
                    <br/><br/>

                    <a href="other_apps/tracking-pengiriman/" class="btn btn-warning" role="button" style="margin:10px">TRACKING STTB</a>
                    <a href="other_apps/s3-status-scan/" class="btn btn-warning" role="button" style="margin:10px">STATUS SCAN</a>
                    <a href="other_apps/print-qrcode/" class="btn btn-warning" role="button" style="margin:10px">PRINT QR CODE</a>
                    <a href="other_apps/print-qrcode-bagging/" class="btn btn-warning" role="button" style="margin:5px">PRINT QR CODE BAGGING</a>
                    </center>

                </div>
            </div>
        </div>

      

    </div>



   

    <!-- Jquery JS
    <script src="vendor/jquery-3.2.1.min.js"></script>
-->
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

    <!-- Main JS-->
    <script src="js/main.js"></script>
    <script>
        $('#loginform').submit((function(e) {
            e.preventDefault();
            //alert('hello');
            $.ajax({
                url:"validasi_login.php",
                method:"POST",
                data: $(this).serialize(),
                success:function(response){
                    console.log(response)
                    if (response === 'true') {
                        Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Login Success',
                        text : 'Welcome',
                        showConfirmButton:false,
                        timer: 1500
                        }).then(function() {
                            window.location.href = 'index.php'; 
                        });

                } else if(response === 'false') {
                    Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Incorrect username or password !!',
                    })
                }
                }
            })
        }));
    </script>
   
        <?php
            echo '
            <script type="text/javascript">

            function myFunction() {

                swal({
                    position: "top-end",
                    type: "success",
                    title: "Your work has been saved",
                    showConfirmButton: false,
                    timer: 3500
                })
            }

            </script>
            ';
        ?>

    
 
    
</body>

</html>
<!-- end document-->