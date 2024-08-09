<?php session_start(); error_reporting(0); ?>
<?php //if(empty($_SESSION['token']) || !isset($_SESSION['token'])) { echo "<script>window.location.href='http://'+ window.location.hostname +'/sig_drainase'</script>"; }?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<script type="text/javascript">
$(document).ready(function(){
	$('#reset').click(function(){ 
		$('#divForm').load('<?=BASE_URL?>script/php/form_param_kategoriPerencanaan.php');
	});
	
	$("#form").submit(function() {
		var inputKategori		= $("#nm_kategori").val();	
		if(inputKategori == ''){
			bootbox.alert('Maaf, Form input Nama Kategori belum anda isi !!');	
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
							$('#divForm').load('<?=BASE_URL?>script/php/form_param_kategoriPerencanaan.php');
							$('#divList').load('<?=BASE_URL?>script/php/list_param_kategoriPerencanaan.php');
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
	$sql = "select nm_kategori_perencanaan, is_active from p_kategori_perencanaan where kd_kategori = ".$_GET['id'];
	$exe = mysqli_query($connDB, $sql);
    writeLog(__LINE__, __FILE__, mysqli_error($connDB));
	$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
	$nmKategori 	= $row['nm_kategori_perencanaan'];
	$checked 	= ($row['is_active'] == 1) ? "checked" : "";
}
else{
	$checked 	= "checked";
}
?>
<form id="form" name="form" method="post" action="<?=BASE_URL?>include/proses.php" autocomplete="off">
<input type="hidden" name="action" value="input_kategoriPerencanaan">
<input type="hidden" name="isEdit" value="<?=$_GET['id'];?>">
<div class="center-block col-sm-4" style="padding-left:0px;">
    <div class="panel panel-info">
    	<div class="panel-heading" align="left">
          <b class="panel-title">Input Kategori Kesiapan Pelaksanaan</b>
        </div>
        <div class="panel-body">
		<table class="table table-striped">
        	<tr>
            	<td><label class="control-label" for="inputNama">Nama Kategori</label></td>
                <td><input class="form-control" type="text" id="nmKategori" name="nmKategori" value="<?=$nmKategori?>"></td>
            </tr>
            <tr>
            	<td><label class="control-label" for="isActive">Is Active</label></td>
                <td><input class="input-status" type="checkbox" id="is_active" name="is_active" value="1" <?=$checked?> ></td>
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