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
    var tableData = btnEdit = image = idvtransaksi = "";
    $.each(data, function(i, obj) {
		/* if(obj.KAS_DEBET != '' && obj.KAS_DEBET != null) {
			tableData += "<tr>" +
				"<td rowspan='2'>" + numData + "</td>" +
				"<td rowspan='2'>" + obj.TANGGAL + "</td>" +
				"<td>" + obj.KAS_DEBET + "</td>" +
				"<td>" + (obj.KET == null ? '' : obj.KET) + "</td>" +
				"<td>" + rupiah(obj.KREDIT) + "</td>" +
				"<td>" + rupiah(0) + "</td>" +
				"</tr>";
				
			tableData += "<tr>" +
				"<td>" + obj.JENIS_TRANSAKSI + "</td>" +
				"<td>" + (obj.KET == null ? '' : obj.KET) + "</td>" +
				"<td>" + rupiah(obj.DEBET) + "</td>" +
				"<td>" + rupiah(obj.KREDIT) + "</td>" +
				"</tr>";
			
		}
		
		if(obj.KAS_KREDIT != '' && obj.KAS_KREDIT != null) {
			tableData += "<tr>" +
				"<td rowspan='2'>" + numData + "</td>" +
				"<td rowspan='2'>" + obj.TANGGAL + "</td>" +
				"<td>" + obj.JENIS_TRANSAKSI + "</td>" +
				"<td>" + (obj.KET == null ? '' : obj.KET) + "</td>" +
				"<td>" + rupiah(obj.DEBET) + "</td>" +
				"<td>" + rupiah(obj.KREDIT) + "</td>" +
				"</tr>";
				
			tableData += "<tr>" +
				"<td>" + obj.KAS_KREDIT + "</td>" +
				"<td>" + (obj.KET == null ? '' : obj.KET) + "</td>" +
				"<td>" + rupiah(0) + "</td>" +
				"<td>" + rupiah(obj.DEBET) + "</td>" +
				"</tr>";
		}*/
		
		if(idvtransaksi != obj.IDVTRANSAKSI) {
			tableData += "<tr>" +
					"<td rowspan='2'>" + numData + "</td>" +
					"<td rowspan='2'>" + obj.TANGGAL + "</td>" +
					"<td rowspan='2'>" + (obj.KETERANGAN == null ? '' : obj.KETERANGAN) + "</td>" +
					"<td>" + obj.JENIS_TRANSAKSI + "</td>" +
					"<td>" + rupiah(obj.DEBET) + "</td>" +
					"<td>" + rupiah(obj.KREDIT) + "</td>" +
					"</tr>";
			numData++;
		} else {
			tableData += "<tr>" +
					"<td>" + obj.JENIS_TRANSAKSI + "</td>" +
					"<td>" + rupiah(obj.DEBET) + "</td>" +
					"<td>" + rupiah(obj.KREDIT) + "</td>" +
					"</tr>";

		}
				
		idvtransaksi	=	obj.IDVTRANSAKSI;
		
    });
	return tableData;
}

function nbsp(level, header, akun) {
	var loop	= (eval(level) * 3);
	var spasi	= '';
	for(var i=0; i<=loop; i++) {
		spasi	+= '&nbsp;';
	}
	if(header == '1') { return spasi + '<b>'+ akun +'<b>'; }
	return spasi + akun;
}