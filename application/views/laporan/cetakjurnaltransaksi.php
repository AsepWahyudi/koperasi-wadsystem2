<?php

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
$gettanggal = explode("-",$tgl);
$tgl1 = $gettanggal[0];
$tgl2 = $gettanggal[1];

$tanggal1 = explode("/",$tgl1);
$daritanggal = $tanggal1[0]." ".$bulan[$tanggal1[1]]." ".$tanggal1[2];
$tanggal2 = explode("/",$tgl2);
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
					<h4 style='text-align:center;' class="color-primary">JURNAL TRANSAKSI</h4>
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
  text-align:center;
  
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>

<?php 

$tanggal = explode('-', $tgl);
$con_keyword .= !empty($tgl) ? " DATE(C.TANGGAL) BETWEEN '" . date('Y-m-d', strtotime(str_replace('/', '-', trim($tanggal[0])))) . "' AND '" . date('Y-m-d', strtotime(str_replace('/', '-', trim($tanggal[1])))) . "'" : "";
		
		
if($cabang == "" AND $idakun == "" )
{
	
	if($this->session->userdata("wad_level") == "admin")
	{
		$con_keyword .= "";
		 
	}
	else
	{ 
		$con_keyword .=" AND C.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
	}
	
}
else
{
	// $con_keyword .=" AND B.KODECABANG = '" .$post['plhcabang']. "' ";
	if($cabang != "" AND $idakun == "" )
	{
		$con_keyword .=" AND C.KODECABANG = '" .$cabang. "'";
	}
	if($cabang == "" AND $idakun != "" )
	{
		$con_keyword .=	" AND A.IDAKUN = '". $idakun ."'";
	}
	if($cabang != "" AND $idakun != "" )
	{
		$con_keyword .=" AND C.KODECABANG = '" .$cabang. "'";
		$con_keyword .=	" AND A.IDAKUN = '". $idakun ."'";
		 
	} 
}
  
$basequery = "SELECT A.IDVTRANSAKSI,
			  DATE_FORMAT(C.TANGGAL, '%d/%m/%Y') TANGGAL, C.KODE_JURNAL, 
			  C.ID_TRX_SIMP, C.ID_TRX_KAS, C.IDPINJ_D, C.REFERENSI, 
			  B.KODE_AKTIVA, B.JENIS_TRANSAKSI, C.KETERANGAN, A.DEBET, A.KREDIT 
			  FROM vtransaksi_dt A 
			  LEFT JOIN jns_akun B ON A.IDAKUN = B.IDAKUN
			  LEFT JOIN vtransaksi C ON A.IDVTRANSAKSI = C.IDVTRANSAKSI
			  WHERE $con_keyword AND C.KODE_JURNAL !='PL'
			  ORDER BY A.IDVTRANSAKSI ASC";
			  
$datajurnal = $this->dbasemodel->loadsql($basequery)->result(); 

?>
<table style ='margin-top:50px; border:1px;'>
	<thead>
		<tr>
			<th style='font-size:12px;'>Tanggal</th>
			<th style='font-size:12px;'>No. Bukti</th>
			<th style='font-size:12px;'>Kode Perkiraan</th>
			<th style='font-size:12px;'>Nama Perkiraan</th>
			<th style='font-size:12px;'>Uraian Jurnal</th>
			<th style='font-size:12px;'>Debet</th>
			<th style='font-size:12px;'>Kredit</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$totalDebet = $totalKredit = 0;

		// NAMBAH
		$totalStDebet = 0;
		$totalStKredit = 0;
		$totalKmDebet = 0;
		$totalKmKredit = 0;
		$totalArDebet = 0;
		$totalArKredit = 0;
		$totalKrDebet = 0;
		$totalKrKredit = 0;
		$totalRtDebet = 0;
		$totalRtKredit = 0;
		// 'ST','KM','AR','KR','RT'

		// KURANG
		$totalPtDebet = 0;
		$totalPtKredit = 0;
		$totalKkDebet = 0;
		$totalKkKredit = 0;
		$totalJtDebet = 0;
		$totalJtKredit = 0;
		
		foreach($datajurnal as $row)
		{
			$noBukti ="";
			if ($row->KODE_JURNAL == 'ST' || $row->KODE_JURNAL == 'PT') {
		  
			    $IDTRXSIMP = "";
			  
			  if($row->ID_TRX_SIMP == "" || $row->ID_TRX_SIMP == null){
				  
				  $IDTRXSIMP = "";
			  }else{
				  $IDTRXSIMP = $row->ID_TRX_SIMP;
			  }
				$noBukti = "TAB.0".$IDTRXSIMP;
				
			} else if ($row->KODE_JURNAL == 'KM' || $row->KODE_JURNAL == 'KK') {
				$noBukti = "KAS.0".$row->ID_TRX_KAS;
			} else if ($row->KODE_JURNAL == 'JT' && $row->ID_TRX_KAS != null) {
				$noBukti = "KRE.0".$row->ID_TRX_KAS;
			} else if ($row->KODE_JURNAL == 'JT' && $row->IDPINJ_D != null) {
				$noBukti = "KRE.0".$row->IDPINJ_D;
			} else if ($row->KODE_JURNAL == 'AK' || $row->KODE_JURNAL == 'KR' || $row->KODE_JURNAL == 'RT') {
				$noBukti = "KRE.0".$row->IDPINJ_D;
			} else if ($row->ID_TRX_SIMP == null || $row->ID_TRX_KAS == null || $row->IDPINJ_D == null) {
				$noBukti = $row->REFERENSI;
			}
			
			if ($row->DEBET != 0) {
				$ket = $row->KETERANGAN;
				$tgl = $row->TANGGAL;
			}else{
				$ket="";
				// $noBukti="";
				$tgl = "";
			}
		?> 
			<tr>
				<td style='font-size:10px;'><?php echo $row->TANGGAL;?></td>
				<td style='font-size:10px;'><?php echo $noBukti;?></td>
				<td style='font-size:10px;'><?php echo $row->KODE_AKTIVA;?></td>
				<td style='font-size:10px;'><?php echo $row->JENIS_TRANSAKSI;?></td>
				<td style='font-size:10px;'><?php echo $ket;?></td>
				<td style='font-size:10px;'><?php echo number_format($row->DEBET);?></td>
				<td style='font-size:10px;'><?php echo number_format($row->KREDIT);?></td>
			</tr>
		<?php
		
			// TAMBAH
			if ($row->KODE_JURNAL == 'ST') { 
				$totalStDebet = (int)$totalStDebet + (int)$row->DEBET;
				$totalStKredit = (int)$totalStKredit + (int)$row->KREDIT;
			}
			if ($row->KODE_JURNAL == 'KM') { 
				$totalKmDebet = (int)$totalKmDebet + (int)$row->DEBET;
				$totalKmKredit = (int)$totalKmKredit + (int)$row->KREDIT;
			}
			if ($row->KODE_JURNAL == 'AR') { 
				$totalArDebet = (int)$totalArDebet + (int)$row->DEBET;
				$totalArKredit = (int)$totalArKredit + (int)$row->KREDIT;
			}
			if ($row->KODE_JURNAL == 'KR') { 
				$totalKrDebet = (int)$totalKrDebet + (int)$row->DEBET;
				$totalKrKredit = (int)$totalKrKredit + (int)$row->KREDIT;
			}
			if ($row->KODE_JURNAL == 'RT') { 
				$totalRtDebet = (int)$totalRtDebet + (int)$row->DEBET;
				$totalRtKredit = (int)$totalRtKredit + (int)$row->KREDIT;
			}
			// KURANG
			if ($row->KODE_JURNAL == 'PT') { 
				$totalPtDebet = (int)$totalPtDebet + (int)$row->DEBET;
				$totalPtKredit = (int)$totalPtKredit + (int)$row->KREDIT;
			}
			if ($row->KODE_JURNAL == 'KK') { 
				$totalKkDebet = (int)$totalKkDebet + (int)$row->DEBET;
				$totalKkKredit = (int)$totalKkKredit + (int)$row->KREDIT;
			}
			if ($row->KODE_JURNAL == 'JT') { 
				$totalJtDebet = (int)$totalJtDebet + (int)$row->DEBET;
				$totalJtKredit = (int)$totalJtKredit + (int)$row->KREDIT;
			}
		}
		
		$grandtotaldebit  = (int)$totalStDebet+(int)$totalKmDebet+(int)$totalArDebet+(int)$totalKrDebet+(int)$totalRtDebet-(int)$totalPtDebet+(int)$totalKkDebet-(int)$totalJtDebet;
		$grandtotalkredit = (int)$totalStKredit+(int)$totalKmKredit+(int)$totalArKredit+(int)$totalKrKredit+(int)$totalRtKredit-(int)$totalPtKredit-(int)$totalKkKredit-(int)$totalJtKredit;
	
		?>
	</tbody>
	<tfoot>
		<tr>
			<th style='font-size:12px;' colspan='5'>Total</th> 
			<th style='font-size:12px;'><?php echo number_format($grandtotaldebit);?></th>
			<th style='font-size:12px;'><?php echo number_format($grandtotalkredit);?></th>
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