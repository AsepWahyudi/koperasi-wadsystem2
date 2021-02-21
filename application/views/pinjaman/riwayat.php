<?php  
$wadCabang = $this->session->userdata('wad_cabang');
$cabsh = $this->dbasemodel->loadsql("SELECT * FROM m_cabang WHERE KODE =  ".$this->session->userdata('wad_kodecabang')."");
$cabHh = $cabsh->row();
?>
<div class="col-sm-12 head-print">
    <div class="panel">
        <div class="panel-content">
                <div class="row">
                        <table>
							<tr>
								<td><img src="img/logokop.png" style="width: 60px;height: 60px;margin-right: 10px;margin-left: 10px;" /></td>
								<td valign="top" class="headtitle">
								<h4><?php  echo $cabHh->NAMAKSP?></h4>
								<?php  echo $cabHh->ALAMAT?> <?php  echo $cabHh->KOTA?><br>
								Telp : <?php  echo $cabHh->TELP?> Email : <?php  echo $cabHh->EMAIL?><br>
								Web : <?php  echo $cabHh->WEB?>
								</td>
							</tr>
						</table>            
                </div>
        </div>
    </div>
</div>
<div class="col-sm-12">
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
                <div class="row">
                    <div class="col-sm-8 col-lg-8">
                        <h4 class="color-primary"><?php  echo $PAGE_TITLE?> [Pencarian:]</h4>
                    </div>
                    <div class="col-sm-2  col-lg-2 ">
	                    <a href="<?php  echo base_url();?>pinjaman-data"" class="btn btn-primary btn-block" >
	                    	<i class="fa fa-angle-double-left"></i> 
	                    	Data Pinjaman
	                	</a>
	                </div>
	                <div class="col-sm-2  col-lg-2 ">
	                    <a onclick="window.print()" class="btn btn-info btn-block" >
	                    	<i class="fa fa-print"></i> 
	                    	Cetak
	                	</a>
	                </div>
                </div>
        </div>
    </div>
</div>
<div class="col-sm-12">
	<div class="panel ">
		<div class="panel-header b-primary bt-sm">
			<div class="row">
				<div class="col-sm-12">
					<div class="element-box">
			            <form id="formPinjaman" action="pinjaman/riwayat/search" method="post" class="formValidate">
			                <!-- <div class="row">
			                	<div class="col-sm-4">
									<div class="form-group">
										<label for="">Cabang :</label>
			                    		<?php  if($this->session->userdata('wad_level')=="admin"){ ?>
			                            <select class="form-control form-control-sm rounded" name="cabang" id="cabang">
			                                <option value="">Pilih Cabang</option>
			                                <?php 
			                                    $cabs = $this->dbasemodel->loadsql("SELECT NAMA,KODE FROM m_cabang ORDER BY NAMA ASC");
			                                    foreach($cabs->result() as $cab) {
			                                        $sel = ($cab->KODE==$this->session->userdata('wad_cabang'))? 'selected="selected"':"";
			                                        echo '<option value="'. $cab->KODE .'" '. $sel .'>'. $cab->NAMA .'</option>';
			                                    } } ?>
			                            </select>
									</div>
								</div>
			                </div>-->
			                <div class="row print-hide">
			                	<div class="col-sm-10">
			                    	<div class="form-group">
			                            <label for="">Nama Anggota : &nbsp; </label>
			                            <input class="form-control" type="text" id="id_anggota" data-error="Harap masukkan nama anggota">
			                            <input type="hidden" id="idanggota" name="id_anggota">
			                            <input type="hidden" id="namaanggota" name="nama_penyetor">
			                            <input type="hidden" id="alamatanggota" name="alamat">
			                            <input type="hidden" id="identitas" name="no_identitas">
			                        </div>
			                    </div>
			                	<div class="col-sm-2" style="padding-top:25px">
			                        <button class="btn btn-primary btn-block" type="button" id="btn-riwayat" onclick="getRiwayat()"> Cari</button>
			                    </div>
			                </div>
			        </div>
				</div>
			</div>
		</div>

		<div class="panel-content">
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group" id="showRiwayat">
						
					</div>
				</div>
			</div>
		</div>
	</form>
	</div>
</div>
<style>
	table.font-small th, table.font-small td { vertical-align:top; }
	span.f-right{ float: right;}
	span.macet{color: #82020; background: rgba(206, 0, 0, 0.3);}
	span.lancar{color: #1f6f04; background: rgba(38, 153, 0, 0.3);}
	span.meragukan{color: #965d08; background: rgba(153, 102, 0, 0.3);}
	span.buruk{color: #82020; background: rgba(206, 0, 0, 0.3);}
</style>
<style type = "text/css">
      @media print {
         .page-header{display: none;}
         .page-body{padding: unset;}
         .content-header{display: none;}
         .left-sidebar{display: none;}
         .btn{display: none;}
         .panel-header{display: none;}
         .dataTables_wrapper .row{display: none;}
         .content{margin: unset; margin-top: unset; padding: unset;}
         .panel{margin: unset; }
         .panel .panel-content{padding: 5px;}
         html.fixed .content{margin: unset;}
         h4{margin-top: 0px;margin-bottom: 0px;}
         .scroll-to-top{display: none;}
         .head-print{display: block;}
         .text-center.print-hide{display: none;}
         body{line-height: unset; font-size: small;}
         .daterangepicker{display: none;}
         .width1{
         	width: 1%;
         }
         .table-custom tr{
			border-bottom: inset;
    		border-bottom-width: thin;
         }
         .table-custom td{
			vertical-align: top;
         }
      }
      @media screen {
      	.head-print{display: none;}
      	.table-custom tr{
			border-bottom: inset;
    		border-bottom-width: thin;
         }
         .table-custom td{
			vertical-align: top;
         }
      }
</style>
<link href="<?php  echo base_url();?>bower_components/autocomplete/tautocomplete.css" rel="stylesheet">
<script src="<?php  echo base_url();?>bower_components/autocomplete/tautocomplete.js"></script>
<script> 
var base_url = '<?php  echo base_url();?>'; 
var action	=	'input';
	
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
		getRiwayat();
	});
	
});

	function getRiwayat() {
		$.ajax({
			type: "POST",
			url: base_url + "pinjaman/riwayat/detail",
			data: 'idanggota=' + $("#idanggota").val(),
			cache: false,
				success: function(msg){
					var data = JSON.parse(msg);
					$("#showRiwayat").html(_parseData(data));
				}, error: function (result) {
					var teks = result['status'] + " - " + result['statusText'];
					$('#informationModalText').html(teks);
					$('#informationModal').modal('show');
				}
		});
	}
	
	function _parseData(obj) {
		console.log(obj[0]['NAMA']);
		if(obj.length == 0) { return 'Belum memiliki riwayat pinjaman'; }
		html	=	'<table class="table table-responsive table-striped table-lightfont table-customX font-small">';
		html	+=	'<thead>';
		html	+=	'<tr>';
		html	+=	'<td>No</td>';
		html	+=	'<td>Nama</td>';
		html	+=	'<td>Rekening</td>';
		html	+=	'<td>Tgl Pinjam</td>';
		html	+=	'<td>Jumlah</td>';
		html	+=	'<td>Status</td>';
		html	+=	'<td>Keterangan</td>';
		html	+=	'</tr>';
		html	+=	'</thead>';
		html	+=	'</tbody>';
		
		for(var i=0; i < obj.length; i++) {
			var keterangan	=	(obj[i]['IS_RESET'] == '0' ? '<span class="lancar">Lancar</span>' : (obj[i]['IS_RESET'] == '1' ? '<span class="meragukan">Meragukan</span>' : (obj[i]['IS_RESET'] == '3' ? '<span class="macet">Macet</span>' : (obj[i]['IS_RESET'] == '2' ? '<span class="buruk">Buruk</span>' : '<span class="macet">Macet</span>'))));
			html	+=	'<tr>';
			html	+=	'<td>'+ (i+1) +'</td>';
			html	+=	'<td>'+ obj[i]['NAMA'] +'</td>';
			html	+=	'<td>'+ obj[i]['REKENING'] +'</td>';
			html	+=	'<td>'+ obj[i]['TGL_PINJ'] +'</td>';
			html	+=	'<td>'+ rupiah(obj[i]['JUMLAH']) +'</td>';
			html	+=	'<td>'+ (obj[i]['LUNAS'] == 'Lunas' ? 'Lunas' : 'Belum Lunas') +'</td>';
			html	+=	'<td>'+ keterangan +'</td>';
			html	+=	'</tr>';
		}
		
		html	+=	'</tbody>';
		html	+=	'</table>';
		
		return html;
	}
</script>