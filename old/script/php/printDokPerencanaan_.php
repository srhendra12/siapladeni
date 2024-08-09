
<?php session_start(); error_reporting(0); ?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7]> <html xmlns="http://www.w3.org/1999/xhtml" class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html xmlns="http://www.w3.org/1999/xhtml" class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html xmlns="http://www.w3.org/1999/xhtml" class="lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html xmlns="http://www.w3.org/1999/xhtml"> <!--<![endif]-->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="description" content="">
	<meta name="author" content="">
	
	<!-- Bootstrap core CSS -->
   <link href="<?=BASE_URL?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	
	<link rel="stylesheet" href="<?=BASE_URL?>assets/common/css/main.css" />
	
	<!-- jQuery v1.11.3 -->
   <script src="<?=BASE_URL?>assets/common/js/jquery.min.js"></script>
   
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>
   	
	<script type="text/javascript">
		$(document).ready(function(){
         var doc = new jsPDF();
            var specialElementHandlers = {
                  '#editor': function (element, renderer) {
                  return true;
            }
         };

         $('#konvert').click(function () {   
            doc.fromHTML($('#konten').html(), 15, 15, {
               'width': 170,
               'elementHandlers': specialElementHandlers
            });
            doc.save('contoh-file.pdf');
         });

         $('#example1').DataTable({
            fixedHeader     : true,
            responsive      : false,
            ordering        : false,
            scrollCollapse  : false,
            paging          : false,
            searching       : false,
            dom             : 'Bfrtip',
            buttons         : [
                  'excelHtml5', 
                  {   
                     extend      : 'pdfHtml5',
                     orientation : 'landscape',
                     pageSize    : 'LEGAL'
                  },
                  {   
                     extend      : 'print',
                     orientation : 'landscape',
                     pageSize    : 'LEGAL'
                  }
            ]
         });
		});

		function tutup(){
			localStorage.removeItem('activeTab');
			self.parent.Shadowbox.close();
		}
	</script>
</head>
<body>
<button onclick="location.reload(true)">reload</button>
<div class="center-block" style="margin-top: 5px;">
   <div class="panel panel-info">
      <div class="panel-body">
        	<!-- Form Informasi Umum -->
         <?php
            $sql = "select a.keyNumber, a.tahun_data, a.keterangan, a.sumPotensi, a.sumSkor, a.valPersentasePetugas,
                  a.valPerilakuNapi, a.kabupatenkotaid, a.provinsiid,
                  b.kabupatenkotaname nm_kota, b.alamat, b.email, b.nama_kepala, b.no_telp_kepala, 
                  c.provinsiname nm_propinsi, a.is_confirm, a.is_verify, a.dokumen
                  from lapas_perencanaan_umum a, kabupatenkota b, provinsi c
                  where a.kabupatenkotaid = b.kabupatenkotaid and c.provinsiid = a.provinsiid and a.kd_perencanaan_umum = '".$_GET['id']."'";
            $exe = mysqli_query($connDB, $sql);
            writeLog(__LINE__, __FILE__, mysqli_error($connDB));
            $row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
            $keyNumber		= $row['keyNumber'];
            $kdPropinsi		= $row['provinsiid'];
            $kdKota			= $row['kabupatenkotaid'];
            $nmPropinsi 	= $row['nm_propinsi'];
            $nmKota  		= $row['nm_kota'];
            $alamat  		= $row['alamat'];
            $email  			= $row['email'];
            $nama_kepala  	= $row['nama_kepala'];
            $no_telp_kepala= $row['no_telp_kepala'];
            $tahun_data 	= substr($row['tahun_data'],0,4);
            $bulan_data 	= substr($row['tahun_data'],-1,2);
            $keterangan 	= nl2br($row['keterangan']);
            $dokumen  		= $row['dokumen'];

            $sumPotensi 				= $row['sumPotensi'];
            $sumSkor 					= $row['sumSkor'];
            $valPersentasePetugas 	= $row['valPersentasePetugas'];
            $valPerilakuNapi 			= $row['valPerilakuNapi'];
            
            $isConfirm 		= $row['is_confirm'];
            $isVerify 		= $row['is_verify'];

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
         ?>

         <div id="konten">
            <div class="space"></div>
            <div class="col-md-12">
               <h5><span class="glyphicon glyphicon-triangle-right small" aria-hidden="true"></span> Instrument Deteksi Dini Potensi Gangguan Keamanan dan Ketertiban</h5>
               <table class="table table-hover table-condensed">
                  <tbody>
                     <tr>
                        <td width="15%"><label class="control-label" for="thnKegiatan">Periode Data</label></td>
                        <td><b>:</b>&nbsp;<?=getBulan($bulan_data)." ".$tahun_data?></td>
                     </tr>
                     <tr>
                        <td><label class="control-label" for="namaPropinsi">Wilayah</label></td>
                        <td><b>:</b>&nbsp;<?=$nmPropinsi?></td>
                     </tr> 
                     <tr>
                        <td><label class="control-label" for="namaKota">UPT Pemasyarakatan</label></td>
                        <td><b>:</b>&nbsp;<?=$nmKota?></td>
                     </tr> 
                     <tr>
                        <td><label class="control-label" for="nmKepala">Nama Kepala UPT</label></td>
                        <td><b>:</b>&nbsp;<?=$nama_kepala?></td>
                     </tr>
                     <tr>
                        <td><label class="control-label" for="alamat">Alamat</label></td>
                        <td><b>:</b>&nbsp;<?=$alamat?></td>
                     </tr>
                     <tr>
                        <td><label class="control-label" for="noTelp">No. HP/WA Kepala UPT</label></td>
                        <td><b>:</b>&nbsp;<?=$no_telp_kepala?></td>
                     </tr>
                     <tr>
                        <td><label class="control-label" for="email">Email</label></td>
                        <td><b>:</b>&nbsp;<?=$email?></td>
                     </tr>
                     <tr>
                        <td><label class="control-label" for="keterangan">Keterangan</label></td>
                        <td><b>:</b>&nbsp;<?=$keterangan?></td>
                     </tr>
                  </tbody>	
               </table>
               <div class="space"></div>
            </div>
            <!-- Form Evaluasi -->
            <div class="space"></div>
            <div class="col-md-12">
               <h5><span class="glyphicon glyphicon-triangle-right small" aria-hidden="true"></span> Evaluasi pengurangan resiko  gangguan keamanan</h5>
               <div class="space"></div>
               <table class="table table-bordered" cellspacing="0" width="100%">
                  <tr>
                     <th width="3%" class="isCenter">No</th>
                     <th width="18%" class="isCenter">Elemen Assessment</th>
                     <th width="6%" class="isCenter">Nilai Bobot<br>Ideal</th>
                     <th width="6%" class="isCenter">Pemenuhan<br>(Total Skor)</th>
                     <th width="6%" class="isCenter">Nilai bobot<br>potensi ganguan keamanan</th>
                     <th width="15%" class="isCenter">Keterangan</th>
                  </tr>
                  <tbody>
                     <?php
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
                        echo '<tr valign="middle" class="bgGrey">'; 
                           echo '<td align="center"><b class="txtBlue">'.$romawi.'</b></td>';
                           echo '<td colspan="8"><b>'.strtoupper($row['indikator']).'</b></td>';
                        echo '</tr>';

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
                           echo '<tr valign="middle">'; 
                                 echo '<td align="center"><b>'.$i.'</b></td>';
                                 echo '<td>'.$roc['indikator'].'</td>';
                                 echo '<td align="center" class="isCenter">';
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
                                    echo '<span>'.$bobot[$i].'</span>';
                                 echo '</td>';
                                 echo '<td class="isCenter">';
                                    echo '<span>'.$totalSkorValDB[$i].'</span>';
                                 echo '</td>';
                                 echo '<td class="isCenter">';
                                    echo '<span>'.$skorPotensi[$i].'</span>';
                                 echo '</td>';
                                 echo '<td>';
                                    echo '<p>'.$ketValDB[$i].'</p>';
                                 echo '</td>';
                           echo '</tr>';
                           $totBobot += $bobot[$i];
                        }
                     }
                     ?>
                  </tbody>
                  <tr class="bgGrey">
                     <td colspan="2" align="left"><b>JUMLAH TOTAL</b></td>
                     <td align="center">
                        <b class="txtBlue"><?=$totBobot?></b>
                     </td>
                     <td align="center">
                        <b class="txtBlue"><?=intval($sumSkor)?></b>
                     </td>
                     <td align="center">
                        <b class="txtBlue"><?=intval($sumPotensi)?></b>
                     </td>
                     <td>&nbsp;</td>
                  </tr>
                  <tr class="bgGrey">
                     <td colspan="2" align="left"><b>PERSENTASE NILAI DIPEROLEH</b></td>
                     <td align="center">&nbsp;</td>
                     <td title="<?=$penilaianPetugas?>"align="center" style="background-color:<?=$bgColorPetugas?>; color:<?=$colorPetugas?>">
                        <b><?=number_format($valPersentasePetugas, 2,".",",")?> %</b>
                     </td>
                     <td title="<?=$penilaianNapi?>" align="center" style="background-color:<?=$bgColorNapi?>; color:<?=$colorNapi?>">
                        <b><?=number_format($valPerilakuNapi, 2,".",",")?> %</b>
                     </td>
                     <td>&nbsp;</td>
                  </tr>
               </table>
            </div>
         
            <!-- Hasil Penilaian -->
            <hr>
            <div class="col-md-12">
               <div class="row">
                  <div class="col-xs-6">
                     <h5 class="txtBlue"><span class="glyphicon glyphicon-triangle-right small" aria-hidden="true"></span> Kecenderungan Pemahaman Petugas terhadap Tupoksi Pemasyarakatan</h5>
                     <table class="table table-bordered" cellspacing="0">
                        <tr class="isCenter bgGrey">
                           <td><b>Kategori</b></td>
                           <td colspan="2"><b>Rentan Nilai (%)</b></td>
                        </tr>
                        <tbody>
                           <?php
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
                              echo '<tr style="background-color:'.$bgColor.'; color:'.$color.'">';
                                 echo '<td>'.$row['nmKategori'].'</td>';
                                 echo '<td align="right" width="12%">'.$row['nilaiBatasBawah'].'</td>';
                                 echo '<td align="right" width="12%">'.$row['nilaiBatasAtas'].'</td>';
                              echo '</tr>';
                           }
                           ?>
                        </tbody>
                     </table>
                  </div>
                  <div class="col-xs-6">
                     <h5 class="txtBlue"><span class="glyphicon glyphicon-triangle-right small" aria-hidden="true"></span> Kecenderungan Perilaku Warga Binaan Pemasyarakatan </h5>
                     <table class="table table-bordered" cellspacing="0">
                        <tr class="isCenter bgGrey">
                           <td><b>Kategori</b></td>
                           <td colspan="2"><b>Rentang Nilai (%)</b></td>
                        </tr>
                        <tbody>
                           <?php
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
                              echo '<tr style="background-color:'.$bgColor.'; color:'.$color.'">';
                                 echo '<td>'.$row['nmKategori'].'</td>';
                                 echo '<td align="right" width="12%">'.$row['nilaiBatasBawah'].'</td>';
                                 echo '<td align="right" width="12%">'.$row['nilaiBatasAtas'].'</td>';
                              echo '</tr>';
                           }
                           ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
            
            <!-- Dokumen -->
            <div class="col-md-12">
               <div class="space"></div>
               <table class="table table-hover table-condensed">
                  <tbody>
                     <tr>
                        <td width="15%"><label class="control-label" for="dokumen">File dokumen</label></td>
                        <td><b>:</b>&nbsp;<a href='<?=BASE_URL?>attachment/dokumen_keabsahan/<?=$kdPropinsi."/".$kdKota."/".$keyNumber."/".str_replace(" ","%20", $dokumen)?>' title="Dokumen Surat Pernyataan Keabsahan Data" alt="dokumen" target="_new"><?=$dokumen?></a></td>
                        </tr>
                  </tbody>
               </table>
            </div>
         </div>

         <div id="editor"></div>
         <button id="konvert">Generate PDF</button>
		</div>
	</div>
</div> 

<!-- Bootstrap Script -->
<script src="<?=BASE_URL?>assets/bootstrap/js/bootstrap.min.js"></script>

<!-- Custom Onload Script -->
<script type="text/javascript" src="<?=BASE_URL?>assets/shadowbox/shadowbox.js"></script>
<script type="text/javascript" src="<?=BASE_URL?>assets/common/js/main.js"></script>

</body>
</html>