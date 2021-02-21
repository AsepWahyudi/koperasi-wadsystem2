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
	var totalDebet = totalKredit = 0;
	saldo	=	0;
    $.each(data, function(i, obj) {
		if(i == 0) {
			saldo	=	obj.SALDO_AWAL;
			tableData += "<tr>" +
				"<td colspan='6' class='text-center'><b>SALDO AWAL</b></td>" +
				"<td>" + rupiah(obj.SALDO_AWAL) + "</td>" +
				"</tr>";
				
		} else if(i == 1) {
			totalDebet	=	obj.DEBET;
			totalKredit	=	obj.KREDIT;
			
		} else {
			if(obj.AKUN == 'Aktiva' || obj.AKUN == 'Tpp') {
				saldo	=	(eval(saldo) + eval(obj.DEBET)) - eval(obj.KREDIT);
			} else {
				saldo	=	(eval(saldo) + eval(obj.KREDIT)) - eval(obj.DEBET);
			}
			
			tableData += "<tr>" +
				"<td><b>" + numData + "</b></td>" +
				"<td>" + obj.TANGGAL + "</td>" +
				"<td>" + obj.JENIS_TRANSAKSI + "</td>" +
				"<td>" + (obj.KETERANGAN == null ? '-' : obj.KETERANGAN) + "</td>" +
				"<td>" + rupiah(obj.DEBET) + "</td>" +
				"<td>" + rupiah(obj.KREDIT) + "</td>" +
				"<td>" + rupiah(saldo) + "</td>" +
				"</tr>";
			numData++;
		}
		
    });
	
	footerTable	= "<tr>" +
				"<td colspan='4' class='text-center'><strong>TOTAL</strong></td>" +
    			"<td>" + rupiah(totalDebet) + "</td>" +
				"<td>" + rupiah(totalKredit) + "</td>" +
				"<td>" + rupiah(saldo) + "</td>" +
                "</tr>";
	$('#footer_jumlah').html(footerTable);
    return tableData;
}