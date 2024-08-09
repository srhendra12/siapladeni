<?php session_start(); error_reporting(0); ?>
<?php //if(empty($_SESSION['token']) || !isset($_SESSION['token'])) { echo "<script>window.location.href='http://'+ window.location.hostname +'/sig_drainase'</script>"; }?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<script type="text/javascript">
$(document).ready(function(){
	$('#reset').click(function(){ 
		$('#divForm').load('<?=BASE_URL?>script/php/formNilai.php');
	});
	
	$("#form").submit(function() {
      var nilai         = $("#nilai").val();
      var keterangan    = $("#keterangan").val();
		
		if(nilai == ''){
			bootbox.alert('Maaf, Parameter Nilai belum anda isi !!');	
		}
      else if(keterangan == ''){
			bootbox.alert('Maaf, Keterangan Nilai belum anda isi !!');	
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
							$('#divForm').load('<?=BASE_URL?>script/php/formNilai.php');
							$('#divList').load('<?=BASE_URL?>script/php/listNilai.php');
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
	$sql = "select nilai, keterangan from p_nilai where kdNilai  = ".$_GET['id'];
	$exe = mysqli_query($connDB, $sql);
    writeLog(__LINE__, __FILE__, mysqli_error($connDB));
	$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
   $nilaiIndikator      = $row['nilai'];
   $keterangan 	= $row['keterangan'];
}
?>
<form id="form" name="form" method="post" action="<?=BASE_URL?>include/proses.php" autocomplete="off">
<input type="hidden" name="action" value="input_nilai">
<input type="hidden" name="isEdit" value="<?=$_GET['id'];?>">
<div class="center-block col-sm-4" style="padding-left:0px;">
    <div class="panel panel-info">
    	<div class="panel-heading" align="left">
          <b class="panel-title">Input Parameter Nilai</b>
        </div>
        <div class="panel-body">
            <table class="table table-striped">
               <tr>
                  <td><label class="control-label" for="nilai">Nilai</label></td>
                  <td><input class="form-control input-sm" type="text" id="nilai" name="nilai" value="<?=$nilaiIndikator?>"></td>
               </tr>
               <tr>
                  <td><label class="control-label" for="keterangan">Keterangan</label></td>
                  <td><input class="form-control input-sm" type="text" id="keterangan" name="keterangan" value="<?=$keterangan?>"></td>
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