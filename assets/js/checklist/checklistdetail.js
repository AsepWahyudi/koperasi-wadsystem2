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
	$("#jumlah").val(spl[1]);
}


function renderTableData(numData, data){
	
	var tableData = btnEdit =   image =   "";
	var jumlah = 0;
	var no = 1;
	$.each(data, function(i, obj) {
		// 
		url = 'https://localhost/wadsystem/edit-setorantunai/'+obj.ID_TRX_SIMP;
		urls = 'https://localhost/wadsystem/delete-setorantunai/'+obj.ID_TRX_SIMP;
		tableData += "<tr>"+
						"<td><b>"+no+"</b></td>"+
						"<td>"+obj.TGL+"</td>"+
						"<td>"+obj.NAMA_PENYETOR+"</td>"+
						"<td>"+rupiah(obj.JUMLAH)+"</td>"+
						"<td>"+obj.JENIS_TRANSAKSI+"</td>"+
						"<td>"+obj.KETERANGAN+"</td>"+
						"<td>"+obj.NAMACABANG+"</td>"+
						"<td> <a href='"+url+"' class='btn btn-warning btn-sm'><i class='fa fa-edit'></i></a><a href='"+urls+"' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i></a></td>"+  
						"</tr>";
		jumlah += eval(obj.JUMLAH);
		no++;
	});
	tableData   +=  "<tr>"+
						"<td colspan='3' style='text-align: right;' ><b>Jumlah</b></td>"+
						"<td>"+rupiah(jumlah)+"</td>"+  
					"</tr>";
					
	
	return tableData;
}

$('#formSetoran').validator().on('submit', function (e) {
  if (e.isDefaultPrevented()) {
    $('#informationModalText').html('Beberapa data belum dimasukkan, harap lengkapi data!');
	$('#informationModal').modal('show');
  } else {
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
