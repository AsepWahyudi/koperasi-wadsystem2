$(document).ready(function() {
    if (action == "view") {
        ajaxDataTable();
    }
});

$("#filterForm").submit(function(e) {
    e.preventDefault();
    return false;
});

function renderTableData(numData, data){
	
	var tableData = btnEdit =   image =   "";
	
	$.each(data, function(i, obj) {
		var btnDetail		=   "<a href='"+base_url+"pinjaman-find?id="+obj.IDPINJM_H+"&r=pl' class='btn btn-info btn-sm'><i class='fa fa-search'></i></a>";
		
		tableData   +=  "<tr>"+
							"<td><b>"+numData+"</b></td>"+
							"<td>"+obj.NAMA_ANGGOTA+"</td>"+
							"<td>"+obj.LUNAS+"</td>"+
							"<td>"+obj.TGL_PINJ+"</td>"+
							"<td>"+obj.JATUH_TEMPO+"</td>"+
							"<td>"+obj.LAMA_ANGSURAN+" bulan</td>"+
							"<td>"+obj.TOTAL_TAGIHAN+"</td>"+
							"<td>"+obj.JML_DENDA+"</td>"+
							"<td>"+obj.SUDAH_DIBAYAR+"</td>"+
							"<td class='text-center print-hide'>"+btnDetail+"</td>"+
						"</tr>";
		numData++;
	});
	
	return tableData;
}