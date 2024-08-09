<?php session_start(); error_reporting(0); ?>
<?php // if(empty($_SESSION['token']) || !isset($_SESSION['token'])) { echo "<script>window.location.href='http://'+ window.location.hostname +'/sig_drainase'</script>"; }?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<script type="text/javascript">
$(document).ready(function(){
	$('#reset').click(function(){ 
		$('#divForm').load('<?=BASE_URL?>script/php/form_penilaian_tahap_perencanaan.php');
	});

	$("#nmSektor").select2({
		placeholder: "Pilih Sektor..",
		allowClear: true
    });
	
	$("#form").submit(function() {
		var nmKategori 		= $("#nmKategori").val();
		var nmSektor 		= $("#nmSektor").val();
		var nilaiBatasBawah = $("#nilaiBatasBawah").val();	
		var nilaiBatasAtas 	= $("#nilaiBatasAtas").val();	
		
		if(nmKategori == ''){
			bootbox.alert('Maaf, Form input Kategori Keberlanjutan belum anda isi !!');	
		}
		else if(nmSektor == ''){
			bootbox.alert('Maaf, Form input Nama Sektor belum anda pilih !!');	
		}
		else if(nilaiBatasBawah == ''){
			bootbox.alert('Maaf, Form input Nilai Batas Bawah belum anda isi !!');	
		}
		else if(nilaiBatasAtas == ''){
			bootbox.alert('Maaf, Form input Nilai Batas Atas belum anda isi !!');	
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
							$('#divForm').load('<?=BASE_URL?>script/php/form_penilaian_tahap_perencanaan.php');
							$('#divList').load('<?=BASE_URL?>script/php/list_penilaian_tahap_perencanaan.php');
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
	$sql = "select nmKategori, nilaiBatasBawah, nilaiBatasAtas from p_range_tahap_perencanaan 
			where kd_kategori = ".$_GET['id'];
	$exe = mysqli_query($connDB, $sql);
	$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
	writeLog(__LINE__, __FILE__, mysqli_error($connDB));
	$nmKategori 		= $row['nmKategori'];
	$nilaiBatasBawah 	= $row['nilaiBatasBawah'];
	$nilaiBatasAtas 	= $row['nilaiBatasAtas'];
	$isEdit 			= $_GET['id'];
}
?>
<form id="form" name="form" method="post" action="<?=BASE_URL?>include/proses.php" autocomplete="off">
<input type="hidden" name="action" value="input_range_perencanaan">
<input type="hidden" name="isEdit" value="<?=$isEdit;?>">
<div class="center-block col-sm-5" style="padding-left:0px;">
	<div class="panel panel-info">
	<div class="panel-heading" align="left">
			<b class="panel-title">Input Range Penilaian Kecenderungan Pemahaman Petugas</b>
		</div>
		<div class="panel-body">
			<table class="table table-striped">
				<tr>
					<td width="30%"><label class="control-label" for="nmKategori">Kategori</label></td>
					<td><input class="form-control" type="text" id="nmKategori" name="nmKategori" value="<?=$nmKategori?>"></td>
				</tr>
				<tr>
					<td><label class="control-label" for="nilaiBatasBawah">Nilai Batas Bawah</label></td>
					<td><input class="form-control input-sm" type="text" id="nilaiBatasBawah" name="nilaiBatasBawah" onKeyPress="return hanyaangka(event);"  style="width: 70px;" value="<?=$nilaiBatasBawah?>"></td>
				</tr>
				<tr>
					<td><label class="control-label" for="nilaiBatasAtas">Nilai Batas Atas</label></td>
					<td><input class="form-control input-sm" type="text" id="nilaiBatasAtas" name="nilaiBatasAtas" onKeyPress="return hanyaangka(event);"  style="width: 70px;" value="<?=$nilaiBatasAtas?>"></td>
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