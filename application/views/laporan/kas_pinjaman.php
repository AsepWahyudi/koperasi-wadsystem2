<h6 class="element-header">
    Laporan Data Kas Pinjaman
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
                  	Periode : &nbsp; 
                    <input class="form-control form-control-sm rounded bright multi-daterange" type="text" name="tgl">
                    <button type="button" class="btn btn-sm btn-secondary btn-rounded" id="tampilfilter">Tampilkan</button>
                    <input type="hidden" name="page" id="page" value="1"/>
                    <input type="hidden" name="dataperpage" id="dataperpage" value="10"/>
                    <input type="hidden" name="urltarget" id="urltarget" value="laporan/kas_pinjaman/data"/>
                  </form>
                </div>
              </div>
              
			</div>
		</div>
		
		<hr>
	
    <div class="table-responsive" id="table-view">
		 <div class="dataTables_wrapper container-fluid dt-bootstrap4">
			<div class="row">
				<div class="col-sm-12 col-md-12">
					<div class="dataTables_length" id="basic-datatables_length">
						Jumlah Pinjaman &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: <span id="jml_pinj"> 0</span><br />
                        Pinjaman Lunas &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : <span id="pinj_lunas"> 0</span><br />
                        Pinjaman Belum Lunas &nbsp; &nbsp;: <span id="pinj_belum"> 0</span>
					</div>
				</div>
			</div><br/>
			<table class="table table-striped table-lightfont font-small">
				<thead>
					<tr>
						<tr>
							<th scope="col" width="10px">No</th>
							<th scope="col">Keterangan</th>
							<th scope="col" width="200px">Jumlah</th>
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
	span.macet{color: #de0505; font-weight: 500;}
	span.lancar{color: #3bb906; font-weight: 500;}
</style>
<script>
	var base_url	=	'<?php  echo base_url();?>',
		action		=	'view';
</script>
<script type="text/javascript" src="<?php  echo base_url();?>assets/js/laporan/kaspinjaman.js"></script>