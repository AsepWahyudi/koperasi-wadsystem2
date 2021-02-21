<ul class="nav nav-left-lines" id="main-nav">
	<!--HOME-->
	<li class="<?php if ($curact=="dashboard"){echo "open-item active-item";}?>">
		<a href="<?php  echo base_url();?>dashboard"><i class="fa fa-home" aria-hidden="true"></i><span>Dashboard</span></a>
	</li>
	<!--Transaksi Kas-->
	<li 
		class="has-child-item <?php  if ($curact=="kas-pemasukan" || $curact=="kas-pemasukan-add" || $curact=="kas-pengeluaran" || $curact=="kas-pengeluaran-add" || $curact=="kas-transfer" || $curact=="kas-transfer-add"){echo "open-item active-item";}else{echo "close-item";} ?>">
		<a><i class="fa fa-cubes" aria-hidden="true"></i><span>Transaksi Kas</span></a>
		<ul class="nav child-nav level-1">
			<li 
				class="<?php  if ($curact=="kas-pemasukan" || $curact=="kas-pemasukan-add"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>kas-pemasukan">Pemasukan</a>
			</li>
			<li
				class="<?php  if ($curact=="kas-pengeluaran" || $curact=="kas-pengeluaran-add"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>kas-pengeluaran">Pengeluaran</a>
			</li>
			<li
				class="<?php  if ($curact=="kas-transfer" || $curact=="kas-transfer-add"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>kas-transfer">Transfer Antar Koprasi</a>
			</li>
			<li
				class="<?php  if ($curact=="kas-transfer-anggota" || $curact=="kas-transfer-add"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>kas-transfer">Transfer Antar Anggota</a>
			</li>
			<li
				class="<?php  if ($curact=="kas-transfer" || $curact=="kas-transfer-add"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>kas-transfer-bank">Transfer Bank</a>
			</li>
		</ul>
	</li>
	<!--Simpanan-->
	<li 
		class="has-child-item <?php  if ($curact=="setoran-tunai" || $curact=="setoran-tunai-add" || $curact=="penarikan-tunai" || $curact=="penarikan-tunai-add" || $curact=="laporan-rekening-koran" || $curact=="saldo-anggota"){echo "open-item active-item";}else{echo "close-item";} ?>">
		<a><i class="fa fa-pie-chart" aria-hidden="true"></i><span>Simpanan</span> </a>
		<ul class="nav child-nav level-1">
			<li
				class="<?php  if ($curact=="setoran-tunai" || $curact=="setoran-tunai-add"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>setoran-tunai">Setoran Tunai</a></li>
			<li
				class="<?php  if ($curact=="penarikan-tunai" || $curact=="penarikan-tunai-add"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>penarikan-tunai">Penarikan Tunai</a></li>
			<li
				class="<?php  if ($curact=="saldo-anggota"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>saldo-anggota">Saldo Anggota</a></li>
			<li
				class="<?php  if ($curact=="laporan-rekening-koran"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>laporan-rekening-koran">Rekening Koran</a></li>
		</ul>
	</li>
	<!--Pinjaman-->
	<li 
		class="has-child-item <?php  if ($curact=="pinjaman-data" || $curact=="pinjaman-add" || $curact=="bayar-angsuran" || $curact=="pinjaman-find" || $curact=="pinjaman-lunas" || $curact=="data-angsuran" || $curact=="pinjaman-riwayat" || $curact=="pinjaman-lancar" || $curact=="pinjaman-meragukan" || $curact=="pinjaman-buruk" || $curact=="pinjaman-macet"){echo "open-item active-item";}else{echo "close-item";} ?>">
		<a><i class="fa fa-money" aria-hidden="true"></i><span>Pinjaman</span></a>
		<ul class="nav child-nav level-1">
			<li
				class="<?php  if ($curact=="pinjaman-data" || $curact=="pinjaman-add"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>pinjaman-data">Data Pinjaman</a></li>
			<li
				class="<?php  if ($curact=="bayar-angsuran" || $curact=="pinjaman-find"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>bayar-angsuran">Bayar Angsuran</a></li>
			<li
				class="<?php  if ($curact=="pinjaman-lunas" || $curact=="pinjaman-find"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>pinjaman-lunas">Pinjaman Lunas</a></li>
			<li
				class="<?php  if ($curact=="data-angsuran"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>data-angsuran">Data Angsuran</a></li>
			<li
				class="<?php  if ($curact=="pinjaman-riwayat"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>pinjaman-riwayat">Riwayat Pinjaman</a></li>
			<li class="has-child-item <?php  if ($curact=="pinjaman-lancar" || $curact=="pinjaman-meragukan" || $curact=="pinjaman-buruk" || $curact=="pinjaman-macet"){echo "open-item";}else{echo "close-item";} ?>">
				<a>Data Pembayaran</a>
				<ul class="nav child-nav level-2 ">
					<li
						class="<?php  if ($curact=="pinjaman-lancar"){echo "active-item";} ?>">
						<a href="<?php  echo base_url();?>pinjaman-lancar">Pemb. Lancar</a></li>
					<li
						class="<?php  if ($curact=="pinjaman-meragukan"){echo "active-item";} ?>">
						<a href="<?php  echo base_url();?>pinjaman-meragukan">Pemb. Meragukan</a></li>
					<li
						class="<?php  if ($curact=="pinjaman-buruk"){echo "active-item";} ?>">
						<a href="<?php  echo base_url();?>pinjaman-buruk">Pemb. Buruk</a></li>
					<li
						class="<?php  if ($curact=="pinjaman-macet"){echo "active-item";} ?>">
						<a href="<?php  echo base_url();?>pinjaman-macet">Pemb. Macet</a></li>
				</ul>
			</li>
		</ul>
	</li>
	<!--Check List-->
	 
	<!--Anggota-->
	<li 
		class="has-child-item <?php  if ($curact=="anggota" || $curact=="add-anggota" || $curact=="view-anggota" || $curact=="anggota-nonaktif" || $curact=="edit-anggota" || $curact=="view-anggota-nonaktif" || $curact=="non-anggota" || $curact=="view-non-anggota"){echo "open-item active-item";}else{echo "close-item";} ?>">
		<a><i class="fa fa-users" aria-hidden="true"></i><span>Anggota</span></a>
		<ul class="nav child-nav level-1">
			<li
				class="<?php  if ($curact=="anggota" || $curact=="add-anggota" || $curact=="edit-anggota" || $curact=="view-anggota"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>anggota">Anggota Aktif</a></li>
			<li
				class="<?php  if ($curact=="anggota-nonaktif" || $curact=="view-anggota-nonaktif"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>anggota-nonaktif">Anggota Non Aktif</a></li>
			<li
				class="<?php  if ($curact=="non-anggota" || $curact=="view-non-anggota"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>non-anggota">Non Anggota</a></li>
		</ul>
	</li>
  
	<!--Laporan-->
	<li 
		class="has-child-item <?php  if ($curact=="laporan-anggota" || $curact=="laporan-kas-anggota" || $curact=="laporan-jatuh-tempo" || $curact=="laporan-kredit-macet" || $curact=="laporan-transaksi-kas" || $curact=="laporan-kas-simpanan" || $curact=="laporan-kas-pinjaman" || $curact=="laporan-saldo-kas"|| $curact=="laporan-shu" || $curact=="laporan-shu" ){echo "open-item active-item";}else{echo "close-item";} ?>">
		<a><i class="fa fa-copy" aria-hidden="true"></i><span>Laporan</span></a>
		<ul class="nav child-nav level-1">
			<li
				class="<?php  if ($curact=="laporan-anggota"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>laporan-anggota">Data Anggota</a></li>
			<li
				class="<?php  if ($curact=="laporan-kas-anggota"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>laporan-kas-anggota">Kas Anggota</a></li>
			<li
				class="<?php  if ($curact=="laporan-jatuh-tempo"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>laporan-jatuh-tempo">Jatuh Tempo</a></li>
			<li
				class="<?php  if ($curact=="laporan-kredit-macet"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>laporan-kredit-macet">Kredit Macet</a></li>
			<li
				class="<?php  if ($curact=="laporan-kas-simpanan"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>laporan-kas-simpanan">Kas Simpanan</a></li>
			<li
				class="<?php  if ($curact=="laporan-kas-pinjaman"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>laporan-kas-pinjaman">Kas Pinjaman</a></li>
			<li
				class="<?php  if ($curact=="laporan-transaksi-kas"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>laporan-transaksi-kas">Transaksi Kas</a></li>
			
			<li
				class="<?php  if ($curact=="laporan-saldo-kas"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>laporan-saldo-kas">Saldo Kas</a></li>
			<li
				class="<?php  if ($curact=="laporan-shu"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>laporan-shu">SHU</a></li>
			
		</ul>
	</li>
	<!--Laporan Keuangan-->
	<li 
		class="has-child-item <?php  if ($curact=="jurnal-transaksi" || $curact=="laporan" || $curact=="laporan-neraca-saldo" || $curact=="laba-rugi"){echo "open-item active-item";}else{echo "close-item";} ?>">
		<a><i class="fa fa-flask" aria-hidden="true"></i><span>Laporan Keuangan</span></a>
		<ul class="nav child-nav level-1">
			<li
				class="<?php  if ($curact=="jurnal-transaksi"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>jurnal-transaksi">Jurnal - Transaksi</a></li>                                        
			<li
				class="<?php  if ($curact=="laporan"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>laporan/bukubesar">Buku Besar</a></li>
			<li
				class="<?php  if ($curact=="laporan-neraca-saldo"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>laporan-neraca-saldo">Neraca Saldo</a></li>
			<li
				class="<?php  if ($curact=="laba-rugi"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>laba-rugi">Laba Rugi</a></li>
		</ul>
	</li> 
</ul> 