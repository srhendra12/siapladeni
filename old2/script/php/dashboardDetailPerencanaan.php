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
        searching       : true,
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

    $('#back').click(function(){ 
        ajaxloading('divTableSummary');
        $('#divTableSummary').load('<?=BASE_URL?>script/php/dasboardTableSummary.php');
    });

    $('#example1 tbody').on('click', 'tr td.detail', function () {
        var kdPerencanaan = $(this).closest('tr').attr('id');
        popup('','<?=BASE_URL?>script/php/detailLaporanPerencanaan.php?id='+ kdPerencanaan,'800',0);
    });
});
</script>
<div class="center-block">
    <div class="panel" style="margin-top: 5px;">
        <div style="padding-bottom:5px; float:right; margin:0px 20px 0 0; cursor:pointer;">
            <img id="back" alt="back" title="Kembali ke Halaman Dashboard" src="<?=BASE_URL?>assets/common/img/back.png" >
        </div>
        <div class="panel-body">
            <?php
            switch($_GET['sektor']){
                case '1'    : $txtSektor = 'Drainase'; break;
                case '2'    : $txtSektor = 'Air Limbah'; break;
                case '3'    : $txtSektor = 'TPA'; break;
                default     : $txtSektor = 'Semua Sektor'; break;
            }

            if($_GET['is_confirm'] == 1 && $_GET['is_verify'] == 0 && $_GET['is_close'] == 0 && $_GET['is_print'] == 0){
                $txtStatus = "Dengan Status Data Konfirmasi";
            }
            elseif($_GET['is_confirm'] == 1 && $_GET['is_verify'] == 1 && $_GET['is_close'] == 0 && $_GET['is_print'] == 0){
                $txtStatus = "Dengan Status Data Terverifikasi";
            }
            elseif($_GET['is_confirm'] == 1 && $_GET['is_verify'] == 1 && $_GET['is_close'] == 1 && $_GET['is_print'] == 1){
                $txtStatus = "Dengan Status Data Tercetak";
            }

            $txtData = ($_GET['is_confirm'] == 0 && $_GET['is_verify'] == 0 && $_GET['is_close'] == 0 && $_GET['is_print'] == 0) ? "Keseluruhan Data" : "Data";
            
            ?>
            <h5 class="txtBlue"><span class="glyphicon glyphicon-triangle-right small" aria-hidden="true"></span> Rekapitulasi <span class="txtOrange"><?=$txtData?> Kesiapan Pelaksanaan</span> (<?=$txtSektor?>) Tahun <?=$_GET['tahun']?> <span class="txtOrange"><?=$txtStatus?></span></h5>
            <div class="space"></div>
            <table id="example1" class="table table-striped table-bordered" cellpadding="0" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th rowspan="2" class="isCenter" width="3%">No</th>
                        <th rowspan="2" class="isCenter" width="6%">Tahun<br>Kegiatan</th>
                        <th rowspan="2" class="isCenter" width="10%">Sektor</th>
                        <th rowspan="2" class="isCenter" width="15%">Propinsi</th>
                        <th rowspan="2" class="isCenter" width="15%">Kota / Kabupaten</th>
                        <th rowspan="2" class="isCenter">Nama Kegiatan</th>
                        <th rowspan="2" class="isCenter" width="5%">Pagu<br>(Rp. x 1000)</th>
                        <th colspan="2" class="isCenter" width="23%">Penilaian</th>
                    </tr>
                    <tr>
                        <th class="isCenter" width="8%">Skor</th>
                        <th class="isCenter" width="15%">Kategori</th>
                    </tr>
                </thead>
                <tbody>
                <?php

                $where = ($_GET['sektor'] != 0) ? " and a.kd_sektor = '".$_GET['sektor']."'" : "";
                $where .= ($_GET['idProp'] != 0) ? " and a.provinsiid = '".$_GET['idProp']."'" : "";

                if($_GET['is_confirm'] == 0 && $_GET['is_verify'] == 0 && $_GET['is_close'] == 0 && $_GET['is_print'] == 0){
                     $where .= "";
                }
                else{
                     $where .= "and a.is_confirm = '".$_GET['is_confirm']."' and 
                        a.is_verify = '".$_GET['is_verify']."' and a.is_close = '".$_GET['is_close']."' and 
                        a.is_print = '".$_GET['is_print']."' ";
                }
         
                $sql = "select a.kd_perencanaan_umum, a.nm_kegiatan, a.pagu, a.tahun_data, a.total_skor, a.keyNumber, a.is_verify, a.is_print,
                        (select b.nm_sektor from p_sektor b where a.kd_sektor = b.kd_sektor) nmSektor, a.kd_sektor,
                        (select c.kabupatenkotaname from kabupatenkota c where c.kabupatenkotaid = a.kabupatenkotaid and 
                        c.r_provinsiid = a.provinsiid) nm_kota,
                        (select d.provinsiname from provinsi d where d.provinsiid = a.provinsiid) nm_propinsi
                        from lapas_perencanaan_umum a 
                        where a.tahun_data = '".$_GET['tahun']."' ".$where." order by 1";
                $exe = mysqli_query($connDB, $sql);
                writeLog(__LINE__, __FILE__, mysqli_error($connDB));
                $x=0;
                while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
                    $x++;
                    echo '<tr valign="middle" id="'.$row['kd_perencanaan_umum'].'" style="cursor:pointer;">';  
                        echo '<td align="center"><b>'.$x.'</b></td>';
                        echo '<td align="center">'.$row['tahun_data'].'</td>';
                        echo '<td class="detail">'.$row['nmSektor'].'</td>';
                        echo '<td class="detail">'.$row['nm_propinsi'].'</td>';
                        echo '<td class="detail">'.$row['nm_kota'].'</td>';
                        echo '<td class="detail">'.$row['nm_kegiatan'].'</td>';
                        echo '<td class="detail" align="right">'.number_format($row['pagu'],2,',','.').'&nbsp;</td>';
                        echo '<td class="detail" align="right"><b class="txtBlue">'.number_format($row['total_skor'],2,',','.').'</b>&nbsp;</td>';
                        $qry = "select kd_kategori, nmKategori from p_range_tahap_perencanaan where '".$row['total_skor']."' 
                                between nilaiBatasBawah and nilaiBatasAtas and kd_sektor = '".$row['kd_sektor']."' ";
                        $run = mysqli_query($connDB, $qry);
                        writeLog(__LINE__, __FILE__, mysqli_error($connDB));
                        $data = mysqli_fetch_array($run, MYSQLI_ASSOC);
                        $penilaian  = $data['nmKategori'];
                        $getBgColor = getColorPenilaian($data['kd_kategori']);
                        switch($getBgColor){
                            case 1 : $bgColor = "#5AD3D1"; $color = "#000000"; break;
                            case 2 : $bgColor = "#FFC870"; $color = "#000000"; break;
                            case 3 : $bgColor = "#FF5A5E"; $color = "#ffffff"; break;
                        }
                        echo '<td class="detail" style="background-color:'.$bgColor.'; color:'.$color.'"><b>'.$penilaian.'</b>&nbsp;</td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
	</div>
</div> 