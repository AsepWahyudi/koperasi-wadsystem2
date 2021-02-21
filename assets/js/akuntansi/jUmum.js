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

function renderTableData(numData, data) {
    var tableData = btnEdit = image = "";
    $.each(data, function(i, obj) {
		var btn		=   "<a href='"+base_url+"pinjaman-find?id="+obj.ID+"' class='btn btn-success btn-sm'><i class='fa fa-edit'></i></a>";
        tableData += "<tr>" +
            "<td>" + numData + "</td>" +
			"<td><b>" + jenis_jurnal(obj.KODE_JURNAL) + "</b></td>" +
            "<td>" + obj.TANGGAL + "</td>" +
            "<td>" + obj.CABANG + "</td>" +
            "<td>" + obj.REFERENSI + "</td>" +
            "<td>" + obj.KETERANGAN + "</td>" +
            "<td>" + obj.JENIS_TRANSAKSI + "</td>" +
            "<td>" + rupiah(obj.DEBET) + "</td>" +
            "<td>" + rupiah(obj.KREDIT) + "</td>" +
            "</tr>";
        numData++;
    });

    return tableData;
}

function jenis_jurnal(jenis) {
	var result	=	'';
	switch(jenis) {
		case 'JU'	:	result = 'Jurnal Umum';
						break;
		case 'CE'	:	result = 'Closing Entry';
						break;
		case 'JK'	:	result = 'Jurnal Koreksi';
						break;
		case 'KK'	:	result = 'Kas Keluar';
						break;
		case 'KM'	:	result = 'Kas Masuk';
						break;
		case 'JE'	:	result = 'Jurnal Eliminasi';
						break;
		default		:	break;
	}
	return result;
}