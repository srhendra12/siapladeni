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
                         echo '<span for="sektor" style="display: inline-block;">Klik <a href="#" onclick="popup(\'\',\''.BASE_URL.'script/php/form_pelaksanaan_konstruksi.php?sektor='.$_GET['sektor'].'\',\'550\',\'280\');" title="Input Parameter Pelaksanaan '.$nmSektor.'"><i class="fa fa-plus text-primary" aria-hidden="true"></i></a> untuk Menambah Indikator Tahap Pelaksanaan Konstruksi <b>'.$nmSektor.'</b></span>';
                         echo '</div>';
                echo '</div>';
                echo '<div class="space"></div>';
                echo '<div class="space"></div>';
            }
            ?>
            <input type="hidden" id="destination" value="<?=$_GET['sektor']?>">
            <table id="example" class="table table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="3%" rowspan="2" style="text-align: center; vertical-align: middle;">No</th>
                        <th width="20%" rowspan="2" style="text-align: center; vertical-align: middle;">Indikator</th>
                        <th rowspan="2" style="text-align: center; vertical-align: middle;">Parameter</th>
                        <th width="10%" colspan="2" style="text-align: center; vertical-align: middle;">Nilai</th>
                        <th width="15%" rowspan="2" style="text-align: center; vertical-align: middle;">Catatan <br>Bukti Dokumen</th>
                        <th width="15%" rowspan="2" style="text-align: center; vertical-align: middle;">Keterangan</th>
                        <th width="5%" rowspan="2" style="text-align: center; vertical-align: middle;">is Active</th>
                        <th width="7%" rowspan="2" style="text-align: center; vertical-align: middle;">Action</th>
                    </tr>
                    <tr>
                        <th style="text-align: center;">Bobot</th>
                        <th style="text-align: center;">Harkat</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Load Data Pelaksanaan
                $sql = "select kd_pelaksanaan, indikator, bobot, is_active
                        from p_pelaksanaan_konstruksi where kd_sektor = '".$_GET['sektor']."'
                        order by 1";        
                $exe = mysqli_query($connDB, $sql);
                writeLog(__LINE__, __FILE__, mysqli_error($connDB));
                $x=0;
                while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
                    $x++;
                    $kdKriteria = $row['kd_pelaksanaan'];
                    $checked    = ($row['is_active'] == 1) ? "checked" : "";
                    $romawi     = romawi($x);
                    echo '<tr valign="middle" class="bgGrey">'; 
                        echo '<td align="center"><b class="txtBlue">'.$romawi.'</b></td>';
                        echo '<td><b>'.strtoupper($row['indikator']).'</b></td>';
                        echo '<td>&nbsp;</td>';
                        echo '<td align="center"><b>'.$row['bobot'].' %</b></td>';
                        echo '<td>&nbsp;</td>';
                        echo '<td>&nbsp;</td>';
                        echo '<td>&nbsp;</td>';
                        echo '<td align="center"><input type="checkbox" value="'.$row['is_active'].'" name="isActive" '.$checked.' class="aktif" alt="isActive" onclick="enableDisable(\''.$row['kd_pelaksanaan'].'_'.$_GET['sektor'].'\', \''.$row['is_active'].'\', \'p_pelaksanaan_konstruksi\', \'pelaksanaan\', \'is_active\', \'list_pelaksanaan_konstruksi\');"></td>';
                        echo '<td align="center">';
                            echo '<a href="#" class="addsub" onclick="popup(\'\',\'script/php/form_subpelaksanaan_konstruksi.php?sektor='.$_GET['sektor'].'&kdPelaksaaan='.$row['kd_pelaksanaan'].'\',\'700\',\'470\');"><img src="'.BASE_URL.'assets/common/img/doc-add.png" alt="addsub" title="Tambah Data Sub Pelaksanaan" width="18px"></a>&nbsp;';
                            echo '<a href="#" class="edit" id="'.$row['kd_pelaksanaan'].'" onclick="popup(\'\',\'script/php/form_pelaksanaan_konstruksi.php?id='.$row['kd_pelaksanaan'].'&sektor='.$_GET['sektor'].'\',\'550\',\'280\');"><img src="'.BASE_URL.'assets/common/img/pencil.png" alt="edit" title="edit data"></a>&nbsp;';
                            echo '<a href="#" class="delete" id="'.$row['kd_pelaksanaan'].'_'.$_GET['sektor'].'" onclick="deleteData(\''.$row['kd_pelaksanaan'].'\', \'p_pelaksanaan_konstruksi\', \'kd_pelaksanaan\', \'list_pelaksanaan_konstruksi\');"><img src="'.BASE_URL.'assets/common/img/delete.png" alt="delete" title="hapus data" width="20px" ></a>';
                        echo '</td>';
                    echo '</tr>';

                    // Load Data Sub Pelaksanaan
                    $sqc = "select a.kd_sub_pelaksanaan, a.indikator, a.keterangan_dokumen, a.keterangan, a.is_active
                            from p_sub_pelaksanaan_konstruksi a, p_pelaksanaan_konstruksi b
                            where a.kd_pelaksanaan = b.kd_pelaksanaan and a.kd_pelaksanaan = '".$kdKriteria."' 
                            order by 1";        
                    $exc = mysqli_query($connDB, $sqc);
                    writeLog(__LINE__, __FILE__, mysqli_error($connDB));
                    $i=0;
                    while($roc = mysqli_fetch_array($exc, MYSQLI_ASSOC)){
                        $i++;
                        $kdSubKriteria  = $roc['kd_sub_pelaksanaan'];
                        $checked        = ($roc['is_active'] == 1) ? "checked" : "";
                        echo '<tr valign="middle">'; 
                            echo '<td align="center">'.$i.'</td>';
                            echo '<td>'.$roc['indikator'].'</td>';
                            echo '<td>';
                                    $qry = "select nm_parameter, harkat from p_param_sub_pelaksanaan where kd_sub_pelaksanaan = ".$kdSubKriteria."
                                            order by harkat desc";
                                    $run = mysqli_query($connDB, $qry);
                                    $numRows = mysqli_num_rows($run);

                                    if($numRows > 0){
                                        echo '<table class="table" cellspacing="0" width="100%">';
                                           $j=0;
                                           while($param = mysqli_fetch_array($run, MYSQLI_ASSOC)){
                                                $j++;
                                                $harkat[$j] = $param['harkat'];
                                                echo '<tr>';
                                                    echo '<td>'.$param['nm_parameter'].'</td>';
                                                echo '</tr>';
                                           }
                                        echo '</table>';
                                    }
                            echo '</td>';
                            echo '<td>&nbsp;</td>';
                            echo '<td>';
                                    if($numRows > 0){
                                           echo '<table class="table" cellspacing="0" width="100%">';
                                           for($z=1;$z<=$j;$z++){
                                                echo '<tr>';
                                                    echo '<td >'.$harkat[$z].'</td>';
                                                echo '</tr>';
                                           }
                                        echo '</table>';
                                    }
                            echo '</td>';
                            echo '<td>'.$roc['keterangan_dokumen'].'</td>';
                            echo '<td>'.$roc['keterangan'].'</td>';
                            echo '<td align="center"><input type="checkbox" value="'.$roc['is_active'].'" name="isActive" '.$checked.' class="aktif" alt="isActive" onclick="enableDisable(\''.$roc['kd_sub_pelaksanaan'].'_'.$_GET['sektor'].'\', \''.$roc['is_active'].'\', \'p_sub_pelaksanaan_konstruksi\', \'sub_pelaksanaan\', \'is_active\', \'list_pelaksanaan_konstruksi\');"></td>';
                            echo '<td align="center">';
                               echo '<a href="#" class="edit" id="'.$roc['kd_sub_pelaksanaan'].'" onclick="popup(\'\',\'script/php/form_subpelaksanaan_konstruksi.php?id='.$roc['kd_sub_pelaksanaan'].'&sektor='.$_GET['sektor'].'\',\'700\',\'470\');"><img src="'.BASE_URL.'assets/common/img/pencil.png" alt="edit" title="edit data"></a>&nbsp;';
                                echo '<a href="#" class="delete" id="'.$roc['kd_sub_pelaksanaan'].'_'.$_GET['sektor'].'" onclick="deleteData(\''.$roc['kd_sub_pelaksanaan'].'\', \'p_sub_pelaksanaan_konstruksi\', \'kd_sub_pelaksanaan\', \'list_pelaksanaan_konstruksi\');"><img src="'.BASE_URL.'assets/common/img/delete.png" alt="delete" title="hapus data" width="20px" ></a>';
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