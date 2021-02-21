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
	
	aktiva = jmlkredit	=	jmldebet	=	0;
    $.each(data, function(i, obj) {
		debet		=	i >= 2 ? (obj.AKUN == 'Aktiva' ? (eval(obj.KREDIT) - eval(obj.DEBET)) : 0) : eval(obj.DEBET);
		kredit		=	i >= 2 ? (obj.AKUN == 'Pasiva' ? (eval(obj.DEBET) - eval(obj.KREDIT)) : 0) : eval(obj.KREDIT);
		
		saldo	=	obj.AKUN == 'Aktiva' ? eval(obj.DEBET) - eval(obj.KREDIT) : eval(obj.KREDIT) - eval(obj.DEBET);
		tableData += "<tr>" +
			"<td>" + nbsp(0, obj._header, obj.KODE_AKTIVA) + "</td>" +
			"<td>" + nbsp(obj._level, obj._header, obj.JENIS_TRANSAKSI) + "</td>" +
			"<td>" + nbsp(0, obj._header, rupiah(obj.DEBET)) + "</td>" +
			"<td>" + nbsp(0, obj._header, rupiah(obj.KREDIT)) + "</td>" +
			"<td>" + nbsp(0, obj._header, rupiah(saldo)) + "</td>" +
			"</tr>";
			
		aktiva		=	obj.KODE_AKTIVA == '1' ? (obj.DEBET - obj.KREDIT) : aktiva;
		
		jmldebet	=	jmldebet + eval(debet);
		jmlkredit	=	jmlkredit + eval(kredit);
		numData++;
		
    });
	tableData2 = "<tr>" +
			"<td colspan='2' class='text-center'><b>Jumlah</b></td>" +
			"<td><b>" + rupiah(jmldebet) + "</b></td>" +
			"<td><b>" + rupiah(jmlkredit) + "</b></td>" +
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