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

    $.each(data, function(i, obj) {
		var tagihan	=	'<span class="f-left">Total </span> <span class="f-right"> ' + rupiah(obj.PINJ_TOTAL) + '</span><br>';
			tagihan	+=	'<span class="f-left">Dibayar </span> <span class="f-right"> ' + rupiah(obj.PINJ_DIBAYAR) + '</span><br>';
			tagihan	+=	'<strong><span class="f-left">Sisa </span> <span class="f-right">' + rupiah(obj.PINJ_SISA) + '</span></strong>';
		btnEdit = '<button class="mr-2 mb-2 btn btn-primary btn-sm" type="button" onclick="testsms(\''+obj.TELP+'\', \''+obj.JATUH_TEMPO+'\', \''+obj.NAMA+'\', \''+rupiah(obj.PINJ_RP_ANGSURAN)+ '\')"> Sms</button>';
        tableData += "<tr>" +
            "<td><b>" + numData + "</b></td>" +
            "<td>" + obj.NAMA + "</td>" +
            "<td>" + obj.TGL_PINJ + "</td>" +
            "<td>" + obj.JATUH_TEMPO + "</td>" +
            "<td>" + obj.TGL_PEMBAYARAN_ANGSURAN + "</td>" +
            "<td>" + rupiah(obj.PINJ_RP_ANGSURAN) + "</td>" +
            "<td>" + tagihan + "</td>" +
			"<td>"+btnEdit+"</td>" +
            "</tr>";
        numData++;
    });

    return tableData;
}
function testsms(nomer,tgl,nama,nominal)
{
	var pesan = 'Kpd Yth Bpk/Ibu '+nama+' angsuran anda sebesar Rp'+nominal+' akan jatuh tempo tgl : '+tgl+' mohon segera melakukan pembayaran, KSP.Wahyu Arta Sejahtera';
	 $.ajax({
        url: base_url + "sms/smsgateway/smskirim",
        type: "post",
        data: 'nomer='+nomer+'&pesan='+pesan ,
        success: function (response) {
			if(response) {
				alert(response);
			}
        },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        }
    });
	
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