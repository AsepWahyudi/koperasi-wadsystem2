<div class="br-pageheader">
	<nav class="breadcrumb pd-0 mg-0 tx-12">
	  <a class="breadcrumb-item" href="#">Dashboard</a>
	  <span class="breadcrumb-item active"><?php  echo $headtitle?></span>
	</nav>
</div><!-- br-pageheader -->

<?php  
    if($detail){
        $row = $detail->row();
    }
?>
<div class="br-pagebody">
	<div class="br-section-wrapper">
		<div class="row">
            <div class="col-xl-6">
				<form method="post" action="" enctype="multipart/form-data">
				<?php  echo $response;?>
              <div class="form-layout form-layout-4">
                <h6 class="br-section-label"><?php  echo $headtitle?></h6>
                <div class="row">
                  <label class="col-sm-4 form-control-label">Nama: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control" placeholder="" name="nama" value="<?php  echo (isset($row->NAMA)) ? $row->NAMA : ''; ?>" required>
                  </div>
                </div>    
                  
                <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Kode: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control" placeholder="" name="kode" value="<?php  echo (isset($row->KODE)) ? $row->KODE : ''; ?>" required>
                  </div>
                </div>
                
                <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Harga Beli: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control" placeholder="" name="harga_beli" value="<?php  echo (isset($row->HARGA_BELI)) ? $row->HARGA_BELI : ''; ?>" required>
                  </div>
                </div>
                <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Harga Jual: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control" placeholder="" name="harga_jual" value="<?php  echo (isset($row->HARGA_JUAL)) ? $row->HARGA_JUAL : ''; ?>" required>
                  </div>
                </div>
             
                <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Kategori: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <select class="form-control" name="kategori" data-select2-id="1" tabindex="-1" aria-hidden="true">
            		<?php  
                    	foreach($katz->result() as $key){
            			 if(($row->KATEGORI == $key->IDMARKETKAT) && isset($row->KATEGORI)){
            			     $select = 'selected';
            			 }else{
            			     $select = '';
            			 }
            		?>
            		 <option <?php  echo $select?> value="<?php  echo $key->IDMARKETKAT?>"><?php  echo $key->KATMARKET?></option>
            		<?php  } ?>
            		</select>
                  </div>
                </div>
                <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Keterangan: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control" placeholder="" name="ket" value="<?php  echo (isset($row->KET)) ? $row->KET : ''; ?>" required>
                  </div>
                </div>
                <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Gambar: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input class="form-control" type="file" name="gambar" placeholder="Upload gambar">
                    <input type="hidden" class="form-control" placeholder="" name="imagenamepost" value="<?php  echo (isset($row->GAMBAR)) ? $row->GAMBAR : ''; ?>" >
                  </div>
                </div>
                <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Status: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <select class="form-control" data-select2-id="1" tabindex="-1" aria-hidden="true" name="aktif">
                        <option <?php  echo (isset($row->AKTIF) && ($row->AKTIF == 1)) ? 'selected' : ''; ?> value='1'>Aktif</option>
                        <option <?php  echo (isset($row->AKTIF) && ($row->AKTIF == 0)) ? 'selected' : ''; ?> value='0'>Non-Aktif</option>
                    </select>
                  </div>
                </div>
                <div class="form-layout-footer mg-t-30">
                  <button class="btn btn-info" type="submit" name="submitedit" value="submitedit" >Update Data</button>
                  
                </div><!-- form-layout-footer -->
              </div><!-- form-layout -->
			  </form>
            </div>
		</div>
	</div>
</div>