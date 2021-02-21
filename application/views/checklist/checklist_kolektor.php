<?php  
$wadCabang = $this->session->userdata('wad_cabang');
$cabsh = $this->dbasemodel->loadsql("SELECT * FROM m_cabang WHERE KODE =  ".$this->session->userdata('wad_kodecabang')."");
$cabHh = $cabsh->row();
?>
<div class="col-sm-12 head-print">
    <div class="panel">
        <div class="panel-content">
                <div class="row">
                        <table>
										<tr>
											<td><img src="img/logokop.png" style="width: 60px;height: 60px;margin-right: 10px;margin-left: 10px;" /></td>
											<td valign="top" class="headtitle">
											<h4><?php  echo $cabHh->NAMAKSP?> <?php  echo $cabHh->KOTA?></h4>
											<?php  echo $cabHh->ALAMAT?> <?php  echo $cabHh->KOTA?><br>
											Telp : <?php  echo $cabHh->TELP?> Email : <?php  echo $cabHh->EMAIL?><br>
											Web : <?php  echo $cabHh->WEB?>
											</td>
										</tr>
									</table>            
                </div>
        </div>
    </div>
</div>
<div class="col-sm-12"> 
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
                <div class="row">
                    <div class="col-sm-8 col-lg-8">
                        <h4 class="color-primary">Checklist Kolektor</h4>
                    </div>
                    <div class="col-sm-2  col-lg-2 ">
	                    <a onclick="window.print()" class="btn btn-primary btn-block" >
	                    	<i class="fa fa-print"></i> 
	                    	Cetak
	                	</a>
	                </div>
	                <div class="col-sm-2  col-lg-2 ">
	                    <a href="<?php  echo base_url();?>cheklist-kolektor/excel" class="btn btn-success btn-block" >
	                    	<i class="fa fa-table"></i> 
	                    	Export Excel
	                	</a>
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
			  <?php  if($this->session->userdata('wad_level')=="admin"){ ?>
				<form class="form-inline">
				  Cabang &nbsp;
				  
				  <select class="form-control form-control-sm rounded bright plhcabang" name="plhcabang" id="plhcabang">
					<option value="">All</option>
					<?php  
					
						$cabs = $this->dbasemodel->loadsql("SELECT NAMA,KODE FROM m_cabang ORDER BY NAMA ASC");
						foreach($cabs->result() as $cab){
							$sel = ($cab->KODE==$this->session->userdata('wad_cabang'))? 'selected="selected"':"";
					?>
				  
					<option value="<?php  echo $cab->KODE?>" <?php  echo $sel?>><?php  echo $cab->NAMA?></option>
					<?php  } ?>
				  </select>
				</form>
			  <?php  } ?>
				   
			  </div>
			  <div class="col-sm-6 justify-content-sm-end">
				<div class="form-inline justify-content-sm-end">
				  <form class="form-inline flr">
	                <input class="form-control form-control-sm rounded bright" type="text" value="<?php  echo date("d/m/Y")?>" id="myudate" name="tgl">
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
		                    <th class="text-left">Cabang</th>
							<th class="text-left">Nama</th>
							<th class="text-center">Bukti</th>
							<?php  if($this->session->userdata('wad_level')=="admin"){?>
							<th class="text-center print-hide">Konfirmasi</th>
							<?php  } ?>
							<!--<th class="text-center">Action</th>-->
		                </tr>
		            </thead>
		            <tbody>
		                <?php 
		                if($query->num_rows() > 0){ $n = 1;
		                foreach($query->result() as $key){ $no = $n++;
		                   
		                    ?>
		                    <tr>
		                    <td><?php  echo $no?></td>
		                    <td><?php  echo $key->TGL_AWAL?></td>
		                    <td class="text-right"><?php  echo toRp($key->NOMINAL_PINJ+$key->NOMINAL_SIMP)?></td>
		                    <td><?php  echo $key->CABANG?></td>
							<td class="text-left"><?php  echo $key->NAMA?></td>
							<td class="text-center">
							
								<?php  if($key->BUKTI !=""){?>
								<img src="<?php  echo base_url();?>uploads/bukti/<?php  echo $key->BUKTI?>" width="50">
								<?php  }
else{?>
								<?php   if($this->session->userdata('wad_level')!="admin"){?>
								<form id="form<?php  echo $key->IDCEKKOLEKTOR?>" method="post" action="otherpage.php" enctype="multipart/form-data">
								<input type="hidden" name="id" value="<?php  echo $key->IDCEKKOLEKTOR?>">
								<button class="btn btn-secondary btn-rounded uploadkolektor btn-sm" type="button" data-id="<?php  echo $key->IDCEKKOLEKTOR?>" id="uploadkolektor<?php  echo $key->IDCEKKOLEKTOR?>">Upload</button>
								<center><img src="<?php  echo base_url();?>img/loading.gif" id="loadingkolektor<?php  echo $key->IDCEKKOLEKTOR?>" style="display:none;"></center>
								<input class="form-control buktikolektor" type="file" name="buktikolektor<?php  echo $key->IDCEKKOLEKTOR?>" id="buktikolektor<?php  echo $key->IDCEKKOLEKTOR?>" style="display:none;">
								</form>
									<?php  }?>
							<?php  }?>
							</td>	
							<?php  if($this->session->userdata('wad_level')=="admin"){?>
							<td class="text-center"><?php  if($key->BUKTI !=""){?><a class="btn btn-success btn-sm btnconfkolektor" href="javascript:void(0)" data-id="<?php  echo $key->IDCEKKOLEKTOR?>">Konfirmasi</a><?php  } ?></td>
							<?php  } ?>
		                    <!--<td class="text-center"><a href="<?php  echo base_url();?>detail/kolektor/<?php  echo $key->IDCEKKOLEKTOR?>" class='btn btn-success btn-sm'><i class='fa fa-search'></i></a></td>-->
		                </tr>
		                <?php  }} ?>
		            </tbody>
		            
		        </table>
		    </div>
        </div>
    </div>
</div>

<style type = "text/css">
      @media print {
         .page-header{display: none;}
         .page-body{padding: unset;}
         .content-header{display: none;}
         .left-sidebar{display: none;}
         .btn{display: none;}
         .panel-header{display: none;}
         .dataTables_wrapper .row .dataTables_length{display: none;}
         .dataTables_wrapper .row .dataTables_filter{display: none;}
         .dataTables_wrapper .row .dataTables_info{display: none;}
         .dataTables_wrapper .row .dataTables_paginate{display: none;}
         .content{margin: unset; margin-top: unset; padding: unset;}
         .panel{margin: unset; }
         .panel .panel-content{padding: 5px;}
         html.fixed .content{margin: unset;}
         h4{margin-top: 0px;margin-bottom: 0px;}
         .scroll-to-top{display: none;}
         .head-print{display: block;}
         td.text-center.col-md-1.gallery-wrap{display: none;}
         .text-center.print-hide{display: none;}
         body{line-height: unset;}
         .daterangepicker{display: none;}
      }
      @media screen {
      	.head-print{display: none;}
      }
</style>