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
		var btnDetail		=   "<a href='"+base_url+"pinjaman-find?id="+obj.IDPINJM_H+"&r=ag' class='btn btn-success btn-sm'><i class='fa fa-money'></i> bayar</a>";
		tableData   +=  "<tr>"+
							"<td><b>"+numData+"</b></td>"+
							"<td>"+obj.TGL_PINJ+"</td>"+
							"<td>"+obj.NAMA_ANGGOTA+"</td>"+
							"<td>"+obj.JUMLAH+"</td>"+
							"<td>"+obj.LAMA_ANGSURAN+" bulan</td>"+
							"<td>"+obj.ANGSURAN_DASAR+"</td>"+
							"<td>"+obj.MARGIN_DASAR+"</td>"+
							"<td>"+obj.ANGSURAN_PERBULAN+"</td>"+
							"<td class='text-center print-hide'>"+btnDetail+"</td>"+
						"</tr>";
		numData++;
	});
	
	return tableData;
}