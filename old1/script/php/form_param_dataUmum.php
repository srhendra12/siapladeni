<?php session_start(); error_reporting(0); ?>
<?php // if(empty($_SESSION['token']) || !isset($_SESSION['token'])) { echo "<script>window.location.href='http://'+ window.location.hostname +'/sig_drainase'</script>"; }?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<?php $loadPage = (isset($_GET['loadPage']) || $_GET['loadPage'] != '') ? $_GET['loadPage'] : "divList"; ?>
<script type="text/javascript">
$(document).ready(function(){
				
	var isAccess	= $('#isAccess').val();

	if(isAccess > 1){
		var kdProp = $("#kdPropinsi<?=$loadPage?>").val(); 
		getLokasi(kdProp);

		var kdKota = $('#kdKotaTemp').val();
	   getKota('kdKota<?=$loadPage?>', kdProp, kdKota);
	}

	$("#bulan_data<?=$loadPage?>").select2({
		placeholder: "Pilih Bulan Data..",
		allowClear: true
	});

	$(".tahun_data").select2({
		placeholder: "Pilih Periode Data..",
		allowClear: true
	});
	
	if(isAccess == 1){
		$(".kdPropinsi").select2({
			placeholder: "Pilih Wilayah..",
			allowClear: true
		});
	}
	
	$(".kdKota").select2({
		placeholder: "Pilih UPT Pemasyarakatan..",
      allowClear: true
	});
	
    $('.kdPropinsi').change(function(){ 
        var prop  		= $("#kdPropinsi<?=$loadPage?>").val();
        var loadPage		= $("#loadPage<?=$loadPage?>").val(); 
        getKota('kdKota'+ loadPage, prop, '');
	 });
	 
	 $('.kdPropinsi, .kdKota, .tahun_data, .bulan_data').change(function(){ 
        var prop 			= $("#kdPropinsi<?=$loadPage?>").val();   
        var kota 			= $("#kdKota<?=$loadPage?>").val();   
		  var bulanData 	= $("#bulan_data<?=$loadPage?>").val();
		  var tahunData 	= $("#tahun_data<?=$loadPage?>").val();
        var listFile		= $("#listFile<?=$loadPage?>").val();  
        var loadPage		= $("#loadPage<?=$loadPage?>").val();  
        var isDashboard = (loadPage != '') ? 'isDashboard' : ''; 
        ajaxloading(loadPage);
       	$('#'+ loadPage).load('<?=BASE_URL?>script/php/'+ listFile +'.php?prop='+ prop +'&kota='+ kota +'&tahunData='+ tahunData +''+ bulanData +'&isDashboard='+ isDashboard );
	 });
	 
	if(isAccess != 1){
		var prop 			= $("#kdPropinsi<?=$loadPage?>").val();   
		var kota 			= $("#kdKota<?=$loadPage?>").val();   
		var bulanData 		= $("#bulan_data<?=$loadPage?>").val();
		var tahunData 		= $("#tahun_data<?=$loadPage?>").val();
		var listFile		= $("#listFile<?=$loadPage?>").val();  
		var loadPage		= $("#loadPage<?=$loadPage?>").val();  
		var isDashboard = (loadPage != '') ? 'isDashboard' : ''; 
		ajaxloading(loadPage);
		$('#'+ loadPage).load('<?=BASE_URL?>script/php/'+ listFile +'.php?prop='+ prop +'&kota='+ kota +'&tahunData='+ tahunData +''+ bulanData +'&isDashboard='+ isDashboard );
	}
});
</script>
<div class="center-block">
	<div class="panel-body">
		<input type="hidden" name="isAccess" id="isAccess" value="<?=$_SESSION['access']?>">
		<input type="hidden" name="listFile" id="listFile<?=$loadPage?>" value="<?=$_GET['listFile']?>">
		<input type="hidden" name="loadPage" id="loadPage<?=$loadPage?>" value="<?=$loadPage?>">
		<table class="table table-hover table-condensed" style="width: 80%;">
			<tbody>
				<tr>
	        		<td width="12%"><label class="control-label" for="thnKegiatan">Periode</label></td>
					<td colspan="4">
						<div class="form-inline">
							<div class="form-group">
								<select class="form-control getPerencanaan bulan_data" id="bulan_data<?=$loadPage?>" name="bulan_data" style="width: 70px !important;"><option></option>
									<?php
									for($x=1;$x<=12;$x++){
										$selected = (date('m') == $x) ? "selected" : "";
										$x = $x < 10 ? "0".$x : $x;
										echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
									}
									?>
								</select>
								<select class="form-control getPerencanaan tahun_data" id="tahun_data<?=$loadPage?>" name="tahun_data" style="width: 100px !important;"><option></option>
									<?php
									for($x=2015;$x<=(date('Y')+2);$x++){
										$selected = (date('Y') == $x) ? "selected" : "";
										echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
									}
									?>
								</select>
							</div>
						</div>
					</td>
	        	</tr>
				<?php
				if($_GET['listFile'] != 'rekap_penilaian'){
					$disabledKota 	= ($_SESSION['access'] != 1) ? "disabled" : "";
					if($_SESSION['access'] == 1){
						echo '<tr>';
							echo '<td><label class="control-label" for="namaPropinsi">Wilayah</label></td>';
							echo '<td width="30%"><select name="kdPropinsi" id="kdPropinsi'.$loadPage.'" class="form-control getPerencanaan kdPropinsi" style="width: 250px !important;">';
								echo '<option></option>';
									$sql = "select  provinsiname, provinsiid from provinsi order by provinsiid";
									$exe = mysqli_query($connDB, $sql);
									writeLog(__LINE__, __FILE__, mysqli_error($connDB));
									$i=0;
									while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
										$i++;
										echo "<option value='".$row['provinsiid']."'>".$i.". ".$row['provinsiname']."</option>";
									}
								echo '</select>';
							echo '</td>';
							echo '<td><label class="control-label" for="namaKota">UPT Pemasyarakatan</label></td>';
							echo '<td><select id="kdKota'.$loadPage.'" name="kdKota" class="form-control getPerencanaan kdKota" style="width: 400px !important;"><option></option></select>';
							echo '</td>';
						echo '</tr>';
					}
					else{
						echo '<tr>';
							echo '<td><label class="control-label" for="namaPropinsi">Wilayah</label></td>';
							echo '<td width="30%"><input class="form-control input-sm" type="text" id="nmPropinsi" name="nmPropinsi" style="width: 250px;" readonly>';
								echo '<input type="hidden" name="kdPropinsi" id="kdPropinsi'.$loadPage.'" value="'.$_SESSION['propinsi'].'" />';
							echo '</td>';
							echo '<td><label class="control-label" for="namaKota">UPT Pemasyarakatan</label></td>';
							echo '<td><select id="kdKota'.$loadPage.'" name="kdKota" class="form-control getPerencanaan kdKota" style="width: 400px !important;" '.$disabledKota.'><option></option></select>';
							if($_SESSION['access'] != 1){
								echo '<input type="hidden" name="kdKotaTemp" id="kdKotaTemp" value="'.$_SESSION['kota'].'" />';
							}
						echo '</tr> ';
					}
				}
	        	?>
			</tbody>	
        </table>
    </div>
</div> 
