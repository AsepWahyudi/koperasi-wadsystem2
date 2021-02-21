<div class="col-sm-12">
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
                <div class="row">
                    <div class="col-sm-6 col-lg-10">
                        <h4 class="color-primary">Jenis Pinjaman</h4>
                    </div>
                    <div class="col-sm-3  col-lg-2 element-actions ">
                        <button class="btn btn-primary btn-block" data-target="#mymodals" data-toggle="modal" type="button">Tambah Jenis Pinjaman</button>
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
                <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Pinjaman</th>
                            <th>Type</th>
                            <th>Bagi Hasil</th>
                            <th>Biaya Admin</th>
                            <th>Rekomendasi Pinjaman</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                       	<?php  if($query->num_rows() > 0){ $n = 1;
        					foreach($query->result() as $key){ $no = $n++;
        					$btn_edit	=	'<a href="javascript:;" onclick="loaddata(\'btnedit' . $no . '\')" var-url="master_data/jenis_pinjaman/get_edit?id='.$key->IDJNS_PINJ.'" class="btn btn-success btnedit'.$no.' btn-sm"><i class="fa fa-pencil"></i></a>';
        					$btn_del	=	'<a href="'.base_url().'master_data/jenis_pinjaman/delete/'.$key->IDJNS_PINJ.'" class="btn btn-danger btn-sm" style="margin-left:3px;" onclick="return confirm(\'Yakin dihapus?\')"><i class="fa fa-trash"></i></a>'; 
        				?>
                        <tr>
                            <td><?php  echo $no?></td>
                            <td><?php  echo $key->JNS_PINJ?></td>
                            <td><?php  echo $key->TIPE?></td>
                            <td class="text-center"><?php  echo $key->BAGIHASIL?> %</td>
                            <td class="text-center"><?php  echo toRp($key->BIAYAADMIN)?></td>
                            <td class="text-center"><?php  echo toRp($key->REKOM_PINJ)?></td>
                            <td><?php  echo $btn_edit . $btn_del?></td>
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
        <form method="post" action="<?php  echo base_url();?>master_data/jenis_pinjaman/save">
        <input type="hidden" name="idtrx" id="idtrx" value="">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Data Jenis Pinjaman</h5>
            <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> &times;</span></button>
        </div>
        <div class="modal-body">
            <div class="row">
            	<div class="col-sm-6">
                	<div class="form-group">
                    	<label for=""> Jenis Pinjaman</label>
                        <input class="form-control" placeholder="Jenis Pinjaman" type="text" name="jns_pinj" id="jns_pinj">
                	</div>
            	</div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for=""> Tipe</label>
                        <input class="form-control" placeholder="Tipe" type="text" name="tipe" id="tipe">
                    </div>
                </div>
            </div>
            
            <div class="row">
            	<div class="col-sm-6">
                	<div class="form-group">
                    	<label for=""> Bagi Hasil</label>
                        <input class="form-control" type="text" name="bagihasil" id="bagihasil">
                	</div>
            	</div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for=""> Biaya Admin</label>
                        <input class="form-control" type="number" name="biayaadmin" id="biayaadmin">
                    </div>
                </div>
            </div>
            
            <div class="row">
            	<div class="col-sm-6">
                	<div class="form-group">
                    	<label for=""> Rekomendasi Pinjaman</label>
                        <input class="form-control" type="number" name="rekom_pinj" id="rekom_pinj">
                	</div>
            	</div>
                <div class="col-sm-6">
                    <div class="form-group">
                    	<label for=""> Biaya Asuransi</label>
                        <input class="form-control" type="number" name="asuransi" id="asuransi">
                	</div>
                </div>
            </div>
            
            
            <div class="row">
            	<div class="col-sm-6">
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
                <div class="col-sm-6">
                    <div class="form-group">
                    	<label for=""> Keterangan</label>
                        <input class="form-control" placeholder="Keterangan" type="text" name="ket" id="ket">
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
	var base_url	=	'<?php  echo base_url();?>'
</script>
<script type="text/javascript" src="<?php  echo base_url();?>assets/js/Master/jns_pinjam.js?v=1.0"></script>