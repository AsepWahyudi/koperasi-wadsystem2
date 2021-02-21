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
	
	var tableData 	= btnEdit = image = "";
	tot_setoran		= tot_penarikan = tot_jumlah = 0;
    $.each(data, function(i, obj) {
		tot_setoran		= eval(tot_setoran) + eval(obj.SETORAN);
		tot_penarikan	= eval(tot_penarikan) + eval(obj.PENARIKAN);
		tableData += "<tr>" +
			"<td><b>" + numData + "</b></td>" +
			"<td>" + obj.JNS_SIMP + "</td>" +
			"<td>" + rupiah(obj.SETORAN) + "</td>" +
			"<td>" + rupiah(obj.PENARIKAN) + "</td>" +
			"<td>" + rupiah(eval(obj.SETORAN) - eval(obj.PENARIKAN)) + "</td>" +
			"</tr>";
		numData++;
    });
	tableData += "<tr>" +
			"<td colspan='2' class='text-right'><b>Jumlah Total : </b> &nbsp; </td>" +
			"<td><b>" + rupiah(tot_setoran) + "</b></td>" +
			"<td><b>" + rupiah(tot_penarikan) + "</b></td>" +
			"<td><b>" + rupiah(eval(tot_setoran) - eval(tot_penarikan)) + "</b></td>" +
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