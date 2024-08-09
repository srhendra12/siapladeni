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
<form id="form" name="form" method="post" action="<?=BASE_URL?>include/proses.php" autocomplete="off">
<input type="hidden" name="action" value="manage_setting">
<div class="center-block">
	<div class="col-md-8">
		<div class="panel panel-info">
	    	<div class="panel-heading">
				<b class="panel-title">Web Setting</b>
			</div>
			<div class="panel-body">
				<table class="table table-striped">
					<tr>
						<td colspan="2" align="center"><h4>WEB SETTING MALUKU</h4></td>
					</tr>
					<?php
					$sql = "select kd_setting, nm_setting, nilai from p_setting where kd_wilayah = 1 order by kd_setting";
					$exe = mysqli_query($connDB, $sql);
					writeLog(__LINE__, __FILE__, mysqli_error($connDB));
					$x=0;
					while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
						$x++;
						echo '<tr>';
							echo '<td width="30%"><label class="control-label" for="input'.$x.'">'.$row['nm_setting'].'</label></td>';
							echo '<td><input class="form-control" type="text" id="input'.$x.'" name="input'.$x.'" value="'.$row['nilai'].'"></td>';
						echo '</tr>';	
					}
					?>
					<tr>
						<td colspan="2" align="center"><h4>WEB SETTING MALUKU UTARA</h4></td>
					</tr>
					<?php
					$sql = "select kd_setting, nm_setting, nilai from p_setting where kd_wilayah = 2 order by kd_setting";
					$exe = mysqli_query($connDB, $sql);
					writeLog(__LINE__, __FILE__, mysqli_error($connDB));
					while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
						$x++;
						echo '<tr>';
							echo '<td width="30%"><label class="control-label" for="input'.$x.'">'.$row['nm_setting'].'</label></td>';
							echo '<td><input class="form-control" type="text" id="input'.$x.'" name="input'.$x.'" value="'.$row['nilai'].'"></td>';
						echo '</tr>';	
					}
					?>
					<tr>
						<td>&nbsp;</td>
						<td>
							<button type="button" class="btn btn-default" id="batal" name="Batal" >Batal</button>
							<button type="submit" class="btn btn-primary">Perbaharui</button>
						</td>
					</tr>
				</table>
	        </div>
		</div>
	</div>
</div> 
<input type="hidden" name="jumData" value="<?=$x?>" />
</form>