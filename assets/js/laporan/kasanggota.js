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

        var file_pic = obj.FILE_PIC == "" ? "noimage.jpg" : obj.FILE_PIC,
        	image = "<img src='" + base_url + "uploads/identitas/" + file_pic + "' width='50'/>";
		
		var identitas	=	'ID Anggota : ' + obj.KODE_ANGGOTA + '<br>';
			identitas	+=	'Nama : ' + obj.NAMA + '<br>';
			identitas	+=	'L/P &nbsp;: ' + obj.JK + '<br>';
			identitas	+=	'Jabatan : ' + (obj.JABATAN == '2' ? 'Anggota' : 'Pengurus') + '<br>';
			identitas	+=	'Alamat  : ' + obj.ALAMAT + '<br>';
			identitas	+=	'Telp. : ' + obj.TELP;
		
		var arr_jenis	=	obj.JENIS_SIMPANAN.split(',');
		var simpanan	=	'';
		var total_simpanan	=	0;
		for(var i = 0; i < arr_jenis.length; i++) {
			var nama_jenis		=	arr_jenis[i].split('|');
			var saldo_simpan	=	0;
			
			if(obj.SALDO_SIMPANAN != '' && obj.SALDO_SIMPANAN != null) {
				var arr_simpan		=	obj.SALDO_SIMPANAN.split(',');
				for(var j=0; j<arr_simpan.length; j++){
					var arr_saldo	 =	arr_simpan[j].split('|');
					if(nama_jenis[0] == arr_saldo[0]) {
						saldo_simpan =	arr_saldo[1];
					}
				}
			}
			simpanan	+=	'<span class="f-left">' + nama_jenis[1] + '</span> <span class="f-right">' + rupiah(saldo_simpan) + '</span><br>';
			total_simpanan	=	eval(total_simpanan) + eval(saldo_simpan);
		}
		simpanan	+=	'<strong><span class="f-left">Jumlah Simpanan</span> <span class="f-right">' + rupiah(total_simpanan) + '</span></strong>';
		
		var tagihan	=	'<span class="f-left">Pokok Pinjaman </span> <span class="f-right"> ' + rupiah(obj.PINJ_POKOK) + '</span><br>';
			tagihan	+=	'<span class="f-left">Tagihan </span> <span class="f-right"> ' + rupiah(obj.PINJ_TOTAL) + '</span><br>';
			tagihan	+=	'<span class="f-left">Dibayar </span> <span class="f-right"> ' + rupiah(obj.PINJ_DIBAYAR) + '</span><br>';
			if(obj.BIAYARESET > 0)
			{ 
				tagihan	+=	'<strong><span class="f-left">Sisa Tagihan</span> <span class="f-right"> 0 </span></strong><br>';
			}
			else
			{
				tagihan	+=	'<strong><span class="f-left">Sisa Tagihan</span> <span class="f-right">' + rupiah(obj.SISATAGIHAN) + '</span></strong><br>';
			} 
			tagihan	+=	'<strong><span class="f-left">Pembulatan Selisih</span> <span class="f-right">' + rupiah(obj.SELISIHPINJAMAN) + '</span></strong><br>';
			tagihan	+=	'<strong><span class="f-left">Biaya Reset</span> <span class="f-right">' + rupiah(obj.BIAYARESET) + '</span></strong><br>';
			tagihan	+=	'<strong><span class="f-left">Biaya Kolektor</span> <span class="f-right">' + rupiah(obj.BIAYAKOLEKTOR) + '</span></strong>';
		
		var keterangan	=	'<span class="f-left">Jumlah Pinjaman </span> <span class="f-right"> ' + obj.JML_PINJAM + '</span><br>';
			keterangan	+=	'<span class="f-left">Pinjaman Lunas </span> <span class="f-right"> ' + (obj.JML_PINJAM - obj.ISCREDIT) + '</span><br>';
			/* keterangan	+=	'<span class="f-left">Pembayaran </span> <span class="f-right"> ' + (obj.JML_PINJAM > 0 ? (obj.ISCREDIT == '1' ? (obj.TGL_NOW > obj.TGL_TEMPO ? '<span class="macet">Macet</span>' : '<span class="lancar">Lancar</span>') : '<span class="lancar">Lancar</span>') : '-' ) + '</span><br>'; */
			// keterangan	+=	'<span class="f-left">Pembayaran </span> <span class="f-right"> ' + (obj.IS_RESET == '0' ? '<span class="lancar">Lancar</span>' : (obj.IS_RESET == '1' ? '<span class="meragukan">Meragukan</span>' : (obj.IS_RESET == '3' ? '<span class="macet">Macet</span>' : '-'))) + '</span><br>';
			
			keterangan	+=	'<strong><span class="f-left">Jatuh Tempo</span> <span class="f-right">' + (obj.JATUH_TEMPO == null ? '-' : obj.JATUH_TEMPO) + '</span></strong>';
			
        tableData += "<tr>" +
            "<td><b>" + numData + "</b></td>" +
            "<td>" + image + "</td>" +
            "<td>" + identitas + "</td>" +
            "<td>" + simpanan + "</td>" +
            "<td>" + tagihan + "</td>" +
            "<td>" + keterangan + "</td>" +
            "</tr>";
        numData++;
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