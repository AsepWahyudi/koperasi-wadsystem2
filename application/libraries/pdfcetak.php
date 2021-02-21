<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';

class Pdfcetak extends TCPDF {

    var $nsi_header = FALSE;
    function __construct() {
        $this->CI =& get_instance();
        parent::__construct();

        $this->SetTopMargin(40);
        $this->setRightMargin(5);
        $this->setLeftMargin(5);
        $this->setFooterMargin(5);

        $this->SetHeaderMargin(5);
        $this->SetAutoPageBreak(true, 8);
        $this->SetAuthor('NSI');
        $this->SetDisplayMode('real', 'default');
        $this->SetFont('helvetica','',10); // default font isi

    }

    public function set_nsi_header($nsi_header) {
        if($nsi_header == TRUE) {
            $this->CI->load->model('setting_m');
            $opsi_val_arr = $this->CI->setting_m->get_key_val();
            foreach ($opsi_val_arr as $key => $value){
                $out[$key] = $value;
            }

            $nsi_header = '<table style="width:100%;text-align:center">
                    <tr>
                        <td style="width:10%;"><img src="assets/theme_admin/img/logo.png" width="60" height="60"></td>
                         <td style="width:90%;"><strong style="font-size: 14px;"> '.$out['nama_lembaga'].'</strong> <br>
                            <table>
                                <tr>
                                    <td>BADAN HUKUM :'.$out['no_badan_hukum'].'</td>
                                </tr>
                                <tr>
                                    <td>'.$out['alamat'].'</td>
                                </tr>
                                <tr>
                                    <td>'.$out['telepon'].'</td>
                                </tr>
                            </table>
                         </td>
                     </tr>
                </table>
                <hr>';
        }
        $this->nsi_header = $nsi_header;
    }
	
	public function set_nsi_headers($nsi_header,$norek, $nama, $alamat) {
        if($nsi_header == TRUE) {
            $this->CI->load->model('setting_m');
            $opsi_val_arr = $this->CI->setting_m->get_key_val();
            foreach ($opsi_val_arr as $key => $value){
                $out[$key] = $value;
            }
			 
            $nsi_header = '<table style="width:100%;" cellspacing="0" cellpadding="2">
                    <tr>
                        <td style="width:55px; text-align:left;">  
							<img src="assets/theme_admin/img/logo.png" width="45" height="45">
						</td>
						<td style="width:555px; font-weight:bold; text-align:left; "> 
							<strong style="font-size: 14px;"> '.$out['nama_lembaga'].'</strong> <br><br>  
							<table style="width:250px; font-weight:bold; text-align:center; ">
                                <tr>
                                    <td>BADAN HUKUM :'.$out['no_badan_hukum'].'</td>
                                </tr>
                                <tr>
                                    <td>'.$out['alamat'].'</td>
                                </tr>
                                <tr>
                                    <td>'.$out['telepon'].'</td>
                                </tr>
                            </table>
						</td>
                     </tr>
                </table> 
                <hr>
				</br>
				 </br></br>
				<table>
					<tr>
						<td style="width:50px; text-align:left;"> 
						<span>&nbsp;&nbsp;No.Rek</span></td><td style="width:10px;text-align:center;">:</td><td style="text-align:left;">'.$norek.'</td>
					</tr>
					<tr>
						<td style="width:50px; text-align:left;"> 
						<span>&nbsp;&nbsp;Nama</span></td><td style="text-align:center;">:</td><td style="text-align:left;">'.$nama.'</td>
					</tr>
					<tr>
						<td style="width:50px; text-align:left;"> 
						<span>&nbsp;&nbsp;Alamat</span></td><td style="text-align:center;">:</td><td style="text-align:left;">'.$alamat.'</td>
					</tr><br>
				</table><br>
				';
        }
        $this->nsi_header = $nsi_header;
    }
	public function set_nsi_headerss($nsi_header,$norek, $nama, $periode, $tertanggal) {
        if($nsi_header == TRUE) {
            $this->CI->load->model('setting_m');
            $opsi_val_arr = $this->CI->setting_m->get_key_val();
            foreach ($opsi_val_arr as $key => $value){
                $out[$key] = $value;
            }
			 
            $nsi_header = '<table style="width:100%;" cellspacing="0" cellpadding="2">
                    <tr>
                        <td style="width:55px; text-align:left;">  
							<img src="assets/theme_admin/img/logo.png" width="45" height="45">
						</td>
						<td style="width:555px; font-weight:bold; text-align:left; "> 
							<strong style="font-size: 14px;"> '.$out['nama_lembaga'].'</strong> <br><br>  
							<table style="width:250px; font-weight:bold; text-align:center; ">
                                <tr>
                                    <td>BADAN HUKUM :'.$out['no_badan_hukum'].'</td>
                                </tr>
                                <tr>
                                    <td>'.$out['koran'].'</td>
                                </tr>
                                <tr>
                                    <td>'.$out['telepon'].'</td>
                                </tr>
                            </table>
						</td>
                     </tr>
                </table> 
                <hr>
				</br>
				 </br></br>
				<table>
					<tr>
						<td style="width:60px; text-align:left;"> 
						<span>&nbsp;&nbsp;No.Rek</span></td><td style="width:10px;text-align:center;">:</td><td style="text-align:left;">'.$norek.'</td>
					</tr>
					<tr>
						<td style="width:60px; text-align:left;"> 
						<span>&nbsp;&nbsp;Nama</span></td><td style="text-align:center;">:</td><td style="text-align:left;">'.$nama.'</td>
					</tr>
					<tr>
						<td style="width:60px; text-align:left;"> 
						<span>&nbsp;&nbsp;Periode</span></td><td style="text-align:center;">:</td><td style="text-align:left;">'.$periode.'</td>
					</tr>
					<tr>
						<td style="width:60px; text-align:left;"> 
						<span>&nbsp;&nbsp;Tertanggal</span></td><td style="text-align:center;">:</td><td style="text-align:left;">'.$tertanggal.'</td>
					</tr>
				</table><br>
				';
        }
        $this->nsi_header = $nsi_header;
    }
    public function marginkiri($val) {
		$this->setLeftMargin($val);
    }
	public function marginatas($val) {
		$this->SetTopMargin($val);
    }
	public function marginkanan($val) {
		$this->setRightMargin($val);
    }
	 
	public function nsi_html($html) {
        $this->SetFont('helvetica', '', 9); // default font header
        $this->writeHTML($html, true, false, true, false, '');
    }
	
    public function nsi_box($text = '', $width = '100%', $spacing = '0', $padding = '10', $border = '0', $align = 'center') {
        $out = '
            <table width="'.$width.'" cellspacing="'.$spacing.'" cellpadding="'.$padding.'" border="'.$border.'">
                <tr>
                    <td align="'.$align.'">'.$text.'</td>
                </tr>
            </table>
        ';
        return $out;
    }


    public function Header() {
        $this->SetFont('helvetica', '', 9); // default font header
        $this->writeHTMLCell(
            $w = 0, $h = 0, $x = '', $y = '',
            $this->nsi_header, $border = 0, $ln = 1, $fill = 0,
            $reseth = true, $align = 'top', $autopadding = true);
        $posisi_y = $this->getY();
        $this->SetTopMargin($posisi_y - 3);
    }

   public function Footer() {
        $x = $this->GetX();
        $y = $this->GetY();
        $pageWidth     = $this->getPageWidth();
        $pageMargins = $this->getMargins();
        $lebar_garis = $pageWidth - ($pageMargins['right']);

        $style = array();
        $this->Line($x, $y, $lebar_garis, $y, $style);
        // Set font
        $this->SetFont('helvetica', 'I', 7); // default font footer
        $this->Cell(0, 0, 'Halaman '.$this->getAliasNumPage().' dari '.$this->getAliasNbPages(), 'T', 0, 'L');
        $this->Cell(0, 0, 'Tanggal Cetak ' . date('d/m/Y H:i:s'), 'T', 0, 'R');
    }
    
} 