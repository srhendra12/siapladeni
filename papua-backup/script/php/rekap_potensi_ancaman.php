<?php session_start(); error_reporting(0); ?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
   $('#example1').DataTable({
      responsive      : true,
      ordering        : false,
      scrollCollapse  : false,
      paging          : false,
      searching       : false,
      dom             : 'Bfrtip',
      buttons         : [
         {
            extend: 'excelHtml5',
            exportOptions: {
               columns: [ 0, 1, 2, 3, 4, 5, 6]
            },
            title: 'Rekapitulasi Penilaian Potensi Ancaman Periode <?php echo !empty($_GET['tahunData']) ? $_GET['tahunData'] : date('Ym')?>'
         },
         {   
            extend      : 'pdfHtml5',
            orientation : 'landscape',
            pageSize    : 'LEGAL',
            exportOptions: {
               columns: [ 0, 1, 2, 3, 4, 5, 6]
            },
            title: 'Rekapitulasi Penilaian Potensi Ancaman Periode <?php echo !empty($_GET['tahunData']) ? $_GET['tahunData'] : date('Ym')?>'
         },
        //  {   
        //     extend      : 'print',
        //     orientation : 'landscape',
        //     pageSize    : 'LEGAL',
        //     exportOptions: {
        //        columns: [ 0, 1, 2, 3, 4, 5, 6]
        //     },
        //     title: 'Rekapitulasi Penilaian Potensi Ancaman Periode <?php echo !empty($_GET['tahunData']) ? $_GET['tahunData'] : date('Ym')?>'
        //  }
      ]
   });

    $('#print').click(function(){ 
        var periode = $('#periode').val();
        window.open('<?=BASE_URL?>script/php/printRekapPotensiGangguan.php?periode='+ periode, '_blank');
    });

});
</script>
<?php
$sql = "select kd_tahap_perencanaan, singkatan, is_active
from p_tahap_perencanaan where is_active = '1'
order by 1";        
$exe = mysqli_query($connDB, $sql);
writeLog(__LINE__, __FILE__, mysqli_error($connDB));
$x=0;
$jmlCol = mysqli_num_rows($exe);
?>

<div class="center-block">
    <div class="panel">
        <div class="panel-body" style="margin-top: 5px;">
            <button class="btn btn-sm btn-primary" id="print" style="margin-bottom:5px;"><i class="glyphicon glyphicon-print"></i> Cetak</button>
            <input type="hidden" id="periode" value="<?php echo (!empty($_GET['tahunData'])) ? $_GET['tahunData'] : date('Ym') ?>">
            <table id="example1" class="table table-striped table-bordered" cellpadding="0" cellspacing="0" width="100%" >
               <thead>
                  <tr>
                     <th rowspan="2" class="isCenter" width="3%">NO</th>
                     <th rowspan="2" class="isCenter" width="20%">NAMA UPT</th>
                     <th colspan="<?=$jmlCol?>" class="isCenter">POTENSI ANCAMAN</th>
                     <th rowspan="2" class="isCenter" width="10%">TOTAL SKOR</th>
                     <th rowspan="2" class="isCenter" width="15%">KETERANGAN</th>
                  </tr>
                  <tr>
                     <?php
                     $kdPerencanaan = array();
                     while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
                        $kdPerencanaan[$x] = $row['kd_tahap_perencanaan'];
                        echo '<th class="isCenter" width="10%">'.$row['singkatan'].'</th>';
                        $x++;
                     }
                     ?>
                  </tr>
               </thead>
               <tbody>
               <?php

					$where = (!empty($_GET['tahunData'])) ? " where lpu.tahun_data = '".$_GET['tahunData']."'" : " where lpu.tahun_data = '".date('Ym')."'";
					$where .= ($_SESSION['access'] < 4) ? "and p.kd_wilayah = ".$_SESSION['wilayah'] : "";
					$sql = "select lpu.kd_perencanaan_umum, lpu.provinsiid, lpu.kabupatenkotaid, lpu.tahun_data, lpu.keyNumber, lpu.keterangan,lpu.valPerilakuNapi, k.kabupatenkotaname uptPemasyarakatan 
					from lapas_perencanaan_umum lpu left join provinsi p on p.provinsiid = lpu.provinsiid left join kabupatenkota k on k.kabupatenkotaid = lpu.kabupatenkotaid and k.r_provinsiid = p.provinsiid 
					".$where." order by 1";
               $exe = mysqli_query($connDB, $sql);
               writeLog(__LINE__, __FILE__, mysqli_error($connDB));
               $x=0;
               $totalSKor = array();
               while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
                  $x++;
                  $valPerilakuNapi = $row['valPerilakuNapi'];
                  echo '<tr valign="middle" id="'.$row['kd_perencanaan_umum'].'">';  
                     echo '<td align="center"><b>'.$x.'</b></td>';
                     echo '<td class="detail">'.$row['uptPemasyarakatan'].'</td>';
                     for($i=0;$i<count($kdPerencanaan);$i++){
                        $qry = "select sum(totalSkor) totalSkor from lapas_perencanaan_penilaian_evaluasi where kd_tahap_perencanaan = '".$kdPerencanaan[$i]."' and keyNumber = '".$row['keyNumber']."'";        
                        $run = mysqli_query($connDB, $qry);
                        writeLog(__LINE__, __FILE__, mysqli_error($connDB));
                        $data = mysqli_fetch_array($run, MYSQLI_ASSOC);
                        echo '<td class="detail" align="right"><b class="txtBlue">'.number_format($data['totalSkor'],0,',','.').'</b>&nbsp;</td>';
                        $totalSKor[$x] += $data['totalSkor'];
                     }
                     echo '<td class="detail" align="right"><b class="txtBlue">'.number_format($totalSKor[$x],0,',','.').'</b>&nbsp;</td>';
                     $sqx = "select kd_kategori, nmKategori from p_range_perilaku_napi where '".$valPerilakuNapi."' 
                              between nilaiBatasBawah and nilaiBatasAtas";
                     $exec = mysqli_query($connDB, $sqx);
                     writeLog(__LINE__, __FILE__, mysqli_error($connDB));
                     $rows = mysqli_fetch_array($exec, MYSQLI_ASSOC);
                     $penilaianNapi = $rows['nmKategori'];
                     echo '<td class="detail">'.$penilaianNapi.'</td>';
                  echo '</tr>';
               }
               ?>
               </tbody>
            </table>
        </div>
	</div>
</div> 