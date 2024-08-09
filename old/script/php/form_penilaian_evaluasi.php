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
	<link href="<?=BASE_URL?>assets/bootstrap/extend/select2-master/css/select2.css" rel="stylesheet">

    
    <link rel="stylesheet" href="<?=BASE_URL?>assets/common/css/main.css" />
    
    <!-- jQuery v1.11.3 -->
    <script src="<?=BASE_URL?>assets/common/js/jquery.min.js"></script>
    
    <!-- Bootstrap Script -->
    <script src="<?=BASE_URL?>assets/bootstrap/js/bootstrap.min.js"></script>
   	<script src="<?=BASE_URL?>assets/bootstrap/extend/bootstrap-modal/bootstrap-modal.js"></script>
	<script src="<?=BASE_URL?>assets/bootstrap/extend/bootbox/bootbox.min.js"></script>
	<script src="<?=BASE_URL?>assets/bootstrap/extend/select2-master/js/select2.full.js"></script>
	
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
		  row.clone(true, true).appendTo('#participantTable').find('textarea, input').val('').end();
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

		$("#nmKriteria").select2({
			placeholder: "Pilih Kriteria Evaluasi..",
			allowClear: true
	    });

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
		var kdSektor = $('#kd_sektor').val();
		self.parent.ajaxloading('divList');
		self.parent.$('#divList').load('<?=BASE_URL?>script/php/list_penilaian_evaluasi.php?sektor='+ kdSektor);
		self.parent.Shadowbox.close();
	}
    </script>
</head>
<body>
<?php
if(!empty($_GET['id']) || $_GET['id'] != ''){
	$sql = "select kd_kriteria, is_active, keyNumber from p_penilaian_evaluasi
			where kd_penilaian = ".$_GET['id'];
	$exe = mysqli_query($connDB, $sql);
	$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
	writeLog(__LINE__, __FILE__, mysqli_error($connDB));
	$kdKriteria = $row['kd_kriteria'];
	$checked 	= ($row['is_active'] == 1) ? "checked" : "";
	$keyNumber 	= $row['keyNumber'];
	$isDisabled = "disabled";
}
else{
	$checked 	= "checked";
	$keyNumber 	= sha1(date('dmY h:i:s').rand());
	$isDisabled = "";
}
?>
<!-- <button onclick="location.reload(true)">reload</button> -->
<form id="form" name="form" method="post" action="<?=BASE_URL?>include/proses.php" autocomplete="off">
<input type="hidden" id="action" name="action" value="add_penilaian_evaluasi">
<input type="hidden" id="isEdit" name="isEdit" value="<?=$_GET['id']?>">
<input type="hidden" id="keyNumber" name="keyNumber" value="<?=$keyNumber?>">
<input type="hidden" id="kd_sektor" name="kd_sektor" value="<?=$_GET['sektor']?>">
<div class="container-fluid" style="margin-top: 4px;">
    <div class="row">
        <div class="col-md-6 col-md-offset-4 col-center">
            <div class="panel panel-info" style="margin-bottom: 0px;">
                <div class="panel-heading">
                  <b class="panel-title">Tambah Parameter Penilaian Pasca Konstruksi</b>
                </div>
                <div class="panel-body">
					<table class="table table-striped table-condensed">
                    	<tbody>
                    		<tr>
                    			<td width="30%"><label class="control-label" for="nmKriteria">Kriteria Evaluasi</label></td>
                				<td>
                					<select class="form-control input-sm" name="nmKriteria" id="nmKriteria" style="width: 180px;" <?=$isDisabled?>><option value=""></option>
										<?php
										$sql = "select kd_kriteria, nm_kriteria from p_kriteria_evaluasi order by 1";
										$exe = mysqli_query($connDB, $sql);
										$i=0;
										while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
											$i++;
											$selected = ($kdKriteria == $row['kd_kriteria']) ? "selected" : "";
											echo '<option value="'.$row['kd_kriteria'].'" '.$selected.'>'.$i.'. '.$row['nm_kriteria'].'</option>';
										}
										?>
									</select>
                				</td>
                    		</tr>
				            <tr>
				            	<td colspan="2"><label class="control-label" for="isActive">Parameter Indikator</label></td>
				           	</tr>
				           	<?php
							if($_GET['id'] != ''){
								$qry = "select kd_parameter, nm_parameter, nilai, nilaiBatasBawah, nilaiBatasAtas 
										from p_param_penilaian_evaluasi 
										where kd_penilaian = ".$_GET['id']."
										order by nilai desc";
								$run = mysqli_query($connDB, $qry);
					        	echo '<tr>';
					        		echo '<td colspan="2">';
					        			echo '<table class="table table-striped">';
					        				echo '<tr>';
										        echo '<th>&nbsp;</th>';
										        echo '<th>Nilai</th>';
										        echo '<th>Rentan Nilai Atas</th>';
												echo '<th>Rentan Nilai Bawah</th>';
										        echo '<th>&nbsp;</th>';
									        echo '</tr>';
									        while($res = mysqli_fetch_array($run, MYSQLI_ASSOC)){
											    echo '<tr>';
											        echo '<td>'.$res['nm_parameter'].'</td>';
											        echo '<td align="center">'.$res['nilai'].'</td>';
											        echo '<td align="center">'.$res['nilaiBatasBawah'].'</td>';
											        echo '<td align="center">'.$res['nilaiBatasAtas'].'</td>';
											        echo '<td><a href="#" class="delete" id="'.$res['kd_parameter'].'" onclick="deleteData(\''.$res['kd_parameter'].'\', \'p_param_penilaian_evaluasi\', \'kd_parameter\', \'form_penilaian_evaluasi\');"><img src="'.BASE_URL.'assets/common/img/delete.png" alt="delete" title="hapus data" width="20px" ></a></td>';
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
											<td width="35%"><textarea class="form-control input-sm" rows="2" id="parameter[]" name="parameter[]" placeholder="Parameter Indikator"></textarea></td>
											<td><input class="form-control input-sm type="text" id="nilai[]" name="nilai[]" placeholder="Nilai"  onKeyPress="return hanyaangka(event);"></td>
											<td><input class="form-control input-sm type="text" id="nilaiBatasBawah[]" name="nilaiBatasBawah[]" placeholder="Nilai Batas Bawah"  onKeyPress="return hanyaangka(event);"></td>
											<td><input class="form-control input-sm type="text" id="nilaiBatasAtas[]" name="nilaiBatasAtas[]" placeholder="Nilai Batas Atas"  onKeyPress="return hanyaangka(event);"></td>
									        <td><button class="btn btn-danger remove" type="button">Hapus</button></td>
								        </tr>
									    <tr id="addButtonRow">
									        <td colspan="5">
									        	<button class="btn btn-success add" type="button">+ Parameter</button>
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
