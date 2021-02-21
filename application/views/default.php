
<div class ="row"> 
	<div class="col-sm-4 col-md-4 col-lg-4">
		<div class="col-sm-12 col-md-12 col-lg-12">
			<div class="panel widgetbox wbox-2 bg-lighter-2 color-light">
				<a href="/kas-pemasukan">
					<div class="panel-content">
						<div class="row">
							<div class="col-xs-4">
								<span class="icon fa fa-arrow-circle-down color-light"></span>
							</div>
							<div class="col-xs-8">
								<h3 class="subtitle">KAS MASUK</h3>
								<h1 class="title">Rp. <?php  echo number_format($kas_masuk->row()->KAS_MASUK); ?></h1>
							</div>
						</div>
					</div>
				</a>
			</div>
		</div>
		<!--BOX Style 1-->
		<div class="col-sm-12 col-md-12 col-lg-12">
			<div class="panel widgetbox wbox-2 bg-warning color-light">
				<a href="/kas-pengeluaran">
					<div class="panel-content">
						<div class="row">
							<div class="col-xs-4">
								<span class="icon fa fa-arrow-circle-up color-light"></span>
							</div>
							<div class="col-xs-8">
								<h3 class="subtitle">KAS KELUAR</h3>
								<h1 class="title">Rp. <?php  echo number_format($kas_keluar->row()->KAS_KELUAR); ?></h1>
							</div>
						</div>
					</div>
				</a>
			</div>
		</div>
		<!--BOX Style 1-->
		<div class="col-sm-12 col-md-12 col-lg-12">
			<div class="panel widgetbox wbox-2 bg-lighter-4 color-light">
				<a href="/kas-pemasukan">
					<div class="panel-content">
						<div class="row">
							<div class="col-xs-4">
								<span class="icon fa fa-pie-chart color-light"></span>
							</div>
							<div class="col-xs-8">
								<h3 class="subtitle">SIMPANAN</h3>
								<h1 class="title">Rp. <?php  echo number_format($kas_masuk->row()->KAS_MASUK); ?></h1>
							</div>
						</div>
					</div>
				</a>
			</div>
		</div>
		<!--BOX Style 1-->
		<div class="col-sm-12 col-md-12 col-lg-12">
			<div class="panel widgetbox wbox-2 bg-lighter-5 color-light">
				<a href="/kas-pengeluaran">
					<div class="panel-content">
						<div class="row">
							<div class="col-xs-4">
								<span class="icon fa fa-credit-card color-light"></span>
							</div>
							<div class="col-xs-8">
								<h3 class="subtitle">PINJAMAN</h3>
								<h1 class="title">Rp. <?php  echo number_format($kas_keluar->row()->KAS_KELUAR); ?></h1>
							</div>
						</div>
					</div>
				</a>
			</div>
		</div>
		<!--BOX Style 1-->
		<div class="col-sm-12 col-md-12 col-lg-12">
			<div class="panel widgetbox wbox-2 bg-lighter-3 color-light">
				<a href="/kas-pemasukan">
					<div class="panel-content">
						<div class="row">
							<div class="col-xs-4">
								<span class="icon fa fa-users color-light"></span>
							</div>
							<div class="col-xs-8">
								<h3 class="subtitle">ANGGOTA</h3>
								<h1 class="title"> <?php  echo number_format($anggota->row()->TOTALANGGOTA); ?></h1>
							</div>
						</div>
					</div>
				</a>
			</div>
		</div>
		<!--BOX Style 1-->
		 
	</div>
	<div class="col-sm-8 col-md-8 col-lg-8">
		<div class="col-sm-12 col-md-12 col-lg-12">
			<!--BAR CHART-->
			<div class="panel">
				<div class="panel-header panel-info">
					<h3 class="panel-title section-subtitle"><b>NERACA</b> LABA RUGI</h3>
					<div class="panel-actions">
						<ul>
							<li class="action toggle-panel panel-expand"><span></span></li>
						</ul>
					</div>
				</div>
				<div class="panel-content">
					<canvas id="bar-chart" height="180px" style="width: 100%;"></canvas>
					<h4 id="lr">LABA RUGI : Rp. </h4>
				</div>
			</div>
		</div>
	</div>  
</div>
 
<div class ="row">
<div class="col-sm-12 col-md-12">
	<div class="panel" style ="margin: 15px;">
		<div class="panel-header bg-lighter-2 panel-info">
			<h3 class="panel-title">MAP KOLEKTOR</h3>
			<div class="panel-actions">
				<ul>
					<li class="action toggle-panel panel-expand"><span></span></li>
				</ul>
			</div>
		</div>
		<div class="panel-content" style="height: 60vh;" id="map">
		</div>
	</div>
</div>

</div>

<form class="form-inline" action="" method="POST" id="filterForm">
	<input class="form-control form-control-sm rounded bright multi-daterange" type="hidden" name="tgl">
	<input type="hidden" name="page" id="page" value="1"/>
	<input type="hidden" name="dataperpage" id="dataperpage" value="10"/>
	 <input type="hidden" name="urltarget" id="urltarget" value="laporan/laba_rugi/data"/>
  </form>

<script>
var base_url = '<?php  echo base_url();?>',
	action = 'view';
</script>
<script type="text/javascript" src="<?php  echo base_url();?>assets/js/laporan/labarugi.js?v=1.3.1"></script> 
<script type="text/javascript" src="<?php  echo base_url();?>dashboard/map"></script>
<script type="text/javascript">
       
</script>
<script type="text/javascript">
var bar = document.getElementById("bar-chart");

var options ={
    scales: {
        yAxes: [{
            ticks: {
                beginAtZero:true
            }
        }]
    }
};
        //BAR CHART EXAMPLE
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
var dataBars = {
    labels: ["<?php  echo "Tahun ".date('Y'); ?>"],
    datasets: [
        {
            label: "Pendapatan",
            fill: true,
            backgroundColor: "rgba(11, 143, 255, 0.65)",
            borderColor: "rgba(11, 143, 255, 0.65)",
            data: 0
        },
        {
            label: "Beban",
            fill: true,
            backgroundColor: "rgba(175, 175, 175, 0.26)",
            borderColor: "rgba(175, 175, 175, 0.26)",
            data: 0,
        }
    ],
    options: {
        scales: {
            yAxes: [{
                stacked: true
            }]
        }
    }
};

var barChar = new Chart(bar, {
    type: 'bar',
    data: dataBars,
    options: options

});
</script>


