<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <div class="row">
                <div class="col-sm-9 col-lg-9">
                    <h4 class="color-primary"><?php  echo $PAGE_TITLE?></h4>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="formDataAnggota" action="<?php  echo $action;?>" method="post" class="col-lg-12 formValidate" target="_self">
	<?php  
		if($this->uri->segment(1)=="edit-anggota"){;
	?>
	<input value="<?php  if(isset($detail['IDANGGOTA'])) echo $detail['IDANGGOTA'] ?>" class="form-control" type="hidden" name="idanggota" >
		<?php  } ?>
	<div class="row">
		<div class="col-lg-12">
			<div class="element-box">
				<h5 class="form-header">
					Tambah Jurnal Umum
				</h5>
				
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="">Kode Jurnal : </label>
							<select class="form-control" name="kode_jurnal" id="kode_jurnal">
								<option value='JU'>Jurnal Umum</option>
								<option value='CE'>Closing Entry</option>
								<option value='JK'>Jurnal Koreksi</option>
								<option value='KK'>Kas Keluar</option>
								<option value='KM'>Kas Masuk</option>
								<option value='JE'>Jurnal Eliminasi</option>
							</select>
						</div>
					</div>

					<div class="col-sm-6">
						<div class="form-group">
							<label for="">Kantor/Cabang: </label>
                            <select class="form-control" name="kantor" id="kantor">
							<?php 
								$cabs = $this->dbasemodel->loadsql("SELECT NAMA,KODE FROM m_cabang WHERE KODE = '". $this->session->userdata('wad_cabang') ."' ");
								if($this->session->userdata('wad_level') == "admin"){ 
									$cabs = $this->dbasemodel->loadsql("SELECT NAMA, KODE FROM m_cabang ORDER BY NAMA ASC");
								}
								foreach($cabs->result() as $cab){
									echo '<option value="'. $cab->KODE .'">'. $cab->NAMA .'</option>';
								}
							?>
                            </select>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="">Referensi : </label>
							<input value="<?php  if(isset($detail['REFERENSI'])) echo $detail['REFERENSI'] ?>" class="form-control" type="text" name="referensi" id="referensi">
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label for="">Tanggal : </label>
							<div class="date-input">
								<input value="<?php  if(isset($detail['TANGGAL'])) echo $detail['TANGGAL'] ?>" class="single-daterange form-control" type="text" name="tanggal" id="tanggal">
							</div>
						</div>
					</div>
				</div>
                
                <div class="form-group">
                    <label for="">Keterangan : </label>
                    <input class="form-control" name="ket" id="ket" value="<?php  if(isset($detail['KETERANGAN'])) echo $detail['KETERANGAN'] ?>" type="text">
                </div>
                <div class="row">
					<div class="col-sm-12">
                    	<table class="table">
                        	<thead>
                            	<tr>
                                	<td width="50px"><button class="btn btn-success" type="button" data-target="#mymodals" data-toggle="modal"><i class="fa fa-plus"></i></button></td>
                                    <td>Kode Perkiraan</td>
                                    <td>Nama Perkiraan</td>
                                    <td width="140px">Debet</td>
                                    <td width="140px">Kredit</td>
                                    <td width="180px">Keterangan</td>
                                    <td width="10px">&nbsp;</td>
                                </td>
                            </thead>
                            <tbody id="showdataakun">
                            	<tr><td colspan="6" class="text-center">Tidak ada data</td></tr>
                            </tbody>
                            <thead id="showtotal" style="display:none">
                            	<tr>
                                	<td colspan="3" class="text-right">Jumlah</td>
                                    <td><span id="total_debet">0</span></td>
                                    <td><span id="total_kredit">0</span></td>
                                    <td></td>
                                </td>
                            </thead>
                        </table>
                    </div>
                </div>

				<div class="form-buttons-w">
					<button class="btn btn-danger" type="button" data-url-redirect="<?php  echo base_url();?>akuntansi/jurnal_umum" data-target="#confirmModal" data-toggle="modal" data-text-confirm="Anda akan meninggalkan editor dan data yang telah dimasukkan akan hilang.<br/><br/>Apakah anda yakin?"> Batal</button>
					<button class="btn btn-primary" type="button" onclick="save_data()"> Simpan</button>
				</div>
			</div>
		</div>
	</div>
</form>

<div aria-hidden="true" aria-labelledby="mymodals" class="modal" id="mymodals" role="dialog" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Kode Perkiraan</h5>
                <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> &times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for=""> Kode Perkiraan</label>
                            <input class="form-control" type="text" id="kode_akun" placeholder="Ketik Kode/Nama Perkiraan">
                            <input type="hidden" id="idakun" name="idakun">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                        	<label for=""> Nama Perkiraan</label>
                            <input class="form-control" placeholder="Nama Perkiraan" type="text" name="nama_akun" id="nama_akun">
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" type="button"> Close</button>
                <button class="btn btn-primary" type="button" id="tambahkan_akun"> Tambahkan</button>
            </div>
        </div>
    </div>
</div>
<link href="<?php  echo base_url();?>bower_components/autocomplete/tautocomplete.css" rel="stylesheet">
<style>._debet, ._kredit, ._keterangan{padding:2px 4px !important;}</style>
<script src="<?php  echo base_url();?>bower_components/autocomplete/tautocomplete.js"></script>
<script>
	var base_url	=	'<?php  echo base_url();?>';
	action			=	'';
	var	item_jurnal	=	[];
	var total_debet =	0;
	var	total_kredit = 	0;
	$('#tambahkan_akun').off("click").on("click", (function(e) {
		var idakun		=	$('#idakun').val();
		var kode_akun	=	$('#kode_akun').val();
		var nama_akun	=	$('#nama_akun').val();
		
		
		if(nama_akun == '' || kode_akun == '') {
			$('#kode_akun').focus(); alert('Kode perkiraan masih kosong'); return false;
		}
		
		var	item_arr	=	[idakun, kode_akun, nama_akun, 0, 0, '']; /* 3=Debet, 4=Kredit, 5=Keterangan*/
		if(item_jurnal.length > 0) {
			for(var i=0; i < item_jurnal.length; i++) {
				if(item_jurnal[i][0] == idakun) {
					$('#idakun').val('');
					$('#kode_akun').val('');
					$('#nama_akun').val('');
					$('#mymodals').modal('hide');
					return false;
				}
			}
		}
		
		item_jurnal.push(item_arr);
		parse_jurnal(item_jurnal);
		
		$('#idakun').val('');
		$('#kode_akun').val('');
		$('#kode_akun').focus();
		$('#nama_akun').val('');
		$('#mymodals').modal('hide');
	}));
	
	function akundelete(i) {
		item_jurnal.splice(i, 1);
		parse_jurnal(item_jurnal);
	}
	
	function parse_jurnal(objek) {
		var html	=	'';
		if(objek.length > 0) {
			for(var i =0; i < objek.length; i++) { var no = i+1;
				html	+=	'<tr>';
				html	+=	'<td>' + no + '</td>';
				html	+=	'<td>' + objek[i][1] + '</td>';
				html	+=	'<td>' + objek[i][2] + '</td>';
				html	+=	'<td><input type="text" value="' + objek[i][3] + '" class="form-control _debet _debet'+ no +'" onkeyup="sum_total(this.value, \'debet\', \''+ no +'\', '+ i +')" name="debet[]"></td>';
				html	+=	'<td><input type="text" value="' + objek[i][4] + '" class="form-control _kredit _kredit'+ no +'" onkeyup="sum_total(this.value, \'kredit\', \''+ no +'\', '+ i +')" name="kredit[]"></td>';
				html	+=	'<td><input type="text" value="' + objek[i][5] + '" class="form-control _keterangan" name="keterangan[]"></td>';
				html	+=	'<td align="center">';
				html	+=	'<a href="javascript:void(0);" onclick="akundelete('+ i +')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>';
				html	+=	'</td>';
				html	+=	'</tr>';
			}
			
			$('#showtotal').show();
			set_total();
		} else { html += '<tr><td colspan="6" align="center">Tidak ada data</td></tr>'; $('#showtotal').hide();}
		$('#showdataakun').html(html);
	}
	
	function sum_total(value, tipe, id, i) {
		value	=	eval(value.replace(/\,/g, ''));
		
		if(tipe == 'debet' && !isNaN(value)) {
			$('._debet' + id).val(rupiah(value));
			if(value > 0) {
				$('._kredit' + id).val(0);
				item_jurnal[i][4] = 0;
			}
			item_jurnal[i][3] = value;
			set_total();
			
		} else if(tipe == 'kredit' && !isNaN(value)) {
			if(value > 0) {
				$('._debet' + id).val(0);
				item_jurnal[i][3] = 0;
			}
			$('._kredit' + id).val(rupiah(value));
			item_jurnal[i][4] = value;
			set_total();
		}
		
	}
	
	function set_total(){
		var totald = totalk = 0;
		for(var i =0; i < item_jurnal.length; i++) { var no = i+1;
			totald	+=	item_jurnal[i][3];
			totalk	+=	item_jurnal[i][4];
		}
		total_debet		=	totald;
		total_kredit	=	totalk;
		$('#total_debet').html(rupiah(total_debet));
		$('#total_kredit').html(rupiah(total_kredit));
	}
	
	function save_data(){
		if(total_debet != total_kredit) { alert('Nominal debet dan kredit tidak seimbang'); return false; }
		
		var ischeck		=	true;
		if(ischeck == true) {
			$.ajax({
				url: base_url + 'akuntansi/jurnal_umum/save',
				type: "POST",
				dataType: "json",
				data: JSON.stringify({'kode_jurnal' : $('#kode_jurnal').val(), 
									  'kantor' : $('#kantor').val(), 
									  'tanggal' : $('#tanggal').val(), 
									  'keterangan' : $('#ket').val(), 
									  'referensi' : $('#referensi').val(),
									  'total_debet' : total_debet,
									  'total_kredit' : total_kredit,
									  'data_jurnal' : item_jurnal}),
				processData: true,
				contentType: "application/json",
				success: function( result ){
					if(result == true) {
						$('#informationModalText').html("Jurnal berhasil disimpan");
                   		$('#informationModal').modal('show');
						window.location.href = base_url + "akuntansi/jurnal_umum";
					} 
				}, error: function( jqXhr, textStatus, errorThrown ){
					console.log('Error: ' + errorThrown + jqXhr); return false;
				}
			});
		}
	}
	
	$().ready(function () {
		var kodeakun = jQuery11("#kode_akun").tautocomplete({
			width: "500px",
			columns: ['id', 'Kode', 'Perkiraan'],
			hide: [],
			ajax: {
				url: base_url + "akuntansi/jurnal_umum/get_kodeakun",
				type: "GET",
				data: function(){var x = { param: kodeakun.searchdata()}; return x;},
				success: function (data) {
					var filterData = [];
	
					var searchData = eval("/" + kodeakun.searchdata() + "/gi");
	
					$.each(data, function (i, v) {
						if ((v.text.search(new RegExp(searchData)) != -1) || (v.perkiraan.search(new RegExp(searchData)) != -1)) {
							filterData.push(v);
						}
					});
					return filterData;
				}
			},
			onchange: function () {
				var selection = kodeakun.all();
				$("#idakun").val(kodeakun.id());
				$("#kode_akun").val(kodeakun.text());
				$("#nama_akun").val(selection['Perkiraan']);
			}
		});
		
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