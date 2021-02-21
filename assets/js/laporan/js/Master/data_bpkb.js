
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
	$("#idbpkb").val(obj.IDBPKB);
	$("#namas").val(obj.NAMAS);
	$("#nos").val(obj.NOS);
	$("#provinsi").val(obj.PROVINSI);
	
	$('#approval option[value="'+ obj.APPROVAL +'"]').prop("selected", true);
}

$('#mymodals').on('hidden.bs.modal', function () {
	$("#idbpkb").val("");
	$("#namas").val("");
	$("#nos").val("");
	$("#provinsi").val("");
	$("#approval").val("");
})