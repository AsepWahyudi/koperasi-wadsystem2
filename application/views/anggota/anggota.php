<div class="col-sm-12">
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
                <div class="row">
                    <div class="col-sm-6 col-lg-8">
                        <h4 class="color-primary">Data Anggota</h4>
                    </div>
                    <div class="col-sm-3  col-lg-2 ">
                        <a href="<?=base_url()?>add-anggota?rdr=<?=$this->input->get('active')?>" class="btn btn-primary btn-block" >
                        	<i class="fa fa-plus"></i> 
                        	Tambah Anggota
                    	</a>
                    </div>
                    <div class="col-sm-3  col-lg-2 ">
                    	<? if($this->session->userdata('wad_cabang') ==""){ ?>
                    	<a href="javascript:void(0)" class="btn btn-success btn-block" onClick="alert('Silahkan Pilih Cabang')" >
                        	<i class="fa fa-table"></i> 
                        	Export Excel
                    	</a>
                    	<?}else{?>
                         <a href="<?=base_url()?>anggota/anggota/anggota_excel/<?php echo $this->session->userdata('wad_cabang');?>" class="btn btn-success btn-block" >
                        	<i class="fa fa-table"></i> 
                        	Export Excel
                    	</a>
                    	<?}?>
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
				  <? if($this->session->userdata('wad_level')=="admin"){ ?>
					<form class="form-inline">
					  Cabang &nbsp;
					  
					  <select class="form-control form-control-sm rounded bright plhcabang" name="plhcabang" id="plhcabang">
						<option value="">All</option>
						<?
						
							$cabs = $this->dbasemodel->loadsql("SELECT NAMA,KODE FROM m_cabang ORDER BY NAMA ASC");
							foreach($cabs->result() as $cab){
								$sel = ($cab->KODE==$this->session->userdata('wad_cabang'))? 'selected="selected"':"";
						?>
					  
						<option value="<?=$cab->KODE?>" <?=$sel?>><?=$cab->NAMA?></option>
						<? } ?>
					  </select>
					</form>
				  <? } ?>
					   
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
					  <input type="hidden" name="urltarget" id="urltarget" value="data-anggota?active=<?=@$_GET['active']?>"/>
					  <input type="hidden" name="page" id="page" value="1"/>
					 </form>
					 <div class="dataTables_wrapper dt-bootstrap4">
						<div class="row">
							<div class="col-sm-12 col-md-6">
								<div class="dataTables_length" id="basic-datatables_length">
									<?=$opt_data_entries?>
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
						<?=$table_footer?>
					 </div>
				</div>
            </div>
        </div>
    </div>
<script>
	var base_url = '<?php echo base_url(); ?>';
	var action   = 'view';
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/Master/anggota.js"></script>