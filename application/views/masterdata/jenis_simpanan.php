<div class="col-sm-12">
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
                <div class="row">
                    <div class="col-sm-6 col-lg-10">
                        <h4 class="color-primary">Jenis Simpanan</h4>
                    </div>
                    <div class="col-sm-3  col-lg-2 element-actions ">
                        <button class="btn btn-primary btn-block" data-target="#mymodals" data-toggle="modal" type="button">Tambah Jenis Simpanan</button>
                    </div>
                </div>
        </div>
    </div>
</div>
<style type="text/css">
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
            vertical-align: baseline;}
</style>
<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            
            <div class="table-responsive">
                <table id="dataTable1" width="100%" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Simpanan</th>
                            <th>Jumlah</th>
                            <th>Auto Debet</th>
                            <th>Tampil</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  if($query->num_rows() > 0){ $n = 1;
            				foreach($query->result() as $key){ $no = $n++;
            				$btn_edit	=	'<a href="javascript:;" onclick="loaddata(\'btnedit' . $no . '\')" var-url="master_data/jenis_simpanan/get_edit?id='.$key->IDJENIS_SIMP.'" class="btn btn-success btnedit'.$no.' btn-sm"><i class="fa fa-pencil"></i></a>';
            				$btn_del	=	'<a href="'.base_url().'master_data/jenis_simpanan/delete/'.$key->IDJENIS_SIMP.'" class="btn btn-danger btn-sm" style="margin-left:3px;" onclick="return confirm(\'Yakin dihapus?\')"><i class="fa fa-trash"></i></a>'; 
            			?>
                        <tr>
                            <td><?php  echo $no?></td>
                            <td><?php  echo $key->JNS_SIMP?></td>
                            <td><?php  echo toRp($key->JUMLAH)?></td>
                            <td><?php  echo ($key->AUTO_DEBET == 0 ? 'Tidak' : 'Ya')?></td>
                            <td><?php  echo ($key->TAMPIL == 'N' ? 'Tidak' : 'Ya')?></td>
                            <td><?php  echo $btn_edit; ?></td>
                        </tr>
                        <?php  } } ?>
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
</div>


<div aria-hidden="true" aria-labelledby="modaltambah" class="modal" id="modaltambah" role="dialog" tabindex="-1">
    <div class="modal-dialog" role="document">
        <form method="post" action="">
        
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Data Jenis Simpanan</h5>
            <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> &times;</span></button>
        </div>
        <div class="modal-body">
            <div class="row">
            	<div class="col-sm-6">
                	<div class="form-group">
                    	<label for=""> Jenis Simpanan</label>
                        <input class="form-control" placeholder="Jenis Simpanan" type="text" name="jns_simpadd" id="jns_simpadd">
                	</div>
            	</div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for=""> Jumlah</label>
                        <input class="form-control" placeholder="Jumlah" type="number" name="jumlahadd" id="jumlahadd">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for=""> Auto Debet</label>
                        <select class="form-control" name="auto_debetadd" id="auto_debetadd">
                            <option value="1">Aktif</option>
                            <option value="0" selected>Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="">Tampil</label>
                        <select class="form-control" name="tampiladd" id="tampiladd">
                            <option value="Y">Ya</option>
                            <option value="N">Tidak</option>
                        </select>
                    </div>
                </div>
            </div>
			
			 <div class="form-group">
				<label for=""> Akun</label>
				<select class="form-control" name="idakun" id="idakun">
					<?php  
						foreach($akunz->result() as $key){
					?>
					<option value="<?php  echo $key->IDAKUN?>"><?php  echo $key->AKUN . ' - ' . $key->JENIS_TRANSAKSI?></option>
						<?php  } ?>
				</select>
			</div>
            
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-dismiss="modal" type="button"> Close</button><button class="btn btn-primary" type="submit" name="simpantambah" value="simpantambah"> Tambah data</button>
        </div>
        </div>
        </form>
    </div>
</div>


<div aria-hidden="true" aria-labelledby="mymodals" class="modal" id="mymodals" role="dialog" tabindex="-1">
    <div class="modal-dialog" role="document">
        <form method="post" action="<?php  echo base_url();?>master_data/jenis_simpanan/save">
        <input type="hidden" name="idtrx" id="idtrx" value="">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Data Jenis Simpanan</h5>
            <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> &times;</span></button>
        </div>
        <div class="modal-body">
            <div class="row">
            	<div class="col-sm-6">
                	<div class="form-group">
                    	<label for=""> Jenis Simpanan</label>
                        <input class="form-control" placeholder="Jenis Simpanan" type="text" name="jns_simp" id="jns_simp">
                	</div>
            	</div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for=""> Jumlah</label>
                        <input class="form-control" placeholder="Jumlah" type="number" name="jumlah" id="jumlah">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for=""> Auto Debet</label>
                        <select class="form-control" name="auto_debet" id="auto_debet">
                            <option value="1">Aktif</option>
                            <option value="0" selected>Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="">Tampil</label>
                        <select class="form-control" name="tampil" id="tampil">
                            <option value="Y">Ya</option>
                            <option value="N">Tidak</option>
                        </select>
                    </div>
                </div>
            </div>
			
			 <div class="form-group">
				<label for=""> Akun</label>
				<select class="form-control" name="idakun" id="idakun">
					<?php  
						foreach($akunz->result() as $key){
					?>
					<option value="<?php  echo $key->IDAKUN?>"><?php  echo $key->AKUN . ' - ' . $key->JENIS_TRANSAKSI?></option>
						<?php  } ?>
				</select>
			</div>
            
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-dismiss="modal" type="button"> Close</button><button class="btn btn-primary" type="submit" name="simpanedit" value="simpanedit"> Simpan</button>
        </div>
        </div>
        </form>
    </div>
</div>
<script>
	var base_url	=	'<?php  echo base_url();?>'
</script>
<script type="text/javascript" src="<?php  echo base_url();?>assets/js/Master/jns_simpan.js?v=1.0"></script>