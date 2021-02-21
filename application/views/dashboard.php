<!doctype html>
<html lang="en" class="fixed left-sidebar-top">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title id="title"><?php echo $PAGE_TITLE; ?></title>
  <link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url(); ?>img/logos.png">
  <link rel="icon" type="image/png" sizes="192x192" href="<?php echo base_url(); ?>img/logos.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>img/logos.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>img/logos.png">
  <!--load progress bar-->
  <script src="<?php echo base_url(); ?>theme/vendor/pace/pace.min.js"></script>
  <link href="<?php echo base_url(); ?>theme/vendor/pace/pace-theme-minimal.css" rel="stylesheet" /> 
  <!--BASIC css-->
  <!-- ========================================================= -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>theme/vendor/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>theme/vendor/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>theme/vendor/animate.css/animate.css">
  <!--SECTION css-->
  <!-- ========================================================= -->
  <!--Template lama-->
  <link href="<?php echo base_url(); ?>bower_components/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
  <!--Notification msj-->
  <link rel="stylesheet" href="<?php echo base_url(); ?>theme/vendor/toastr/toastr.min.css">
  <!--Magnific popup-->
  <link rel="stylesheet" href="<?php echo base_url(); ?>theme/vendor/magnific-popup/magnific-popup.css">
  <!--TEMPLATE css-->
  <!-- ========================================================= -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>theme/stylesheets/css/style.css">
  <!--Date picker-->
  <link rel="stylesheet" href="<?php echo base_url(); ?>theme/vendor/bootstrap_date-picker/css/bootstrap-datepicker3.min.css">
  <!--dataTable-->
  <link rel="stylesheet" href="<?php echo base_url(); ?>theme/vendor/data-table/media/css/dataTables.bootstrap.min.css">
  <!-- dataTable Columns hiding responsive-->
  <link rel="stylesheet" href="<?php echo base_url(); ?>theme/vendor/data-table/extensions/Responsive/css/responsive.bootstrap.min.css">
  <style type="text/css">
    .flr {
      float: right;
    } 
    h5 {
      display: contents;
    } 
    .nav-tabs>li>a.active {
      border-top: 2px solid #004E90;
      background-color: #dddddd;
    } 
    .nav-tabs>li>a {
      border-top: 2px solid #dddddd;
    }
	.panel {
		margin-bottom: 29px;  
	}
	.widgetbox.wbox-2 .subtitle { 
		font-weight: 700;
	}
	.bg-lighter-2 { 
		/* background: linear-gradient(-45deg, #06352a, #4CAF50, #022f3f, #033d2f) !important; */
		/* background: #388e3c !important; */
	    background: linear-gradient(45deg,#00b09b,#96c93d)!important;
	}
	.bg-lighter-3 {  
		/* background: #0d47a1 !important; */
		background: linear-gradient(45deg,#6a11cb,#2575fc)!important;
	}
	.bg-lighter-4 {  
		/* background: #00695c !important; */
		background: linear-gradient(45deg,#00695c ,#00b09b)!important;
	}
	.bg-lighter-5 {  
		/* background: #d84315 !important; */
		background: linear-gradient(45deg,#d84315 ,#ff6738 )!important;
	}
	.bg-warning { 
		/* background: linear-gradient(-45deg, #3a0404, #952a2a, #3f0202, #3d0303) !important; */
		/* background: #b71c1c !important; */
		background: linear-gradient(45deg,#ee0979,#ff6a00)!important;
	}
	.left-sidebar .left-sidebar-header .left-sidebar-title {
		 
		font-size: 20px;
		color: #ffffff;
		font-weight: 700; 
	}
	.panel .panel-header.panel-info {
		/* background-color: #1565c0 ;
		border-color: #1565c0 ; */
		    background: linear-gradient(45deg,#1565c0,#2575fc)!important;
	}
	.widgetbox.wbox-2 .icon {
		text-align: left; 
		font-size: 2.615385rem;
	}
	/* thead{
		background: #212121  !important;
		color: #fff!important;
	} */
	.table-striped > tbody > tr:nth-of-type(odd) {
		background-color: #e3f2fd  !important;
	}
  </style>

  <link href="<?php echo base_url(); ?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet"> 
  <script src="<?php echo base_url(); ?>bower_components/autocomplete/jquery-latest.min.js" type="text/javascript"></script>
  <script>
    //jQuery.noConflict();
    var jQuery11 = $.noConflict(true);
  </script>
  <script src="<?php echo base_url(); ?>bower_components/jquery/dist/jquery.min.js"></script>
  <script src="<?php echo base_url(); ?>bower_components/bootstrap-validator/dist/validator.min.js"></script> 
  <!--morris chart-->
  <script src="<?php echo base_url(); ?>theme/vendor/chart-js/chart.min.js"></script> 
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
  <link href="<?php echo base_url('assets/addchat/css/addchat.min.css') ?>" rel="stylesheet">
</head> 
<body>
  <div id="addchat_app" data-baseurl="<?php echo base_url() ?>" data-csrfname="<?php echo $this->security->get_csrf_token_name() ?>" data-csrftoken="<?php echo $this->security->get_csrf_hash() ?>"></div>
  <div aria-hidden="true" id="confirmModal" class="modal bd-example-modal-sm animated bounceInDown" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">
            Konfirmasi
          </h5>
          <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> ×</span></button>
        </div>
        <div class="modal-body">
          <p id="confirmModalText"></p>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-dismiss="modal" type="button"> Tutup</button>
          <button class="btn btn-primary" type="button" id="confirmModalBtn"> Yakin</button>
        </div>
      </div>
    </div>
  </div>

  <div aria-hidden="true" id="informationModal" class="modal bd-example-modal-sm animated bounceInDown" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">
            Informasi
          </h5>
          <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> ×</span></button>
        </div>
        <div class="modal-body">
          <p id="informationModalText"></p>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-dismiss="modal" type="button"> Ok</button>
        </div>
      </div>
    </div>
  </div>

  <div class="wrap">
    <!-- page HEADER -->
    <!-- ========================================================= -->
    <div class="page-header">
      <!-- LEFTSIDE header -->
      <div class="leftside-header">
        <div class="logo" style="width: fit-content;">
          <a href="<?php echo base_url(); ?>" class="on-click">
            <h3 style=" color: white; font-family: fantasy; font-family: sans-serif; font-weight: bold; margin-left: 20px; margin-right: 20px; margin-top: 12px; "> PAN </h3>
          </a>
        </div>
        <div id="menu-toggle" class="visible-xs toggle-left-sidebar" data-toggle-class="left-sidebar-open" data-target="html">
          <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
        </div>
      </div>
      <!-- RIGHTSIDE header -->
      <div class="rightside-header">
        <div class="header-middle"></div>
        <!--USER HEADERBOX -->
        <div class="header-section" id="user-headerbox">
          <div class="user-header-wrap">
            <div class="user-photo">
              <img alt="profile photo" src="<?php echo base_url(); ?>img/logos.png" />
            </div>
            <div class="user-info">
              <span class="user-name" style="font-variant-caps: all-petite-caps;"><?php echo $this->session->userdata('wad_user'); ?></span>
              <span class="user-profile">Pengurus</span>
            </div>
          </div>
        </div>
        <div class="header-separator"></div>
        <!--Log out -->
        <div class="header-section">
          <a href="<?php echo base_url(); ?>auth_logout" data-toggle="tooltip" data-placement="left" title="Logout">
			<i class="fa fa-sign-out log-out" aria-hidden="true" style ="font-family: 'FontAwesome'; color: #ffff; font-weight: 100;"></i> </a>
        </div>
      </div>
    </div>
    <!-- page BODY -->
    <!-- ========================================================= -->
    <div class="page-body">
      <!-- LEFT SIDEBAR -->
      <!-- ========================================================= -->
      <div class="left-sidebar">
        <!-- left sidebar HEADER -->
        <div class="left-sidebar-header">
          <div class="left-sidebar-title">Menu</div>
          <div class="left-sidebar-toggle c-hamburger c-hamburger--htla hidden-xs" data-toggle-class="left-sidebar-collapsed" data-target="html">
            <span></span>
          </div>
        </div>
        <!-- NAVIGATION -->
        <!-- ========================================================= -->
        <div id="left-nav" class="nano">
          <div class="nano-content">
            <nav>
              <?php include_once("menu.php"); ?>
            </nav>
          </div>
        </div>
      </div>
      <!-- CONTENT -->
      <!-- ========================================================= -->
      <div class="content">
        <!-- content HEADER -->
        <!-- ========================================================= -->
        <div class="content-header">
          <!-- leftside content header -->
          <div class="leftside-content-header">
            <ul class="breadcrumbs">
              <li><i class="fa fa-home" aria-hidden="true"></i><a href="<?php echo base_url(); ?>">Dashboard <?php //echo $this->session->userdata("wad_cabang");?></a></li>
              <?php if ($PAGE_TITLE != 'Dashboard') { ?>
                <li><a><?php echo $PAGE_TITLE; ?></a></li>
              <?php } ?>

            </ul>
          </div>
        </div>
        <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
        <div class="row animated fadeInUp">
          <div class="row">
            <div class="col-sm-12"> 
              <?php
              if ($page != "") 
			  {
                $mypath = str_replace('index.php', 'application/views/', $_SERVER['SCRIPT_FILENAME']) . $page . ".php";
                
				if (file_exists($mypath)) 
				{
                  include $page . ".php";
                } 
				else 
				{
                  include "default.php";
                }
              } 
			  else 
			  {
                include "default.php";
              }
              ?>

            </div>
          </div>
        </div>
        <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
      </div>
      <!--scroll to top-->
      <a href="#" class="scroll-to-top"><i class="fa fa-angle-double-up"></i></a>
    </div>
  </div>

  <!--BASIC scripts-->
  <!-- ========================================================= -->
  <script src="<?php echo base_url(); ?>theme/vendor/jquery/jquery-1.12.3.min.js"></script>
  <script src="<?php echo base_url(); ?>theme/vendor/bootstrap/js/bootstrap.js"></script>
  <script src="<?php echo base_url(); ?>theme/vendor/nano-scroller/nano-scroller.js"></script>

  <script src="<?php echo base_url(); ?>bower_components/bootstrap-validator/dist/validator.min.js"></script>

  <!--TEMPLATE scripts-->
  <!-- ========================================================= -->
  <script src="<?php echo base_url(); ?>theme/javascripts/template-script.min.js"></script>
  <script src="<?php echo base_url(); ?>theme/javascripts/template-init.min.js"></script>
  <!-- SECTION script and examples-->
  <!-- ========================================================= -->
  <!--Notification msj-->
  <script src="<?php echo base_url(); ?>theme/vendor/toastr/toastr.min.js"></script>

  <!--Gallery with Magnific popup-->
  <script src="<?php echo base_url(); ?>theme/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
  <!--Date picker-->
  <script src="<?php echo base_url(); ?>theme/vendor/bootstrap_date-picker/js/bootstrap-datepicker.min.js"></script>
  <!--dataTable-->
  <script src="<?php echo base_url(); ?>theme/vendor/data-table/media/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url(); ?>theme/vendor/data-table/media/js/dataTables.bootstrap.min.js"></script>
  <!-- dataTable Columns hiding responsive-->
  <script src="<?php echo base_url(); ?>theme/vendor/data-table/extensions/Responsive/js/dataTables.responsive.min.js"></script>
  <script src="<?php echo base_url(); ?>theme/vendor/data-table/extensions/Responsive/js/responsive.bootstrap.min.js"></script>
  <!--Examples-->
  <script src="<?php echo base_url(); ?>theme/javascripts/examples/tables/data-tables.js"></script>
  <script src="<?php echo base_url(); ?>theme/javascripts/examples/dashboard.js"></script>
  <script src="<?php echo base_url(); ?>theme/javascripts/examples/ui-elements/lightbox.js"></script>
  <!-- <script src="<?php echo base_url(); ?>theme/javascripts/examples/charts/chart-js.js"></script> -->

  <script src="<?php echo base_url(); ?>bower_components/popper.js/dist/umd/popper.min.js"></script> 
  <script src="<?php echo base_url(); ?>bower_components/moment/moment.js"></script>
  <script src="<?php echo base_url(); ?>bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
  <script src="<?php echo base_url(); ?>bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url(); ?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <script src="<?php echo base_url(); ?>bower_components/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js"></script>
  <script src="<?php echo base_url(); ?>bower_components/tether/dist/js/tether.min.js"></script>
  <script src="<?php echo base_url(); ?>bower_components/slick-carousel/slick/slick.min.js"></script>
  <script src="<?php echo base_url(); ?>bower_components/bootstrap/js/dist/util.js"></script>
  <script src="<?php echo base_url(); ?>bower_components/bootstrap/js/dist/alert.js"></script>
  <script src="<?php echo base_url(); ?>bower_components/bootstrap/js/dist/button.js"></script>
  <script src="<?php echo base_url(); ?>bower_components/bootstrap/js/dist/collapse.js"></script>
  <script src="<?php echo base_url(); ?>bower_components/bootstrap/js/dist/dropdown.js"></script>
  <script src="<?php echo base_url(); ?>bower_components/bootstrap/js/dist/modal.js"></script>
  <script src="<?php echo base_url(); ?>bower_components/bootstrap/js/dist/tab.js"></script>
  <script src="<?php echo base_url(); ?>js/dataTables.bootstrap4.min.js"></script>

  <script src="<?php echo base_url(); ?>js/main.js"></script>
  
  <?php
  if (is_array(@$js_to_load)) {
    foreach (@$js_to_load as $row) :
      echo '<script type="text/javascript" src="assets/js/' . $row . '">';
    endforeach;
  }
  ?>
 <script>
    $('#confirmModal').on('shown.bs.modal', function(event) {

      var confirmText = $(event.relatedTarget).attr('data-text-confirm'),
        urlRedirect = $(event.relatedTarget).attr('data-url-redirect');
      $('#confirmModalText').html(confirmText);

      $('#confirmModalBtn').click(function(e) {
        $(location).attr('href', urlRedirect);
      });

    });

    $("#cidentitas").change(function() {
      var input = $('#telp').val();
      if (input.length < 6) {
        alert("Silahkan Masukan nomer hp dengan benar");
      } else {
        $.ajax({
          url: "cekhp",
          type: "post",
          data: "nomer=" + input,
          success: function(response) {
            if (response == "ok") {

            } else {
              alert("Nomer telp sudah terdaftar di anggota lain");
            }
          }
        });
      }
    });

    //Default datepicker example
    $('#default-datepicker').datepicker({
      format: "dd/mm/yyyy",
    });

    //Range datepicker example
    $('#range-datepicker').datepicker({
      format: "dd/mm/yy",
      weekStart: 1,
      todayBtn: "linked",
      todayHighlight: true
    });

    $("#bank").change(function() {
      var input = $('#xidentitas').val();
      if (input.length < 8) {
        alert("Silahkan Masukan nomer identitas dengan benar");
      } else {
        $.ajax({
          url: "cekidentitas",
          type: "post",
          data: "nomer=" + input,
          success: function(response) {
            if (response == "ok") {

            } else {
              alert("Nomer Identitas sudah terdaftar di anggota lain");
            }
          }
        });
      }
    });
  </script>
  <?php if (is_array(@$js_to_load)) { ?>
    <?php foreach (@$js_to_load as $row) : ?>
      <script type="text/javascript" src="assets/js/<?php $row; ?>"></script>
    <?php endforeach; ?>
  <?php } ?>

  <script>
    $('input.multi-daterange').daterangepicker({
      "startDate": "<?php echo date('01/m/Y') ?>",
      "endDate"  : "<?php echo date('dd/mm/Y') ?>",
      "locale"   : {
		 'format': 'DD/MM/YYYY'
      }
    });
    $("#plhcabang").change(function() { 
      $.ajax({
        url    : "<?php echo base_url(); ?>ganticabang",
        type   : "post",
        data   : "cabang=" + this.value,
        success: function(response) {
          location.reload(true);
        }
      });
    });

    function rupiah(data) {
      if (data == "") {
        return data;
      }
      return number_format(data);
    }

    function number_format(number, decimals, dec_point, thousands_sep) {
      number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
      var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function(n, prec) {
          var k = Math.pow(10, prec);
          return '' + Math.round(n * k) / k;
        };
      // Fix for IE parseFloat(0.55).toFixed(0) = 0;
      s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
      if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
      }
      if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
      }
      return s.join(dec);
    }
  </script> 
  <script type="module" src="<?php echo base_url('assets/addchat/js/addchat.min.js') ?>"></script> 
  <script nomodule src="<?php echo base_url('assets/addchat/js/addchat-legacy.min.js') ?>"></script>
</body> 
</html>