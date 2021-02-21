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
	
	var tableData = btnEdit =   image =   "";
	
	$.each(data, function(i, obj) {
		tableData   +=  "<tr>"+
							"<td><b>"+numData+"</b></td>"+
							"<td>"+obj.TGL_BAYAR+"</td>"+
							"<td>"+obj.TGL_PINJ+"</td>"+
							"<td>"+obj.NAMA_ANGGOTA+"</td>"+
							"<td>"+obj.TOTAL_TAGIHAN+"</td>"+
							"<td>"+obj.JML_ANGSURAN+"</td>"+
							"<td>"+obj.SISA_TAGIHAN+"</td>"+
							"<td>"+obj.ANGSURAN_KE + "/" + obj.LAMA_ANGSURAN +"</td>"+
							"<td>"+obj.SISA_ANGSURAN+"</td>"
						"</tr>";
		numData++;
	});
	
	return tableData;
}