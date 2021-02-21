<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col">
						<i style="font-size: 24px;">Kategori Produk</i>
		          		<button type="button" class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="modal" data-target="#modaldemo1" style="float: right;">
							<i class="fa fa-plus"></i> Tambah Kategori
						</button>
		          	</div>	
	          	</div>
			</div>
			<div class="card-body">
					<?php  echo $this->session->flashdata('messagebox');?>
				<div class="table-responsive">
	                <table class="table table-striped table-hover" id="table_id">
		                <thead>
			                <tr>
			                  <th>No</th>
			                  <th>Kategori</th>
			                  <th>Tipe</th>
			                  <th>Action</th>
			                </tr>
		                </thead>
		                <tbody>
		                <?php  
							$i=1;
							foreach($result->result() as $key){
					   ?>
						   <tr>
			                  <td><?php  echo $i?></td>
			                  <td><?php  echo $key->KATEGORI?></td>
			                  <td><?php  echo $key->TIPE?></td>
			                  <td class="table-data-feature">
	                                <a href="<?php  echo base_url();?>kategori/edit-kat/<?php  echo $key->IDKAT?>" data-id="<?php  echo $key->IDKAT?>" data-kat="<?php  echo $key->KATEGORI?>" class="item" data-toggle="tooltip" data-placement="top" title="Edit">
	                                    <i class="zmdi zmdi-edit"></i>
	                                </a>
	                                <a href="javascript:void(0)" class="item" data-toggle="tooltip" data-placement="top" title="Hapus" id="<?php  echo base_url();?>kat/delete/<?php  echo $key->IDKAT?>">
	                                    <i class="zmdi zmdi-delete"></i>
	                                </a>
			                  </td>
			                </tr>
						<?php  $i++;} ?>
		                </tbody>
		        	</table>
		        </div>
			</div>
		</div>
	</div>
</div>

 <!-- BASIC MODAL -->
<div id="modaldemo1" class="modal">
<div class="modal-dialog modal-dialog-centered" role="document">
  <div class="modal-content bd-0 tx-14">
  <div class="modal-header pd-y-20 pd-x-25">
    <strong>Tambah Kategori</strong>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <form method="post" action="" data-parsley-validate>
  <div class="modal-body pd-25">
   
  <div class="form-group">
    <label class="form-control-label">Kategori: <span class="tx-danger">*</span></label>
    <input class="form-control" type="text" name="kategori" value="" placeholder="Masukan Kategori" required>

  </div>
  
   <div class="form-group mg-b-10-force">
      <label class="form-control-label">Tipe: <span class="tx-danger">*</span></label>
      <select class="form-control" name="tipe" data-select2-id="1" tabindex="-1" aria-hidden="true">
      <?php  
        foreach($tipe->result() as $key){
      ?>
      <option value="<?php  echo $key->IDKAT?>"><?php  echo $key->KATEGORI?></option>
        <?php  } ?>
      </select>
    </div>
    
  </div>
  <div class="modal-footer">
    <button type="submit" class="btn btn-primary tx-uppercase btn-sm"  name="submitadd" value="submitadd">Simpan</button>
  </div>
  </form>
  </div>
</div><!-- modal-dialog -->
</div><!-- modal -->