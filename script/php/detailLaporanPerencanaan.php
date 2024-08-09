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
	<link href="<?=BASE_URL?>assets/bootstrap/extend/bootstrap-image-gallery/css/blueimp-gallery.min.css" rel="stylesheet" >
    
    <link rel="stylesheet" href="<?=BASE_URL?>assets/common/css/main.css" />
    
    <!-- jQuery v1.11.3 -->
    <script src="<?=BASE_URL?>assets/common/js/jquery.min.js"></script>
    
    <!-- Bootstrap Script -->
    <script src="<?=BASE_URL?>assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?=BASE_URL?>assets/bootstrap/extend/bootstrap-image-gallery/js/jquery.blueimp-gallery.min.js"></script>
    <script src="<?=BASE_URL?>assets/bootstrap/extend/bootstrap-image-gallery/js/bootstrap-image-gallery.min.js"></script>

	<!-- Custom Onload Script -->
	<script type="text/javascript" src="<?=BASE_URL?>assets/shadowbox/shadowbox.js"></script>
    <script type="text/javascript" src="<?=BASE_URL?>assets/common/js/main.js"></script>

	<script type="text/javascript">
		$(document).ready(function(){
			$('.image-link').click(function(){ 
				$('#blueimp-gallery').data('useBootstrapModal', false);
				$('#blueimp-gallery').toggleClass('blueimp-gallery-controls', true);
			});
		});

		function tutup(){
			self.parent.Shadowbox.close();
		}
	</script>
</head>
<body>
<!-- <button onclick="location.reload(true)">reload</button> -->
<div class="center-block" style="margin-top: 5px;">
   <div class="panel panel-info" style="margin-bottom: 0px;">
		<?php
		$sql = "select a.tahun_data, a.nm_kegiatan, a.pagu, a.status_usulan, a.kesimpulan, a.catatan, 
				a.total_skor, a.keyNumber, a.foto_kegiatan1, a.foto_kegiatan2, a.provinsiid, a.kd_sektor, a.tgl_kelengkapan_dok,
				(select c.kabupatenkotaname from kabupatenkota c where c.kabupatenkotaid = a.kabupatenkotaid and
				c.r_provinsiid = a.provinsiid) nm_kota,
				(select d.provinsiname from provinsi d where d.provinsiid = a.provinsiid) nm_propinsi,
				a.lokasi, a.kategori, a.deskripsi, a.ruangLingkup, a.penerimaManfaat, a.kapasitas, a.sumber_dana,
				(select e.nmJenisInfra from p_jenis_infrastruktur e where e.kd_jenisInfra = a.kd_jenisInfra) nmJenisInfra
				from lapas_perencanaan_umum a
				where kd_perencanaan_umum = '".$_GET['id']."'";
		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
		$kdPropinsi			= $row['provinsiid'];
		$nmPropinsi 		= $row['nm_propinsi'];
		$nmKota  			= strpos(strtolower($row['nm_kota']), 'kota' ) !== false ? strtoupper($row['nm_kota']) : "KABUPATEN ".strtoupper($row['nm_kota']);
		$tahun_data 		= $row['tahun_data'];
		$nmKegiatan 		= $row['nm_kegiatan'];
		$pagu 				= number_format($row['pagu'],2,',','.');
		$statusUsulan 		= $row['status_usulan'];
		$kesimpulan 		= $row['kesimpulan'];
		$catatan 			= $row['catatan'];
		$totalSkor 			= $row['total_skor'];
		$foto_kegiatan1	= $row['foto_kegiatan1'];
		$foto_kegiatan2 	= $row['foto_kegiatan2'];
		$keyNumber 			= trim($row['keyNumber']);
		$sektor 				= $row['kd_sektor'];
		$tglKelengkapanDok	= $row['tgl_kelengkapan_dok'];

		$lokasi 				= $row['lokasi'];
		$kategori 			= $row['kategori'];
		$deskripsi 			= nl2br($row['deskripsi']);
		$ruangLingkup 		= nl2br($row['ruangLingkup']);
		$penerimaManfaat= $row['penerimaManfaat'];

		$jenisInfra 		= $row['nmJenisInfra'];
		$kapasitas 			= $row['kapasitas'];
		$sumberDana 		= $row['sumber_dana'];

		$txtKapasitas 			= ($sektor == 1) ? "m<sup>3</sup>/dt" : "m<sup>3</sup>/hari";
		$txtPenerimaManfaat 	= ($sektor == 1) ? "Ha" : "KK";
			
		$sql = "select kd_kategori, nmKategori from p_range_tahap_perencanaan where '".$totalSkor."'
				between nilaiBatasBawah and nilaiBatasAtas and kd_sektor = '".$sektor."'";
		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
		$kdKategori = $row['kd_kategori'];
		$penilaian 	= $row['nmKategori'];

		if($kdKategori > 3){
			$optKategori = $kdKategori - 3;
		}
		elseif($kdKategori > 6){
			$optKategori = $kdKategori - 6;
		}
		else{
			$optKategori = $kdKategori;
		}

		switch($optKategori){
			case 1 : $bgColor = "#5AD3D1"; $color = "#000000"; break;
			case 2 : $bgColor = "#FFC870"; $color = "#000000"; break;
			case 3 : $bgColor = "#FF5A5E"; $color = "#ffffff"; break;
		}

		$sql = "select timPemantau, balaiPPW, pemda 
				from monev_perencanaan_petugas where keyNumber = '".$keyNumber."'";
		$exe = mysqli_query($connDB, $sql);
		$x=0;
		$timPemantau 	= array();
		$balaiPPW		= array();
		$pemda			= array();
		while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
			$timPemantau[$x] 	= $row['timPemantau'];
			$balaiPPW[$x] 		= $row['balaiPPW'];
			$pemda[$x] 			= $row['pemda'];
			$x++;
		}
		?>
		<div class="panel-heading">
			<h5><b><?=strtoupper($nmKegiatan)?></b></h5>
			<b><?=$nmKota?>, PROPINSI <?=strtoupper($nmPropinsi)?></b>
			<img width="35px" id="back" alt="back" onclick="tutup();" title="Kembali ke Laporan Perencanaan" src="<?=BASE_URL?>assets/common/img/close.png" style="padding-bottom:5px; float:right; margin:-28px 10px 0 0; cursor:pointer;">
      </div>
		<div class="panel-body">
			<div class="row">
				<div class="col-xs-4 col-md-4">
					<?php
					$thumb1 = (@getimagesize(BASE_URL."attachment/inputData/perencanaan/dataUmum/".$kdPropinsi."/".$sektor."/".$keyNumber."/".str_replace(" ","%20", $foto_kegiatan1)."")) ? BASE_URL."attachment/inputData/perencanaan/dataUmum/".$kdPropinsi."/".$sektor."/".$keyNumber."/".str_replace(" ","%20", $foto_kegiatan1) : BASE_URL."assets/common/img/not-available.png";

					$thumb2 = (@getimagesize(BASE_URL."attachment/inputData/perencanaan/dataUmum/".$kdPropinsi."/".$sektor."/".$keyNumber."/".str_replace(" ","%20", $foto_kegiatan2)."")) ? BASE_URL."attachment/inputData/perencanaan/dataUmum/".$kdPropinsi."/".$sektor."/".$keyNumber."/".str_replace(" ","%20", $foto_kegiatan2) : BASE_URL."assets/common/img/not-available.png";

					?>
					<a href="<?=$thumb1?>"  title="Foto Kegiatan 1" alt="ImageHome" target="_new" data-gallery class="image-link">
					<div class="post-thumbnail bg-img" style="max-width: 100%; width: 100%; margin-bottom:2px;  background-image: url(<?=$thumb1?>);"></div>
					</a>
					<a href="<?=$thumb2?>"  title="Foto Kegiatan 2" alt="ImageHome" target="_new" data-gallery class="image-link">
						<div class="post-thumbnail bg-img" style="max-width: 100% !important; width: 100% !important;  margin-bottom:2px;background-image: url(<?=$thumb2?>);"></div>
					</a>
				</div>
				<div class="col-xs-8 col-md-8">
					<div class="space"></div>
					<table cellspacing="0" style="margin-top: -25px;">
							<tbody>
								<tr>
									<td><b>DESKRIPSI SINGKAT :</b></td>
								</tr>
								<tr>
									<td><?=$deskripsi?><div class="space"></div></td>
								</tr>
								<tr>
									<td><b>LINGKUP PEKERJAAN :</b></td>
								</tr>
								<tr>
									<td><?=$ruangLingkup?><div class="space"></div></td>
								</tr>
								<tr>
									<td><b>BIAYA PELAKSANAAN :</b></td>
								</tr>
								<tr>
									<td><?=STRTOUPPER($sumberDana)?> - TA. <?=$tahun_data?> : Rp. <?=$pagu?><div class="space"></div></td>
								</tr>
								<tr>
									<td><b>CATATAN :</b></td>
								</tr>
								<tr>
									<td><?=$catatan?><div class="space"></div></td>
								</tr>
								<tr>
									<td>
									<b>TINDAK LANJUT :</b></td>
								</tr>
								<tr>
									<td><?=$kesimpulan?><div class="space"></div></td>
								</tr>
							</tbody>
						</table>
				</div>
			</div>
			<div class="space"></div>
			<h5 class="txtBlue"><span class="glyphicon glyphicon-triangle-right small" aria-hidden="true"></span> Hasil Evaluasi :</h5>
			<h5>Paket Kesiapan Pelaksanaan <span class="txtOrange"><?=ucwords(str_replace('Paket', '', $penilaian))?></span>, karena :</h5>
			<?php
			// Load Data Tahap Kesiapan Pelaksanaan
			$sql = "select kd_tahap_perencanaan, indikator, bobot, is_active
					from p_tahap_perencanaan where is_active = '1' and kd_sektor = '".$sektor."'
					order by 1";        
			$exe = mysqli_query($connDB, $sql);
			writeLog(__LINE__, __FILE__, mysqli_error($connDB));
			$total = array();
			$x=0;
			while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
				$x++;
				$kdKriteria = $row['kd_tahap_perencanaan'];
				$romawi     = romawi($x);
				$bobot[$x] 	= $row['bobot'];
				
				// Load Data Sub Tahap Kesiapan Pelaksanaan
				$sqc = "select a.kd_sub_tahap_perencanaan, a.indikator, a.keterangan_dokumen, a.keterangan, a.is_active
							from p_sub_tahap_perencanaan a, p_tahap_perencanaan b
							where a.kd_tahap_perencanaan = b.kd_tahap_perencanaan and 
							a.kd_tahap_perencanaan = '".$kdKriteria."' and a.is_active = '1'
							order by 1";        
				$exc = mysqli_query($connDB, $sqc);
				writeLog(__LINE__, __FILE__, mysqli_error($connDB));
				$i=0;
				while($roc = mysqli_fetch_array($exc, MYSQLI_ASSOC)){
					$i++;
					$kdSubKriteria  = $roc['kd_sub_tahap_perencanaan'];

					$qry = "select a.skor, a.dokumen, a.keterangan, b.nm_parameter, b.harkat
							from lapas_perencanaan_evaluasi a, p_param_sub_tahap_perencanaan b
							where a.kd_parameter = b.kd_parameter and 
							a.kd_sub_tahap_perencanaan = ".$kdSubKriteria." and a.keyNumber = '".$keyNumber."'";
					$run = mysqli_query($connDB, $qry);
					$data = mysqli_fetch_array($run, MYSQLI_ASSOC);
				
				$total[$x] += $data['skor'];
				}

				$totalKeseluruhan += $total[$x];
			}
			?>
			<table id="example" class="table table-bordered table-striped" cellspacing="0" width="100%">
				<tr align="center" style="font-weight: bold;">
					<td>No</td>
					<td>Indikator</td>
					<td>Skor</td>
					<td>Persentase Skor</td>
				</tr>
				<?php
				// Load Data Tahap Kesiapan Pelaksanaan
				$sql = "select kd_tahap_perencanaan, indikator, bobot, is_active
						from p_tahap_perencanaan where is_active = '1' and kd_sektor = '".$sektor."'
						order by 1";        
				$exe = mysqli_query($connDB, $sql);
				writeLog(__LINE__, __FILE__, mysqli_error($connDB));
				$x=0;
				$nilaiPersenEvaluasi = array();
				while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
					$x++;
					$romawi     = romawi($x);

					$nilaiPersenEvaluasi[$x] = ($total[$x] / $totalKeseluruhan) * 100;
					echo '<tr valign="middle">'; 
						echo '<td align="center"><b class="txtBlue">'.$romawi.'</b></td>';
						echo '<td><b>'.strtoupper($row['indikator']).'</b></td>';
						echo '<td  align="right"><b>'.number_format($total[$x],2,',','.').'</b></td>';
						echo '<td  align="right"><b>'.number_format($nilaiPersenEvaluasi[$x],2,',','.').' %</b></td>';
					echo '</tr>';

					$totalPersenEvaluasi += $nilaiPersenEvaluasi[$x];
					$totalEvaluasi += $total[$x];
				}
				echo '<tr valign="middle">'; 
					echo '<td colspan="2" align="right"><b>Total : </b></td>';
					echo '<td align="right"><b>'.number_format($totalEvaluasi,2,',','.').'</b></td>';
					echo '<td align="right"><b>'.number_format($totalPersenEvaluasi,2,',','.').' %</b></td>';
				echo '</tr>';
				?>
			</table>
			<!-- 
			<table class="table">
				<tbody>
					<tr style="background-color: <?=$bgColor?>; color:<?=$color?>">
						<td align="center"><h4><i><?=$penilaian?><br><b>(<?=$totalSkor?>)</b></i></h4></td>
					</tr>
				</tbody>
			</table> 
			-->
		</div>
	</div>
</div> 

<!-- The Bootstrap Image Gallery lightbox, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery">
	<!-- The container for the modal slides -->
	<div class="slides"></div>
	<!-- Controls for the borderless lightbox -->
	<h3 class="title"></h3>
	<a class="close">×</a>
</div>
</body>
</html>