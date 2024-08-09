<?php session_start(); error_reporting(0); ?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
   $('#example1').DataTable({
      responsive      : true,
      ordering        : false,
      scrollCollapse  : true,
      paging          : true,
      searching       : false,
      dom             : 'Bfrtip',
      buttons         : [
         {
            extend: 'excelHtml5',
            exportOptions: {
               columns: [ 0, 1, 2, 3, 4, 5, 6]
            },
            title: 'Rekapitulasi Penilaian Deteksi Dini Potensi Gangguan Keamanan dan Ketertiban Periode <?php echo !empty($_GET['tahunData']) ? $_GET['tahunData'] : date('Ym')?>'
         },
         {   
            extend      : 'pdfHtml5',
            orientation : 'landscape',
            pageSize    : 'LEGAL',
            exportOptions: {
               columns: [ 0, 1, 2, 3, 4, 5, 6]
            },
            title: 'Rekapitulasi Penilaian Deteksi Dini Potensi Gangguan Keamanan dan Ketertiban Periode <?php echo !empty($_GET['tahunData']) ? $_GET['tahunData'] : date('Ym')?>'
         },
         {   
            extend      : 'print',
            orientation : 'landscape',
            pageSize    : 'LEGAL',
            exportOptions: {
               columns: [ 0, 1, 2, 3, 4, 5, 6]
            },
            title: 'Rekapitulasi Penilaian Deteksi Dini Potensi Gangguan Keamanan dan Ketertiban Periode <?php echo !empty($_GET['tahunData']) ? $_GET['tahunData'] : date('Ym')?>',
         }
      ]
   });

});
</script>
<div class="center-block">
    <div class="panel">
        <div class="panel-body" style="margin-top: 5px;">
            <div class="space"></div>
            <table id="example1" class="table table-striped table-bordered" cellpadding="0" cellspacing="0" width="100%" >
                <thead>
                    <tr>
                        <th rowspan="2" class="isCenter" width="3%">No</th>
                        <th rowspan="2" class="isCenter" width="15%">Wilayah</th>
                        <th rowspan="2" class="isCenter" width="15%">UPT Pemasyarakatan</th>
                        <th colspan="2" class="isCenter">Persentasi Penilaian</th>
                        <th colspan="2" class="isCenter">Hasil Penilaian</th>
                    </tr>
                    <tr>
                        <th class="isCenter" width="7%">Total Skor<br>(%)</th>
                        <th class="isCenter" width="8%">Nilai bobot<br>potensi ganguan keamanan<br>(%)</th>
                        <th class="isCenter" width="18%">Kecenderungan Pemahaman Petugas<br>terhadap Tupoksi Pemasyarakatan</th>
                        <th class="isCenter" width="18%">Kecenderungan Perilaku<br>Warga Binaan Pemasyarakatan</th>
                    </tr>
                </thead>
                <tbody>
                <?php

                $where = (!empty($_GET['tahunData'])) ? " where a.tahun_data = '".$_GET['tahunData']."'" : " where a.tahun_data = '".date('Ym')."'";
                $sql = "select a.kd_perencanaan_umum, a.provinsiid, a.kabupatenkotaid, a.tahun_data, 
                        a.valPersentasePetugas, a.valPerilakuNapi, a.keyNumber, a.is_verify, a.is_confirm, a.is_close, a.is_rejected, a.is_print,
                        (select c.kabupatenkotaname from kabupatenkota c where c.kabupatenkotaid = a.kabupatenkotaid and 
                        c.r_provinsiid = a.provinsiid) uptPemasyarakatan,
                        (select d.provinsiname from provinsi d where d.provinsiid = a.provinsiid) wilayah
                        from lapas_perencanaan_umum a ".$where."
                        order by 1";
                $exe = mysqli_query($connDB, $sql);
                writeLog(__LINE__, __FILE__, mysqli_error($connDB));
                $x=0;
                while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
                    $x++;
                    echo '<tr valign="middle" id="'.$row['kd_perencanaan_umum'].'" style="cursor:pointer;">';  
                        echo '<td align="center"><b>'.$x.'</b></td>';
                        echo '<td class="detail">'.$row['wilayah'].'</td>'; 
                        echo '<td class="detail">'.$row['uptPemasyarakatan'].'</td>';
                        echo '<td class="detail" align="right"><b class="txtBlue">'.number_format($row['valPersentasePetugas'],2,',','.').'</b>&nbsp;</td>';
                        echo '<td class="detail" align="right"><b class="txtBlue">'.number_format($row['valPerilakuNapi'],2,',','.').'</b>&nbsp;</td>';
                        $qry = "select kd_kategori, nmKategori from p_range_tahap_perencanaan 
                                where '".$row['valPersentasePetugas']."' 
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
                        echo '<td class="detail" style="background-color:'.$bgColorPetugas.'; color:'.$colorPetugas.'"><b>'.$penilaianPetugas.'</b>&nbsp;</td>';

                        $qry = "select kd_kategori, nmKategori from p_range_perilaku_napi 
                                where '".$row['valPerilakuNapi']."' 
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
                        echo '<td class="detail" style="background-color:'.$bgColorNapi.'; color:'.$colorNapi.'"><b>'.$penilaianNapi.'</b>&nbsp;</td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
	</div>
</div> 