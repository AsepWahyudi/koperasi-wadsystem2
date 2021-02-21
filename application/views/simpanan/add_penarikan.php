<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <div class="row">
                <div class="col-sm-9 col-lg-9">
                    <h4 class="color-primary">Tambah Penarikan Tunai</h4>
                </div>
                <div class="col-sm-3 col-lg-3">
                    <a href="<?php echo base_url()?>penarikan-tunai" class="btn btn-primary btn-block" >
                        <i class="fa fa-angle-double-left"></i> 
                        Data Penarikan Tunai
                    </a>
                </div>
            </div>
        </div>
        <div class="panel-content">
            <?php
	        	echo notifikasi($this->session->flashdata('ses_trx_simp'));
				if(isset($data_source)) {
					$row = $data_source->row();
				}
			?>
            <form id="formPenarikan" action="simpanan/penarikan/save" method="post" class="formValidate">
                <div class="form-group">
                    <label for="">Tanggal Transaksi :</label>
                    <input class="single-daterange form-control" placeholder="Tanggal Transaksi" type="text" name="tgl_trx">
                </div>
                <div class="form-group">
                    <label for="">Nama Anggota : &nbsp; </label>
                    <input class="form-control" type="text" id="id_anggota" data-error="Harap masukkan nama anggota" required>
                    <input type="hidden" id="idanggota" name="id_anggota">
                    <input type="hidden" id="namaanggota" name="namaanggota">
                    <input type="hidden" id="KODECABANG" name="KODECABANG">
                </div>
                
                <div class="row">
                	<div class="col-sm-6">
						<div class="form-group">
							<label for="">Jenis Simpanan:</label>
                            <select class="form-control" name="id_jenis" onchange="get_saldo(this.value)"  data-error="Harap memilih jenis simpanan" required>
                                <option value="" disabled selected>Pilih salah satu</option>
                            <?php
                                if($jenis_simpanan->num_rows() > 0) {
                                    foreach($jenis_simpanan->result() as $res) {
                                        echo '<option value="'. $res->IDAKUN .'">'. $res->JNS_SIMP .'</option>';
                                    }
                                }
                            ?>
                            </select>
						</div>
					</div>
                    
                    <div class="col-sm-6">
						<div class="form-group">
							<label for="">Jumlah Penarikan :</label>
                    		<input class="form-control" placeholder="100000" type="text" name="jumlah" value="0" id="jumlah" data-error="Harap jumlah setoran" required>
						</div>
					</div>
                </div>
                <div class="form-group">
                    <label for="">Keterangan :</label>
                    <input class="form-control" placeholder="Keterangan" type="text" name="keterangan" value="<?php (isset($row->IDTRAN_KAS) ? $row->KETERANGAN : '')?>">
                </div>
                
                <div class="form-group">
                    <label for="">Ambil Dari Kas :</label>
                    <select class="form-control" name="id_kas">
                    <?php
						if($jenis_kas->num_rows() > 0) {
							$result	=	$jenis_kas->result_array();
							
                            foreach($result as $key=>$res) {
								echo '<option value="'. $res['IDAKUN'].'">'. $res['NAMA_KAS'] .'</option>';
                            }
                        }
                    ?>
                    </select>
                </div>
                
                <div class="form-buttons-w">
                    <button class="btn btn-primary" type="submit"> Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<link href="<?php echo base_url();?>bower_components/autocomplete/tautocomplete.css" rel="stylesheet">
<script src="<?php echo base_url();?>bower_components/autocomplete/tautocomplete.js"></script>
<script> 
	var base_url = '<?php echo base_url();?>'; 
	var base_url	=	'<?php echo base_url();?>',
		action		=	'input';
	var _saldo		=	0;
	var _saldo_mengendap	=	<?php echo sukubunga('saldo_mengendap')?>;
	
	$().ready(function () {
		var anggota = jQuery11("#id_anggota").tautocomplete({
			width: "500px",
			columns: ['id', 'Nama', 'Alamat', 'Identitas', 'Kodecabang'],
			hide: ['id'],
			ajax: {
				url: base_url + "get-anggota",
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
				$("#namaanggota").val(selection['Nama']);
				$("#KODECABANG").val(selection['Kodecabang']);
			}
		});
		
		$('#jumlah').keyup(function(){
			_jumlah	=	eval($('#jumlah').val().replace(/\,/g, ''));
			$(this).val(rupiah(_jumlah));
			
		}).focus(function(){ $(this).select() });
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
<script src="<?php echo base_url();?>assets/js/simpanan/penarikan.js"></script>
