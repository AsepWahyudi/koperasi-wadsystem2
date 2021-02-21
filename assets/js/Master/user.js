
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
	$("#iduser").val(obj.IDUSER);
	$("#nama").val(obj.NAMA);
	$("#uname").val(obj.USERNAME);
	$("#passwd").val(obj.PASSWORD);
	$("#kodecabang").val(obj.KODECABANG);
	$('#aktif option[value="'+ obj.AKTIF +'"]').prop("selected", true);
	$('#level option[value="'+ obj.LEVEL +'"]').prop("selected", true);
	$('#approval option[value="'+ obj.APPROVAL +'"]').prop("selected", true);
}

$('#mymodals').on('hidden.bs.modal', function () {
	$("#iduser").val("");
	$("#nama").val("");
	$("#uname").val("");
	$("#uname").val("");
	$("#passwd").val("");
})