$(document).ready(function() {
    if (action == "view") {
        ajaxDataTable();
    }
});

$("#filterForm").submit(function(e) {
    e.preventDefault();
    return false;
});

$("#tampilfilter").click(function(){
	resetPage();
    ajaxDataTable();
});


function renderTableData(numData, data){
	
	var tableData = btnEdit =   image =   "";
	
	$.each(data, function(i, obj) {
		var btnDetail		=   "<a href='"+base_url+"pinjaman-find?id="+obj.IDPINJM_H+"&r=pd' class='btn btn-success btn-sm'><i class='fa fa-search'></i></a>";
		
		var btnCetak		=   "<a href='"+base_url+"pinjaman-cetak/"+obj.IDPINJM_H+"' class='btn btn-info btn-sm'><i class='fa fa-print'></i></a>";

		var btnMap		=   "<a class='btn btn-warning btn-sm' href='https://www.google.co.id/maps/dir//"+obj.lat+","+obj.lng+"/@"+obj.lat+","+obj.lng+",19z' target='_blank'><i class='fa fa-map-marker'></i></a>";

		var hitungan	=	'<table class="table-custom">';
			hitungan	+=	'<tr><td>Jenis Kredit</td> <td>:</td> <td>' + obj.JNS_PINJ + '</td></tr>';
			hitungan	+=	'<tr><td>Jml Kredit</td> <td>:</td> <td>' + obj.JUMLAH + '</td></tr>';
			hitungan	+=	'<tr><td>Biaya Admin</td> <td>:</td> <td>' + obj.BIAYA_ADMIN + '</td></tr>';
			hitungan	+=	'<tr><td>Biaya Asuransi</td> <td>:</td> <td>' + obj.BIAYA_ASURANSI + '</td></tr>';
			hitungan	+=	'<tr><td>Lama Angsuran</td> <td>:</td> <td>' + obj.LAMA_ANGSURAN + ' bulan</td></tr>';
			hitungan	+=	'<tr><td>Angsuran Dasar</td> <td>:</td> <td>' + obj.ANGSURAN_DASAR + '</td></tr>';
			hitungan	+=	'<tr><td>Basil Dasar</td> <td>:</td> <td>' + obj.BASIL_DASAR + '</td></tr>';
			hitungan	+=	'</table>';
		
		var tagihan		=	'<table class="table-custom">';
			tagihan		+=	'<tr><td>Jml Angsuran</td> <td>:</td> <td>' + obj.JML_ANGSURAN + '</td></tr>';
			tagihan		+=	'<tr><td>Jml Denda</td> <td>:</td> <td>' + obj.JML_DENDA + '</td></tr>';
			tagihan		+=	'<tr><td>Total Tagihan</td> <td>:</td> <td>' + obj.TOTAL_TAGIHAN + '</td></tr>';
			tagihan		+=	'<tr><td>Sudah Bayar</td> <td>:</td> <td>' + obj.SUDAH_DIBAYAR + '</td></tr>';
			tagihan		+=	'<tr><td>Sisa Angsuran</td> <td>:</td> <td>' + obj.SISA_ANGSURAN + '</td></tr>';
			tagihan		+=	'<tr><td>Sisa Tagihan</td> <td>:</td> <td>' + obj.SISA_TAGIHAN + '</td></tr>';
			tagihan		+=	'</table>';
			
		tableData   +=  "<tr>"+
							"<td class='width1'><b>"+numData+"</b></td>"+
							"<td class='width1'>"+obj.TGL_PINJ+"</td>"+
							"<td class='width1'>"+obj.NAMA_ANGGOTA+"</td>"+
							"<td class='width1'>"+obj.ALAMAT+"</td>"+
							"<td class='width1'>"+ hitungan +"</td>"+
							"<td class='width1'>"+ tagihan +"</td>"+
							"<td class='width1'>"+obj.LUNAS+"</td>"+
							"<td class='width1'>"+obj.USERNAME+"</td>"+
							"<td class='text-center print-hide'>" + btnDetail +" "+ btnCetak + " "+ btnMap + "</td>" +
						"</tr>";
		numData++;
	});
	
	return tableData;
}

$('#formPinjaman').validator().on('submit', function (e) {
  if (e.isDefaultPrevented()) {
    $('#informationModalText').html('Beberapa data belum dimasukkan, harap lengkapi data!');
	$('#informationModal').modal('show');
  } else {
    var dataPost	=   $("#formPinjaman").serializeArray(),
		target		=	$("#formPinjaman").attr("action");
	$.ajax({
		type: 'POST',
		url: base_url+target,
		data: dataPost,
		dataType: 'json',
		beforeSend:function(){
			$("#formPinjaman :input").attr("disabled", true);
		},
		success:function(apiRes){
			$(location).attr('href', base_url + 'pinjaman-data');
		},
		error:function(){
			$("#formPinjaman :input").attr("disabled", false);
		}
	});
	return false;
  }
});


function getFormAgs(){
	$().ready(function () {
		$("#mymodals").modal("show");
		var urldata	=	base_url + $(this).attr('var-url');
		$.ajax({
			type: "POST",
			url: urldata,
			data: 'data=',
			cache: false,
				success: function(msg){
					console.log(msg);
				}, error: function (result) {
					var teks = result['status'] + " - " + result['statusText'];
					$('#informationModalText').html(teks);
					$('#informationModal').modal('show');
				}
		});
	});
}