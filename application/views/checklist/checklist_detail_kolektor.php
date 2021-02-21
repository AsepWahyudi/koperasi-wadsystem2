<div class="element-actions">
    <a class="btn btn-primary btn-sm" href="<?php  echo base_url();?>setoran-tunai-add"><i class="os-icon os-icon-ui-22"></i><span>Tambah Setoran Tunai</span></a>
</div>
<h6 class="element-header">
    Checklist Detail Kolektor
</h6>
<div class="element-box">
		<div class="controls-above-table">
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
		  <div class="col-sm-6 justify-content-sm-end">
			<div class="form-inline justify-content-sm-end">
			  
			  <form class="form-inline" action="" method="POST" id="filterForm">
				<!--<input class="form-control form-control-sm rounded bright" type="text" value="<?php  echo date("m/d/Y")?>" id="myudate" name="tgl">
				<button type="button" class="btn btn-sm btn-secondary btn-rounded" id="tampilfilter">Tampilkan</button>-->
				<input class="form-control form-control-sm rounded bright" type="hidden" value="<?php  echo $tgl?>" name="tgl">
				<input type="hidden" name="page" id="page" value="1"/>
				<input type="hidden" name="dataperpage" id="dataperpage" value="10"/>
				 <input type="hidden" name="urltarget" id="urltarget" value="checklist/ceklist_kolektor/detaildata"/>
			  </form>
			</div>
		  </div>
		</div>
	</div>
	<hr>
    
	<div class="table-responsive" id="table-view">
    	
		 <!--<form id="filterForm" action="#" method="POST">
		  <input type="hidden" name="urltarget" id="urltarget" value="simpanan/simpanan/datasetoran"/>
		  <input type="hidden" name="page" id="page" value="1"/>
		 </form>-->
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
			<table class="table table-striped table-lightfont">
				<thead>
					<tr>
                        <th>No</th>
                        <th>Tanggal Transaksi</th>
                        <th>Nama Anggota</th>
                        <th>Jumlah</th>
                        <th>Transaksi</th>
                        <th>Cabang</th>
                    </tr>
				</thead>
				<tbody></tbody>
			</table>
			<?php  echo $table_footer?>
		 </div>
    </div>
</div>

<script>
	var base_url	=	'<?php  echo base_url();?>',
		action		=	'view';
</script>
<script src="<?php  echo base_url();?>assets/js/checklist/checklistdetail.js"></script>