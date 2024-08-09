<?php session_start(); error_reporting(0); ?>
<?php 
// if(empty($_SESSION['token']) || !isset($_SESSION['token'])) { echo "<script>window.location.href='http://'+ window.location.hostname +'/sig_drainase'</script>"; }
?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
    $('#example').DataTable({
        responsive  : true,
        fixedHeader : true,
        ordering    : false
    });

    $('#example tbody').on('click', 'tr td.detail', function () {
        var kdPerencanaan = $(this).closest('tr').attr('id');
        popup('','<?=BASE_URL?>script/php/detailOutputPerencanaan.php?id='+ kdPerencanaan,0,0);
    });

    $('#example tbody').on('click', '.edit', function () { 
        var param = $(this).attr('id');  
        window.location.href='index.php?fl=input_perencanaan&id='+ param;
    });

    $('#example tbody').on('click', '.isRejected', function () {
        var kdPerencanaan = $(this).attr('id');
        var prop 			= $("#prop").val();   
        var kota 			= $("#kota").val();   
        var tahunData 	    = $("#tahun_data").val();
        var isDashboard     = $("#isDashboard").val(); 
 
        function BootboxContent() {
            var frm_str = '<div class="row">  ' +
                    '<div class="col-md-12"> ' +
                    '<form class="form-horizontal"> ' +
                        '<label class="control-label" for="name">Keterangan Data Tidak Dapat Diverifikasi :</label> ' +
                        '<div class="space"></div>' +
                        '<textarea id="keterangan" name="keterangan" type="text" class="form-control input-md" rows="5"></textarea>' +
                    '</div> ' +
                    '</div> </div>' +
                    '</form> </div> </div>';

            var object = $('<div/>').html(frm_str).contents();
            return object
        }

        bootbox.dialog({
                title   : "Data Tidak Dapat Diverifikasi",
                message : BootboxContent,
                buttons: {
                    success: {
                        label: "Simpan",
                        className: "btn-primary",
                        callback    : function () { 
                            var keterangan         = $('#keterangan').val();
                            $.ajax({  
                                type    : 'POST',
                                url     :  'include/proses.php',
                                dataType: "json",
                                data    : {'table' : 'lapas_perencanaan_umum', 'keterangan': keterangan, 'kdDataMonev' : kdPerencanaan, 'jenis' : 'perencanaan', 'action' : 'addKeteranganTolak'},
                                dataType: "json",
                                success : function(data) {
                                    if(data.error == false){
                                        var timeout = 2000; // 1 seconds
                                        var dialog = bootbox.dialog({
                                            message : '<p class="text-center">'+ data.message +'</p>',
                                            size    : "small",
                                            closeButton: false
                                        });
                                        setTimeout(function () {
                                            dialog.modal('hide');
                                            $('#divList').load('script/php/list_output_perencanaan.php?prop='+ prop +'&kota='+ kota +'&tahunData='+ tahunData +'&isDashboard='+ isDashboard )
                                        }, timeout);
                                    }
                                    else{
                                        bootbox.alert(data.message);
                                    }
                                },  
                                error : function() {  
                                    bootbox.alert("#error");  
                                }  
                            });                         
                        } 
                    },
                    cancel : {
                        label: "Batal",
                        className: "btn-default",
                        callback    : function () { 
                            $('.bootbox.modal').modal('hide');
                        }
                    }
                },
                onEscape: function () {
                    $('.bootbox.modal').modal('hide');
                }
            }
        );
    });

    $('#example tbody').on('click', '.showReason', function () {
        var keyNumber   = $(this).attr('id');
        $('.modal-body').load('include/proses.php?act=getShowReason&keyNumber='+ keyNumber +'&jenis=perencanaan',function(){
            $('#myModal').modal({show:true});
        });
    });

});
</script>

<input type="hidden" id="prop" value="<?=$_GET['prop']?>">
<input type="hidden" id="kota" value="<?=$_GET['kota']?>">
<input type="hidden" id="tahunData" value="<?=$_GET['tahunData']?>">
<input type="hidden" id="isDashboard" value="<?=$_GET['isDashboard']?>">

<div class="center-block">
    <div class="panel">
        <div class="panel-body">
            <div class="space"></div>
            <table id="example" class="table table-striped table-bordered" cellpadding="0" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="isCenter" width="3%">No</th>
                        <th class="isCenter" width="7%">Periode<br>Kegiatan</th>
                        <th class="isCenter" width="15%">Wilayah</th>
                        <th class="isCenter" width="15%">UPT Pemasyarakatan</th>
                        <th class="isCenter" width="7%">Total Skor</th>
                        <th class="isCenter" width="8%">Nilai bobot<br>potensi ganguan keamanan</th>
                        <?php
                        if(!empty($_SESSION['token']) && !empty($_SESSION['access'])) {
                            if($_SESSION['access'] != 3){
                                echo '<th class="isCenter" width="7%">Action</th>';
                                echo '<th class="isCenter" width="7%">Konfirmasi</th>';
                            }
                            if($_SESSION['access'] == 1 || $_SESSION['access'] == 3){
                                echo '<th class="isCenter" width="7%">Verifikasi</th>';
                                // echo '<th class="isCenter" width="7%">Closing</th>';
                                echo '<th class="isCenter" width="7%">Entry<br>Date</th>';
                                echo '<th class="isCenter" width="7%">Last Update<br>Date</th>';
                                echo '<th class="isCenter" width="7%">Confirm<br>Date</th>';
                                echo '<th class="isCenter" width="7%">Verify<br>Date</th>';
                                echo '<th class="isCenter" width="7%">Close<br>Date</th>';
                            }
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                <?php

                $where = (!empty($_GET['tahunData'])) ? " where a.tahun_data = '".$_GET['tahunData']."'" : " where a.tahun_data = '".date('Ym')."'";

               if (isset($_GET['kota']) and $_GET['kota'] != ''){
                    $where .= " and a.provinsiid = '".$_GET['prop']."' and a.kabupatenkotaid = '".$_GET['kota']."'";
                }
                else if (isset($_GET['prop']) and $_GET['prop'] != ''){
                    $where .= " and a.provinsiid = '".$_GET['prop']."'";
                }
                else{
                    $where .= ($_SESSION['access'] != 1) ? " and a.provinsiid = '".$_SESSION['propinsi']."'" : "";
                }

                $sql = "select a.kd_perencanaan_umum, a.provinsiid, a.kabupatenkotaid, a.tahun_data, a.sumPotensi, a.sumSkor, 
                        a.keyNumber, a.is_verify, a.is_confirm, a.is_close, a.is_rejected, a.is_print,
                        a.confirm_date, a.verify_date, a.close_date, a.entry_date, a.last_update_date,
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
                    $kelengkapanDok = ($row['tgl_kelengkapan_dok'] != '0000-00-00') ? '<span class="txtOrange"><b>'.$row['tgl_kelengkapan_dok'].'</b></span>' : "-";

                    $isActive       = ($row['is_verify'] == "1") ? '<span class="glyphicon glyphicon-remove"></span> Batalkan' : '<span class="glyphicon glyphicon-ok"></span> Verifikasi';
                    $colorActive    = ($row['is_verify'] == "1") ? "btn-danger" : "btn-success";

                    $isConfirm      = ($row['is_confirm'] == "1") ? '<span class="glyphicon glyphicon-remove"></span> Batalkan' : '<span class="glyphicon glyphicon-ok"></span> Konfirmasi';
                    $colorConfirm   = ($row['is_confirm'] == "1") ? "btn-danger" : "btn-success";

                    $isClose        = ($row['is_close'] == "1") ? '<span class="glyphicon glyphicon-remove"></span> Re-Open' : '<span class="glyphicon glyphicon-ok"></span> Close';
                    $colorClose     = ($row['is_close'] == "1") ? "btn-danger" : "btn-success";

                    echo '<tr valign="middle" id="'.$row['kd_perencanaan_umum'].'" style="cursor:pointer;">';  
                        echo '<td align="center"><b>'.$x.'</b></td>';
                        echo '<td class="detail" align="center">'.$row['tahun_data'].'</td>';
                        echo '<td class="detail">'.$row['wilayah'].'</td>'; 
                        echo '<td class="detail">'.$row['uptPemasyarakatan'].'</td>';
                        echo '<td class="detail" align="right"><b class="txtBlue">'.number_format($row['sumSkor'],2,',','.').'</b>&nbsp;</td>';
                        echo '<td class="detail" align="right"><b class="txtBlue">'.number_format($row['sumPotensi'],2,',','.').'</b>&nbsp;</td>';
                        if(!empty($_SESSION['token']) && !empty($_SESSION['access'])) {
                            if($_SESSION['access'] != 3){
                                echo '<td align="center">';
                                    if($row['is_verify'] == 0 && $row['is_confirm'] == 0){
                                        echo '<a href="#" class="edit" id="'.$row['kd_perencanaan_umum'].'"><img src="'.BASE_URL.'assets/common/img/pencil.png" alt="edit" title="edit data"></a>&nbsp;';
                                        echo '<a href="#" class="delete" id="'.$row['keyNumber'].'" onclick="deleteData(\''.$row['keyNumber'].'\', \'lapas_perencanaan_umum\', \'keyNumber\', \'list_output_perencanaan\', \''.$_GET['prop'].'#'.$_GET['kota'].'#'.$_GET['tahunData'].'#'.$_GET['isDashboard'].'\');"><img src="'.BASE_URL.'assets/common/img/delete.png" alt="delete" title="hapus data" width="20px" ></a>';
                                    }
                                    else{
                                        echo '-';
                                    }
                                echo '</td>';
                                echo '<td align="center">';
                                    if($row['is_verify'] == 0){
                                        echo '<button class="btn '.$colorConfirm.'" value="'.$row['is_confirm'].'" name="isConfirm" alt="isConfirm" onclick="enableDisable(\''.$row['kd_perencanaan_umum'].'_'.$row['provinsiid'].'_'.$row['kabupatenkotaid'].'_'.$row['tahun_data'].'\', \''.$row['is_confirm'].'\', \'lapas_perencanaan_umum\', \'perencanaan_umum\', \'is_confirm\', \'list_output_perencanaan\');">'.$isConfirm.'</button>';
                                        if($row['is_rejected'] == 1){
                                            echo '<br><i class="glyphicon glyphicon-comment fa-2x showReason" name="showReason" id="'.$row['kd_perencanaan_umum'].'_'.$row['keyNumber'].'" style="margin-top:2px;"></i>';
                                        }
                                    }
                                    else{
                                        echo '-';
                                    }
                                echo '</td>';
                            }
                            if($_SESSION['access'] == 1 || $_SESSION['access'] == 3){
                                echo '<td align="center">';
                                    if($row['is_confirm'] == 1 && $row['is_close'] == 0){
                                        echo '<button class="btn '.$colorActive.'" value="'.$row['is_verify'].'" name="isVerify" alt="isVerify" onclick="enableDisable(\''.$row['kd_perencanaan_umum'].'_'.$row['provinsiid'].'_'.$row['kabupatenkotaid'].'_'.$row['tahun_data'].'\', \''.$row['is_verify'].'\', \'lapas_perencanaan_umum\', \'perencanaan_umum\', \'is_verify\', \'list_output_perencanaan\');">'.$isActive.'</button>';
                                        if($row['is_verify'] == 0){
                                            echo '<br><button class="btn btn-danger isRejected" name="isRejected" id="'.$row['keyNumber'].'_'.$row['kd_perencanaan_umum'].'" style="margin-top:2px;">Tolak</button>';
                                        }
                                    }
                                    else{
                                        echo '-';
                                    }
                                echo '</td>';
                                // echo '<td align="center">';
                                //         if($row['is_print'] == 1 && $row['is_verify'] == 1){
                                //             echo '<button class="btn '.$colorClose.'" value="'.$row['is_close'].'" name="isClose" alt="isClose" onclick="enableDisable(\''.$row['kd_perencanaan_umum'].'_'.$row['provinsiid'].'_'.$row['kabupatenkotaid'].'_'.$row['tahun_data'].'\', \''.$row['is_close'].'\', \'lapas_perencanaan_umum\', \'perencanaan_umum\', \'is_close\', \'list_output_perencanaan\');">'.$isClose.'</button>';
                                //         }
                                //         else{
                                //             echo '-';
                                //         }
                                // echo '</td>';
                                echo '<td align="center">'.$row['entry_date'].'</td>';
                                echo '<td align="center">'.$row['last_update_date'].'</td>';
                                echo '<td align="center">'.$row['confirm_date'].'</td>';
                                echo '<td align="center">'.$row['verify_date'].'</td>';
                                echo '<td align="center">'.$row['close_date'].'</td>';
                            }
                        }
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
	</div>
</div> 

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">History Keterangan Data Tidak Dapat Diverifikasi :</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>