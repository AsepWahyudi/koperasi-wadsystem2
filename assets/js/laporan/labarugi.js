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
        url       : base_url + "/laplabarugi",
        data      : data, 
        beforeSend: function() {
            // tableBody.html("<tr><td colspan='" + columnNum + "' class='text-center'>Sedang mengambil data..</td></tr>");
        },
        success: function(apiRes) {
			window.open(base_url + "cetaklaplabarugi");   
        },
        error: function() {
            alert("Gagal mendapatkan data. Harap cek koneksi anda");
        }
    });  
});
function renderTableData(numData, data) {
    var tableData = btnEdit = image = "";
	var pendapatan = beban = debet = kredit = 0;
    $.each(data, function(i, obj) {
		var saldo_akhir	= obj.AKUN == 'Pendapatan' ? eval(obj.KREDIT) : 0;		

		if (saldo_akhir != 0) {
			tableData += "<tr>" +
				"<td>" + numData + "</td>" +
				"<td>" + nbsp(obj._level, obj._header, obj.KODE_AKTIVA) + "</td>" +
				"<td>" + nbsp(obj._level, obj._header, obj.JENIS_TRANSAKSI) + "</td>" +
				/*"<td>" + nbsp(0, obj._header, rupiah(obj.DEBET)) + "</td>" +
				"<td>" + nbsp(0, obj._header, rupiah(obj.KREDIT)) + "</td>" +*/
				"<td>" + nbsp(0, obj._header, rupiah(saldo_akhir)) + "</td>" +
				"</tr>";
		
			pendapatan = obj.KODE_AKTIVA == '4' ? eval(obj.KREDIT) : pendapatan;
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
			"<td><b>" + rupiah(eval(pendapatan)) + "</b></td>" +
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