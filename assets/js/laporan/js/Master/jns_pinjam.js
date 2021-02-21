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
	$("#idtrx").val(obj.IDJNS_PINJ);
	$("#jns_pinj").val(obj.JNS_PINJ);
	$("#tipe").val(obj.TIPE);
	$("#bagihasil").val(obj.BAGIHASIL);
	$("#biayaadmin").val(obj.BIAYAADMIN);
	$("#rekom_pinj").val(obj.REKOM_PINJ);
	$("#asuransi").val(obj.ASURANSI);
	$("#ket").val(obj.KET);
	$('#idakun option[value="'+ obj.IDAKUN +'"]').prop("selected", true);
}

$('#mymodals').on('hidden.bs.modal', function () {
	$("#idtrx").val("");
	$("#jns_pinj").val("");
	$("#tipe").val("");
	$("#bagihasil").val("");
	$("#biayaadmin").val("0");
	$("#rekom_pinj").val("0");
	$("#asuransi").val("0");
	$("#ket").val("");
})