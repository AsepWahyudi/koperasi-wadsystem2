$(document).ready(function() {
    if (action == "view") {
        ajaxDataTable();
    }
});

function renderTableData(numData, data){
	
	var tableData = btnEdit =   image =   "";
	
	$.each(data, function(i, obj) {
		var btnDetail		=   "<a href='"+base_url+"data-pengajuan-pinjaman?id="+obj.IDPINJM_H+"' class='btn btn-success btn-sm'><i class='fa fa-search'></i></a>";
		
		var hitungan	=	'<table class="table-custom">';
			hitungan	+=	'<tr><td>Jenis Kredit</td> <td>:</td> <td>' + obj.JNS_PINJ + '</td></tr>';
			hitungan	+=	'<tr><td>Jml Kredit</td> <td>:</td> <td>' + obj.JUMLAH + '</td></tr>';
			hitungan	+=	'<tr><td>Biaya Admin</td> <td>:</td> <td>' + obj.BIAYA_ADMIN + '</td></tr>';
			hitungan	+=	'<tr><td>Biaya Asuransi</td> <td>:</td> <td>' + obj.BIAYA_ASURANSI + '</td></tr>';
			hitungan	+=	'<tr><td>Lama Angsuran</td> <td>:</td> <td>' + obj.LAMA_ANGSURAN + ' bulan</td></tr>';
			hitungan	+=	'<tr><td>Angsuran Dasar</td> <td>:</td> <td>' + obj.ANGSURAN_DASAR + '</td></tr>';
			hitungan	+=	'<tr><td>Basil Dasar</td> <td>:</td> <td>' + obj.BASIL_DASAR + '</td></tr>';
			hitungan	+=	'</table>';
		
			
		tableData   +=  "<tr>"+
							"<td><b>"+numData+"</b></td>"+
							"<td>"+obj.TGL_PINJ+"</td>"+
							"<td>"+obj.NAMA_ANGGOTA+"</td>"+
							"<td>"+obj.ALAMAT+"</td>"+
							"<td>"+ hitungan +"</td>"+
							"<td>Menunggu</td>"+
							"<td>"+btnDetail+"</td>"+
						"</tr>";
		numData++;
	});
	
	return tableData;
}