<div class="col-sm-12">
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
			<div class="row">
				<div class="col-sm-6 col-lg-10">
					<h4 class="color-primary">Data User</h4>
				</div>
				<div class="col-sm-3  col-lg-2 element-actions ">
					<button class="btn btn-primary btn-block" data-target="#mymodals" data-toggle="modal" type="button">Tambah User</button>
				</div>
			</div>
        </div>
    </div>
</div>
<style type="text/css">
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td { vertical-align: baseline; }
</style>
<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <?php  echo $response;?>
            <div class="table-responsive">
                <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Aktif</th>
                            <th>Level</th>
        					<!--th>Pusat</th-->
        					<th>Cabang</th>
                            <th>Approval</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($query->num_rows() > 0){ $n = 1;
                        foreach($query->result() as $key){ 
						
							$no = $n++;
						
                            $btn_edit = '<a href="javascript:;" onclick="loaddata(\'btnedit' . $no . '\')" var-url="master_data/user/get_edit?id='.$key->IDUSER.'" class="btn btn-success btnedit'.$no.' btn-sm"><i class="fa fa-edit"></i></a>';
							
        					$btn_del = '<a href="'.base_url().'master_data/user/delete/'.$key->IDUSER.'" class="btn btn-danger btn-sm" style="margin-left:3px;" onclick="return confirm(\'Yakin dihapus?\')"><i class="fa fa-trash"></i></a>';
							
							$getdatacabang = $this->dbasemodel->loadsql("SELECT * FROM m_cabang WHERE KODE='".$key->KODECABANG."'")->row();
                            ?>
                            <tr>
                            <td><?php echo $no?></td>
                            <td><?php echo $key->NAMA?></td>
                            <td><?php echo $key->USERNAME?></td>
                            <td><?php echo $key->AKTIF?></td>
                            <td><?php echo $key->LEVEL?></td>
        					<!--td><?php echo $key->KODEPUSAT?></td-->
        					<td><?php echo $getdatacabang->NAMA?></td>
                            <td><?php echo ($key->APPROVAL == 0 ? 'Tidak' : 'Ya')?></td>
                            <td><?php echo $btn_edit ?> <?php  echo $btn_del?></td>
                        </tr>
                        <?php  }} ?>
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
</div>
 
<div aria-hidden="true" aria-labelledby="mymodals" class="modal" id="mymodals" role="dialog" tabindex="-1">
    <div class="modal-dialog" role="document">
        <form method="post">
        <input type="hidden" name="iduser" id="iduser">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Data User</h5>
            <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> &times;</span></button>
        </div>
        <div class="modal-body"> 
            <div class="row">
            	<div class="col-sm-6">
                	<div class="form-group">
                        <label for=""> Nama</label><input class="form-control" placeholder="Nama" type="text" name="nama" id="nama">
                    </div>
            	</div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for=""> Approval</label>
                        <select class="form-control" name="approval" id="approval">
                            <option value="1">Ya</option>
                            <option value="0">Tidak</option>
                        </select>
                    </div>
                </div>
            </div>  
            <div class="row">
                <div class="col-sm-6">
                <div class="form-group">
                    <label for=""> Username</label><input class="form-control" placeholder="Username" type="text" name="uname" id="uname">
                </div>
                </div>
                <div class="col-sm-6">
                <div class="form-group">
                    <label for="">Password</label><input class="form-control" placeholder="Password" type="password" name="passwd" id="passwd">
                </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                <div class="form-group">
                    <label for=""> Aktif</label>
					<select class="form-control" name="aktif" id="aktif">
						<option value="Y">Aktif</option>
						<option value="N">Tidak Aktif</option>
					</select>
                </div>
                </div>
                <div class="col-sm-6">
                <div class="form-group">
                    <label for="">Level</label><select class="form-control" name="level" id="level">
						<option value="admin">Admin</option>
						<option value="it_support">IT Support</option>
						<option value="manajement_umum">Management Umum</option>
						<option value="keuangan_umum">Keuangan Umum</option>
						
						<option value="keuangan">Keuangan</option>
						<option value="regional">Regional</option>
						<option value="operator">Operator</option>
						<option value="kepala_cabang">Kepala cabang</option>
						<option value="approval">Approval</option>
						<option value="kolektor">Kolektor</option>
						<option value="analis">Analis</option>
						<option value="finance">Finance</option>
					</select>
                </div>
                </div>
            </div>
			<div class="row"> 
				<div class="col-sm-12">
					<div class="form-group">
						<label for="">Cabang</label>
						<select class="form-control" name="kodecabang" id="kodecabang">
							<!--<?php  
								foreach($cbg->result() as $key){
							?>
							<option value="<?php  echo $key->KODE?>"><?php  echo $key->NAMA?></option>
							<?php 
							}
							?>-->
							<?php  
								foreach($akn->result() as $key){
							?>
							<option value="<?php echo $key->IDAKUN; ?>"><?php echo $key->JENIS_TRANSAKSI; ?></option>
							<?php 
							}
							?>
						</select>
					</div>
				</div>
			</div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-dismiss="modal" type="button"> Close</button><button class="btn btn-primary" type="submit"> Simpan</button>
        </div>
        </div>
        </form>
    </div>
</div>
<script>
	var base_url = '<?php  echo base_url();?>'
</script>
<script type="text/javascript" src="<?php  echo base_url();?>assets/js/Master/user.js"></script>