<!--------------------
    START - Mobile Menu
    --------------------> 
    <div class="menu-mobile menu-activated-on-click color-scheme-dark">
        <div class="mm-logo-buttons-w">
        <a class="mm-logo" href="<?php  echo base_url();?>"><img src="<?php  echo base_url();?>img/logos.png"><span>Koperasi Digital</span></a>
        <div class="mm-buttons">
            <div class="content-panel-open">
            <div class="os-icon os-icon-grid-circles"></div>
            </div>
            <div class="mobile-menu-trigger">
            <div class="os-icon os-icon-hamburger-menu-1"></div>
            </div>
        </div>
        </div>
        <div class="menu-and-user">
        <div class="logged-user-w">
            <div class="avatar-w">
            <img alt="" src="<?php  echo base_url();?>img/avatar1.jpg">
            </div>
            <div class="logged-user-info-w">
            <div class="logged-user-name">
                PUSAT
            </div>
            <div class="logged-user-role">
                Administrator
            </div>
            </div>
        </div>
        <!--------------------
        START - Mobile Menu List
        -------------------->
        <ul class="main-menu">
            <li class="">
            <a href="<?php  echo base_url();?>dashboard">
                <div class="icon-w">
                <div class="os-icon os-icon-layout"></div>
                </div>
                <span>Dashboard</span></a>
            </li>

            <li class="has-sub-menu">
            <a href="javascript:void(0)">
                <div class="icon-w">
                <div class="fa fa-harddrive"></div>
                </div>
                <span>Transaksi Kas</span></a>
                <ul class="sub-menu">
                    <li><a href="<?php  echo base_url();?>kas-pemasukan">Pemasukan</a></li>
                    <li><a href="<?php  echo base_url();?>kas-pengeluaran">Pengeluaran</a></li>
                    <li><a href="<?php  echo base_url();?>kas-transfer">Transfer</a></li>
                </ul>
            </li>
            <li class="has-sub-menu">
            <a href="javascript:void(0)">
                <div class="icon-w">
                <div class="fa fa-harddrive"></div>
                </div>
                <span>Simpanan</span></a>
                <ul class="sub-menu">
                     <li><a href="<?php  echo base_url();?>setoran-tunai">Setoran Tunai</a></li>
                    <li><a href="<?php  echo base_url();?>penarikan-tunai">Penarikan Tunai</a></li>
                </ul>
            </li>
            <li class="has-sub-menu">
            <a href="javascript:void(0)">
                <div class="icon-w">
                <div class="fa fa-harddrive"></div>
                </div>
                <span>Pinjaman</span></a>
                <ul class="sub-menu">
                     <li><a href="<?php  echo base_url();?>pinjaman-data">Data Pinjaman</a></li>
                    <li><a href="<?php  echo base_url();?>bayar-angsuran">Bayar Angsuran</a></li>
                    <li><a href="<?php  echo base_url();?>pinjaman-lunas">Pinjaman Lunas</a></li>
                    <li><a href="<?php  echo base_url();?>data-angsuran">Data Angsuran</a></li>
                </ul>
            </li>

             <li class="has-sub-menu">
            <a href="javascript:void(0)">
                <div class="icon-w">
                <div class="fa fa-harddrive"></div>
                </div>
                <span>Check List</span></a>
                <ul class="sub-menu">
                    <li><a href="<?php  echo base_url();?>cheklist-teller">Checklist Teller</a></li>
		   <li><a href="<?php  echo base_url();?>cheklist-kolektor">Checklist Kolektor</a></li>
                    <li><a href="<?php  echo base_url();?>list-pengajuan-pinjaman">Pengajuan Pinjaman</a></li>
                </ul>
            </li>
			<li class="has-sub-menu">
            <a href="javascript:void(0)">
                <div class="icon-w">
                <div class="os-icon os-icon-layers"></div>
                </div>
                <span>Master</span></a>
                <ul class="sub-menu">
                    <li><a href="<?php  echo base_url();?>anggota">Data Anggota</a></li>
                    <li><a href="<?php  echo base_url();?>user">Data User</a></li>
 
                    <li><a href="<?php  echo base_url();?>jenis-simpanan">Jenis Simpanan</a></li>
                    <li><a href="<?php  echo base_url();?>jenis-pinjaman">Jenis Pinjaman</a></li>
                    <li><a href="<?php  echo base_url();?>jenis-akun">Jenis Akun</a></li>
                    <li><a href="<?php  echo base_url();?>jenis-kas">Jenis Kas</a></li>
                    
                </ul>
            </li>
            <li class="has-sub-menu">
            <a href="javascript:void(0)">
                <div class="icon-w">
                <div class="os-icon os-icon-layers"></div>
                </div>
                <span>Akuntansi</span></a>
                <ul class="sub-menu">
                    <li><a href="<?php  echo base_url();?>akuntansi/perkiraan">Daftar Perkiraan(COA)</a></li>
                    <li><a href="<?php  echo base_url();?>akuntansi/saldo_awal">Saldo Awal Perkiraan</a></li>
                    <li><a href="<?php  echo base_url();?>akuntansi/jurnal_umum">Pencatatan Jurnal Umum</a></li>
                    
                </ul>
            </li>
           
            <li class="has-sub-menu">
            <a href="javascript:void(0)">
                <div class="icon-w">
                <div class="os-icon os-icon-layers"></div>
                </div>
                <span>Laporan</span></a>
                <ul class="sub-menu">
                    <li><a href="<?php  echo base_url();?>laporan-anggota">Data Anggota</a></li>
                  	<li><a href="<?php  echo base_url();?>laporan-kas-anggota">Kas Anggota</a></li>
                  	<li><a href="<?php  echo base_url();?>laporan-jatuh-tempo">Jatuh Tempo</a></li>
                  	<li><a href="<?php  echo base_url();?>laporan-kredit-macet">Kredit Macet</a></li>
                  	<li><a href="<?php  echo base_url();?>laporan-transaksi-kas">Transaksi Kas</a></li>
			<li><a href="<?php  echo base_url();?>laporan/bukubesar">Buku Besar</a></li>
                       <li><a href="<?php  echo base_url();?>laporan-neraca-saldo">Neraca Saldo</a></li>
                  	<li><a href="<?php  echo base_url();?>laporan-kas-simpanan">Kas Simpanan</a></li>
                  	<li><a href="<?php  echo base_url();?>laporan-kas-pinjaman">Kas Pinjaman</a></li>
                  	<li><a href="<?php  echo base_url();?>laporan-saldo-kas">Saldo Kas</a></li>
                  	<li><a href="<?php  echo base_url();?>laporan-shu">SHU</a></li>
                  	<li><a href="<?php  echo base_url();?>laporan-rekening-koran">Rekening Koran</a></li>
                  	<li><a href="#">Transfer Antar Anggota</a></li>
                  	<li><a href="#">Transfer Antar Lembaga KSP</a></li>
                  	<li><a href="#">Transfer Rek KSP Ke Bank Umum</a></li>
                </ul>
            </li>
            <li class="has-sub-menu">
            <a href="javascript:void(0)">
                <div class="icon-w">
                <div class="os-icon os-icon-layers"></div>
                </div>
                <span>Persyaratan</span></a>
                <ul class="sub-menu">
                    <li><a href="https://drive.google.com/file/d/1HBPu0uo7my3h6x6sR42mQ7WgUuwdMIF7/view?usp=sharing">Surat perjanjian pinjaman</a></li>
                  	<li><a href="https://drive.google.com/file/d/1dqAFf8izmLaSH254dAqWaFogRki8a6Tl/view?usp=sharing">Surat pengajuan pinjaman</a></li>
                  	
                  	
                </ul>
            </li>
           
			
        </ul>
        <!--------------------
        END - Mobile Menu List
        -------------------->
        <div class="mobile-menu-magic">
            <h4>Admin Dashboard</h4>
            <p>Version 1.0</p>
            <div class="btn-w">
            <a class="btn btn-white btn-rounded" href="#" target="_blank">Halo</a>
            </div>
        </div>
        </div>
    </div>
    <!--------------------
    END - Mobile Menu
    -------------------->