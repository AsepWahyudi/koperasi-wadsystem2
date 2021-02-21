<?php 
$route['kas-pemasukan']      = 'transaksi_kas/pemasukan';
$route['kas-pemasukan-add']  = 'transaksi_kas/pemasukan/formadd';
$route['kas-pemasukan-edit'] = 'transaksi_kas/pemasukan/formedit';

$route['kas-pengeluaran']      = 'transaksi_kas/pengeluaran';
$route['kas-pengeluaran-add']  = 'transaksi_kas/pengeluaran/formadd';
$route['kas-pengeluaran-edit'] = 'transaksi_kas/pengeluaran/formedit';

$route['kas-transfer']      = 'transaksi_kas/transfer';
$route['kas-transfer-add']  = 'transaksi_kas/transfer/formadd';
$route['kas-transfer-edit'] = 'transaksi_kas/transfer/formedit';

$route['setoran-tunai']                 = 'simpanan/simpanan';
$route['setoran-tunai-add']             = 'simpanan/simpanan/formadd';
$route['edit-setorantunai/(:num)']      = 'simpanan/simpanan/formedit/$1';
$route['edit-setorantunai/editsetoran'] = 'simpanan/simpanan/edit';
$route['delete-setorantunai/(:num)']    = 'simpanan/simpanan/deletes/$1';
$route['get-anggota']                   = 'simpanan/simpanan/get_anggota';

$route['penarikan-tunai']     = 'simpanan/penarikan';
$route['penarikan-tunai-add'] = 'simpanan/penarikan/formadd'; 

$route['saldo-anggota']         = 'laporan/kas_anggota'; 
$route['pinjaman-data']         = 'pinjaman/pinjaman';
$route['pinjaman-add']          = 'pinjaman/pinjaman/formadd';
$route['pinjaman-find']         = 'pinjaman/pinjaman/detail';
$route['pinjaman-cetak/(:num)'] = 'dokumen/pinjamancetak';
$route['cetak-struk/(:num)']    = 'dokumen/pinjamancetak/struk';
$route['pinjaman-riwayat']      = 'pinjaman/riwayat';

$route['bayar-angsuran'] = 'pinjaman/angsuran';
$route['pinjaman-lunas'] = 'pinjaman/pinjaman/lunas';
$route['data-angsuran']  = 'pinjaman/angsuran/data';

$route['pinjaman-lancar']    = 'pinjaman/pembayaran/lancar';
$route['pinjaman-meragukan'] = 'pinjaman/pembayaran/meragukan';
$route['pinjaman-buruk']     = 'pinjaman/pembayaran/buruk';
$route['pinjaman-macet']     = 'pinjaman/pembayaran/macet';

$route['list-anggota-baru']           = 'checklist/anggota';
$route['data-anggota-baru']           = 'checklist/anggota/dataanggota';
$route['list-view-anggota/(:num)']    = 'checklist/anggota/detail';
$route['view-anggota/(:num)']         = 'checklist/anggota/detail';
$route['approve-anggota-baru/(:num)'] =	'checklist/anggota/approve';
$route['tolak-anggota-baru/(:num)']   = 'checklist/anggota/tolak';

$route['nonaktif-anggota/(:num)'] =	'checklist/anggota/nonaktif';
$route['aktif-anggota/(:num)']    = 'checklist/anggota/aktif';

$route['list-pengajuan-pinjaman']               = 'checklist/pinjaman';
$route['data-pengajuan-pinjaman']               = 'checklist/pinjaman/detail';
$route['approve-pengajuan-pinjaman/(:num)']     = 'checklist/pinjaman/approve';
$route['tolak-pengajuan-pinjaman/(:num)/(:num)']= 'checklist/pinjaman/tolak';

$route['laporan-anggota']        = 'laporan/anggota';
$route['laporan-kas-anggota']    = 'laporan/kas_anggota';
$route['laporan-jatuh-tempo']    = 'laporan/jatuh_tempo';
$route['laporan-kredit-macet']   = 'laporan/kredit_macet';
$route['laporan-transaksi-kas']  = 'laporan/transaksi_kas';
$route['laporan-neraca-saldo']   = 'laporan/neraca';
$route['laporan-kas-simpanan']   = 'laporan/kas_simpanan';
$route['laporan-kas-pinjaman']   = 'laporan/kas_pinjaman';
$route['laporan-saldo-kas']      = 'laporan/saldo_kas';
$route['laporan-shu']            = 'laporan/shu';
$route['laporan-rekening-koran'] = 'laporan/rekening_koran';
$route['laporan-pinj-lancar']    = 'laporan/kredit_pinj';
$route['laporan-pinj-meragukan'] = 'laporan/kredit_pinj';
$route['laporan-pinj-buruk']     = 'laporan/kredit_pinj';

//Laporan Keuangan
$route['jurnal-transaksi'] = 'laporan/jurnal_transaksi';

$route['user']       = 'master_data/user';
$route['cabang']     = 'master_data/cabang';
$route['savecabang'] = 'master_data/cabang/savecabang';
$route['bpkb']       = 'master_data/bpkb';