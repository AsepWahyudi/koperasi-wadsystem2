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
                    <div class="col-sm-6 col-lg-6">
                        <h4 class="color-primary">Daftar Anggota Baru</h4>
                    </div>
                    <div class="col-sm-2  col-lg-2 ">
                        <a href="<?php  echo base_url(); ?>add-anggota?rdr=<?php  $this->input->get('active')?>" class="btn btn-primary btn-block" >
                        	<i class="fa fa-plus"></i> 
                        	Tambah Anggota
                    	</a>
                    </div>
                    <div class="col-sm-2  col-lg-2 ">
	                    <a onclick="window.print()" class="btn btn-info btn-block" >
	                    	<i class="fa fa-print"></i> 
	                    	Cetak
	                	</a>
	                </div>
	                <div class="col-sm-2  col-lg-2 ">
	                    <a href="<?php  echo base_url();?>checklist/anggota/excel" class="btn btn-success btn-block" >
	                    	<i class="fa fa-table"></i> 
	                    	Export Excel
	                	</a>
	                </div>
                </div>
        </div>
    </div>
</div>
<div class="col-sm-12">
		<div class="panel">
	        <div class="panel-content b-primary bt-sm">
                <div class="table-responsive" id="table-view">
			    	
					 <form id="filterForm" action="#" method="POST">
					  <input type="hidden" name="urltarget" id="urltarget" value="data-anggota-baru?active=<?php  echo @$_GET['active']?>"/>
					  <input type="hidden" name="page" id="page" value="1"/>
					 </form>
					 <div class="dataTables_wrapper dt-bootstrap4">
						<div class="row">
							<div class="col-sm-12 col-md-6">
								<div class="dataTables_length" id="basic-datatables_length">
									<?php  echo $opt_data_entries?>
								</div>
							</div>
							<div class="col-sm-12 col-md-6">
								<div id="basic-datatables_filter" class="dataTables_filter">
									<label>Cari: <input type="search" id="form-search" class="form-control form-control-sm" aria-controls="basic-datatables"></label>
								</div>
							</div>
						</div><br/>
						<table class="table table-striped table-lightfont">
							<thead>
								<tr>
									<tr>
										<th scope="col">No</th>
										<th scope="col">Foto Wajah</th>
										<th scope="col">Cabang</th>
										<th scope="col">Nama Lengkap</th>
										<th scope="col">Jenis Kelamin</th>
										<th scope="col">Tanggal Lahir</th>
										<th scope="col">Usia</th>
										<th scope="col">Alamat</th>
										<th scope="col">Tgl Registrasi</th>
										<th scope="col">Status</th>
										<th scope="col" class="text-center print-hide">Pilihan</th>
									</tr>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
						<?php  echo $table_footer;?>
					 </div>
			    </div>
            </div>
        </div>
    </div>
<script>
	var base_url	=	'<?php  echo base_url();?>';
	var action		=	'view';
</script>
<script type="text/javascript" src="<?php  echo base_url();?>assets/js/checklist/anggota2.js"></script>
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
         .text-center.print-hide{display: none;}
         body{line-height: unset;}
         .daterangepicker{display: none;}
      }
      @media screen {
      	.head-print{display: none;}
      }
</style>