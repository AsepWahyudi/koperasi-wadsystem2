function loaddata(idload){
	$().ready(function () {
		$("#mymodals").modal("show");
		var urldata	=	base_url + $('.' + idload).attr('var-url');
		$.ajax({
			type: "POST",
			url: urldata,
			data: 'data=',
			cache: false,
				success: function(msg){
					_parse_html(msg);
				}, error: function (result) {
					var teks = result['status'] + " - " + result['statusText'];
					alert(teks);
				}
		});
	});
}

function _parse_html(data){
	var obj = JSON.parse(data);
	$("#idtrx").val(obj.ID_JNS_KAS);
	$("#nama_kas").val(obj.NAMA_KAS);
	$('#aktif option[value="'+ obj.AKTIF +'"]').prop("selected", true);
	$('#auto_debet option[value="'+ obj.AUTO_DEBET +'"]').prop("selected", true);
	$('#tmpl_simpan option[value="'+ obj.TMPL_SIMPAN +'"]').prop("selected", true);
	$('#tmlp_penarikan option[value="'+ obj.TMLP_PENARIKAN +'"]').prop("selected", true);
	$('#tmpl_pinjaman option[value="'+ obj.TMPL_PINJAMAN +'"]').prop("selected", true);
	$('#tmpl_bayar option[value="'+ obj.TMPL_BAYAR +'"]').prop("selected", true);
	$('#tmpl_pemasukan option[value="'+ obj.TMPL_PEMASUKAN +'"]').prop("selected", true);
	$('#tmpl_pengeluaran option[value="'+ obj.TMPL_PENGELUARAN +'"]').prop("selected", true);
	$('#tmpl_transver option[value="'+ obj.TMPL_TRANSVER +'"]').prop("selected", true);
}

$('#mymodals').on('hidden.bs.modal', function () {
	
	$("#idtrx").val("");
	$("#nama_kas").val("");
	$('#aktif option[value="Y"]').prop("selected", true);
	$('#auto_debet option[value="0"]').prop("selected", true);
	$('#tmpl_simpan option[value="T"]').prop("selected", true);
	$('#tmlp_penarikan option[value="T"]').prop("selected", true);
	$('#tmpl_pinjaman option[value="T"]').prop("selected", true);
	$('#tmpl_bayar option[value="T"]').prop("selected", true);
	$('#tmpl_pemasukan option[value="T"]').prop("selected", true);
	$('#tmpl_pengeluaran option[value="T"]').prop("selected", true);
	$('#tmpl_transver option[value="T"]').prop("selected", true);
})