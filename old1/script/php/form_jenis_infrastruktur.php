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
		
		if(nmKategori == ''){
			bootbox.alert('Maaf, Form input Kategori Keberlanjutan belum anda isi !!');	
		}
		else if(nmSektor == ''){
			bootbox.alert('Maaf, Form input Nama Sektor belum anda pilih !!');	
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
							$('#divForm').load('script/php/form_jenis_infrastruktur.php');
							$('#divList').load('script/php/list_jenis_infrastruktur.php');
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
	$sql = "select nmJenisInfra, kd_sektor from p_enis_infrastruktur 
			where kd_jenisInfra = ".$_GET['id'];
	$exe = mysqli_query($connDB, $sql);
	$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
	writeLog(__LINE__, __FILE__, mysqli_error($connDB));
	$nmJenisInfra 		= $row['nmJenisInfra'];
	$kdSektor 			= $row['kd_sektor'];
	$isEdit 			= $_GET['id'];
}
?>
<form id="form" name="form" method="post" action="<?=BASE_URL?>include/proses.php" autocomplete="off">
<input type="hidden" name="action" value="input_jenis_infrastruktur">
<input type="hidden" name="isEdit" value="<?=$isEdit;?>">
<div class="center-block col-sm-5" style="padding-left:0px;">
    <div class="panel panel-info">
    	<div class="panel-heading" align="left">
          <b class="panel-title">Input Parameter Jenis Infrastruktur</b>
        </div>
        <div class="panel-body">
			<table class="table table-striped">
	        	<tr>
	    			<td><label class="control-label" for="nmSektor">Sektor</label></td>
					<td><select class="form-control input-sm" name="nmSektor" id="nmSektor" style="width: 225px !important;"><option value=""></option>
						<?php
						$sql = "select kd_sektor, nm_sektor from p_sektor where is_active = '1' order by 1";
						$exe = mysqli_query($connDB, $sql);
						$i=0;
						while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
							$i++;
							$selected = ($row['kd_sektor'] == $kdSektor) ? "selected" : "";
							echo '<option value="'.$row['kd_sektor'].'" '.$selected.'>'.$i.'. '.$row['nm_sektor'].'</option>';
						}
						?>
					</select>
					</td>
	    		</tr>
	    		<tr>
	            	<td width="30%"><label class="control-label" for="nmJenisInfra">Jenis Infrastruktur</label></td>
	                <td><input class="form-control" type="text" id="nmJenisInfra" name="nmJenisInfra" value="<?=$nmJenisInfra?>"></td>
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