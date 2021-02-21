<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
 

$route['default_controller'] = 'welcome';
$route['auth_login']         = 'welcome/login';
$route['auth_logout']        = 'welcome/logout';
$route['ganticabang']        = 'welcome/ganticabang';

$route['dashboard']           = 'dashboard';
$route['anggota']             = 'anggota/anggota';
$route['anggota/uploadktp']   = 'anggota/anggota/uploadktp';
$route['anggota/uploadnpwp']  = 'anggota/anggota/uploadnpwp';
$route['anggota/uploadkk']    = 'anggota/anggota/uploadkk';
$route['anggota/uploadbn']    = 'anggota/anggota/uploadbn';
$route['anggota/uploadwajah'] = 'anggota/anggota/uploadwajah';
$route['anggota/uploadlok']   = 'anggota/anggota/uploadlok';
$route['cekidentitas']        = 'anggota/anggota/cekKtp';
$route['cekhp']               = 'anggota/anggota/cekHp';
$route['importanggota']                = 'anggota/importanggota';
$route['anggota-nonaktif']             = 'anggota/anggota/nonaktif';
$route['data-anggota-nonaktif']        = 'anggota/anggota/dataanggotanonaktif';
$route['view-anggota-nonaktif/(:num)'] = 'checklist/anggota/detail';

$route['non-anggota']             = 'anggota/anggota/nonanggota';
$route['data-non-anggota']        = 'anggota/anggota/datanonanggota';
$route['view-non-anggota/(:num)'] = 'checklist/anggota/detail';

$route['data-anggota']        = 'anggota/anggota/dataanggota';
$route['add-anggota']         = 'anggota/anggota/addanggota';
$route['save-anggota']        = 'anggota/anggota/saveanggota';
$route['anggotaedit']         = 'anggota/anggota/anggotaedit';
$route['edit-anggota/(:any)'] = 'anggota/anggota/editanggota/$1';

$route['sertifikat']             = 'sertifikat/sertifikat';
$route['editjaminanpinjaman/(:num)'] = 'sertifikat/sertifikat/editjaminanpinjaman/$1';
$route['downloadanggunan/(:num)'] = 'sertifikat/sertifikat/downloadanggunan/$1';
$route['sertifikat/uploadktp']   = 'sertifikat/sertifikat/uploadktp';
$route['sertifikat/uploadnpwp']  = 'sertifikat/sertifikat/uploadnpwp';
$route['sertifikat/uploadkk']    = 'sertifikat/sertifikat/uploadkk';
$route['sertifikat/uploadbn']    = 'sertifikat/sertifikat/uploadbn';
$route['sertifikat/uploadwajah'] = 'sertifikat/sertifikat/uploadwajah';
$route['cekidentitas']           = 'sertifikat/sertifikat/cekKtp';
$route['cekhp']                  = 'sertifikat/sertifikat/cekHp';

$route['data-sertifikat']        = 'sertifikat/sertifikat/datasertifikat';
$route['add-sertifikat']         = 'sertifikat/sertifikat/addsertifikat';
$route['save-sertifikat']        = 'sertifikat/sertifikat/savesertifikat';
$route['sertifikatedit']         = 'sertifikat/sertifikat/sertifikatedit';
$route['edit-sertifikat/(:any)'] = 'sertifikat/sertifikat/editsertifikat/$1';

$route['jenis-simpanan'] = 'master_data/jenis_simpanan';
$route['jenis-pinjaman'] = 'master_data/jenis_pinjaman';
$route['jenis-akun']     = 'master_data/jenis_akun';
$route['jenis-kas']      = 'master_data/jenis_kas';
$route['biaya-transfer'] = 'master_data/biaya_transfer';
$route['savebiayatransfer'] = 'master_data/biaya_transfer/savebiayatransfer';

// $route['cheklist-teller']      = 'checklist/checklist';
// $route['uploadbukti/teller']   = 'checklist/checklist/uploadteller';
// $route['confirmteller']        = 'checklist/checklist/confirmteller';
// $route['detail-teller/(:num)'] = 'checklist/checklist/detailceklis';
// $route['confirmpinjam']        = 'checklist/pinjaman/konfirmasi';


$route['cheklist-teller']           = 'checklist/checklist';
$route['uploadbukti/teller']        = 'checklist/checklist/uploadteller';
$route['uploadbukti/tellersetoran'] = 'checklist/checklist/uploadtellersetoran';
$route['uploadbukti/tellerangsuran'] = 'checklist/checklist/uploadtellerangsuran';
$route['confirmteller']             = 'checklist/checklist/confirmteller';
$route['confirmtellersetoran']      = 'checklist/checklist/confirmtellersetoran';
$route['confirmtellerangsuran']      = 'checklist/checklist/confirmtellerangsuran';
$route['detail-teller/(:num)']      = 'checklist/checklist/detailceklis';
$route['detail-tellerangsuran/(:num)'] = 'checklist/checklist/detailceklisangsuran';
$route['confirmpinjam']             = 'checklist/pinjaman/konfirmasi';


$route['cheklist-kolektor']       = 'checklist/ceklist_kolektor';
$route['detail/kolektor/(:num)']  = 'checklist/ceklist_kolektor/detailceklis';
$route['uploadbukti/kolektor']    = 'checklist/ceklist_kolektor/uploadkolektor';
$route['confirmkolektor']         = 'checklist/ceklist_kolektor/confirmkoletor';
$route['cheklist-kolektor/excel'] = 'checklist/ceklist_kolektor/excel';

$route['uploadbukti/pinjaman']   = 'checklist/pinjaman/uploadbukti';
$route['kontrak/(:num)']         = 'dokumen/kontrakperjanjian';
$route['perjanjianbasil/(:num)'] = 'dokumen/perjanjianbasil';
$route['permohonan/(:num)']      = 'dokumen/permohonan';


$route['laporan/bukubesar'] = 'laporan/bukubesar';
$route['laba-rugi']         = 'laporan/laba_rugi';

$route['smsgateway']       = 'sms/smsgateway';
$route['kirimsms']         = 'sms/smsgateway/kirimsms';
$route['smsgateway/reset'] = 'sms/smsgateway/reset';
$route['kirimsmsh3']       = 'sms/smsgateway/kirimsmsh3';
$route['kirimsmsrisert']   = 'sms/smsgateway/kirimsmsrisert';

$route['paymentgateway'] = 'payment/paymentgateway';
$route['gopay']          = 'payment/gopay';
$route['ovo']            = 'payment/ovo';
$route['ovo-payment']    = 'payment/ovoPayment';

////////////////////////////////////////////////////////////
$route['apis/search']            = 'api/carianggota';
$route['apis/login']             = 'api/carianggota/login';
$route['apis/caripinjaman']      = 'api/carianggota/caripinjaman';
$route['apis/detailanggota']     = 'api/carianggota/detailanggota';
$route['apis/kolektor/login']    = 'api/kolektor';
$route['apis/kolektor/simpanan'] = 'api/kolektor/simpanan';
$route['apis/detailpinjaman']    = 'api/pinjaman';
$route['apis/pinjbukti']         = 'api/pinjaman/buktipinjaman';
$route['apis/kolektor/angsuran'] = 'api/pinjaman/angsuran';
$route['apis/kolektor/pending']  = 'api/kolektor/getPending';

//struk
$route['struk-simpanan/(:num)']  = 'simpanan/simpanan/struk';
$route['struk-setoran/(:num)']  = 'simpanan/simpanan/struksetoran';
$route['struk-penarikan/(:num)'] = 'simpanan/penarikan/struk';

$route['jenis-jaminan'] = 'master_data/jenis_jaminan';

$route['kas-pemasukan']      = 'transaksi_kas/pemasukan';
$route['kas-pemasukan-add']  = 'transaksi_kas/pemasukan/formadd';
$route['kas-pemasukan-edit'] = 'transaksi_kas/pemasukan/formedit';
 
$route['kas-pengeluaran']      = 'transaksi_kas/pengeluaran';
$route['kas-pengeluaran-add']  = 'transaksi_kas/pengeluaran/formadd';
$route['kas-pengeluaran-edit'] = 'transaksi_kas/pengeluaran/formedit';

$route['kas-transfer']      = 'transaksi_kas/transfer';
$route['kas-transfer-anggota']      = 'transaksi_kas/transfer/transferanggota';
$route['kas-transfer-bank']      = 'transaksi_kas/transfer/transferbank';
$route['kas-transfer-add']  = 'transaksi_kas/transfer/formadd';
$route['kas-transfer-add-anggota']  = 'transaksi_kas/transfer/formaddanggota';
$route['kas-transfer-add-bank']  = 'transaksi_kas/transfer/formaddbank';
$route['kas-transfer-edit'] = 'transaksi_kas/transfer/formedit';
$route['kas-transfer-editanggota'] = 'transaksi_kas/transfer/formeditanggota';
$route['kas-transfer-editbank'] = 'transaksi_kas/transfer/formeditbank';

$route['setoran-tunai']                 = 'simpanan/simpanan';
$route['setoran-tunai-add']             = 'simpanan/simpanan/formadd';
$route['edit-setorantunai/(:num)']      = 'simpanan/simpanan/formedit/$1';
$route['edit-setorantunai/editsetoran'] = 'simpanan/simpanan/edit';
$route['delete-setorantunai/(:num)']    = 'simpanan/simpanan/deletes/$1';
$route['get-anggota']                   = 'simpanan/simpanan/get_anggota';

$route['cetak-buku-tabungan'] = 'simpanan/simpanan/bukutabungan';
$route['cetak-rekening-koran'] = 'simpanan/simpanan/rekeningkoran';

$route['penarikan-tunai']     = 'simpanan/penarikan';
$route['penarikan-tunai-add'] = 'simpanan/penarikan/formadd'; 

$route['saldo-anggota']         = 'laporan/kas_anggota'; 
$route['pinjaman/importpinjaman'] = 'pinjaman/pinjaman/importpinjaman';
$route['pinjaman-data']         = 'pinjaman/pinjaman';
$route['pinjaman-add']          = 'pinjaman/pinjaman/formadd';
$route['pinjaman-find']         = 'pinjaman/pinjaman/detail';
$route['pinjaman-cetak/(:num)'] = 'dokumen/pinjamancetak';
$route['cetak-struk/(:num)']    = 'dokumen/pinjamancetak/struk';
$route['pinjaman-riwayat']      = 'pinjaman/riwayat';

$route['bayar-angsuran'] = 'pinjaman/angsuran';
$route['angsuran/importangsuran'] = 'pinjaman/angsuran/importangsuran';
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
$route['lapjurnaltransaksi'] = 'laporan/jurnal_transaksi/cetak'; 
$route['cetaklaporanjurnaltransaksi'] = 'laporan/jurnal_transaksi/cetaklaporanjurnaltransaksi';

$route['lapbukubesar'] = 'laporan/bukubesar/cetak';
$route['cetaklapbukubesar'] = 'laporan/bukubesar/cetaklapbukubesar';

$route['lapneraca'] = 'laporan/neraca/cetak';
$route['cetaklapneraca'] = 'laporan/neraca/cetaklapneraca';

$route['laplabarugi'] = 'laporan/laba_rugi/cetak';
$route['cetaklaplabarugi'] = 'laporan/laba_rugi/cetaklaplabarugi';

$route['user']       = 'master_data/user';
$route['cabang']     = 'master_data/cabang';
$route['savecabang'] = 'master_data/cabang/savecabang';
$route['bpkb']       = 'master_data/bpkb';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// include("routes2.php");
include("routes_mobileapi.php");