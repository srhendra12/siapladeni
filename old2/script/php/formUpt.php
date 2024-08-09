<?php session_start(); error_reporting(0); ?>
<?php //if(empty($_SESSION['token']) || !isset($_SESSION['token'])) { echo "<script>window.location.href='http://'+ window.location.hostname +'/sig_drainase'</script>"; }?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<script type="text/javascript">
$(document).ready(function(){
	$('#reset').click(function(){ 
		$('#divForm').load('<?=BASE_URL?>script/php/formUpt.php');
	});

   $("#wilayahID").select2({
		placeholder: "Pilih Wilayah..",
		allowClear: true
	});

	$('#email').blur(function(){
      var email = $('#email').val();
      checkDataEntry('email', email, 'submit');
   });
	
	$("#form").submit(function() {
      var wilayahID  = $("#wilayahID").val();
		var nmUpt		= $("#nmUpt").val();	
		var nmKepala	= $("#nmKepala").val();	
		var noTelp		= $("#noTelp").val();	
		var isEdit		= $("#isEdit").val();
		
		if(wilayahID == '' && isEdit == ''){
			bootbox.alert('Maaf, Wilayah belum anda pilih !!');	
		}
      else if(nmUpt == '' && isEdit == ''){
			bootbox.alert('Maaf, Form input Nama UPT Pemasyarakatan belum anda isi !!');	
		}
		else if(nmKepala == '' && isEdit == ''){
			bootbox.alert('Maaf, Form input Nama Kepala UPT Pemasyarakatan belum anda isi !!');	
		}
		else if(noTelp == '' && isEdit == ''){
			bootbox.alert('Maaf, Form input No Telp/WA Kepala UPT Pemasyarakatan belum anda isi !!');	
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
							$('#divForm').load('<?=BASE_URL?>script/php/formUpt.php');
							$('#divList').load('<?=BASE_URL?>script/php/listUpt.php');
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
	$sql = "select kabupatenkotaname, r_provinsiid, alamat, email, nama_kepala, no_telp_kepala 
			from kabupatenkota where kabupatenkotaid  = ".$_GET['id'];
	$exe = mysqli_query($connDB, $sql);
    writeLog(__LINE__, __FILE__, mysqli_error($connDB));
	$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
   $provID 	= $row['r_provinsiid'];
	$nmUpt 	= $row['kabupatenkotaname'];
	$alamat 	= $row['alamat'];
	$email 	= $row['email'];
	$nmKepala= $row['nama_kepala'];
	$noTelp 	= $row['no_telp_kepala'];
}
?>
<form id="form" name="form" method="post" action="<?=BASE_URL?>include/proses.php" autocomplete="off">
<input type="hidden" name="action" value="input_upt">
<input type="hidden" name="isEdit" value="<?=$_GET['id'];?>">
<div class="center-block col-sm-4" style="padding-left:0px;">
    <div class="panel panel-info">
    	<div class="panel-heading" align="left">
          <b class="panel-title">Input UPT Pemasyarakatan</b>
        </div>
        <div class="panel-body">
            <table class="table table-striped">
               <tr>
                  <td><label class="control-label" for="inputNama">Wilayah</label></td>
                  <td>
                     <select name='wilayahID' id="wilayahID" class="form-control input-sm">
                        <option></option>
                        <?php
                        $sql = "select provinsiid, provinsiname from provinsi order by 1";
                        $exe = mysqli_query($connDB, $sql);
                        writeLog(__LINE__, __FILE__, mysqli_error($connDB));
                        $x=0;
                        while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
                           $x++;
                           $selected = $row['provinsiid'] == $provID ? "selected" : "";
                           echo "<option value='".$row['provinsiid']."' ".$selected.">".$x.". ".$row['provinsiname']."</option>";
                        }
                        ?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td><label class="control-label" for="nmUpt">Nama UPT Pemasyarakatan</label></td>
                  <td><input class="form-control input-sm" type="text" id="nmUpt" name="nmUpt" value="<?=$nmUpt?>"></td>
					</tr>
					<tr>
                  <td><label class="control-label" for="nmKepala">Nama Kepala UPT</label></td>
                  <td><input class="form-control input-sm" type="text" id="nmKepala" name="nmKepala" value="<?=$nmKepala?>"></td>
					</tr>
					<tr>
                  <td><label class="control-label" for="alamat">Alamat</label></td>
                  <td><textarea class="form-control input-sm" type="text" id="alamat" name="alamat"><?=$alamat?></textarea></td>
					</tr>
					<tr>
                  <td><label class="control-label" for="noTelp">No. HP/WA Kepala UPT</label></td>
                  <td><input class="form-control input-sm" type="text" id="noTelp" name="noTelp" value="<?=$noTelp?>" onKeyPress="return hanyaangka(event);" ></td>
					</tr>
					<tr>
                  <td><label class="control-label" for="email">Email</label></td>
                  <td><input class="form-control input-sm" type="text" id="email" name="email" value="<?=$email?>"></td>
					</tr>
               <tr>
                  <td>&nbsp;</td>
                  <td><button type="reset" id="reset" class="btn btn-default">Batal</button>&nbsp;<button type="submit" class="btn btn-primary" id="submit">Simpan</button></td>
               </tr>
            </table>
        </div>
	</div>
</div> 
</form>