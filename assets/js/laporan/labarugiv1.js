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
	var pendapatan = beban = debet = kredit = 0;
    $.each(data, function(i, obj) {
		var saldo_akhir	=	obj.AKUN == 'Pendapatan' ? (eval(obj.DEBET) - eval(obj.KREDIT)) : (eval(obj.KREDIT) - eval(obj.DEBET));		

		if (saldo_akhir != 0) {
			tableData += "<tr>" +
				"<td>" + numData + "</td>" +
				"<td>" + nbsp(obj._level, obj._header, obj.KODE_AKTIVA) + "</td>" +
				"<td>" + nbsp(obj._level, obj._header, obj.JENIS_TRANSAKSI) + "</td>" +
				/*"<td>" + nbsp(0, obj._header, rupiah(obj.DEBET)) + "</td>" +
				"<td>" + nbsp(0, obj._header, rupiah(obj.KREDIT)) + "</td>" +*/
				"<td>" + nbsp(0, obj._header, rupiah(saldo_akhir)) + "</td>" +
				"</tr>";
		
			pendapatan = obj.KODE_AKTIVA == '4' ? (eval(obj.DEBET) - eval(obj.KREDIT)) : pendapatan;
			beban = obj.KODE_AKTIVA == '5' ? (eval(obj.KREDIT) - eval(obj.DEBET)) : beban;
		
			numData++;
		}
    });
	
	tableData += "<tr>" +
            "<td colspan='3' class='text-right'><b>Laba Rugi Sebelum Pajak</b></td>" +
			"<td><b>" + rupiah(eval(pendapatan) - eval(beban)) + "</b></td>" +
            "</tr>";
			
	tableData += "<tr>" +
            "<td colspan='3' class='text-right'><b>Taksiran Pajak</b></td>" +
			"<td><b>" + rupiah(0) + "</b></td>" +
            "</tr>";
			
	tableData += "<tr>" +
            "<td colspan='3' class='text-right'><b>Laba Rugi Setelah Pajak</b></td>" +
			"<td><b>" + rupiah(eval(pendapatan) - eval(beban)) + "</b></td>" +
            "</tr>";

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