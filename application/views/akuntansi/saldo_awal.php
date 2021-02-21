<div class="col-sm-12">
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
            <div class="row">
                <div class="col-sm-6 col-lg-7">
                    <h4 class="color-primary">Saldo Awal Perkiraan(COA)</h4>
                </div>
                <div class="col-sm-3  col-lg-5">
    <form action="" method="post" id="formSaldoAwal">

                    <?php  $row  =   $query->row();  ?>
                    <div class="controls-above-table">
                        <div class="row">
                            <div class="col-sm-12 justify-content-sm-end">
                                <div class="form-inline justify-content-sm-end flr">
                                    Tanggal : &nbsp; <input class="form-control form-control-sm bright" type="text" name="tgl" id="myudate" value="<?php  echo (empty($row->TANGGAL) ? date('01/01/Y') : date('d/m/Y', strtotime($row->TANGGAL)) ) ?>">
                                    <button type="submit" class="btn btn-primary">Simpan Saldo Awal</button>
                                </div>
                            </div>
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
            vertical-align: baseline;}
</style>
<div class="col-sm-12">
    <div class="panel">
        <div class="panel-content b-primary bt-sm">     
            <div class="table-responsive">

                <table width="100%" class="table table-bordered table-stripped font-small">
                    <thead>
                        <tr>
                            <th style="width:90px">Kode</th>
                            <th>Perkiraan</th>
                            <th>Akun</th>
                            <th>Jenis</th>
                            <th style="width:160px">Saldo Awal</th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php  if($query->num_rows() > 0){ $n = 1;
            				$data_source	=	$query->result_array();
            				$data_source	=	$this->tree->result_tree('PARENT', 'IDAKUN', $data_source);
            				$result			=	$data_source['return'];

            				foreach($result as $key=>$item){ $no = $n++; ?>
                                <tr>
                                    <td><?php  echo $item['KODE_AKTIVA']?></td>
                                    <td><?php  echo  $this->tree->level($item['_level'], $item['_header'], $item['JENIS_TRANSAKSI'])?></td>
                                    <td><?php  echo $item['AKUN']?></td>
                                    <td><?php  echo $item['TIPE']?></td>
                                    <td class="text-center">
                                    	<?php  if($item['_header'] == 0) { ?>
                                    	<input type="hidden" name="idakun[]" value="<?php  echo $item['IDAKUN']?>" class="idakun"/>
                                    	<input type="text" name="saldo_awal[]" value="<?php  echo number_format($item['SALDO_AWAL'], 0, ",", ".")?>" class="form-control text-center saldo_awal _saldo<?php  echo $no?>" onkeyup="check_number(this.value, '_saldo<?php  echo $no?>')" required/>
                                        <div class="help-block form-text with-errors form-control-feedback" data-error="Harap mengisi form berikut" required="required"></div>
                                    	<?php  } ?>
                                    </td>
                                </tr>
                        <?php 	} }  ?>
                    </tbody> 
                </table>
            </div>
        </div>
    </div>
    </form>
</div>

<style>
	table.font-small td { vertical-align:middle; padding: 5px 8px;}
	.saldo_awal{border:1px solid #ddd; padding: 1px 0.75rem; border-bottom: 2px solid #1856d2; border-radius: 2px;}
</style>
<script>
var base_url = '<?php  echo base_url();?>'; 
$('#formSaldoAwal').validator().on('submit', function(e) {
    if (e.isDefaultPrevented()) {
        $('#informationModalText').html('Harap lengkapi data saldo awal!');
        $('#informationModal').modal('show');
    } else {
        var dataPost = $("#formSaldoAwal").serializeArray();
        $.ajax({
            type: 'POST',
            url: base_url + 'akuntansi/saldo_awal/save',
            data: dataPost,
            dataType: 'json',
            beforeSend: function() {
                $("#formSaldoAwal :input").attr("disabled", true);
            },
            success: function(apiRes) {
                $("#formSaldoAwal :input").attr("disabled", false);
                if (apiRes.status == 200) {
                    $(location).attr('href', base_url + 'akuntansi/saldo_awal');
                } else {
                    $('#informationModalText').html(apiRes.msg);
                    $('#informationModal').modal('show');

                }
            },
            error: function() {
                $("#formSaldoAwal :input").attr("disabled", false);
            }
        });
        return false;
    }
});

function check_number(isi, tg) {
	$().ready(function () {
		isi		=	isi.replace(/\,/g, '');
		_saldo	=	eval(isi);
		$('.' + tg).val(rupiah(_saldo));
	});
}

</script>