<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <div class="row">
                <div class="col-sm-10 col-lg-10">
                    <h4 class="color-primary">Pencatatan Jurnal Umum</h4>
                </div>
                <div class="col-sm-2  col-lg-2 ">
                    <a href="<?php  echo base_url();?>akuntansi/jurnal_umum/form_add" class="btn btn-primary btn-sm btn-block" >
                        <i class="fa fa-plus"></i> 
                        Tambah Jurnal
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
				  Kantor/cabang &nbsp;
				  
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
					 <input type="hidden" name="urltarget" id="urltarget" value="akuntansi/jurnal_umum/datajurnal"/>
				  </form>
				</div>
			  </div>
			</div>
		</div>
        <div class="panel-content">
        	<div class="table-responsive" id="table-view">
				 <div class="dataTables_wrapper container-fluid dt-bootstrap4">
					<table class="table table-striped table-lightfont font-small">
						<thead>
							<tr>
								<tr>
									<th scope="col">No</th>
									<th scope="col">Jenis Jurnal</th>
									<th scope="col">Tanggal</th>
									<th scope="col">Kantor</th>
									<th scope="col">Referensi</th>
									<th scope="col">Keterangan</th>
									<th scope="col">Akun</th>
									<th scope="col">Debet</th>
									<th scope="col">Kredit</th>
								</tr>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				 </div>
		    </div>
        </div>
    </div>
</div>

<script>
	var base_url	=	'<?php  echo
  base_url();?>',
		action		=	'view';
</script>
<script type="text/javascript" src="<?php  echo
  base_url();?>assets/js/akuntansi/jUmum.js?v=1.0"></script>