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
                  <label class="col-sm-4 form-control-label">Nama: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control" placeholder="Nama Produk" name="nama" value="<?php  echo $row->NAMA?>" required>
                  </div>
                </div>
                <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Username: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control" placeholder="Nama Produk" name="username" value="<?php  echo $row->USERNAME?>" required>
                  </div>
                </div>
                
                 <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Level:</label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <select class="form-control" data-select2-id="1" tabindex="-1" aria-hidden="true" name="level">
                        <option <?php  echo ($row->LEVEL == 'admin') ? 'selected' : ''; ?> value='admin'>Admin</option>
                        <option <?php  echo ($row->LEVEL == 'operator') ? 'selected' : ''; ?> value='operator'>Operator</option>
                    </select>
                  </div>
                </div>
                
                <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Ganti Password:</label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control" name="gantipassword" value="" >
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