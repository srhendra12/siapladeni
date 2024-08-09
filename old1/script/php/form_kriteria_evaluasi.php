<?php session_start(); error_reporting(0); ?>
<?php // if(empty($_SESSION['token']) || !isset($_SESSION['token'])) { echo "<script>window.location.href='http://'+ window.location.hostname +'/sig_drainase'</script>"; }?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<script type="text/javascript">
$(document).ready(function(){
	$('#reset').click(function(){ 
		$('#divForm').load('<?=BASE_URL?>script/php/form_kriteria_evaluasi.php');
	});
	
	$("#form").submit(function() {
		var nmKriteria = $("#nmKriteria").val();	
		
		if(nmKriteria == ''){
			bootbox.alert('Maaf, Form input Nama Kriteria Evaluasi belum anda isi !!');	
		}
		else{	
			$.ajax({  
				type	: 'POST',
				url		: $(this).attr('action'),
				data	: $(this).serialize(),
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
			                ajaxloading('divList');
							$('#divForm').load('<?=BASE_URL?>script/php/form_kriteria_evaluasi.php');
							$('#divList').load('<?=BASE_URL?>script/php/list_kriteria_evaluasi.php');
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
		return false;  
	});

});
</script>
<?php
if(!empty($_GET['id']) || $_GET['id'] != ''){
	$sql = "select nm_kriteria from p_kriteria_evaluasi where kd_kriteria = ".$_GET['id'];
	$exe = mysqli_query($connDB, $sql);
	$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
	writeLog(__LINE__, __FILE__, mysqli_error($connDB));
	$nmKriteria = $row['nm_kriteria'];
	$isEdit 	= $_GET['id'];
}
?>
<form id="form" name="form" method="post" action="<?=BASE_URL?>include/proses.php" autocomplete="off">
<input type="hidden" name="action" value="input_kriteria_evaluasi">
<input type="hidden" name="isEdit" value="<?=$isEdit;?>">
<div class="center-block col-sm-4" style="padding-left:0px;">
    <div class="panel panel-info">
    	<div class="panel-heading" align="left">
          <b class="panel-title">Input Nama Kriteria Evaluasi</b>
        </div>
        <div class="panel-body">
		<table class="table table-striped">
        	<tr>
            	<td><label class="control-label" for="nmKriteria">Nama Kriteria Evaluasi</label></td>
                <td><input class="form-control" type="text" id="nmKriteria" name="nmKriteria" value="<?=$nmKriteria?>"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><button type="reset" id="reset" class="btn btn-default">Batal</button>&nbsp;<button type="submit" class="btn btn-primary">Simpan</button></td>
               	</tr>
        </table>
        </div>
	</div>
</div> 
</form>