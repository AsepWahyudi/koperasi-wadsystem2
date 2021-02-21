<!-- modal kategori -->
<div class="modal" id="kategoriModal" tabindex="-1" role="dialog" aria-labelledby="kategoriModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="mediumModalLabel">Tambah Kategori</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" action="" data-parsley-validate>
			<div class="modal-body">
				<div class="form-group">
                    <label for="kategori" class=" form-control-label">Kategori</label>
                    <input type="text" name="kategori" id="kategori" placeholder="Masukkan Kategori" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="tipe" class=" form-control-label">Tipe</label>
                    <select name="tipe" id="tipe" class="form-control" data-select2-id="1" required>
                        <option value="">Please select</option>
                        <?php  
							foreach($tipe->result() as $key){
						?>
						<option value="<?php  echo $key->IDKAT?>"><?php  echo $key->KATEGORI?></option>
							<?php  } ?>
					</select>
                </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="button" type="submit" class="btn btn-primary" name="submitedit" value="submitedit">Simpan</button>
			</div>
			</form>
		</div>
	</div>
</div>
<!-- end modal kategori -->