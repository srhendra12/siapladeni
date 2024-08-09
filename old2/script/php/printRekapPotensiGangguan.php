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

$sql = "select kd_tahap_perencanaan, singkatan, is_active
from p_tahap_perencanaan where is_active = '1'
order by 1";        
$exe = mysqli_query($connDB, $sql);
writeLog(__LINE__, __FILE__, mysqli_error($connDB));
$x=0;
$jmlCol = mysqli_num_rows($exe);

$html .='
<div align="center">
   <h4>REKAPITULASI HASIL PENILAIAN POTENSI ANCAMAN<br>PERIODE '.strtoupper(getBulan($bulan_data))." ".$tahun_data.'</h4>
</div>
<table border="1" cellspacing="1" cellpadding="4">
   <tr>
      <th align="center" rowspan="2" width="30">NO</th>
      <th align="center" rowspan="2" width="200">NAMA UPT</th>
      <th align="center" colspan="'.$jmlCol.'" width="'.(110*$jmlCol).'" class="isCenter">POTENSI ANCAMAN</th>
      <th align="center" rowspan="2" width="100">TOTAL SKOR</th>
      <th align="center" rowspan="2" width="150">KETERANGAN</th>
   </tr>
   <tr>';
      $kdPerencanaan = array();
      while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
         $kdPerencanaan[$x] = $row['kd_tahap_perencanaan'];
         $html .='<th width="110" align="center">'.$row['singkatan'].'</th>';
         $x++;
      }
$html .='</tr>';
   $where = (!empty($_GET['periode'])) ? " where a.tahun_data = '".$_GET['periode']."'" : " where a.tahun_data = '".date('Ym')."'";
   $sql = "select a.kd_perencanaan_umum, a.provinsiid, a.kabupatenkotaid, a.tahun_data, a.keyNumber, 
            a.keterangan, a.valPerilakuNapi,
            (select c.kabupatenkotaname from kabupatenkota c where c.kabupatenkotaid = a.kabupatenkotaid and 
            c.r_provinsiid = a.provinsiid) uptPemasyarakatan
            from lapas_perencanaan_umum a ".$where."
            order by 1";
   $exe = mysqli_query($connDB, $sql);
   writeLog(__LINE__, __FILE__, mysqli_error($connDB));
   $x=0;
   $totalSKor = array();
   while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
      $x++;
      $valPerilakuNapi = $row['valPerilakuNapi'];
      $html .='<tr valign="middle" id="'.$row['kd_perencanaan_umum'].'" style="cursor:pointer;">';  
         $html .= '<td align="center"><b>'.$x.'</b></td>';
         $html .= '<td class="detail">'.$row['uptPemasyarakatan'].'</td>';
         for($i=0;$i<count($kdPerencanaan);$i++){
            $qry = "select sum(totalSkor) totalSkor from lapas_perencanaan_penilaian_evaluasi where kd_tahap_perencanaan = '".$kdPerencanaan[$i]."' and keyNumber = '".$row['keyNumber']."'";        
            $run = mysqli_query($connDB, $qry);
            writeLog(__LINE__, __FILE__, mysqli_error($connDB));
            $data = mysqli_fetch_array($run, MYSQLI_ASSOC);
            $html .= '<td class="detail" align="right">'.number_format($data['totalSkor'],0,',','.').'&nbsp;</td>';
            $totalSKor[$x] += $data['totalSkor'];
         }
         $html .= '<td class="detail" align="right">'.number_format($totalSKor[$x],0,',','.').'&nbsp;</td>';

         $sqx = "select kd_kategori, nmKategori from p_range_perilaku_napi where '".$valPerilakuNapi."' 
                  between nilaiBatasBawah and nilaiBatasAtas";
         $exec = mysqli_query($connDB, $sqx);
         writeLog(__LINE__, __FILE__, mysqli_error($connDB));
         $rows = mysqli_fetch_array($exec, MYSQLI_ASSOC);
         $penilaianNapi = $rows['nmKategori'];

         $html .= '<td class="detail">'.$penilaianNapi.'</td>';
      $html .= '</tr>';
   }
   $html .='
   </table><br><br><br><br>';

$html .='
<table border="0" cellspacing="1" cellpadding="4">
   <tr align="center">
      <td width="450">&nbsp;</td>
      <td width="500">
         Kepala Divisi Pemasyarakatan Kemeneterian Hukum dan HAM<br>'._NM_KANTOR_WILAYAH.',<br>
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