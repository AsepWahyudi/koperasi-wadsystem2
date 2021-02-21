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

    var tableData = btnEdit = image = btnView = "";

    $.each(data, function(i, obj) {

        var file_pic = obj.FILE_PIC == "" ? "noimage.jpg" : obj.FILE_PIC, 
            aktif    = (obj.AKTIF == 'Y' ? 'Aktif' : (obj.AKTIF == 'N' ? 'Tidak Aktif' : 'Menunggu Persetujuan'));


        image = "<a href='" + base_url + "uploads/identitas/" + file_pic + "'><img alt='photo' src='" + base_url + "uploads/identitas/" + file_pic + "' class='img-responsive'></a>";
        btnEdit = "<a href='" + base_url + "edit-anggota/" + obj.IDANGGOTA + "' class='btn btn-warning btn-sm'><i class='fa fa-edit'></i></a>";
        btnView = "<a href='" + base_url + "view-anggota/" + obj.IDANGGOTA + "' class='btn btn-info btn-sm'><i class='fa fa-eye'></i></a>";
        tableData += "<tr>" +
            "<td><b>" + numData + "</b></td>" +
            "<td class='text-center col-md-1 gallery-wrap'>" + image + "</td>" +
            // "<td>" + obj.KODEPUSAT+"."+obj.KODECABANG+"."+ obj.NO_ANGGOTA +"</td>" +
            "<td>" + obj.KODE_ANGGOTA +"</td>" +
            "<td>" + obj.NAMA + "</td>" +
            "<td>" + obj.JK + "</td>" +
            "<td>" + obj.TGL_LAHIR + "</td>" +
            "<td>" + obj.USIA + "</td>" +
            "<td>" + obj.ALAMAT + "</td>" +
            "<td>" + obj.TGL_DAFTAR + "</td>" +
            "<td>" + aktif + "</td>" +
            "<td>" + btnEdit +" "+ btnView + "</td>" +
            "</tr>";
        numData++;
    });

    return tableData;
}


$('#formDataAnggota').validator().on('submit', function(e) {
    if (e.isDefaultPrevented()) {
        //$('#informationModalText').html('Beberapa data belum dimasukkan, harap lengkapi data!');
        //$('#informationModal').modal('show');
    } else {
        var dataPost = $("#formDataAnggota").serializeArray(),
            target = $("#formDataAnggota").attr("action");
        $.ajax({
            type: 'POST',
            url: base_url + target,
            data: dataPost,
            dataType: 'json',
            beforeSend: function() {
                $("#formDataAnggota :input").attr("disabled", true);
            },
            success: function(apiRes) {
                $("#formDataAnggota :input").attr("disabled", false);
                if (apiRes.status == 200) {
                    $(location).attr('href', base_url + 'anggota');
                } else {
                    $('#informationModalText').html(apiRes.msg);
                    $('#informationModal').modal('show');

                }
            },
            error: function() {
                $("#formDataAnggota :input").attr("disabled", false);
            }
        });
        return false;
    }
}); 