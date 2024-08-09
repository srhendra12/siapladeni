<?php session_start(); error_reporting(0); ?>
<?php //if(empty($_SESSION['token']) || !isset($_SESSION['token'])) { echo "<script>window.location.href='http://'+ window.location.hostname +'/sig_drainase'</script>"; }?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<script type="text/javascript">
$(document).ready(function(){
	$('#reset').click(function(){ 
		$('#divForm').load('<?=BASE_URL?>script/php/form_daftar_user.php');
   });

   var kdProp = $('#kdPropinsi').val();
   var isEdit = $('#isEdit').val();
   
   if(isEdit !=""){
		var kdKota = $('#kdKotaTemp').val();
	   getKota('kdKota', kdProp, kdKota);
	}
   
   $("#userAccess").select2({
		placeholder: "Pilih Akses User..",
		allowClear: true
	});

   $('#email').blur(function(){
      var email = $('#email').val();
      checkDataEntry('email', email, 'submit');
   });
   
   $('#userid').blur(function(){
      var userid = $('#userid').val();
      checkDataEntry('userid', userid, 'submit');
   });

   $(".kdPropinsi").select2({
		placeholder: "Pilih Wilayah..",
      allowClear: true
   });
   
   $(".kdKota").select2({
		placeholder: "Pilih UPT Pemasyarakatan..",
      allowClear: true
	});
	
    $('.kdPropinsi').change(function(){ 
        var prop  		= $("#kdPropinsi").val();
        getKota('kdKota', prop, '');
    });

   /* ----------------- Check Re-Password -------------------- */
   $('#repassword').blur(function(){
      var passwd 		= $('#password').val();
      var rePasswd 	= $('#repassword').val();
      if(passwd != rePasswd){
         bootbox.alert('Maaf, Pasword yang anda masukan tidak sama !');
      }
   });
	
	$("#form").submit(function() {
      var userid = $("#userid").val();
      var username = $("#username").val();
      var email = $("#email").val();
		
		if(userid == ''){
			bootbox.alert('Maaf, User ID belum anda isi !!');	
      }
      else if(username == ''){
			bootbox.alert('Maaf, Nama User belum anda isi !!');	
      }
      else if(email == ''){
			bootbox.alert('Maaf, Alamat Email belum anda isi !!');	
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
							$('#divForm').load('<?=BASE_URL?>script/php/form_daftar_user.php');
							$('#divList').load('<?=BASE_URL?>script/php/list_daftar_user.php');
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
	$sql = "select kd_user, userid, username, email, kd_access, is_active, kd_propinsi, kd_kota
			from p_user_management
			where kd_user = ".$_GET['id'];
	$exe = mysqli_query($connDB, $sql);
    writeLog(__LINE__, __FILE__, mysqli_error($connDB));
   $row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
   
   $userid     = $row['userid'];
	$username	= $row['username'];
   $email 		= $row['email'];
   $kdProp 		= $row['kd_propinsi'];
   $kdKota 		= $row['kd_kota'];
	$userAccess	= $row['kd_access'];
   $checked 	= ($row['is_active'] == 1) ? "checked" : "";
   $txtPass 	= "";
   $isReadOnlyPass = "";
   $isReadOnlyUser = "readonly";
   $isEdit     = $_GET['id'];

}
else{
   $checked 	= "checked";
   $txtPass    = "lapasAmbon@2020";
   $isReadOnlyPass = "readonly";
   $isReadOnlyUser = "";
}
?>
<form id="form" name="form" method="post" action="<?=BASE_URL?>include/proses.php" autocomplete="off">
<input type="hidden" name="action" value="add_account">
<input type="hidden" name="isEdit" value="<?=$_GET['id'];?>">
<div class="center-block col-sm-5" style="padding-left:0px;">
    <div class="panel panel-info">
    	<div class="panel-heading" align="left">
          <b class="panel-title">Input User</b>
      </div>
      <div class="panel-body">
         <h4 class="txtOrange">Informasi Akses sistem</h4>
         <table class="table table-striped table-condensed">
            <tbody>
               <tr>
                  <td width="30%"><label class="control-label" for="userid">Nama Akun ID<span style="color:#F00;">*</span></label></td>
                  <td><input class="form-control input-sm" type="text" placeholder="User ID" id="userid" name="userid" value="<?=$userid?>" <?=$isReadOnlyUser?> ></td>
               </tr>
               <tr>
                  <td><label class="control-label" for="password">Kata Sandi<span style="color:#F00;">*</span></label></td>
                  <td><input class="form-control input-sm" type="password" placeholder="Password" id="password" name="password" value="<?=$txtPass?>" <?=$isReadOnlyPass?>  >
                  <?php
                  if(!empty($_SESSION['token']) && empty($_GET['id'])) {
                     echo '<small>Default Password : <em><b>lapasAmbon@2020</b></em></small>';	
                  }
                  ?> 
                  </td>
               </tr>
               <tr>
                  <td><label class="control-label" for="rePassword">Ulangi Kata Sandi<span style="color:#F00;">*</span></label>
                  <td><input class="form-control input-sm" type="password" placeholder="Confirm Password" id="repassword" value="<?=$txtPass?>" <?=$isReadOnlyPass?> />
               </td>
               </tr>
               <tr>
                  <td><label class="control-label" for="userAccess">Level Akses User<span style="color:#F00;">*</span></label>
                  <td>
                     <select name="userAccess" id="userAccess" class="form-control" data-placeholder="Pilih Level User" style="width: 50%;" >
                        <option value=""></option>
                        <?php
                        $sql = "select kd_access, nm_access from p_user_access order by 1";
                        $exe 	= mysqli_query($connDB, $sql);
                        writeLog(__LINE__, __FILE__, mysqli_error($connDB));
                        $x=0;
                        while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
                           $x++;
                           $selected = ($row['kd_access'] == $userAccess) ? "selected" : "";
                           echo '<option value="'.$row['kd_access'].'" '.$selected.'>'.$x.'. '.$row['nm_access'].'</option>';
                        } 
                     ?>
                     </select>
                  </td>
               </tr>
            </tbody>
         </table>
         <hr>
         <h4 class="txtOrange">Informasi Pengguna</h4>
         <table class="table table-striped table-condensed">
            <tbody>
               <tr>
                  <td width="30%"><label class="control-label" for="username">Nama User</label></td>
                  <td><input class="form-control input-sm" type="text" placeholder="Nama User" id="username" name="username" value="<?=$username?>"></td>
               </tr>
               <tr>
                  <td><label class="control-label" for="email">Email</label></td>
                  <td><input class="form-control input-sm" type="text" placeholder="Alamat Email" id="email" name="email" value="<?=$email?>">
                  <small>&nbsp;e.g : someone@example.com</small></td>
               </tr>
               <tr>
                  <td><label class="control-label" for="namaPropinsi">Wilayah</label></td>
                  <td><select name="kdPropinsi" id="kdPropinsi" class="form-control getPerencanaan kdPropinsi" style="width: 250px !important;">
                     <option></option>
                        <?php
                        $sql = "select  provinsiname, provinsiid from provinsi order by provinsiid";
                        $exe = mysqli_query($connDB, $sql);
                        writeLog(__LINE__, __FILE__, mysqli_error($connDB));
                        $i=0;
                        while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
                           $i++;
                           $selected = ($row['provinsiid'] == $kdProp) ? "selected" : "";
                           echo "<option value='".$row['provinsiid']."' ".$selected.">".$i.". ".$row['provinsiname']."</option>";
                        }
                        ?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td><label class="control-label" for="upt">UPT Pemasyarakatan</label></td>
                  <td><select id="kdKota" name="kdKota" class="form-control getPerencanaan kdKota" style="width: 250px !important;"><option"></option></select>
                  </td>
                  <?php
                  if(!empty($_GET['id']) || $_GET['id'] != ''){
                     echo '<input type="hidden" name="kdKotaTemp" id="kdKotaTemp" value="'.$kdKota.'" />';
                  }
                  ?>
               </tr>
               <tr>
                  <td>&nbsp;</td>
                  <td><input class="input-status" type="checkbox" id="isActive" name="isActive" value="1" <?=$checked?> > <label class="control-label" for="inputKategori">Is Active</label></td>
               </tr>
            </tbody>
         </table>
      </div>
      <div class="panel-footer text-right"> 
         <button type="reset" id="reset" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-remove"></span> Batal</button>&nbsp;<button id="submit" type="submit" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
      </div>
	</div>
</div> 
</form>