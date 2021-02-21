<div class="col-sm-12">
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
                <div class="row">
                    <div class="col-sm-6 col-lg-10">
                        <h4 class="color-primary">Kode Perkiraan</h4>
                    </div>
                    <div class="col-sm-3  col-lg-2 element-actions ">
                        <a class="btn btn-success btn-block" href="<?php  echo base_url();?>akuntansi/perkiraan/perkiraan_excel"><i class="fa fa-table"> </i> <span>Export Excel</span></a>
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
                <table width="100%" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width:90px">Kode</th>
                            <th>Perkiraan</th>
                            <th>Akun</th>
                            <th>Aktif</th>
                            <th>Jenis</th>
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
                                    <td><?php  echo $item['AKTIF']?></td>
                                    <td><?php  echo $item['TIPE']?></td>
                                </tr>
                        <?php 	} }  ?>
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
</div>