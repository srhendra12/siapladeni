<?php session_start(); error_reporting(0); ?>
<?php 
// if(empty($_SESSION['token']) || !isset($_SESSION['token'])) { echo "<script>window.location.href='http://'+ window.location.hostname +'/sig_drainase'</script>"; }
?>
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
            if(!empty($_GET['sektor'])){
                switch($_GET['sektor']){
                    case 1 : $nmSektor = "Drainase"; break;
                    case 2 : $nmSektor = "Air Limbah"; break;
                    case 3 : $nmSektor = "Persampahan"; break;
                }

                echo '<div class="form-inline">';
                     echo '<div class="form-group">';
                         echo '<span for="sektor" style="display: inline-block;">Klik <a href="#" onclick="popup(\'\',\''.BASE_URL.'script/php/form_penilaian_evaluasi.php?sektor='.$_GET['sektor'].'\',\'850\',\'470\');" title="Input Parameter Penilaian Pasca Konstruksi Sektor '.$nmSektor.'"><i class="fa fa-plus text-primary" aria-hidden="true"></i></a> untuk menambah Parameter Penilaian Pasca Konstruksi Sektor <b>'.$nmSektor.'</b>  </span>';
                         echo '</div>';
                echo '</div>';
                echo '<div class="space"></div>';
            }
            ?>

            <table id="example" class="table table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="isCenter" rowspan="2" width="4%">No</th>
                        <th class="isCenter" rowspan="2" width="20%">Kriteria</th>
                        <th class="isCenter" rowspan="2">Parameter</th>
                        <th class="isCenter" rowspan="2" width="10%">Nilai</th>
                        <th class="isCenter" colspan="2" width="14%">Rentan Nilai</th>
                        <th class="isCenter" rowspan="2" width="5%">is Active</th>
                        <th class="isCenter" rowspan="2" width="7%">Action</th>
                    </tr>
                    <tr>
                        <th class="isCenter">Batas Bawah</th>
                        <th class="isCenter">Batas Atas</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Load Data Parameter Penilaian Pasca Konstruksi
                $sqc = "select a.kd_penilaian, b.nm_kriteria, a.is_active
                        from p_penilaian_evaluasi a, p_kriteria_evaluasi b 
                        where a.kd_kriteria = b.kd_kriteria and a.kd_penilaian in (select c.kd_penilaian from p_param_penilaian_evaluasi c where a.kd_penilaian = c.kd_penilaian and c.kd_sektor = '".$_GET['sektor']."' group by c.kd_penilaian)
                        order by 1";        
                $exc = mysqli_query($connDB, $sqc);
                writeLog(__LINE__, __FILE__, mysqli_error($connDB));
                $i=0;
                while($roc = mysqli_fetch_array($exc, MYSQLI_ASSOC)){
                    $i++;
                    $kdSubKriteria  = $roc['kd_penilaian'];
                    $checked        = ($roc['is_active'] == 1) ? "checked" : "";
                    echo '<tr valign="middle">'; 
                        echo '<td align="center"><b>'.$i.'</b></td>';
                        echo '<td><b>'.$roc['nm_kriteria'].'</b></td>';
                        echo '<td>';
                                $qry = "select nm_parameter, nilai, nilaiBatasBawah, nilaiBatasAtas 
                                        from p_param_penilaian_evaluasi where kd_penilaian = ".$kdSubKriteria." and 
                                        kd_sektor = '".$_GET['sektor']."'
                                        order by nilai desc";
                                $run = mysqli_query($connDB, $qry);
                                $numRows = mysqli_num_rows($run);

                                if($numRows > 0){
                                    echo '<table class="table" cellspacing="0" width="100%">';
                                       $j=0;
                                       while($param = mysqli_fetch_array($run, MYSQLI_ASSOC)){
                                            $j++;
                                            $nilai[$j]              = $param['nilai'];
                                            $nilaiBatasBawah[$j]    = $param['nilaiBatasBawah'];
                                            $nilaiBatasAtas[$j]     = $param['nilaiBatasAtas'];
                                            echo '<tr>';
                                                echo '<td>'.$param['nm_parameter'].'</td>';
                                            echo '</tr>';
                                       }
                                    echo '</table>';
                                }
                        echo '</td>';
                        echo '<td>';
                                if($numRows > 0){
                                       echo '<table class="table" cellspacing="0" width="100%">';
                                       for($z=1;$z<=$j;$z++){
                                            echo '<tr>';
                                                echo '<td >'.$nilai[$z].'</td>';
                                            echo '</tr>';
                                       }
                                    echo '</table>';
                                }
                        echo '</td>';
                        echo '<td>';
                                if($numRows > 0){
                                       echo '<table class="table" cellspacing="0" width="100%">';
                                       for($z=1;$z<=$j;$z++){
                                            echo '<tr>';
                                                echo '<td >'.$nilaiBatasBawah[$z].'</td>';
                                            echo '</tr>';
                                       }
                                    echo '</table>';
                                }
                        echo '</td>';
                        echo '<td>';
                                if($numRows > 0){
                                       echo '<table class="table" cellspacing="0" width="100%">';
                                       for($z=1;$z<=$j;$z++){
                                            echo '<tr>';
                                                echo '<td >'.$nilaiBatasAtas[$z].'</td>';
                                            echo '</tr>';
                                       }
                                    echo '</table>';
                                }
                        echo '</td>';
                        echo '<td align="center"><input type="checkbox" value="'.$roc['is_active'].'" name="isActive" '.$checked.' class="aktif" alt="isActive" onclick="enableDisable(\''.$roc['kd_penilaian'].'\', \''.$roc['is_active'].'\', \'p_penilaian_evaluasi\', \'penilaian\', \'is_active\', \'list_penilaian_evaluasi\');"></td>';
                        echo '<td align="center">';
                           echo '<a href="#" class="edit" id="'.$roc['penilaian'].'" onclick="popup(\'\',\'script/php/form_penilaian_evaluasi.php?id='.$roc['kd_penilaian'].'&sektor='.$_GET['sektor'].'\',\'850\',\'470\');"><img src="'.BASE_URL.'assets/common/img/pencil.png" alt="edit" title="edit data"></a>&nbsp;';
                            echo '<a href="#" class="delete" id="'.$roc['kd_penilaian'].'" onclick="deleteData(\''.$roc['kd_penilaian'].'\', \'p_penilaian_evaluasi\', \'kd_penilaian\', \'list_penilaian_evaluasi\');"><img src="'.BASE_URL.'assets/common/img/delete.png" alt="delete" title="hapus data" width="20px" ></a>';
                        echo '</td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
	</div>
</div> 