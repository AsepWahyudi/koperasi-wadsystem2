<div class="account2">
                    <div class="image img-cir img-120">
                        <img src="<?php  echo base_url();?>img/logos.png" alt="<?php  $CI =& get_instance(); echo $CI->session->ppobuser;?>" />
                    </div>
                    <h4 class="name"><?php  $CI =& get_instance(); echo $CI->session->ppobuser;?></h4>
                    <a href="<?php  echo base_url();?>" class="btn btn-primary" style="color: white;"><i class="fas fa-arrow-left"></i> KOPERASI DASHBOARD</a>
                </div>
                <nav class="navbar-sidebar2">
                    <ul class="list-unstyled navbar__list">
                        <li>
                            <a href="<?php  echo base_url();?>market_dashboard">
                                <i class="fas fa-home"></i>Dashboard</a>
                        </li>
                        <li>
                            <a href="<?php  echo base_url();?>kategori">
                                <i class="fas fa-chart-bar"></i>Kategori</a>
                        </li>
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-trophy"></i>Produk
                                <span class="arrow">
                                    <i class="fas fa-angle-down"></i>
                                </span>
                            </a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list">
                                <li>
                                    <a href="<?php  echo base_url();?>produk/pembelian">
                                        <i class="fas fa-table"></i>Pembelian</a>
                                </li>
                                <li>
                                    <a href="<?php  echo base_url();?>produk/pembayaran">
                                        <i class="far fa-check-square"></i>Pembayaran</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="<?php  echo base_url();?>iklan">
                                <i class="fas fa-th-large"></i>Pengaturan Iklan</a>
                        </li>
                        <li>
                            <a href="<?php  echo base_url();?>laporan">
                                <i class="fas fa-window-restore"></i>Laporan Transaksi</a>
                        </li>
                        <li>
                            <a href="<?php  echo base_url();?>datauser">
                                <i class="fas fa-user"></i>User</a>
                        </li>
                        <li>
                            <a href="<?php  echo base_url();?>market_kat">
                                <i class="fas fa-tasks"></i>Market Kategori</a>
                        </li>
                        <li>
                            <a href="<?php  echo base_url();?>market_prod">
                                <i class="fas fa-shopping-basket"></i>Market Produk</a>
                        </li>
                    </ul>
                </nav>