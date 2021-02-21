<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <div class="row">
                <div class="col-sm-9 col-lg-10">
                    <h4 class="color-primary"><?php echo $PAGE_TITLE?></h4>
                </div>
                <div class="col-sm-3  col-lg-2 ">
                    <a href="<?php echo base_url();?>pinjaman-data" class="btn btn-primary btn-block" >
                        <i class="fa fa-angle-double-left"></i> 
                        Data Pinjaman
                    </a>
                </div>
            </div>
        </div>
    
        <div class="panel-content">
            <?php
		    	echo notifikasi($this->session->flashdata('ses_trx_pinj'));
				if(isset($data_source)) {
					$row	=	$data_source->row();
				}
			?>
            <form id="formPinjaman" action="pinjaman/pinjaman/save" method="post" class="formValidate">
                <div class="form-group">
                    <label for="">Tanggal Transaksi :</label>
                    <input class="single-daterange form-control" placeholder="Tanggal Transaksi" type="text" name="tgl_pinj">
                </div>
                <div class="form-group">
                    <label for="">Nama Anggota : &nbsp; </label>
                    <input class="form-control" type="text" id="id_anggota" data-error="Harap masukkan nama anggota">
                    <input type="hidden" id="idanggota" name="id_anggota">
                    <input type="hidden" id="namaanggota" name="nama_penyetor">
                    <input type="hidden" id="alamatanggota" name="alamat">
                    <input type="hidden" id="identitas" name="no_identitas">
                </div>
                
                <div class="row">
                	<div class="col-sm-6">
						<div class="form-group">
							<label for="">Jenis Pembiayaan:</label>
                            <select class="form-control" name="idjenis_pinjam" onchange="jenis_pinjaman(this.value)"  data-error="Harap memilih jenis pembiayaan" required>
                                <option value="" disabled selected>Pilih salah satu</option>
                            <?php
                                if($jenis_pembiayaan->num_rows() > 0) {
                                    foreach($jenis_pembiayaan->result() as $res) {
                                        echo '<option value="'. $res->IDAKUN.'">'. $res->JNS_PINJ .'</option>';
                                    }
                                }
                            ?>
                            </select>
						</div>
					</div>
                    
                    <div class="col-sm-6">
						<div class="form-group">
							<label for="">Nilai Pembiayaan :</label>
                    		<input class="form-control" placeholder="100000" type="text" name="nilai" value="0" id="nilai" data-error="Harap masukkan nilai pembiayaan" required>
						</div>
					</div>
                </div>
                
                 <div class="row">
                	<div class="col-sm-6">
						<div class="form-group">
							<label for="">Saldo Tabungan :</label>
                    		<input class="form-control" placeholder="Saldo Tabungan" type="text" value="" id="saldo_tabungan" name="saldo_tabungan" readonly="readonly">
						</div>
					</div>
                    
                    <div class="col-sm-6">
						<div class="form-group">
							<label for="">Topup Dari Saldo :</label>
                            <select class="form-control" name="topup" id="topup">
                                <option value="0">Tidak</option>
                                <option id="nilai_topup" value="0">0</option>
                            </select>
						</div>
					</div>
                </div>
                
                
                 <div class="row">
                	<div class="col-sm-6">
						<div class="form-group">
							<label for="">Kebijakan Ka.Cab :</label>
                            <select class="form-control" name="kacab" id="kacab">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
						</div>
					</div>
                    
                    <div class="col-sm-6">
						<div class="form-group">
							<label for="">Nominal Kebijakan :</label>
                    		<input class="form-control" type="text" value="0" name="nominal_kacab" id="nominal_kacab">
						</div>
					</div>
                </div>
                
                
                 <div class="row">
                	<div class="col-sm-6">
						<div class="form-group">
							<label for="">Total Pembiayaan :</label>
                    		<input class="form-control" type="text" value="0" name="jumlah" id="jumlah" readonly>
						</div>
					</div>
                    
                    <div class="col-sm-6">
						<div class="form-group">
							<label for="">Lama Angsuran :</label>
                            <select class="form-control" name="lama_angsuran" id="lama_angsuran">
                            <option value="0" disabled="disabled" selected="selected"> -- Pilih Angsuran --</option>
                            <?php
                                if($jenis_ags->num_rows() > 0) {
                                    foreach($jenis_ags->result() as $res) {
                                        echo '<option value="'. $res->KETERANGAN .'">'. $res->KETERANGAN .' bulan</option>';
                                    }
                                }
                            ?>
                            </select>
						</div>
					</div>
                </div>
                
                <div class="row">
                	<div class="col-sm-6">
						<div class="form-group">
                        	<label for="">Bagi Hasil(Rp) :</label>
							<input class="form-control" type="text" value="0" name="bunga_rp_txt" id="bunga_rp_txt">
						</div>
					</div>

					<div class="col-sm-6">
						<div class="form-group">
                        	<label for="">Bagi Hasil(%) :</label>
							<input class="form-control" type="text" value="0" name="bunga_persen_txt" id="bunga_persen_txt">
						</div>
					</div>
                </div>
                
                <div class="row">
                	<div class="col-sm-6">
						<div class="form-group">
							<label for="">Biaya Admin :</label>
                            <input type="hidden" name="biaya_adm" id="biaya_adm" value="0"/>
                    		<input class="form-control" type="text" name="biaya_adm_txt" id="biaya_adm_txt" value="0"/>
						</div>
					</div>
                    
                    <div class="col-sm-6">
						<div class="form-group">
                        	<label for="">Biaya Asuransi :</label>
                            <input type="hidden" name="biaya_asuransi" id="biaya_asuransi" value="0"/>
							<input class="form-control" type="text" name="biaya_asuransi_txt" id="biaya_asuransi_txt" value="0"/>
						</div>
					</div>
                </div>
                
                <div class="row">
                	<div class="col-sm-5">
						<div class="form-group">
							<label for="">Ambil Dari Kas :</label>
                            <select class="form-control" name="id_kas">
                            <?php
                                if($jenis_kas->num_rows() > 0) {
                                    foreach($jenis_kas->result() as $res) {
                                        echo '<option value="'. $res->IDAKUN .'">'. $res->NAMA_KAS .'</option>';
                                    }
                                }
                            ?>
                            </select>
						</div>
					</div>
                    
                    <div class="col-sm-7">
						<div class="form-group">
                        	<label for="">Keterangan Pembiayaan :</label>
							<input class="form-control" type="text" name="ket" id="ket">
						</div>
					</div>
                </div>
                
                <div class="row">
                	<div class="col-sm-5">
						<div class="form-group">
							<label for="">Nomor Jaminan :</label>
							<input class="form-control" type="text" name="no_jaminan" id="no_jaminan">
						</div>
					</div>
                    
                    <div class="col-sm-7">
						<div class="form-group">
                        	<label for="">Jenis Jaminan :</label>
														
							<select class="form-control" name="jenis_jaminan" id="jenis_jaminan">
                            <option value="0" disabled="disabled" selected="selected"> -- Pilih jenis_jaminan --</option>
						   <?php
							 if($jaminan->num_rows() > 0) {
						 	foreach($jaminan->result() as $rjam){
								 echo '<option value="'.$rjam->IDJAMINAN.'">'.$rjam->NAMAJAMINAN.'</option>';
							 }  
							}
						   ?>
                            </select>
							
							
						</div>
					</div>
                </div>
                
                <div class="row">
                	<div class="col-sm-5">
						<div class="form-group">
							<label for="">Nama Saudara : (2)</label>
							<input class="form-control" type="text" name="nama_saudara" id="nama_saudara" required>
						</div>
					</div>
                    
                    <div class="col-sm-7">
						<div class="form-group">
                        	<label for="">Hubungan Saudara : (2)</label>
							<input class="form-control" type="text" name="hubungan_saudara" id="hubungan_saudara" required>
						</div>
					</div>
                </div>
                
                <div class="row">
                	<div class="col-sm-5">
						<div class="form-group">
							<label for="">No Telp. Saudara : (2)</label>
							<input class="form-control" type="text" name="telp_saudara" id="telp_saudara" required>
						</div>
					</div>
                    
                    <div class="col-sm-7">
						<div class="form-group">
                        	<label for="">Alamat Saudara : (2)</label>
							<input class="form-control" type="text" name="alamat_saudara" id="alamat_saudara" required>
						</div>
					</div>
                </div>
                
                
                <div class="row">
                	<div class="col-sm-5">
                		<br>
                    	<button class="btn btn-primary" type="submit" id="submitpinjaman"> Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<link href="<?php echo base_url();?>bower_components/autocomplete/tautocomplete.css" rel="stylesheet">
<script src="<?php echo base_url();?>bower_components/autocomplete/tautocomplete.js"></script>
<script> 
	var base_url = '<?php echo base_url();?>'; 
	var action	=	'input';
	var max_pinjaman	=	null;
	var min_asuransi	=	0;
	function jenis_pinjaman(val){
		if($('#idanggota').val() == "") { $('.acontainer input').focus(); $('#informationModalText').html("Harap mengisi nama anggota"); $('#informationModal').modal('show'); return false; }
		$().ready(function () {
			$.ajax({
				type: "POST",
				url: base_url + 'pinjaman/pinjaman/get_jenispinjam',
				data: 'data=' + val + '&idanggota=' + $('#idanggota').val(),
				cache: false,
					success: function(msg){
						var data = JSON.parse(msg);
						$('#biaya_adm').val(data.BIAYAADMIN);
						$('#biaya_adm_txt').val(rupiah(data.BIAYAADMIN));
						$('#nilai').val(rupiah(data.REKOM_PINJ));
						$('#jumlah').val(rupiah(data.REKOM_PINJ));
						$('#bunga').val(rupiah(data.BAGIHASIL));
						$('#bunga_txt').val(rupiah(data.BAGIHASIL));
						$('#biaya_asuransi').val((data.ASURANSI));
						$('#biaya_asuransi_txt').val(rupiah(data.ASURANSI));
						_biaya_adm_min 	= data.BIAYAADMIN;
        				_biaya_adm 		= data.BIAYAADMIN;
						_pinj_adm_min 	= data.BIAYAADMIN;
						min_asuransi	= data.ASURANSI;
						if(data.jml_pinjam < 1) { 
							max_pinjaman = data.REKOM_PINJ;
							$('#lama_angsuran option[value="3"]').prop("selected", true);
						} else { 
							max_pinjaman = null;
							$('#lama_angsuran option[value="1"]').prop("selected", true);
						}
					}, error: function () {}
			});
		});
	}
	
	$().ready(function () {
	var anggota = jQuery11("#id_anggota").tautocomplete({
		width: "600px",
		columns: ['id', 'Nama', 'Alamat', 'Identitas'],
		hide: ['id'],
		ajax: {
			url: base_url + "general/get_anggota?aktif=y",
			type: "GET",
			data: function(){var x = { para1: anggota.searchdata()}; return x;},
			success: function (data) {
				var filterData = [];

				var searchData = eval("/" + anggota.searchdata() + "/gi");

				$.each(data, function (i, v) {
					if (v.text.search(new RegExp(searchData)) != -1) {
						filterData.push(v);
					}
				});
				return filterData;
			}
		},
		onchange: function () {
			var selection = anggota.all();
			$("#id_anggota").val(anggota.text());
			$("#idanggota").val(selection['id']);
			$("#idanggota").trigger("change");
			$("#alamatanggota").val(selection['Alamat']);
			$("#namaanggota").val(selection['Nama']);
			$("#identitas").val(selection['Identitas']);
		}
	});
	
	

	$("#idanggota").off("change").on("change", function(){
		$('#submitpinjaman').show();
		var _saldo_min_pinj	=	<?=str_replace(',','', sukubunga('saldo_min_pinj'))?>;
		
		var _saldo	=	0;
		$.ajax({
			type: "POST",
			url: base_url + "general/get_total_saldo",
			data: 'idanggota=' + $("#idanggota").val(),
			cache: false,
				success: function(msg){
					_saldo	=	(msg == "" || msg == null) ? 0 : msg
					$("#saldo_tabungan").val(rupiah(_saldo));
					$('#submitpinjaman').show();
					if (_saldo < _saldo_min_pinj) {
						$('#idanggota').val('');
						$('#id_anggota').val('');
						$('.acontainer input').val('');
						_msg = 'Saldo tabungan kurang dari ' + rupiah(_saldo_min_pinj) + '';
						$('#informationModalText').html(_msg);
						$('#informationModal').modal('show');
						$('#submitpinjaman').hide();
						return false;
					}
					
					$.ajax({
						type: "POST",
						url: base_url + 'pinjaman/pinjaman/get_pinjaman',
						data: 'data=' + $("#idanggota").val(),
						cache: false,
						success: function(msgpj){
							if(msgpj >= 1) {
								_msg = 'Nasabah ini masih ada pinjaman yang belum dilunasi!';
								$('#informationModalText').html(_msg);
								$('#informationModal').modal('show');
								$('#idanggota').val('');
								$('#id_anggota').val('');
								$('.acontainer input').val('');
								$('#submitpinjaman').hide();
								return false;
							} else {
								get_datasaudara($("#idanggota").val());
							}
						}, error: function () {}
					});
					
					if(_saldo != 0) {
						var _persen = <?=str_replace(',','', sukubunga('rekomendasi_pinj_per')) ?>;
						var _nilai_topup = eval(_saldo) * _persen / 100;
						
						if(max_pinjaman != null) {
							$('#nilai_topup').val(_nilai_topup);
							$('#nilai_topup').text(rupiah(_nilai_topup));
							$('#nilai_topup').show();
						} else {
							$('#nilai_topup').val(0);
							$('#nilai_topup').text(0);
							$('#nilai_topup').hide();
						}
					}
					$('#nilai_kacab').val(<?=str_replace(',','', sukubunga('rekomendasi_pinj_kc')) ?>);
					$('#nilai_kacab').text(rupiah(<?=str_replace(',','', sukubunga('rekomendasi_pinj_kc')) ?>));
					
				}, error: function (result) {
					var teks = result['status'] + " - " + result['statusText'];
					$('#informationModalText').html(teks);
					$('#informationModal').modal('show');
				}
		});
	});
	
	function get_datasaudara(idagt) {
		$.ajax({
			type: "POST",
			url: base_url + 'pinjaman/pinjaman/get_datasaudara',
			data: 'data=' + idagt,
			cache: false,
				success: function(msg){
					var data = JSON.parse(msg);
					$('#nama_saudara').val(data.NAMA_SAUDARA);
					$('#hubungan_saudara').val((data.HUB_SAUDARA));
					$('#telp_saudara').val(data.TELP_SAUDARA);
					$('#alamat_saudara').val(data.ALMT_SAUDARA);
				}, error: function () {}
		});
	}
	
	function check_pinjaman(idagt){
		$.ajax({
			type: "POST",
			url: base_url + 'pinjaman/pinjaman/get_pinjaman',
			data: 'data=' + idagt,
			cache: false,
				success: function(msg){
					if(msg >= 1) {
						_msg = 'Nasabah ini masih ada pinjaman yang belum dilunasi!';
						$('#informationModalText').html(_msg);
						$('#informationModal').modal('show');
						return false;
					}
				}, error: function () {}
		});
	}
	
	$('#nominal_kacab').keyup(function(){
        _kacab = eval($('#kacab').val());
        _nominal_kacab = eval($(this).val().replace(/\,/g, ''));
		//console.log(_nominal_kacab);
        if (_kacab == 0) {
            $(this).val(0);
			var total = parseInt(_kacab) + parseInt($("#jumlah").val());
			$("#jumlah").val(rupiah(total));
        } else {
            $(this).val(rupiah(_nominal_kacab));
			var total = parseInt($('#nominal_kacab').val()) + parseInt($("#jumlah").val());
			$("#jumlah").val(rupiah(total));
        }
		}).focus(function(){
			$(this).select();
    });
	
	/* $('#jumlah').keyup(function(){
        /*_jumlah = eval($('#jumlah').val().replace(/\,/g, ''));
        _bunga =  eval($('#bunga_txt').val().replace(/\,/g, ''));

        _bunga_2 = _bunga * _jumlah / 100;
        _bunga_1 = (Math.round(_bunga_2 / 100) * 100);
        $('#bunga_rp_txt').val((_bunga_1));*/
		//proses_total();
    //});
	
	$('#jumlah').keyup(function(){
		var val_jumlah = $(this).val();
		
		_jml = eval(val_jumlah.replace(/\,/g,''));
		/*if (_jml > _pinj_biaya_adm_min) {
			_biaya = _jml * _biaya_adm / 100;
			//$('#biaya_adm').val(_biaya);
			//$('#biaya_adm_txt').val((_biaya));
		} else {
			_biaya = _biaya_adm_min;
			//$('#biaya_adm').val(_biaya);
			//$('#biaya_adm_txt').val((_biaya));			
		}*/
		$('#jumlah').val(rupiah(val_jumlah));
		proses_total();
	});
	
	$('#jumlah').change(function(){
		var val_jumlah = $(this).val();
		_jml = eval(val_jumlah.replace(/\,/g,''));
		/*if (_jml > _pinj_biaya_adm_min) {
			_biaya = _jml * _biaya_adm / 100;
			//$('#biaya_adm').val(_biaya);
			//$('#biaya_adm_txt').val((_biaya));
		} else {
			_biaya = _biaya_adm_min;
			//$('#biaya_adm').val(_biaya);
			//$('#biaya_adm_txt').val((_biaya));			
		}*/
		$('#jumlah').val(rupiah(val_jumlah));
		proses_total();
	});
	
	$('#topup').change(function(){
	    proses_total();
	});
	
	$('#kacab').change(function(){
	    proses_total();
	});
	$('#nilai').keyup(function(){
		//var nilai 	= eval($(this).val());
		var nilai	= eval($(this).val().replace(/\,|\./g,''));
		// if(max_pinjaman != null) {
		// 	if(eval(nilai) > eval(max_pinjaman)) {
		// 		$('#informationModalText').html("Maksimal pinjaman " + rupiah(max_pinjaman));
		// 		$('#informationModal').modal('show');
		// 		$('#nilai').val(rupiah(max_pinjaman)); 
		// 		return false;
		// 	}
		// }
		$(this).val(rupiah(nilai));
	    proses_total();
	}).focus(function(){ $(this).select() });
	
	$('#nominal_kacab').keyup(function(){
	    proses_total();
	});
	$('#biaya_adm_txt').keyup(function(){
		var _admin = $(this).val();
		$('#biaya_adm').val(rupiah(_admin));
		$(this).val(rupiah(_admin));
	})
	$('#biaya_asuransi_txt').keyup(function(){
		var _asuransi = $(this).val();
		$('#biaya_asuransi').val(rupiah(_asuransi));
		$(this).val(rupiah(_asuransi));
	});
	$('#bunga_txt').keyup(function(){
        _jumlah = eval($('#jumlah').val().replace(/\,/g, ''));
        _bunga = eval($('#bunga_txt').val().replace(/\,/g, ''));
        _bunga_2 = _bunga * _jumlah / 100;
        _bunga_1 = (Math.round(_bunga_2 / 100) * 100);
        $('#bunga_rp_txt').val(rupiah(_bunga_1));
	}).focus(function(){ $(this).select() });
	
	$('#bunga_persen_txt').keyup(function(){
        _jumlah = eval($('#jumlah').val().replace(/\,/g, ''));
        _bunga = eval($('#bunga_persen_txt').val().replace(/\,/g, ''));
        _bunga_2 = _bunga * _jumlah / 100;
        _bunga_1 = (Math.round(_bunga_2 / 100) * 100);
        $('#bunga_rp_txt').val(rupiah(_bunga_1));
    }).focus(function(){ $(this).select() });
	
    $('#bunga_rp_txt').keyup(function(){
        _bunga_1	=	eval($('#bunga_rp_txt').val().replace(/\,/g, ''));
		$(this).val(rupiah(_bunga_1));
        _jumlah = eval($('#jumlah').val().replace(/\,/g, ''));
        _bunga_rp = eval($('#bunga_rp_txt').val().replace(/\,/g, ''));
        _bunga_1 = _bunga_rp / _jumlah * 100;
        $('#bunga_persen_txt').val(_bunga_1);
        $('#bunga_txt').val(_bunga_1);
	}).focus(function(){ $(this).select() });
	
	function  proses_total() {
        _nilai = eval($('#nilai').val().replace(/\,|\./g,''));
        _topup = eval($('#topup').val());
        _nominal_kacab = eval($('#nominal_kacab').val().replace(/\,|\./g,''));
        _lama_angsuran = ($('#lama_angsuran').val()=='0') ? 1 : eval($('#lama_angsuran').val());
        _bunga = eval($('#bunga_persen_txt').val());
        
        _jumlah = _nilai + _topup + _nominal_kacab;
		_basil = _jumlah * _bunga / 100;
		_asuransi_rp	=	eval(min_asuransi) * Math.ceil(_jumlah / 1000000);
		
		$('#jumlah').val(rupiah(_jumlah));
        $('#bunga_rp_txt').val(rupiah(_basil));
		$('#biaya_asuransi').val(_asuransi_rp);
		$('#biaya_asuransi_txt').val(rupiah(_asuransi_rp));
    }
	

});


function rupiah(data){
	if(data == "") {return data;}
	return number_format(data);
}

function number_format (number, decimals, dec_point, thousands_sep) {
  number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
	prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
	sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
	dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
	s = '',
	toFixedFix = function (n, prec) {
	  var k = Math.pow(10, prec);
	  return '' + Math.round(n * k) / k;
	};
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
	s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
	s[1] = s[1] || '';
	s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}
</script>
<script src="<?php echo base_url();?>assets/js/pinjaman/pinjaman.js"></script>