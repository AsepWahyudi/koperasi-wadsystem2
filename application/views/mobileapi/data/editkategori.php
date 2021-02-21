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
				<?php  echo $response;?>
              <div class="form-layout form-layout-4">
                <h6 class="br-section-label"><?php  echo $headtitle?></h6>
                <div class="row">
                  <label class="col-sm-4 form-control-label">Kategori: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control" placeholder="Kategori" name="kategori" value="<?php  echo $row->KATEGORI?>" required>
                  </div>
                </div><!-- row -->
                <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Tipe: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <select class="form-control" name="tipe">
						<?php  
							foreach($tipe->result() as $key){
							 if($row->PARENT == $key->IDKAT){
							     $selected = 'selected';
							 }else{
							     $selected = '';
							 }
						?>
						  <option <?php  echo $selected?> value="<?php  echo $key->IDKAT?>"><?php  echo $key->KATEGORI?></option>
						<?php  } ?>
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