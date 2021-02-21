
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
	$("#idtrx").val(obj.IDJENIS_SIMP);
	$("#jns_simp").val(obj.JNS_SIMP);
	$("#jumlah").val(obj.JUMLAH);
	$('#auto_debet option[value="'+ obj.AUTO_DEBET +'"]').prop("selected", true);
	$('#tampil option[value="'+ obj.TAMPIL +'"]').prop("selected", true);
	$('#idakun option[value="'+ obj.IDAKUN +'"]').prop("selected", true);
}

$('#mymodals').on('hidden.bs.modal', function () {
	$("#idtrx").val("");
	$("#jns_simp").val("");
	$("#jumlah").val("");
})