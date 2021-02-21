<div class="col-sm-12">
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
                <div class="row">
                    <div class="col-sm-9 col-lg-9">
                        <h4 class="color-primary">Data Barang</h4>
                    </div>
                    <div class="col-sm-3  col-lg-3 ">
                        <a href="<?php  echo base_url();?>setoran-tunai-add" class="btn btn-primary btn-block" >
                        	<i class="fa fa-plus"></i> 
                        	Tambah Barang
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
                </div>
        </div>
    </div>
</div>
<div class="col-sm-12">
		<div class="panel">
			
	        <div class="panel-content">
            <input type="hidden" name="urltarget" id="urltarget" value="marketplace/barang/listbarang"/>
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
			                        <th>Kode Item</th>
			                        <th>Foto Barang</th>
			                        <th>Barcode</th>
			                        <th>Nama Item</th>
			                        <th>Kategori</th>
			                        <th>Harga Beli</th>
			                        <th>Harga Retail</th>
			                        <th>Harga Member</th>
			                        <th>Harga Toko</th>
			                        <th>Harga Sales</th>
			                        <th>Karton</th>
			                        <th>Satuan</th>
			                        <th>Box</th>
			                        <th>Stok</th>
			                        <th>Expired</th>
			                        <th>Opsi</th>
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
	var base_url = '<?php  echo base_url(); ?>';
		var action = 'view';
</script>
<script src="<?php  echo base_url();?>assets/js/marketplace/barang.js"></script>