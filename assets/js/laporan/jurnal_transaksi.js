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
        url       : base_url + "/lapjurnaltransaksi",
        data      : data, 
        beforeSend: function() {
            // tableBody.html("<tr><td colspan='" + columnNum + "' class='text-center'>Sedang mengambil data..</td></tr>");
        },
        success: function(apiRes) {
			window.open(base_url + "/cetaklaporanjurnaltransaksi");  
			
        },
        error: function() {
            alert("Gagal mendapatkan data. Harap cek koneksi anda");
        }
    });  
});

function renderTableData(numData, data) {
	
	var tableData = btnEdit = image = "";
	noBukti = "";
	ket = "";
	tgl = "";
	
	var totalDebet = totalKredit = 0;
	
	// NAMBAH
	var totalStDebet = totalStKredit = 0;
	var totalKmDebet = totalKmKredit = 0;
	var totalArDebet = totalArKredit = 0;
	var totalKrDebet = totalKrKredit = 0;
	var totalRtDebet = totalRtKredit = 0;
	// 'ST','KM','AR','KR','RT'
	
	// KURANG
	var totalPtDebet = totalPtKredit = 0;
	var totalKkDebet = totalKkKredit = 0;
	var totalJtDebet = totalJtKredit = 0;
	
	
    $.each(data, function(i, obj) {
		//if( i > 0) {
	  if (obj.KODE_JURNAL == 'ST' || obj.KODE_JURNAL == 'PT') {
		  
		  var IDTRXSIMP = "";
		  
		  if(obj.ID_TRX_SIMP == "" || obj.ID_TRX_SIMP == null){
			  
			  IDTRXSIMP = "";
		  }else{
			  IDTRXSIMP = obj.ID_TRX_SIMP;
		  }
        noBukti = "TAB.0"+IDTRXSIMP;
		
      } else if (obj.KODE_JURNAL == 'KM' || obj.KODE_JURNAL == 'KK') {
        noBukti = "KAS.0"+obj.ID_TRX_KAS;
      } else if (obj.KODE_JURNAL == 'JT' && obj.ID_TRX_KAS != null) {
        noBukti = "KRE.0"+obj.ID_TRX_KAS;
      } else if (obj.KODE_JURNAL == 'JT' && obj.IDPINJ_D != null) {
        noBukti = "KRE.0"+obj.IDPINJ_D;
      } else if (obj.KODE_JURNAL == 'AK' || obj.KODE_JURNAL == 'KR' || obj.KODE_JURNAL == 'RT') {
        noBukti = "KRE.0"+obj.IDPINJ_D;
      } else if (obj.ID_TRX_SIMP == null || obj.ID_TRX_KAS == null || obj.IDPINJ_D == null) {
        noBukti = obj.REFERENSI;
      }

      if (obj.DEBET != 0) {
        ket = obj.KETERANGAN,
        tgl = obj.TANGGAL
      }else{
        ket="",
        noBukti="",
        tgl = ""
      }

			tableData += "<tr>" +
				"<td>" + tgl + "</td>" +
				"<td>" + noBukti + "</td>" +
        "<td>" + obj.KODE_AKTIVA + "</td>" +
        "<td>" + obj.JENIS_TRANSAKSI + "</td>" +        
        "<td>" + ket + "</td>" +
				"<td>" + rupiah(obj.DEBET) + "</td>" +
				"<td>" + rupiah(obj.KREDIT) + "</td>" +
				"</tr>";
			numData++;

		// totalDebet = (eval(totalDebet) + eval(obj.DEBET));

		// TAMBAH
		if (obj.KODE_JURNAL == 'ST') { 
			totalStDebet = (eval(totalStDebet) + eval(obj.DEBET));
			totalStKredit = (eval(totalStKredit) + eval(obj.KREDIT));
		}
		if (obj.KODE_JURNAL == 'KM') { 
			totalKmDebet = (eval(totalKmDebet) + eval(obj.DEBET));
			totalKmKredit = (eval(totalKmKredit) + eval(obj.KREDIT));
		}
		if (obj.KODE_JURNAL == 'AR') { 
			totalArDebet = (eval(totalArDebet) + eval(obj.DEBET));
			totalArKredit = (eval(totalArKredit) + eval(obj.KREDIT));
		}
		if (obj.KODE_JURNAL == 'KR') { 
			totalKrDebet = (eval(totalKrDebet) + eval(obj.DEBET));
			totalKrKredit = (eval(totalKrKredit) + eval(obj.KREDIT));
		}
		if (obj.KODE_JURNAL == 'RT') { 
			totalRtDebet = (eval(totalRtDebet) + eval(obj.DEBET));
			totalRtKredit = (eval(totalRtKredit) + eval(obj.KREDIT));
		}
		// KURANG
		if (obj.KODE_JURNAL == 'PT') { 
			totalPtDebet = (eval(totalPtDebet) + eval(obj.DEBET));
			totalPtKredit = (eval(totalPtKredit) + eval(obj.KREDIT));
		}
		if (obj.KODE_JURNAL == 'KK') { 
			totalKkDebet = (eval(totalKkDebet) + eval(obj.DEBET));
			totalKkKredit = (eval(totalKkKredit) + eval(obj.KREDIT));
		}
		if (obj.KODE_JURNAL == 'JT') { 
			totalJtDebet = (eval(totalJtDebet) + eval(obj.DEBET));
			totalJtKredit = (eval(totalJtKredit) + eval(obj.KREDIT));
		}
 
		//}
	});
	
	grandtotaldebit  = eval(totalStDebet)+eval(totalKmDebet)+eval(totalArDebet)+eval(totalKrDebet)+eval(totalRtDebet)-eval(totalPtDebet)-eval(totalKkDebet)-eval(totalJtDebet);
	grandtotalkredit = eval(totalStKredit)+eval(totalKmKredit)+eval(totalArKredit)+eval(totalKrKredit)+eval(totalRtKredit)-eval(totalPtKredit)-eval(totalKkKredit)-eval(totalJtKredit);
	
footerTable	= "<tr>" + "<td colspan='5' class='text-center'><strong>TOTAL </strong></td>" + "<td>" + rupiah(grandtotaldebit) + "</td>" + "<td>" + rupiah(grandtotalkredit) + "</td>" + "</tr>"; $('#footer_jumlah').html(footerTable);
    return tableData;
}

function rupiah(data){
	if(data == "") {return data;}
	return number_format(data);
}

function number_format (number, decimals, dec_point, thousands_sep) {
  number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function (n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}