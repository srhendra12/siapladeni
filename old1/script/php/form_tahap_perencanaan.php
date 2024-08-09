<?php session_start(); error_reporting(0); ?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7]> <html xmlns="http://www.w3.org/1999/xhtml" class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html xmlns="http://www.w3.org/1999/xhtml" class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html xmlns="http://www.w3.org/1999/xhtml" class="lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html xmlns="http://www.w3.org/1999/xhtml"> <!--<![endif]-->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="description" content="">
    <meta name="author" content="">
    
    <!-- Bootstrap core CSS -->
    <link href="<?=BASE_URL?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="<?=BASE_URL?>assets/common/css/main.css" />
    
    <!-- jQuery v1.11.3 -->
    <script src="<?=BASE_URL?>assets/common/js/jquery.min.js"></script>
    
    <!-- Bootstrap Script -->
    <script src="<?=BASE_URL?>assets/bootstrap/js/bootstrap.min.js"></script>
   	<script src="<?=BASE_URL?>assets/bootstrap/extend/bootstrap-modal/bootstrap-modal.js"></script>
	<script src="<?=BASE_URL?>assets/bootstrap/extend/bootbox/bootbox.min.js"></script>
	
	<!-- Custom Onload Script -->
    <script type="text/javascript" src="<?=BASE_URL?>assets/shadowbox/shadowbox.js"></script>
    <script type="text/javascript" src="<?=BASE_URL?>assets/common/js/main.js"></script>

	<script type="text/javascript">
    $(document).ready(function(){
		
		/* ----------------- Save Data -------------------- */
		$("#form").submit(function() {
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
                            tutup();
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
			return false;  
		});
	});
	
	function tutup(){
		self.parent.ajaxloading('divList');
		self.parent.$('#divList').load('<?=BASE_URL?>script/php/list_tahap_perencanaan.php');
		self.parent.Shadowbox.close();
	}
    </script>
</head>
<body>
<?php
if(!empty($_GET['id']) || $_GET['id'] != ''){
	$sql = "select indikator, is_active from p_tahap_perencanaan
			where kd_tahap_perencanaan = ".$_GET['id'];
	$exe = mysqli_query($connDB, $sql);
	$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
	writeLog(__LINE__, __FILE__, mysqli_error($connDB));
	$indikator 	= $row['indikator'];
	$checked 	= ($row['is_active'] == 1) ? "checked" : "";
}
else{
	$checked 	= "checked";
}
?>
<!-- <button onclick="location.reload(true)">reload</button> -->
<form id="form" name="form" method="post" action="<?=BASE_URL?>include/proses.php" autocomplete="off">
<input type="hidden" id="action" name="action" value="add_tahap_perencanaan">
<input type="hidden" id="isEdit" name="isEdit" value="<?=$_GET['id']?>">
<div class="container-fluid" style="margin-top: 4px;">
    <div class="row">
        <div class="col-md-6 col-md-offset-4 col-center">
            <div class="panel panel-info" style="margin-bottom: 0px;">
                <div class="panel-heading">
                  <b class="panel-title">Tambah Elemen Assessment Instrument Deteksi Dini</b>
                </div>
                <div class="panel-body">
					<table class="table table-striped table-condensed">
                    	<tbody>
                    		<tr>
                    			<td width="30%"><label class="control-label" for="indikator">Elemen Assessment</label></td>
                				<td><textarea class="form-control input-sm" rows="2" id="indikator" name="indikator"><?=$indikator?></textarea></td>
                    		</tr>
                    		<tr>
				            	<td><label class="control-label" for="isActive">Is Active</label></td>
				                <td><input class="input-status" type="checkbox" id="isActive" name="isActive" value="1" <?=$checked?> ></td>
				            </tr>
				            <tr>
				            	<td>&nbsp;</td>
				                <td><button type="reset" id="reset" class="btn btn-default" onclick="tutup();" >Batal</button>&nbsp;<button type="submit" class="btn btn-primary" id="submit" name="simpan" >Simpan</button></td>
				            </tr>
                    	</tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>    
</form>
</body>
</html>
