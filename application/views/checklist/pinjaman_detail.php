<?php  $row	=	$data_source->row(); ?>
<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <div class="row">
                <div class="col-sm-9 col-lg-9">
                    <h4 class="color-primary">Data Pengajuan Pinjaman</h4>
                </div>
                <div class="col-sm-3  col-lg-3 ">
                    <a href="<?php  echo base_url();?>list-pengajuan-pinjaman" class="btn btn-primary btn-sm btn-block" >
                        <i class="fa fa-angle-double-left"></i> 
                        Data Pengajuan Pinjaman
                    </a>
                </div>
            </div>
        </div>
    
        <div class="panel-content">        		
        	<div class="row">
        		<div class="col-sm-12 controls-above-tables">
					<a class="btn btn-warning btn-sm" href="<?php  echo base_url();?>permohonan/<?php  echo $row->IDPINJM_H?>"><i class="fa fa-import"></i><span> Permohonan Pembiayaan</span></a>
					<a class="btn btn-danger btn-sm" href="<?php  echo base_url();?>perjanjianbasil/<?php  echo $row->IDPINJM_H?>"><i class="fa fa-import"></i><span> Perjanjian Bagi Hasil</span></a>
					<a class="btn btn-success btn-sm" href="<?php  echo base_url();?>kontrak/<?php  echo $row->IDPINJM_H?>"><i class="fa fa-import"></i><span> Kontrak Perjanjian</span></a>
				</div>
        	</div>
        </div>
    </div>
</div>

<div class="col-sm-5">
	<div class="panel">
        <div class="panel-header b-primary bt-sm">
            <div class="row">
            	<div class="col-sm-9 col-lg-9">
                    <h4 class="color-primary">Data Pengajuan</h4>
                </div>
            </div>
        </div>
        <div class="panel-content">
        	<div class="row">
            	<div class="col-lg-12">
                    <div class="table-responsive">
                        <?php  
                            $bagi_hasil=(((int)$row->BASIL_DASAR)*$row->LAMA_ANGSURAN*100)/$row->JUMLAH;
                             $temp = array ('table_open' => '<table class="table table-lightborder table-custom">');
                            $this->table->add_row(array(array('data' => 'Nama Lengkap', 'width' => '30%', 'style' => 'border:none'), array('data' => ':', 'width' => '10px', 'style' => 'border:none'), array('data' => $row->NAMA_ANGGOTA, 'style' => 'border:none')));
                            $this->table->add_row(array('Alamat', ':', $row->ALAMAT));
                            $this->table->add_row(array('Tgl. Pengajuan', ':', tgl_indo($row->TGL_PINJ)));
                            $this->table->add_row(array('Jenis Pembiayaan', ':', $row->JNS_PINJ));
                            $this->table->add_row(array('Nilai Pembiayaan', ':', toRp($row->JUMLAH)));
                            $this->table->add_row(array('Lama Angsuran', ':',  $row->LAMA_ANGSURAN . ' bulan'));
                            $this->table->add_row(array('Bagi Hasil', ':', $bagi_hasil . ' %'));
                            $this->table->add_row(array('Biaya Admin', ':', toRp($row->BIAYA_ADMIN)));
                            $this->table->add_row(array('Biaya Asuransi', ':', toRp($row->BIAYA_ASURANSI)));
                            $this->table->add_row(array('Nama Saudara', ':', $row->NAMA_SDR));
                            $this->table->add_row(array('Hubungan', ':', $row->HUB_SDR));
                            $this->table->add_row(array('No. Telp.', ':', $row->TELP_SDR));
                            $this->table->add_row(array('Alamat', ':', $row->ALAMAT_SDR));
                            $this->table->set_template($temp);
                            echo $this->table->generate();
                        ?>
                    </div> 
			    </div>
			</div>
		</div>
	</div>
</div>
    
<div class="col-sm-7">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <div class="row">
            	<div class="col-sm-9 col-lg-9">
                    <h4 class="color-primary">Simulasi Tagihan</h4>
                </div>
            </div>
        </div>
        <div class="panel-content">
        	<div class="row">
            	<div class="col-lg-12">
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
													  array('data' => date('d M Y', strtotime($row->TGL_PINJ.'+'. $i.' month')), 'class' => 'text-center')
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
        	</div>
        </div>
    </div>
</div>