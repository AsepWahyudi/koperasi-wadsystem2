<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <div class="row">
                <div class="col-sm-9 col-lg-10">
                    <h4 class="color-primary">Tambah Pemasukan</h4>
                </div>
                <div class="col-sm-3  col-lg-2 ">
                    <a href="<?php  echo base_url();?>kas-pemasukan" class="btn btn-primary btn-block" >
                        <i class="fa fa-angle-double-left"></i> 
                        Data Pemasukan
                    </a>
                </div>
            </div>
        </div>
    
        <div class="panel-content">
            <?php  
                if(isset($data_source)) {
                    $row = $data_source->row();
                }
            ?>
            <form method="post" action="<?php  echo base_url();?>transaksi_kas/pemasukan/<?php  echo ( isset($row->IDTRAN_KAS) ? 'update?id=' . $row->IDTRAN_KAS: 'save')?>">
				<div class="form-row">
					<div class="form-group col-md-2">
						<label >Tanggal Transaksi :</label>
						<input id="default-datepicker" class="form-control" placeholder="Tanggal Transaksi" type="text" value="<?php  echo (isset($row->IDTRAN_KAS) ? date('d/m/Y', strtotime($row->TGL)): date('d/m/Y'))?>" name="tgl">
					</div>
					<div class="form-group col-md-2">
						<label >Jumlah :</label>
						<input class="form-control" placeholder="100000" type="number" name="jumlah" value="<?php  echo (isset($row->IDTRAN_KAS) ? $row->JUMLAH : '')?>">
					</div>
					<div class="form-group col-md-4">
						<label >Dari Akun :</label>
						<select class="form-control" name="jenis_trans">
						<?php 
							if($dari_akun->num_rows() > 0) {
								foreach($dari_akun->result() as $res) {
									echo '<option value="'. $res->IDAKUN .'" '. (isset($row->IDTRAN_KAS) ? ($row->JENIS_TRANS == $res->IDAKUN ? 'selected' : ''): '') .'>'. $res->JENIS_TRANSAKSI .'</option>';
								}
							}
						?>
						</select> 
					</div>
					<div class="form-group col-md-4">
						<label for="">Untuk Kas :</label>
						<select class="form-control" name="untuk_kas_id">
						<?php 
							if($untuk_kas->num_rows() > 0) {
								$result = $untuk_kas->result_array();
								
								foreach($result as $key=>$res) {
									echo '<option value="'. $res['IDAKUN'].'">'. $res['NAMA_KAS'] .'</option>';
								}
							}
						?>
						</select>
					</div> 
				</div>
				
				<div class="form-row">
					<div class="form-group col-md-12">
						<label for="">Keterangan :</label>
						<input class="form-control" placeholder="Keterangan" type="text" name="keterangan" value="<?php  echo (isset($row->IDTRAN_KAS) ? $row->KETERANGAN : '')?>">
					</div>
				</div>
				 
				<div class="modal-footer">
					<div class="form-row">
						<div class="form-group col-md-12" style ="padding-top: 20px; text-align: center;">
							<button class="btn btn-primary" type="submit" style ="width:200px;"> Submit</button>
						</div>
					</div> 
				</div>
				
            </form>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-lg-6">
    	
        <div class="element-box">
        	
        </div>
	</div>
</div>
