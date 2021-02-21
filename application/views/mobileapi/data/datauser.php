<div class="br-pageheader">
	<nav class="breadcrumb pd-0 mg-0 tx-12">
	  <a class="breadcrumb-item" href="#">Dashboard</a>
	  <span class="breadcrumb-item active">Data User</span>
	</nav>
</div><!-- br-pageheader -->


<div class="br-pagebody">
	<?php  echo $response;?>
    <?php  echo $this->session->flashdata('messagebox');?>
	<div class="br-section-wrapper">
			<div class="row">
				<div class="col-md-6">
					<h6 class="br-section-label">Data User</h6>
				</div>
				<div class="col-md-6 text-right">
					<a href="javascript:void(0)" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modaldemo1">Tambah User</a>
				</div>
			</div>
          
			
			<hr>
            <div class="table-wrappers">
            <table id="datatable" class="table display  nowrap">
              <thead>
                <tr>
                  <th class="wd-30">No</th>
                  <th>Nama</th>
                  <th>Username</th>
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
                  <td><?php  echo $key->NAMA?></td>
                  <td><?php  echo $key->USERNAME?></td>
                  <td>
                  <a href="<?php  echo base_url();?>datauser/edit/<?php  echo $key->IDUSER;?>" class="editprod" alt="edit" title="edit"><i class="ion-android-settings"></i> Edit</a>
                  <a href="javascript:void(0)" class="hapuskat" alt="edit" title="hapus" id="<?php  echo base_url();?>datauser/delete/<?php  echo $key->IDUSER;?>"><i class="ion-android-delete"></i> Hapus</a>
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
	  <label class="form-control-label">Nama: <span class="tx-danger">*</span></label>
	  <input class="form-control" type="text" name="nama" value="" placeholder="Masukan Nama" required>
	</div>
	 
	<div class="form-group">
	  <label class="form-control-label">Username: <span class="tx-danger">*</span></label>
	  <input class="form-control" type="text" name="username" value="" placeholder="Masukan Username" required>
	</div>
	
	 <div class="form-group">
	  <label class="form-control-label">Password: <span class="tx-danger">*</span></label>
	  <input class="form-control" type="password" name="password" value="" placeholder="Masukan Password" required>

	</div>
	  
	</div>
	<div class="modal-footer">
	  <button type="submit" class="btn btn-primary tx-uppercase btn-sm">Tambah User</button>
	</div>
	</form>
  </div>
</div><!-- modal-dialog -->
</div><!-- modal -->