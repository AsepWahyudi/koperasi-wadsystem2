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
	
			
	total = saldosebelum = 0;
    $.each(data, function(i, obj) {
		if(i == 0) { 
			tableData += "<tr>" +
			"<td colspan='3' class='text-right'><b>Saldo Sebelumnya</b></td>" +
			"<td id>" + rupiah(obj.JUMLAH) + "</td>" +
			"</tr>";
			saldosebelum	=	eval(obj.JUMLAH);	
		}
		if(i > 0) {
			tableData += "<tr>" +
				"<td>" + numData + "</td>" +
				"<td>" + obj.NAMA_CABANG + "</td>" +
				"<td>" + obj.JENIS_TRANSAKSI + "</td>" +
				"<td>" + rupiah(obj.JUMLAH) + "</td>" +
				"</tr>";
			numData++;
			total	=	eval(total) + eval(obj.JUMLAH)
		}
		
    });
	tableData += "<tr>" +
			"<td colspan='3' class='text-right'><b>Jumlah</b></td>" +
			"<td><b>" + rupiah(total) + "</b></td>" +
			"</tr>";
	tableData += "<tr>" +
			"<td colspan='3' class='text-right'><b>Saldo</b></td>" +
			"<td><b>" + rupiah(eval(saldosebelum) + eval(total)) + "</b></td>" +
			"</tr>";
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