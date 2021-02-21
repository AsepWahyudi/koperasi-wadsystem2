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

        var file_pic = obj.FILE_PIC == "" ? "noimage.jpg" : obj.FILE_PIC,
            aktif 	= (obj.AKTIF == 'Y' ? 'Aktif' : (obj.AKTIF == 'N' ? 'Tidak Aktif' : ''));
        image = "<img src='" + base_url + "uploads/identitas/" + file_pic + "' width='50'/>";
        tableData += "<tr>" +
            "<td><b>" + numData + "</b></td>" +
            "<td>" + obj.KODE_ANGGOTA + "</td>" +
            "<td>" + obj.NAMA + "</td>" +
            "<td>" + obj.JK + "</td>" +
            "<td>" + obj.ALAMAT + "</td>" +
            "<td>" + obj.TGL_DAFTAR + "</td>" +
            "<td>" + aktif + "</td>" +
            "<td>" + image + "</td>" +
            "</tr>";
        numData++;
    });

    return tableData;
}