<!-- HEADER DESKTOP-->
<header class="header-desktop2">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="header-wrap2">
                    <div class="logo d-block d-lg-none">
                        <a href="#">
                            <img src="images/icon/Buat-Youtube2-1-1024x254.png" width="220px;" alt="CoolAdmin" />
                        </a>
                    </div>
                    <div class="header-button2">

                        <div class="header-button-item mr-0 js-sidebar-btn">
                            <i class="zmdi zmdi-menu"></i>
                        </div>
                        <div class="setting-menu js-right-sidebar d-none d-lg-block">
                            <div class="account2">
                                <div class="image img-cir img-120">
                                    <img src="images/icon/avatar-big-01.jpg" alt="John Doe" />
                                </div>
                                <br/>

                                <h5 class=""><?php echo '['.$_SESSION['id_user_login'].'] '. $_SESSION['name'];?></h5>
                                <small>[ <?php echo $_SESSION['nama_roles'];?> ]</small>
                                <br/>
                            
                                <h6 class=""><?php echo strtoupper($_SESSION['cabang_name']);?></h6>
                                
            
                              
                            </div>
                            <div class="account-dropdown__body">
                                <div class="account-dropdown__item">
                                    <a href="#" id="signout">
                                        <i class="zmdi zmdi-account"></i>Sign Out</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <aside class="menu-sidebar2 js-right-sidebar d-block d-lg-none">
        <div class="logo">
            <a href="#">
            <img src="images/icon/Buat-Youtube2-1-1024x254.png" width="250px;" alt="Cool Admin" />
            </a>
        </div>
        <div class="menu-sidebar2__content js-scrollbar2">
            <div class="account2">
                <div class="image img-cir img-120">
                    <img src="images/icon/avatar-big-01.jpg" alt="John Doe" />
                </div>

                <h5 class=""><?php echo '['.$_SESSION['id_user_login'].'] '. $_SESSION['name'];?></h5>
                <small>[ <?php echo $_SESSION['nama_roles'];?> ]</small>
                <br/>
            
                <h6 class=""><?php echo strtoupper($_SESSION['cabang_name']);?></h6>

                <!--<h6 class=""><?php echo $_SESSION['id_karyawan'];?></h6>-->
                
                <!-- <a onClick="return confirm('Please confirm Logout ?');" href="logout.php">Sign out</a> -->
                <!-- <a id="signout">Sign out</a> -->
                
                <button  id="signoutmob" type="button" class="btn btn-outline-primary mt-2">Sign Out</button>
            </div>
            <nav class="navbar-sidebar2">
                    <ul class="list-unstyled navbar__list">
                    <li class="has-sub">
                                <a class="" href="index.php">
                                    <i class="fas fa-bars"></i><b>DASHBOARD</b>
                                </a>
                        </li>

                        <li class="has-sub">
                                <a class="js-arrow" href="#">
                                    <i class="fas  fa-bars"></i><b>S3 REPORT</b>
                                    <span class="arrow">
                                        <i class="fas fa-angle-down"></i>
                                    </span>
                                </a>
                                <ul class="list-unstyled navbar__sub-list js-sub-list" style="margin-left: 20px;">
                                    <br/>
                                    <?php if ($_SESSION['role_id'] == 168 OR $_SESSION['role_id'] == 211 OR $_SESSION['role_id'] == 109) { ?>
                                        <div style="margin-left: 50px;">Master</div>
                                        <hr/>
                                        
                                        <li>
                                            <a href="index.php?page=rayon_index" style="font-size:14px !important;">
                                            <i class=""></i><i class="fas fa-check-circle"></i>Master Rayon</a>
                                        </li>

                                        <li>
                                            <a href="index.php?page=ops_loading" style="font-size:14px !important;">
                                            <i class=""></i><i class="fas fa-check-circle"></i></i>Loading</a>
                                        </li>
                                        <br/>
                                    <?php } ?>
                                </ul>
                                <!-- Edited by Mahesa -->
                                <ul class="has-sub">
                                    <a class="js-arrow" href="#">
                                        <i class="fas  fa-bars"></i><b>SHIPMENT</b>
                                        <span class="arrow">
                                            <i style="margin-top: 15px;"  class="fas fa-angle-down"></i>
                                        </span>
                                    </a>
                                    
                                    <ul class="list-unstyled navbar__sub-list js-sub-list">
                                        <br/>    
                                            <li>
                                                <a href="index.php?page=pickup_list" style="font-size:14px !important;">
                                                <i class=""></i><i class="fas fa-check-circle"></i>Pickup Barang</a>
                                            </li>

                                            <li>
                                                <a href="index.php?page=history_barang" style="font-size:14px !important;">
                                                <i class=""></i><i class="fas fa-check-circle"></i></i>History Pickup</a>
                                            </li>
                                    </ul>
                                </ul>
                                <!-- Edited by Mahesa -->
                        </li>
                   
                       
                    </ul>
                </nav>
        </div>
    </aside>
    <!-- END HEADER DESKTOP-->