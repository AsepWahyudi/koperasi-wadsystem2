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
					<h4 style='text-align:center;' class="color-primary">LABA RUGI</h4>
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
			<th style='font-size:11px;' scope="col">No</th>
			<th style='font-size:11px;' scope="col">Kode</th>
			<th style='font-size:11px;' scope="col">Nama Perkiraan</th>
			<th style='font-size:11px;' scope="col">Saldo Akhir</th>
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
			
			$tableData  = "";
			$numData    = 1;
			$pendapatan = 0;
			$beban      = 0;
			$debet      = 0;
			$kredit     = 0;
			
			foreach($data as $key){
				
				$saldo_akhir = ($key['AKUN'] == 'Pendapatan') ? (int)$key['KREDIT'] : 0;		

				if ($saldo_akhir != 0) {
					
					$tableData .= "<tr>";
					$tableData .= "<td style='font-size:9px;'>" . $numData . "</td>";
					$tableData .= "<td style='font-size:9px;'>" . nbsp($key['_level'], $key['_header'], $key['KODE_AKTIVA']) . "</td>";
					$tableData .= "<td style='font-size:9px;'>" . nbsp($key['_level'], $key['_header'], $key['JENIS_TRANSAKSI']) . "</td>"; 
					$tableData .= "<td style='font-size:9px;'>" . nbsp(0, $key['_header'], number_format($saldo_akhir)) . "</td>";
					$tableData .="</tr>";
				
					$pendapatan = ($key['KODE_AKTIVA'] == '4') ? (int)$key['KREDIT'] : $pendapatan;
					$beban = ($key['KODE_AKTIVA'] == '5') ? ((int)$key['KREDIT'] - (int)$key['DEBET']) : $beban;
				
					$numData++;
					
				} 
			}
			
			echo $tableData;

		?>
	</tbody>
	<tfoot>
		<tr>
			<td style='font-size:11px;' colspan='3' class='text-right'><b>Laba Rugi Sebelum Pajak</b></td>
			<td style='font-size:11px;'><b><?php echo number_format((int)$pendapatan- (int)$beban);?> </b></td>
		</tr>

		<tr>
			<td style='font-size:11px;' colspan='3' class='text-right'><b>Taksiran Pajak</b></td>
			<td style='font-size:11px;'><b> <?php echo number_format(0);?></b></td>
		</tr>

		<tr>
			<td style='font-size:11px;' colspan='3' class='text-right'><b>Laba Rugi Setelah Pajak</b></td>
			<td style='font-size:11px;'><b> <?php echo number_format($pendapatan);?> </b></td> 
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