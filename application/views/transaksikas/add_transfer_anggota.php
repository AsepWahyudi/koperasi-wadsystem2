<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <div class="row">
                <div class="col-sm-9 col-lg-9">
                    <h4 class="color-primary">Tambah Transfer Antar Anggota</h4>
                </div>
                <div class="col-sm-3 col-lg-3 ">
                    <a href="<?php  echo base_url();?>kas-transfer-anggota" class="btn btn-primary btn-block" >
                        <i class="fa fa-angle-double-left"></i> 
                        Data Transfer Antar Anggota
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
            <form method="post" action="<?php  echo base_url();?>transaksi_kas/transfer/<?php  echo ( isset($row->IDTRAN_KAS) ? 'updatetransferanggota?id=' . $row->IDTRAN_KAS : 'savetransferanggota')?>">
                <div class="form-group">
                    <label for="">Tanggal Transaksi :</label>
                    <input id="default-datepicker" class="form-control" placeholder="Tanggal Transaksi" type="text" value="<?php  echo (isset($row->IDTRAN_KAS) ? date('d/m/Y', strtotime($row->TGL)): date('d/m/Y'))?>" name="tgl">
                    
                <div class="form-group">
                    <label for="">Jumlah :</label>
                    <input class="form-control" placeholder="100000" type="number" name="jumlah" value="<?php  echo (isset($row->IDTRAN_KAS) ? $row->JUMLAH : '')?>">
                </div>
                
                <div class="form-group">
                    <label for="">Keterangan :</label>
                    <input class="form-control" placeholder="Keterangan" type="text" name="keterangan" value="<?php  echo (isset($row->IDTRAN_KAS) ? $row->KETERANGAN : '')?>">
                </div>
				
				 <div class="form-group">
                    <label for="">Dari Anggota : &nbsp; </label>
                    <input class="form-control" type="text" id="id_anggota" data-error="Harap masukkan nama anggota" required>
                    <input type="hidden" id="idanggota" name="id_anggota">
                    <input type="hidden" id="namaanggota" name="nama_penyetor">
                    <input type="hidden" id="alamatanggota" name="alamat">
                    <input type="hidden" id="identitas" name="no_identitas">
                    <input type="hidden" id="KODECABANG" name="KODECABANG"> 
                </div>
				<div class="form-group">
                    <label for="">Transfer Ke Anggota : &nbsp; </label>
                    <input class="form-control" type="text" id="id_anggotake" data-error="Harap masukkan nama anggota" required>
                    <input type="hidden" id="keidanggota" name="keid_anggota">
                    <input type="hidden" id="kenamaanggota" name="kenama_penyetor">
                    <input type="hidden" id="kealamatanggota" name="kealamat">
                    <input type="hidden" id="keidentitas" name="keno_identitas">
                    <input type="hidden" id="KEKODECABANG" name="KEKODECABANG"> 
                </div>
				
				 <!--div class="form-group">
                    <label for="">Biaya Transfer :</label>
                    <select class="form-control" name="dari_kas_id">
                    <?php 
                        // if($biayatransfer->num_rows() > 0) {
                            // foreach($biayatransfer->result() as $res) {
                                // echo '<option value="'. $res->ID_BIAYA_TRF_KAS .'" '. (isset($row->IDTRAN_KAS) ? ($row->ID_BIAYA_TRF_KAS == $res->ID_BIAYA_TRF_KAS ? 'selected' : ''): '') .'>'. $res->NAMA_BIAYA .' - Biaya = '. number_format($res->BIAYA_TRF) .'</option>';
                            // }
                        // }
                    ?>
                    </select>
                </div-->
                <!--div class="form-group">
                    <label for="">Ambil Dari Kas :</label>
                    <select class="form-control" name="dari_kas_id">
                    <?php 
                        // if($jenis_kas->num_rows() > 0) {
                            // foreach($jenis_kas->result() as $res) {
                                // echo '<option value="'. $res->IDAKUN .'" '. (isset($row->IDTRAN_KAS) ? ($row->DARI_KAS_ID == $res->ID_JNS_KAS ? 'selected' : ''): '') .'>'. $res->NAMA_KAS .'</option>';
                            // }
                        // }
                    ?>
                    </select>
                </div-->
				
				
				
                <!--div class="form-group">
                    <label for="">Transfer Ke Kas :</label>
                    <select class="form-control" name="untuk_kas_id">
                   <?php 
                        // if($jenis_kas->num_rows() > 0) {
                            // foreach($jenis_kas->result() as $res) {
                                // echo '<option value="'. $res->IDAKUN .'" '. (isset($row->IDTRAN_KAS) ? ($row->UNTUK_KAS_ID == $res->ID_JNS_KAS ? 'selected' : ''): '') .'>'. $res->NAMA_KAS .'</option>';
                            // }
                        // }
                    ?>
                    </select>
                </div--> 
                <div class="form-buttons-w">
                    <button class="btn btn-primary" type="submit"> Submit</button>
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

<link href="<?php echo base_url();?>bower_components/autocomplete/tautocomplete.css" rel="stylesheet">
<script src="<?php echo base_url();?>bower_components/autocomplete/tautocomplete.js"></script>
<script> 
	var base_url = '<?php echo base_url();?>'; 
	action = 'input';
	
	$().ready(function () {
		var anggota = jQuery11("#id_anggota").tautocomplete({
			width: "500px",
			columns: ['id', 'Nama', 'Alamat', 'Identitas', 'Kodecabang'],
			hide: ['id'],
			ajax: {
				url: base_url + "transaksi_kas/transfer/get_anggota",
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
				$("#KODECABANG").val(selection['Kodecabang']);
			}
		});
	
		var keanggota = jQuery11("#id_anggotake").tautocomplete({
			width: "500px",
			columns: ['id', 'Nama', 'Alamat', 'Identitas', 'Kodecabang'],
			hide: ['id'],
			ajax: {
				url: base_url + "transaksi_kas/transfer/get_anggotas",
				type: "GET",
				data: function(){var x = { para1: keanggota.searchdata()}; return x;},
				success: function (data) {
					var filterData = [];
	
					var searchData = eval("/" + keanggota.searchdata() + "/gi");
	
					$.each(data, function (i, v) {
						if (v.text.search(new RegExp(searchData)) != -1) {
							filterData.push(v);
						}
					});
					return filterData;
				}
			},
			onchange: function () {
				var selection = keanggota.all();
				$("#id_anggotake").val(keanggota.text());
				$("#keidanggota").val(selection['id']);
				$("#kealamatanggota").val(selection['Alamat']);
				$("#kenamaanggota").val(selection['Nama']);
				$("#keidentitas").val(selection['Identitas']);
				$("#KEKODECABANG").val(selection['Kodecabang']);
			}
		}); 
		
	});
	
	 
</script>
