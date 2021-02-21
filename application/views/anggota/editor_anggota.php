<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <div class="row">
                <div class="col-sm-9 col-lg-10">
                    <h4 class="color-primary"><?php  echo $PAGE_TITLE; ?></h4>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="formDataAnggota" action="<?php  echo $action;?>" method="post" class="formValidate" target="_self">
<?php 
if($this->uri->segment(1)=="edit-anggota"){; ?>
	<input value="<?php  if(isset($detail['IDANGGOTA'])) echo $detail['IDANGGOTA'] ?>" class="form-control" type="hidden" name="idanggota" >
<?php  
} 
?>
	<div class="col-sm-12 col-md-6 mt-md">
		<div class="panel">
	        <div class="panel-header b-primary bt-sm">
	        	<div class="row">
	        	<div class="col-sm-12">
					<h4 class="color-primary">Data Pribadi</h4>
					<h6 class="color-primary">Informasi Data Pribadi</h6>
	        	</div>
	        	</div>
	        </div>
	        <div class="panel-content">
	        	<div class="row">
	        	<div class="col-sm-12">
	        		<div class="form-group">
						<label for="">Nama Lengkap* :</label>
						<input value="<?php  if(isset($detail['NAMA'])) echo $detail['NAMA'] ?>" class="form-control" placeholder="Nama Lengkap" type="text" name="nama" data-error="Harap masukkan nama lengkap" required="required">
						<div class="help-block form-text with-errors form-control-feedback"></div>
					</div>

					<div class="row">
						

						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Agama : </label>
								<select class="form-control" name="agama">
									<option value=''></option>
									<option value='islam' <?php  if(isset($detail['AGAMA'])) echo ($detail['AGAMA'] == 'islam' ? 'selected' : '') ?>>Islam</option>
									<option value='katolik' <?php  if(isset($detail['AGAMA'])) echo ($detail['AGAMA'] == 'katolik' ? 'selected' : '') ?>>Katolik</option>
									<option value='protestan' <?php  if(isset($detail['AGAMA'])) echo ($detail['AGAMA'] == 'protestan' ? 'selected' : '') ?>>Protestan</option>
									<option value='hindu' <?php  if(isset($detail['AGAMA'])) echo ($detail['AGAMA'] == 'hindu' ? 'selected' : '') ?>>Hindu</option>
									<option value='budha' <?php  if(isset($detail['AGAMA'])) echo ($detail['AGAMA'] == 'budha' ? 'selected' : '') ?>>Budha</option>
									<option value='lainnya' <?php  if(isset($detail['AGAMA'])) echo ($detail['AGAMA'] == 'lainnya' ? 'selected' : '') ?>>Lainnya</option>
								</select>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Jenis Kelamin : </label>
								<select class="form-control" name="jk">
									<option value="laki-laki" <?php  if(isset($detail['JK'])) echo ($detail['JK'] == 'laki-laki' ? 'selected' : '') ?>>Laki-Laki</option>
									<option value="perempuan" <?php  if(isset($detail['JK'])) echo ($detail['JK'] == 'perempuan' ? 'selected' : '') ?>>Perempuan</option>
								</select>
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Tempat Lahir : </label>
								<input value="<?php  if(isset($detail['TMP_LAHIR'])) echo $detail['TMP_LAHIR'] ?>" class="form-control" placeholder="Tempat Lahir" type="text" name="tmptlhr">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Tgl Lahir : </label>
								<div class="date-input">
									<input value="<?php  if(isset($detail['TGL_LAHIR'])) echo $detail['TGL_LAHIR'] ?>" class="single-daterange form-control" placeholder="Date of birth" type="text" name="tgllhr">
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Status : </label>
								<select class="form-control" name="status">
									<option value=''></option>
									<option value='Belum Kawin' <?php  if(isset($detail['STATUS'])) echo ($detail['STATUS'] == 'Belum Kawin' ? 'selected' : '') ?>>Belum Kawin</option>
									<option value='Kawin' <?php  if(isset($detail['STATUS'])) echo ($detail['STATUS'] == 'Kawin' ? 'selected' : '') ?>>Kawin</option>
									<option value='Cerai Hidup' <?php  if(isset($detail['STATUS'])) echo ($detail['STATUS'] == 'Cerai Hidup' ? 'selected' : '') ?>>Cerai Hidup</option>
									<option value='Cerai Mati' <?php  if(isset($detail['STATUS'])) echo ($detail['STATUS'] == 'Cerai Mati' ? 'selected' : '') ?>>Cerai Mati</option>
									<option value='Lainnya' <?php  if(isset($detail['STATUS'])) echo ($detail['STATUS'] == 'Lainnya' ? 'selected' : '') ?>>Lainnya</option>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Pekerjaan : </label>
								<select class="form-control" name="pekerjaan">
									<option value=''></option>
									<option value='TNI' <?php  if(isset($detail['PEKERJAAN'])) echo ($detail['PEKERJAAN'] == 'TNI' ? 'selected' : '') ?>>TNI</option>
									<option value='PNS' <?php  if(isset($detail['PEKERJAAN'])) echo ($detail['PEKERJAAN'] == 'PNS' ? 'selected' : '') ?>>PNS</option>
									<option value='Karyawan Swasta' <?php  if(isset($detail['PEKERJAAN'])) echo ($detail['PEKERJAAN'] == 'Karyawan Swasta' ? 'selected' : '') ?>>Karyawan Swasta</option>
									<option value='Guru' <?php  if(isset($detail['PEKERJAAN'])) echo ($detail['PEKERJAAN'] == 'Guru' ? 'selected' : '') ?>>Guru</option>
									<option value='Buruh' <?php  if(isset($detail['PEKERJAAN'])) echo ($detail['PEKERJAAN'] == 'Buruh' ? 'selected' : '') ?>>Buruh</option>
									<option value='Tani' <?php  if(isset($detail['PEKERJAAN'])) echo ($detail['PEKERJAAN'] == 'Tani' ? 'selected' : '') ?>>Tani</option>
									<option value='Pedagang' <?php  if(isset($detail['PEKERJAAN'])) echo ($detail['PEKERJAAN'] == 'Pedagang' ? 'selected' : '') ?>>Pedagang</option>
									<option value='Wiraswasta' <?php  if(isset($detail['PEKERJAAN'])) echo ($detail['PEKERJAAN'] == 'Wiraswasta' ? 'selected' : '') ?>>Wiraswasta</option>
									<option value='Mengurus Rumah Tangga' <?php  if(isset($detail['PEKERJAAN'])) echo ($detail['PEKERJAAN'] == 'Mengurus Rumah Tangga' ? 'selected' : '') ?>>Mengurus Rumah Tangga</option>
									<option value='Pensiunan' <?php  if(isset($detail['PEKERJAAN'])) echo ($detail['PEKERJAAN'] == 'Pensiunan' ? 'selected' : '') ?>>Pensiunan</option>
									<option value='Penjahit' <?php  if(isset($detail['PEKERJAAN'])) echo ($detail['PEKERJAAN'] == 'Penjahit' ? 'selected' : '') ?>>Penjahit</option>
									<option value='Lainnya' <?php  if(isset($detail['PEKERJAAN'])) echo ($detail['PEKERJAAN'] == 'Lainnya' ? 'selected' : '') ?>>Lainnya</option>
								</select>
							</div>
						</div>
					</div>
		            
		            <div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Nama Pekerjaan : </label>
								<input class="form-control" name="nama_pekerjaan" id="nama_pekerjaan" value="<?php  if(isset($detail['NAMA_PEKERJAAN'])) echo $detail['NAMA_PEKERJAAN'] ?>" placeholder="Nama Pekerjaan" type="text">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Alamat Kantor : </label>
								<input class="form-control" name="alamat_pekerjaan" id="alamat_pekerjaan" value="<?php  if(isset($detail['ALAMAT_PEKERJAAN'])) echo $detail['ALAMAT_PEKERJAAN'] ?>" placeholder="Alamat Kantor" type="text">
							</div>
						</div>
					</div>
		            
		            <div class="row">
		            	<div class="col-sm-12">
						<div class="form-group">
							<label for="">Alamat Ktp* :</label>
							<input class="form-control" name="alamatktp" id="alamatktp" value="<?php  if(isset($detail['ALAMAT'])) echo $detail['ALAMAT'] ?>" placeholder="Alamat" type="text">
							<div class="help-block form-text with-errors form-control-feedback" data-error="Harap masukkan alamat lengkap" required="required"></div>
						</div>
					</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
						<div class="form-group">
							<label for="">Alamat Domisili* :</label> &nbsp;
			                <label for="gunakanalamatktp"><input type="checkbox" name="gunakanalamatktp" id="gunakanalamatktp"/> Gunakan alamat ktp</label>
							<input class="form-control" name="alamatdom" id="alamatdom" value="<?php  if(isset($detail['ALAMAT_DOMISILI'])) echo $detail['ALAMAT_DOMISILI'] ?>" placeholder="Alamat" type="text">
							<div class="help-block form-text with-errors form-control-feedback" data-error="Harap masukkan alamat domisili" required="required"></div>
						</div>
					</div>
					</div>
		            
		            <div class="row">
		            	<div class="col-sm-6">
							<div class="form-group">
								<label for="">Provinsi: </label>
								<select class="form-control" name="provinsi" id="provinsi" onchange="get_kota(this.value)">
								<option value=''>Pilih Provinsi</option>
									<?php  
										foreach($provinsi->result() as $res){
											$selected = "";
											if(isset($detail['IDPROVINSI'])){
												$selected =  ($detail['IDPROVINSI'] == $res->id_provinsi)? 'selected="selected"':"";
											}
											echo '<option value="'. $res->id_provinsi .'" '. $selected .'>'. $res->name .'</option>';
										}
									?>
								</select>
							</div>
						</div>
		                
		                <div class="col-sm-6">
							<div class="form-group">
								<label for="">Kota/Kabupaten : </label>
								<select class="form-control" name="idkota" id="idkota" onchange="get_kecamatan(this.value)">
								<?php  $option	=	'<option value="">Pilih Kota/Kabupaten</option>';
									if(isset($detail['IDKOTA'])){
										$option	=	'<option value="'.$detail['IDKOTA'].'">'.$detail['NAMA_KOTA'].'</option>';
									}
									echo $option; ?>
		                        </select>
							</div>
						</div>
					</div>
		            
		            <div class="row">
		            	<div class="col-sm-6">
							<div class="form-group">
								<label for="">Kecamatan: </label>
								<select class="form-control" name="kecamatan" id="kecamatan" onchange="get_kelurahan(this.value)">
								<?php  $option	=	'<option value="">Pilih Kecamatan</option>';
									if(isset($detail['IDKECAMATAN'])){
										$option	=	'<option value="'.$detail['IDKECAMATAN'].'">'.$detail['NAMA_KECAMATAN'].'</option>';
									}
									echo $option; ?>
		                        </select>
							</div>
						</div>
		                
		                <div class="col-sm-6">
							<div class="form-group">
								<label for="">Kelurahan/Desa : </label>
								<select class="form-control" name="kelurahan" id="kelurahan">
								<?php  $option	=	'<option value="">Pilih Kelurahan/Desa</option>';
									if(isset($detail['IDKELURAHAN'])){
										$option	=	'<option value="'.$detail['IDKELURAHAN'].'">'.$detail['NAMA_KELURAHAN'].'</option>';
									}
									echo $option; ?>
		                        </select>
							</div>
						</div>
					</div>
		            
		            <div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">No. Telp : </label>
								<input value="<?php  if(isset($detail['TELP'])) echo $detail['TELP'] ?>" class="form-control" placeholder="No Telp" type="text" name="telp" id="telp">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Ibu Kandung* :</label>
		                        <input value="<?php  if(isset($detail['IBU_KANDUNG'])) echo $detail['IBU_KANDUNG'] ?>" class="form-control" placeholder="Ibu Kandung" type="text" name="ibu" data-error="Harap masukkan nama ibu kandung" required="required">
		                        <div class="help-block form-text with-errors form-control-feedback"></div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Email : </label>
								<input value="<?php  if(isset($detail['EMAIL'])) echo $detail['EMAIL'] ?>" class="form-control" placeholder="email" type="email" name="email" id="email">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Tgl Registrasi : </label>
								<div class="date-input">
									<input value="<?php  if(isset($detail['TGL_DAFTAR'])) echo $detail['TGL_DAFTAR'] ?>" class="single-daterange form-control" placeholder="Tanggal Registrasi" type="text" name="tglreg">
								</div>
							</div>
						</div>
					</div>
	        	</div>
	        	</div>
	        </div>
	    </div>
	</div>

	<div class="col-sm-12 col-md-6 mt-md">
		<div class="panel">
	        <div class="panel-header b-primary bt-sm">
	        	<div class="row">
	        	<div class="col-sm-12">
					<h4 class="color-primary">Data Identitas</h4>
					<h6 class="color-primary">Informasi Identitas</h6>
	        	</div>
	        	</div>
	        </div>
	        <div class="panel-content">
	        	<div class="row">
	        	<div class="col-sm-12">
	        		<div class="row">
						<div class="col-sm-12">

						<div class="form-group">
							<label for="">Identitas</label>
							<select class="form-control" name="identitas" id="cidentitas">
								<option value=''></option>
								<option value='ktp' <?php  if(isset($detail['IDENTITAS'])) echo ($detail['IDENTITAS'] == 'ktp' ? 'selected' : '') ?>>Ktp</option>
								<option value='sim' <?php  if(isset($detail['IDENTITAS'])) echo ($detail['IDENTITAS'] == 'sim' ? 'selected' : '') ?>>Sim</option>
							</select>
						</div>

						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">No. Identitas* : </label>
								<input value="<?php  if(isset($detail['NO_IDENTITAS'])) echo $detail['NO_IDENTITAS'] ?>" class="form-control" id="xidentitas" placeholder="No Identitas" type="text" name="noidentitas" data-error="Harap masukkan nomor KTP" required="required">
								<div class="help-block form-text with-errors form-control-feedback"></div>
							</div>
						</div>
					
					</div>

					
					<div class="row">
						
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Nama Bank : </label>
								<select class="form-control" name="bank" id="bank">
								<option value=''>Pilih Bank</option>
									<?php  
										foreach($banks->result() as $bank){
											if(isset($detail['KODEBANK'])){
												$selected =  ($detail['KODEBANK']==$bank->KODE_BANK)? 'selected="selected"':"";
											}else{
												$selected = "";
											}
									?>
									<option value='<?php  echo $bank->NAMA_BANK?>-<?php  echo $bank->KODE_BANK?>' <?php  echo $selected?>><?php  echo $bank->NAMA_BANK?> [ <?php  echo $bank->KODE_BANK?> ]</option>
									<?php  } ?>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">No Rek : </label>
								<input value="<?php  if(isset($detail['NOREK'])) echo $detail['NOREK'] ?>" class="form-control" placeholder="No Rek" type="text" name="norek">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">No Kartu ATM : </label>
								<input value="<?php  if(isset($detail['NOKARTU'])) echo $detail['NOKARTU'] ?>" class="form-control" placeholder="No Kartu ATM" type="text" name="nokartu">
							</div>
						</div>
					</div>
	        	</div>
	        	</div>
	        </div>
	    </div>
	</div>

	<div class="col-sm-12 col-md-6 mt-md">
		<div class="panel">
	        <div class="panel-header b-primary bt-sm">
	        	<div class="row">
	        	<div class="col-sm-12">
					<h4 class="color-primary">Data Saudara</h4>
					<h6 class="color-primary">Informasi Data Saudara</h6>
	        	</div>
	        	</div>
	        </div>
	        <div class="panel-content">
	        	<div class="row">
	        	<div class="col-sm-12">
	        		<div class="form-group">
						<label for="">Saudara Yang Dapat Dihubungi* :</label>
						<input value="<?php  if(isset($detail['NAMA_SAUDARA'])) echo $detail['NAMA_SAUDARA'] ?>" class="form-control" placeholder="Saudara" type="text" name="saudara" data-error="Harap masukkan nama saudara yang dapat dihubungi" required="required">
						<div class="help-block form-text with-errors form-control-feedback"></div>
					</div>
					<div class="form-group">
						<label for="">Hubungan Saudara :</label>
						<input value="<?php  if(isset($detail['HUB_SAUDARA'])) echo $detail['HUB_SAUDARA'] ?>" class="form-control" placeholder="Hubungan Saudara" type="text" name="hubsaudara">
					</div>

					<div class="form-group">
						<label for="">Alamat Saudara :</label>
						<input class="form-control" name="alamatsaudara" value="<?php  if(isset($detail['ALMT_SAUDARA'])) echo $detail['ALMT_SAUDARA'] ?>">
					</div>

					<div class="form-group">
						<label for="">Telp Saudara :</label>
						<input value="<?php  if(isset($detail['TELP_SAUDARA'])) echo $detail['TELP_SAUDARA'] ?>" class="form-control" placeholder="Telp Saudara" type="text" name="telpsaudara">
					</div>
	        	</div>
	        	</div>
	        </div>
	    </div>
	</div>

	<div class="col-sm-12 col-md-6 mt-md">
		<div class="panel">
	        <div class="panel-header b-primary bt-sm">
	        	<div class="row">
	        	<div class="col-sm-12">
					<h4 class="color-primary">Lampiran</h4>
					<h6 class="color-primary">File Identitas</h6>
	        	</div>
	        	</div>
	        </div>
	        <div class="panel-content">
	        	<div class="row">
	        	<div class="col-sm-12">
	        		<div class="form-group">
						<!--<label for="">Status :</label>
						<select class="form-control" name="statusaktif">
							<option value=''></option>
							<option value='Y'>Aktif</option>
							<option value='N'>Non Aktif</option>
						</select>-->
						<div class="form-group">
							<label for="">Jabatan : </label>
							<select class="form-control" name="jabatan">
								<option value='2' <?php  if(isset($detail['JABATAN'])) echo ($detail['JABATAN'] == '2' ? 'selected' : '') ?>>Anggota</option>
								<option value='1' <?php  if(isset($detail['JABATAN'])) echo ($detail['JABATAN'] == '1' ? 'selected' : '') ?>>Pengurus</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="">Foto Wajah* :</label><br>
						<button class="mr-2 mb-2 btn btn-secondary btn-rounded" type="button" id="btnfotowajah"> Pilih Foto Wajah</button>
						<div id="blokfotowajah">&nbsp;</div>
						<img src="<?php  echo base_url();?>img/loading.gif" id="loadingfotowajah" style="display:none;">

						<?php  if(isset($detail['FILE_PIC'])) {?>
							<img src="<?php  echo base_url();?>uploads/identitas/<?php  echo $detail['FILE_PIC']?>" width="50" id="lamafotowajah">
						<?php  } ?>

						<input class="form-control" type="file" name="bfotowajah" id="bfotowajah" style="display:none;" data-url="<?php  echo base_url();?>uploads/identitas/">
						<input class="form-control" type="text" name="filefotowajah" id="filefotowajah" data-error="Harap upload foto wajah" required="required" style="display:none;" value="<?php  if(isset($detail['FILE_PIC'])) echo $detail['FILE_PIC'] ?>">
						<div class="help-block form-text with-errors form-control-feedback"></div>
						
					</div>

					<div class="form-group col-lg-6">
						<label for="">Foto Ktp* :</label><br>
						<button class="mr-2 mb-2 btn btn-secondary btn-rounded" type="button" id="btnfotoktp"> Pilih Foto Ktp </button>
						<div id="blokfotoktp">&nbsp;</div>
						<img src="<?php  echo base_url();?>img/loading.gif" id="loadingfotowajah" style="display:none;">
						
						<?php  if(isset($detail['FILE_KTP'])) {?>
							<img src="<?php  echo base_url();?>uploads/identitas/<?php  echo $detail['FILE_KTP']?>" width="50" id="lamafotoktp">
						<?php  } ?>
						<input class="form-control" type="file" name="bfotoktp" id="bfotoktp" style="display:none;" data-url="<?php  echo base_url();?>uploads/identitas/">
						<input class="form-control" type="text" name="filefotoktp" id="filefotoktp" data-error="Harap upload foto ktp" required="required" style="display:none;" value="<?php  if(isset($detail['FILE_KTP'])) echo $detail['FILE_KTP'] ?>">
						<div class="help-block form-text with-errors form-control-feedback"></div>
						
					</div>
					<div class="form-group col-lg-6">
						<label for="">Foto Data Lain :</label><br>
						<button class="mr-2 mb-2 btn btn-secondary btn-rounded" type="button" id="btnfotonpwp"> Pilih Foto Data Lain </button>
						<div id="blokfotonpwp">&nbsp;</div>
						<img src="<?php  echo base_url();?>img/loading.gif" id="loadingfotonpwp" style="display:none;">

						<?php  if(isset($detail['FILE_NPWP'])) {?>
							<img src="<?php  echo base_url();?>uploads/identitas/<?php  echo $detail['FILE_NPWP']?>" width="50" id="lamafotonpwp">
						<?php  } ?>

						<input class="form-control" type="file" name="bfotonpwp" id="bfotonpwp" style="display:none;" data-url="<?php  echo base_url();?>uploads/identitas/">
						<input class="form-control" type="text" name="filefotonpwp" id="filefotonpwp" style="display:none;" value="<?php  if(isset($detail['FILE_NPWP'])) echo $detail['FILE_NPWP'] ?>">
					
					</div>

					<div class="form-group col-lg-6">
						<label for="">Foto Kartu Keluarga :</label><br>
						<button class="mr-2 mb-2 btn btn-secondary btn-rounded" type="button" id="btnfotokk"> Pilih Foto kk </button>
						<div id="blokfotokk">&nbsp;</div>
						<img src="<?php  echo base_url();?>img/loading.gif" id="loadingfotokk" style="display:none;">

						<?php  if(isset($detail['FILE_KK'])) {?>
							<img src="<?php  echo base_url();?>uploads/identitas/<?php  echo $detail['FILE_KK']?>" width="50" id="lamafotokk">
						<?php  } ?>
						
						<input class="form-control" type="file" name="bfotokk" id="bfotokk" style="display:none;" data-url="<?php  echo base_url();?>uploads/identitas/">
						<input class="form-control" type="text" name="filefotokk" id="filefotokk" style="display:none;" value="<?php  if(isset($detail['FILE_KK'])) echo $detail['FILE_KK'] ?>">
					
					</div>

	                <div class="form-group col-lg-6">
						<label for="">Foto Buku Nikah :</label><br>
						<button class="mr-2 mb-2 btn btn-secondary btn-rounded" type="button" id="btnfotobn"> Pilih Foto Buku Nikah </button>
						<div id="blokfotobn">&nbsp;</div>
						<img src="<?php  echo base_url();?>img/loading.gif" id="loadingfotobn" style="display:none;">

						<?php  if(isset($detail['FILE_BK_NKH'])) {?>
							<img src="<?php  echo base_url();?>uploads/identitas/<?php  echo $detail['FILE_BK_NKH']?>" width="50" id="lamafotobn">
						<?php  } ?>

						<input class="form-control" type="file" name="bfotobn" id="bfotobn" style="display:none;" data-url="<?php  echo base_url();?>uploads/identitas/">
						<input class="form-control" type="text" name="filefotobn" id="filefotobn" style="display:none;" value="<?php  if(isset($detail['FILE_BK_NKH'])) echo $detail['FILE_BK_NKH'] ?>">
					
					</div>
					
					<div class="form-group col-lg-6">
						<label for="">Foto Surat Permohonan Pinjaman :</label><br>
						<button class="mr-2 mb-2 btn btn-secondary btn-rounded" type="button" id=""> Comming Soon </button>
						<div id="">&nbsp;</div>
						<img src="<?php  echo base_url();?>img/loading.gif" id="loadingfotobn" style="display:none;">

						<?php  if(isset($detail[''])) {?>
							<img src="<?php  echo base_url();?>uploads/identitas/<?php  echo $detail['']?>" width="50" id="">
						<?php  } ?>

						<input class="form-control" type="file" name="bfotobn" id="bfotobn" style="display:none;" data-url="<?php  echo base_url();?>uploads/identitas/">
						<input class="form-control" type="text" name="filefotobn" id="filefotobn" style="display:none;" value="<?php  if(isset($detail[''])) echo $detail[''] ?>">
					</div>
					
					<div class="form-group col-lg-6">
						<label for="">Foto Surat Bagi Hasil :</label><br>
						<button class="mr-2 mb-2 btn btn-secondary btn-rounded" type="button" id=""> Comming Soon </button>
						<div id="">&nbsp;</div>
						<img src="<?php  echo base_url();?>img/loading.gif" id="loadingfotobn" style="display:none;">

						<?php  if(isset($detail[''])) {?>
							<img src="<?php  echo base_url();?>uploads/identitas/<?php  echo $detail['']?>" width="50" id="">
						<?php  } ?>

						<input class="form-control" type="file" name="bfotobn" id="bfotobn" style="display:none;" data-url="<?php  echo base_url();?>uploads/identitas/">
						<input class="form-control" type="text" name="filefotobn" id="filefotobn" style="display:none;" value="<?php  if(isset($detail[''])) echo $detail[''] ?>">
					</div>
					
					<div class="form-group col-lg-6">
						<label for="">Foto Surat Kontrak :</label><br>
						<button class="mr-2 mb-2 btn btn-secondary btn-rounded" type="button" id=""> Comming Soon </button>
						<div id="">&nbsp;</div>
						<img src="<?php  echo base_url();?>img/loading.gif" id="loadingfotobn" style="display:none;">

						<?php  if(isset($detail[''])) {?>
							<img src="<?php  echo base_url();?>uploads/identitas/<?php  echo $detail['']?>" width="50" id="">
						<?php  } ?>

						<input class="form-control" type="file" name="bfotobn" id="bfotobn" style="display:none;" data-url="<?php  echo base_url();?>uploads/identitas/">
						<input class="form-control" type="text" name="filefotobn" id="filefotobn" style="display:none;" value="<?php  if(isset($detail[''])) echo $detail[''] ?>">
					</div>
					
					<div class="form-group col-lg-6">
						<label for="">Foto Surat Jaminan :</label><br>
						<button class="mr-2 mb-2 btn btn-secondary btn-rounded" type="button" id=""> Comming Soon </button>
						<div id="">&nbsp;</div>
						<img src="<?php  echo base_url();?>img/loading.gif" id="loadingfotobn" style="display:none;">

						<?php  if(isset($detail[''])) {?>
							<img src="<?php  echo base_url();?>uploads/identitas/<?php  echo $detail['']?>" width="50" id="">
						<?php  } ?>

						<input class="form-control" type="file" name="bfotobn" id="bfotobn" style="display:none;" data-url="<?php  echo base_url();?>uploads/identitas/">
						<input class="form-control" type="text" name="filefotobn" id="filefotobn" style="display:none;" value="<?php  if(isset($detail[''])) echo $detail[''] ?>">
					</div>
					
	        	</div>
	        	</div>
	        </div>
	    </div>
	</div>

	<div class="col-sm-12 col-md-6 mt-md">
		<div class="panel">
	        <div class="panel-header b-primary bt-sm">
	        	<div class="row">
	        	<div class="col-sm-12">
	        					<h4 class="color-primary">Data Kordinat</h4>
	        					<h6 class="color-primary">Latitude, Longitude</h6>
	        	</div>
	        	</div>
	        </div>
	        <div class="panel-content b-primary bt-sm">
	        	<div class="row">
	        	<div class="col-sm-12">
	        		<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Latitude : </label>
								<input value="<?php  if(isset($detail['lat'])) echo $detail['lat'] ?>" class="form-control" placeholder="0.00000" type="text" name="lat" id="lat">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Longitude : </label>
		                        <input value="<?php  if(isset($detail['lng'])) echo $detail['lng'] ?>" class="form-control" placeholder="100.00000" type="text" name="lng" id="lat">
							</div>
						</div>
					</div>
					<br>
	        		<!-- <div class="row">
	        						        		<div class="form-group">
	        											<label for="">No. Sertifikat* :</label>
	        											<input value="<?php  if(isset($detail['NAMA_SAUDARA'])) echo $detail['NAMA_SAUDARA'] ?>" class="form-control" placeholder="No Sertfikat" type="text" name="saudara" data-error="Harap masukkan nama saudara yang dapat dihubungi" required="">
	        											<div class="help-block form-text with-errors form-control-feedback"></div>
	        										</div>
	        										<div class="form-group">
	        											<label for="">Provinsi :</label>
	        											<input value="<?php  if(isset($detail['HUB_SAUDARA'])) echo $detail['HUB_SAUDARA'] ?>" class="form-control" placeholder="Provinsi" type="text" name="hubsaudara">
	        										</div>
	        				
	        										<div class="form-group">
	        											<label for="">Kota :</label>
	        											<input value="<?php  if(isset($detail['HUB_SAUDARA'])) echo $detail['HUB_SAUDARA'] ?>" class="form-control" placeholder="Kota" type="text" name="hubsaudara">
	        										</div>
	        				
	        										<div class="form-group">
	        											<label for="">Kecamatan :</label>
	        											<input value="<?php  if(isset($detail['TELP_SAUDARA'])) echo $detail['TELP_SAUDARA'] ?>" class="form-control" placeholder="Kecamatan" type="text" name="telpsaudara">
	        										</div>
	        						                                
	        									
	        										<div class="form-group">
	        											<label for="">Kelurahan :</label>
	        											<input value="<?php  if(isset($detail['TELP_SAUDARA'])) echo $detail['TELP_SAUDARA'] ?>" class="form-control" placeholder="kelurahan" type="text" name="telpsaudara">
	        										</div>
	        						                                <div class="form-group">
	        											<label for="">Luas Lahan :</label>
	        											<input value="<?php  if(isset($detail['TELP_SAUDARA'])) echo $detail['TELP_SAUDARA'] ?>" class="form-control" placeholder="Luas Lahan" type="text" name="telpsaudara">
	        										</div>
	        						                                <div class="form-group">
	        											<label for="">Nama Pemilik :</label>
	        											<input value="<?php  if(isset($detail['TELP_SAUDARA'])) echo $detail['TELP_SAUDARA'] ?>" class="form-control" placeholder="Nama Pemilik" type="text" name="telpsaudara">
	        										</div>
	        						                                 <div class="form-group">
	        											<label for="">Taksiran Harga :</label>
	        											<input value="<?php  if(isset($detail['TELP_SAUDARA'])) echo $detail['TELP_SAUDARA'] ?>" class="form-control" placeholder="Taksiran Harga" type="text" name="telpsaudara">
	        										</div>
	        						                                 <div class="form-group">
	        											<label for="">Jalur Masuk Motor / Mobil :</label>
	        											<input value="<?php  if(isset($detail['TELP_SAUDARA'])) echo $detail['TELP_SAUDARA'] ?>" class="form-control" placeholder="Jalur Masuk" type="text" name="telpsaudara">
	        										</div>
	        										<div class="form-group">
	        											<label for="">Foto Lokasi* :</label><br>
	        											<button class="mr-2 mb-2 btn btn-secondary btn-rounded" type="button" id="btnfotolok"> Pilih Foto Lokasi</button>
	        											<div id="blokfotolok">&nbsp;</div>
	        											<img src="<?php  echo base_url();?>img/loading.gif" id="loadingfotolok" style="display:none;">
	        				
	        											<?php  if(isset($detail['FILE_LOK'])) {?>
	        												<img src="<?php  echo base_url();?>uploads/identitas/<?php  echo $detail['FILE_LOK']?>" width="50" id="lamafotolok">
	        											<?php  } ?>
	        				
	        											<input class="form-control" type="file" name="bfotolok" id="bfotolok" style="display:none;" data-url="<?php  echo base_url();?>uploads/identitas/">
	        											<input class="form-control" type="text" name="filefotolok" id="filefotolok" style="display:none;" data-error="Harap upload foto lok" value="<?php  if(isset($detail['FILE_LOK'])) echo $detail['FILE_LOK'] ?>">
	        				
	        											<div class="help-block form-text with-errors form-control-feedback"></div>
	        											</div>
	        									</div>	 -->		

					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<button class="btn btn-danger" type="button" data-url-redirect="<?php  echo base_url().'anggota?active='.$this->input->get('rdr')?>" data-target="#confirmModal" data-toggle="modal" data-text-confirm="Anda akan meninggalkan editor dan data yang telah dimasukkan akan hilang.<br/><br/>Apakah anda yakin?"> Batal</button>
								<button class="btn btn-primary" type="submit"> Simpan</button>
							</div>
						</div>
					</div>
	        	</div>
	        	</div>
	        </div>
	    </div>
	</div>
</form>
<script>
	var base_url = '<?php  echo base_url(); ?>';
	var req_rdr  = '<?php  echo $this->input->get('rdr'); ?>';
	action       = '';
	var t_kota   = <?php  echo json_encode($kota->result()); ?>;
	
	
	function get_kota(value) {
		$(document).ready(function() {
			var html	=	'<option value="">Pilih Kota/Kabupaten</option>';
			for(var i=0; i<t_kota.length; i++){
				if(t_kota[i].p == value) {
					html	+=	'<option value="'+ t_kota[i].id +'">'+ t_kota[i].n +'</option>';
				}
			}
			$("#idkota").html(html).show();
		});
	}
	
	function get_kecamatan(value) {
		$(document).ready(function() {
			var html	=	'<option value="">Pilih Kecamatan</option>';
			var kc		=	_send_request_ajax(base_url + "anggota/anggota/kecamatan", "id="+value);
			var obj		=	JSON.parse(kc);
			for(var i=0; i<obj.length; i++){
				html	+=	'<option value="'+ obj[i].id +'">'+ obj[i].n +'</option>';
			}
			$("#kecamatan").html(html).show();
		});
	}
	
	function get_kelurahan(value) {
		$(document).ready(function() {
			var html	=	'<option value="">Pilih Kelurahan/Desa</option>';
			var kl		=	_send_request_ajax(base_url + "anggota/anggota/kelurahan", "id="+value);
			var obj		=	JSON.parse(kl);
			for(var i=0; i<obj.length; i++){
				html	+=	'<option value="'+ obj[i].id +'">'+ obj[i].n +'</option>';
			}
			$("#kelurahan").html(html).show();
		});
	}
	
	$("#cidentitas").off("change").on("change", function(){
		if($(this).val() == "ktp") {
			$("#xidentitas").attr("minlength", "16");
			$("#xidentitas").attr("maxlength", "16");
			$("#xidentitas").attr("data-error", "Harap masukkan nomor KTP dengan benar");
		} else if($(this).val() == "sim") {
			$("#xidentitas").attr("minlength", "12");
			$("#xidentitas").attr("maxlength", "12");
			$("#xidentitas").attr("data-error", "Harap masukkan nomor SIM dengan benar");
		}
	});
	
	$("#gunakanalamatktp").off("click").on("click", function(){
		if($(this).prop('checked') == true) {
			$("#alamatdom").val($("#alamatktp").val());
		} else {
			$("#alamatdom").val('');
			$("#alamatdom").focus();
		}
	});
	
	var _send_request_ajax	=	function(url, datapost){
		var getRespon = $.ajax({
			type: 'POST',       
			url: url,
			data: datapost,
			dataType: 'html',
			context: document.body,
			global: false,
			async:false,
			success: function(data) {
				return data;
			}
		}).responseText;
		
		return getRespon;
	}
	
</script>
<script src="<?php  echo base_url(); ?>assets/js/Master/anggota.js"></script>