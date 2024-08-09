<?php session_start(); error_reporting(0); ?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
    $("#example").DataTable({
        responsive      : true,
        ordering        : false,
        scrollCollapse  : true,
        paging          : false
    });
});
</script>
<div class="center-block">
    <div class="panel">
        <div class="panel-body">
            <?php
            echo '<div class="form-inline">';
                    echo '<div class="form-group">';
                        echo '<span for="sektor" style="display: inline-block;">Klik <a href="#" onclick="popup(\'\',\''.BASE_URL.'script/php/form_tahap_perencanaan.php?sektor='.$_GET['sektor'].'\',\'550\',\'280\');" title="Input Elemen Assessment Instrument Deteksi Dini"><i class="fa fa-plus text-primary" aria-hidden="true"></i></a> untuk Menambah Elemen Assessment Instrument Deteksi Dini </span>';
                        echo '</div>';
            echo '</div>';
            echo '<div class="space"></div>';
            echo '<div class="space"></div>';
            ?>
            <input type="hidden" id="destination" value="<?=$_GET['sektor']?>">
            <table id="example" class="table table-bordered" cellspacing="0">
                <thead>
                    <tr>
                        <th width="3%" style="text-align: center; vertical-align: middle;">No</th>
                        <th width="20%" style="text-align: center; vertical-align: middle;">Elemen Assessment</th>
                        <th style="text-align: center; vertical-align: middle;">Parameter</th>
                        <th width="10%" style="text-align: center; vertical-align: middle;">Nilai Bobot Ideal</th>
                        <th width="5%" style="text-align: center; vertical-align: middle;">is Active</th>
                        <th width="7%" style="text-align: center; vertical-align: middle;">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Load Data Tahap Kesiapan Pelaksanaan
                $sql = "select kd_tahap_perencanaan, indikator, is_active
                        from p_tahap_perencanaan order by 1";        
                $exe = mysqli_query($connDB, $sql);
                writeLog(__LINE__, __FILE__, mysqli_error($connDB));
                $x=0;
                while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
                    $x++;
                    $kdKriteria = $row['kd_tahap_perencanaan'];
                    $checked    = ($row['is_active'] == 1) ? "checked" : "";
                    $romawi     = romawi($x);
                    echo '<tr valign="middle" class="bgGrey">'; 
                        echo '<td align="center"><b class="txtBlue">'.$romawi.'</b></td>';
                        echo '<td colspan="2"><b>'.strtoupper($row['indikator']).'</b></td>';
                        echo '<td style="display: none">&nbsp;</td>';
                        echo '<td>&nbsp;</td>';
                        echo '<td align="center"><input type="checkbox" value="'.$row['is_active'].'" name="isActive" '.$checked.' class="aktif" alt="isActive" onclick="enableDisable(\''.$row['kd_tahap_perencanaan'].'_'.$_GET['sektor'].'\', \''.$row['is_active'].'\', \'p_tahap_perencanaan\', \'tahap_perencanaan\', \'is_active\', \'list_tahap_perencanaan\');"></td>';
                        echo '<td align="center">';
                            echo '<a href="#" class="addsub" onclick="popup(\'\',\'script/php/form_subtahap_perencanaan.php?kdPelaksaaan='.$row['kd_tahap_perencanaan'].'&sektor='.$_GET['sektor'].'\',\'700\',\'470\');"><img src="'.BASE_URL.'assets/common/img/doc-add.png" alt="addsub" title="Tambah Data Sub Tahap Kesiapan Pelaksanaan" width="18px"></a>&nbsp;';
                            echo '<a href="#" class="edit" id="'.$row['kd_tahap_perencanaan'].'" onclick="popup(\'\',\'script/php/form_tahap_perencanaan.php?id='.$row['kd_tahap_perencanaan'].'&sektor='.$_GET['sektor'].'\',\'550\',\'280\');"><img src="'.BASE_URL.'assets/common/img/pencil.png" alt="edit" title="edit data"></a>&nbsp;';
                            echo '<a href="#" class="delete" id="'.$row['kd_tahap_perencanaan'].'_'.$_GET['sektor'].'" onclick="deleteData(\''.$row['kd_tahap_perencanaan'].'\', \'p_tahap_perencanaan\', \'kd_tahap_perencanaan\', \'list_tahap_perencanaan\');"><img src="'.BASE_URL.'assets/common/img/delete.png" alt="delete" title="hapus data" width="20px" ></a>';
                        echo '</td>';
                    echo '</tr>';

                    // Load Data Sub Tahap Kesiapan Pelaksanaan
                    $sqc = "select a.kd_sub_tahap_perencanaan, a.indikator, a.bobot, a.is_active
                            from p_sub_tahap_perencanaan a, p_tahap_perencanaan b
                            where a.kd_tahap_perencanaan = b.kd_tahap_perencanaan and a.kd_tahap_perencanaan = '".$kdKriteria."' 
                            order by 1";        
                    $exc = mysqli_query($connDB, $sqc);
                    writeLog(__LINE__, __FILE__, mysqli_error($connDB));
                    $i=0;
                    while($roc = mysqli_fetch_array($exc, MYSQLI_ASSOC)){
                        $i++;
                        $kdSubKriteria  = $roc['kd_sub_tahap_perencanaan'];
                        $checked        = ($roc['is_active'] == 1) ? "checked" : "";
                        echo '<tr valign="middle">'; 
                            echo '<td align="center">'.$i.'</td>';
                            echo '<td>'.$roc['indikator'].'</td>';
                            echo '<td>';
                                    $qry = "select kd_parameter, nm_parameter, isMandatory, deskripsi
                                            from p_param_sub_tahap_perencanaan where               
                                            kd_sub_tahap_perencanaan = ".$kdSubKriteria."
                                            order by kd_parameter";
                                    $run = mysqli_query($connDB, $qry);
                                    $numRows = mysqli_num_rows($run);

                                    if($numRows > 0){
                                        echo '<table class="table" cellspacing="0" width="100%">';
                                           $j=0;
                                           while($param = mysqli_fetch_array($run, MYSQLI_ASSOC)){
                                                $j++;
                                                $isMandatory = ($param['isMandatory'] == 1) ? "checked" : "";
                                                echo '<tr>';
                                                    $color = ($param['isMandatory'] == 1) ? "style='color:red'" : "";
                                                    echo '<td width="8%" align="center">'.$x.'.'.$i.'.'.$j.'</td>';
                                                    echo '<td '.$color.'>'.$param['nm_parameter'].'</td>';
                                                    echo '<td>'.$param['deskripsi'].'</td>';
                                                    echo '<td width="7%" align="center"><input type="checkbox" value="'.$param['isMandatory'].'" name="isActive" '.$isMandatory.' class="aktif" alt="isActive" onclick="enableDisable(\''.$param['kd_parameter'].'\', \''.$param['isMandatory'].'\', \'p_param_sub_tahap_perencanaan\', \'parameter\', \'isMandatory\', \'list_tahap_perencanaan\');"></td>';
                                                echo '</tr>';
                                           }
                                        echo '</table>';
                                    }
                            echo '</td>';
                            echo '<td align="center"><b>'.$roc['bobot'].'</b></td>';
                            echo '<td align="center"><input type="checkbox" value="'.$roc['is_active'].'" name="isActive" '.$checked.' class="aktif" alt="isActive" onclick="enableDisable(\''.$roc['kd_sub_tahap_perencanaan'].'_'.$_GET['sektor'].'\', \''.$roc['is_active'].'\', \'p_sub_tahap_perencanaan\', \'sub_tahap_perencanaan\', \'is_active\', \'list_tahap_perencanaan\');"></td>';
                            echo '<td align="center">';
                               echo '<a href="#" class="edit" id="'.$roc['kd_sub_tahap_perencanaan'].'" onclick="popup(\'\',\'script/php/form_subtahap_perencanaan.php?id='.$roc['kd_sub_tahap_perencanaan'].'&sektor='.$_GET['sektor'].'\',\'700\',\'470\');"><img src="'.BASE_URL.'assets/common/img/pencil.png" alt="edit" title="edit data"></a>&nbsp;';
                                echo '<a href="#" class="delete" id="'.$roc['kd_sub_tahap_perencanaan'].'_'.$_GET['sektor'].'" onclick="deleteData(\''.$roc['kd_sub_tahap_perencanaan'].'\', \'p_sub_tahap_perencanaan\', \'kd_sub_tahap_perencanaan\', \'list_tahap_perencanaan\');"><img src="'.BASE_URL.'assets/common/img/delete.png" alt="delete" title="hapus data" width="20px" ></a>';
                            echo '</td>';
                        echo '</tr>';
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
	</div>
</div> 