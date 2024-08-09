<?php session_start(); error_reporting(0); ?>
<?php // if(empty($_SESSION['token']) || !isset($_SESSION['token'])) { echo "<script>window.location.href='http://'+ window.location.hostname +'/sig_drainase'</script>"; }?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<script type="text/javascript">
$(document).ready(function(){

	var isKriteria = $('#isKriteria').val();
	if(isKriteria == 'false'){
		$('.isKriteria').hide();
		$('#nmKriteria').attr('disabled', 'disabled');
	}

	$("#nmKriteria").select2({
		placeholder: "Pilih Kriteria Evaluasi..",
		allowClear: true
    });

    $('#nmKriteria').change(function(){ 
        var nmKriteria 	= $("#nmKriteria").val();    
        var sektor 		= $("#nmSektor").val();  
        var nmFile 		= $("#nmFile").val();  
       	$('#divList').load('script/php/'+ nmFile +'.php?kdKriteria='+ nmKriteria);
    });
});
</script>
<form class="form-inline" style="padding-left: 14px;">
	<input type="hidden" name="isKriteria" id="isKriteria" value="<?=$_GET['isKriteria']?>">
	<input type="hidden" name="nmFile" id="nmFile" value="<?=$_GET['nmFile']?>">
	<div class="form-group isKriteria">
		<label class="control-label" for="nmKriteria">Kriteria Evaluasi</label>&nbsp;<b>:</b>
		<select class="form-control input-sm" id="nmKriteria" style="width: 250px;"><option value=""></option>
			<?php
			$sql = "select kd_kriteria, nm_kriteria from p_kriteria_evaluasi order by 1";
			$exe = mysqli_query($connDB, $sql);
			$i=0;
			while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
				$i++;
				echo '<option value="'.$row['kd_kriteria'].'">'.$i.'. '.$row['nm_kriteria'].'</option>';
			}
			?>
		</select>
	</div>
</form>