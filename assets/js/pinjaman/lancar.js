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

        obj.TEMPO_FIRST_NUMB = parseInt(obj.TEMPO_FIRST_NUMB, 10);
        obj.TEMPO_SECOND_NUMB = parseInt(obj.TEMPO_SECOND_NUMB, 10);
        obj.TEMPO_THIRD_NUMB = parseInt(obj.TEMPO_THIRD_NUMB, 10);
        obj.NOW_NUMB = parseInt(obj.NOW_NUMB, 10);

        var keterangan	=	(obj.LUNAS === 'Lunas' ? '<span class="badge b-rounded x-success">Lancar</span>' : 

                            (obj.TEMPO_FIRST_NUMB > obj.NOW_NUMB ? '<span class="badge b-rounded x-success">Lancar</span>'));

        

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