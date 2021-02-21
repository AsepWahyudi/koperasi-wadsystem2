<div class="col-sm-12">
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
			<div class="row">
				<div class="col-sm-6 col-lg-10">
					<h4 class="color-primary">Jenis Kas Transaksi</h4>
					<?php// echo print_r($akn->result());	?>
				</div>
				<div class="col-sm-3  col-lg-2 element-actions ">
					<button class="btn btn-primary btn-block" data-target="#mymodals" data-toggle="modal" type="button">Tambah Jenis Kas </button>
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
            <div class="table-responsive">
                <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Akun</th>
                            <th>Cabang</th>
                            <th>Nama Kas</th>
                            <th>Aktif</th>
                            <th>Auto Debet</th>
                            <th>Simpanan</th>
                            <th>Penarikan</th>
                            <th>Pinjaman</th>
                            <th>Angsuran</th>
                            <th>Pemasukan Kas</th>
                            <th>Pengeluaran Kas</th>
                            <th>Transfer Kas</th>
                            <th style="width:100px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php  
						if($query->num_rows() > 0)
						{
							$n = 1;
						foreach($query->result() as $key)
						{ 
							$no = $n++;
							
							$getakun = $this->dbasemodel->loadsql("SELECT * FROM jns_akun WHERE IDAKUN='".$key->IDAKUN."'")->row();
							$getdatacabang = $this->dbasemodel->loadsql("SELECT * FROM m_cabang WHERE KODE='".$key->KODECABANG."'")->row();
							
							$btn_edit = '<a href="javascript:;" onclick="loaddata(\'btnedit' . $no . '\')" var-url="master_data/jenis_kas/get_edit?id='.$key->ID_JNS_KAS.'" class="btnedit'.$no.'"><i class="fa fa-edit"></i></a>';
        					$btn_del = '<a href="'.base_url().'master_data/jenis_kas/delete/'.$key->ID_JNS_KAS.'" class="" style="margin-left:3px; color:red" onclick="return confirm(\'Yakin dihapus?\')"><i class="fa fa-trash"></i></a>'; 
        				?>
                        <tr>
                            <td><?php echo $no; ?></td>
							<td><?php echo $getakun->JENIS_TRANSAKSI; ?></td>
							<td><?php echo $getdatacabang->NAMA; ?></td>
                            <td><?php echo $key->NAMA_KAS; ?></td>
                            <td><?php echo $key->AKTIF; ?></td>
                            <td><?php echo ($key->AUTO_DEBET == 1 ? 'Aktif' : 'Tidak Aktif')?></td>
                            <td><?php echo $key->TMPL_SIMPAN; ?></td>
                            <td><?php echo $key->TMLP_PENARIKAN; ?></td>
                            <td><?php echo $key->TMPL_PINJAMAN; ?></td>
                            <td><?php echo $key->TMPL_BAYAR; ?></td>
                            <td><?php echo $key->TMPL_PEMASUKAN; ?></td>
                            <td><?php echo $key->TMPL_PENGELUARAN; ?></td>
                            <td><?php echo $key->TMPL_TRANSVER; ?></td>
                            <td style="width:150px;"><?php echo $btn_edit . $btn_del; ?></td>
                        </tr>
                        <?php  
						}
						}
						?>
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
</div>

<div aria-hidden="true" aria-labelledby="mymodals" class="modal" id="mymodals" role="dialog" tabindex="-1">
    <div class="modal-dialog" role="document">
        <form method="post" action="<?php echo base_url();?>master_data/jenis_kas/save">
        <input type="hidden" name="idtrx" id="idtrx" value=""> 
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Data Jenis Kas</h5>
            <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> &times;</span></button>
        </div>
        <div class="modal-body">
			<div class="row">
            	<div class="col-sm-6">
                	<div class="form-group">
                    	<label for=""> Akun</label>
                        <select class="form-control" name="idakun" id="idakun"> 
							<?php  
							foreach($akn->result() as $keys){
							?>
								<option value="<?php echo $keys->IDAKUN; ?>"><?php echo $keys->JENIS_TRANSAKSI; ?></option>
							<?php 
							}
							?>
						</select>
                	</div>
            	</div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for=""> Cabang</label>
						
						<?php
						if($this->session->userdata("wad_level") == "admin")
						{
						?> 
                        <select class="form-control" name="kodecabang" id="kodecabang"> 
							<?php  
							foreach($cbg->result() as $key){
							?>
								<option value="<?php echo $key->IDAKUN; ?>"><?php echo $key->JENIS_TRANSAKSI; ?></option>
							<?php 
							}
							?>
						</select>
						<?php
						}
						else
						{ 
						?>
						<input type="text" name="kodecabang" id="kodecabang" value="<?php echo $this->session->userdata('wad_kodecabang');?>"> 
						<?php
						}
						?>
                    </div>
                </div>
            </div>
        	<div class="row">
            	<div class="col-sm-6">
                	<div class="form-group">
                    	<label for=""> Nama Kas</label>
                        <input class="form-control" placeholder="Nama Kas" type="text" name="nama_kas" id="nama_kas">
                	</div>
            	</div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for=""> Aktif</label>
                        <select class="form-control" name="aktif" id="aktif">
                            <option value="Y" selected>Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
            	<div class="col-sm-6">
                	<div class="form-group">
                    	<label for=""> Auto Debet</label>
                        <select class="form-control" name="auto_debet" id="auto_debet">
                            <option value="Y">Aktif</option>
                            <option value="T" selected>Tidak Aktif</option>
                        </select>
                	</div>
            	</div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for=""> Simpanan</label>
                        <select class="form-control" name="tmpl_simpan" id="tmpl_simpan">
                            <option value="Y">Ya</option>
                            <option value="T" selected>Tidak</option>
                        </select>
                    </div>
                </div>
            </div>
            
             <div class="row">
            	<div class="col-sm-6">
                	<div class="form-group">
                    	<label for=""> Penarikan</label>
                        <select class="form-control" name="tmlp_penarikan" id="tmlp_penarikan">
                            <option value="Y">Ya</option>
                            <option value="T" selected>Tidak</option>
                        </select>
                	</div>
            	</div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for=""> Pinjaman</label>
                        <select class="form-control" name="tmpl_pinjaman" id="tmpl_pinjaman">
                            <option value="Y">Ya</option>
                            <option value="T" selected>Tidak</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
            	<div class="col-sm-6">
                	<div class="form-group">
                    	<label for=""> Angsuran</label>
                        <select class="form-control" name="tmpl_bayar" id="tmpl_bayar">
                            <option value="Y">Ya</option>
                            <option value="T" selected>Tidak</option>
                        </select>
                	</div>
            	</div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for=""> Pemasukan Kas</label>
                        <select class="form-control" name="tmpl_pemasukan" id="tmpl_pemasukan">
                            <option value="Y">Ya</option>
                            <option value="T" selected>Tidak</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
            	<div class="col-sm-6">
                	<div class="form-group">
                    	<label for=""> Pengeluaran Kas</label>
                        <select class="form-control" name="tmpl_pengeluaran" id="tmpl_pengeluaran">
                            <option value="Y">Ya</option>
                            <option value="T" selected>Tidak</option>
                        </select>
                	</div>
            	</div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for=""> Transfer Kas</label>
                        <select class="form-control" name="tmpl_transver" id="tmpl_transver">
                            <option value="Y">Ya</option>
                            <option value="T" selected>Tidak</option>
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
<!-- <style>
@media (min-width: 800px) {
	.table-responsive {
		display: block;
		width: 100%;
		overflow-x: auto;
	}
}
</style> -->
<script>
	var base_url = '<?php  echo base_url();?>'
</script>
<script type="text/javascript" src="<?php  echo base_url();?>assets/js/Master/jns_kas.js"></script>