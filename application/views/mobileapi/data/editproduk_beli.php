<div class="br-pageheader">
	<nav class="breadcrumb pd-0 mg-0 tx-12">
	  <a class="breadcrumb-item" href="#">Dashboard</a>
	  <span class="breadcrumb-item active"><?php  echo $headtitle?></span>
	</nav>
</div><!-- br-pageheader -->

<?php  
    $row = $detail->row();
?>
<div class="br-pagebody">
	<div class="br-section-wrapper">
		<div class="row">
            <div class="col-xl-6">
				<form method="post" action="" enctype="multipart/form-data">
				<input type="hidden" name="tipe" value="<?php  echo $row->PRDINQ?>">
				<?php  echo $response;?>
              <div class="form-layout form-layout-4">
                <h6 class="br-section-label"><?php  echo $headtitle?></h6>
                <div class="row">
                  <label class="col-sm-4 form-control-label">Nama: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control" placeholder="Nama Produk" name="nama" value="<?php  echo $row->NAMA?>" required>
                  </div>
                </div><!-- row -->
                <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Kode: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control" placeholder="Kode Produk" name="kode" value="<?php  echo $row->KODE?>" required>
                  </div>
                </div>
                <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Harga Beli: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control" placeholder="Harga Beli" name="hbeli" value="<?php  echo $row->HARGA_BELI?>" required>
                  </div>
                </div>
				<div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Harga Jual: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control" placeholder="Harga Jual" name="hjual" value="<?php  echo $row->HARGA_JUAL?>" required>
                  </div>
                </div>
				<div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Kategori: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <select class="form-control" name="kategori">
						<?php  
							foreach($katz->result() as $key){
							 if($row->KATEGORI == $key->IDKAT){
							     $select = 'selected';
							 }else{
							     $select = '';
							 }
						?>
						      <option <?php  echo $select?> value="<?php  echo $key->IDKAT?>"><?php  echo $key->KATEGORI?></option>
							<?php  } ?>
					</select>
                  </div>
                </div>
				
				<div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Gambar: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="file" class="form-control" name="gambar">
                  </div>
                </div>
				
				<div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Diskripsi: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <textarea rows="2" class="form-control" placeholder="Diskripsi Produk" name="keterangan"><?php  echo $row->KET?></textarea>
                  </div>
                </div>

                <div class="form-layout-footer mg-t-30">
                  <button class="btn btn-info" type="submit">Update Data</button>
                  
                </div><!-- form-layout-footer -->
              </div><!-- form-layout -->
			  </form>
            </div>
		</div>
	</div>
</div>