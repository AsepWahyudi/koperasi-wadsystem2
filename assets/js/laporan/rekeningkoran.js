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

$("#tampilcetak").click(function(){
    resetPage();
    ajaxDataTable();
});

function renderTableData(numData, data) {

    var tableData = btnEdit = image = "";
 
    $.each(data, function(i, obj) {

        tableData += "<tr role='row'>" +
                        "<td>" + numData + "</td>" +
                        "<td>" + obj.TANGGAL + "</td>" +
                        "<td>" + obj.NAMA + ' - ' + obj.KODE_ANGGOTA + "</td>" +
                        "<td>" + obj.AKUN + "</td>" +
                        "<td>" + (obj.KETERANGAN != null && obj.KETERANGAN != "" ? obj.KETERANGAN : obj.JENIS_TRANSAKSI) + "</td>" +
                        "<td>" + rupiah(obj.JUMLAH) + "</td>" +
                        /*"<td>" + rupiah(obj.SALDO) + "</td>" +*/
			         "</tr>";
        numData++;
    });

    return tableData;
} 