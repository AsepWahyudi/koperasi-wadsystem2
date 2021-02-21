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
	saldo	=	0;
    $.each(data, function(i, obj) {
		//if( i > 0) {
			saldo	=	saldo + (obj.DK == 'D' ? (eval(1) * eval(obj.JUMLAH)) : (eval(-1) * eval(obj.JUMLAH)));
			tableData += "<tr>" +
				"<td><b>" + numData + "</b></td>" +
				"<td>" + obj.TANGGAL + "</td>" +
				"<td>" + obj.JENIS_TRANSAKSI + "</td>" +
				"<td>" + (obj.DARI_KAS == null ? '-' : obj.DARI_KAS) + "</td>" +
				"<td>" + (obj.UNTUK_KAS == null ? '-' : obj.UNTUK_KAS) + "</td>" +
				"<td>" + rupiah(obj.DK == 'D' ? obj.JUMLAH : 0) + "</td>" +
				"<td>" + rupiah(obj.DK == 'K' ? obj.JUMLAH : 0) + "</td>" +
				"<td>" + rupiah(saldo) + "</td>" +
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