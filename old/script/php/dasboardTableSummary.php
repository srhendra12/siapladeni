<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<script type="text/javascript">
$(document).ready(function(){

	$('.txtSektor').text('Semua Sektor');

    $("#nmSektor").select2({
		placeholder: "Semua Sektor..",
		allowClear: true
    });

    $('#nmSektor').change(function(){ 
       	var sektor = $("#nmSektor").val();
       	var txtSektor;  
        switch(sektor){
        	case '1' 	: txtSektor = 'Sistem Pengelolaan Drainase'; break;
        	case '2' 	: txtSektor = 'Sistem Pengolahan Air Limbah'; break;
        	case '3' 	: txtSektor = 'Sistem Penanganan Persampahan'; break;
        	default 	: txtSektor = 'Semua Sektor'; break;
        }
        $('.txtSektor').empty().text(txtSektor);

       	$('#dashboardContent').load('<?=BASE_URL?>include/proses.php?act=getRekapJumData&sektor='+ sektor);
    });

    $('#dashboardContent').load('<?=BASE_URL?>include/proses.php?act=getRekapJumData&sektor=0');
    
});
</script>
<h5 class="txtBlue"><span class="glyphicon glyphicon-triangle-right small" aria-hidden="true"></span> Rekapitulasi Jumlah <span class="txtOrange">Data Kesiapan Pelaksanaan</span> (<span class="txtSektor"></span>) Tahun <?=date('Y')?></h5>
<div style="float: right; margin-top: -28px;">
	<form class="form-inline">
		<div class="form-group">
			<label for="exampleInputName2">Pilih Sektor : </label>
			<select class="form-control input-sm" id="nmSektor" style="width: 225px !important;"><option value="0">1. Semua Sektor</option>
			<?php
			$sql = "select kd_sektor, nm_sektor from p_sektor where is_active = '1' order by sortBy asc";
			$exe = mysqli_query($connDB, $sql);
			$i=1;
			while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
				$i++;
				echo '<option value="'.$row['kd_sektor'].'">'.$i.'. '.$row['nm_sektor'].'</option>';
			}
			?>
		</select>
		</div>
	</form>
</div>
<hr>
<div id="dashboardContent"></div>
