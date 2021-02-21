<?php  
$wadCabang = $this->session->userdata('wad_cabang');
$cabsh = $this->dbasemodel->loadsql("SELECT * FROM m_cabang WHERE KODE =  ".$this->session->userdata('wad_kodecabang')."");
$cabHh = $cabsh->row();
?>
<div class="col-sm-12 head-print">
    <div class="panel">
        <div class="panel-content">
                <div class="row">
                        <table>
							<tr>
								<td><img src="img/logokop.png" style="width: 60px;height: 60px;margin-right: 10px;margin-left: 10px;" /></td>
								<td valign="top" class="headtitle">
								<h4><?php  echo $cabHh->NAMAKSP?> <?php  echo $cabHh->KOTA?></h4>
								<?php  echo $cabHh->ALAMAT?> <?php  echo $cabHh->KOTA?><br>
								Telp : <?php  echo $cabHh->TELP?> Email : <?php  echo $cabHh->EMAIL?><br>
								Web : <?php  echo $cabHh->WEB?>
								</td>
							</tr>
						</table>            
                </div>
        </div>
    </div>
</div>

<div class="col-sm-12">
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
                <div class="row">
                    <div class="col-sm-10 col-lg-10">
                        <h4 class="color-primary">Laporan Data Rekening Koran</h4>
                    </div>
                    <div class="col-sm-2 col-lg-2">
                        <button type="button" class="btn btn-sm btn-primary btn-rounded flr" onclick="window.print()">Cetak</button>
                    </div>                    
                </div>
        </div>
    </div>
</div>
<div class="col-sm-12">
	<div class="panel">
		<div class="panel-header b-primary bt-sm">
			<div class="row">
				<div class="col-sm-6">
				  <?php  if($this->session->userdata('wad_level')=="admin"){ ?>
					<form class="form-inline">
					  Cabang &nbsp;
					  
					  <select class="form-control form-control-sm rounded bright plhcabang" name="plhcabang" id="plhcabang">
						<option value="">All</option>
						<?php  
							$cabs = $this->dbasemodel->loadsql("SELECT NAMA,KODE FROM m_cabang ORDER BY NAMA ASC");
							$cabH = $cabs->result();
							
							foreach($cabH as $cab){
								$sel = ($cab->KODE==$this->session->userdata('wad_cabang'))? 'selected="selected"':"";
						?>
					  
						<option value="<?php  echo $cab->KODE?>" <?php  echo $sel?>><?php  echo $cab->NAMA?></option>
						<?php  } ?>
					  </select>
					</form>
				  <?php  } ?>
					   
				</div>
				<div class="col-sm-6">
	                  	<form class="form-inline flr" action="#" method="POST" id="filterForm">
	                    Periode : &nbsp; 
		                    <input class="form-control form-control-sm rounded bright multi-daterange" type="text" name="tgl">
		                    <button type="button" class="btn btn-sm btn-secondary btn-rounded" id="tampilfilter">Tampilkan</button>
		                    <input type="hidden" name="page" id="page" value="1"/>
		                    <input type="hidden" name="dataperpage" id="dataperpage" value="10"/>
		                    <input type="hidden" name="urltarget" id="urltarget" value="laporan/rekening_koran/data"/>
	                  	</form>
	            </div>
			</div>
		</div>
		<div class="panel-content">
			<div class="table-responsive" id="table-view">
				 <div class="dataTables_wrapper dt-bootstrap4">
					<div class="row">
						<div class="col-sm-12 col-md-6">
							<div class="dataTables_length" id="basic-datatables_length">
								<?php  echo $opt_data_entries?>
							</div>
						</div>
						<div class="col-sm-12 col-md-6">
							<div id="basic-datatables_filter" class="dataTables_filter">
								<label>Cari (Masukan Nama / ID Anggota) : 
								<input type="search" id="form-search" class="form-control form-control-sm" aria-controls="basic-datatables"></label>
							</div>
						</div>
					</div>
					<table id="example" class="table table-striped table-lightfont font-small display">
						<thead>
							<tr role="row">
									<th scope="col" aria-controls="example">No</th>
									<th scope="col" aria-controls="example">Tanggal</th>
									<th scope="col" aria-controls="example">Nama Anggota</th>
									<th scope="col" aria-controls="example" width="180">Jenis Transaksi</th>
									<th scope="col" aria-controls="example" width="200">Keterangan</th>
									<th scope="col" aria-controls="example" width="150">Jumlah</th>
									<!-- <th scope="col" aria-controls="example" width="150">Saldo Akhir</th> -->
							</tr>
						</thead>
						<tbody></tbody>
					</table>
					<?php  echo $table_footer?>
				 </div>
		    </div>
		</div>
    </div>
</div>

<style>
	span.f-right{ float: right;}
	span.macet{color: #de0505; font-weight: 500;}
	span.lancar{color: #3bb906; font-weight: 500;}
</style>
<style type = "text/css">
      @media print {
         .page-header{display: none;}
         .page-body{padding: unset;}
         .content-header{display: none;}
         .left-sidebar{display: none;}
         .btn{display: none;}
         .panel-header{display: none;}
         .dataTables_wrapper .row{display: none;}
         .content{margin: unset; margin-top: unset; padding: unset;}
         .panel{margin: unset; }
         .panel .panel-content{padding: 5px;}
         html.fixed .content{margin: unset;}
         h4{margin-top: 0px;margin-bottom: 0px;}
         .scroll-to-top{display: none;}
         .head-print{display: block;}
         body{line-height: unset;}
         .daterangepicker{display: none;}
      }
      @media screen {
      	.head-print{display: none;}
      }
</style>
<script>
	var base_url	=	'<?php  echo base_url();?>',
		action		=	'view';
</script>
<script type="text/javascript" src="<?php  echo base_url();?>assets/js/laporan/rekeningkoran.js?v=1.1.1"></script>