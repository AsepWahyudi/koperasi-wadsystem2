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

function jenis_simpanan(value){
	var spl	=	value.split("|");
	$("#jumlah").val(rupiah(spl[1]));
}


function renderTableData(numData, data){
	
	var tableData = btnEdit =   image =   "";
	
	$.each(data, function(i, obj) {
		var btnStruk		=   "<a href='"+base_url+"struk-simpanan/"+obj.ID_TRX_SIMP+"' class='btn btn-info btn-sm'><i class='fa fa-print'></i></a>";
		tableData   +=  "<tr>"+
							"<td><b>"+numData+"</b></td>"+
							"<td>"+obj.TGL_TRX+"</td>"+
							"<td>"+obj.NAMA_PENYETOR+"</td>"+
							"<td>"+obj.ALAMAT+"</td>"+
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

$('#formSetoran').validator().on('submit', function (e) {
  if (e.isDefaultPrevented()) {
    $('#informationModalText').html('Beberapa data belum dimasukkan, harap lengkapi data!');
	$('#informationModal').modal('show');
  } else {
	
	var _jumlah	=	eval($('#jumlah').val().replace(/\,/g, ''));
	$('#jumlah').val(_jumlah);
    var dataPost	=   $("#formSetoran").serializeArray(),
		target		=	$("#formSetoran").attr("action");
	$.ajax({
		type: 'POST',
		url: base_url+target,
		data: dataPost,
		dataType: 'json',
		beforeSend:function(){
			$("#formSetoran :input").attr("disabled", true);
		},
		success:function(apiRes){
			$(location).attr('href', base_url + 'setoran-tunai');
			/*$("#formSetoran :input").attr("disabled", false);
			if(apiRes.status == 200){
				$(location).attr('href', base_url + 'setoran-tunai');
			} else {
				$('#informationModalText').html(apiRes.msg);
				$('#informationModal').modal('show');
			}*/
		},
		error:function(){
			$("#formSetoran :input").attr("disabled", false);
		}
	});
	return false;
  }
});
