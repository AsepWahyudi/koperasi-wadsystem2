<div class="col-sm-12">
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
                <div class="row">
                    <div class="col-sm-7 col-lg-7">
                        <h4 class="color-primary">Data Transaksi Setoran Tunai</h4>
                    </div>
                    <div class="col-sm-3  col-lg-3 ">
                        <a href="<?php  echo base_url();?>setoran-tunai-add" class="btn btn-primary btn-block" >
                        	<i class="fa fa-plus"></i> 
                        	Tambah Setoran Tunai
                    	</a>
                    </div>
                    <?php  
						if($this->input->post('tgl') != "")
						{
							$tgl			= explode('-', $this->input->post('tgl'));
							$tgl1			= $tgl[0];
							$tgl2			= $tgl[1];
						}else{
							$tgl1			= date('01/m/Y');
							$tgl2			= date('d/m/Y');
						}
					?>
                    <div class="col-sm-2  col-lg-2 ">
                        <a href="<?php  echo base_url();?>simpanan/simpanan/setoran_excel?tgl1=<?php  echo $tgl1?>&tgl2=<?php  echo $tgl2?>" class="btn btn-success btn-block" >
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
				  <div class="col-sm-6 justify-content-sm-end">
					<div class="form-inline justify-content-sm-end">
					  
					  <form class="form-inline flr" action="" method="POST" id="filterForm">
						<input class="form-control form-control-sm rounded bright multi-daterange" type="text" name="tgl">
						<button type="button" class="btn btn-sm btn-secondary btn-rounded" id="tampilfilter">Tampilkan</button>
						<input type="hidden" name="page" id="page" value="1"/>
						<input type="hidden" name="dataperpage" id="dataperpage" value="10"/>
						 <input type="hidden" name="urltarget" id="urltarget" value="simpanan/simpanan/datasetoran"/>
					  </form>
					</div>
				  </div>
				</div>
			</div>
			
	        <div class="panel-content">
                <div class="table-responsive" id="table-view">
					 <!--<form id="filterForm" action="#" method="POST">
					  <input type="hidden" name="urltarget" id="urltarget" value="simpanan/simpanan/datasetoran"/>
					  <input type="hidden" name="page" id="page" value="1"/>
					 </form>-->
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
			                        <th>No</th>
			                        <th>Tanggal Transaksi</th>
			                        <th>Nama Anggota</th>
			                        <th>Alamat</th>
			                        <th>Jenis Simpanan</th>
			                        <th>Jumlah</th>
			                        <th>Keterangan</th>
			                        <th>User</th>
			                        <th>Action</th>
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
	var base_url	=	'<?php  echo base_url(); ?>';
		var action		=	'view';
</script>
<script src="<?php  echo base_url();?>assets/js/simpanan/simpanan.js"></script>