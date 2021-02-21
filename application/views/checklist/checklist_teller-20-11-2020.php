<?php
// echo $sqlquery;

?>

<div class="col-sm-12"> 
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
			<div class="row">
				<div class="col-sm-9 col-lg-10">
					<h4 class="color-primary">Checklist Teller</h4>
				 
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
				  <?php
				  if($this->session->userdata('wad_level')=="admin")
				  { 
				  ?>
					<form class="form-inline">
					  Cabang &nbsp;
					  
					  <select class="form-control form-control-sm rounded bright plhcabang" name="plhcabang" id="plhcabang">
						<option value="">All</option>
						<?php
						
						$cabs = $this->dbasemodel->loadsql("SELECT NAMA,KODE FROM m_cabang ORDER BY NAMA ASC");
						foreach($cabs->result() as $cab)
						{
							$sel = ($cab->KODE==$this->session->userdata('wad_cabang'))? 'selected="selected"':"";
						?> 
						<option value="<?php echo $cab->KODE; ?>" <?php echo $sel; ?>><?php echo $cab->NAMA; ?></option>
						<?php 
						} 
						?>
					  </select>
					</form>
				  <?php 
				  } 
				  ?>
					   
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
                <div class="table-responsive">
			        <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
			            <thead>
			                <tr>
			                    <th>No</th>
			                    <th>Tanggal</th>
			                    <th class="text-center">Nominal</th>
			                    <th class="text-center">Cabang</th>
								<th class="text-center">Bukti</th>
								<?php 
								if($this->session->userdata('wad_level')=="admin")
								{
								?>
								<th class="text-center">Konfirmasi</th>
								<?php 
								}
								?>
								<th class="text-center">Action</th>
			                </tr>
			            </thead>
			            <tbody>
			                <?php
			                if($query->num_rows() > 0)
							{ 
								$n = 1;
								foreach($query->result() as $key)
								{ 
									$no = $n++; 
							?>
									<tr>
										<td><?php echo $no; ?></td>
										<td><?php echo $key->TGL_AWAL; ?></td>
										<td class="text-center"><?php echo toRp($key->NOMINAL_PINJ+$key->NOMINAL_SIMP); ?></td>
										<td><?php echo $key->CABANG; ?></td>
										<td class="text-center col-md-1" id="gallery">
										<?php //if($this->session->userdata('wad_level')!="admin"){?>
										<?php 
										if($key->BUKTI !="")
										{
										?>
											<a href="<?php echo base_url()?>uploads/bukti/<?php echo $key->BUKTI; ?>" >
												<img alt="first photo" src="<?php echo base_url()?>uploads/bukti/<?php echo $key->BUKTI; ?>" class="img-responsive">
											</a>
										<?php 
										}
										else
										{
										?>
											<form id="form<?php echo $key->IDCEKTELLER;?>" method="post" action="otherpage.php" enctype="multipart/form-data">
											
												<input type="hidden" name="id" value="<?php echo $key->IDCEKTELLER;?>">
												<button class="btn btn-secondary btn-rounded uploadbukti btn-sm" type="button" data-id="<?php echo $key->IDCEKTELLER; ?>" id="uploadbukti<?php echo $key->IDCEKTELLER; ?>">Upload</button>
												
													<center>
														<img src="<?php echo base_url()?>img/loading.gif" id="loadingteller<?php echo $key->IDCEKTELLER;?>" style="display:none;">
													</center>
													
												<input class="form-control buktiteller" type="file" name="buktiteller<?php echo $key->IDCEKTELLER;?>" id="buktiteller<?php echo $key->IDCEKTELLER;?>" style="display:none;">
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
											if($key->BUKTI !="")
											{
											?>
												<a class="btn btn-success btn-sm btnconfteller" href="javascript:void(0)" data-id="<?php echo $key->IDCEKTELLER;?>">Konfirmasi</a>
											<?php
											}
											?>
										</td>
										<?php 
										} 
										?>
										<td class="text-center">
											<a href="<?php echo base_url()?>detail-teller/<?php echo $key->IDCEKTELLER; ?>" class='btn btn-success btn-sm'>
												<i class='fa fa-search'></i>
											</a>
										</td>
									</tr>
			                <?php 
								}
							}
							?>
			            </tbody> 
			        </table>
			    </div>
            </div>
        </div>
    </div>