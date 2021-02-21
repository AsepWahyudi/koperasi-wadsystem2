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
        aktif 	= (obj.AKTIF == 'Y' ? 'Aktif' : (obj.AKTIF == 'N' ? 'Tidak Aktif' : 'Menunggu Persetujuan'));

        image 	= "<img src='" + base_url + "uploads/identitas/" + file_pic + "' width='50'/>";
        var btnEdit = "<a href='" + base_url + "list-view-anggota/" + obj.IDANGGOTA + "' class='btn btn-warning btn-sm'><i class='fa fa-edit'></i></a>";
        tableData += "<tr>" +
            "<td><b>" + numData + "</b></td>" +
            "<td>" + image + "</td>" +
            "<td>" + obj.NAMACABANG + "</td>" +
            "<td>" + obj.NAMA + "</td>" +
            "<td>" + obj.JK + "</td>" +
            "<td>" + obj.TGL_LAHIR + "</td>" +
            "<td>" + obj.USIA + "</td>" +
            "<td>" + obj.ALAMAT + "</td>" +
            "<td>" + obj.TGL_DAFTAR + "</td>" +
            "<td>" + aktif + "</td>" +
            "<td class='text-center print-hide'>" + btnEdit + "</td>" +
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