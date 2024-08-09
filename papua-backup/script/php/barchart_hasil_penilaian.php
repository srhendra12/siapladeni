<?php session_start(); error_reporting(0); ?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<div class="container-fluid">
	<div class="row">
		<div class="center-block">
			<div class="row">
				<div class="col-lg-12 col-xs-12">
					<div class='alert alert-info alert-dismissible fade in' role='alert'>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<?php
						echo '<i class="icon fa fa-user"></i> <a style="margin-right:10px; text-decoration:none;">'.str_replace("#access", "<b>".$_SESSION['nmAccess']."</b>", str_replace("#user", "<b>".$_SESSION['username']."</b>", _WELCOME_MSG)).' - '._DESC_WELCOME.' <i class="fa fa-smile-o" aria-hidden="true"></i></a>';
						?>
					</div>
				</div>
			</div>
			
			<?php
			$where = ($_SESSION['access'] == 1) ? "and p.kd_wilayah = ".$_SESSION['wilayah'] : "";
			$sql = "select p.provinsiid from provinsi p join lapas_perencanaan_umum lpu on p.provinsiid = lpu.provinsiid 
			where lpu.tahun_data = '".date('Ym')."' ".$where." group  by 1 order by rand() limit 1";
			$exe = mysqli_query($connDB, $sql);
			writeLog(__LINE__, __FILE__, mysqli_error($connDB));
			$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
			$wilayah = ($_SESSION['access'] == 1 || $_SESSION['access'] == 4) ? $row['provinsiid'] : $_SESSION['propinsi'];

			if($wilayah > 0) :
				$sql = "select k.kabupatenkotaid, k.kabupatenkotaname, p.provinsiname from kabupatenkota k join lapas_perencanaan_umum lpu on k.kabupatenkotaid = lpu.kabupatenkotaid join provinsi p on k.r_provinsiid = p.provinsiid  where k.r_provinsiid = ".$wilayah." group  by 1 order by rand() limit 1";
				$exe = mysqli_query($connDB, $sql);
				writeLog(__LINE__, __FILE__, mysqli_error($connDB));
				$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
				$upt 			= $row['kabupatenkotaid'];
				$nmPropinsi = $row['provinsiname'];
				$nmKota 		= $row['kabupatenkotaname'];
				?>
				<div class="row">
					<div class="panel panel-info">
						<div class="panel-heading">
							<b class="panel-title">Grafik Hasil Evaluasi Deteksi Dini Potensi Gangguan Keamanan Dan Ketertiban <?=strtoupper($nmKota)?> Wilayah <?=strtoupper($nmPropinsi)?> Tahun <?=date('Y')?></b>
						</div>
						<div class="panel-body">

								<?php
								$periode = array();
								$valPersentasePetugas = array();
								$valPerilakuNapi = array();
								$x=0;
								$sql = 'select tahun_data, valPersentasePetugas, valPerilakuNapi
											from lapas_perencanaan_umum
											where provinsiid = '.$wilayah.' and kabupatenkotaid = '.$upt.' and is_close = 1 
											and substring(tahun_data, 1, 4) = '.date('Y').'
											order by 1';
								$exe = mysqli_query($connDB, $sql);
								writeLog(__LINE__, __FILE__, mysqli_error($connDB));
								while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
									$tahun[$x]  = substr($row['tahun_data'], 0,4);
									$bulan[$x]  = getBulan(substr($row['tahun_data'], 5,2));
									$periode[$x] = getBulan(substr($row['tahun_data'], 5,2))." ".$tahun[$x];
									$valPersentasePetugas[$x]  = $row['valPersentasePetugas'];
									$valPerilakuNapi[$x]  = $row['valPerilakuNapi'];
									$x++;
							}

							echo '<script type="text/javascript">';
									echo "
									var ctxB = document.getElementById('barChart').getContext('2d');
									var myBarChart = new Chart(ctxB, {
										type: 'bar',
										data: {
											labels: [";
											for($r=0;$r<$x;$r++){
												$labels .= '"'.$periode[$r].'", ';
											}
											echo substr($labels, 0,-2);  
											echo "],
											datasets: [{
												label: 'Kecenderungan Pemahaman Petugas Terhadap Tupoksi Pemasyarakatan (%)',
												data: [";
												for($i=0;$i<$x;$i++){
														$valPetugas .= '"'.$valPersentasePetugas[$i].'", ';
												}
												echo substr($valPetugas, 0,-2);  
												echo "],
												backgroundColor: [
													'rgba(91, 192, 222, 0.8)',
													'rgba(91, 192, 222, 0.8)',
													'rgba(91, 192, 222, 0.8)',
													'rgba(91, 192, 222, 0.8)',
													'rgba(91, 192, 222, 0.8)',
													'rgba(91, 192, 222, 0.8)',
													'rgba(91, 192, 222, 0.8)',
													'rgba(91, 192, 222, 0.8)',
													'rgba(91, 192, 222, 0.8)',
													'rgba(91, 192, 222, 0.8)',
													'rgba(91, 192, 222, 0.8)',
													'rgba(91, 192, 222, 0.8)',
												],
												borderColor: [
													'rgba(91, 192, 222, 1)',
													'rgba(91, 192, 222, 1)',
													'rgba(91, 192, 222, 1)',
													'rgba(91, 192, 222, 1)',
													'rgba(91, 192, 222, 1)',
													'rgba(91, 192, 222, 1)',
													'rgba(91, 192, 222, 1)',
													'rgba(91, 192, 222, 1)',
													'rgba(91, 192, 222, 1)',
													'rgba(91, 192, 222, 1)',
													'rgba(91, 192, 222, 1)',
													'rgba(91, 192, 222, 1)'
												],
												borderWidth: 1
											},
											{
												label: 'Kecenderungan Perilaku Warga Binaan Pemasyarakatan (%)',
												data: [";
												for($i=0;$i<$x;$i++){
													$valPerilaku .= '"'.$valPerilakuNapi[$i].'", ';
												}
												echo substr($valPerilaku, 0,-2);  
												echo "],
												backgroundColor: [
													'rgba(255, 0, 0, 0.8)',
													'rgba(255, 0, 0, 0.8)',
													'rgba(255, 0, 0, 0.8)',
													'rgba(255, 0, 0, 0.8)',
													'rgba(255, 0, 0, 0.8)',
													'rgba(255, 0, 0, 0.8)',
													'rgba(255, 0, 0, 0.8)',
													'rgba(255, 0, 0, 0.8)',
													'rgba(255, 0, 0, 0.8)',
													'rgba(255, 0, 0, 0.8)',
													'rgba(255, 0, 0, 0.8)',
													'rgba(255, 0, 0, 0.8)'
												],
												borderColor: [
													'rgba(255, 0, 0, 1)',
													'rgba(255, 0, 0, 1)',
													'rgba(255, 0, 0, 1)',
													'rgba(255, 0, 0, 1)',
													'rgba(255, 0, 0, 1)',
													'rgba(255, 0, 0, 1)',
													'rgba(255, 0, 0, 1)',
													'rgba(255, 0, 0, 1)',
													'rgba(255, 0, 0, 1)',
													'rgba(255, 0, 0, 1)',
													'rgba(255, 0, 0, 1)',
													'rgba(255, 0, 0, 1)'
												],
												borderWidth: 1
											}]
										},
										optionss: {
											scales: {
												yAxes: [{
														ticks: {
															beginAtZero:true
														}
												}]
											}
										}
									});";
							echo '</script>';
							?>
							<canvas id="barChart" height="270" width="700"></canvas>
						</div>
					</div>  
				</div> 	
			<?php endif; ?>

		</div>
	</div>
</div> 