<h6 class="element-header">
    Laporan Pembagian SHU
</h6>
<div class="element-box">
	<div class="controls-above-table">
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
                  <form class="form-inline" action="" method="GET">
                  	Periode : &nbsp; 
                    <input class="form-control form-control-sm rounded bright multi-daterange" type="text" name="tgl" value="<?php  echo date('01/01/Y - 12/31/Y')?>">
                    <button type="submit" class="btn btn-sm btn-secondary btn-rounded" id="tampilfilter">Tampilkan</button>
                  </form>
                </div>
              </div>
              
			</div>
		</div>
		
		<hr>
	<?php 
		$dibayar		=	$arr_pinj['DIBAYAR'];
		$pinjaman 		=	$arr_pinj['PINJAMAN']; 
		$laba_pinjaman	=	$dibayar - $pinjaman;
		
		$pendapatan		=	$arr_pend['PENDAPATAN'];
		$jml_pendapatan	=	$laba_pinjaman + $pendapatan;
		
		$biaya			=	$arr_pend['BIAYA'];
		$shu_belum 		=	$jml_pendapatan - $biaya; #SHU Sebelum Pajak
		
		$pajak 			=	($shu_belum * $arr_opsi['pjk_pph'] / 100); # Pajak
		$shu_setelah_pajak	=	$shu_belum - $pajak;
		
		$dana_cadangan	=	$arr_opsi['dana_cadangan'] * $shu_setelah_pajak/100; 
		$jasa_anggota 	=	$arr_opsi['jasa_anggota'] * $shu_setelah_pajak/100; 
		$dana_pengurus 	=	$arr_opsi['dana_pengurus'] * $shu_setelah_pajak/100; 
		$dana_karyawan	=	$arr_opsi['dana_karyawan'] * $shu_setelah_pajak/100; 
		$dana_pend		= 	$arr_opsi['dana_pend'] * $shu_setelah_pajak/100; 
		$dana_sosial	= 	$arr_opsi['dana_sosial'] * $shu_setelah_pajak/100; 
		
		$tot_simpanan	=	$arr_simp['SIMPANAN'] - $arr_simp['PENARIKAN'];
		
		$jasa_modal		=	$arr_opsi['jasa_modal'] * $jasa_anggota/100; 
		$jasa_usaha		=	$arr_opsi['jasa_usaha'] * $jasa_anggota/100; 
		
		$tgl			=	$this->input->get('tgl') != ""? $this->input->get('tgl') : date('Y') . '/01/01 - ' . date('Y') . '/12/31';
		$tgl			=	explode('-', $tgl);
	?>
    <div class="table-responsive" id="table-view">
		 <div class="dataTables_wrapper container-fluid dt-bootstrap4">
			<h4 style="text-align: center; font-size: 1.2rem;">Laporan Pembagian SHU Periode <?php  echo date('d M Y', strtotime(trim($tgl[0]))) . " - " . date('d M Y', strtotime(trim($tgl[1]))); ?></h4>
			<table class="table table-lightfont">
				<thead>
					<tr>
                        <td colspan="2">SHU Sebelum Pajak</td>
                        <td width="200" class="text-right"><?php  echo toRp($shu_belum)?></td>
					</tr>
                    <tr>
                        <td colspan="2">Pajak PPh (<?php  echo $arr_opsi['pjk_pph'] ?>%)</td>
                        <td class="text-right"><?php  echo toRp($pajak)?></td>
					</tr>
                    <tr>
                        <td colspan="2">SHU Setelah Pajak</td>
                        <td class="text-right"><?php  echo toRp($shu_setelah_pajak)?></td>
					</tr>
				</thead>
				<tbody>
                    <tr>
                        <td colspan="3"><strong>PEMBAGIAN SHU UNTUK DANA-DANA</strong></td>
					</tr>
                    <tr>
                        <td>Dana Cadangan</td>
                        <td><?php  echo $arr_opsi['dana_cadangan'] ?>%</td>
                        <td class="text-right"><?php  echo toRp($dana_cadangan)?></td>
					</tr>
                    <tr>
                        <td>Jasa Anggota</td>
                        <td><?php  echo $arr_opsi['jasa_anggota'] ?>%</td>
                        <td class="text-right"><?php  echo toRp($jasa_anggota)?></td>
					</tr>
                    <tr>
                        <td>Dana Pengurus</td>
                        <td><?php  echo $arr_opsi['dana_pengurus'] ?>%</td>
                        <td class="text-right"><?php  echo toRp($dana_pengurus)?></td>
					</tr>
                    <tr>
                        <td>Dana Karyawan</td>
                        <td><?php  echo $arr_opsi['dana_karyawan'] ?>%</td>
                        <td class="text-right"><?php  echo toRp($dana_karyawan)?></td>
					</tr>
                    <tr>
                        <td>Dana Pendidikan</td>
                        <td><?php  echo $arr_opsi['dana_pend'] ?>%</td>
                        <td class="text-right"><?php  echo toRp($dana_pend)?></td>
					</tr>
                    <tr>
                        <td>Dana Sosial</td>
                        <td><?php  echo $arr_opsi['dana_sosial'] ?>%</td>
                        <td class="text-right"><?php  echo toRp($dana_sosial)?></td>
					</tr>
                	
                     <tr>
                        <td colspan="3"><strong>PEMBAGIAN SHU ANGGOTA</strong></td>
					</tr>
                    <tr>
                        <td>Jasa Usaha</td>
                        <td><?php  echo $arr_opsi['jasa_usaha'] ?>%</td>
                        <td class="text-right"><?php  echo toRp($jasa_usaha)?></td>
					</tr>
                    <tr>
                        <td>Jasa Modal</td>
                        <td><?php  echo $arr_opsi['jasa_modal'] ?>%</td>
                        <td class="text-right"><?php  echo toRp($jasa_modal)?></td>
					</tr>
                    <tr>
                        <td colspan="2">Total Pendapatan Anggota</td>
                        <td class="text-right"><?php  echo toRp($laba_pinjaman)?></td>
					</tr>
                    <tr>
                        <td colspan="2">Total Simpanan Anggota</td>
                        <td class="text-right"><?php  echo toRp($tot_simpanan)?></td>
					</tr>
                </tbody>
			</table>
			<?php  //$table_footer?>
		 </div>
    </div>
</div>
<style>
	.table thead td { font-weight: 500 !important; background: #eee;}
</style>