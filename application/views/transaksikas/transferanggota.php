<?php //echo $sql;?>
<div class="col-sm-12">
	<div class="panel  b-primary bt-sm ">
		<div class="panel-content">
			<div class="row">
				<div class="col-sm-9 col-lg-9">
					<h4 class="color-primary">Data Transaksi Transfer Antar Anggota</h4>
				</div>
				<div class="col-sm-3  col-lg-3 ">
					<a href="<?php echo base_url(); ?>kas-transfer-add-anggota" class="btn btn-primary btn-block">
						<i class="fa fa-plus"></i>
						Tambah Transfer Antar Anggota
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
					<?php if ($this->session->userdata('wad_level') == "admin") { ?>
						<form class="form-inline">
							Cabang &nbsp;

							<select class="form-control form-control-sm rounded bright plhcabang" name="plhcabang" id="plhcabang">
								<option value="">All</option>
								<?php

								$cabs = $this->dbasemodel->loadsql("SELECT NAMA,KODE FROM m_cabang ORDER BY NAMA ASC");
								foreach ($cabs->result() as $cab) {
									$sel = ($cab->KODE == $this->session->userdata('wad_cabang')) ? 'selected="selected"' : "";
								?>

									<option value="<?php echo $cab->KODE ?>" <?php echo $sel ?>><?php echo $cab->NAMA ?></option>
								<?php  } ?>
							</select>
						</form>
					<?php  } ?>

				</div>
				<div class="col-sm-6 justify-content-sm-end">
					<div class="form-inline justify-content-sm-end">

						<form class="form-inline flr">
							<div class="input-group">
								<span class="input-group-addon x-primary"><i class="fa fa-calendar"></i></span>
								<?php
								if (isset($_GET['tgl'])) {
									$tgl = $_GET['tgl'];
								} else {
									$tgl = date("d/m/Y");
								}
								?>
								<input type="text" class="form-control" id="default-datepicker" name="tgl" value="<?php echo $tgl ?>">
							</div>
							<button type="submit" class="btn btn-sm btn-secondary btn-rounded">Tampilkan</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-content">

			<table id="responsive-table" class="data-table table table-striped table-hover responsive nowrap" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>No</th>
						<th>Tanggal Transaksi</th>
						<th>Keterangan</th>
						<th>Jumlah</th>
						<th>Dari Anggota</th>
						<th>Untuk Anggota</th>
						<th>User</th>
						<!--<th>Action</th>-->
					</tr>
				</thead>
				<tbody>
					<?php if ($data_source->num_rows() > 0) {
						$n = 1;
						foreach ($data_source->result() as $key) {
							$no = $n++;
							$btn_edit	=	'<a href="' . base_url() . 'kas-transfer-edit?id=' . $key->IDTRAN_KAS . '" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>';
							$btn_del	=	'<a href="' . base_url() . 'transaksi_kas/transfer/delete?id=' . $key->IDTRAN_KAS . '" class="btn btn-danger btn-sm" style="margin-left:3px;"><i class="fa fa-trash"></i></a>';
							
							$DARI_ANGGOTA_ID = $key->DARI_ANGGOTA_ID;
							$UNTUK_ANGGOTA_ID = $key->UNTUK_ANGGOTA_ID;
							
							$this->db->select('*');
							$this->db->from('m_anggota');
							$this->db->where('IDANGGOTA',$DARI_ANGGOTA_ID); 
							$getanggota = $this->db->get()->row();
							
							$NAMA_DARI_ANGGOTA = $getanggota->NAMA;
							
							$this->db->select('*');
							$this->db->from('m_anggota');
							$this->db->where('IDANGGOTA',$UNTUK_ANGGOTA_ID); 
							$getanggotas = $this->db->get()->row();
							
							$NAMA_UNTUK_ANGGOTA = $getanggotas->NAMA;
							
					?>
							<tr>
								<td><?php echo $no ?></td>
								<td><?php echo tgl_en($key->TGL) ?></td>
								<td><?php echo $key->KETERANGAN ?></td>
								<td><?php echo toRp($key->JUMLAH) ?></td>
								<td><?php echo $NAMA_DARI_ANGGOTA ?></td>
								<td><?php echo $NAMA_UNTUK_ANGGOTA ?></td>
								<td><?php echo $key->USERNAME ?></td>
								<!--<td><?php echo $btn_edit . $btn_del ?></td>-->
							</tr>
					<?php  }
					} ?>
				</tbody>
			</table>
		</div>
	</div>
</div>