<?php //echo $sql;?>
<div class="col-sm-12">
	<div class="panel  b-primary bt-sm ">
		<div class="panel-content">
			<div class="row">
				<div class="col-sm-10 col-lg-10">
					<h4 class="color-primary">Data Transaksi Pemasukan Kas</h4>
				</div>
				<div class="col-sm-2 col-lg-2">
					<a href="<?php echo base_url('kas-pemasukan-add'); ?>" class="btn btn-primary btn-block">
						<i class="fa fa-plus"></i>
						Tambah Pemasukan
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
				<form class="form-inline">
				<div class="col-sm-2">
					<?php if ($this->session->userdata('wad_level') == "admin") { ?> 
						<select class="form-control form-control-sm rounded bright" name="plhcabang">
							<option value="">All</option>
							<?php 
							foreach ($cabs->result() as $cab) {
								$sel = ($cab->KODE == $this->session->userdata('wad_cabang')) ? 'selected="selected"' : "";
								
								echo "<option value='".$cab->KODE."' $sel>".$cab->NAMA."</option>";
							}
							?>  
						</select> 
					<?php } ?> 
				</div>
				<div class="col-sm-6 justify-content-sm-end">
					<div class="form-inline justify-content-sm-end">  
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
					</div>
				</div>
				</form>
			</div> 
		</div>
		<div class="panel-content"> 
			<table id="responsive-table" class="data-table table table-striped table-hover responsive nowrap" cellspacing="0" width="100%">
				<thead style ="background: #212121  !important; color: #fff!important;">
					<tr>
						<th>No</th>
						<th>Tanggal Transaksi</th>
						<th>Keterangan</th>
						<th>Untuk Kas</th>
						<th>Dari Akun</th>
						<th>Jumlah</th>
						<th>User</th>
						<th>Cabang</th>
						<!--<th>Action</th>-->
					</tr>
				</thead>
				<tbody>
					<?php if ($data_source->num_rows() > 0) {
						$n = 1;
						foreach ($data_source->result() as $key) {
							
							$no = $n++;
							$btn_edit = '<a href="' . base_url() . 'kas-pemasukan-edit?id=' . $key->IDTRAN_KAS . '" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>';
							$btn_del = '<a href="' . base_url() . 'transaksi_kas/pemasukan/delete?id=' . $key->IDTRAN_KAS . '" class="btn btn-danger btn-sm" style="margin-left:3px;"><i class="fa fa-trash"></i></a>';
					?>
							<tr>
								<td><?php echo $no ?></td>
								<td><?php echo tgl_en($key->TGL) ?></td>
								<td><?php echo $key->KETERANGAN ?></td>
								<td><?php echo $key->NAMA_KAS ?></td>
								<td><?php echo $key->JENIS_TRANSAKSI ?></td>
								<td><?php echo toRp($key->JUMLAH) ?></td>
								<td><?php echo $key->USERNAME ?></td>
								<td><?php echo $key->NAMACABANG ?></td>
								<!--<td><?php echo $btn_edit . $btn_del ?></td>-->
							</tr>
					<?php  }
					} ?>
				</tbody>
			</table>
		</div>
	</div>
</div>