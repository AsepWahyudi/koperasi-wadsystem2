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
$("#cetaklaporan").click(function(){
	data = $('#filterForm').serializeArray();
	
	 $.ajax({
        type      : 'POST',
        url       : base_url + "/lapbukubesar",
        data      : data, 
        beforeSend: function() {
            // tableBody.html("<tr><td colspan='" + columnNum + "' class='text-center'>Sedang mengambil data..</td></tr>");
        },
        success: function(apiRes) {
			window.open(base_url + "cetaklapbukubesar");  
			
        },
        error: function() {
            alert("Gagal mendapatkan data. Harap cek koneksi anda");
        }
    });  
});
function renderTableData(numData, data) {
	var tableData = btnEdit = image = "";
	var totalDebet = totalKredit = 0;
	saldo = 0;
    $.each(data, function(i, obj) {
		if (obj.KODE_AKTIVA != "1010202") {
			if (i == 0) {
				saldo = obj.SALDO_AWAL;
				tableData += "<tr>" +
					"<td colspan='6' class='text-center'><b>SALDO AWAL</b></td>" +
					"<td>" + rupiah(obj.SALDO_AWAL) + "</td>" +
					"</tr>";
				
			} else if (i == 1) {
				totalDebet = obj.DEBET;
				totalKredit = obj.KREDIT;
			
			} else {
				// if (obj.AKUN == 'Aktiva' || obj.AKUN == 'Tpp') {
				// 	saldo = (eval(saldo) + eval(obj.DEBET)) - eval(obj.KREDIT);
				// } else {
				// 	saldo = (eval(saldo) + eval(obj.KREDIT)) - eval(obj.DEBET);
				// }

				saldo = (eval(saldo) + eval(obj.KREDIT)) - eval(obj.DEBET);

				totalDebet = eval(totalDebet) + eval(obj.DEBET);
				totalKredit = eval(totalKredit) + eval(obj.KREDIT);

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