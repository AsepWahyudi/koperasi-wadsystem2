<div class="br-pageheader">
	<nav class="breadcrumb pd-0 mg-0 tx-12">
	  <a class="breadcrumb-item" href="#">Dashboard</a>
	  <span class="breadcrumb-item active"><?php  echo $title?></span>
	</nav>
</div><!-- br-pageheader -->


<div class="br-pagebody">
	<?php  echo $this->session->flashdata('messagebox');?>
	<div class="br-section-wrapper">
			<div class="row">
				<div class="col-md-6">
					<h6 class="br-section-label"><?php  echo $title?></h6>
				</div>
				<div class="col-md-6 text-right">
					<a href="javascript:void(0)" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modaldemo1">Tambah Market Kategori</a>
				</div>
			</div>
          
			
			<hr>
            <div class="table-wrappers">
            <table id="datatable" class="table display  nowrap">
              <thead>
                <tr>
                  <th class="wd-30">No</th>
                  <th>Kateogri Market</th>
                  <th class="wd-150">Action</th>
                </tr>
              </thead>
              <tbody>
               <?php  
					$i=1;
					foreach($result->result() as $key){
			   ?>
			   <tr>
                  <td><?php  echo $i?></td>
                  <td><?php  echo $key->KATMARKET?></td>
                  <td>
                  <a href="<?php  echo base_url();?>market_kat/edit/<?php  echo $key->IDMARKETKAT;?>" class="editprod" alt="edit" title="edit"><i class="ion-android-settings"></i> Edit</a>
                  <a href="javascript:void(0)" class="hapuskat" alt="edit" title="hapus" id="<?php  echo base_url();?>market_kat/delete/<?php  echo $key->IDMARKETKAT;?>"><i class="ion-android-delete"></i> Hapus</a>
                  </td>
                  
                </tr>
					<?php  $i++;} ?>
              </tbody>
            </table>
          </div><!-- table-wrapper -->

		  
	</div>
</div>

</div><!-- br-pagebody -->



 <!-- BASIC MODAL -->
<div id="modaldemo1" class="modal">
<div class="modal-dialog modal-dialog-centered" role="document">
  <div class="modal-content bd-0 tx-14">
	<div class="modal-header pd-y-20 pd-x-25">
	  <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Tambah User</h6>
	  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	  </button>
	</div>
	<form method="post" action="" data-parsley-validate>
	<div class="modal-body pd-25">
	
	<div class="form-group">
	  <label class="form-control-label">Kateogri Market: <span class="tx-danger">*</span></label>
	  <input class="form-control" type="text" name="kat_market" value="" placeholder="Masukan Kategori Market" required>
	</div>
	  
	</div>
	<div class="modal-footer">
	  <button type="submit" class="btn btn-primary tx-uppercase btn-sm">Tambah Data</button>
	</div>
	</form>
  </div>
</div><!-- modal-dialog -->
</div><!-- modal -->