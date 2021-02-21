 <div class="col-sm-12">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <div class="row">
                <div class="col-sm-9 col-lg-9">
                    <h4 class="color-primary">Edit Anggunan Pinjaman</h4>
					<?php //echo $sqlkas;?>
                </div>
                <div class="col-sm-3 col-lg-3">
                    <a href="<?=base_url()?>sertifikat?active=Y" class="btn btn-primary btn-block" >
                        <i class="fa fa-angle-double-left"></i> 
                        Berkas Anggunan
                    </a>
                </div>
            </div>
        </div>
    
        <div class="panel-content"> 
            <form action="<?php echo base_url('sertifikat/sertifikat/save');?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="IDANGGUNAN" value="<?php echo $dataanggunan->IDANGGUNAN;?>">
				<input type="hidden" name="IDPINJM_H" value="<?php echo $datapinjaman->IDPINJM_H;?>">
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group">
							<label for="">Tanggal Penyerahan Dokumen:</label>
							<input id="default-datepicker" class="single-daterange form-control" placeholder="Tanggal Penyerahan Dokumen" type="text" name="tgl_trx">
						</div>  
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="">Jenis Jaminan:</label>
							<select class="form-control" name="id_jenis" data-error="Harap jenis jaminan" required>
                                <option value="" disabled selected>Pilih salah satu </option>
                            <?php
                                
								foreach($selectjaminan  as $res) {
									
									if($datapinjaman->JENIS_JAMINAN == $res->IDJAMINAN){
										
										echo '<option selected value="'. $res->IDJAMINAN .'">'. $res->NAMAJAMINAN.'</option>';
									}else{
										echo '<option value="'. $res->IDJAMINAN .'">'. $res->NAMAJAMINAN.'</option>'; 
									}
								} 
                            ?>
                            </select>
						</div>  
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="">No Berkas:</label>
							<input class="form-control" placeholder="No Berkas" type="text" name="no_jaminan" value="<?php echo $datapinjaman->NO_JAMINAN;?>">
						</div>  
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="">Nama Penyerah Dokumen:</label>
							<input class="form-control" disabled type="text" value="<?php echo $dataanggota->NAMA;?>">
						</div>  
					</div>
				</div>
               
                <div class="row">
                	<div class="col-sm-3">
						<div class="form-group">
							<label for="">Status Berkas:</label>
                            <select class="form-control" name="status" data-error="Harap jenis simpanan" required>
								<?php
								$selectedm = "";
								$selecteds = "";
								$selectedk = "";
								if($dataanggunan->STATUS =="Belum Masuk Berkas"){
									$selectedm = "selected";
								}elseif($dataanggunan->STATUS = "Sudah Masuk Berkas"){
									$selecteds = "selected";
								}else{
									$selectedk = "selected";
								}
								?>
                                <option value="" disabled selected>Pilih salah satu</option>
                                <option <?php echo $selectedm;?> value="Belum Masuk Berkas">Belum Masuk Berkas</option>
                                <option <?php echo $selecteds;?> value="Sudah Masuk Berkas">Sudah Masuk Berkas</option>
                                <option <?php echo $selectedk;?> value="Berkas Keluar">Berkas Keluar</option>
                           
                            </select>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="">Berkas* :</label><br> 
							<input class="form-control" id="file" type="file" name ="file" >
						</div>
					</div>  
                </div>
                 <br>  
                <div class="form-buttons-w">
                    <button class="btn btn-primary" type="submit"> Submit</button>
                </div>
            </form>
        </div>
    </div>
</div> 
 
