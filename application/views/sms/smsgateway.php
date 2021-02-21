<?php  
$decode = json_decode($credit, true);
 foreach ($decode as $value) {
 	$creditval = $value[0];
 }
?>
<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <div class="row">
                <div class="col-sm-10 col-lg-10">
                    <h4 class="color-primary">SMS Gateway</h4>
                </div>
                <div class="col-sm-2 col-lg-2">
                    <h4 class="color-primary flr">Sisa Credit : <strong class="color-danger"><?php  echo $creditval;?></strong></h4>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="col-sm-6">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <h4 class="color-primary">SMS Massal</h4>
                </div>
            </div>
        </div>    
        <div class="panel-content">
            <form id="formSms" action="kirimsms" method="post" class="formValidate">                
                <div class="form-group">
	                <label for="textareaMaxLength" class="control-label">Isi Pesan</label>
	                <textarea class="form-control" rows="8" id="textareaMaxLength" placeholder="Masukkan Pesan" maxlength="400" name="pesan" required></textarea>
	                <span class="help-block"><i class="fa fa-info-circle mr-xs"></i>Max characters <span class="code">400</span></span>
	            </div>
                
                <div class="form-buttons-w">
                    <button class="btn btn-primary" type="submit"> Kirim</button> 
                    <h6 class="color-primary flr">Jumlah Anggota : <?php  echo $anggota;?></h6>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="col-sm-6">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <h4 class="color-primary">SMS Pemberitahuan Pembayaran H-3</h4>
                </div>
            </div>
        </div>    
        <div class="panel-content">
            <form id="formSms2" action="kirimsmsh3" method="post" class="formValidate">                
                <div class="form-group">
                    <label for="textareaMaxLength" class="control-label">Isi Pesan</label>
                    <blockquote class=" b-primary bl-md b-rounded">
                        <p>H-3 segera lakukan pembayaran angsuran anda sebesar [Rp. ...] melalui cash/transfer</p>
                    </blockquote>
                </div>
                
                <div class="form-buttons-w">
                    <button class="btn btn-primary" type="submit"> Kirim</button> 
                    <h6 class="color-primary flr">Jumlah Anggota : <?php  echo $tempoh3;?></h6>
                </div>
            </form>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <h4 class="color-primary">SMS Pemberitahuan Angsuran</h4>
                </div>
            </div>
        </div>    
        <div class="panel-content">
            <form id="formSms3" action="kirimsmsrisert" method="post" class="formValidate">                
                <div class="form-group">
                    <label for="textareaMaxLength" class="control-label">Isi Pesan</label>
                    <blockquote class=" b-primary bl-md b-rounded">
                        <p>Segera lakukan pembayaran angsuran anda sebelum jatuh tempo akad risert</p>
                    </blockquote>
                </div>
                
                <div class="form-buttons-w">
                    <button class="btn btn-primary" type="submit"> Kirim</button> 
                    <h6 class="color-primary flr">Jumlah Anggota : <?php  echo $agtrisert;?></h6>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <div class="row">
                <div class="col-sm-10 col-lg-10">
                    <h4 class="color-primary">History Pengiriman SMS </h4>
                </div>
                <?php  if($this->session->userdata('wad_level')=="admin"){ ?>
                <div class="col-sm-2  col-lg-2 ">
                    <a href="<?php  echo base_url();?>smsgateway/reset" class="btn btn-danger btn-block" >
                        <i class="fa fa-trash"></i> 
                        Hapus History
                    </a>
                </div>
                <?php  } ?>
            </div>
        </div>
    
        <div class="panel-content">
            <table id="responsive-table" class="data-table table table-striped table-hover responsive nowrap" cellspacing="0" width="100%">
                <thead>
	                <tr>
	                    <th>No</th>
                        <th>Tanggal</th>
	                    <th>Pesan</th>	                    
	                    <th>Jumlah</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Jenis</th>
	                </tr>
	            </thead>
	            <tbody>
                    <?php   $n = 1; 
                        foreach($outbox->result() as $key){$no = $n++; ?>
                        <tr>
                            <td><?php  echo $no?></td>
                            <td><?php  echo tgl_en($key->TANGGAL)?></td>
                            <td><?php  echo $key->PESAN?></td>
                            <td><?php  echo $key->KIRIM?></td>
                            <td><?php  echo $key->STATUS?></td>
                            <td><?php  echo $key->TEXT?></td>
                            <td><?php  echo $key->JENIS?></td>
                        </tr>
                    <?php   }  ?>
	            </tbody>
            </table>
        </div>
    </div>
</div>