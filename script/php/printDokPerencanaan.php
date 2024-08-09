<?php 
session_start(); 
error_reporting(1);
include "../../include/config.php"; 
include "../../include/phpfunction.php";
require_once "../../include/modules/tcpdf/tcpdf.php";

// create new PDF document
// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new TCPDF('P', 'mm', 'Letter', true, 'UTF-8', false);
$pdf->SetTitle('INSTRUMENT DETEKSI DINI POTENSI GANGGUAN KEAMANAN DAN KETERTIBAN');
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
$pdf->AddPage('P', 'Letter');

$sql = "select a.keyNumber, a.tahun_data, a.keterangan, a.sumPotensi, a.sumSkor, a.valPersentasePetugas,
        a.valPerilakuNapi, a.kabupatenkotaid, a.provinsiid,
        b.kabupatenkotaname nm_kota, b.alamat, b.email, b.nama_kepala, b.no_telp_kepala, 
        c.provinsiname nm_propinsi, a.is_confirm, a.is_verify, a.dokumen, a.close_date
        from lapas_perencanaan_umum a, kabupatenkota b, provinsi c
        where a.kabupatenkotaid = b.kabupatenkotaid and c.provinsiid = a.provinsiid and a.keyNumber = '".$_GET['keyNumber']."'";
$exe = mysqli_query($connDB, $sql);
writeLog(__LINE__, __FILE__, mysqli_error($connDB));
$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);

$keyNumber		= $row['keyNumber'];
$kdPropinsi		= $row['provinsiid'];
$kdKota			= $row['kabupatenkotaid'];
$nmPropinsi     = $row['nm_propinsi'];
$nmKota  		= $row['nm_kota'];
$alamat  		= $row['alamat'];
$email  		= $row['email'];
$nama_kepala  	= $row['nama_kepala'];
$no_telp_kepala= $row['no_telp_kepala'];
$tahun_data 	= substr($row['tahun_data'],0,4);
$bulan_data 	= intval(substr($row['tahun_data'],-2,2));
$keterangan 	= nl2br($row['keterangan']);
$dokumen  		= $row['dokumen'];

$sumPotensi             = $row['sumPotensi'];
$sumSkor                = $row['sumSkor'];
$valPersentasePetugas 	= $row['valPersentasePetugas'];
$valPerilakuNapi        = $row['valPerilakuNapi'];

$isConfirm 		= $row['is_confirm'];
$isVerify 		= $row['is_verify'];
$closeDate      = explode('-', $row['close_date']);

$sql = "select kd_kategori, nmKategori from p_range_tahap_perencanaan where '".$valPersentasePetugas."' 
            between nilaiBatasBawah and nilaiBatasAtas";
$exe = mysqli_query($connDB, $sql);
writeLog(__LINE__, __FILE__, mysqli_error($connDB));
$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
$kdKategoriPetugas 	= $row['kd_kategori'];
$penilaianPetugas		= $row['nmKategori'];
switch($kdKategoriPetugas){
    case 1 : $bgColorPetugas = "#5BC0DE"; $colorPetugas = "#000000"; break;
    case 2 : $bgColorPetugas = "#FFC000"; $colorPetugas = "#000000"; break;
    case 3 : $bgColorPetugas = "#5CB85C"; $colorPetugas = "#ffffff"; break;
    case 4 : $bgColorPetugas = "#FF0000"; $colorPetugas = "#ffffff"; break;
}

$sql = "select kd_kategori, nmKategori from p_range_perilaku_napi where '".$valPerilakuNapi."' 
            between nilaiBatasBawah and nilaiBatasAtas";
$exe = mysqli_query($connDB, $sql);
writeLog(__LINE__, __FILE__, mysqli_error($connDB));
$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
$kdKategoriNapi	= $row['kd_kategori'];
$penilaianNapi 	= $row['nmKategori'];
switch($kdKategoriNapi){
    case 1 : $bgColorNapi = "#C6D9F1"; $colorNapi = "#000000"; break;
    case 2 : $bgColorNapi = "#FFC000"; $colorNapi = "#000000"; break;
    case 3 : $bgColorNapi = "#F79646"; $colorNapi = "#000000"; break;
    case 4 : $bgColorNapi = "#FF0000"; $colorNapi = "#ffffff"; break;
    case 5 : $bgColorNapi = "#DD0000"; $colorNapi = "#ffffff"; break;
    case 6 : $bgColorNapi = "#C00000"; $colorNapi = "#ffffff"; break;
}

$html = '
<table border="0" cellspacing="3" cellpadding="4">
    <tr>
        <td>
            <div align="center"><b>DASHBOARD INSTRUMEN<br>DETEKSI DINI POTENSI GANGGUAN KEAMANAN DAN KETERTIBAN 
            <br>'.strtoupper($nmKota).'<br>WILAYAH '.strtoupper($nmPropinsi).'<br>PERIODE '.strtoupper(getBulan($bulan_data))." ".$tahun_data.'</b>
            </div>
        </td>
    </tr>
</table><br><br>';

$html .= '
<table class="table table-hover table-condensed">
    <tbody>
        <tr>
            <td width="30%"><label class="control-label" for="thnKegiatan">Periode</label></td>
            <td><b>:</b>&nbsp;'.getBulan($bulan_data).' '.$tahun_data.'</td>
        </tr>
        <tr>
            <td><label class="control-label" for="namaPropinsi">Wilayah</label></td>
            <td><b>:</b>&nbsp;'.$nmPropinsi.'</td>
        </tr> 
        <tr>
            <td><label class="control-label" for="namaKota">UPT Pemasyarakatan</label></td>
            <td><b>:</b>&nbsp;'.$nmKota.'</td>
        </tr> 
        <tr>
            <td><label class="control-label" for="nmKepala">Nama Kepala UPT</label></td>
            <td><b>:</b>&nbsp;'.$nama_kepala.'</td>
        </tr>
        <tr>
            <td><label class="control-label" for="alamat">Alamat</label></td>
            <td><b>:</b>&nbsp;'.$alamat.'</td>
        </tr>
        <tr>
            <td><label class="control-label" for="noTelp">No. HP/WA Kepala UPT</label></td>
            <td><b>:</b>&nbsp;'.$no_telp_kepala.'</td>
        </tr>
        <tr>
            <td><label class="control-label" for="email">Email</label></td>
            <td><b>:</b>&nbsp;'.$email.'</td>
        </tr>
        <tr>
            <td><label class="control-label" for="keterangan">Keterangan</label></td>
            <td><b>:</b>&nbsp;'.$keterangan.'</td>
        </tr>
    </tbody>	
</table>
<div class="space"></div>
<br>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->SetMargins(20, 12, 20, true);
$pdf->AddPage('P', 'Letter');

$html ='
<h4>DASHBOARD PENGURANGAN RESIKO  GANGGUAN KEAMANAN</h4>
<table border="1" cellspacing="1" cellpadding="4">
    <tr align="center">
        <th width="30"><b>No</b></th>
        <th width="180"><b>Elemen Assessment</b></th>
        <th width="90"><b>Nilai Bobot<br>Ideal</b></th>
        <th width="100"><b>Pemenuhan<br>(Total Skor)</b></th>
        <th width="100"><b>Nilai bobot<br>potensi ganguan keamanan</b></th>
        <th><b>Keterangan</b></th>
    </tr>';
    
    // Load Data Tahap Kesiapan Pelaksanaan
    $sql = "select kd_tahap_perencanaan, indikator, is_active
        from p_tahap_perencanaan where is_active = '1'
        order by 1";        
    $exe = mysqli_query($connDB, $sql);
    writeLog(__LINE__, __FILE__, mysqli_error($connDB));
    $x=0;
    while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
        $x++;
        $kdKriteria[$x] = $row['kd_tahap_perencanaan'];
        $romawi     	 = romawi($x);
        $html .= '<tr valign="middle" class="bgGrey">'; 
            $html .= '<td align="center"><b>'.$romawi.'</b></td>';
            $html .= '<td colspan="7"><b>'.strtoupper($row['indikator']).'</b></td>';
        $html .= '</tr>';

        // Load Data Sub Tahap Kesiapan Pelaksanaan
        $sqc = "select a.kd_sub_tahap_perencanaan, a.indikator, a.bobot, a.is_active
                from p_sub_tahap_perencanaan a, p_tahap_perencanaan b
                where a.kd_tahap_perencanaan = b.kd_tahap_perencanaan and 
                a.kd_tahap_perencanaan = '".$kdKriteria[$x]."' and a.is_active = '1'
                order by 1";        
        $exc = mysqli_query($connDB, $sqc);
        writeLog(__LINE__, __FILE__, mysqli_error($connDB));
        $i=0;
        while($roc = mysqli_fetch_array($exc, MYSQLI_ASSOC)){
            $i++;
            $kdSubKriteria[$i]  	= $roc['kd_sub_tahap_perencanaan'];
            $bobot[$i] 				= intval($roc['bobot']);
            $html .= '<tr valign="middle">'; 
                    $html .= '<td align="center"><b>'.$i.'</b></td>';
                    $html .= '<td>'.$roc['indikator'].'</td>';
                    $html .= '<td align="center" class="isCenter">';
                    $sqp = "select totalSkor, skorPotensi, catatan
                            from lapas_perencanaan_penilaian_evaluasi
                            where kd_sub_tahap_perencanaan = '".$kdSubKriteria[$i]."' and 
                            keyNumber = '".$keyNumber."'";
                    $exp 	= mysqli_query($connDB, $sqp);
                    $rop 	= mysqli_fetch_array($exp, MYSQLI_ASSOC);
                    $totalSkorValDB[$i] 		= intval($rop['totalSkor']);
                    $skorPotensiValDB[$i] 	= intval($rop['skorPotensi']);
                    $ketValDB[$i] 				= $rop['catatan'];
                    $skorPotensi[$i] 			= (!empty($skorPotensiValDB[$i])) ? $skorPotensiValDB[$i] : $bobot[$i];
                    $html .= '<span>'.$bobot[$i].'</span>';
                    $html .= '</td>';
                    $html .= '<td align="center">';
                    $html .= '<span>'.$totalSkorValDB[$i].'</span>';
                    $html .= '</td>';
                    $html .= '<td align="center">';
                    $html .= '<span>'.$skorPotensi[$i].'</span>';
                    $html .= '</td>';
                    $html .= '<td>';
                    $html .= '<p>'.$ketValDB[$i].'</p>';
                    $html .= '</td>';
                    $html .= '</tr>';
            $totBobot += $bobot[$i];
        }
    }
    $html .= '</tbody>
    <tr class="bgGrey">
        <td colspan="2" align="left"><b>JUMLAH TOTAL</b></td>
        <td align="center">
        <b class="txtBlue">'.$totBobot.'</b>
        </td>
        <td align="center">
        <b class="txtBlue">'.intval($sumSkor).'</b>
        </td>
        <td align="center">
        <b class="txtBlue">'.intval($sumPotensi).'</b>
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr class="bgGrey">
        <td colspan="2" align="left"><b>PERSENTASE NILAI DIPEROLEH</b></td>
        <td align="center">&nbsp;</td>
        <td title="'.$penilaianPetugas.'"align="center" style="background-color:'.$bgColorPetugas.'; color:'.$colorPetugas.'">
        <b>'.number_format($valPersentasePetugas, 2,".",",").' %</b>
        </td>
        <td title="'.$penilaianNapi.'" align="center" style="background-color:'.$bgColorNapi.'; color:'.$colorNapi.'">
        <b>'.number_format($valPerilakuNapi, 2,".",",").' %</b>
        </td>
        <td>&nbsp;</td>
    </tr>
</table><br>';

$html .='
<h4 class="txtBlue">HASIL EVALUASI</h4>
<table border="1" cellspacing="1" cellpadding="4">
    <tr>
        <td><b>Kecenderungan Pemahaman Petugas Terhadap Tupoksi Pemasyarakatan</b></td>
    </tr>';
    $qry = "select kd_kategori, nmKategori from p_range_tahap_perencanaan 
            where '".$valPersentasePetugas."' 
            between nilaiBatasBawah and nilaiBatasAtas";
    $run = mysqli_query($connDB, $qry);
    writeLog(__LINE__, __FILE__, mysqli_error($connDB));
    $data = mysqli_fetch_array($run, MYSQLI_ASSOC);
    $kdKategoriPetugas 	= $data['kd_kategori'];
    $penilaianPetugas   = $data['nmKategori'];
    switch($kdKategoriPetugas){
        case 1 : $bgColorPetugas = "#5BC0DE"; $colorPetugas = "#000000"; break;
        case 2 : $bgColorPetugas = "#FFC000"; $colorPetugas = "#000000"; break;
        case 3 : $bgColorPetugas = "#5CB85C"; $colorPetugas = "#ffffff"; break;
        case 4 : $bgColorPetugas = "#FF0000"; $colorPetugas = "#ffffff"; break;
    }
    $html .='<tr><td style="background-color:'.$bgColorPetugas.'; color:'.$colorPetugas.'"><b>'.strtoupper($penilaianPetugas).'</b>&nbsp;</td></tr>';
    $html .='
</table><br><br>';

$html .='
<table border="1" cellspacing="1" cellpadding="4">
    <tr>
        <td><b>Kecenderungan Perilaku Warga Binaan Pemasyarakatan</b></td>
    </tr>';
    $qry = "select kd_kategori, nmKategori from p_range_perilaku_napi 
            where '".$valPerilakuNapi."' 
            between nilaiBatasBawah and nilaiBatasAtas";
    $run = mysqli_query($connDB, $qry);
    writeLog(__LINE__, __FILE__, mysqli_error($connDB));
    $data = mysqli_fetch_array($run, MYSQLI_ASSOC);
    $kdKategoriNapi	= $data['kd_kategori'];
    $penilaianNapi 	= $data['nmKategori'];
    switch($kdKategoriNapi){
        case 1 : $bgColorNapi = "#C6D9F1"; $colorNapi = "#000000"; break;
        case 2 : $bgColorNapi = "#FFC000"; $colorNapi = "#000000"; break;
        case 3 : $bgColorNapi = "#F79646"; $colorNapi = "#000000"; break;
        case 4 : $bgColorNapi = "#FF0000"; $colorNapi = "#ffffff"; break;
        case 5 : $bgColorNapi = "#DD0000"; $colorNapi = "#ffffff"; break;
        case 6 : $bgColorNapi = "#C00000"; $colorNapi = "#ffffff"; break;
    }
    $html .='<tr><td style="background-color:'.$bgColorNapi.'; color:'.$colorNapi.'"><b>'.strtoupper($penilaianNapi).'</b>&nbsp;</td></tr>';
    $html .='
</table><br>';

// echo $html;
// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->SetMargins(20, 12, 20, true);
$pdf->AddPage('P', 'Letter');

$html ='
<h5 class="txtBlue">KETERANGAN KECENDERUNGAN PEMAHAMAN PETUGAS TERHADAP TUPOKSI PEMASYARAKATAN</h5>
<table border="1" cellspacing="1" cellpadding="4">
    <tr align="center">
        <td width="400"><b>Kategori</b></td>
        <td width="200" colspan="2"><b>Rentan Nilai (%)</b></td>
    </tr>
    <tbody>';
       $sql = "select kd_kategori, nmKategori, nilaiBatasBawah, nilaiBatasAtas 
                from p_range_tahap_perencanaan order by kd_kategori";
        $exe = mysqli_query($connDB, $sql);
        $x=0;
        while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
            $x++;
            switch($x){
                case 1 : $bgColor = "#5BC0DE"; $color = "#000000"; break;
                case 2 : $bgColor = "#FFC000"; $color = "#000000"; break;
                case 3 : $bgColor = "#5CB85C"; $color = "#ffffff"; break;
                case 4 : $bgColor = "#FF0000"; $color = "#ffffff"; break;
            }
            $html .= '<tr style="background-color:'.$bgColor.'; color:'.$color.'">';
                $html .= '<td width="400">'.$row['nmKategori'].'</td>';
                $html .= '<td align="right" width="100">'.$row['nilaiBatasBawah'].'</td>';
                $html .= '<td align="right" width="100">'.$row['nilaiBatasAtas'].'</td>';
            $html .= '</tr>';
        }
    $html .='</tbody>
</table><br>';

$html .='
<h5 class="txtBlue">KETERANGAN KECENDERUNGAN PERILAKU WARGA BINAAN PEMASYARAKATAN</h5>
<table border="1" cellspacing="1" cellpadding="4">
    <tr align="center">
        <td width="400"><b>Kategori</b></td>
        <td width="200" colspan="2"><b>Rentan Nilai (%)</b></td>
    </tr>
    <tbody>';
    $sql = "select kd_kategori, nmKategori, nilaiBatasBawah, nilaiBatasAtas 
            from p_range_perilaku_napi order by kd_kategori";
        $exe = mysqli_query($connDB, $sql);
        $x=0;
        while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
            $x++;
            switch($x){
                case 1 : $bgColor = "#C6D9F1"; $color = "#000000"; break;
                case 2 : $bgColor = "#FFC000"; $color = "#000000"; break;
                case 3 : $bgColor = "#F79646"; $color = "#000000"; break;
                case 4 : $bgColor = "#FF0000"; $color = "#ffffff"; break;
                case 5 : $bgColor = "#DD0000"; $color = "#ffffff"; break;
                case 6 : $bgColor = "#C00000"; $color = "#ffffff"; break;
            }
            $html .= '<tr style="background-color:'.$bgColor.'; color:'.$color.'">';
                $html .= '<td width="400">'.$row['nmKategori'].'</td>';
                $html .= '<td align="right" width="100">'.$row['nilaiBatasBawah'].'</td>';
                $html .= '<td align="right" width="100">'.$row['nilaiBatasAtas'].'</td>';
            $html .= '</tr>';
        }
    $html .='</tbody>
</table><br>';

$html .='
<h4 class="txtBlue">Lampiran Dokumen :</h4>';
if(!empty($dokumen)){
    $html .='<table border="0" cellspacing="1" cellpadding="4">
        <tr>
            <td width="200"><label class="control-label" for="dokumen">File dokumen</label></td>
            <td><b>:</b>&nbsp;'.$dokumen.'</a></td>
        </tr>
    </table>';
}

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');


// reset pointer to the last page
$pdf->lastPage();

if(empty($closeDate[0]) || $closeDate[0] == '0000'){
    $sql = "update lapas_perencanaan_umum set print_date = NOW(), print_by = '".$_SESSION['username']."', is_print = 1, is_close = 1,
            print_date = '".date('Y-m-d h:i:s')."', close_date  = '".date('Y-m-d h:i:s')."'
            where keyNumber = '".$_GET['keyNumber']."'";
    $exe4 = mysqli_query($connDB, $sql);
    writeLog(__LINE__, __FILE__, mysqli_error($connDB));
}

$pdf->Output('HASIL_EVALUASI_DETEKSI_DINI_POTENSI_GANGGUAN_KEAMANAN_DAN_KETERTIBAN_'.strtoupper($nmKota).'_WILAYAH '.strtoupper($nmPropinsi).'_PERIODE_DATA_'.strtoupper(getBulan($bulan_data))."_".$tahun_data.'.pdf', 'I');