<?php session_start(); error_reporting(0); ?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<script type="text/javascript">
$(document).ready(function(){
	$('#reset').click(function(){ 
		$('#divForm').load('<?=BASE_URL?>script/php/form_link_terkait.php');
	});

   $("#form").submit(function() {
		var nmLink 		= $("#nmLink").val();
		var urlLink    = $("#urlLink").val();
		
		if(nmLink == ''){
			bootbox.alert('Maaf, Nama Link belum anda isi !!');	
		}
		else if(urlLink == ''){
			bootbox.alert('Maaf, Alamat URL Link belum anda pilih !!');	
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
							$('#divForm').load('script/php/form_link_terkait.php');
							$('#divList').load('script/php/list_link_terkait.php');
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
	$sql = "select nm_link, url_link, isActive from lapas_link_terkait
			   where kd_link = ".$_GET['id'];
	$exe = mysqli_query($connDB, $sql);
    writeLog(__LINE__, __FILE__, mysqli_error($connDB));
	$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
	$checked = ($row['isActive'] == 1) ? "checked" : "";
}
else{
    $checked    = "checked";
}
?>
<form id="form" name="form" method="post" action="<?=BASE_URL?>include/proses.php" autocomplete="off">
<input type="hidden" name="action" value="add_link">
<input type="hidden" name="isEdit" value="<?=$_GET['id'];?>">
<div class="center-block col-sm-5" style="padding-left:0px;">
    <div class="panel panel-info">
    	<div class="panel-heading" align="left">
          <b class="panel-title">Tambah Link Informasi Terkait</i></b>
        </div>
        <div class="panel-body">
            <table class="table table-striped">
                <tr>
                    <td width="30%"><label class="control-label" for="nmLink">Nama Link Informasi</label></td>
                    <td><input class="form-control" type="text" id="nmLink" name="nmLink" value="<?=$row['nm_link']?>"></td>
                </tr>
                <tr>
                    <td><label class="control-label" for="urlLink">URL Link Informasi</label></td>
                    <td><input class="form-control" type="text" id="urlLink" name="urlLink" value="<?=$row['url_link']?>"></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><label><input class="input-status" type="checkbox" name="isActive" id="isActive" value="1" <?=$checked?> >&nbsp;Is Active</label></td>
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
