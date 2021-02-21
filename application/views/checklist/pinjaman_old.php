<h6 class="element-header">
    Daftar Pengajuan Pembiayaan
</h6>
<div class="element-box">
    <div class="table-responsive" id="table-view">
    	
		 <form id="filterForm" action="#" method="POST">
		  <input type="hidden" name="urltarget" id="urltarget" value="checklist/pinjaman/datapinjaman"/>
		  <input type="hidden" name="page" id="page" value="1"/>
		 </form>
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
                        <th>Tgl Pengajuan</th>
                        <th>Nama Anggota</th>
                        <th>Alamat</th>
                        <th>Perhitungan</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
				</thead>
				<tbody></tbody>
			</table>
			<?php  echo $table_footer?>
		 </div>
    </div>
</div>
<style>
	table.table-custom tr td{ padding: 0;}
	.table-customX td { vertical-align: top !important;} 
</style>
<script>
	var base_url	=	'<?php  echo base_url();?>',
		action		=	'view';
</script>
<script src="<?php  echo base_url();?>assets/js/checklist/pinjaman.js"></script>