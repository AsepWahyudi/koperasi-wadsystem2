<div class="col-sm-12">
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
                <div class="row">
                    <div class="col-sm-9 col-lg-9">
                        <h4 class="color-primary">Data Transaksi Penarikan Tunais</h4>
                    </div>
                    <div class="col-sm-3 col-lg-3">
                        <a href="<?=base_url()?>penarikan-tunai-add" class="btn btn-primary btn-block" >
                        	Tambah Penarikan Tunai
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
					  
					  <form class="form-inline flr" action="" method="POST" id="filterForm">
						<input class="single-daterange form-control form-control-sm rounded bright" type="text" name="tgl">
						<button type="button" class="btn btn-sm btn-secondary btn-rounded" id="tampilfilter" data-action="simpanan/simpanan/datasetoran">Tampilkan</button>
						<input type="hidden" name="page" id="page" value="1"/>
						<input type="hidden" name="dataperpage" id="dataperpage" value="10"/>
						 <input type="hidden" name="urltarget" id="urltarget" value="simpanan/penarikan/datapenarikan"/>
					  </form>
					</div>
				  </div>
				</div>
			</div>
			<?=notifikasi($this->session->flashdata('ses_trx_simp'))?>
	        <div class="panel-content">
                <div class="table-responsive" id="table-view">
					 <!--<form id="filterForm" action="#" method="POST">
					  <input type="hidden" name="urltarget" id="urltarget" value="simpanan/penarikan/datapenarikan"/>
					  <input type="hidden" name="page" id="page" value="1"/>
					 </form>-->
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
			                        <th>No</th>
			                        <th>Tanggal Transaksi</th>
			                        <th>Nama Anggota</th>
			                        <th>Jenis Penarikan</th>
			                        <th>Jumlah</th>
			                        <th>User</th>
			                        <!--<th>Action</th>-->
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
	var base_url	=	'<?=base_url()?>',
		action		=	'view';
</script>
<script src="<?=base_url()?>assets/js/simpanan/penarikan.js"></script>