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
	pinj_lunas	= pinj_belum = tot_jumlah = tot_pinjam_pokok = tot_tagihan = tot_denda = tot_dibayar = total_sisa = 0;
	
    $.each(data, function(i, obj) {
		pinj_lunas	= (obj.LUNAS == 'Lunas' ? obj.JML_PINJAM : pinj_lunas);
		pinj_belum	= (obj.LUNAS == 'Belum' ? obj.JML_PINJAM : pinj_belum);
		
		tot_jumlah	= eval(tot_jumlah) + eval(obj.JUMLAH);
		tot_pinjam_pokok	= eval(tot_pinjam_pokok) + eval(obj.PINJAMAN_POKOK);
		tot_tagihan	= eval(tot_tagihan) + eval(obj.TAGIHAN);
		tot_denda	= eval(tot_denda) + eval(obj.DENDA);	
		tot_dibayar	= eval(tot_dibayar) + eval(obj.DIBAYAR);	
		total_sisa	= eval(total_sisa) + eval(obj.SISATAGIHAN);	
		
		numData++;
    });
	
	$('#jml_pinj').html(eval(pinj_lunas) + eval(pinj_belum));
	$('#pinj_lunas').html(eval(pinj_lunas));
	$('#pinj_belum').html(eval(pinj_belum));
	tableData += "<tr>" +
		"<td><b>1</b></td>" +
		"<td>Pokok Pinjaman</td>" +
		"<td>" + rupiah(tot_jumlah) + "</td>" +  
		"</tr>";
	tableData += "<tr>" +
		"<td><b>2</b></td>" +
		"<td>Tagihan Pinjaman</td>" +
		"<td>" + rupiah(tot_pinjam_pokok) + "</td>" +
		// "<td>" + rupiah(obj.TAGIHAN) + "</td>" +
		// "<td>" + rupiah(eval(tot_dibayar) + eval(tot_dibayar)) + "</td>" +
		"</tr>";
	tableData += "<tr>" +
		"<td><b>3</b></td>" +
		"<td>Tagihan Denda</td>" +
		"<td>" + rupiah(tot_denda) + "</td>" +
		"</tr>";
	
	tableData += "<tr>" +
			"<td colspan='2' class='text-right'><b>Jumlah Tagihan + Denda</b> &nbsp; </td>" +
			// "<td><b>" + rupiah(eval(tot_tagihan) + eval(tot_denda)) + "</b></td>" +
			"<td><b>" + rupiah(eval(tot_pinjam_pokok)+eval(tot_denda)) + "</b></td>" +
			"</tr>";
			
	tableData += "<tr>" +
		"<td><b>4</b></td>" +
		"<td>Tagihan Sudah Dibayar</td>" +
		"<td>" + rupiah(tot_dibayar) + "</td>" +
		"</tr>";
	
	
	if(total_sisa < 0)
	{
		tableData += "<tr>" +
			"<td colspan='2' class='text-right'><b>Sisa Tagihan</b> &nbsp; </td>" + 
			"<td><b>0</b></td>" +
			"</tr>";
		  
	}
	else
	{
		tableData += "<tr>" +
			"<td colspan='2' class='text-right'><b>Sisa Tagihan</b> &nbsp; </td>" +
			// "<td><b>" + rupiah(eval(tot_tagihan) - eval(tot_dibayar)) + "</b></td>" +
			"<td><b>" + rupiah(total_sisa) + "</b></td>" +
			"</tr>";
	}
	

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