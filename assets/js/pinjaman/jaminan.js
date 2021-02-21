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

 
	 
function renderTableData(numData, data){
	
	var tableData = btnEdit = image =   "";
	
	$.each(data, function(i, obj) {
		var btnDetail = "<a href='"+base_url+"pinjaman-find?id="+obj.IDPINJM_H+"&r=pd' class='btn btn-success btn-sm'><i class='fa fa-search'></i></a>";
		
		var btnedit = "<a href='"+base_url+"editjaminanpinjaman/"+obj.IDANGGUNAN+"' class='btn btn-success btn-sm'><i class='fa fa-edit'></i></a>";
		var btndownload = "<a href='"+base_url+"downloadanggunan//"+obj.IDANGGUNAN+"' class='btn btn-info btn-sm'><i class='fa fa-download'></i></a>";
		// var btnupload = "<a href='"+base_url+"downloadanggunan/"+obj.IDANGGUNAN+"' class='btn btn-warning btn-sm'><i class='fa fa-upload'></i></a>";
		
		var btnupload = "";
		btnupload += "<form id='form"+obj.IDANGGUNAN+"' method='post' action='otherpage.php' enctype='multipart/form-data'>";
		btnupload += "<input type='hidden' name='id' value='"+obj.IDANGGUNAN+"'>";
		btnupload += "<button class='btn btn-secondary btn-rounded uploadbuktisetoran btn-sm' type='button' data-id='"+obj.IDANGGUNAN+"' id='uploadbuktisetoran"+obj.IDANGGUNAN+"'>Upload</button>";
			
		btnupload += "<center>";
		btnupload += "<img src='"+base_url+"img/loading.gif' id='loadingtellersetoran"+obj.IDANGGUNAN+"' style='display:none;'>";
		btnupload += "</center>";
				
		btnupload += "<input class='form-control buktitellersetoran' type='file' name='buktitellersetoran"+obj.IDANGGUNAN+"' id='buktitellersetoran"+obj.IDANGGUNAN+"' style='display:none;'>";
		btnupload += "</form>"; 
											
		// btnupload += "<input class='form-control buktitellersetoran' type='file' name='buktitellersetoran"+obj.IDANGGUNAN+"' id='buktitellersetoran"+obj.IDANGGUNAN+"' style='display:none;'>";

		// var btnupload = "<a class='btn btn-warning btn-sm' href='https://www.google.co.id/maps/dir//"+obj.lat+","+obj.lng+"/@"+obj.lat+","+obj.lng+",19z' target='_blank'><i class='fa fa-map-marker'></i></a>";
 
		tableData += "<tr>"+
						"<td class='width1'><b>"+numData+"</b></td>"+
						"<td class='width1'>"+obj.TGL_PINJ+"</td>"+
						"<td class='width1'>"+obj.KODE_ANGGOTA+"</td>"+
						"<td class='width1'>"+obj.NAMA_ANGGOTA+"</td>"+
						"<td class='width1'>"+obj.NAMAJAMINAN+"</td>"+
						"<td class='width1'>"+obj.NO_JAMINAN+"</td>"+ 
						"<td class='width1'>"+obj.STATUSANGGUNAN+"</td>"+  
						// "<td class='text-center print-hide'>" + btnDetail +" "+ btnCetak + " "+ btnMap + "</td>" +
						// "<td class='text-center print-hide'>" + btndownload + " "+ btnupload + "</td>" +
						"<td class='text-center print-hide'>" + btnedit + " "+ btndownload + "</td>" +
						// "<td class='text-center print-hide'>" + btndownload + "</td>" +
					"</tr>";
		numData++;
	});
	
	return tableData;
}

$('#formPinjaman').validator().on('submit', function (e) {
  if (e.isDefaultPrevented()) {
    $('#informationModalText').html('Beberapa data belum dimasukkan, harap lengkapi data!');
	$('#informationModal').modal('show');
  } else {
    var dataPost	=   $("#formPinjaman").serializeArray(),
		target		=	$("#formPinjaman").attr("action");
	$.ajax({
		type: 'POST',
		url: base_url+target,
		data: dataPost,
		dataType: 'json',
		beforeSend:function(){
			$("#formPinjaman :input").attr("disabled", true);
		},
		success:function(apiRes){
			$(location).attr('href', base_url + 'pinjaman-data');
		},
		error:function(){
			$("#formPinjaman :input").attr("disabled", false);
		}
	});
	return false;
  }
});


function getFormAgs(){
	$().ready(function () {
		$("#mymodals").modal("show");
		var urldata	=	base_url + $(this).attr('var-url');
		$.ajax({
			type: "POST",
			url: urldata,
			data: 'data=',
			cache: false,
				success: function(msg){
					console.log(msg);
				}, error: function (result) {
					var teks = result['status'] + " - " + result['statusText'];
					$('#informationModalText').html(teks);
					$('#informationModal').modal('show');
				}
		});
	});
}