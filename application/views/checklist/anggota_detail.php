<?php  
error_reporting(1);
$row	=	$data_source->row(); ?>
<div class="col-md-6 col-lg-4">
    <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
    <!--PROFILE-->
    <div>
        <div class="profile-photo gallery-wrap">
            <a href="<?php  echo base_url() . 'uploads/identitas/' . $row->FILE_PIC;?>" title="Photo Anggota">
                <img alt="User photo" src="<?php  echo base_url() . 'uploads/identitas/' . $row->FILE_PIC;?>" class="img-responsive">
            </a>
        </div>
        <div class="user-header-info" style="margin-left: unset;">
            <h2 class="user-name"><?php  echo $row->NAMA?></h2>
            <h5 class="user-position"><i class="fa fa-home"></i> <?php  echo $row->ALAMAT?></h5>
            <h5 class="user-position"><i class="fa fa-user-md"></i> <?php  echo $row->JABATAN == '1' ? 'Pengurus' : 'Anggota'?></h5>
        </div>
    </div>
    <div class="panel bg-scale-0">
        <div class="panel-content">
            <div class="row">
                <div class="col-lg-12 text-right">
                    <a class="btn btn-warning btn-sm" onclick="goBack()"><i class="fa fa-arrow-left"></i><span> Kembali</span></a>
                    <?php  if($row->AKTIF == ''){ ?>
                    <a class="btn btn-danger btn-sm" href="<?php  echo base_url() . "tolak-anggota-baru/" . $row->IDANGGOTA ?>" onclick="return confirm('Yakin menolak anggota baru tersebut?')"><i class="fa fa-close"></i><span> Tolak</span></a>
                    <a class="btn btn-success btn-sm" href="<?php  echo base_url() . "approve-anggota-baru/" . $row->IDANGGOTA ?>" onclick="return confirm('Yakin menyetujui anggota baru tersebut?')"><i class="fa fa-check"></i><span> Setujui Permohonan</span></a>
                    <?php  }elseif($row->AKTIF == 'Y'){?>
                    <a class="btn btn-danger btn-sm" href="<?php  echo base_url() . "nonaktif-anggota/" . $row->IDANGGOTA ?>" onclick="return confirm('Yakin Non Aktifkan anggota tersebut?')"><i class="fa fa-close"></i><span> Non Aktifkan</span></a>
                    <?php  }elseif($row->AKTIF == 'N'){?>
                    <a class="btn btn-success btn-sm" href="<?php  echo base_url() . "aktif-anggota/" . $row->IDANGGOTA ?>" onclick="return confirm('Yakin Aktifkan anggota tersebut?')"><i class="fa fa-check"></i><span> Aktifkan</span></a>
                    <?php  }?>
                </div>
            </div>
        </div>
    </div>
    <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
    <!--CONTACT INFO-->
    <div class="panel bg-scale-0 b-primary bt-sm mt-xl">
        <div class="panel-content">
            <h4 class=""><b>Saudara Yang Dapat Dihubungi</b></h4>
            <div class="table-responsive">
                <?php  
                    $temp = array ('table_open' => '<table class="table table-lightborder table-custom" style="margin-bottom:unset;">');
                    $this->table->add_row(array(array('data' => 'Nama', 'width' => '80px', 'style' => 'border:none'), array('data' => ':', 'width' => '10px', 'style' => 'border:none'), array('data' => $row->NAMA_SAUDARA, 'style' => 'border:none')));
                    $this->table->add_row(array('Hubungan', ':', $row->HUB_SAUDARA));
                    $this->table->add_row(array('Alamat', ':', $row->ALMT_SAUDARA));
                    $this->table->add_row(array('No. Telp.', ':', $row->TELP_SAUDARA));
                    $this->table->set_template($temp);
                    echo $this->table->generate();
                ?>
            </div>
        </div>
    </div>
    <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
    <!--LIST-->
    
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
            <div class="widget-list list-sm list-left-element ">
                <h4 class=""><b>File Lampiran Identitas</b></h4>
                <div class=" gallery-wrap">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col-xs-6 col-md-6">
                                <?php  echo $row->FILE_KTP != '' ? 'Foto KTP <a href="'.base_url().'uploads/identitas/'. $row->FILE_KTP.'" title="Foto KTP"><img src="'.base_url().'uploads/identitas/'. $row->FILE_KTP.'" class="img-responsive"></a>' : '';?>
                            </div>
                            <div class="col-xs-6 col-md-6">
                                <?php  echo $row->FILE_NPWP != '' ? 'Foto NPWP <a href="'.base_url().'uploads/identitas/'. $row->FILE_NPWP.'" title="Foto NPWP"><img src="'.base_url().'uploads/identitas/'. $row->FILE_NPWP.'" class="img-responsive"></a>' : ''; ?>
                            </div>
                            <div class="col-xs-6 col-md-6">
                                <?php  echo $row->FILE_KK != '' ? 'Foto KK <a href="'.base_url().'uploads/identitas/'. $row->FILE_KK.'" title="Foto KK"><img src="'.base_url().'uploads/identitas/'. $row->FILE_KK.'" class="img-responsive"></a>' : ''; ?>
                            </div>
                            <div class="col-xs-6 col-md-6">
                                <?php  echo $row->FILE_BK_NKH != '' ? 'Foto Buku Nikah <a href="'.base_url().'uploads/identitas/'. $row->FILE_BK_NKH.'" title="Foto Buku Nikah"><img src="'.base_url().'uploads/identitas/'. $row->FILE_BK_NKH.'" class="img-responsive"></a>' : ''; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-6 col-lg-8">
    <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
    <!--TIMELINE-->
    <div class="timeline animated fadeInUp">
        <div class="timeline-box ">
            <div class="timeline-icon bg-primary">
                <i class="fa fa-user"></i>
            </div>
            <div class="timeline-content b-primary bt-sm mt-xl">
                <h4 class="tl-title">Data Pribadi</h4> 
                <div class="table-responsive">
                    <?php  
                        $temp = array ('table_open' => '<table class="table table-lightborder table-custom" style="margin-bottom:unset;">');
                        $this->table->add_row(array(array('data' => 'Nama Lengkap', 'width' => '130px', 'style' => 'border:none'), array('data' => ':', 'width' => '10px', 'style' => 'border:none'), array('data' => $row->NAMA, 'style' => 'border:none')));
                        $this->table->add_row(array('Agama', ':', $row->AGAMA));
                        $this->table->add_row(array('Jenis Kelamin', ':', $row->JK));
                        $this->table->add_row(array('Tempat Lahir', ':', $row->TMP_LAHIR));
                        $this->table->add_row(array('Tanggal Lahir', ':',  tgl_indo($row->TGL_LAHIR)));
                        $this->table->add_row(array('Status', ':', $row->STATUS));
                        $this->table->add_row(array('Pekerjaan', ':', $row->PEKERJAAN));
                        $this->table->add_row(array('Alamat', ':', $row->ALAMAT));
                        $this->table->add_row(array('Alamat Domisili', ':', $row->ALAMAT_DOMISILI));
						
						$this->db->select('*');
						$this->db->from('t_kota');
						$this->db->where('IDTKOTA',$row->IDKOTA); 
						$output = $this->db->get()->row(); 
						
                        $this->table->add_row(array('Kota', ':', $output->KOTA));
                        $this->table->add_row(array('No. Telp.', ':', $row->TELP));
                        $this->table->add_row(array('eMail', ':', $row->EMAIL));
                        $this->table->add_row(array('Ibu Kandung', ':', $row->IBU_KANDUNG));
                        $this->table->add_row(array('Tanggal Registrasi', ':', tgl_indo($row->TGL_DAFTAR)));
                        $this->table->set_template($temp);
                        echo $this->table->generate();
                    ?>
                </div> 
            </div>
        </div>
        <div class="timeline-box">
            <div class="timeline-icon bg-primary">
                <i class="fa fa-file"></i>
            </div>
            <div class="timeline-content b-primary bt-sm mt-xl">
                <h4 class="tl-title">Identitas</h4> 
                <div class="table-responsive">
                    <?php  
                        $temp = array ('table_open' => '<table class="table table-lightborder table-custom" style="margin-bottom:unset;">');
                        $this->table->add_row(array(array('data' => 'Identitas', 'width' => '130px', 'style' => 'border:none'), array('data' => ':', 'width' => '10px', 'style' => 'border:none'), array('data' => $row->IDENTITAS, 'style' => 'border:none')));
                        $this->table->add_row(array('No. Identitas', ':', $row->NO_IDENTITAS));
                        $this->table->add_row(array('Nama Bank', ':', $row->NAMA_BANK));
                        $this->table->add_row(array('No Rek', ':', $row->NOREK));
                        $this->table->add_row(array('No Kartu ATM', ':',  $row->NOKARTU));
                        $this->table->add_row(array('Tanggal Registrasi', ':', tgl_indo($row->TGL_DAFTAR)));
                        $this->table->add_row(array('Jabatan', ':', ($row->JABATAN == '1' ? 'Pengurus' : 'Anggota')));
                        $this->table->set_template($temp);
                        echo $this->table->generate();
                    ?>
                </div>
            </div>
        </div>
        <div class="timeline-box">
            <div class="timeline-icon bg-primary">
                <i class="fa fa-check"></i>
            </div>
            <div class="timeline-content b-primary bt-sm mt-xl">
                <h4 class="tl-title">Status Anggota</h4> 
                <div class="table-responsive">
                    <?php  
                        $temp = array ('table_open' => '<table class="table table-lightborder table-custom" style="margin-bottom:unset;">');
                        $this->table->add_row(array(($row->AKTIF == '' ? '<span class="badge badge-md x-warning">Menunggu Persetujuan</span>' : ($row->AKTIF == 'Y' ? '<span class="badge badge-md x-success">Aktif</span>' : '<span class="badge badge-md x-danger">Tidak Aktif</span>')),'',''));
                        $this->table->set_template($temp);
                        echo $this->table->generate();
                    ?>
                </div>
            </div>
        </div>
        
        <div class="timeline-box">
            <div class="timeline-icon bg-primary">
                <i class="fa fa-street-view"></i>
            </div>
            <div class="timeline-content b-primary bt-sm mt-xl">
                <h4 class="tl-title">Lokasi</h4> 
                <div class="table-responsive">
                    <?php  
                        $temp = array ('table_open' => '<table class="table table-lightborder table-custom" style="margin-bottom:unset;">');
                        $this->table->add_row(array('<a class="btn btn-warning btn-sm" href="https://www.google.co.id/maps/dir//'.$row->lat.','.$row->lng.'/@'.$row->lat.','.$row->lng.',19z" target="_blank"><i class="fa fa-location-arrow"></i><span> Buka Map</span></a>','',''));
                        $this->table->set_template($temp);
                        echo $this->table->generate();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function goBack() {
  window.history.back();
}
</script>