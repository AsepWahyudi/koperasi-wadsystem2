<?php
// echo $basequery;   
?>
<div class="col-sm-12"> 
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
                <div class="row">
                    <div class="col-sm-9 col-lg-9">
                        <h4 class="color-primary">Checklist Detail</h4>
                    </div>
                    <!--div class="col-sm-3  col-lg-3 ">
                        <a href="<?php  echo base_url();?>setoran-tunai-add" class="btn btn-primary btn-block" >
                        	<i class="fa fa-plus"></i>
                        	Tambah Setoran Tunai
                    	</a>
                    </div-->
                </div>
        </div>
    </div>
</div>
<div class="col-sm-12">
		<div class="panel">
			<form class="form-inline flr" action="" method="POST" id="filterForm">
						<!--<input class="form-control form-control-sm rounded bright" type="text" value="<?php  date("m/d/Y")?>" id="myudate" name="tgl">
						<button type="button" class="btn btn-sm btn-secondary btn-rounded" id="tampilfilter">Tampilkan</button>-->
						<input class="form-control form-control-sm rounded bright" type="hidden" value="<?php echo $tgl;?>" name="tgl">
						<input type="hidden" name="page" id="page" value="1"/>
						<input type="hidden" name="dataperpage" id="dataperpage" value="10"/>
						<input type="hidden" name="kodecabang" id="kodecabang" value="<?php echo $KODECABANG;?>"/>
						 <input type="hidden" name="urltarget" id="urltarget" value="checklist/checklist/detaildata/<?php echo $this->uri->segment(3) ?>"/>
					  </form>
	        <div class="panel-content">
                <div class="table-responsive" id="table-view">
					 <div class="dataTables_wrapper dt-bootstrap4"> 
						<table id="dataTable1" width="100%" class="table table-striped table-lightfont">
							<thead>
								<tr>
			                        <th>No</th>
			                        <th>Tanggal Pinjam</th>
			                        <th>Tanggal Bayar</th>
			                        <th>Nama Anggota</th>
			                        <th>Cabang</th>
			                        <th>Total Tagihan</th>
			                        <th>Jml Angsuran</th>
			                        <th>Sisa Tagih</th>
			                        <th>Angsuran Ke</th>
			                        <th>Bukti</th>
			                        <th>Konfirmasi</th>
			                        <!--th>Action</th-->
			                    </tr>
							</thead>
							<tbody>
							<?php
							
							$jumlah = 0;
							$no = 1;
							foreach($datadetail as $row){
							?>
								<tr>
									<td><b><?php echo $no++;?></b></td>
									<td><?php echo $row->TGL_PINJ;?></td>
									<td><?php echo $row->TGL_BAYAR;?></td>
									<td><?php echo $row->NAMA_ANGGOTA;?></td>
									<td><?php echo $row->NAMACABANG;?></td> 
									<td><?php echo $row->TOTAL_TAGIHAN;?></td>
									<td><?php echo $row->JML_ANGSURAN;?></td>
									<td><?php echo $row->SISA_TAGIHAN;?></td>  
									<td><?php echo $row->ANGSURAN_KE.'/'.$row->LAMA_ANGSURAN;?></td>  
									<td class="text-center col-md-1" id="gallery">
										 
										<?php 
										if($row->BUKTI != '' or $row->BUKTI > 0) 
										{
										?>
											<a href="<?php echo base_url()?>uploads/bukti/<?php echo $row->BUKTI; ?>" >
												<img alt="first photo" src="<?php echo base_url()?>uploads/bukti/<?php echo $row->BUKTI; ?>" class="img-responsive">
											</a>
										<?php 
										}
										else
										{
										?>
											<form id="form<?php echo $row->IDPINJ_D;?>" method="post" action="otherpage.php" enctype="multipart/form-data"> 
												<input type="hidden" name="id" value="<?php echo $row->IDPINJ_D;?>">
												<button class="btn btn-secondary btn-rounded uploadbuktiangsuran btn-sm" type="button" data-id="<?php echo $row->IDPINJ_D; ?>" id="uploadbuktiangsuran<?php echo $row->IDPINJ_D; ?>">Upload</button>
												
													<center>
														<img src="<?php echo base_url()?>img/loading.gif" id="loadingtellerangsuran<?php echo $row->IDPINJ_D;?>" style="display:none;">
													</center>
													
												<input class="form-control buktitellerangsuran" type="file" name="buktitellerangsuran<?php echo $row->IDPINJ_D;?>" id="buktitellerangsuran<?php echo $row->IDPINJ_D;?>" style="display:none;">
											</form> 
										<?php 
										}
										?>
									</td>
									<?php 
									if($this->session->userdata('wad_level')=="admin")
									{
									?>
									<td class="text-center">
										<?php  
										// if($row->STATUS =="0")
										// { 
										if($row->BUKTI != '' or $row->BUKTI > 0)
										{ 
										?>
											<a class="btn btn-success btn-sm btnconftellerangsuran" href="javascript:void(0)" data-id="<?php echo $IDCEKTELLER."#".$row->IDPINJ_D;?>">Konfirmasi</a>
										<?php
										}
										// }
										?>
									</td>
									<?php 
									} 
									?>
									<!--td> 
										<a href='#' class='btn btn-warning btn-sm'>
											<i class='fa fa-edit'></i>
										</a>
										<a href='#' class='btn btn-danger btn-sm'>
											<i class='fa fa-trash'></i>
										</a>
									</td-->  
								</tr> 
							<?php
								$jumlah = $jumlah+$row->JUMLAH;
							}
							?>
							</tbody>
							<tr>
								<td colspan='3' style='text-align: right;' ><b>Jumlah</b></td>
								<td><?php echo number_format($jumlah);?></td>
						    </tr>
						</table> 
					 </div>
			    </div>
            </div>
        </div>
    </div> 
<!--script src="<?php  echo base_url();?>assets/js/checklist/checklistdetail.js"></script--> 