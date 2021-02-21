<?php  $row	=	$data_source->row(); ?>

<?php //echo "idpinjaman = ".$idpinjaman;?>

<?php
$keterangan = "";
//$gettotal =  $this->db->query("SELECT count(*) as total FROM $table")->row();
$ceklancar = $this->db->query("SELECT A.IDPINJM_H, DATE_FORMAT(A.TGL_PINJ, '%d/%m/%Y') TGL_PINJ, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%d/%m/%Y') JATUH_TEMPO, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_FIRST_NUMB, DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_SECOND_NUMB, DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_THIRD_NUMB, DATE_FORMAT(NOW(), '%Y%m%d') NOW_NUMB, A.PINJ_TOTAL, A.PINJ_DIBAYAR, A.PINJ_SISA, A.IS_RESET, A.LUNAS, B.NAMA NAMA_ANGGOTA 
FROM tbl_pinjaman_h A LEFT JOIN m_anggota B ON B.IDANGGOTA = A.ANGGOTA_ID 
WHERE A.IDPINJM_H ='".$idpinjaman."' AND 1=1 
-- AND A.PINJ_SISA > 0 
AND A.LUNAS LIKE 'Lunas' OR 1=1 
AND DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') > DATE_FORMAT(NOW(), '%Y%m%d')")->num_rows();


if($ceklancar > 0){ 
	$keterangan = 'Lancar';
}

$cekragu = $this->db->query("SELECT A.IDPINJM_H, DATE_FORMAT(A.TGL_PINJ, '%d/%m/%Y') TGL_PINJ, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%d/%m/%Y') JATUH_TEMPO, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_FIRST_NUMB, DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_SECOND_NUMB, DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_THIRD_NUMB, DATE_FORMAT(NOW(), '%Y%m%d') NOW_NUMB, A.PINJ_TOTAL, A.PINJ_DIBAYAR, A.PINJ_SISA, A.IS_RESET, A.LUNAS, B.NAMA NAMA_ANGGOTA 
FROM tbl_pinjaman_h A LEFT JOIN m_anggota B ON B.IDANGGOTA = A.ANGGOTA_ID 
WHERE A.IDPINJM_H ='".$idpinjaman."' AND 1=1 AND A.LUNAS LIKE 'Belum' 
AND A.PINJ_SISA > 0 
AND DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') <= DATE_FORMAT(NOW(), '%Y%m%d') 
AND DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') > DATE_FORMAT(NOW(), '%Y%m%d')")->num_rows();
 
if($cekragu > 0){ 
	$keterangan = 'Meragukan';
}                            
  

$cekburuk = $this->db->query("SELECT A.IDPINJM_H, DATE_FORMAT(A.TGL_PINJ, '%d/%m/%Y') TGL_PINJ, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%d/%m/%Y') JATUH_TEMPO, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_FIRST_NUMB, DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_SECOND_NUMB, DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_THIRD_NUMB, DATE_FORMAT(NOW(), '%Y%m%d') NOW_NUMB, A.PINJ_TOTAL, A.PINJ_DIBAYAR, A.PINJ_SISA, A.IS_RESET, A.LUNAS, B.NAMA NAMA_ANGGOTA 
FROM tbl_pinjaman_h A LEFT JOIN m_anggota B ON B.IDANGGOTA = A.ANGGOTA_ID 
WHERE A.IDPINJM_H ='".$idpinjaman."' AND 1=1 
AND A.LUNAS LIKE 'Belum' 
AND A.PINJ_SISA > 0 
AND DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') <= DATE_FORMAT(NOW(), '%Y%m%d') 
AND DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') > DATE_FORMAT(NOW(), '%Y%m%d')")->num_rows();
 
if($cekburuk > 0){ 
	$keterangan = 'Buruk';
}     

$cekmacet = $this->db->query("SELECT A.IDPINJM_H, DATE_FORMAT(A.TGL_PINJ, '%d/%m/%Y') TGL_PINJ, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%d/%m/%Y') JATUH_TEMPO, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_FIRST_NUMB, DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_SECOND_NUMB, DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_THIRD_NUMB, DATE_FORMAT(NOW(), '%Y%m%d') NOW_NUMB, A.PINJ_TOTAL, A.PINJ_DIBAYAR, A.PINJ_SISA, A.IS_RESET, A.LUNAS, B.NAMA NAMA_ANGGOTA 
FROM tbl_pinjaman_h A LEFT JOIN m_anggota B ON B.IDANGGOTA = A.ANGGOTA_ID 
WHERE A.IDPINJM_H ='".$idpinjaman."' AND 1=1 
AND A.LUNAS LIKE 'Belum' 
AND A.PINJ_SISA > 0 
AND DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') <= DATE_FORMAT(NOW(), '%Y%m%d')")->num_rows();
 
if($cekmacet > 0){ 
	$keterangan = 'Macet';
}     
 //echo $keterangan;

?>
<div class="col-sm-12">
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
			<div class="row">
				<div class="col-lg-12">
					<?php  
						switch($this->input->get('r')) 
						{
							case 'pd' : $redirect = 'pinjaman-data'; break;
							case 'pl' : $redirect = 'pinjaman-lunas'; break;
							case 'ra' : $redirect = 'data-angsuran'; break;
							default   : $redirect = 'bayar-angsuran'; break;
						}
					?>
					<?php  if($row->LUNAS == 'Belum') { ?>
						<a class="btn btn-primary btnFormAgs" href="#" onclick="getFormAgs('btnFormAgs')" var-url="pinjaman/pinjaman/formAngsuran?idpj=<?php  echo $row->IDPINJM_H?>&idagt=<?php  echo $row->IDANGGOTA?>"><i class="fa fa-money"></i><span> Bayar Angsuran</span></a>
					<?php  } ?>
					  <a class="btn btn-danger flr" href="<?php  echo base_url() . $redirect; ?>"><i class="fa fa-arrow-left"></i><span> Kembali</span></a>
				</div>
			</div>
        </div>
    </div>
</div>
<div class="col-sm-12">
	<div class="panel">
		<div class="panel-header b-primary bt-sm">
			<div class="row">
                <div class=" col-lg-10">
                    <h4 class="color-primary">Detail Pinjaman</h4>
                </div>
                <div class=" col-lg-2">
                    <a class="btn btn-info flr" href="<?php echo base_url(); ?>pinjaman-cetak/<?php echo $row->IDPINJM_H; ?>"><i class="fa fa-print"></i><span> Cetak Laporan</span></a>
                </div>
            </div>
		</div>
		<div class="panel-content">
			<div class="row">
				<div class="col-sm-12">
					<div class="row m-b">
		            	<div class="col-lg-5">
		                	<div class="table-responsive">
		                    	<?php  
									$temp = array ('table_open' => '<table class="table table-lightborder table-custom">');
									$this->table->set_heading(array(array('data' => '<h3 style="margin:0; padding:0; font-size: 1rem; text-transform: capitalize; color: #09c3c5; ">Data Anggota</h3>', 'colspan' => 3)));
									$this->table->add_row(array(array('data' => 'Nama Anggota', 'width' => '120px'), array('data' => ':', 'width' => '5px'), array('data' => $row->NAMA)));
									$this->table->add_row(array('Alamat', ':', $row->ALAMAT));
									$this->table->add_row(array('Tempat Lahir', ':', $row->TMP_LAHIR));
									$this->table->add_row(array('Tanggal Lahir', ':', tgl_indo($row->TGL_LAHIR)));
									$this->table->add_row(array('Kota Tinggal', ':', $row->KOTA));
									
									$this->table->set_template($temp);
									echo $this->table->generate();
								?>
		                    </div>
		              	</div>
		                
		                <div class="col-lg-7">
		                	<div class="table-responsive">
								<?php  
								
									function pembulatans($uang)
									{
										$ratusan = substr($uang, -2);
										$akhir = $uang + (100-$ratusan);
										return $akhir;
										 
									}
										
									$tot_bayar	= $tot_denda = $tot_basil = $tot_pokok = 0 ;
									if($data_angsuran->num_rows() > 0) {
										foreach($data_angsuran->result() as $res) { 
											$tot_bayar	+=	$res->JUMLAH_BAYAR;
											$tot_denda	+=	$res->DENDA_RP;
											$tot_pokok	+=	$res->POKOKBAYAR;
											$tot_basil	+=	$res->BASILBAYAR;
										}
									}

									$ang_dasar = ($row->JUMLAH / $row->LAMA_ANGSURAN);
									$bas_dasar = $row->PINJ_BASIL_DASAR;
									
									$angdasar = pembulatans($ang_dasar);
									$basdasar = pembulatans($bas_dasar);
									
									$temp = array ('table_open' => '<table class="table table-lightborder table-custom">');
									$this->table->set_heading(array(array('data' => '<h3 style="margin:0; padding:0; font-size: 1rem; text-transform: capitalize; color: #09c3c5; ">Data Pinjaman</h3>', 'colspan' => 6)));
									$this->table->add_row(array(array('data' => 'Tgl Pinjam', 'width' => '120px'), array('data' => ':', 'width' => '7px'), array('data' => date('d M Y', strtotime($row->TGL_PINJ)), 'width' => '150px'),
																array('data' => 'Pokok Pinjaman', 'width' => '140px'), array('data' => ':', 'width' => '7px'), array('data' => toRp($row->JUMLAH))));
																
									$this->table->add_row(array('Jatuh Tempo', ':', (date('d M Y', strtotime($row->TGL_PINJ.'+'. $row->LAMA_ANGSURAN.' month'))),
																'Angsuran Dasar', ':', toRp($angdasar) ));
																
									$this->table->add_row(array('Lama Pinjaman', ':', $row->LAMA_ANGSURAN.' bulan',
																'Basil Dasar', ':', toRp($bas_dasar) ));
																
									$this->table->add_row(array('Status Lunas', ':', $row->LUNAS,
																'Jumlah Angsuran', ':', toRp($angdasar + $bas_dasar)));
																
									$this->table->add_row(array('Total Tagihan', ':', toRp($row->PINJ_RP_ANGSURAN),
																'Tagihan Dibayar', ':', toRp($tot_bayar) ));
									
									$this->table->set_template($temp); 
									echo $this->table->generate();
								?>
		                    </div>
		              	</div>
		            </div>
		            
		            <div class="os-tabs-w">
		            	<div class="os-tabs-controls">
		                    <ul class="nav nav-tabs bigger">
		                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#tab_simulasi">Simulasi Tagihan</a> </li>
		                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#tab_pembayaran">Transaksi Pembayaran</a> </li>
		                        <!-- <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#tab_reset">Biaya Reset</a> </li> -->
		                    </ul>
		              	</div>
		                
		              	<div class="tab-content">
		              		<div class="tab-pane" id="tab_simulasi">
		                    	<div class="table-responsive">
									<?php 
									
										function pembulatan($uang)
										{
											$ratusan = substr($uang, -2);
											$akhir = $uang + (100-$ratusan);
											return $akhir;
											 
										}
										
										$ang_dasar	=	($row->JUMLAH / $row->LAMA_ANGSURAN);
										$bas_dasar	=	((($row->JUMLAH * $row->BUNGA) / 100) / $row->LAMA_ANGSURAN);
		                                $temp = array ('table_open' => '<table class="table table-striped table-bordered">');
										$heading		=	array(array('data' => 'Bln Ke', 'width' => '100px', 'class' => 'text-center'), 
																  array('data' => 'Angsuran Dasar', 'width' => '130px', 'class' => 'text-center'), 
																  array('data' => 'Basil Dasar', 'width' => '120px', 'class' => 'text-center'), 
																  array('data' => 'Jumlah Angsuran', 'width' => '150px', 'class' => 'text-center'), 
																  array('data' => 'Jatuh Tempo', 'width' => '200px', 'class' => 'text-center') );
		                                $this->table->set_heading($heading);
										
										$tot_ang_dasar	= 0;
										$tot_bas_dasar	= 0;
										$tot_jml_ang	= 0;
										for($i = 1; $i <= $row->LAMA_ANGSURAN; $i++) {
											
											$angdasar = pembulatan($ang_dasar);
											$basdasar = pembulatan($bas_dasar);
											
											$tambahangbas = (int)$angdasar+(int)$basdasar;
											
											
											$datarow	=	array(array('data' => $i, 'class' => 'text-center'), 
																  array('data' => number_format($angdasar), 'class' => 'text-right'), 
																  array('data' => number_format($basdasar), 'class' => 'text-right'),
																  array('data' => number_format($tambahangbas), 'class' => 'text-right'),
																  array('data' => tgl_indo(date('Y-m-d', strtotime($row->TGL_PINJ.'+'. $i.' month'))), 'class' => 'text-center')
															);
											$this->table->add_row($datarow);
											$tot_ang_dasar	+= (int)$angdasar;
											$tot_bas_dasar	+= (int)$basdasar;
											$tot_jml_ang	+= $tambahangbas;
										}
		                                
										$this->table->add_row(array(array('data' => '<strong>Jumlah</strong>', 'class' => 'text-center'), 
																	array('data' => '<strong>' . toRp($tot_ang_dasar) . '</strong>', 'class' => 'text-right'), 

																	array('data' => '<strong>' . toRp($tot_bas_dasar) . '</strong>', 'class' => 'text-right'), 
																	array('data' => '<strong>' . toRp($tot_jml_ang) . '</strong>', 'class' => 'text-right'), ''));
		                                
		                                $this->table->set_template($temp);
		                                echo $this->table->generate();
		                            ?>
		                        </div>
		                    </div>
		                	<div class="tab-pane active" id="tab_pembayaran">
		                		<div class="table-responsive">
									<?php 
										$heading = array(array('data' => 'No', 'width' => '10px', 'class' => 'text-center'), 
																  array('data' => 'Tgl Bayar', 'width' => '170px', 'class' => 'text-center'), 
																  array('data' => 'Angsuran Ke', 'width' => '100px', 'class' => 'text-center'), 
																  array('data' => 'Jenis Pembayaran', 'width' => '130px', 'class' => 'text-center'),  
																  array('data' => 'Pokok', 'width' => '110px', 'class' => 'text-center'), 
																  array('data' => 'Basil', 'width' => '110px', 'class' => 'text-center'), 
																  array('data' => 'Total', 'width' => '130px', 'class' => 'text-center'),
																  array('data' => 'Kolektor', 'width' => '130px', 'class' => 'text-center'),
																  array('data' => 'Reset', 'width' => '120px', 'class' => 'text-center'), 
																  array('data' => 'User', 'width' => '120px', 'class' => 'text-center'), 
																  array('data' => 'Action', 'width' => '80px', 'class' => 'text-center') );
		                                $this->table->set_heading($heading);
										$n = 1;
										$tot_bayar	= $tot_denda = $tot_basil = $tot_pokok = 0 ;
										if($data_angsuran->num_rows() > 0) {
											foreach($data_angsuran->result() as $res) { $no = $n++;
												$addrow	=	array($no, tgl_indo($res->TGL_BAYAR), 
																  array('data' => $res->ANGSURAN_KE, 'class' => 'text-center'), 
																  $res->KET_BAYAR,
																  // array('data' => toRp($res->POKOKBAYAR), 'class' => 'text-right'),
																  array('data' => toRp($res->JUMLAH_BAYAR-$res->BASILBAYAR), 'class' => 'text-right'),
																  array('data' => toRp($res->BASILBAYAR), 'class' => 'text-right'),
																  array('data' => toRp($res->JUMLAH_BAYAR), 'class' => 'text-right'),
																  array('data' => toRp($res->BIAYA_KOLEKTOR), 'class' => 'text-right'),
																  array('data' => toRp($res->DENDA_RP), 'class' => 'text-right'),
																  $res->USERNAME,  '<a class="btn btn-info" href="cetak-struk/'.$res->IDPINJ_D.'"><i class="fa fa-print"></i><span> Struk</span></a>');
												$this->table->add_row($addrow);
												$tot_bayar	+=	$res->JUMLAH_BAYAR;
												$tot_denda	+=	$res->DENDA_RP;
												$tot_pokok	+=	$res->POKOKBAYAR;
												$tot_kol	+=	$res->BIAYA_KOLEKTOR;
												$tot_basil	+=	$res->BASILBAYAR;
											}
										}
										$this->table->add_row(array(array('data' => '<strong>Jumlah</strong>', 'class' => 'text-right', 'colspan' => '4'),
																	array('data' => '<strong>' . toRp($tot_pokok) . '</strong>', 'class' => 'text-right'),
																	array('data' => '<strong>' . toRp($tot_basil) . '</strong>', 'class' => 'text-right'),
																	array('data' => '<strong>' . toRp($tot_bayar) . '</strong>', 'class' => 'text-right'),
																	array('data' => '<strong>' . toRp($tot_kol) . '</strong>', 'class' => 'text-right'),
																	array('data' => '<strong>' . toRp($tot_denda) . '</strong>', 'class' => 'text-right'), '', ''));
		                                
										$this->table->set_template($temp);
		                                echo $this->table->generate();
										
										
										$heading = array(array('data' => 'No', 'width' => '10px', 'class' => 'text-center'), 
														 array('data' => 'Tgl Reset', 'width' => '170px', 'class' => 'text-center'), 
														 //array('data' => 'Angsuran Ke', 'width' => '100px', 'class' => 'text-center'), 
													     array('data' => 'Jumlah', 'width' => '130px', 'class' => 'text-center'),  
														 array('data' => 'Status', 'width' => '110px', 'class' => 'text-center'));
		                               
										$tot_reset = 0;
										$n = 1;
										if($data_reset->num_rows() > 0) {
											foreach($data_reset->result() as $res) { $no = $n++;
												$addrow	=	array($no, 
																  tgl_indo($res->TANGGAL), 
																  //array('data' => $res->ANGSURAN_KE, 'class' => 'text-center'), 
																  array('data' => toRp($res->JUMLAH), 'class' => 'text-right'),
																  array('data' => ($res->LUNAS == '1' ? 'Lunas' : 'Belum lunas'), 'class' => 'text-center')
																);
												$this->table->add_row($addrow);
												$tot_reset	+=	$res->JUMLAH;
											}
											$this->table->add_row(array(array('data' => '<strong>Jumlah</strong>', 'class' => 'text-right', 'colspan' => '2'),
																		array('data' => '<strong>' . toRp($tot_reset) . '</strong>', 'class' => 'text-right'), ''
																	));
											
											
											echo '<h5 class="element-header">Biaya Reset</h5>';
											$this->table->set_heading($heading);
											$this->table->set_template($temp);
		                                	echo $this->table->generate();
										}
										
										$heading		=	array(array('data' => 'No', 'width' => '10px', 'class' => 'text-center'), 
																  array('data' => 'Periode', 'width' => '170px', 'class' => 'text-center'), 
																  array('data' => 'Jumlah', 'width' => '130px', 'class' => 'text-center'),  
																  array('data' => 'Status', 'width' => '110px', 'class' => 'text-center') );
		                                
										
										$tot_kolektor	=	0;
										$n = 1;
										if($data_kolektor->num_rows() > 0) {
											foreach($data_kolektor->result() as $res) { $no = $n++;
												$addrow	=	array($no, 
																  blth_indo($res->TANGGAL), 
																  array('data' => toRp($res->JUMLAH), 'class' => 'text-right'),
																  array('data' => ($res->LUNAS == '1' ? 'Lunas' : 'Belum lunas'), 'class' => 'text-center')
																);
												$this->table->add_row($addrow);
												$tot_kolektor	+=	$res->JUMLAH;
											}
											$this->table->add_row(array(array('data' => '<strong>Jumlah</strong>', 'class' => 'text-right', 'colspan' => '2'),
																		array('data' => '<strong>' . toRp($tot_kolektor) . '</strong>', 'class' => 'text-right'), ''
																	));
																	
											echo '<h5 class="element-header">Biaya Kolektor</h5>';
											$this->table->set_heading($heading);
											$this->table->set_template($temp);
		                                	echo $this->table->generate();
										}
									?>
		                		</div>
		                    </div>  
		                </div>
		                
		           	</div>
               </div>
			</div>
		</div>
	</div>
</div>

<div aria-hidden="true" aria-labelledby="modalAngsuran" class="modal" id="modalAngsuran" role="dialog" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 0px solid #eee;">
                <h5 class="modal-title" id="modalLabel">Form Pembayaran Angsuran</h5>
                <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> &times;</span></button>
            </div> 
            <div class="modal-body" id="modalBody">
                <?php 
				
					$temp = array ('table_open' => '<table class="table table-lightborder table-custom">');
					$this->table->add_row(array(array('data' => 'Tgl Transaksi', 'width' => '200px'), array('data' => ':', 'width' => '10px'), array('data' => '<input class="single-daterange form-control" type="text" name="tgl_trx" id="tgl_trx">' )));
					$this->table->add_row(array('Status', ':', $keterangan ));
					$this->table->add_row(array('Angsuran Ke', ':', '<span id="ags_ke"></span>' ));
					$this->table->add_row(array('Sisa Angsuran', ':', '<span id="sisa_ags"></span>' ));
					$this->table->add_row(array('Sisa Tagihan', ':', '<span id="sisa_tag"></span>' ));
					$this->table->add_row(array('Angsuran per bulan', ':', '<span id="ags_perbulan"></span>' ));
					$this->table->add_row(array('Bayar Dengan Saldo', ':', '<input type="checkbox" id="chk_bayar_saldo" onclick="chk_bayar()" name="chk_bayar_saldo"> : <input type="text" name="bayar_saldo" id="bayar_saldo" value="0" readonly onkeyup="cheksaldo(this.value, \'bayar_saldo\')"> (<span id="saldo_sekarang"></span>)' ));
					$this->table->add_row(array('Jumlah Pembayaran', ':', '<input type="text" class="form-control" name="jml_angsuran" id="jml_angsuran" onkeyup="cheksaldo(this.value, \'jml_angsuran\')">' ));
					 
					
					$this->table->add_row(array('Bayar Pokok', ':', '<span id="bayar_pokok_text"></span><input type="hidden" name="bayar_pokok" id="bayar_pokok">' ));
					$this->table->add_row(array('Bayar Basil', ':', '<span id="bayar_basil_text"></span><input type="hidden" name="bayar_basil" id="bayar_basil">' ));
					
					if($keterangan == 'Meragukan' OR $keterangan == 'Buruk' OR $keterangan == 'Macet')
					{ 
					    $this->table->add_row(array('Biaya Reset', ':', '<input type="text" class="form-control" name="biaya_reset" id="biaya_reset" value="0" onkeyup="cheksaldo(this.value, \'biaya_reset\')">' ));
					}
					else
					{
					    $this->table->add_row(array('Biaya Reset', ':', '<input type="hidden" class="form-control" name="biaya_reset" id="biaya_reset" value="0" onkeyup="cheksaldo(this.value, \'biaya_reset\')">' ));
					}
					 
					
					$tglnow        = date("d-m-Y");
                    $settglnow     = strtotime($tglnow); 
                    
                    $tgljatuhtempo = (date('d-m-Y', strtotime($row->TGL_PINJ.'+'. $row->LAMA_ANGSURAN.' month')));
                    $setjthtempo   = strtotime($tgljatuhtempo);   
                    $hitungtgl     = $setjthtempo - $settglnow;  
                    $hari          = ($hitungtgl/24/60/60);	

					if($getdata->LUNAS !="Lunas" AND $hari < 0)
					{
						$this->table->add_row(array('Biaya Kolektor', ':', '<input type="text" class="form-control" name="biaya_kolektor" id="biaya_kolektor" value="30,000" onkeyup="cheksaldo(this.value, \'biaya_kolektor\')">' ));
					}
					else
					{
						$this->table->add_row(array('Biaya Kolektor', ':', '<input type="hidden" class="form-control" name="biaya_kolektor" id="biaya_kolektor" value="0" onkeyup="cheksaldo(this.value, \'biaya_kolektor\')">' ));
					}
					$this->table->add_row(array('Keterangan', ':', '<input type="text" class="form-control" name="keterangan" id="keterangan">' ));
					$this->table->add_row(array('Simpan Ke Kas', ':', '<select class="form-control" name="simpan_ke" id="simpan_ke" onchange="check_ags()"></select>' ));
					
					$this->table->set_template($temp);
					echo $this->table->generate();
				
				?>
            </div>
        
            <div class="modal-footer" id="modalFooter">
                <input type="hidden" id="idanggota">
                <input type="hidden" id="idpinjam">
                <button class="btn btn-secondary" data-dismiss="modal" type="button"> Close</button>
                <button class="btn btn-primary save_angsuran" type="button" onclick="save_angsuran()" style="visibility:hidden" var-url="pinjaman/pinjaman/save_angsuran"> Simpan</button>
            </div>
            
        </div>
    </div>
</div>
<script>
	var base_url 		= '<?php  echo base_url();?>';
	var saldo_sekarang 	= 0;
	var sisa_tagihan 	= 0;
	var ags_perbulan	= 0;
	var ags_ke			= 1;
	var pinj_basil_dasar	= 0;
	var ags_perbulan_pokok	= 0;
	var pinj_pokok_sisa		= 0;
	var pinj_basil_total	= 0;
	var pinj_basil_dibayar	= 0;
	var pinj_basil_sisa		= 0;
	var biaya_reset			= 0;
	var biaya_kolektor		= 0;
	var jumlah_angsuran		= 0;
	function getFormAgs(idload){
		$().ready(function () {
			$("#modalAngsuran").modal("show");
			var urldata	= base_url + $('.' + idload).attr('var-url');
			$.ajax({
				type: "POST",
				url: urldata,
				data: 'data=',
				cache: false,
					success: function(msg){ 
					
						var obj	= JSON.parse(msg);
						
						ags_ke	=	obj.ags_ke; 
						bunga	    		= obj.bunga; // SISA TAGIHAN
						sisa_tagihan	    = obj.sisatagihan.replace(/\,|\./g,''); // SISA TAGIHAN
						ags_perbulan	    = obj.angsuranperbulan.replace(/\,|\./g,''); // ANGSURAN PERBULAN
						// ags_perbulan	    = eval(ags_perbulan) > eval(sisa_tagihan) ? sisa_tagihan : ags_perbulan; 
						pinj_pokok_sisa		= obj.pinj_pokok_sisa.replace(/\,|\./g,'');
						pinj_basil_dasar	= obj.pinj_basil_dasar.replace(/\,|\./g,'');
						pinj_basil_total	= obj.pinj_basil_total.replace(/\,|\./g,'');
						pinj_basil_dibayar	= obj.pinj_basil_dibayar.replace(/\,|\./g,'');
						pinj_basil_sisa		= eval(pinj_basil_total) - eval(pinj_basil_dibayar) ;
						// ags_perbulan		= eval(ags_perbulan) + eval(pinj_basil_dasar); 
						ags_perbulan_pokok	= eval(ags_perbulan) - eval(pinj_basil_dasar) ;
						
						<?php
						if($keterangan == 'Meragukan' OR $keterangan == 'Buruk' OR $keterangan == 'Macet')
						{
						?>
							// var getbunga = (pinj_basil_total/pinj_pokok_sisa)*100;
							var hitreset = (sisa_tagihan/100)*bunga;
 
							$('#biaya_reset').val(rupiah(hitreset));
						 
						<?php 
						}
						else
						{ 
						?>
							$('#biaya_reset').val(0);
						<?php
						}
						?>
						  
						$('#ags_ke').html(obj.ags_ke); 
						$('#sisa_ags').html(obj.sisa_ags);
						 
						$('#sisa_tag').html(rupiah(obj.sisatagihan));
						
						$('#ags_perbulan').html(rupiah(obj.angsuranperbulan));
					 
						$('#jml_angsuran').val(rupiah(obj.sisatagihan));
						$('#bayar_pokok_text').html(rupiah(obj.bayarpokok));
						$('#bayar_pokok').val(rupiah(obj.bayarpokok));
						$('#bayar_basil_text').html(rupiah(obj.bayarbasil));
						$('#bayar_basil').val(rupiah(obj.bayarbasil));
						
						var sisa_ags = obj.sisa_ags;
						var sisa_tag = eval(obj.sisatagihan.replace(/\,|\./g,''));
						// var bayar_pokok = eval(pinj_pokok_sisa) <= 0 ? 0 : ags_perbulan_pokok;
						var bayar_basil = eval(pinj_pokok_sisa) <= 0 ? ags_perbulan : pinj_basil_dasar;
						  
						if (sisa_ags == 0) 
						{
							// $('#ags_perbulan').html(rupiah(obj.sisatagihan));
							// $('#jml_angsuran').val(rupiah(obj.sisatagihan));
							
							var getjmlangsuran = $("#jml_angsuran").val();
    						var biayaangsuran  = getjmlangsuran.replace(/\,|\./g,''); 
							
							var getreset       = $("#biaya_reset").val();
    						var biayareset     = getreset.replace(/\,|\./g,''); 
							
    						var getkolektor    = $("#biaya_kolektor").val();
    						var biayakolektor  = getkolektor.replace(/\,|\./g,''); 
							
					    	var totalbayar     = eval(biayaangsuran) + eval(biayareset) + eval(biayakolektor);
						
							$('#jml_angsuran').val(rupiah(totalbayar));
							// $('#bayar_pokok').val(rupiah(sisa_tag - bayar_basil));
							jumlah_angsuran = obj.sisatagihan;
						}
						else
						{
							// $('#ags_perbulan').html(rupiah(ags_perbulan) + ' (pokok + basil)');
							// $('#jml_angsuran').val(rupiah(sisatagihan));
							
				            var getjmlangsuran = $("#jml_angsuran").val();
    						var biayaangsuran  = getjmlangsuran.replace(/\,|\./g,'');
    						
							var getreset       = $("#biaya_reset").val();
    						var biayareset     = getreset.replace(/\,|\./g,'');
    						
    						var getkolektor    = $("#biaya_kolektor").val();
    						var biayakolektor  = getkolektor.replace(/\,|\./g,'');
    						
					    	var totalbayar     = eval(biayaangsuran) + eval(biayareset) + eval(biayakolektor);
							$('#jml_angsuran').val(rupiah(totalbayar));
							// $('#bayar_pokok').val(rupiah(bayar_pokok));
							jumlah_angsuran = ags_perbulan;
						}
						
						$('#simpan_ke').html(_kas(obj.simpan_kas));
						$('#idanggota').val(obj.idagt);
						$('#idpinjam').val(obj.idpinjam);
						 
						saldo_sekarang = _send_request_ajax(base_url + 'general/get_total_saldo', 'idanggota=' + obj.idagt );
						$('#saldo_sekarang').html(rupiah(saldo_sekarang));
						 
					}, error: function (result) {
						var teks = result['status'] + " - " + result['statusText'];
						$('#informationModalText').html(teks);
						$('#informationModal').modal('show');
					}
			});
		});
	}
	
	$('#jml_angsuran').keyup(function(){
		// var nilai = jumlah_angsuran;//eval($(this).val().replace(/\,|\./g,''));
		// $(this).val(rupiah(nilai));
		check_ags();
		// cheksaldo(nilai, 'jml_angsuran');
		
	}).focus(function(){ $(this).select() });

	/* $('#bayar_pokok').keyup(function(){
		var nilai	= eval($(this).val().replace(/\,|\./g,''));
		$(this).val(rupiah(nilai));
		  
		getTotalPembayaran();
		
	}).focus(function(){ $(this).select() }); */

	/* $('#bayar_basil').keyup(function(){
		var nilai	= eval($(this).val().replace(/\,|\./g,''));
		$(this).val(rupiah(nilai));
		
		//cheksaldo(nilai, 'jml_angsuran');

		getTotalPembayaran();
		
	}).focus(function(){ $(this).select() }); */

	$('#biaya_reset').keyup(function(){
		var nilai	= eval($(this).val().replace(/\,|\./g,''));
		$(this).val(rupiah(nilai));
		
		//cheksaldo(nilai, 'jml_angsuran');

		getTotalPembayaran();
		
	}).focus(function(){ $(this).select() });

	$('#biaya_kolektor').keyup(function(){
		var nilai	= eval($(this).val().replace(/\,|\./g,''));
		$(this).val(rupiah(nilai));
		
		//cheksaldo(nilai, 'jml_angsuran');

		getTotalPembayaran();
		
	}).focus(function(){ $(this).select() });

	function getTotalPembayaran(){
		var bayar_pokok		= $('#bayar_pokok').val() == '' ? 0 : eval($('#bayar_pokok').val().replace(/\,|\./g,''));
		var bayar_basil		= $('#bayar_basil').val() == '' ? 0 : eval($('#bayar_basil').val().replace(/\,|\./g,''));
		var bayar_reset		= $('#biaya_reset').val() == '' ? 0 : eval($('#biaya_reset').val().replace(/\,|\./g,''));
		var bayar_kolektor	= $('#biaya_kolektor').val() == '' ? 0 : eval($('#biaya_kolektor').val().replace(/\,|\./g,''));
		var total = (eval(bayar_pokok) + eval(bayar_basil) + eval(bayar_reset) + eval(bayar_kolektor));

		$("#jml_angsuran").val(rupiah(total));
		jumlah_angsuran = total;
	}
	
	function save_angsuran() {
		
		var jml_angsuran	= $('#jml_angsuran').val() == '' ? 0 : eval($('#jml_angsuran').val().replace(/\,|\./g,''));
		var bayar_saldo		= $('#bayar_saldo').val() == '' ? 0 : eval($('#bayar_saldo').val().replace(/\,|\./g,''));
		var bayar_pokok		= $('#bayar_pokok').val() == '' ? 0 : eval($('#bayar_pokok').val().replace(/\,|\./g,''));
		var bayar_basil		= $('#bayar_basil').val() == '' ? 0 : eval($('#bayar_basil').val().replace(/\,|\./g,''));
		var bayar_reset		= $('#biaya_reset').val() == '' ? 0 : eval($('#biaya_reset').val().replace(/\,|\./g,''));
		var bayar_kolektor	= $('#biaya_kolektor').val() == '' ? 0 : eval($('#biaya_kolektor').val().replace(/\,|\./g,''));
		
		// if(eval(jml_angsuran) != (eval(bayar_pokok) + eval(bayar_basil) + eval(bayar_reset) + eval(bayar_kolektor)) ) {
		// 	$('#informationModalText').html("Jumlah angsuran tidak sesuai dengan pokok + basil");
		// 	$('#informationModal').modal('show');
		// 	return false;
		// }
		
		if($('#chk_bayar_saldo').prop("checked") == true) 
		{
			if((bayar_saldo < 0 ) || (jml_angsuran <= 0 )) 
			{
				$('#informationModalText').html("Harap mengisi jumlah angsuran");
				$('#informationModal').modal('show');
				return false;
			}
		} 
		else 
		{
			if(jml_angsuran <= 0 ) 
			{
				$('#informationModalText').html("Harap mengisi jumlah angsuran");
				$('#informationModal').modal('show');
				return false;
			}
		}
		
		if($('#simpan_ke').val() == "0") 
		{ 
			$('#informationModalText').html("Harap Pilih Kas"); $('#informationModal').modal('show'); return false;
		}
		
		var urldata	= base_url + $('.save_angsuran').attr('var-url');
		$.ajax({
			type: "POST",
			url: urldata,
			data : {'chk_bayar_saldo' : $('#chk_bayar_saldo').prop('checked'),
					'tgl_trx'         : $('#tgl_trx').val(),
					'bayar_saldo'     : bayar_saldo,
					'jml_angsuran'    : jml_angsuran,
					'bayar_pokok'     : bayar_pokok,
					'bayar_basil'     : bayar_basil,
					'biaya_reset'     : bayar_reset,
					'biaya_kolektor'  : bayar_kolektor,
					'simpan_ke'       : $('#simpan_ke').val(),
					'keterangan'      : $('#keterangan').val(),
					'idanggota'       : $('#idanggota').val(),
					'idpinjam'        : $('#idpinjam').val(),
					'ags_ke'          : ags_ke
				  },
			cache: false,
			success: function(msg){
				location.reload();
			}, error: function (result) {
				var teks = result['status'] + " - " + result['statusText'];
				$('#informationModalText').html(teks);
				$('#informationModal').modal('show');
			}
		});
		
	}
	function cheksaldo(value, target){ //console.log(value + "   " + sisa_tagihan);
		
		_val = eval(value.replace(/\,|\./g,''));
		_max = eval(saldo_sekarang.replace(/\,|\./g,''));
		_ags = eval($('#jml_angsuran').val().replace(/\,|\./g,''));
		_tgh = eval(sisa_tagihan.replace(/\,|\./g,''));
		 
		if(target == 'bayar_saldo') 
		{
			if (_val > _max && _val < _ags) 
			{
				$('#' + target).val(rupiah(_max)).focus();
				//alert("KONDISI 1");
			}
			if (_val > _tgh && _max > _tgh) 
			{ 
				$('#' + target).val(rupiah(_tgh)).focus();
				// alert("VAL > " + _val + " SISA TAGIHAN " + _tgh + " SALDO > " + _max + " SISA TAGIHAN " + _tgh);
			}
		} 
		else if(target == 'bayar_pokok') 
		{
			if (eval(pinj_pokok_sisa) <= 0) 
			{
				$('#' + target).val(rupiah(0)).focus();
			}
			if (_val > eval(pinj_pokok_sisa)) 
			{
				$('#' + target).val(rupiah(pinj_pokok_sisa)).focus();
			}
		} 
		else if(target == 'bayar_basil') 
		{
			$('#' + target).val(rupiah(_val)).focus();
			// if (eval(pinj_basil_sisa) <= 0) {
			// 	$('#' + target).val(rupiah(0)).focus();
			// }
			// if (_val > eval(pinj_basil_sisa)) {
			// 	$('#' + target).val(rupiah(pinj_basil_sisa)).focus();
			// }
		} 
		else if(target == 'biaya_reset') 
		{
			if (eval(biaya_reset) <= 0) 
			{
				$('#' + target).val(rupiah(0)).focus();
			}
			if (_val > eval(biaya_reset)) 
			{
				$('#' + target).val(rupiah(biaya_reset)).focus();
			}
		} 
		else if(target == 'biaya_kolektor') 
		{
			if (eval(biaya_kolektor) <= 0) 
			{
				$('#' + target).val(rupiah(0)).focus();
			}
			if (_val > eval(biaya_kolektor)) 
			{
				$('#' + target).val(rupiah(biaya_kolektor)).focus();
			}
		}
		else 
		{
			// if (_val > _tgh) 
			// {
                // $('#' + target).val(rupiah(_tgh));
		    // }
		}
		check_ags();
	}
	
	function check_ags() {
		
		_angsuran = ags_perbulan;
		$('.save_angsuran').attr('style','visibility="hidden"');
		
		var nilai_angsuran	= eval($('#jml_angsuran').val().replace(/\,|\./g,''));
		var bayar_saldo		= eval($('#bayar_saldo').val().replace(/\,|\./g,''));
		var biaya_reset		= eval($('#biaya_reset').val().replace(/\,|\./g,''));
		var biaya_kolektor  = eval($('#biaya_kolektor').val().replace(/\,|\./g,''));
			
		if($('#chk_bayar_saldo').prop("checked") == true) 
		{
			
			if((bayar_saldo > 100 ) || (nilai_angsuran > 0 ) )
			{
				$('.save_angsuran').attr('style','visibility="visible"');
			} 
			else 
			{
				$('#jml_angsuran').val(_angsuran);
				jumlah_angsuran = _angsuran;
				$('.save_angsuran').attr('style','visibility="visible"');
			}
		} 
		else 
		{
			// if(nilai_angsuran > 0 && nilai_angsuran <= sisa_tagihan) 
			if(nilai_angsuran > 0) 
			{
				 
				$('.save_angsuran').attr('style','visibility="visible"');
			} 
			else  
			{
				
				 
				$('#jml_angsuran').val(rupiah(_angsuran));
				jumlah_angsuran = _angsuran;
				$('.save_angsuran').attr('style','visibility="visible"');
			}
		}
	}
	
	function _kas(data) {
		html	=	'<option value="0" disabled selected>Pilih kas</option>';
		for(i=0; i<data.length; i++){
			html	+=	'<option value="' + data[i].IDAKUN + '">' + data[i].NAMA_KAS + '</option>';
			/* if(data[i]._header == 1) {
				html	+=	'<option value="" disabled>' + _nbsp(data[i]._level) + data[i].JENIS_TRANSAKSI + '</option>';
			} else {
				html	+=	'<option value="' + data[i].IDAKUN + '">' + _nbsp(data[i]._level) + data[i].JENIS_TRANSAKSI + '</option>';
			}*/
		}
		return html;
	}
	
	function _nbsp(level){
		var html = '';
		for(var i = 0; i<= level; i++){
			html	+=	'&nbsp;&nbsp;&nbsp;';
		}
		return html;
	}
	
	function chk_bayar() {
		$('#bayar_saldo').attr('readonly', 'readonly');
		if($('#chk_bayar_saldo').prop('checked') == true) {
			$('#bayar_saldo').removeAttr('readonly');
			$('#bayar_saldo').focus();
		}
	}
	
	var _send_request_ajax = function(urlsend, datapost){
		var getRespon = $.ajax({
			type: 'POST',       
			url: urlsend,
			data: datapost,
			dataType: 'html',
			context: document.body,
			global: false,
			async:false,
			success: function(data) {
				return data;
			}
		}).responseText;
		
		return getRespon;
	}
</script>