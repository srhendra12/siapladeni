<?php session_start(); error_reporting(0); ?>
<?php // if(empty($_SESSION['token']) || !isset($_SESSION['token'])) { echo "<script>window.location.href='http://'+ window.location.hostname +'/sig_drainase'</script>"; }?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<style>
	/* Scroll Up */
	#button {
		display: inline-block;
		background-color: #FF9800;
		width: 50px;
		height: 50px;
		text-align: center;
		border-radius: 4px;
		position: fixed;
		bottom: 30px;
		right: 30px;
		transition: background-color .3s, 
			opacity .5s, visibility .5s;
		opacity: 0;
		visibility: hidden;
		z-index: 1000;
		text-decoration: none;
	}
	#button::after {
		content: "\f077";
		font-family: FontAwesome;
		font-weight: normal;
		font-style: normal;
		font-size: 2em;
		line-height: 50px;
		color: #fff;
	}
	#button:hover {
		cursor: pointer;
		background-color: #333;
	}
	#button:active {
		background-color: #555;
	}
	#button.show {
		opacity: 1;
		visibility: visible;
	}
</style>
<script type="text/javascript">
$(document).ready(function(){

	var kdProp 				= $('#kdPropinsi').val();
	var isEdit 				= $('#isEdit').val();
	var isAccess			= $('#isAccess').val();

	// Check isExist
	if(isEdit == ''){
		var periode = $('#tahun_data').val() +''+ $('#bulan_data').val();
		getIsExist(periode, 'buttonSimpan');
	}

	/* Scroll top */
	var btn = $('#button');
	$(window).scroll(function() {
		if ($(window).scrollTop() > 300) {
			btn.addClass('show');
		} else {
			btn.removeClass('show');
		}
	});

	btn.on('click', function(e) {
		e.preventDefault();
		$('html, body').animate({scrollTop:0}, '300');
	});

	$(".nilai").select2({
		placeholder: "Nilai..",
		allowClear: true
	});

	$('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
		localStorage.setItem('activeTab', $(e.target).attr('href'));
	});

	var activeTab = localStorage.getItem('activeTab');
	if(activeTab){
		$('#myTab a[href="' + activeTab + '"]').tab('show');
	}

	$('.nilai').change(function(){ 
		var tempTotSkor	= 0;
    	var sumSkor 		= 0;
		var sumPotensi 	= 0;
    	var param 			= $(this).attr('id');
    	var splitParam 	= param.split('_');
    	var id 				= splitParam[0] + splitParam[1] + splitParam[2];
    	var selectVal		= $('select[name=nilai'+ id +']').val() === '' ? 0 : $('select[name=nilai'+ id +']').val();

		var idParam			= splitParam[0] + splitParam[1];
		var bobot 			= $('#bobot'+ idParam).val();

		$(".pilihNilai" + idParam).each(function(){
    		nilai = parseFloat($(this).val());
    		if (!isNaN(nilai)) {
				tempTotSkor += +nilai;
	      }
	   });
		var skorPotensi	= parseFloat(bobot) - parseFloat(tempTotSkor);
		$("#totalSkor"+ idParam).val(parseFloat(tempTotSkor.toFixed(2)));
		$('#totalSkor'+ idParam).val(parseFloat(tempTotSkor.toFixed(2)));
		$('#skorPotensi'+ idParam).val(parseFloat(skorPotensi.toFixed(2)));
		$('#txtSkorPotensi'+ idParam).text(parseFloat(skorPotensi.toFixed(2)));
    	$('#selectedNilai'+ id).val(selectVal);
		
    	$(".totalSkor").each(function(){
    		nilai = parseFloat($(this).val());
    		if (!isNaN(nilai)) {
				sumSkor += +nilai;
	      }
	   });

		$(".skorPotensi").each(function(){
    		nilai = parseFloat($(this).val());
    		if (!isNaN(nilai)) {
				sumPotensi += +nilai;
	      }
	   });
		
		$("#sumSkor").val(parseFloat(sumSkor.toFixed(2)));
		$("#sumPotensi").val(parseFloat(sumPotensi.toFixed(2)));

		var sumBobot = $('#sumBobot').val();
		var valPersentasePetugas = (sumSkor / sumBobot) * 100;
		var valPerilakuNapi = (100 - parseFloat(valPersentasePetugas));

		$("#valPersentasePetugas").val(parseFloat(valPersentasePetugas.toFixed(4)));
		$("#valPerilakuNapi").val(parseFloat(valPerilakuNapi.toFixed(4)));

		getKategori('p_range_tahap_perencanaan', parseFloat(valPersentasePetugas.toFixed(2)), 'txtPersentasePetugas', 'kdPemahamanPetugas');
		getKategori('p_range_perilaku_napi', parseFloat(valPerilakuNapi.toFixed(2)), 'txtPerilakuNapi', 'kdPerilakuNapi');

	});

	if(isEdit !="" || isAccess > 1){
		getLokasi(kdProp);
		var kdKota = $('#kdKotaTemp').val();
	   getKota('kdKota', kdProp, kdKota);
	}

	$("#tahun_data").select2({
		placeholder: "Pilih Tahun Data..",
		allowClear: true
	});

	$("#bulan_data").select2({
		placeholder: "Pilih Bulan Data..",
		allowClear: true
	});

	if(isAccess == 1){
		$("#kdPropinsi").select2({
			placeholder: "Pilih Wilayah..",
			allowClear: true
		});
	}

	$("#kdKota").select2({
		placeholder: "Pilih UPT Pemasyarakatan..",
		allowClear: true
    });

	$('#kdPropinsi').change(function(){ 
		var prop = $("#kdPropinsi").val();    
		getKota('kdKota', prop, '');
	});

	$('#bulan_data').change(function(){ 
		var bulan = $("#bulan_data").val();   
		var tahun = $("#tahun_data").val();  
		getIsExist(tahun+''+bulan, 'buttonSimpan');
	});

	$('#tahun_data').change(function(){ 
		var bulan = $("#bulan_data").val();   
		var tahun = $("#tahun_data").val();    
		getIsExist(tahun+''+bulan, 'buttonSimpan');
	});

	$("#batal").click(function() {
		localStorage.removeItem('activeTab');
		location.href='<?=BASE_URL?>?fl=output_perencanaan';
	});

	$("#form").submit(function() {
		var bulanData 		= $('#bulan_data').val();
		var tahunData 		= $('#tahun_data').val();
		var kdPropinsi 	= $('#kdPropinsi').val();
		var kdKota 			= $('#kdKota').val();
		if(bulanData === '' || tahunData === ''){
			alert('Maaf, Periode Data belum lengkap !');
			return false;
		}
		else if(kdPropinsi === ''){
			alert('Maaf, Wilayah belum anda pilih !');
			return false;
		}
		else if(kdKota === ''){
			alert('Maaf, UPT Pemasyarakatan belum anda pilih !');
			return false;
		}
		else{
			var myForm = document.getElementById('form');
			var formData = new FormData(myForm); 
			formData.append("dokumen", $("#dokumen")[0].files[0]);
			$.ajax({
				type			: "POST",
				url			: $(this).attr('action'),
				data			: $(this).serialize(),
				data			: formData,
				contentType	: false,
	 	   	processData	: false, 
				dataType	: "json",
				success 		: function(data) {
					if(data.error == false){
						var timeout = 2000; // 1 seconds
		             	var dialog = bootbox.dialog({
			             	message : '<p class="text-center">'+ data.message +'</p>',
			                size    : "small",
			                closeButton: false
		                });
		             	setTimeout(function () {
			             	dialog.modal('hide');
								window.location.href='<?=BASE_URL?>?fl=output_perencanaan';
							}, timeout);
					}
					else{
						bootbox.alert(data.message);
					}
				},  
				error : function() {  
					bootbox.alert("#errorCode !!");  
				}  
			}); 

			localStorage.removeItem('activeTab');
			return false;
		}
	}); 

});
</script>
<?php
if (!empty($_GET['isEdit']) && $_GET['isEdit'] != 'undefined'){
	
	$sql = "select provinsiid, kabupatenkotaid, tahun_data, keterangan, sumPotensi, sumSkor, valPersentasePetugas, 
			valPerilakuNapi, dokumen, keyNumber
			from lapas_perencanaan_umum
			where kd_perencanaan_umum = '".$_GET['isEdit']."'";
	$exe = mysqli_query($connDB, $sql);
	writeLog(__LINE__, __FILE__, mysqli_error($connDB));
	$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
	$keyNumber 		= trim($row['keyNumber']);

	$kdPropinsi		= $row['provinsiid'];
	$kdKota			= $row['kabupatenkotaid'];
	$tahun_data 	= substr($row['tahun_data'],0,4);
	$bulan_data 	= substr($row['tahun_data'],-1,2);
	$keterangan 	= nl2br($row['keterangan']);
	$dokumen 	= $row['dokumen'];

	$sumPotensi 				= $row['sumPotensi'];
	$sumSkor 					= $row['sumSkor'];
	$valPersentasePetugas 	= $row['valPersentasePetugas'];
	$valPerilakuNapi 			= $row['valPerilakuNapi'];

	$sql = "select kd_kategori, nmKategori from p_range_tahap_perencanaan where '".$valPersentasePetugas."' 
    		between nilaiBatasBawah and nilaiBatasAtas";
	$exe = mysqli_query($connDB, $sql);
	writeLog(__LINE__, __FILE__, mysqli_error($connDB));
	$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
	$penilaianPetugas					= $row['nmKategori'];

	$sql = "select kd_kategori, nmKategori from p_range_perilaku_napi where '".$valPerilakuNapi."' 
    		between nilaiBatasBawah and nilaiBatasAtas";
	$exe = mysqli_query($connDB, $sql);
	writeLog(__LINE__, __FILE__, mysqli_error($connDB));
	$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
	$penilaianNapi				= $row['nmKategori'];
}
else{
	$tahun_data	= date('Y');
	$bulan_data	= date('m');
	$keyNumber 	= sha1(date('dmY h:i:s').rand());
	$kdKota 		= $_SESSION['access'] != 1 ? $_SESSION['kota'] : "";
}

$disabled 		= (!empty($_GET['isEdit']) && $_GET['isEdit'] != 'undefined') ? "disabled" : "";
$hideColumn 	= ($_SESSION['access'] != 1) ? "style='display: none;'" : "";
$disabledKota 	= (!empty($_GET['isEdit']) && $_GET['isEdit'] != 'undefined' || $_SESSION['access'] != 1) ? "disabled" : "";

?>
<!-- Back to top button -->
<a id="button"></a>

<form id="form" name="form" method="post" action="<?=BASE_URL?>include/proses.php" autocomplete="off" enctype="multipart/form-data">
<div class="center-block content">
    	<div class="panel panel-info">
    		<div class="panel-heading">
				<b class="panel-title">Instrument Deteksi Dini Potensi Gangguan Keamanan dan Ketertiban</b>
        	</div>
        	<div class="panel-body">
				<input type="hidden" name="action" value="inputPerencanaan">
				<input type="hidden" name="isAccess" id="isAccess" value="<?=$_SESSION['access']?>">
				<input type="hidden" name="isEdit" id="isEdit" value="<?php echo ($_GET['isEdit'] == 'undefined') ? '' : $_GET['isEdit']; ?>" />
				<input type="hidden" name="keyNumber" id="keyNumber" value="<?=$keyNumber?>" />
				<input type="hidden" name="kdPropTemp" id="kdPropTemp" value="<?=$kdPropinsi?>" />

				<div id="exTab3">
					<ul  class="nav nav-pills" id="myTab">
						<li class="active"><a  href="#dataUmum" data-toggle="tab">Informasi Umum</a></li>
						<li><a href="#evaluasi" data-toggle="tab">Instrument Deteksi Dini</a></li>
						<li><a href="#uploadDok" data-toggle="tab">Dokumen Keabsahan Data</a></li>
					</ul>
				
					<div class="tab-content clearfix">
						<!-- Form Informasi Umum -->
						<div class="tab-pane active" id="dataUmum">
							<div class="space"></div>
							<div class="col-md-6">
								<table class="table table-hover table-condensed">
									<tbody>
										<tr>
						        			<td width="35%"><label class="control-label" for="thnKegiatan">Periode Data</label></td>
											<td>
						               	<div class="form-inline">
													<div class="form-group">  
														<select class="form-control getPerencanaan" id="bulan_data" name="bulan_data" style="width: 70px !important;" <?=$disabled?>><option></option>
															<?php
															for($x=1;$x<=12;$x++){
																$selected = ($bulan_data == $x) ? "selected" : "";
																$x = $x < 10 ? "0".$x : $x;
																echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
															}
															?>
														</select>
														<select class="form-control getPerencanaan" id="tahun_data" name="tahun_data" style="width: 100px !important;" <?=$disabled?>><option value=""></option>
															<?php
															for($x=2015;$x<=(date('Y')+2);$x++){
																$selected = ($tahun_data == $x) ? "selected" : "";
																echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
															}
															?>
														</select>
													</div>
												</div>
											</td>
										</tr>
										<?php

										if($_SESSION['access'] == 1){
											echo '<tr>';
												echo '<td><label class="control-label" for="namaPropinsi">Wilayah</label></td>';
												echo '<td><select name="kdPropinsi" id="kdPropinsi" class="form-control getPerencanaan" style="width: 250px !important;" '.$disabled.'>';
													echo '<option></option>';
													$sql = "select  provinsiname, provinsiid from provinsi order by provinsiid";
													$exe = mysqli_query($connDB, $sql);
													writeLog(__LINE__, __FILE__, mysqli_error($connDB));
													$i=0;
													while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
															$i++;
															$selected = ($kdPropinsi == $row['provinsiid']) ? "selected" : "";
															echo "<option value='".$row['provinsiid']."' ".$selected.">".$i.". ".$row['provinsiname']."</option>";
													}
													echo '</select>';
												echo '</td>';
											echo '</tr>';
										}
										else{
											echo '<tr>';
												echo '<td><label class="control-label" for="namaPropinsi">Wilayah</label></td>';
												echo '<td><input class="form-control input-sm" type="text" id="nmPropinsi" name="nmPropinsi" style="width: 250px;"'.$disabled.' readonly>';
													$propSelected = (!empty($_GET['isEdit']) && $_GET['isEdit'] != 'undefined') ? $kdPropinsi : $_SESSION['propinsi'];
													echo '<input type="hidden" name="kdPropinsi" id="kdPropinsi" value="'.$propSelected.'" />';
												echo '</td>';
											echo '</tr> ';
										}
										?>
										<tr>
											<td><label class="control-label" for="namaKota">UPT Pemasyarakatan</label></td>
											<td>
												<select id="kdKota" name="kdKota" class="form-control getPerencanaan" style="width: 400px !important;" <?=$disabledKota?>><option></option></select>
												<?php
												if(!empty($_GET['isEdit']) && $_GET['isEdit'] != 'undefined' || $_SESSION['access'] != 1){
													echo '<input type="hidden" name="kdKotaTemp" id="kdKotaTemp" value="'.$kdKota.'" />';
												}
												?>
											</td>
										</tr>
										<tr>
											<td><label class="control-label" for="keterangan">Keterangan</label></td>
											<td><textarea class="form-control input-sm" rows="3" id="keterangan" name="keterangan"><?=$keterangan?></textarea></td>
										</tr>
								</tbody>	
					        </table>
							<div class="space"></div>
						</div>
					</div>

					<!-- Form Evaluasi -->
					<div class="tab-pane " id="evaluasi">
						<h5><span class="glyphicon glyphicon-triangle-right small" aria-hidden="true"></span> Evaluasi pengurangan resiko  gangguan keamanan</h5>
						<div class="space"></div>
							<table id="example" class="table table-bordered" cellspacing="0" width="100%">
								<thead class="bgBlue">
									<tr>
										<th width="3%" class="isCenter">No</th>
										<th width="15%" class="isCenter">Elemen Assessment</th>
										<th class="isCenter">Parameter</th>
										<th width="6%" class="isCenter" <?=$hideColumn?>>Nilai Bobot<br>Ideal</th>
										<th width="6%" class="isCenter">Total<br>Skor</th>
										<th width="6%" class="isCenter" <?=$hideColumn?>>Nilai bobot<br>potensi ganguan keamanan</th>
										<th width="15%" class="isCenter">Keterangan</th>
										<?php
										if($isConfirm == 1){
											echo '<th width="20%" class="isCenter">Status Verifikasi</th>';
										}
										?>
									</tr>
								</thead>
			               <tbody>
									<?php
									// Load Data Tahap Kesiapan Pelaksanaan
									$sql = "select kd_tahap_perencanaan, indikator, is_active
											from p_tahap_perencanaan where is_active = '1'
											order by 1";        
									$exe = mysqli_query($connDB, $sql);
									writeLog(__LINE__, __FILE__, mysqli_error($connDB));
									$x=0;
									while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
										$x++;
										$kdKriteria[$x] = $row['kd_tahap_perencanaan'];
										$romawi     	 = romawi($x);
										echo '<tr valign="middle" class="bgGrey">'; 
											echo '<td align="center"><b class="txtBlue">'.$romawi.'</b></td>';
											$colspan = ($_SESSION['access'] != 1) ? 6 : 8;
											echo '<td colspan="'.$colspan.'"><b>'.strtoupper($row['indikator']).'</b></td>';
										echo '</tr>';

										// Load Data Sub Tahap Kesiapan Pelaksanaan
										$sqc = "select a.kd_sub_tahap_perencanaan, a.indikator, a.bobot, a.is_active
												from p_sub_tahap_perencanaan a, p_tahap_perencanaan b
												where a.kd_tahap_perencanaan = b.kd_tahap_perencanaan and 
												a.kd_tahap_perencanaan = '".$kdKriteria[$x]."' and a.is_active = '1'
												order by 1";        
										$exc = mysqli_query($connDB, $sqc);
										writeLog(__LINE__, __FILE__, mysqli_error($connDB));
										$i=0;
										while($roc = mysqli_fetch_array($exc, MYSQLI_ASSOC)){
											$i++;
											$kdSubKriteria[$i]  	= $roc['kd_sub_tahap_perencanaan'];
											$bobot[$i] 				= $roc['bobot'];
											echo '<tr valign="middle">'; 
													echo '<td align="center"><b>'.$i.'</b></td>';
													echo '<td>'.$roc['indikator'].'</td>';
													echo '<td>';
														$qry = "select kd_parameter, nm_parameter, deskripsi, isMandatory 
																from p_param_sub_tahap_perencanaan 
																where kd_sub_tahap_perencanaan = ".$kdSubKriteria[$i]." and is_active = '1'
																order by kd_parameter";
														$run = mysqli_query($connDB, $qry);
														$numRows = mysqli_num_rows($run);

														if($numRows > 0){
															echo '<table class="table" cellspacing="0" width="100%">';
															$j=0;
															while($param = mysqli_fetch_array($run, MYSQLI_ASSOC)){
																$j++;
																$kdParameter[$j] 	= $param['kd_parameter'];

																if(!empty($_GET['isEdit']) && $_GET['isEdit'] != 'undefined'){
																	$sqp = "select nilai from lapas_perencanaan_param_evaluasi
																			where kd_parameter = '".$kdParameter[$j]."' and 
																			keyNumber = '".$keyNumber."'";
																	$exp 	= mysqli_query($connDB, $sqp);
																	$rop 	= mysqli_fetch_array($exp, MYSQLI_ASSOC);
																	$nilailDB[$j] = $rop['nilai'];
																}
																$color = $param['isMandatory'] == 1 ? "style='color:red;'" : "";
																echo '<tr>';
																	echo '<td width="5%">'.$j.'.</td>';
																	echo '<td '.$color.'>'.$param['nm_parameter'].'</td>';
																	echo '<td>'.$param['deskripsi'].'</td>';
																	echo '<td class="isCenter" width="8%">';
																		echo '<select class="form-control input-sm pilihNilai'.$x.$i.' nilai" name="nilai[]" id="'.$x.'_'.$i.'_'.$j.'_'.$bobot[$i].'_'.$kdParameter[$j].'_'.$kdSubKriteria[$i].'_'.$kdKriteria[$x].'" placeholder="Nilai"><option></option>';
																		$where = $param['isMandatory'] == 1 ? "where nilai != 1" : "";
																		$qrt 	= "select nilai, keterangan from p_nilai ".$where." order by nilai desc";
																		$ext	= mysqli_query($connDB, $qrt);
																		$y=0;
																		while($point = mysqli_fetch_array($ext, MYSQLI_ASSOC)){
																			$y++;
																			$selected = ($nilailDB[$j] == $point['nilai'] && isset($nilailDB[$j])) ? "selected" : "";
																			echo "<option value='".$point['nilai']."' ".$selected.">".$point['nilai']." - ".$point['keterangan']."</option>";
																		}
																		echo "</select>";

																		echo '<input class="form-control input-sm" type="hidden" id="kdKriteria'.$x.$i.$j.'" name="kdKriteria[]" value="'.$kdKriteria[$x].'">';
																		echo '<input class="form-control input-sm" type="hidden" id="kdSubKriteria'.$x.$i.$j.'" name="kdSubKriteria[]" value="'.$kdSubKriteria[$i].'">';
																		echo '<input class="form-control input-sm" type="hidden" id="kdParameter'.$x.$i.$j.'" name="kdParameter[]" value="'.$kdParameter[$j].'">';
																		echo '<input class="form-control input-sm" type="hidden" id="selectedNilai'.$x.$i.$j.'" name="selectedNilai[]" value="'.$nilailDB[$j].'">';

																	echo '</td>';
																echo '</tr>';
															}
															echo '</table>';
														}
													echo '</td>';
													echo '<td align="center" class="isCenter" '.$hideColumn.'>';
														if(!empty($_GET['isEdit']) && $_GET['isEdit'] != 'undefined'){
															$sqp = "select totalSkor, skorPotensi, catatan
																	from lapas_perencanaan_penilaian_evaluasi
																	where kd_sub_tahap_perencanaan = '".$kdSubKriteria[$i]."' and 
																	keyNumber = '".$keyNumber."'";
															$exp 	= mysqli_query($connDB, $sqp);
															$rop 	= mysqli_fetch_array($exp, MYSQLI_ASSOC);
															$totalSkorValDB[$i] = $rop['totalSkor'];
															$skorPotensiValDB[$i] = $rop['skorPotensi'];
															$ketValDB[$i] = $rop['catatan'];
														}
														$skorPotensi[$i] = (!empty($skorPotensiValDB[$i])) ? $skorPotensiValDB[$i] : $bobot[$i];
														echo '<b>'.$bobot[$i].'</b>';
														echo '<input class="form-control input-sm" type="hidden" id="bobot'.$x.$i.'" value="'.$bobot[$i].'">';
														echo '<input class="form-control input-sm" type="hidden" name="kdKriteriaBobot[]" value="'.$kdKriteria[$x].'">';
														echo '<input class="form-control input-sm" type="hidden" name="kdSubKriteriaBobot[]" value="'.$kdSubKriteria[$i].'">';
													echo '</td>';
													echo '<td class="isCenter">';
														echo '<input class="form-control input-sm totalSkor" type="text" id="totalSkor'.$x.$i.'" name="totalSkor[]" readonly style="width:60px; margin-left:3px;" value="'.$totalSkorValDB[$i].'">';
													echo '</td>';
													echo '<td class="isCenter" '.$hideColumn.'>';
														echo '<input class="form-control input-sm skorPotensi" type="hidden" id="skorPotensi'.$x.$i.'" name="skorPotensi[]" style="width:70px; margin-left:5px;" value="'.$skorPotensi[$i].'">';
														echo '<span style="font-weight:bold;" id="txtSkorPotensi'.$x.$i.'">'.$skorPotensi[$i].'</span>';
													echo '</td>';
													echo '<td>';
														echo '<textarea class="form-control input-sm" rows="2" class="catatan" name="catatan[]" style="width: 100%; height:35vh">'.$ketValDB[$i].'</textarea>';
													echo '</td>';
											echo '</tr>';
											$totBobot += $roc['bobot'];
										}
									}
									?>
			               </tbody>
			            	<tr>
									<td colspan="<?php echo $colspan = ($_SESSION['access'] != 1) ? 3 : 4; ?>" align="right"><b>Total</b></td>
									<td align="center" <?=$hideColumn?>>
										<b><?=$totBobot?></b>
										<input class="form-control input-sm" style="width:70px;" type="hidden" id="sumBobot" name="sumBobot" readonly value="<?=$totBobot?>">
									</td>
									<td align="center">
										<input class="form-control input-sm" style="width:70px;" type="text" id="sumSkor" name="sumSkor" readonly value="<?=$sumSkor?>">
									</td>
									<td align="center" <?=$hideColumn?>>
										<input class="form-control input-sm" style="width:70px;" type="text" id="sumPotensi" name="sumPotensi" readonly value="<?=$sumPotensi?>">
									</td>
			               </tr>
							</table>
							
							<!-- Hasil Penilaian -->
							<hr>
							<div class="panel panel-info" <?=$hideColumn?>>
								<div class="panel-heading"><b>Kecenderungan Pemahaman Petugas terhadap Tupoksi Pemasyarakatan</b></div>
								<div class="panel-body">
									<div class="row">
										<div class="col-xs-6">
											<h5><span class="glyphicon glyphicon-triangle-right small" aria-hidden="true"></span> Hasil Penilaian : </h5>
											<div class="form-group" style="margin-left:20px;">
												<h4><i><span id="txtPersentasePetugas" class="txtBlue"><?=$penilaianPetugas?></span></i></h4>
												<input type="hidden" name="valPersentasePetugas" id="valPersentasePetugas" value="<?=$valPersentasePetugas?>">
											</div>
										</div>
										<div class="col-xs-6">
											<h5><span class="glyphicon glyphicon-triangle-right small" aria-hidden="true"></span> Keterangan Hasil :</h5>
											<table class="table table-bordered" cellspacing="0">
												<thead>
													<tr class="isCenter bgGrey">
														<td><b>Kategori</b></td>
														<td colspan="2"><b>Rentan Nilai (%)</b></td>
													</tr>
												</thead>
												<tbody>
													<?php
													$sql = "select kd_kategori, nmKategori, nilaiBatasBawah, nilaiBatasAtas 
															from p_range_tahap_perencanaan order by kd_kategori";
													$exe = mysqli_query($connDB, $sql);
													$x=0;
													while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
														$x++;
														switch($x){
															case 1 : $bgColor = "#5BC0DE"; $color = "#000000"; break;
															case 2 : $bgColor = "#FFC000"; $color = "#000000"; break;
															case 3 : $bgColor = "#5CB85C"; $color = "#ffffff"; break;
															case 4 : $bgColor = "#FF0000"; $color = "#ffffff"; break;
														}
														echo '<tr style="background-color:'.$bgColor.'; color:'.$color.'">';
															echo '<td>'.$row['nmKategori'].'</td>';
															echo '<td align="right" width="12%">'.$row['nilaiBatasBawah'].'</td>';
															echo '<td align="right" width="12%">'.$row['nilaiBatasAtas'].'</td>';
														echo '</tr>';
													}
													?>
												</tbody>
											</table>
									
										</div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-info" <?=$hideColumn?>>
								<div class="panel-heading"><b>Kecenderungan Perilaku Narapidana/Tahanan</b></div>
								<div class="panel-body">
									<div class="row">
										<div class="col-xs-6">
											<h5><span class="glyphicon glyphicon-triangle-right small" aria-hidden="true"></span> Hasil Penilaian : </h5>
											<div class="form-group" style="margin-left:20px;">
												<h4><i><span id="txtPerilakuNapi" class="txtBlue"><?=$penilaianNapi?></span></i></h4>
												<input type="hidden" name="valPerilakuNapi" id="valPerilakuNapi" value="<?=$valPerilakuNapi?>">
											</div>
										</div>
										<div class="col-xs-6">
											<h5><span class="glyphicon glyphicon-triangle-right small" aria-hidden="true"></span> Keterangan Hasil :</h5>
											<table class="table table-bordered" cellspacing="0">
												<thead>
													<tr class="isCenter bgGrey">
														<td><b>Kategori</b></td>
														<td colspan="2"><b>Rentang Nilai (%)</b></td>
													</tr>
												</thead>
												<tbody>
													<?php
													$sql = "select kd_kategori, nmKategori, nilaiBatasBawah, nilaiBatasAtas 
															from p_range_perilaku_napi order by kd_kategori";
													$exe = mysqli_query($connDB, $sql);
													$x=0;
													while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
														$x++;
														switch($x){
															case 1 : $bgColor = "#C6D9F1"; $color = "#000000"; break;
															case 2 : $bgColor = "#FFC000"; $color = "#000000"; break;
															case 3 : $bgColor = "#F79646"; $color = "#000000"; break;
															case 4 : $bgColor = "#FF0000"; $color = "#ffffff"; break;
															case 5 : $bgColor = "#DD0000"; $color = "#ffffff"; break;
															case 6 : $bgColor = "#C00000"; $color = "#ffffff"; break;
														}
														echo '<tr style="background-color:'.$bgColor.'; color:'.$color.'">';
															echo '<td>'.$row['nmKategori'].'</td>';
															echo '<td align="right" width="12%">'.$row['nilaiBatasBawah'].'</td>';
															echo '<td align="right" width="12%">'.$row['nilaiBatasAtas'].'</td>';
														echo '</tr>';
													}
													?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						<div class="space"></div>
					</div>

					<div class="tab-pane" id="uploadDok">
						<div class="space"></div>
						<div class="col-md-6">
							<div class="panel panel-info">
								<div class="panel-heading">
									<b class="panel-title">Downnload Surat Pernyataan Keabsahan dokumen :</b>
								</div>
								<div class="panel-body">
									<?php
									$sql = "select nmFile, keterangan from p_dokumen_pendukung where isActive = 1";
									$exe = mysqli_query($connDB, $sql);
									writeLog(__LINE__, __FILE__, mysqli_error($connDB));
									echo "<ul class='list-group'>";
									while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
										echo "<li class='list-group-item'><a href='".BASE_URL."attachment/dokumen_pendukung/".$row['nmFile']."'>".$row['nmFile']." - ".$row['keterangan']."</a></li>";
									}
									echo "</ul>";
									?>
								</div>
							</div>
							<div class="space"></div>
							<div class="panel panel-info">
								<div class="panel-heading">
									<b class="panel-title">Upload Surat Pernyataan Keabsahan dokumen :</b>
								</div>
								<div class="panel-body">
									<input class="btn btn-primary" type="file"  name="dokumen" id="dokumen">
									<div class="space"></div>
									<?php
									if(!empty($_GET['isEdit']) && $_GET['isEdit'] != 'undefined'){
										echo 'File terlampir : <a href="'.BASE_URL.'attachment/dokumen_keabsahan/'.$kdPropinsi.'/'.$kdKota.'/'.$keyNumber.'/'.str_replace(" ","%20", $dokumen).'" title="Dokumen Surat Pernyataan Keabsahan Data" alt="dokumen" target="_new">'.$dokumen.'</a>';
									}
									?>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
      </div>

		<!-- Submit Button -->
		<div class="panel-heading">
			<div class="text-right">
				<button type="button" class="btn btn-default" id="batal" name="Batal" ><span class="glyphicon glyphicon-remove"></span> Batal</button>
				<button type="submit" class="btn btn-primary" id="buttonSimpan"><span class="glyphicon glyphicon-ok"></span> Simpan Data</button>
			</div>
		</div>
	</div>
</div> 
</form>