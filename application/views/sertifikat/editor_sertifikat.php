<h6 class="element-header">
    <?php  echo $PAGE_TITLE?>
</h6>
<form id="formDataSertifkat" action="<?php  echo $action;?>" method="post" class="col-lg-12 formValidate" target="_self">
	<?php  
		if($this->uri->segment(1)=="edit-sertifikat"){;
	?>
	<input value="<?php  if(isset($detail['IDSERTIFIKAT'])) echo $detail['IDSERTIFIKAT'] ?>" class="form-control" type="hidden" name="idsertifikat" >
		<?php  } ?>
	<div class="row">
		<div class="col-lg-6">
			<div class="element-box">
				<h5 class="form-header">
					Data Pribadi
				</h5>
				<div class="form-desc">
					Informasi Data Pribadi
				</div>
				
				<div class="form-group">
					<label for="">Nomor Sertifikat* :</label>
					<input value="<?php  if(isset($detail['NOSE'])) echo $detail['NOSE'] ?>" class="form-control" placeholder="Nama Lengkap" type="text" name="nose" data-error="Harap masukkan nama lengkap" required="required">
					<div class="help-block form-text with-errors form-control-feedback"></div>
				</div>


				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="">Nama Pemilik : </label>
							<input value="<?php  if(isset($detail['NAMA'])) echo $detail['NAMA'] ?>" class="form-control" placeholder="Tempat Lahir" type="text" name="nama">
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
							<label for="">Luas : </label>
							<input value="<?php  if(isset($detail['LUAS'])) echo $detail['LUAS'] ?>" class="form-control" placeholder="No Telp" type="text" name="luas" id="luas">
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label for="">Taksir* :</label>
                            <input value="<?php  if(isset($detail['TAKSIR'])) echo $detail['TAKSIR'] ?>" class="form-control" placeholder="Ibu Kandung" type="text" name="taksir" data-error="Harap masukkan nama ibu kandung" required="required">
                            <div class="help-block form-text with-errors form-control-feedback"></div>
						</div>
					</div>
				 <div class="row">
					
					<div class="col-sm-6">
						<div class="form-group">
							<label for="">Jalur* :</label>
                            <input value="<?php  if(isset($detail['JALUR'])) echo $detail['JALUR'] ?>" class="form-control" placeholder="Jalur Masuk / Mobil" type="text" name="jalur" data-error="Pilih Mobil / Motor" required="required">
                            <div class="help-block form-text with-errors form-control-feedback"></div>
						</div>
					</div>
				
				

				<div class="form-group">
					<label for="">Foto Lokasi* :</label><br>
					<button class="mr-2 mb-2 btn btn-secondary btn-rounded" type="button" id="btnfotowajah"> Pilih Foto Lokasi</button>
					<div id="blokfotowajah">&nbsp;</div>
					<img src="<?php  echo base_url();?>img/loading.gif" id="loadingfotowajah" style="display:none;">

					<?php  if(isset($detail['FILE_PIC'])) {?>
						<img src="<?php  echo base_url();?>uploads/identitas/<?php  echo $detail['FILE_PIC']?>" width="50" id="lamafotowajah">
					<?php  } ?>

					<input class="form-control" type="file" name="bfotowajah" id="bfotowajah" style="display:none;" data-url="<?php  echo base_url();?>uploads/identitas/">
					<input class="form-control" type="text" name="filefotowajah" id="filefotowajah" data-error="Harap upload foto lokasi" required="required" style="display:none;" value="<?php  if(isset($detail['FILE_PIC'])) echo $detail['FILE_PIC'] ?>">
					<div class="help-block form-text with-errors form-control-feedback"></div>
					
				</div>


				<div class="form-buttons-w">
					<button class="btn btn-danger" type="button" data-url-redirect="<?php  echo base_url().'sertifikat?'.$this->input->get('rdr')?>" data-target="#confirmModal" data-toggle="modal" data-text-confirm="Anda akan meninggalkan editor dan data yang telah dimasukkan akan hilang.<br/><br/>Apakah anda yakin?"> Batal</button>
					<button class="btn btn-primary" type="submit"> Simpan</button>
				</div>
			</div>
		</div>
	</div>
</form>
<script>
	var base_url	=	'<?php  echo base_url();?>';
	var req_rdr		=	'<?php  echo $this->input->get('rdr')?>';
	action			=	'';
	var t_kota		=	<?php  echo json_encode($kota->result()); ?>;
	
	
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
<script type="text/javascript" src="<?php  echo base_url();?>assets/js/Master/sertifikat.js?v=1.1"></script>