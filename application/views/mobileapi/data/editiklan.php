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
                  <label class="col-sm-4 form-control-label">Gambar: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input class="form-control" type="file" name="gambar" value="" placeholder="Upload gambar">
                    <input class="form-control" type="hidden" name="imagenamepost" value="<?php  echo $row->GAMBAR?>" >
                  </div>
                </div><!-- row -->
                <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Posisi: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <select class="form-control" name="posisi">
						  <option <?php  if($row->POSISI == 0){echo 'selected';} ?> value="0">Home</option>						
                          <option <?php  if($row->POSISI == 1){echo 'selected';} ?> value="1">Bottom</option>
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