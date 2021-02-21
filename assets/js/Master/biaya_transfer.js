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
	$("#ID_BIAYA_TRF_KAS").val(obj.ID_BIAYA_TRF_KAS);
	$("#NAMA_BIAYA").val(obj.NAMA_BIAYA);
	$("#BIAYA_TRF").val(obj.BIAYA_TRF);
	$ 
}
 