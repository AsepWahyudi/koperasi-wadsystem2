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
					<h4 style='text-align:center;' class="color-primary">BUKU BESAR</h4>
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
$con_keyword ="";
$tanggal = explode('-', $tgl);
$con_keyword .= !empty($tgl) ? " DATE(B.TANGGAL) BETWEEN '" . date('Y-m-d', strtotime(str_replace('/', '-', trim($tanggal[0])))) . "' AND '" . date('Y-m-d', strtotime(str_replace('/', '-', trim($tanggal[1])))) . "'" : "";
		
		
if($cabang == "" AND $idakun == "" )
{
	
	if($this->session->userdata("wad_level") == "admin")
	{
		$con_keyword .= "";
	}
	else
	{ 
		$con_keyword .=" AND B.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
	}
	
}
else
{ 
	if($cabang != "" AND $idakun == "" )
	{
		$con_keyword .=" AND B.KODECABANG = '".$cabang. "'";
	}
	if($cabang == "" AND $idakun != "" )
	{
		$con_keyword .=	" AND A.IDAKUN = '".$idakun."'";
	}
	if($cabang != "" AND $idakun != "" )
	{
		$con_keyword .=" AND B.KODECABANG = '".$cabang."'";
		$con_keyword .=	" AND A.IDAKUN = '".$idakun ."'";
		 
	} 
}
 
// echo $con_keyword;

$basequery = "SELECT A.IDDETAIL, A.DEBET, A.KREDIT, B.KETERANGAN, 
			  DATE_FORMAT(B.TANGGAL, '%d/%m/%Y') TANGGAL, C.JENIS_TRANSAKSI, C.AKUN, 
			  C.KODE_AKTIVA FROM vtransaksi_dt A    
			  LEFT JOIN vtransaksi B ON A.IDVTRANSAKSI = B.IDVTRANSAKSI  
			  LEFT JOIN jns_akun C ON A.IDAKUN = C.IDAKUN
			  WHERE $con_keyword AND (A.DEBET <> 0 OR A.KREDIT <> 0)  
			  ORDER BY DATE(B.TANGGAL) ASC";		


$data = $this->dbasemodel->loadsql($basequery)->result(); 	
 
// array_unshift($data['data'], sumBukuBesar());
// array_unshift($data['data'], saldoAwalBB());		  

// print_r(sumBukuBesar($cabang, $tgl, $idakun));

$_where = "";
$tanggal = explode('-', $tgl); 

$_where .= !empty($tgl) ? " DATE(B.TANGGAL) BETWEEN '". date('Y-m-d', strtotime(trim($tanggal[0]))) ."' AND '". date('Y-m-d', strtotime(trim($tanggal[1]))) ."'" : "";
		
if($cabang == "" AND $idakun == "" )
{
	
	if($this->session->userdata("wad_level") == "admin")
	{
		$_where .= ""; 
	}
	else
	{ 
		$_where .="B.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
	} 	 
}
else
{
	if($cabang != "" AND $idakun == "" )
	{
		$_where .=" AND B.KODECABANG = '" .$cabang. "'";
	}
	if($cabang == "" AND $idakun != "" )
	{
		$_where .=	" AND A.IDAKUN = '". $idakun ."'";
	}
	if($cabang != "" AND $idakun != "" )
	{
		$_where .=" AND B.KODECABANG = '" .$cabang. "'";
		$_where .=	" AND A.IDAKUN = '". $idakun ."'";
		 
	} 
}
  
$sql = "SELECT SUM(A.DEBET) DEBET, SUM(A.KREDIT) KREDIT FROM vtransaksi_dt A LEFT JOIN vtransaksi B ON A.IDVTRANSAKSI = B.IDVTRANSAKSI WHERE $_where";

$query = $this->dbasemodel->loadsql($sql);

if($query->num_rows() > 0) {
	$row = $query->row();
	$sum = array('DEBET' => $row->DEBET, 'KREDIT' => $row->KREDIT);
}else{
	$sum = array('DEBET' => 0, 'KREDIT' => 0);
}

array_unshift($data, $sum);

$_where = "";
$tanggal = explode('-', $tgl); 
$_where .= !empty($tgl) ? " DATE(B.TANGGAL) < '". date('Y-m-d', strtotime(trim($tanggal[0]))) ."'" : "";

if($cabang == "" AND $idakun == "" )
{
	
	if($this->session->userdata("wad_level") == "admin")
	{
		$_where .= ""; 
	}
	else
	{ 
		$_where .="B.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
	} 	 
}
else
{
	if($cabang != "" AND $idakun == "" )
	{
		$_where .=" AND B.KODECABANG = '" .$cabang. "'";
	}
	if($cabang == "" AND $idakun != "" )
	{
		$_where .=	" AND A.IDAKUN = '". $idakun ."'";
	}
	if($cabang != "" AND $idakun != "" )
	{
		$_where .=" AND B.KODECABANG = '" .$cabang. "'";
		$_where .=	" AND A.IDAKUN = '". $idakun ."'";
		 
	} 
}
 
$sql = "SELECT IF(ISNULL(SUM(A.DEBET)), 0, SUM(A.DEBET)) DEBET, IF(ISNULL(SUM(A.KREDIT)), 0, SUM(A.KREDIT)) KREDIT,
C.AKUN FROM vtransaksi_dt A LEFT JOIN vtransaksi B ON A.IDVTRANSAKSI = B.IDVTRANSAKSI LEFT JOIN jns_akun C ON A.IDAKUN = C.IDAKUN WHERE $_where ";

$query = $this->dbasemodel->loadsql($sql);

$saldo = 0;
if($query->num_rows() > 0) 
{
	$row = $query->row();
	if($row == "") 
	{
		$saldo = 0;
	} 
	elseif(strtolower($row->AKUN) == "aktiva" || strtolower($row->AKUN) == "tpp") 
	{
		$saldo = ($row->DEBET - $row->KREDIT);
	} 
	else
	{
		$saldo = ($row->KREDIT - $row->DEBET);
	}
}
$sumsaldo = array('SALDO_AWAL' => $saldo);

array_unshift($data, $sumsaldo);


// echo "<pre>";
// print_r($data);
// foreach($data as $index => $val ){
	
	// echo $index."=>".$val."<br>";
	// [IDDETAIL] => 12582
	// [DEBET] => 0
	// [KREDIT] => 20000
	// [KETERANGAN] => Setoran awal simpanan wajib NURUL SYIFA 0001-002-0025
	// [TANGGAL] => 25/11/2020
	// [JENIS_TRANSAKSI] => Simpanan Wajib
	// [AKUN] => Equity
	// [KODE_AKTIVA] => 30102
	// if($index == 2){
		
		// echo $val['SALDO_AWAL']."<br>";
		// echo $val['DEBET']."<br>";
		// echo $val['KREDIT']."<br>";
		
		// echo $val->IDDETAIL ."<br>";
		// echo $val->DEBET ."<br>";
		// echo $val->KREDIT ."<br>";
		// echo $val->KETERANGAN ."<br>";
		// echo $val->TANGGAL ."<br>";
		// echo $val->JENIS_TRANSAKSI ."<br>";
		// echo $val->AKUN ."<br>";
		// echo $val->KODE_AKTIVA ."<br>";
	// }
// }
 
?>
<table style ='margin-top:50px; border:1px;'>
	<thead>
		<tr>
			<th style='font-size:12px;'>No</th>
			<th style='font-size:12px;'>Tanggal</th>
			<th style='font-size:12px;'>Perkiraan</th>
			<th style='font-size:12px;'>Uraian</th> 
			<th style='font-size:12px;'>Debet</th>
			<th style='font-size:12px;'>Kredit</th>
			<th style='font-size:12px;'>Saldo</th>
		</tr>
	</thead>
	<tbody>
		<?php
	 
		$totalDebet  = 0;
		$totalKredit = 0;
		$saldo       = 0;
		$numData 	 = 1;
		foreach($data as $index => $val ){
			
			// echo $index."=>".$val."<br>";
			// echo $val->KETERANGAN;
			
			if($val->KODE_AKTIVA != "1010202"){
				
				if($index == 0){
					
					$saldo = $val['SALDO_AWAL'];
					echo "<tr  style ='font-size:10px;'>" ;
					echo "<td colspan='6' class='text-center'><b>SALDO AWAL</b></td>";
					echo "<td>" . number_format($val['SALDO_AWAL']) . "</td>";
					echo "</tr>";
				}else if($index == 1) {
					$totalDebet = $val['DEBET'];
					$totalKredit = $val['KREDIT']; 
				}else{
					$saldo = (int)$saldo + (int)$val->KREDIT - (int)$val->DEBET;

					$totalDebet = (int)$totalDebet + (int)$val->DEBET;
					$totalKredit = (int)$totalKredit + (int)$val->KREDIT;

					echo "<tr style='font-size:9px;'>";
					echo "<td><b>" . $numData . "</b></td>";
					echo "<td>" . $val->TANGGAL . "</td>";
					echo "<td>" . $val->JENIS_TRANSAKSI . "</td>";
					echo "<td>" . $val->KETERANGAN  . "</td>";
					echo "<td>" . number_format($val->DEBET) . "</td>";
					echo "<td>" . number_format($val->KREDIT) . "</td>";
					echo "<td>" . number_format($saldo) . "</td>";
					echo "</tr>";
					$numData++;
				}
			}
		}
		?>
			 
	</tbody>
	<tfoot>
		<tr>
			<th style='font-size:12px;' colspan='4'>Total</th> 
			<th style='font-size:12px;'><?php echo number_format($totalDebet);?></th>
			<th style='font-size:12px;'><?php echo number_format($totalKredit);?></th>
			<th style='font-size:12px;'><?php echo number_format($saldo);?></th>
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