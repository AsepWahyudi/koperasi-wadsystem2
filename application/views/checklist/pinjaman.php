<div class="col-sm-12"> 
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
                <div class="row">
                    <div class="col-sm-9 col-lg-10">
                        <h4 class="color-primary">Pengajuan Pinjaman</h4>
                    </div>
                </div>
        </div>
    </div>
</div>
<div class="col-sm-12">
		<div class="panel">
			<div class="panel-header b-primary bt-sm">
				<div class="row">
				  <div class="col-sm-6">
				  <? if($this->session->userdata('wad_level')=="admin"){ ?>
					<form class="form-inline">
					  Cabang &nbsp;
					  
					  <select class="form-control form-control-sm rounded bright plhcabang" name="plhcabang" id="plhcabang">
						<option value="">All</option>
						<?
						
							$cabs = $this->dbasemodel->loadsql("SELECT NAMA,KODE FROM m_cabang ORDER BY NAMA ASC");
							foreach($cabs->result() as $cab){
								$sel = ($cab->KODE==$this->session->userdata('wad_cabang'))? 'selected="selected"':"";
						?>
					  
						<option value="<?php echo $cab->KODE;?>" <?php echo $sel;?>><?php echo$cab->NAMA;?></option>
						<? } ?>
					  </select>
					</form>
				  <? } ?>
					   
				  </div>
				  <div class="col-sm-6 justify-content-sm-end">
					<div class="form-inline justify-content-sm-end">
					  <form class="form-inline flr">
		                <input class="single-daterange form-control form-control-sm rounded bright" type="text" name="tgl">
						<button type="submit" class="btn btn-sm btn-secondary btn-rounded">Tampilkan</button>
		              </form>
					</div>
				  </div>
				</div>
			</div>
	        <div class="panel-content">
	        	<?php notifikasi($this->session->flashdata('ses_checklist'))?>
                <div class="table-responsive">
			        <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
			            <thead>
			                <tr>
			                    <th>No</th>
			                    <th>Tanggal</th>
			                    <th class="text-center">Nama Nasabah</th>
			                    <th class="text-center">Pinjaman (IDR)</th>
								<th class="text-center">Diterima Nasabah (IDR)</th>
								<th class="text-center">No Rekening</th>
								<th class="text-center">Kode Bank</th>
								<th class="text-center">Nama Bank</th>
								<th class="text-center">Bukti</th>
								<? if($this->session->userdata('wad_level')=="admin"){?>
								<th class="text-center">Konfirmasi</th>
								<th class="text-center">Action</th>
								<? } ?>
			                </tr>
			            </thead>
			            <tbody>
			                <?php
			                if($query->num_rows() > 0){ $n = 1;
			                foreach($query->result() as $key){ $no = $n++;
			                   
			                    ?>
			                    <tr>
			                    <td><?=$no?></td>
			                    <td><?=$key->TGL?></td>
								<td><?=$key->NAMA?></td>
			                    <td class="text-right"><?=toRp($key->JUMLAH)?></td>
								<td class="text-right text-success"><?=toRp($key->JUMLAH-$key->BIAYA_ADMIN-$key->BIAYA_ASURANSI)?></td>
			                    <td class="text-center"><?=$key->NOREK?></td>
								<td class="text-center"><?=$key->KODEBANK?></td>
								<td class="text-center"><?=$key->NAMA_BANK?></td>
								<td class="text-center">
								<?// if($this->session->userdata('wad_level')!="admin"){?>
									<? if($key->FILEBUKTI !=""){?>
									<img src="<?=base_url()?>uploads/bukti/<?=$key->FILEBUKTI?>" width="50">
									<?}else{?>
									<form id="form<?=$key->IDPINJM_H?>" method="post" action="otherpage.php" enctype="multipart/form-data">
									<input type="hidden" name="id" value="<?=$key->IDPINJM_H?>">
									<button class="btn btn-secondary btn-rounded cairbutton btn-sm" type="button" data-id="<?=$key->IDPINJM_H?>" id="cairbutton<?=$key->IDPINJM_H?>">Upload</button>
									<center><img src="<?=base_url()?>img/loading.gif" id="loadingcair<?=$key->IDPINJM_H?>" style="display:none;"></center>
									<input class="form-control bukticair" type="file" name="bukticair<?=$key->IDPINJM_H?>" id="bukticair<?=$key->IDPINJM_H?>" style="display:none;">
									</form>
										<? }?>
								<? //}?>
								</td>
								
								<? //if($this->session->userdata('wad_level')=="admin"){?>
								<td class="text-center"><? if($key->FILEBUKTI !=""){?><a class="btn btn-success btn-sm btnconfpinjam" href="javascript:void(0)" data-id="<?=$key->IDPINJM_H?>">Konfirmasi</a><?}?></td>
			                    <td class="text-center"><a href="<?=base_url()?>data-pengajuan-pinjaman?id=<?=$key->IDPINJM_H?>" class='btn btn-success btn-sm'><i class='fa fa-search'></i></a></td>
								<? //}?>
			                </tr>
			                <?php }} ?>
			            </tbody>
			            
			        </table>
			    </div>
            </div>
        </div>
    </div>