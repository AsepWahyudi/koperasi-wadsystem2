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

        image = "<a href='" + base_url + "uploads/identitas/" + file_pic + "' class='popimages'><img src='" + base_url + "uploads/identitas/" + file_pic + "' width='50'/></a>";
        btnEdit = "<a href='" + base_url + "edit-sertifikat/" + obj.IDSERTIFIKAT + "'><i class='os-icon os-icon-ui-49'></i></a>";
        tableData += "<tr>" +
            "<td><b>" + numData + "</b></td>" +
            "<td>" + image + "</td>" +
            "<td>" + obj.KODEPUSAT+"."+obj.KODECABANG+"."+ obj.NOSE +"</td>" +
            "<td>" + obj.NAMA + "</td>" +
            "<td>" + obj.JALUR + "</td>" +
            "<td>" + obj.TGL_LAHIR + "</td>" +
            "<td>" + obj.LUAS + "</td>" +
            "<td>" + obj.TAKSIR + "</td>" +
            
            "<td>" + aktif + "</td>" +
            "<td>" + btnEdit + "</td>" +
            "</tr>";
        numData++;
    });

    return tableData;
}


$('#formDataSertifikat').validator().on('submit', function(e) {
    if (e.isDefaultPrevented()) {
        //$('#informationModalText').html('Beberapa data belum dimasukkan, harap lengkapi data!');
        //$('#informationModal').modal('show');
    } else {
        var dataPost = $("#formDataSertifikat").serializeArray(),
            target = $("#formDataSertifikat").attr("action");
        $.ajax({
            type: 'POST',
            url: base_url + target,
            data: dataPost,
            dataType: 'json',
            beforeSend: function() {
                $("#formDataSertifikat :input").attr("disabled", true);
            },
            success: function(apiRes) {
                $("#formDataSertifikat :input").attr("disabled", false);
                if (apiRes.status == 200) {
                    $(location).attr('href', base_url + 'sertifikat?active=' + req_rdr);
                } else {
                    $('#informationModalText').html(apiRes.msg);
                    $('#informationModal').modal('show');

                }
            },
            error: function() {
                $("#formDataSertifikat :input").attr("disabled", false);
            }
        });
        return false;
    }
});