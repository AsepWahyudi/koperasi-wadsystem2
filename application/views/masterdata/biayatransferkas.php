<div class="col-sm-12">
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
                <div class="row">
                    <div class="col-sm-6 col-lg-10">
                        <h4 class="color-primary">Data Biaya Transfer Kas</h4>
						<?php //echo $sqlquery;?>
                    </div>
                    <div class="col-sm-3  col-lg-2 element-actions ">
                        <button class="btn btn-primary btn-block" data-target="#mymodals" data-toggle="modal" type="button">Tambah Biaya Transfer</button>
                    </div>
                </div>
        </div>
    </div>
</div>
<style type="text/css">
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td { vertical-align: baseline;}
</style>
<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <?php echo $response;?>
            <div class="table-responsive">
                <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Biaya Transfer</th>
                            <th>Biaya Transfer</th> 
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
						 $n = 1;
                        foreach($query->result() as $key)
						{ 
						    $no = $n++;
							
                            $btn_edit = '<a href="javascript:;" onclick="loaddata(\'btnedit' . $no . '\')" 
							var-url="master_data/biaya_transfer/get_edit?id='.$key->ID_BIAYA_TRF_KAS.'" class="btn btn-success btnedit'.$no.' btn-sm"><i class="fa fa-edit"></i></a>';
							 
                            ?>
                            <tr>
								<td><?php echo $no;?></td>
								<td><?php echo $key->NAMA_BIAYA;?></td>
								<td><?php echo number_format($key->BIAYA_TRF);?></td> 
								<td><?php echo $btn_edit; ?></td>
							</tr>
                        <?php
						}
						?>
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
</div>
 
<div aria-hidden="true" aria-labelledby="mymodals" class="modal" id="mymodals" role="dialog" tabindex="-1">
    <div class="modal-dialog" role="document">
        <form method="post" action ="<?php echo base_url('savebiayatransfer');?>"> 
		<input type="hidden" name="ID_BIAYA_TRF_KAS" id="ID_BIAYA_TRF_KAS" value=""> 
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Data Biaya Tranfer Kas</h5>
            <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> &times;</span></button>
        </div>
        <div class="modal-body"> 
            <div class="row">
            	<div class="col-sm-12">
                	<div class="form-group">
                        <label for=""> Nama Biaya Transfer </label> 
						<input class="form-control" placeholder="Nama Biaya Transfer" type="text" name="NAMA_BIAYA" id="NAMA_BIAYA">
                    </div>
            	</div>
				<div class="col-sm-12">
                	<div class="form-group">
                        <label for=""> Biaya Transfer Kas </label>
						<input class="form-control" placeholder="Biaya Transfer" type="text" name="BIAYA_TRF" id="BIAYA_TRF">
                    </div>
            	</div> 
            </div>   
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-dismiss="modal" type="button"> Close</button>
			<button class="btn btn-primary" type="submit"> Simpan</button>
        </div>
        </div>
        </form>
    </div>
</div>
<script>
	var base_url = '<?php  echo base_url();?>'
</script>
<script type="text/javascript" src="<?php  echo base_url();?>assets/js/Master/biaya_transfer.js"></script>