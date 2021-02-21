<div class="col-sm-12">
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
                <div class="row">
                    <div class="col-sm-6 col-lg-10">
                        <h4 class="color-primary">Jenis Akun</h4>
                    </div>
                    <div class="col-sm-3  col-lg-2 element-actions ">
                        <button class="btn btn-primary btn-block" data-target="#mymodals" data-toggle="modal" type="button">Tambah kode</button>
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
                <table width="100%" class="table table-bordered table-striped" style="vertical-align: middle;">
                    <thead>
                        <tr>
                            <th style="width:110px">Kode Aktiva</th>
                            <th>Jenis Transaksi</th>
                            <th>Akun</th>
                            <th>Aktif</th>
                            <th>Laba Rugi</th>
                            <th>Tipe</th>
                            <th style="width:50px">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php  if($query->num_rows() > 0){ $n = 1;
        					$data_source	=	$query->result_array();
        					$data_source	=	$this->tree->result_tree('PARENT', 'IDAKUN', $data_source);
        					$result			=	$data_source['return'];
        	
        					foreach($result as $key=>$item){ $no = $n++;
        						$btn_edit	=	'<a href="javascript:;" onclick="loaddata(\'btnedit' . $no . '\')" var-url="master_data/jenis_akun/get_edit?id='.$item['IDAKUN'].'" class="btn btn-success btnedit'.$no.' btn-sm"><i class="fa fa-edit"></i></a>';
        						$btn_del	=	''; //'<a href="'.base_url().'master_data/jenis_akun/delete/'.$item['IDAKUN'].'" class="btn btn-danger btn-sm" style="margin-left:3px;" onclick="return confirm(\'Yakin dihapus?\')"><i class="fa fa-trash"></i></a>';  ?>
                                <tr>
                                    <td><?php  echo $item['KODE_AKTIVA']?></td>
                                    <td><?php  echo  $this->tree->level($item['_level'], $item['_header'], $item['JENIS_TRANSAKSI'])?></td>
                                    <td><?php  echo $item['AKUN']?></td>
                                    <td><?php  echo $item['AKTIF']?></td>
                                    <td><?php  echo $item['LABA_RUGI']?></td>
                                    <td><?php  echo $item['TIPE']?></td>
                                    <td><?php  echo $btn_edit . $btn_del?></td>
                                </tr>
                        <?php 	} }  ?>
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
</div>
<div aria-hidden="true" aria-labelledby="mymodals" class="modal" id="mymodals" role="dialog" tabindex="-1">
    <div class="modal-dialog" role="document">
        <form method="post" action="<?php  echo base_url();?>master_data/jenis_akun/save">
        <input type="hidden" name="idtrx" id="idtrx" value="">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Data Kode Perkiraan</h5>
            <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> &times;</span></button>
        </div>
        <div class="modal-body">
        	<div class="row">
            	<div class="col-sm-6">
                	<div class="form-group">
                    	<label for=""> Kode Perkiraan</label>
                        <input class="form-control" placeholder="Kode Perkiraan" type="text" name="kode_aktiva" id="kode_aktiva">
                	</div>
            	</div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for=""> Akun</label>
                        <select class="form-control" name="akun" id="akun">
                            <option value="">Pilih salah satu</option>
                            <option value="Aktiva">Aktiva</option>
                            <option value="Pasiva">Pasiva</option>
							<option value="Equity">Equity</option>
							<option value="Pendapatan">Pendapatan</option>
							<option value="Beban">Beban</option>
							<option value="Tpp">Taksiran Pajak Penghasilan</option>
                        </select>
                    </div>
                </div>
            </div>
        	<div class="form-group">
                <label for=""> Nama Perkiraan</label>
                <input class="form-control" placeholder="Nama Perkiraan" type="text" name="jenis_transaksi" id="jenis_transaksi">
            </div>
            
             <div class="row">
            	<div class="col-sm-6">
                	<div class="form-group">
                    	<label for=""> Pemasukan</label>
                        <select class="form-control" name="pemasukan" id="pemasukan">
                            <option value="Y">Ya</option>
                            <option value="N">Tidak</option>
                        </select>
                	</div>
            	</div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for=""> Pengeluaran</label>
                        <select class="form-control" name="pengeluaran" id="pengeluaran">
                            <option value="Y">Ya</option>
                            <option value="N" selected="selected">Tidak</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
            	<div class="col-sm-6">
                	<div class="form-group">
                    	<label for=""> Laba Rugi</label>
                        <select class="form-control" name="laba_rugi" id="laba_rugi">
                            <option value="">Pilih laba rugi</option>
                            <option value="PENDAPATAN">PENDAPATAN</option>
                            <option value="BIAYA">BIAYA</option>
                        </select>
                	</div>
            	</div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for=""> Aktif</label>
                        <select class="form-control" name="aktif" id="aktif">
                            <option value="Y">Ya</option>
                            <option value="N">Tidak</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
            	<div class="col-sm-6">
                	<div class="form-group">
                    	<label for=""> Akun Header/Induk</label>
                        <select class="form-control" name="parent" id="idparent">
                            <option value="0">Akun Header/Induk</option>
                            <?php  
								if($header->num_rows() > 0) {
									foreach($header->result() as $res) {
										echo '<option value="'. $res->IDAKUN .'">'. $res->JENIS_TRANSAKSI .'</option>';
									}
								}
							?>
                        </select>
                	</div>
            	</div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for=""> Jadikan Header/Induk</label>
                        <select class="form-control" name="header" id="header">
                            <option value="0" selected>Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
            	<div class="col-sm-6">
                	<div class="form-group">
                    	<label for=""> Tipe</label>
                       <select class="form-control" name="tipe" id="tipe">
                            <option value="HARTA">HARTA</option>
                            <option value="KEWAJIBAN">KEWAJIBAN</option>
							<option value="MODAL">MODAL</option>
							<option value="LABA RUGI">LABA RUGI</option>
							<option value="PENDAPATAN">PENDAPATAN</option>
							<option value="BIAYA">BIAYA</option>
							<option value="PAJAK">PAJAK</option>
                        </select>
                	</div>
            	</div>
                
            </div>
            
            <i>Ket: <br />
				1. Harap memilih akun yang akan dijadikan sebagai header/induknya Pada kolom <strong>Akun header/induk</strong>, tetapi jika ingin dijadikan sebagai akun header maka kosongkan pilihan tersebut.<br />
            	2. Pilih YA pada kolom <strong>jadikan header/induk</strong>, apabila ingin menjadikan akun tersebut sebagai induk/header.</i>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-dismiss="modal" type="button"> Close</button><button class="btn btn-primary" type="submit"> Simpan</button>
        </div>
        </div>
        </form>
    </div>
</div>
<script>
	var base_url	=	'<?php  echo base_url();?>';
</script>
<script type="text/javascript" src="<?php  echo base_url();?>assets/js/Master/jns_akun.js"></script>