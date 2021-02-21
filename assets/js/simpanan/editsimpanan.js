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
	$("#editjumlah").val(rupiah(spl[1]));
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

$('#editsetoran').on('click', function () {
	
 
    var dataPost = $("#formSetoran").serializeArray();
		 
	$.ajax({
		type: 'POST',
		url: 'editsetoran',
		data: dataPost,
		dataType: 'json',
		beforeSend:function(){
			$("#formSetoran :input").attr("disabled", true);
		},
		success:function(apiRes){
			// $(location).attr('href', base_url + 'setoran-tunai');
			location.reload();
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
});
