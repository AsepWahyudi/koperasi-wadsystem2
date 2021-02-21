<div class="col-sm-12">
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
                <div class="row">
                    <div class="col-sm-10 col-lg-8">
                        <h4 class="color-primary">Data Anggota Non Aktif</h4>
                    </div>
                    <div class="col-sm-3  col-lg-2 ">
                        <a href="<?php  echo base_url();?>add-anggota?rdr=<?php  echo $this->input->get('active')?>" class="btn btn-primary btn-block" >
                        	<i class="fa fa-plus"></i> 
                        	Tambah Anggota
                    	</a>
                    </div>
                    <div class="col-sm-2  col-lg-2 ">
                    	<?php  if($this->session->userdata('wad_cabang') ==""){ ?>
                    	<a href="javascript:void(0)" class="btn btn-success btn-block" onClick="alert('Silahkan Pilih Cabang')" >
                        	<i class="fa fa-table"></i> 
                        	Export Excel
                    	</a>
                    	<?php  }
 else{?>
                        <a href="<?php  echo base_url();?>anggota/anggota/anggota_excel" class="btn btn-success btn-block" >
                        	<i class="fa fa-table"></i> 
                        	Export Excel
                    	</a>
                    	<?php  } ?>
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
					</div>
				  </div>
				</div>
			</div>
	        <div class="panel-content">
                <div class="table-responsive" id="table-view">
					 <form id="filterForm" action="#" method="POST">
					  <input type="hidden" name="urltarget" id="urltarget" value="data-anggota-nonaktif?active=<?php  echo @$_GET['active']?>"/>
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
										<th scope="col">No Rek</th>
										<th scope="col">Nama Lengkap</th>
										<th scope="col">Jenis Kelamin</th>
										<th scope="col">Tanggal Lahir</th>
										<th scope="col">Usia</th>
										<th scope="col">Alamat</th>
										<th scope="col">Tgl Registrasi</th>
										<th scope="col">Status</th>
										<th scope="col">Pilihan</th>
									</tr>
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
<script type="text/javascript" src="<?php  echo base_url();?>assets/js/Master/anggota_nonaktif.js"></script>