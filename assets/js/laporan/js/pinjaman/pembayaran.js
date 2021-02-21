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

function renderTableData(numData, data) {

    var tableData = btnEdit = image = "";

    $.each(data, function(i, obj) {
		var keterangan	=	(obj.IS_RESET == '0' ? '<span class="badge b-rounded x-success">Lancar</span>' : 
                            (obj.IS_RESET == '1' ? '<span class="badge b-rounded x-warning">Meragukan</span>' : 
                            (obj.IS_RESET == '3' ? '<span class="badge b-rounded x-danger">Macet</span>' : 
                            (obj.IS_RESET == '2' ? '<span class="badge b-rounded x-danger">Buruk</span>' : '<span class="badge b-rounded x-danger">Macet</span>'))));
        
		tableData += "<tr>" +
            "<td><b>" + numData + "</b></td>" +
            "<td>" + obj.NAMA_ANGGOTA + "</td>" +
            "<td>" + obj.TGL_PINJ + "</td>" +
            "<td>" + obj.JATUH_TEMPO + "</td>" +
            "<td>" + rupiah(obj.PINJ_TOTAL) + "</td>" +
            "<td>" + rupiah(obj.PINJ_DIBAYAR) + "</td>" +
            "<td>" + rupiah(obj.PINJ_SISA) + "</td>" +
            "<td>" + keterangan + "</td>" +
            "</tr>";
        numData++;
    });

    return tableData;
}