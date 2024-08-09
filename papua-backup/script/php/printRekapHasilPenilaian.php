<?php 
session_start(); 
error_reporting(1);
include "../../include/config.php"; 
include "../../include/phpfunction.php";
require_once "../../include/modules/tcpdf/tcpdf.php";

// create new PDF document
// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new TCPDF('P', 'mm', 'Letter', true, 'UTF-8', false);
$pdf->SetTitle('REKAPITULASI HASIL PENILAIAN DETEKSI DINI POTENSI GANGGUAN KEAMANAN DAN KETERTIBAN');
$pdf->SetAuthor('Author');
$pdf->SetDisplayMode('fullpage', 'continuous');

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetHeaderMargin(0);
// $pdf->SetTopMargin(10);
$pdf->setFooterMargin(20);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set font
$pdf->SetFont('times', '', 12);

// ---------------------------------------------------------

// add a page
$pdf->SetMargins(20, 10, 20, true);
$pdf->AddPage('L', 'Letter');

$tahun_data 	= substr($_GET['periode'],0,4);
$bulan_data 	= substr($_GET['periode'],-1,2);

$html = '
<table border="0" cellspacing="2" cellpadding="2" width="1300">
   <tr style="vertical-align: middle;">
      <td align="right" width="230"><img src="'.BASE_URL.'assets/common/img/kemenkumham.png" height="100px"></td>
      <td align="center">
         <b>KEMENTERIAN HUKUM DAN HAK ASASI MANUSIA REPUBLIK INDONESIA</b><br>
         <b>'._NM_KANTOR_WILAYAH.'</b><br>
         '._ALAMAT_KANTOR.'<br>
         Telepon : '._TELP.'<br>
         <i>website : <span style="color:blue">'._WEBSITE.'</span> / email : <span style="color:blue">'._EMAIL.'</span></i><br>
      </td>
   </tr>
</table><hr><br><br>';


$html .='
<div align="center">
   <h4>REKAPITULASI HASIL PENILAIAN<br>DETEKSI DINI POTENSI GANGGUAN KEAMANAN DAN KETERTIBAN<br>PERIODE '.strtoupper(getBulan($bulan_data))." ".$tahun_data.'</h4>
</div>
<table border="1" cellspacing="1" cellpadding="4">
   <tr>
      <th rowspan="2" align="center" width="30">No</th>
      <th rowspan="2" align="center" width="170">Wilayah</th>
      <th rowspan="2" align="center" width="200">UPT Pemasyarakatan</th>
      <th colspan="2" align="center" width="200">Persentasi Penilaian</th>
      <th colspan="2" align="center" width="320">Hasil Penilaian</th>
   </tr>
   <tr>
      <th align="center" width="100">Total Skor<br>(%)</th>
      <th align="center" width="100">Nilai bobot<br>potensi ganguan keamanan<br>(%)</th>
      <th align="center" width="160">Kecenderungan Pemahaman Petugas<br>terhadap Tupoksi Pemasyarakatan</th>
      <th align="center" width="160">Kecenderungan Perilaku<br>Warga Binaan Pemasyarakatan</th>
   </tr>';
    
	$where = (!empty($_GET['periode'])) ? " where lpu.tahun_data = '".$_GET['periode']."'" : " where lpu.tahun_data = '".date('Ym')."'";
	$where .= ($_SESSION['access'] < 4) ? "and p.kd_wilayah = ".$_SESSION['wilayah'] : "";

   $sql = "select lpu.kd_perencanaan_umum, lpu.provinsiid, lpu.kabupatenkotaid, lpu.tahun_data, lpu.valPersentasePetugas, lpu.valPerilakuNapi,lpu.keyNumber, lpu.is_verify, lpu.is_confirm, lpu.is_close, lpu.is_rejected, lpu.is_print, p.provinsiname wilayah, k.kabupatenkotaname uptPemasyarakatan 
   from lapas_perencanaan_umum lpu  left join provinsi p on p.provinsiid = lpu.provinsiid  left join kabupatenkota k on k.kabupatenkotaid = lpu.kabupatenkotaid and k.r_provinsiid = p.provinsiid ".$where." order by 1";
   $exe = mysqli_query($connDB, $sql);
   writeLog(__LINE__, __FILE__, mysqli_error($connDB));
   $x=0;
   while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
      $x++;
      $html .='<tr valign="middle" id="'.$row['kd_perencanaan_umum'].'" style="cursor:pointer;">';  
         $html .= '<td align="center"><b>'.$x.'</b></td>';
         $html .= '<td class="detail">'.$row['wilayah'].'</td>'; 
         $html .= '<td class="detail">'.$row['uptPemasyarakatan'].'</td>';
         $html .= '<td class="detail" align="right">'.number_format($row['valPersentasePetugas'],2,',','.').'&nbsp;</td>';
         $html .= '<td class="detail" align="right">'.number_format($row['valPerilakuNapi'],2,',','.').'&nbsp;</td>';
         $qry = "select kd_kategori, nmKategori from p_range_tahap_perencanaan 
                  where '".$row['valPersentasePetugas']."' 
                  between nilaiBatasBawah and nilaiBatasAtas";
         $run = mysqli_query($connDB, $qry);
         writeLog(__LINE__, __FILE__, mysqli_error($connDB));
         $data = mysqli_fetch_array($run, MYSQLI_ASSOC);
         $kdKategoriPetugas 	= $data['kd_kategori'];
         $penilaianPetugas   = $data['nmKategori'];

         $html .= '<td class="detail">'.$penilaianPetugas.'&nbsp;</td>';

         $qry = "select kd_kategori, nmKategori from p_range_perilaku_napi 
                  where '".$row['valPerilakuNapi']."' 
                  between nilaiBatasBawah and nilaiBatasAtas";
         $run = mysqli_query($connDB, $qry);
         writeLog(__LINE__, __FILE__, mysqli_error($connDB));
         $data = mysqli_fetch_array($run, MYSQLI_ASSOC);
         $kdKategoriNapi	= $data['kd_kategori'];
         $penilaianNapi 	= $data['nmKategori'];
         $html .= '<td>'.$penilaianNapi.'&nbsp;</td>';
      $html .= '</tr>';
   }
   $html .='
   </table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->SetMargins(20, 12, 20, true);
$pdf->AddPage('L', 'Letter');

$html ='
<br><br><br><br>
<table border="0" cellspacing="1" cellpadding="4">
   <tr align="center">
      <td width="450">&nbsp;</td>
      <td width="500">
         <br>'._NM_KANTOR_WILAYAH.',<br>
         <br><br><br><br><br>
         '._NM_KEPALA.'<br>
         NIP. '._NIK_KEPALA.'
      </td>
   </tr>
</table><br><br><br><br><br><br><br><br><br><br>

<i>Nb : Hasil rekapitulasi di cetak dari sistem <span style="color:blue;">'._TITLE.'</span></i>';
// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();
$pdf->Output('REKAPITULASI_HASIL_PENILAIAN_DETEKSI_DINI_POTENSI_GANGGUAN_KEAMANAN_DAN_KETERTIBAN_PERIODE_'.strtoupper(getBulan($bulan_data))."_".$tahun_data.'.pdf', 'I');