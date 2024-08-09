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

    	/* -- Add / Remove Row Input --*/
		var p = $('#participants').val();
		var row = $('.participantRow');

		/* Functions */
		function getP(){
		  p = $('#participants').val();
		}

		function addRow() {
			 row.clone(true, true).appendTo('#participantTable').find('textarea').val('').end();
		}	

		function removeRow(button) {
		  button.closest('tr').remove();
		}

		if($('#participantTable tr').length === 2) {
			$('.remove').hide();
		} 

		$('.add').on('click', function () {
			getP();
			addRow();
			var i = Number(p)+1;
			$('#participants').val(i);
			
			$(this).closest('tr').appendTo('#participantTable');
			if ($('#participantTable tr').length === 2) {
				$('.remove').hide();
			} 
			else {
				$('.remove').show();
			}
		});

		$('.remove').on('click', function () {
			getP();
			if($('#participantTable tr').length === 2) {
				//alert('Can't remove row.');
				$('.remove').hide();
			} 
			else if($('#participantTable tr').length - 1 ==2) {
				$('.remove').hide();
				removeRow($(this));
				var i = Number(p)-1;
				$('#participants').val(i);
			} 
			else {
				removeRow($(this));
				var i = Number(p)-1;
				$('#participants').val(i);
			}
		});

		$('.hapus').on('click', function () {
			getP();
			removeRow($(this));
			var i = Number(p)-1;
			$('#participants').val(i);
		});

		/* ----------------- Save Data -------------------- */
		$("#form").submit(function() {
			$.ajax({  
				type		: 'POST',
				url		: $(this).attr('action'),
				data		: $(this).serialize(),
				dataType	: "json",
				success 	: function(data) {
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
	$sql = "select indikator, bobot, is_active, keyNumber from p_sub_tahap_perencanaan
			where kd_sub_tahap_perencanaan = ".$_GET['id'];
	$exe = mysqli_query($connDB, $sql);
	$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
	writeLog(__LINE__, __FILE__, mysqli_error($connDB));
	$indikator	= $row['indikator'];
	$bobot		= $row['bobot'];
	$checked 	= ($row['is_active'] == 1) ? "checked" : "";
	$keyNumber 	= $row['keyNumber'];
}
else{
	$checked 	= "checked";
	$keyNumber 	= sha1(date('dmY h:i:s').rand());
}
?>
<!-- <button onclick="location.reload(true)">reload</button> -->
<form id="form" name="form" method="post" action="<?=BASE_URL?>include/proses.php" autocomplete="off">
<input type="hidden" id="action" name="action" value="add_sub_tahap_perencanaan">
<input type="hidden" id="kd_pelaksaaan" name="kd_pelaksaaan" value="<?=$_GET['kdPelaksaaan']?>">
<input type="hidden" id="isEdit" name="isEdit" value="<?=$_GET['id']?>">
<input type="hidden" id="keyNumber" name="keyNumber" value="<?=$keyNumber?>">
<div class="container-fluid" style="margin-top: 4px;">
    <div class="row">
        <div class="col-md-6 col-md-offset-4 col-center">
            <div class="panel panel-info" style="margin-bottom: 0px;">
                <div class="panel-heading">
                  <b class="panel-title">Tambah Sub Elemen Assessment Instrument Deteksi Dini</b>
                </div>
                <div class="panel-body">
					<table class="table table-striped table-condensed">
                    	<tbody>
                    		<tr>
                    			<td width="30%"><label class="control-label" for="indikator">Elemen Assessment</label></td>
                				<td><textarea class="form-control input-sm" rows="2" id="indikator" name="indikator"><?=$indikator?></textarea></td>
								</tr>
								<tr>
                    			<td><label class="control-label" for="bobot">Nilai Bobot Ideal</label></td>
                				<td><input class="form-control input-sm" type="text" id="bobot" name="bobot" onKeyPress="return hanyaangka(event);"  style="width: 70px;" value="<?=$bobot?>"></td>
                    		</tr>
				            <tr>
				            	<td colspan="2"><label class="control-label" for="isActive">Point Assessment</label></td>
				           	</tr>
				           	<?php
								if($_GET['id'] != ''){
									$qry = "select kd_parameter, nm_parameter, deskripsi from p_param_sub_tahap_perencanaan 
											where kd_sub_tahap_perencanaan = ".$_GET['id']."
											order by kd_parameter desc";
									$run = mysqli_query($connDB, $qry);
									echo '<tr>';
										echo '<td colspan="3">';
											echo '<table class="table table-striped table-bordered">';
												echo '<tr>';
													echo '<td align="center"><b>Parameter</b></td>';
													echo '<td align="center"><b>Deskripsi</b></td>';
													echo '<td>&nbsp;</td>';
												echo '</tr>';
												while($res = mysqli_fetch_array($run, MYSQLI_ASSOC)){
													echo '<tr>';
														echo '<td>'.$res['nm_parameter'].'</td>';
														echo '<td>'.$res['deskripsi'].'</td>';
														echo '<td align="center"><a href="#" class="delete" id="'.$res['kd_parameter'].'" onclick="deleteData(\''.$res['kd_parameter'].'\', \'p_param_sub_tahap_perencanaan\', \'kd_parameter\', \'form_subtahap_perencanaan\');"><img src="'.BASE_URL.'assets/common/img/delete.png" alt="delete" title="hapus data" width="20px" ></a></td>';
													echo '</tr>';
													}
											echo '</table>';
										echo '</td>';
									echo '</tr>';
								}
								?>
				            <tr>
									<td colspan="2">
										<input type="hidden" id="participants" name="participants" value="1">
										<table class="table table-hover table-condensed" id="participantTable">
											<tr class="participantRow">
													<td><textarea class="form-control input-sm" rows="2" id="parameter[]" name="parameter[]" placeholder="Point Assessment"></textarea></td>
													<td><textarea class="form-control input-sm" rows="2" id="deskripsi[]" name="deskripsi[]" placeholder="Deskripsi"></textarea></td>
												<td><button class="btn btn-danger remove" type="button">Hapus</button></td>
											</tr>
											<tr id="addButtonRow">
												<td colspan="3">
													<button class="btn btn-success add" type="button">+ Assesment</button>
												</td>
											</tr>
										</table>	
									</td>
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
