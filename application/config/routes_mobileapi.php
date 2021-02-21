<?php 
$route['xcallback'] 					= 'mobileapi/mycallback';
$route['api/history'] 					= 'mobileapi/historytrx';

$route['auth'] 							= 'mobileapi/login';
$route['intro'] 						= 'mobileapi/mobileapi/Intromobile';


$route['api/produk'] 					= 'mobileapi/mobileapi/produk';
$route['api/kategori']					= 'mobileapi/mobileapi/produk/kategori';
$route['api/trx/pembelian']				= 'mobileapi/mobileapi/pembelian';
$route['api/trx/pembelianpln']			= 'mobileapi/mobileapi/pembelian/pembelianpln';

$route['api/cektagihan']				= 'mobileapi/mobileapi/pembayaran/cektagihan';
$route['api/pelunasan']					= 'mobileapi/mobileapi/pembayaran/pelunasan';
$route['api/test']						= 'mobileapi/mobileapi/pembayaran/tesdata';

$route['api/marketproduk'] 				= 'mobileapi/mobileapi/Marketplace';


/*$route['prd/pulsa'] 					= 'h2h_tri/tripay';
$route['prd/pembayaran']				= 'mobileapi/pembayaran';
$route['tokenpln']						= 'mobileapi/pln/tokenpln';

$route['cek/pembayaran'] 				= 'h2h_tri/tri_pembayaran';


$route['pulsa/(:num)'] 					= 'h2h_tri/tripay/detailpulsa';
$route['trx/pulsa'] 					= 'h2h_tri/tripay/trx';
$route['proses/pulsa'] 					= 'h2h_tri/tripay/prosespulsa';

$route['triprd'] 						= 'produktri';
$route['triprd/pembelian'] 				= 'produktri/pembelian';
$route['triprd/pembayaran'] 			= 'produktri/prosespembayaran';
*/


////////////////////////////////////////////////////////////////
$route['auth/sign-in'] 					= 'welcome/login';
$route['auth/sign-out'] 				= 'mobileapi/dashboard/logout';

$route['market_dashboard'] 				= 'mobileapi/dashboard';
$route['kategori'] 						= 'mobileapi/kategori';
$route['kategori/(:num)']				= 'mobileapi/kategori';
$route['kategori/edit-kat/(:num)']   	= 'mobileapi/kategori/build_edit/$1';
$route['kat/delete/(:num)']				= 'mobileapi/kategori/hapuskat';

$route['produk/pembelian'] 				= 'mobileapi/produk';
$route['produk/pembayaran']             = 'mobileapi/produk';

$route['produk/pembelian/(:num)'] 		= 'mobileapi/produk';
$route['produk/pembayaran/(:num)'] 		= 'mobileapi/produk';

$route['addproduk/(:any)'] 				= 'mobileapi/produk/tambahproduk';
$route['prod/delete/(:num)']			= 'mobileapi/produk/hapusprod';
$route['editproduk/(:any)/(:num)']	    = 'mobileapi/produk/editproduk';

$route['laporan'] 						= 'mobileapi/laporan';
$route['laporan/pag/[A-Z-a-z-0-9\-]/[A-Z-a-z-0-9\-]/(:num)'] = 'mobileapi/laporan/pag/$1/$2/$3';
$route['detailtrx/(:num)']				= 'mobileapi/laporan/trxdetail';

$route['iklan'] 						= 'mobileapi/iklan';
$route['iklan/edit/(:num)'] 			= 'mobileapi/iklan/build_edit/$1';
$route['iklan/delete/(:num)'] 			= 'mobileapi/iklan/hapuskat/$1';

$route['datauser'] 						= 'mobileapi/datauser';
$route['datauser/edit/(:num)'] 			= 'mobileapi/datauser/build_edit/$1';
$route['datauser/delete/(:num)'] 		= 'mobileapi/datauser/hapuskat/$1';

$route['market_kat'] 					= 'mobileapi/market_kat';
$route['market_kat/edit/(:num)'] 		= 'mobileapi/market_kat/build_edit/$1';
$route['market_kat/delete/(:num)'] 		= 'mobileapi/market_kat/hapuskat/$1';

$route['market_prod'] 					= 'mobileapi/market_prod';
$route['market_prod/add']        		= 'mobileapi/market_prod/build_add/$1';
$route['market_prod/edit/(:num)'] 		= 'mobileapi/market_prod/build_edit/$1';
$route['market_prod/delete/(:num)'] 	= 'mobileapi/market_prod/hapuskat/$1';