<?php session_start(); error_reporting(0); ?>
<?php // if(empty($_SESSION['token']) || !isset($_SESSION['token'])) { echo "<script>window.location.href='http://'+ window.location.hostname +'/sig_drainase'</script>"; }?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<script type="text/javascript">
$(document).ready(function(){
	$("#batal").click(function() {
		location.href='<?=BASE_URL?>';
	});

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
						ajaxloading('divList');
						window.location.reload(true);
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
</script>
<?php
$sql = "select (select x.provinsiname	from provinsi x where x.provinsiid = a.r_provinsiid) provinsiname,
      a.kabupatenkotaname, a.r_provinsiid, a.alamat, a.email, a.nama_kepala, a.no_telp_kepala 
      from kabupatenkota a where a.kabupatenkotaid  = ".$_SESSION['kota'];
$exe = mysqli_query($connDB, $sql);
writeLog(__LINE__, __FILE__, mysqli_error($connDB));
$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
$provinsiname 	= $row['provinsiname'];
$provID 	= $row['r_provinsiid'];
$nmUpt 	= $row['kabupatenkotaname'];
$alamat 	= $row['alamat'];
$email 	= $row['email'];
$nmKepala= $row['nama_kepala'];
$noTelp 	= $row['no_telp_kepala'];
?>
<form id="form" name="form" method="post" action="<?=BASE_URL?>include/proses.php" autocomplete="off">
<input type="hidden" name="action" value="input_upt">
<input type="hidden" name="isEdit" value="<?=$_SESSION['kota'];?>">
<div class="center-block">
	<div class="col-md-6">
	    <div class="panel panel-info">
	    	<div class="panel-heading">
            <b class="panel-title">Profile UPT Pemasyarakatan</b>
	      </div>
         <div class="panel-body">
            <table class="table table-striped">
               <tr>
                  <td width="35%"><label class="control-label" for="inputNama">Wilayah</label></td>
                  <td>
                     <?=$provinsiname?>
                     <input class="form-control input-sm" type="hidden" id="wilayahID" name="wilayahID" value="<?=$provID?>">
                  </td>
               </tr>
               <tr>
                  <td><label class="control-label" for="nmUpt">Nama UPT Pemasyarakatan</label></td>
                  <td>
                     <?=$nmUpt?>
                     <input class="form-control input-sm" type="hidden" id="nmUpt" name="nmUpt" value="<?=$nmUpt?>">
                  </td>
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
                  <td><button type="reset" id="batal" class="btn btn-default">Batal</button>&nbsp;<button type="submit" class="btn btn-primary" id="submit">Simpan</button></td>
               </tr>
            </table>
         </div>
		</div>
	</div>
</div> 
<input type="hidden" name="jumData" value="<?=$x?>" />
</form>