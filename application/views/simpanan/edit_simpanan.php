<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <div class="row">
                <div class="col-sm-9 col-lg-9">
                    <h4 class="color-primary">Edit Setoran Tunai</h4>
                </div>
                <!--div class="col-sm-3 col-lg-3">
                    <a href="<?=base_url()?>setoran-tunai" class="btn btn-primary btn-block" >
                        <i class="fa fa-angle-double-left"></i> 
                        Data Setoran Tunai
                    </a>
                </div-->
            </div>
        </div>
    
        <div class="panel-content">
            <?php
	        	echo notifikasi($this->session->flashdata('ses_trx_simp'));
				if(isset($data_source)) {
					$row = $data_source->row();
				}
				
				$tgl = date_create($datasetoran->TGL_TRX);
				$tgl = date_format($tgl,"d/m/Y");
				
				$idanggota = $datasetoran->ID_ANGGOTA;
				
				$this->db->select('*');
				$this->db->from('m_anggota');
				$this->db->where('idanggota',$idanggota); 
				$output = $this->db->get()->row(); 
				
			?>
            <form id="formSetoran">
			
				<input type="hidden" name="ID_TRX_SIMP" value="<?php echo $datasetoran->ID_TRX_SIMP;?>">
                <div class="form-group">
                    <label for="">Tanggal Transaksi :</label>
					
                    <input class="single-daterange form-control" value="<?php $tgl;?>" placeholder="Tanggal Transaksi" type="text" name="tgl_trx" readonly>
                </div>
                <div class="form-group">
                    <label for="">Nama Anggota : &nbsp; </label>
                    <input class="form-control" type="text"  value="<?php echo $output->NAMA;?>" readonly >
                    <input type="hidden" id="idanggota" name="id_anggota" value="<?php echo $output->IDANGGOTA;?>">
                    <input type="hidden" id="namaanggota" name="nama_penyetor" value="<?php echo $output->NAMA;?>">
                    <input type="hidden" id="alamatanggota" name="alamat" value="<?php echo $output->ALAMAT;?>">
                    <input type="hidden" id="identitas" name="no_identitas" value="<?php echo $output->NO_IDENTITAS;?>">
                    
                </div>
                
                <div class="row">
                	<div class="col-sm-6">
						<div class="form-group">
							<label for="">Jenis Simpanan:</label>
                            <select class="form-control" name="id_jenis" onchange="jenis_simpanan(this.value)"  data-error="Harap jenis simpanan" required>
                                <option value="" disabled selected>Pilih salah satu</option>
                            <?php
                                if($jenis_simpanan->num_rows() > 0) {
                                    foreach($jenis_simpanan->result() as $res) {
										
										if($datasetoran->ID_JENIS == $res->IDAKUN){
											
											echo '<option value="'. $res->IDAKUN . '|' . $res->JUMLAH .'" selected>'. $res->JNS_SIMP .'</option>';
										}else{
											echo '<option value="'. $res->IDAKUN . '|' . $res->JUMLAH .'">'. $res->JNS_SIMP .'</option>';
										}
                                    }
                                }
                            ?>
                            </select>
						</div>
					</div>
                    
                    <div class="col-sm-6">
						<div class="form-group">
							<label for="">Jumlah :</label>
                    		<input class="form-control" placeholder="100,000" type="text" name="jumlah" value="<?php echo number_format($datasetoran->JUMLAH);?>" id="editjumlah" data-error="Harap jumlah setoran" required>
						</div>
					</div>
                    
                </div>
                
                
                <div class="form-group">
                    <label for="">Keterangan :</label>
                    <input class="form-control" placeholder="Keterangan" type="text" name="keterangan" value="<?php echo $datasetoran->KETERANGAN;?>">
                </div>
                
                <div class="form-group">
                    <label for="">Simpan Ke Kas :</label>
                    <select class="form-control" name="id_kas">
                    <?php
						if($jenis_kas->num_rows() > 0) {
							$result	=	$jenis_kas->result_array();
							
                            foreach($result as $key=>$res) {
								
								if($datasetoran->ID_KAS == $res['IDAKUN']){
									echo '<option value="'. $res['IDAKUN'].'" selected>'. $res['NAMA_KAS'] .'</option>';
								}else{
									echo '<option value="'. $res['IDAKUN'].'">'. $res['NAMA_KAS'] .'</option>';
								} 
                            }
                        }
                    ?>
                    </select>
                </div>
                
                <div class="form-buttons-w">
                    <button class="btn btn-primary" type="button" id="editsetoran"> Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<link href="<?php echo base_url();?>bower_components/autocomplete/tautocomplete.css" rel="stylesheet">
<script src="<?php echo base_url();?>bower_components/autocomplete/tautocomplete.js"></script>
<script> 
	var base_url = '<?php echo base_url();?>'; 
	action = 'input'; 
	$().ready(function () {
		var anggota = jQuery11("#id_anggota").tautocomplete({
			width: "500px",
			columns: ['id', 'Nama', 'Alamat', 'Identitas'],
			hide: ['id'],
			ajax: {
				url: base_url + "simpanan/simpanan/get_anggota",
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
				$("#alamatanggota").val(selection['Alamat']);
				$("#namaanggota").val(selection['Nama']);
				$("#identitas").val(selection['Identitas']);
			}
		});
	
		$('#editjumlah').keyup(function(){
			_jumlah	=	eval($('#editjumlah').val().replace(/\,/g, ''));
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
<script src="<?=base_url()?>assets/js/simpanan/editsimpanan.js"></script>
