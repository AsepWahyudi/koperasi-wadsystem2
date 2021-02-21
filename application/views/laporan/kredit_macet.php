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
                        <h4 class="color-primary">Laporan Kredit Macet</h4>
                    </div>
                    <div class="col-sm-2  col-lg-2 ">
	                    <a onclick="window.print()" class="btn btn-info btn-block" >
	                    	<i class="fa fa-print"></i> 
	                    	Cetak
	                	</a>
	                </div> 
                    <div class="col-sm-2  col-lg-2 ">
                        <a href="<?php  echo base_url();?>laporan/kredit_macet/kreditmacet_excel" class="excel btn btn-success btn-block" id="excel" >
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
                         <form class="form-inline" action="#" method="POST" id="filterForm">
		                  	<!--Periode : &nbsp; 
		                    <input class="form-control form-control-sm rounded bright" type="text" name="tgl" id="myudate">
		                    <button type="button" class="btn btn-sm btn-secondary btn-rounded" id="tampilfilter">Tampilkan</button>-->
		                    <input type="hidden" name="page" id="page" value="1"/>
		                    <input type="hidden" name="dataperpage" id="dataperpage" value="10"/>
		                    <input type="hidden" name="urltarget" id="urltarget" value="laporan/kredit_macet/data"/>
		                  </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-content">
        	<div class="table-responsive" id="table-view">
				 <div class="dataTables_wrapper container-fluid dt-bootstrap4">
					<div class="row">
						<div class="col-sm-12 col-md-6">
							<div class="dataTables_length" id="basic-datatables_length">
								<?php  echo $opt_data_entries?>
							</div>
						</div>
						<div class="col-sm-12 col-md-6">
							<div id="basic-datatables_filter" class="dataTables_filter">
								<label>Cari: <input type="search" id="form-search" class="form-control form-control-sm" aria-controls="basic-datatables"></label>
							</div>
						</div>
					</div><br/>
					<table class="table table-striped table-lightfont">
						<thead>
							<tr>
								<tr>
									<th scope="col">No</th>
									<th scope="col">Nama Anggota</th>
									<th scope="col">Tanggal Pinjam</th>
									<th scope="col">Jatuh Tempo</th>
									<th scope="col">Lama Pinjam</th>
									<th scope="col">Jumlah Tagihan</th>
									<th scope="col">Dibayar</th>
									<th scope="col">Sisa</th>
									<th scope="col">Telat</th>
								</tr>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
					<?php  echo $table_footer?>
				 </div>
		    </div>
        </div>
    </div>
</div>

<style>
	table.font-small th, table.font-small td { vertical-align:top; }
	span.f-right{ float: right;}
	span.macet{color: #de0505; font-weight: 500;}
	span.lancar{color: #3bb906; font-weight: 500;}
</style>
<script>
	var base_url	=	'<?php  echo base_url();?>',
		action		=	'view';
</script>
<script type="text/javascript" src="<?php  echo base_url();?>assets/js/laporan/kreditmacet.js"></script>

<style type = "text/css">
      @media print {
         .page-header{display: none;}
         .page-body{padding: unset;}
         .content-header{display: none;}
         .left-sidebar{display: none;}
         .btn{display: none;}
         .panel-header{display: none;}
         .dataTables_wrapper .row{display: none;}
         .content{margin: unset; margin-top: unset; padding: unset;}
         .panel{margin: unset; }
         .panel .panel-content{padding: 5px;}
         html.fixed .content{margin: unset;}
         h4{margin-top: 0px;margin-bottom: 0px;}
         .scroll-to-top{display: none;}
         .head-print{display: block;}
         body{line-height: unset;font-size: small;}
         .daterangepicker{display: none;}
      }
      @media screen {
        .head-print{display: none;}
      }
</style>
<script>
    var base_url    =   '<?php  echo base_url();?>',
        action      =   'view';

  $("#form-search").on("keyup", function(){
  		var char = document.getElementById("form-search").value;
  		document.getElementById("excel").href = base_url+"laporan/kredit_macet/kreditmacet_excel?id="+char;
  });

</script>