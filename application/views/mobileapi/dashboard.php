<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Wad System Dashboard">
    <meta name="author" content="wadsystem">
    <meta name="keywords" content="wadsystem">

    <!-- Title Page-->
    <title>Dashboard Marketplace</title>
    <link rel="apple-touch-icon" sizes="120x120" href="<?php  echo base_url();?>img/logos.png">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php  echo base_url();?>img/logos.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php  echo base_url();?>img/logos.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php  echo base_url();?>img/logos.png">

    <!-- Fontfaces CSS-->
    <link href="<?php  echo base_url();?>theme/market/css/font-face.css" rel="stylesheet" media="all">
    <link href="<?php  echo base_url();?>theme/market/vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="<?php  echo base_url();?>theme/market/vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="<?php  echo base_url();?>theme/market/vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="<?php  echo base_url();?>theme/market/vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="<?php  echo base_url();?>theme/market/vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="<?php  echo base_url();?>theme/market/vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="<?php  echo base_url();?>theme/market/vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="<?php  echo base_url();?>theme/market/vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="<?php  echo base_url();?>theme/market/vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="<?php  echo base_url();?>theme/market/vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="<?php  echo base_url();?>theme/market/vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="<?php  echo base_url();?>theme/market/css/theme.css" rel="stylesheet" media="all">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
  
    <style type="text/css">
        .modal-backdrop {
          z-index: 0;
          background: transparent;
        }
    </style>

</head>

<body class="">
    <div class="page-wrapper">
        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar2">
            <?php  include("logo.php"); ?>
            <div class="menu-sidebar2__content js-scrollbar1">
                <?php  include("menu.php"); ?>
            </div>
        </aside>
        <!-- END MENU SIDEBAR-->

        <!-- PAGE CONTAINER-->
        <div class="page-container2">
            <!-- HEADER DESKTOP-->
            <header class="header-desktop2">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap2">
                          <div class="logo d-block d-lg-none">
                            <?php  include("logo.php"); ?>
                          </div>
                            <div class="header-button2 d-lg-none">
                                <div class="header-button-item mr-0 js-sidebar-btn">
                                    <i class="zmdi zmdi-menu"></i>
                                </div>
                                <div class="setting-menu js-right-sidebar d-none d-lg-block">
                                    <div class="account-dropdown__body">
                                        <div class="account-dropdown__item">
                                          <?php  include("menu.php"); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <aside class="menu-sidebar2 js-right-sidebar d-block d-lg-none">
                <?php  include('logo.php'); ?>
                <div class="menu-sidebar2__content js-scrollbar2">
                    <?php  include('menu.php'); ?>
                </div>
            </aside>
            <!-- END HEADER DESKTOP-->

            <!-- MAIN CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        
                            <?php  
                               if($page !=""){
                                    $mypath = str_replace('index.php','application/views/mobileapi/data/',$_SERVER['SCRIPT_FILENAME']).$page.".php";
                                    if(file_exists($mypath)){
                                        include "data/".$page.".php";   
                                    }else{
                                        include"data/default.php";  
                                    }
                                }else{
                                    include"data/default.php";  
                                }
                            ?>
                    </div>
                </div>
            </div>
            <!-- END MAIN CONTENT-->

            <section>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="copyright">
                                <p>Marketplace by <a href="#">WadSystem</a>.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- END PAGE CONTAINER-->
        </div>
    </div>
    <!-- Jquery JS-->
    <script src="<?php  echo base_url();?>theme/market/vendor/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="<?php  echo base_url();?>theme/market/vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="<?php  echo base_url();?>theme/market/vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script src="<?php  echo base_url();?>theme/market/vendor/slick/slick.min.js">
    </script>
    <script src="<?php  echo base_url();?>theme/market/vendor/wow/wow.min.js"></script>
    <script src="<?php  echo base_url();?>theme/market/vendor/animsition/animsition.min.js"></script>
    <script src="<?php  echo base_url();?>theme/market/vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
    </script>
    <script src="<?php  echo base_url();?>theme/market/vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="<?php  echo base_url();?>theme/market/vendor/counter-up/jquery.counterup.min.js">
    </script>
    <script src="<?php  echo base_url();?>theme/market/vendor/circle-progress/circle-progress.min.js"></script>
    <script src="<?php  echo base_url();?>theme/market/vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?php  echo base_url();?>theme/market/vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="<?php  echo base_url();?>theme/market/vendor/select2/select2.min.js"></script>

    <!-- Main JS-->
    <script src="<?php  echo base_url();?>theme/market/js/main.js"></script>

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    


    <script type="text/javascript">
      $(document).ready( function () {
        $('#table_id').DataTable();
      } );
    </script>

</body>

</html>
<!-- end document-->
