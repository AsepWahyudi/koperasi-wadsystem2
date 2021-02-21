<?php  $row	=	$data_source->row(); ?>


<div class="row">

	<div class="col-sm-5">
        <div class="element-wrapper">
			<div class="user-profile compact">
             
                <div class="up-controls">
                	<div class="row">
                    	<div class="col-lg-12 text-right">
                            <a class="btn btn-warning btn-sm" href="<?php  echo base_url() ?>list-anggota-baru"><i class="fa fa-arrow-left"></i><span> Kembali</span></a>
                            <a class="btn btn-danger btn-sm" href="<?php  echo base_url() . "tolak-pengajuan-pinjaman/" . $row->IDPINJM_H . '/' . $row->IDANGGOTA; ?>" onclick="return confirm('Yakin menolak pengajuan tersebut?')"><i class="fa fa-close"></i><span> Tolak</span></a>
                            <a class="btn btn-success btn-sm" href="<?php  echo base_url() . "approve-pengajuan-pinjaman/" . $row->IDPINJM_H; ?>" onclick="return confirm('Yakin menyetujui permohonan tersebut?')"><i class="fa fa-check"></i><span> Setujui Pengajuan</span></a>
                      	</div>
                    </div>
                </div>
                
                <div class="up-contents">
                    <h5 class="element-header" style="margin-bottom:.5rem"> Data Pengajuan </h5>
                        <div class="table-responsive">
                            <?php  
                                 $temp = array ('table_open' => '<table class="table table-lightborder table-custom">');
                                $this->table->add_row(array(array('data' => 'Nama Lengkap', 'width' => '120px', 'style' => 'border:none'), array('data' => ':', 'width' => '10px', 'style' => 'border:none'), array('data' => $row->NAMA_ANGGOTA, 'style' => 'border:none')));
                                $this->table->add_row(array('Alamat', ':', $row->ALAMAT));
                                $this->table->add_row(array('Tgl. Pengajuan', ':', tgl_indo($row->TGL_PINJ)));
                                $this->table->add_row(array('Jenis Pembiayaan', ':', $row->JNS_PINJ));
                                $this->table->add_row(array('Nilai Pembiayaan', ':', toRp($row->JUMLAH)));
                                $this->table->add_row(array('Lama Angsuran', ':',  $row->LAMA_ANGSURAN . ' bulan'));
                                $this->table->add_row(array('Bagi Hasil', ':', $row->BUNGA . ' %'));
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
    
    <div class="col-sm-7">
        <div class="element-wrapper">
        	<div class="element-box"> 
        		<h5 class="element-header" style="margin-bottom:.5rem"> Simulasi Tagihan</h5>
                <div class="table-responsive">
					<?php 
						$ang_dasar	=	($row->JUMLAH / $row->LAMA_ANGSURAN);
						$bas_dasar	=	((($row->JUMLAH * $row->BUNGA) / 100) / $row->LAMA_ANGSURAN);
						$temp = array ('table_open' => '<table class="table table-striped table-bordered">');
						$heading		=	array(array('data' => 'Bln Ke', 'width' => '100px', 'class' => 'text-center'), 
												  array('data' => 'Angsuran Dasar', 'width' => '130px', 'class' => 'text-center'), 
												  array('data' => 'Basil Dasar', 'width' => '120px', 'class' => 'text-center'), 
												  array('data' => 'Jumlah Angsuran', 'width' => '150px', 'class' => 'text-center'), 
												  array('data' => 'Jatuh Tempo', 'width' => '200px', 'class' => 'text-center') );
						$this->table->set_heading($heading);
						
						$tot_ang_dasar	=	0;
						$tot_bas_dasar	=	0;
						$tot_jml_ang	=	0;
						for($i = 1; $i <= $row->LAMA_ANGSURAN; $i++) {
							$datarow	=	array(array('data' => $i, 'class' => 'text-center'), 
												  array('data' => toRp($ang_dasar), 'class' => 'text-right'), 
												  array('data' => toRp($bas_dasar), 'class' => 'text-right'),
												  array('data' => toRp($ang_dasar + $bas_dasar), 'class' => 'text-right'),
												  array('data' => date('d M Y', strtotime($row->TGL_PINJ.'+'. $i.' month')), 'class' => 'text-center')
											);
							$this->table->add_row($datarow);
							$tot_ang_dasar	+=	$ang_dasar;
							$tot_bas_dasar	+=	$bas_dasar;
							$tot_jml_ang	+=	($ang_dasar + $bas_dasar);
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

<style>
	table.table-custom td { padding: 0.5rem 1px !important; }
</style>