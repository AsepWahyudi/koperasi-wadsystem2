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
	noBukti = "";
  ket = "";
  tgl = "";
    $.each(data, function(i, obj) {
		//if( i > 0) {
			if (obj.KODE_JURNAL == 'ST' || obj.KODE_JURNAL == 'PT') {
        noBukti = "TAB.0"+obj.ID_TRX_SIMP;
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
		//}
    });

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