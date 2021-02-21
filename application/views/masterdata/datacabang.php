<div class="col-sm-12">
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
                <div class="row">
                    <div class="col-sm-6 col-lg-10">
                        <h4 class="color-primary">Data Cabang</h4>
						<?php //echo $sqlquery;?>
                    </div>
                    <!--div class="col-sm-3  col-lg-2 element-actions ">
                        <button class="btn btn-primary btn-block" data-target="#mymodals" data-toggle="modal" type="button">Tambah Cabang</button>
                    </div-->
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
                            <th>Kode Cabang</th>
                            <th>Nama Cabang</th>
                            <th>Alamat</th>
                            <th>Kota</th>
                            <th>Tlp</th>
        					<th>Nama KSP</th>
        					<th>Email</th>
                            <th>Web</th>
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
							var-url="master_data/cabang/get_edit?id='.$key->IDCABANG.'" class="btn btn-success btnedit'.$no.' btn-sm"><i class="fa fa-edit"></i></a>';
							
        					// $btn_del = '<a href="'.base_url().'master_data/user/delete/'.$key->IDCABANG.'" class="btn btn-danger btn-sm" style="margin-left:3px;" onclick="return confirm(\'Yakin dihapus?\')"><i class="fa fa-trash"></i></a>';
                            ?>
                            <tr>
								<td><?php echo $no;?></td>
								<td><?php echo $key->KODECABANG;?></td>
								<td><?php echo $key->NAMA;?></td>
								<td><?php echo $key->ALAMAT;?></td>
								<td><?php echo $key->KOTA;?></td>
								<td><?php echo $key->TELP;?></td>
								<td><?php echo $key->NAMAKSP;?></td>
								<td><?php echo $key->EMAIL;?></td> 
								<td><?php echo $key->WEB;?></td> 
								<td><?php echo $btn_edit; ?> <?php //echo $btn_del; ?></td>
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
        <form method="post" action ="<?php echo base_url('savecabang');?>"> 
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Data Cabang</h5>
            <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> &times;</span></button>
        </div>
        <div class="modal-body"> 
            <div class="row">
            	<div class="col-sm-12">
                	<div class="form-group">
                        <label for=""> Nama </label>
						<input type="hidden" name="IDCABANG" id="idcabang">
						<input class="form-control" placeholder="Nama" type="text" name="NAMA" id="nama">
                    </div>
            	</div>
				<div class="col-sm-12">
                	<div class="form-group">
                        <label for=""> Alamat </label>
						<input class="form-control" placeholder="Alamat" type="text" name="ALAMAT" id="alamat">
                    </div>
            	</div> 
            </div>  
            <div class="row">
                <div class="col-sm-6">
					<div class="form-group">
						<label for=""> Kota</label><input class="form-control" placeholder="Kota" type="text" name="KOTA" id="kota">
					</div>
                </div>
                <div class="col-sm-6">
					<div class="form-group">
						 <label for=""> Tlp</label><input class="form-control" placeholder="Tlp" type="text" name="TELP" id="telp">
					</div>
                </div>
            </div>
			<div class="row">
                <div class="col-sm-6">
					<div class="form-group">
						<label for=""> Nama KSP</label><input class="form-control" placeholder="Nama KSP" type="text" name="NAMAKSP" id="namaksp">
					</div>
                </div>
                <div class="col-sm-6">
					<div class="form-group">
						 <label for=""> EMAIL</label><input class="form-control" placeholder="Email" type="text" name="EMAIL" id="email">
					</div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
					<div class="form-group">
						<label for=""> Website</label><input class="form-control" placeholder="Website" type="text" name="WEB" id="web">
					</div>
                </div> 
				<div class="col-sm-6">
					<div class="form-group">
						<label for=""> Kode Cabang</label><input class="form-control" placeholder="Kode Cabang" type="text" name="KODECABANG" id="kodecabang">
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
<script type="text/javascript" src="<?php  echo base_url();?>assets/js/Master/cabang.js"></script>