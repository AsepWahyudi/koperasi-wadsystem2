<?php
// header("Content-type: application/vnd-ms-excel");
// header("Content-Disposition: attachment; filename=Data Neraca Saldo.xls");
error_reporting(1);
ini_set('memory_limit', '512');      // DIDN'T WORK
ini_set('memory_limit', '512MB');    // DIDN'T WORK
ini_set('memory_limit', '512M');     // OK - 512MB
ini_set('memory_limit', 512000000);  // OK - 512MB
	// echo print_r($tanggal);
// if($cabang == ""){
	// redirect('jurnal-transaksi');
// }
$bulan = array(
		'01' => 'JANUARI',
		'02' => 'FEBRUARI',
		'03' => 'MARET',
		'04' => 'APRIL',
		'05' => 'MEI',
		'06' => 'JUNI',
		'07' => 'JULI',
		'08' => 'AGUSTUS',
		'09' => 'SEPTEMBER',
		'10' => 'OKTOBER',
		'11' => 'NOVEMBER',
		'12' => 'DESEMBER',
);

$gettanggal = explode("-",$tanggal);
$tgl1       = $gettanggal[0];
$tgl2       = $gettanggal[1];

$tanggal1      = explode("/",$tgl1);
$daritanggal   = $tanggal1[0]." ".$bulan[$tanggal1[1]]." ".$tanggal1[2];
$tanggal2      = explode("/",$tgl2);
$sampaitanggal = $tanggal2[0]." ".$bulan[$tanggal2[1]]." ".$tanggal2[2];



?>
<div class="col-sm-12" style='border-bottom: 1px solid black;'>
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
			<div class="row">
				<div class="col-sm-12 col-lg-12"> 
				</div> 
			</div>
			<div class="row">
				<div class="col-sm-12 col-lg-12">
					<span style='text-align:center; font-size:14px;' class="color-primary"><b>KOPERASI SIMPAN PINJAM DAN PEMBIAYAAN SYARI'AH</b></span>
				</div> 
			</div>
			<div class="row">
				<div class="col-sm-12 col-lg-12">
					<span style='text-align:center; font-size:13px;' class="color-primary"><b>WAHYU ARTA SEJAHTERA </b></span>
				</div> 
			</div>
			<div class="row">
				<div class="col-sm-12 col-lg-12">
					<span style='text-align:center; font-size:12px;' class="color-primary"><?php echo $datacabang->NAMA;?></span>
				</div> 
			</div>
			<div class="row">
				<div class="col-sm-12 col-lg-12">
					<span style='text-align:center; font-size:12px;' class="color-primary"><?php echo $datacabang->ALAMAT;?></span>
				</div> 
			</div>
        </div>
    </div>
</div>
<div class="col-sm-12">
    <div class="panel  b-primary bt-sm ">
        <div class="panel-content">
			<div class="row">
				<div class="col-sm-12 col-lg-12">
					<h4 style='text-align:center;' class="color-primary">NERACA SALDO</h4>
				</div> 
			</div>
			<div class="row">
				<div class="col-sm-12 col-lg-12">
					<center style='margin-top:-20px;'><span style='text-align:center; font-size:12px;' class="color-primary"><?php echo $datacabang->NAMA;?></span></center>
				</div> 
			</div>
			<div class="row">
				<div class="col-sm-12 col-lg-12">
					<center><span style='text-align:center; font-size:12px;' class="color-primary">Periode :<?php echo $daritanggal;?> s.d. <?php echo $sampaitanggal;?></span></center>
				</div> 
			</div>
        </div>
    </div>
</div>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px; 
  
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style> 
 
<table style ='margin-top:50px; border:1px;'>
	<thead>
		<tr>
			<th style='font-size:11px;' scope="col" width="90">#</th>
			<th style='font-size:11px;' scope="col">Nama Akun</th>
			<th style='font-size:11px;' scope="col">Debet</th>
			<th style='font-size:11px;' scope="col">Kredit</th>
			<th style='font-size:11px;' scope="col">Saldo</th>
		</tr>
	</thead>
	<tbody>
		<?php
			
			function nbsp($level, $header, $akun) {
				$loop  = ((int)$level * 3);
				$spasi = '';
				for($i=0; $i<=$loop; $i++)
				{
					$spasi .= '&nbsp;';
				}
				if($header == '1') 
				{ 
					return $spasi . '<b>'. $akun .'<b>'; 
				}
				return $spasi . $akun;
			}
			
			$tableData ="";
			$numData = 0;
			
			$debitactiva     = 0;
			$debitpasiva     = 0;
			$debiteq         = 0;
			
			$kreditactiva    = 0;
			$kreditpasiva    = 0;
			$krediteq        = 0;
			
			$saldoactiva     = 0;
			$saldopasiva     = 0;
			$saldoeq         = 0;
			
			$jmldebitactiva  = 0;
			$jmldebitpasiva  = 0;
			$jmldebiteq      = 0;
			 
			$jmlkreditactiva = 0;
			$jmlkreditpasiva = 0;
			$jmlkrediteq     = 0;
			
			$jmlsaldoactiva  = 0;
			$jmlsaldopasiva  = 0;
			$jmlsaldoeq      = 0;
			
			foreach($data as $key){
				
				$debitactiva = $numData >= 2 ? ($key['AKUN'] == 'Aktiva' ? ((int)$key['KREDIT'] - (int)$key['DEBET']) : 0) : (int)$key['DEBET'];
				$debitpasiva = $numData >= 2 ? ($key['AKUN'] == 'Pasiva' ? ((int)$key['KREDIT'] - (int)$key['DEBET']) : 0) : (int)$key['DEBET'];
				$debiteq     = $numData >= 2 ? ($key['AKUN'] == 'Equity' ? ((int)$key['KREDIT'] - (int)$key['DEBET']) : 0) : (int)$key['DEBET'];
 
				$kreditactiva = $numData >= 2 ? ($key['AKUN'] == 'Aktiva' ? ((int)$key['DEBET'] - (int)$key['KREDIT']) : 0) : (int)$key['KREDIT'];
				$kreditpasiva = $numData >= 2 ? ($key['AKUN'] == 'Pasiva' ? ((int)$key['DEBET'] - (int)$key['KREDIT']) : 0) : (int)$key['KREDIT'];
				$krediteq     = $numData >= 2 ? ($key['AKUN'] == 'Equity' ? ((int)$key['DEBET'] - (int)$key['KREDIT']) : 0) : (int)$key['KREDIT'];
 
				$saldoactiva = $key['AKUN'] == 'Aktiva' ? (int)$key['DEBET'] - (int)$key['KREDIT'] : (int)$key['KREDIT'] - (int)$key['DEBET'];
				$saldopasiva = $key['AKUN'] == 'Pasiva' ? (int)$key['DEBET'] - (int)$key['KREDIT'] : (int)$key['KREDIT'] - (int)$key['DEBET'];
				$saldoeq = $key['AKUN'] == 'Equity' ? (int)$key['DEBET'] - (int)$key['KREDIT'] : (int)$key['KREDIT'] - (int)$key['DEBET'];
				
				$saldo = ($key['AKUN'] == 'Aktiva') ? (int)$key['DEBET'] - (int)$key['KREDIT'] : (int)$key['KREDIT'] - (int)$key['DEBET'];
				// echo $key['JENIS_TRANSAKSI']."</br>";
				$tableData .= "<tr>";
				$tableData .= "<td style='font-size:9px;'>" . nbsp(0, $key['_header'], $key['KODE_AKTIVA']) . "</td>";
				$tableData .= "<td style='font-size:9px;'>" . nbsp($key['_level'], $key['_header'], $key['JENIS_TRANSAKSI']) . "</td>" ;
				$tableData .= "<td style='font-size:9px;'>" . nbsp(0, $key['_header'], (($key['DEBET'] == 0) ? "0" : number_format($key['DEBET']))) . "</td>";
				$tableData .= "<td style='font-size:9px;'>" . nbsp(0, $key['_header'], (($key['KREDIT'] == 0) ? "0" : number_format($key['KREDIT']))) . "</td>";
				$tableData .= "<td style='font-size:9px;'>" . nbsp(0, $key['_header'], ($saldo == 0 ? "0" : number_format($saldo))) . "</td>";
				$tableData .= "</tr>";
				
				if($key['AKUN'] == 'Aktiva'){
					$jmldebitactiva = $jmldebitactiva + (int)$debitactiva;
					$jmlkreditactiva = $jmlkreditactiva + (int)$kreditactiva;
					$jmlsaldoactiva  = $jmlsaldoactiva + (int)$saldoactiva;
				}
				if($key['AKUN'] == 'Pasiva'){
					$jmldebitpasiva  = $jmldebitpasiva + (int)$debitpasiva;
					$jmlkreditpasiva = $jmlkreditpasiva + (int)$debitpasiva;
					$jmlsaldopasiva  = $jmlsaldopasiva + (int)$saldopasiva;
				}
				if($key['AKUN'] == 'Equity'){
					$jmldebiteq      = $jmldebiteq + (int)$debiteq;
					$jmlkrediteq     = $jmlkrediteq + (int)$krediteq;
					$jmlsaldoeq      = $jmlsaldoeq + (int)$saldoeq; 
				}
				// $jmldebitactiva  = $jmldebitactiva + (int)$debitactiva;
				 
			
				$numData++;
				
			}
			 
			echo $tableData;
			
		?>
	</tbody>
	<tfoot>
		 
		<tr> 
			<th colspan='2' style='font-size:11px;'><strong>TOTAL AKTIVA</strong></th> 
			<th style='font-size:11px;'><?php echo number_format($jmldebitactiva); ?></th>
			<th style='font-size:11px;'><?php echo number_format($jmlkreditactiva); ?> </th>
			<th style='font-size:11px;'><?php echo number_format($jmlsaldoactiva); ?> </th>
		</tr> 
		<tr> 
			<th style='font-size:11px;' colspan='2' ><strong>TOTAL PASIVA</strong></th> 
			<th style='font-size:11px;'><?php echo number_format($jmldebitpasiva); ?> </th> 
			<th style='font-size:11px;'><?php echo number_format($jmlkreditpasiva); ?></th> 
			<th style='font-size:11px;'><?php echo number_format($jmlsaldoeq); ?></th> 
		</tr> 
		<tr> 
			<th style='font-size:11px;' colspan='2'><strong>TOTAL EQUITY</strong></th> 
			<th style='font-size:11px;'><?php echo number_format($jmldebiteq); ?></th> 
			<th style='font-size:11px;'><?php echo number_format($jmlkrediteq); ?></th> 
			<th style='font-size:11px;'><?php echo number_format($jmlsaldoeq); ?></th> 
		</tr> 
	</tfoot>
</table>

<table style ='margin-top:50px; border:0px;  background-color: #ffff; width:50%;'> 
	<tr style ='border:0px;  background-color: #ffff;'>
		<?php
		if($this->session->userdata("wad_level") == "admin")
		{
		?>
		<th style='font-size:12px;'>Ketua Koperasi</th>
		<?php
		}
		if($this->session->userdata("wad_level") == "kepala_cabang")
		{
		?>
		<th style='font-size:12px;'>Kepala Cabang</th> 
		<?php
		}
		?> 
		<th style='font-size:12px;'>Sekertaris</th>
		<th style='font-size:12px;'>Bendahara</th> 
	</tr>
	<tr style='border:0px;  background-color: #ffff; line-height: 120px;'>
		<th style='font-size:12px;'>(..................................)</th> 
		<th style='font-size:12px;'>(..................................)</th> 
		<th style='font-size:12px;'>(..................................)</th> 
	</tr> 
</table>
<span style='font-size:10px;'>Dicetak Tanggal <?php echo date("d/m/Y H:i:s");?></span>   