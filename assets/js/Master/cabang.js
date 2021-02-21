
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
	$("#idcabang").val(obj.IDCABANG);
	$("#nama").val(obj.NAMA);
	$("#alamat").val(obj.ALAMAT);
	$("#kota").val(obj.KOTA); 
	$("#telp").val(obj.TELP); 
	$("#namaksp").val(obj.NAMAKSP); 
	$("#email").val(obj.EMAIL); 
	$("#web").val(obj.WEB); 
	$("#kodecabang").val(obj.KODECABANG); 
}

$('#mymodals').on('hidden.bs.modal', function () {
	$("#idcabang").val("");
	$("#nama").val("");
	$("#alamat").val("");
	$("#kota").val(""); 
	$("#telp").val(""); 
	$("#namaksp").val(""); 
	$("#email").val(""); 
	$("#web").val(""); 
})