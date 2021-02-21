
<?php 

// echo print_r($this->session->all_userdata());
?>
<div class="col-sm-12">
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
			<div class="row">
				<div class="col-sm-10 col-lg-10">
					<h4 class="color-primary">Laporan Laba Rugi</h4>
				</div>
			 
			</div>
        </div>
    </div>
</div>
<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <form class="form-inline" action="#" method="POST" id="filterForm">
			<div class="row">
                <div class="col-sm-4">
                	<?php  if($this->session->userdata('wad_level')=="admin"){ ?>
						
						  Cabang &nbsp; 
						  <select class="form-control form-control-sm rounded bright" name="plhcabang">
							<option value="">All</option>
							<?php   
								foreach($cabs->result() as $cab){ 
							?>  
							<option value="<?php echo $cab->KODE?>"><?php echo $cab->NAMA?></option>
							<?php } ?>
						  </select>
					 
					  <?php  } ?>
                </div>
                <div class="col-sm-8">
                	<!--form class="form-inline flr" action="#" method="POST" id="filterForm"-->
	                    <!--select class="form-control form-control-sm rounded bright" name="idakun" id="idakun" style="width:250px">
	                    <option value="">All</option>
	                    <?php 
	                        //$data_source = $query->result_array();
	                        //$data_source = $this->tree->result_tree('PARENT', 'IDAKUN', $data_source);
	                        //$result      = $data_source['return'];
	                        
	                       // foreach($result as $key=>$res){
	                        //    echo $this->tree->levelOpt($res['_level'], $res['_header'], $res['IDAKUN'], $res['JENIS_TRANSAKSI']);
	                       // }
	                    ?>
					  </select-->
	                  
	                  	Periode : &nbsp; 
	                    <input class="form-control form-control-sm rounded bright multi-daterange" type="text" name="tgl">
	                    <button type="button" class="btn btn-sm btn-secondary btn-rounded" id="tampilfilter">Tampilkan</button>
						<button type="button" class="btn btn-sm btn-secondary btn-rounded" id="cetaklaporan">Cetak Laporan</button>
	                    <input type="hidden" name="page" id="page" value="1"/>
	                    <input type="hidden" name="dataperpage" id="dataperpage" value="10"/>
						<input type="hidden" name="urltarget" id="urltarget" value="laporan/laba_rugi/data"/>
                </div>
            </div>
			</form>
        </div>
        <div class="panel-content">
			<div class="table-responsive" id="table-view">
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
					<table class="table table-striped table-lightfont font-small">
						<thead>
							<tr>
								<tr>
									<th scope="col">No</th>
									<th scope="col">Kode</th>
									<th scope="col">Nama Perkiraan</th>
									<th scope="col">Saldo Akhir</th>
								</tr>
							</tr>
						</thead>
						<tbody></tbody> 
					</table>
					<?php echo $table_footer ?>
				 </div>
		    </div>
        </div>
    </div>
</div>
<style>
	table.font-small th, table.font-small td { font-size: .8rem !important; vertical-align:top; }
</style>
<script>
	var base_url = '<?php  echo base_url();?>',
		action   = 'view';
</script>
<script type="text/javascript" src="<?php  echo base_url();?>assets/js/laporan/labarugi.js"></script>