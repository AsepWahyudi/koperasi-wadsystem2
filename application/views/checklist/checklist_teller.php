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
									
									if($key->JMLSIMPANAN != "0" OR $key->JMLPINJAMAN != "0"){
							?>
									<tr>
										<td><?php echo $no; ?></td>
										<td><?php echo $key->TGL_AWAL; ?></td>
										<td class="text-center"><?php echo toRp($key->NOMINAL_PINJ+$key->NOMINAL_SIMP); ?></td>
										<td><?php echo $key->CABANG; ?></td> 
										<td class="text-center">
											
											<?php
											if($key->JMLSIMPANAN != "0"){
											
											?>
											<a href="<?php echo base_url()?>detail-teller/<?php echo $key->IDCEKTELLER; ?>" class='btn btn-success btn-sm'>
												<i class='fa fa-search'></i> Konfirmasi Simpanan
											</a> 
											<?php
											}
											if($key->JMLPINJAMAN != "0"){
											?>
											<a href="<?php echo base_url()?>detail-tellerangsuran/<?php echo $key->IDCEKTELLER; ?>" class='btn btn-success btn-sm'>
												<i class='fa fa-search'></i> Konfirmasi Angsuran 
											</a>
											<?php
											}
											?>
										</td>
									</tr>
			                <?php 
									}
								}
							}
							?>
			            </tbody> 
			        </table>
			    </div>
            </div>
        </div>
    </div>