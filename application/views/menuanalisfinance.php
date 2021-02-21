<ul class="nav nav-left-lines" id="main-nav">
	<!--HOME-->
	<li class="<?php if ($curact=="dashboard"){echo "open-item active-item";}?>">
		<a href="<?php  echo base_url();?>dashboard"><i class="fa fa-home" aria-hidden="true"></i><span>Dashboard</span></a>
	</li>
	<!--Check List-->
	<li 
		class="has-child-item <?php  if ($curact=="cheklist-teller" || $curact=="cheklist-kolektor" || $curact=="list-pengajuan-pinjaman" || $curact=="detail" || $curact=="list-anggota-baru" || $curact=="list-view-anggota" || $curact=="data-pengajuan-pinjaman" || $curact=="detail-teller"){echo "open-item active-item";}else{echo "close-item";} ?>">
		<a><i class="fa fa-list" aria-hidden="true"></i><span>Check List</span></a>
		<ul class="nav child-nav level-1">
			<li
				class="<?php  if ($curact=="cheklist-teller" || $curact=="detail-teller"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>cheklist-teller">Checklist Teller</a></li>
			<!--li
				class="<?php  if ($curact=="cheklist-kolektor"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>cheklist-kolektor">Checklist Kolektor</a></li-->
			<li
				class="<?php  if ($curact=="list-pengajuan-pinjaman" || $curact=="data-pengajuan-pinjaman"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>list-pengajuan-pinjaman">Pengajuan Pinjaman</a></li>
			<li
				class="<?php  if ($curact=="list-anggota-baru" || $curact=="list-view-anggota"){echo "active-item";} ?>">
				<a href="<?php  echo base_url();?>list-anggota-baru">Anggota Baru</a></li>
		</ul>
	</li>  
	  
</ul>