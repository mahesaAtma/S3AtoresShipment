<!-- MENU SIDEBAR-->
<aside class="menu-sidebar2">
            <div class="logo" style="background:#F2F2F2;">
                <a href="#">
                    <img src="images/icon/Buat-Youtube2-1-1024x254.png" alt="Cool Admin" />
                </a>
            </div>
            <div class="menu-sidebar2__content js-scrollbar1">
                
                <nav class="navbar-sidebar2">
                    <ul class="list-unstyled navbar__list">

                        <li class="has-sub">
                                <a class="js-arrow" href="index.php">
                                    <i class="fas fa-bars"></i><b>DASHBOARD</b>
                                   
                                </a>
                                
                        </li>

                        <li class="has-sub">
                                <a class="js-arrow" href="#">
                                    <i class="fas  fa-bars"></i><b>S3 REPORT</b>
                                    <span class="arrow">
                                        <i style="margin-top: 15px;"  class="fas fa-angle-down"></i>
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
                                <?php } ?>
                                
                                </ul>
                        </li>

                        <!-- Edited by Mahesa -->
                        <li class="has-sub">
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
                        </li>
                        <!-- Edited by Mahesa -->
                    </ul>
                </nav>
            </div>
        </aside>
        <!-- END MENU SIDEBAR-->