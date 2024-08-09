<?php session_start(); error_reporting(0); ?>
<?php // if(empty($_SESSION['token']) || !isset($_SESSION['token'])) { echo "<script>window.location.href='http://'+ window.location.hostname +'/sig_drainase'</script>"; }?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<?php $loadPage = (isset($_GET['loadPage']) || $_GET['loadPage'] != '') ? $_GET['loadPage'] : "divList"; ?>
<script type="text/javascript">
$(document).ready(function(){
	$("#sektor").val('1'); 
	var isAccess	= $('#isAccess').val();
	var prop 		= $("#kdPropinsi").val();   
    var kota 		= $("#kdKota").val();   
    var tahunData 	= $("#tahun_data").val();
    var sektor 		= $("#sektor").val();  

    if(isAccess > '2'){
    	var kdProp = $("#kdPropinsi").val(); 
		getLokasi(kdProp);
   		getKota('kdKota', kdProp, '');
	}

	$(".tahun_data").select2({
	  placeholder: "Pilih Tahun Anggaran..",
      allowClear: true
    });

    $(".sektor").select2({
	  placeholder: "Pilih Sektor..",
      allowClear: true
    });

	if(isAccess <= '2'){
	    $(".kdPropinsi").select2({
		  placeholder: "Semua Propinsi..",
	      allowClear: true
	    });
	}
	
	$(".kdKota").select2({
	  placeholder: "Semua Kota / Kab..",
      allowClear: true
    });
	
    $('.kdPropinsi').change(function(){ 
        var prop = $("#kdPropinsi").val();
        getKota('kdKota', prop, '');
    });

    $('#tampilkan').click(function(){ 
        var prop 		= $("#kdPropinsi").val();   
        var kota 		= $("#kdKota").val();   
        var tahunData 	= $("#tahun_data").val();
        var sektor 		= $("#sektor").val();   

        ajaxloading('divList');
       	$('#divList').load('<?=BASE_URL?>script/php/rekap_hasil_monev.php?prop='+ prop +'&kota='+ kota +'&tahunData='+ tahunData +'&sektor='+ sektor );
    });

    ajaxloading('divList');
	$('#divList').load('<?=BASE_URL?>script/php/rekap_hasil_monev.php?prop='+ prop +'&kota='+ kota +'&tahunData='+ tahunData +'&sektor='+ sektor );
});
</script>
<div class="center-block">
	<div class="panel-body">
		<input type="hidden" name="isAccess" id="isAccess" value="<?=$_SESSION['access']?>">
		<table class="table table-hover table-condensed" style="width: 70%;">
			<tbody>
				<tr>
	        		<td width="20%"><label class="control-label" for="thnKegiatan">Tahun <?php echo $jenTahun = ($_GET['listFile'] == 'list_output_pascaKonstruksi') ? "Anggaran" : "Kegiatan";?></label></td>
               		<td>
               			<select class="form-control getPerencanaan tahun_data" id="tahun_data" name="tahun_data" style="width: 100px !important;"><option value=""></option>
	               			<?php
	               			for($x=2015;$x<=(date('Y')+2);$x++){
	               				$selected = (date('Y') == $x) ? "selected" : "";
	               				echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
	               			}
	               			?>
	               		</select>
	               	</td>
	               	<td><label class="control-label" for="sektor">Sektor</label></td>
               		<td>
               			<select class="form-control sektor" id="sektor" name="sektor" style="width: 225px !important;"><option value=""></option>
	               			<?php
	               			$sql = "select kd_sektor, nm_sektor from p_sektor where is_active = '1' order by 1";
							$exe = mysqli_query($connDB, $sql);
							$i=0;
							while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
								$i++;
								echo '<option value="'.$row['kd_sektor'].'">'.$i.'. '.$row['nm_sektor'].'</option>';
							}
	               			?>
	               		</select>
	               	</td>
	               	<td>&nbsp;</td>
	        	</tr>
				<?php
				echo '<tr>';
	        	if($_SESSION['access'] == 1){
	        		echo '<td><label class="control-label" for="namaPropinsi">Propinsi</label></td>';
               		echo '<td><select name="kdPropinsi" id="kdPropinsi" class="form-control getPerencanaan kdPropinsi" style="width: 250px !important;">';
               			echo '<option value="">-</option>';
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
	        		echo '<td><label class="control-label" for="namaKota">Kota/Kab.</label></td>';
		        	echo '<td><select id="kdKota" name="kdKota" class="form-control getPerencanaan kdKota" style="width: 250px !important;"><option value="">-</option></select>';
		        	echo '</td>';
	        	}
	        	else{
	        		echo '<td><label class="control-label" for="namaPropinsi">Propinsi</label></td>';
               		echo '<td><input class="form-control input-sm" type="text" id="nmPropinsi" name="nmPropinsi" style="width: 250px;" readonly>';
	               		$propSelected = (!empty($_GET['isEdit'])) ? $kdPropinsi : $_SESSION['propinsi'];
	               		echo '<input type="hidden" name="kdPropinsi" id="kdPropinsi" value="'.$propSelected.'" />';
               		echo '</td>';
	        		echo '<td><label class="control-label" for="namaKota">Kota/Kab.</label></td>';
               		echo '<td><select id="kdKota" name="kdKota" class="form-control getPerencanaan kdKota" style="width: 250px !important;"><option value="">-</option></select>';
	        	}
	        	echo '<td><button type="submit" class="btn btn-primary" id="tampilkan"><span class="glyphicon glyphicon-ok"></span> Tampilkan</button></td>';
	        	echo '</tr>';
	        	?>
			</tbody>	
        </table>
    </div>
</div> 
