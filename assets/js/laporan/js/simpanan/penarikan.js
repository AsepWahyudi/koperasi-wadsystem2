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


function get_saldo(value){
	var idanggota	=	$("#idanggota").val();
		_saldo		=	eval(0);
	if(idanggota != "") {
		$.ajax({
			type: 'POST',
			url: base_url + 'simpanan/penarikan/get_total_saldo',
			data: 'idanggota=' + idanggota + '&idjenis=' + value,
			success:function(msg){
				if((msg == 0) || (msg == "")) {
					$("#jumlah").val(0);
					//$('#informationModalText').html('Saldo kosong');
					//$('#informationModal').modal('show');
				} else {
					_saldo	=	eval(msg);
					$("#jumlah").val(rupiah(_saldo));
				}
			},
			error:function(){
				$('#informationModalText').html('Gagal mengambil saldo');
				$('#informationModal').modal('show');
			}
		});
	} else {
		$('#informationModalText').html('Harap memilih nama anggota terlebih dahulu');
		$('#informationModal').modal('show');
	}
}


function renderTableData(numData, data){
	
	var tableData = btnEdit =   image =   "";
	
	$.each(data, function(i, obj) {
		var btnStruk		=   "<a href='"+base_url+"struk-penarikan/"+obj.ID_TRX_SIMP+"' class='btn btn-info btn-sm'><i class='fa fa-print'></i></a>";
		tableData   +=  "<tr>"+
							"<td><b>"+numData+"</b></td>"+
							"<td>"+obj.TGL_TRX+"</td>"+
							"<td>"+obj.NAMA_ANGGOTA+"</td>"+
							"<td>"+obj.JNS_SIMP+"</td>"+
							"<td>"+obj.JUMLAH+"</td>"+
							"<td>"+obj.KETERANGAN+"</td>"+
							"<td>"+obj.USERNAME+"</td>"+
							"<td>"+btnStruk+"</td>"+
						"</tr>";
		numData++;
	});
	
	return tableData;
}

$('#formPenarikan').validator().on('submit', function (e) {
  if (e.isDefaultPrevented()) {
    $('#informationModalText').html('Beberapa data belum dimasukkan, harap lengkapi data!');
	$('#informationModal').modal('show');
  } else {
	var jmlpenarikan	=	eval($('#jumlah').val().replace(/\,/g, ''));
	
	if(eval(_saldo) < eval(_saldo_mengendap)) { 
		$('#informationModalText').html("Saldo tidak mencukupi");
		$('#informationModal').modal('show');
		$('#jumlah').focus();
		return false;
	}
	
	var _saldo_baru	=	(eval(_saldo) - eval(_saldo_mengendap));
	if((jmlpenarikan == "") || (jmlpenarikan <= 0)) { 
		$('#informationModalText').html("Jumlah penarikan tidak sesuai");
		$('#informationModal').modal('show');
		$('#jumlah').focus();
		return false;
	} 
	if(eval(jmlpenarikan) > eval(_saldo_baru) ) { 
		$('#informationModalText').html("Jumlah penarikan melebihi saldo");
		$('#informationModal').modal('show');
		$('#jumlah').val(rupiah(_saldo));
		$('#jumlah').focus();
		return false;
	} 
	
	$('#jumlah').val(jmlpenarikan);
	var dataPost	=   $("#formPenarikan").serializeArray(),
		target		=	$("#formPenarikan").attr("action");
	$.ajax({
		type: 'POST',
		url: base_url+target,
		data: dataPost,
		dataType: 'json',
		beforeSend:function(){
			$("#formPenarikan :input").attr("disabled", true);
		},
		success:function(apiRes){
			$(location).attr('href', base_url + 'penarikan-tunai');
			/*$("#formSetoran :input").attr("disabled", false);
			if(apiRes.status == 200){
				$(location).attr('href', base_url + 'setoran-tunai');
			} else {
				$('#informationModalText').html(apiRes.msg);
				$('#informationModal').modal('show');
			}*/
		},
		error:function(){
			$("#formPenarikan :input").attr("disabled", false);
		}
	});
	return false;
  }
});
