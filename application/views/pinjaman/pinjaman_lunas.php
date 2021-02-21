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
                    <div class="col-sm-6 col-lg-10">
                        <h4 class="color-primary">Pelunasan Pinjaman</h4>
                    </div>
                    <div class="col-sm-2  col-lg-2 ">
	                    <a onclick="window.print()" class="btn btn-info btn-block" >
	                    	<i class="fa fa-print"></i> 
	                    	Cetak
	                	</a>
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
							foreach($cabs->result() as $cab){
								$sel = ($cab->KODE==$this->session->userdata('wad_cabang'))? 'selected="selected"':"";
						?>
					  
						<option value="<?php  echo $cab->KODE?>" <?php  echo $sel?>><?php  echo $cab->NAMA?></option>
						<?php  } ?>
					  </select>
					</form>
				  <?php  } ?>
				</div>
				<div class="col-sm-6">
					<form class="form-inline" action="#" method="POST" id="filterForm">
						<!--<input class="form-control form-control-sm rounded bright" type="text" value="<?php  echo date("m/d/Y")?>" id="myudate" name="tgl">
						<button type="button" class="btn btn-sm btn-secondary btn-rounded" id="tampilfilter">Tampilkan</button>-->
						<input type="hidden" name="page" id="page" value="1"/>
						<input type="hidden" name="dataperpage" id="dataperpage" value="10"/>
						 <input type="hidden" name="urltarget" id="urltarget" value="pinjaman/pinjaman/datalunas"/>
					  </form>
				</div>
			</div>				  
		</div>
			
	    <div class="panel-content table-responsive" id="table-view">
		    
		    <div class="table-responsive" id="table-view">
				 <div class="dataTables_wrapper container-fluid dt-bootstrap4">
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
					<table class="table table-striped table-lightfont table-customX">
						<thead>
							<tr>
		                        <th>No</th>
		                        <th>Nama Anggota</th>
		                        <th>Status</th>
		                        <th>Tgl Pinjam</th>
		                        <th>Jatuh Tempo</th>
		                        <th>Lama Pinjam</th>
		                        <th>Total Tagihan</th>
		                        <th>Total Denda</th>
		                        <th>Dibayar</th>
		                        <th class="text-center print-hide">Action</th>
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
<script>
	var base_url	=	'<?php  echo base_url();?>',
		action		=	'view';
</script>
<script src="<?php  echo base_url();?>assets/js/pinjaman/pinjamanLunas.js"></script>
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
         body{line-height: unset; font-size: x-small;}
         .daterangepicker{display: none;}
         .width1{
         	width: 1%;
         }
         .table-custom tr{
			border-bottom: inset;
    		border-bottom-width: thin;
         }
         .table-custom td{
			vertical-align: top;
         }
      }
      @media screen {
      	.head-print{display: none;}
      	.table-custom tr{
			border-bottom: inset;
    		border-bottom-width: thin;
         }
         .table-custom td{
			vertical-align: top;
         }
      }
</style>