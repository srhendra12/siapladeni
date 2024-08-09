<?php session_start(); error_reporting(0); ?>
<?php if(empty($_SESSION['token']) || !isset($_SESSION['token'])) { echo "<script>window.location.href='http://'+ window.location.hostname +'/sig_drainase'</script>"; }?>
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
    
    <!-- jQuery v1.11.3 -->
    <script src="<?=BASE_URL?>assets/common/js/jquery.min.js"></script>
    
    <!-- Bootstrap Script -->
    <script src="<?=BASE_URL?>assets/bootstrap/js/bootstrap.min.js"></script>
   	<script src="<?=BASE_URL?>assets/bootstrap/extend/bootstrap-modal/bootstrap-modal.js"></script>
	<script src="<?=BASE_URL?>assets/bootstrap/extend/bootbox/bootbox.min.js"></script>
    
    <!-- Custom Onload Script -->
    <script type="text/javascript" src="<?=BASE_URL?>assets/shadowbox/shadowbox.js"></script>

	<script type="text/javascript">
    $(document).ready(function(){
		
		$("#submit").click(function() {
          	var newpass 	= $("#newpass").val();
			var renewpass 	= $("#renewpass").val();
			var userID 		= $("#userID").val();
	
			if(newpass == ''){
				bootbox.alert('Maaf, form input Password Baru belum anda isi !!');	
			}
			else if(renewpass == ''){
				bootbox.alert('Maaf, form input Tulis Ulang Password Baru belum anda isi !!');	
			}
			else if(newpass != renewpass){
				bootbox.alert('Maaf, Isian Password Baru dan Tulis Ulang Password Baru tidak sama, silahkan anda periksa kembali !!');	
			}
			else{
				$.ajax({  
					type	: 'POST',
					url		: '<?=BASE_URL?>include/proses.php',
					data	: {'userID' : userID, 'newpass' : newpass, 'action' : 'update_passwd'},
					dataType: 'json',
					success : function(data) {
						if(data.error == false){
							var timeout = 3000; // 3 seconds
	                        var dialog = bootbox.dialog({
	                            message: '<p class="text-center">'+ data.message +'</p>',
	                            closeButton: false
	                        });

	                        setTimeout(function () {
	                            dialog.modal('hide');
	                            // Should restart the system and re-login with new Password
								$.ajax({  
									url		: '<?=BASE_URL?>include/proses.php?act=signout',
									dataType: "json",
									success : function(data) {
										if(data.error == false){
											tutup();
										}
										else{
											bootbox.alert(data.message);
										}
									}
								});
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
    
    function tutup(){
    	self.parent.window.location = '<?=BASE_URL?>';
        self.parent.Shadowbox.close();
    }
    </script>
    
    <style type="text/css">
	*{
		margin:0;
		padding:0;
		font-family:Verdana, Geneva, sans-serif;
		font-size:11px;
		
	}
	</style>
</head>
<body style="margin-top:5px;">
<div class="center-block">
    <div class="panel panel-info" style="margin-bottom: 0px !important;">
        <div class="panel-heading">
          <b class="panel-title">Ubah Password</b>
        </div>
        <div class="panel-body">
        <input type="hidden" id="userID" value="<?=$_SESSION['kdUser']?>">
        <table class="table table-striped" style="margin-bottom: 10px !important;">
            <tr>
                <td width="40%"><label class="control-label" for="lblUsername">Username</label></td>
                <td><label class="control-label" for="username"><?=strtoupper($_SESSION['username'])?></label></td>
            </tr>
            <tr>
                <td><label class="control-label" for="newpass">Kata Sandi Baru</label></td>
                <td><input class="form-control" type="password" id="newpass" style="width:250px !important;"></td>
            </tr> 
			<tr>
                <td><label class="control-label" for="renewpass">Ulangi Kata Sandi Baru</label></td>
                <td><input class="form-control" type="password" id="renewpass" style="width:250px !important;"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><button type="reset" id="reset" class="btn btn-default" onclick="self.parent.Shadowbox.close();" >Tutup</button>&nbsp;<button type="button" class="btn btn-primary" id="submit">Simpan</button></td>
            </tr>
       	 </table>
         </div>
    </div>
</div>
</body>
</html>