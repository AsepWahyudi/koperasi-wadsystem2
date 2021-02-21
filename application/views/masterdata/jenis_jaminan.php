<div class="col-sm-12">
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
                <div class="row">
                    <div class="col-sm-6 col-lg-9">
                        <h4 class="color-primary">Jenis Jaminan</h4>
                    </div>
                    <div class="col-sm-4  col-lg-3 element-actions ">
                        <button class="btn btn-primary btn-block" data-target="#mymodals" data-toggle="modal" type="button">Tambah Jenis Jaminan</button>
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
        <div class="panel-header b-primary bt-sm">
        <?=$response;?>
        <?=$this->session->flashdata('response');?>
            <div class="table-responsive">
                <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Jaminan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                       <?php
                       $no = 0;
                       if(!empty($query)){
                           foreach($query as $key){
                               $no++;
                               $btn_edit	=	'<a href="javascript:;" onclick="loaddata(\'btnedit' . $no . '\')" var-url="master_data/jenis_jaminan/get_edit?id='.$key->IDJAMINAN.'" class="btn btn-success btnedit'.$no.' btn-sm"><i class="fa fa-pencil"></i></a>';
					            $btn_del	=	'<a href="'.base_url().'master_data/jenis_jaminan/delete/'.$key->IDJAMINAN.'" class="btn btn-danger btn-sm" style="margin-left:3px;" onclick="return confirm(\'Yakin dihapus?\')"><i class="fa fa-trash"></i></a>';
                               ?>
                               <tr>
                                <td><?=$no;?></td>
                                <td><?=$key->NAMAJAMINAN;?></td>
                                <td><?=$btn_edit;?>&nbsp;<?=$btn_del;?></td>
                               </tr>
                               <?
                           }
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
        <form method="post" action="<?php echo base_url();?>master_data/jenis_jaminan">
        <input type="hidden" name="idtrx" id="idtrx" value="">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Data Jenis Jaminan</h5>
            <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> &times;</span></button>
        </div>
        <div class="modal-body">
            <div class="row">
            	<div class="col-sm-8">
                	<div class="form-group">
                    	<label for="">Nama Jaminan</label>
                        <input class="form-control" placeholder="Masukkan Nama Jaminan" type="text" name="nama" id="nama">
                	</div>
            	</div>
            </div>
            
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-dismiss="modal" type="button"> Close</button><button class="btn btn-primary" type="submit"> Simpan</button>
        </div>
        </div>
        </form>
    </div>
</div>

<script>
	var base_url	=	'<?php echo base_url();?>';
</script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/Master/jns_jaminan.js?v=1.0"></script>