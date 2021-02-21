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
	$("#idtrx").val(obj.IDAKUN);
	$("#kode_aktiva").val(obj.KODE_AKTIVA);
	$('#akun option[value="'+ obj.AKUN +'"]').prop("selected", true);
	$("#jenis_transaksi").val(obj.JENIS_TRANSAKSI);
	$('#pemasukan option[value="'+ obj.PEMASUKAN +'"]').prop("selected", true);
	$('#pengeluaran option[value="'+ obj.PENGELUARAN +'"]').prop("selected", true);
	$('#laba_rugi option[value="'+ obj.LABA_RUGI +'"]').prop("selected", true);
	$('#aktif option[value="'+ obj.AKTIF +'"]').prop("selected", true);
	$('#idparent option[value="'+ obj.PARENT +'"]').prop("selected", true);
	$('#header option[value="'+ obj.HEADER +'"]').prop("selected", true);
	$("#tipe").val(obj.TIPE);
}

$('#mymodals').on('hidden.bs.modal', function () {
	$("#idtrx").val("");
	$("#kode_aktiva").val("");
	$("#tipe").val("");
	$("#jenis_transaksi").val("");
	$('#idparent option[value="0"]').prop("selected", true);
	$('#header option[value="0"]').prop("selected", true);
})