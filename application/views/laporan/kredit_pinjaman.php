<!--<div class="element-actions">
    <a class="btn btn-success btn-sm" href="<?php  echo base_url();?>laporan/kredit_macet/kreditmacet_excel"><i class="os-icon os-icon-grid-10"></i><span>Export Excel</span></a>
</div>-->


<h6 class="element-header">
    <?php  echo $PAGE_TITLE?>
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
                  <form class="form-inline" action="#" method="POST" id="filterForm">
                  	<!--Periode : &nbsp; 
                    <input class="form-control form-control-sm rounded bright" type="text" name="tgl" id="myudate">
                    <button type="button" class="btn btn-sm btn-secondary btn-rounded" id="tampilfilter">Tampilkan</button>-->
                    <input type="hidden" name="page" id="page" value="1"/>
                    <input type="hidden" name="kredit" value="<?php  echo $this->input->get('q')?>"/>
                    <input type="hidden" name="dataperpage" id="dataperpage" value="10"/>
                    <input type="hidden" name="urltarget" id="urltarget" value="laporan/kredit_pinj/data"/>
                  </form>
                </div>
              </div>
          
			</div>
		</div>
		
		<hr>
	
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
			<table class="table table-striped table-lightfont">
				<thead>
					<tr>
						<tr>
							<th scope="col">No</th>
							<th scope="col">Nama Anggota</th>
							<th scope="col">Tanggal Pinjam</th>
							<th scope="col">Jatuh Tempo</th>
							<th scope="col">Tagihan</th>
							<th scope="col">Dibayar</th>
							<th scope="col">Sisa</th>
							<th scope="col">Status</th>
						</tr>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
			<?php  echo $table_footer?>
		 </div>
    </div>
</div>
<style>
	table.font-small th, table.font-small td { font-size: .8rem !important; vertical-align:top; }
	span.f-right{ float: right;}
	
	span.macet{color: #82020; background: rgba(206, 0, 0, 0.3);}
	span.lancar{color: #1f6f04; background: rgba(38, 153, 0, 0.3);}
	span.meragukan{color: #965d08; background: rgba(153, 102, 0, 0.3);}
	span.buruk{color: #82020; background: rgba(206, 0, 0, 0.3);}
	
</style>
<script>
	var base_url	=	'<?php  echo base_url();?>',
		action		=	'view';
</script>
<script type="text/javascript" src="<?php  echo base_url();?>assets/js/laporan/kreditpinjaman.js?v=1.0"></script>