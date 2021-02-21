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
        url       : base_url + "/lapneraca",
        data      : data, 
        beforeSend: function() {
            // tableBody.html("<tr><td colspan='" + columnNum + "' class='text-center'>Sedang mengambil data..</td></tr>");
        },
        success: function(apiRes) {
			window.open(base_url + "cetaklapneraca");  
			
        },
        error: function() {
            alert("Gagal mendapatkan data. Harap cek koneksi anda");
        }
    });  
});
function renderTableData(numData, data) {
	
	var tableData = btnEdit = image = "";
	
	aktiva = jmlkredit = jmldebet = 0;
	
	var jmldebitactiva = 0;
	var jmldebitpasiva = 0;
	var jmldebiteq = 0;
	
	var jmlkreditactiva = 0;
	var jmlkreditpasiva = 0;
	var jmlkrediteq = 0;
	
	var jmlsaldoactiva = 0;
	var jmlsaldopasiva = 0;
	var jmlsaldoeq = 0;
	
	var debitactiva = 0;
	var debitpasiva = 0;
	var debiteq = 0;
	
	var kreditactiva = 0;
	var kreditpasiva = 0;
	var krediteq = 0;
	
	var saldoactiva = 0;
	var saldopasiva = 0;
	var saldoeq = 0;

    $.each(data, function(i, obj) {
		
		// console.log(i);
		debet		= i >= 2 ? (obj.AKUN == 'Aktiva' ? (eval(obj.KREDIT) - eval(obj.DEBET)) : 0) : eval(obj.DEBET);
		debitactiva = i >= 2 ? (obj.AKUN == 'Aktiva' ? (eval(obj.KREDIT) - eval(obj.DEBET)) : 0) : eval(obj.DEBET);
		debitpasiva = i >= 2 ? (obj.AKUN == 'Pasiva' ? (eval(obj.KREDIT) - eval(obj.DEBET)) : 0) : eval(obj.DEBET);
		debiteq = i >= 2 ? (obj.AKUN == 'Equity' ? (eval(obj.KREDIT) - eval(obj.DEBET)) : 0) : eval(obj.DEBET);
		
		kredit = i >= 2 ? (obj.AKUN == 'Pasiva' ? (eval(obj.DEBET) - eval(obj.KREDIT)) : 0) : eval(obj.KREDIT);
		
		kreditactiva = i >= 2 ? (obj.AKUN == 'Aktiva' ? (eval(obj.DEBET) - eval(obj.KREDIT)) : 0) : eval(obj.KREDIT);
		kreditpasiva = i >= 2 ? (obj.AKUN == 'Pasiva' ? (eval(obj.DEBET) - eval(obj.KREDIT)) : 0) : eval(obj.KREDIT);
		krediteq = i >= 2 ? (obj.AKUN == 'Equity' ? (eval(obj.DEBET) - eval(obj.KREDIT)) : 0) : eval(obj.KREDIT);
		
		saldo	= obj.AKUN == 'Aktiva' ? eval(obj.DEBET) - eval(obj.KREDIT) : eval(obj.KREDIT) - eval(obj.DEBET);
		saldoactiva	= obj.AKUN == 'Aktiva' ? eval(obj.DEBET) - eval(obj.KREDIT) : eval(obj.KREDIT) - eval(obj.DEBET);
		saldopasiva	= obj.AKUN == 'Pasiva' ? eval(obj.DEBET) - eval(obj.KREDIT) : eval(obj.KREDIT) - eval(obj.DEBET);
		saldoeq	= obj.AKUN == 'Equity' ? eval(obj.DEBET) - eval(obj.KREDIT) : eval(obj.KREDIT) - eval(obj.DEBET);
		
		if(obj.AKUN == 'Aktiva'){
			jmldebitactiva = jmldebitactiva + eval(debet);
			jmlkreditactiva = jmlkreditactiva + eval(kreditactiva);
			jmlsaldoactiva = jmlsaldoactiva + eval(saldoactiva);
		}
		if(obj.AKUN == 'Pasiva'){
			jmldebitpasiva = jmldebitpasiva + eval(debitpasiva);
			jmlkreditpasiva = jmlkreditpasiva + eval(debitpasiva);
			jmlsaldopasiva = jmlsaldopasiva + eval(saldopasiva);
		}
		if(obj.AKUN == 'Equity'){
			jmldebiteq = jmldebiteq + eval(debiteq);
			jmlkrediteq = jmlkrediteq + eval(krediteq);
			jmlsaldoeq = jmlsaldoeq + eval(saldoeq); 
		}
		// if (obj.DEBET != 0 || obj.KREDIT != 0 || saldo != 0) {
			
			tableData += "<tr>" +
						 "<td>" + nbsp(0, obj._header, obj.KODE_AKTIVA) + "</td>" +
						 "<td>" + nbsp(obj._level, obj._header, obj.JENIS_TRANSAKSI) + "</td>" +
						 "<td>" + nbsp(0, obj._header, (obj.DEBET == 0 ? "0" : rupiah(obj.DEBET))) + "</td>" +
						 "<td>" + nbsp(0, obj._header, (obj.KREDIT == 0 ? "0" : rupiah(obj.KREDIT))) + "</td>" +
						 "<td>" + nbsp(0, obj._header, (saldo == 0 ? "0" : rupiah(saldo))) + "</td>" +
						 "</tr>";
			
			aktiva = obj.KODE_AKTIVA == '1' ? (obj.DEBET - obj.KREDIT) : aktiva;
		
			jmldebet = jmldebet + eval(debet);
			jmlkredit = jmlkredit + eval(kredit);
			
			// jmldebitactiva = jmldebitactiva + eval(debitactiva);
			  
			numData++;
		// }
    });
	// tableData = "<tr>" +
			// "<td colspan='2' class='text-center'><b>Jumlah</b></td>" +
			// "<td><b>" + rupiah(jmldebet) + "</b></td>" +
			// "<td><b>" + rupiah(jmlkredit) + "</b></td>" +
			// "</tr>";
	
	footerTable = "<tr>" +
					"<td colspan='2' class='text-center'><strong>TOTAL AKTIVA</strong></td>" +
					"<td>" + rupiah(jmldebitactiva) + "</td>" +
					"<td>" + rupiah(jmlkreditactiva) + "</td>" +
					"<td>" + rupiah(jmlsaldoactiva) + "</td>" +
				   "</tr>"+
					"<tr>" +
					"<td colspan='2' class='text-center'><strong>TOTAL PASIVA</strong></td>" +
					"<td>" + rupiah(jmldebitpasiva) + "</td>" +
					"<td>" + rupiah(jmlkreditpasiva) + "</td>" +
					"<td>" + rupiah(jmlsaldopasiva) + "</td>" +
				   "</tr>"+
				   "<tr>" +
					"<td colspan='2' class='text-center'><strong>TOTAL EQUITY</strong></td>" +
					"<td>" + rupiah(jmldebiteq) + "</td>" +
					"<td>" + rupiah(jmlkrediteq) + "</td>" +
					"<td>" + rupiah(jmlsaldoeq) + "</td>" +
				   "</tr>";
	$('#footer_jumlah').html(footerTable);		
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