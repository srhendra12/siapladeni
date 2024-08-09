<?php session_start(); error_reporting(0); ?>
<?php // if(empty($_SESSION['token']) || !isset($_SESSION['token'])) { echo "<script>window.location.href='http://'+ window.location.hostname +'/sig_drainase'</script>"; }?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<div class="center-block" style="overflow-x:hidden;">
	<div id="exTab3">
		<ul  class="nav nav-pills">
			<li class="active"><a  href="#perencanaan" data-toggle="tab">Kesiapan Pelaksanaan</a></li>
			<li><a href="#pelaksanaan" data-toggle="tab">Pelaksanaan Konstruksi</a></li>
			<li><a href="#pascaKonstruksi" data-toggle="tab">Pasca Konstruksi</a></li>
		</ul>
		
		<?php
		$sql = "select nm_sektor from p_sektor where kd_sektor = '".$_GET['sektor']."'";
		$exe = mysqli_query($connDB, $sql);
		$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		$nmSektor = $row['nm_sektor'];
		?>

		<div class="tab-content clearfix" style="max-width: 94%; ">
			<div class="tab-pane active" id="perencanaan">
				<div class="space"></div>
				<div class="col-md-12">
					<h5 class="txtBlue"><span class="glyphicon glyphicon-triangle-right small" aria-hidden="true"></span> Hasil Evaluasi <span class="txtOrange">Kesiapan Pelaksanaan</span> Sektor <span class="txtOrange"><?=$nmSektor?></span> Tahun <?=$_GET['tahunData']?>:</h5>
		            <table id="example" class="table table-bordered table-striped" cellspacing="0" style="width: 70%">
		            	<thead>
		            		<tr class="isCenter bgGrey">
		            			<td><b>Kategori</b></td>
		            			<td><b>Jumlah</b></td>
		            		</tr>
		            	</thead>
		            	<tbody>
		            		<?php
			                $where = (!empty($_GET['tahunData'])) ? " and a.tahun_data = '".$_GET['tahunData']."'" : " and a.tahun_data = '".date('Y')."'";
			                if (isset($_GET['kota']) and $_GET['kota'] != ''){
			                    $where .= " and a.provinsiid = '".$_GET['prop']."' and a.kabupatenkotaid = '".$_GET['kota']."'";
			                }
			                else if (isset($_GET['prop']) and $_GET['prop'] != ''){
			                    $where .= " and a.provinsiid = '".$_GET['prop']."'";
			                }
			                else{
			                    $where .= ($_SESSION['access'] != 1) ? " and a.provinsiid = '".$_SESSION['propinsi']."'" : "";
			                }

		            		$sql = "select kd_kategori, nmKategori, nilaiBatasBawah, nilaiBatasAtas 
		            				from p_range_tahap_perencanaan where kd_sektor = '".$_GET['sektor']."'
		            				order by kd_kategori";
		            		$exe = mysqli_query($connDB, $sql);
							writeLog(__LINE__, __FILE__, mysqli_error($connDB));
							$x=0;
							$kdKategori = array();
							$nmKategori = array();
							$jumlah 	= array();
		            		while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
		            			$x++;
		            			switch($x){
		            				case 1 : $bgColor = "#5AD3D1"; $color = "#000000"; break;
			        				case 2 : $bgColor = "#FFC870"; $color = "#000000"; break;
			        				case 3 : $bgColor = "#FF5A5E"; $color = "#ffffff"; break;
		            			}

		            			$kdKategori[$x] = $row['kd_kategori'];
		            			$nmKategori[$x] = $row['nmKategori'];
		            			
		            			$qry = "select count(*) jml, a.kdKategoriPenilaian from lapas_perencanaan_umum a
										where a.kd_sektor = '".$_GET['sektor']."' ".$where." and a.kdKategoriPenilaian = '".$kdKategori[$x]."'
										group by 2";
								$run = mysqli_query($connDB, $qry);
		            			$data = mysqli_fetch_array($run, MYSQLI_ASSOC);
								writeLog(__LINE__, __FILE__, mysqli_error($connDB));
								$jumlah[$x] = ($data['jml'] > 0) ? $data['jml'] : 0;

			            		echo '<tr style="background-color:'.$bgColor.'; color:'.$color.'">';
			            			echo '<td>'.$x.'. '.$row['nmKategori'].'</td>';
			            			echo '<td align="right">'.$jumlah[$x].'&nbsp;</td>';
			            		echo '</tr>';

			            		$totalDataPerencanaan += $jumlah[$x];
		            		}
		            		echo '<tr valign="middle">'; 
			                    echo '<td align="right"><b>Total Kegiatan Kesiapan Pelaksanaan : </b></td>';
			                    echo '<td align="right"><b>'.number_format($totalDataPerencanaan,0,',','.').'&nbsp;</b></td>';
			                echo '</tr>';	
		            		?>
		            	</tbody>
		            </table>
		       		<hr>
					<?php
					if($jumlah[2] > 0){
						echo '<table cellspacing="0" cellpadding="0" width="100%">';
							echo '<tr>';
								echo '<td width="53%">';
									echo '<h5 class="txtBlue"><span class="glyphicon glyphicon-triangle-right small" aria-hidden="true"></span> <span class="txtOrange">'.$nmKategori[2].'</span>, karena :</h5>';

									// Load Data Tahap Kesiapan Pelaksanaan
						            $sql = "select kd_tahap_perencanaan, indikator, bobot, is_active
						                    from p_tahap_perencanaan where is_active = '1' and kd_sektor = '".$_GET['sektor']."'
						                    order by 1";        
						            $exe = mysqli_query($connDB, $sql);
						            writeLog(__LINE__, __FILE__, mysqli_error($connDB));
						            $total = array();
						            $x=0;
						            $totalKeseluruhan = 0;
						            while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
						                $x++;
						                $kdKriteria = $row['kd_tahap_perencanaan'];
						                $romawi     = romawi($x);
						                
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
						   
						                    $qry = "select b.skor
													from lapas_perencanaan_umum a, lapas_perencanaan_evaluasi b, p_param_sub_tahap_perencanaan c 
													where a.keyNumber = b.keyNumber and b.kd_parameter = c.kd_parameter 
													and b.kd_sub_tahap_perencanaan = ".$kdSubKriteria." ".$where." and 
													a.kdKategoriPenilaian = '".$kdKategori[2]."'";
						                    $run = mysqli_query($connDB, $qry);
						                    $data = mysqli_fetch_array($run, MYSQLI_ASSOC);
											
											$total[$x] += $data['skor'];
						                }

						                $totalKeseluruhan += $total[$x];
						           	}
						            
						            echo '<table id="example" class="table table-bordered table-striped" cellspacing="0" width="100%">';
						                // Load Data Tahap Kesiapan Pelaksanaan
						                $sql = "select kd_tahap_perencanaan, indikator, bobot, is_active
						                        from p_tahap_perencanaan where is_active = '1' and kd_sektor = '".$_GET['sektor']."'
						                        order by 1";        
						                $exe = mysqli_query($connDB, $sql);
						                writeLog(__LINE__, __FILE__, mysqli_error($connDB));
						                $x=0;
						                $nilaiEvaluasi = array();
						                $totalEvaluasi = 0;
						                $indikatorPerencanaan = array();
						                while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
						                    $x++;
						                    $romawi     = romawi($x);

						                    $nilaiEvaluasi[$x] = ($total[$x] / $totalKeseluruhan) * 100;
						                    $indikatorPerencanaan[$x] = strtoupper($row['indikator']);
						                    echo '<tr valign="middle">'; 
						                        echo '<td align="center"><b class="txtBlue">'.$romawi.'</b></td>';
						                        echo '<td colspan="2"><b>'.strtoupper($row['indikator']).'</b></td>';
						                        echo '<td  align="right"><b>'.number_format($nilaiEvaluasi[$x],2,',','.').' %</b></td>';
						                    echo '</tr>';

						                    $totalEvaluasi += $nilaiEvaluasi[$x];
						                }
						                echo '<tr valign="middle">'; 
						                    echo '<td colspan="3" align="right"><b>Total : </b></td>';
						                    echo '<td align="right"><b>'.number_format($totalEvaluasi,2,',','.').' %</b></td>';
						                echo '</tr>';
						            echo '</table>';
					        	echo '</td>';
					        	echo '<td align="center">';
										echo '<script type="text/javascript">';
											echo 'var ctxP = document.getElementById("pieChart2").getContext("2d");';
										    echo 'var myPieChart = new Chart(ctxP, {';
										        echo 'type: "pie",';
										        echo 'data: {';
										            echo 'labels: [';
										            	for($i=1;$i<=$x;$i++){
										            		echo '"'.$indikatorPerencanaan[$i].'",';
										            	}
														echo '],';
										            echo 'datasets: [{';
										                echo 'data: [';
										                	for($i=1;$i<=$x;$i++){
											            		echo '"'.number_format($nilaiEvaluasi[$i],2,'.',',').'",';
											            	}
										                echo '],';
										                echo 'backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360"],';
										                echo 'hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774"]';
										            echo '}]';
										        echo '},';
										        echo 'options: {';
										            echo 'responsive: true,';
										        echo '}';
										    echo '});';
										echo '</script>';
									echo '<canvas id="pieChart2" height="150"></canvas>';
					        	echo '</td>';
					   		echo '</tr>';
					   	echo '</table>';
					}
					echo "<hr>";
					if($jumlah[3] > 0){
						echo '<table cellspacing="0" cellpadding="0" width="100%">';
							echo '<tr>';
								echo '<td width="53%" valign="top">';

									echo '<h5 class="txtBlue"><span class="glyphicon glyphicon-triangle-right small" aria-hidden="true"></span> <span class="txtOrange">'.$nmKategori[3].'</span>, karena :</h5>';

									// Load Data Tahap Kesiapan Pelaksanaan
						            $sql = "select kd_tahap_perencanaan, indikator, bobot, is_active
						                    from p_tahap_perencanaan where is_active = '1' and kd_sektor = '".$_GET['sektor']."'
						                    order by 1";        
						            $exe = mysqli_query($connDB, $sql);
						            writeLog(__LINE__, __FILE__, mysqli_error($connDB));
						            $total = array();
						            $x=0;
						            $totalKeseluruhan = 0;
						            while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
						                $x++;
						                $kdKriteria = $row['kd_tahap_perencanaan'];
						                $romawi     = romawi($x);
					                
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
						   
						                    $qry = "select b.skor
													from lapas_perencanaan_umum a, lapas_perencanaan_evaluasi b, p_param_sub_tahap_perencanaan c 
													where a.keyNumber = b.keyNumber and b.kd_parameter = c.kd_parameter 
													and b.kd_sub_tahap_perencanaan = ".$kdSubKriteria." ".$where." and 
													a.kdKategoriPenilaian = '".$kdKategori[3]."'";
						                    $run = mysqli_query($connDB, $qry);
						                    $data = mysqli_fetch_array($run, MYSQLI_ASSOC);
											
											$total[$x] += $data['skor'];
						                }

						                $totalKeseluruhan += $total[$x];
						           	}
						            
						            echo '<table id="example" class="table table-bordered table-striped" cellspacing="0" width="100%">';
						                // Load Data Tahap Kesiapan Pelaksanaan
						                $sql = "select kd_tahap_perencanaan, indikator, bobot, is_active
						                        from p_tahap_perencanaan where is_active = '1' and kd_sektor = '".$_GET['sektor']."'
						                        order by 1";        
						                $exe = mysqli_query($connDB, $sql);
						                writeLog(__LINE__, __FILE__, mysqli_error($connDB));
						                $x=0;
						                $nilaiEvaluasi = array();
						                $totalEvaluasi = 0;
						                $indikatorPerencanaan = array();
						                while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
						                    $x++;
						                    $romawi     = romawi($x);

						                    $nilaiEvaluasi[$x] = ($total[$x] / $totalKeseluruhan) * 100;
											$indikatorPerencanaan[$x] = strtoupper($row['indikator']);
											echo '<tr valign="middle">'; 
						                        echo '<td align="center"><b class="txtBlue">'.$romawi.'</b></td>';
						                        echo '<td colspan="2"><b>'.strtoupper($row['indikator']).'</b></td>';
						                        echo '<td  align="right"><b>'.number_format($nilaiEvaluasi[$x],2,',','.').' %</b></td>';
						                    echo '</tr>';

						                    $totalEvaluasi += $nilaiEvaluasi[$x];
						                }
						                echo '<tr valign="middle">'; 
						                    echo '<td colspan="3" align="right"><b>Total : </b></td>';
						                    echo '<td align="right"><b>'.number_format($totalEvaluasi,2,',','.').' %</b></td>';
						                echo '</tr>';
						            echo '</table>';
						    	echo '</td>';
					        	echo '<td align="center">';
										echo '<script type="text/javascript">';
											echo 'var ctxP = document.getElementById("pieChart3").getContext("2d");';
										    echo 'var myPieChart = new Chart(ctxP, {';
										        echo 'type: "pie",';
										        echo 'data: {';
										            echo 'labels: [';
										            	for($i=1;$i<=$x;$i++){
										            		echo '"'.$indikatorPerencanaan[$i].'",';
										            	}
														echo '],';
										            echo 'datasets: [{';
										                echo 'data: [';
										                	for($i=1;$i<=$x;$i++){
											            		echo '"'.number_format($nilaiEvaluasi[$i],2,'.',',').'",';
											            	}
										                echo '],';
										                echo 'backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360"],';
										                echo 'hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774"]';
										            echo '}]';
										        echo '},';
										        echo 'options: {';
										            echo 'responsive: true';
										        echo '}';
										    echo '});';
										echo '</script>';
									echo '<canvas id="pieChart3" height="150"></canvas>';
					        	echo '</td>';
					   		echo '</tr>';
					   	echo '</table>';
					}				
					?>
					<div class="space"></div>
				</div>
			</div>

			<div class="tab-pane " id="pelaksanaan">
				<div class="space"></div>
				<div class="col-md-12">
					<h5 class="txtBlue"><span class="glyphicon glyphicon-triangle-right small" aria-hidden="true"></span> Hasil Evaluasi <span class="txtOrange">Pelaksanaan Konstruksi</span> Sektor <span class="txtOrange"><?=$nmSektor?></span> Tahun <?=$_GET['tahunData']?>:</h5>
		            <table id="example" class="table table-bordered table-striped" cellspacing="0" style="width: 70%">
		            	<thead>
		            		<tr class="isCenter bgGrey">
		            			<td><b>Kategori</b></td>
		            			<td><b>Jumlah</b></td>
		            		</tr>
		            	</thead>
		            	<tbody>
		            		<?php
		            		
			                $where = (!empty($_GET['tahunData'])) ? " and a.tahun_data = '".$_GET['tahunData']."'" : " and a.tahun_data = '".date('Y')."'";
			                if (isset($_GET['kota']) and $_GET['kota'] != ''){
			                    $where .= " and a.provinsiid = '".$_GET['prop']."' and a.kabupatenkotaid = '".$_GET['kota']."'";
			                }
			                else if (isset($_GET['prop']) and $_GET['prop'] != ''){
			                    $where .= " and a.provinsiid = '".$_GET['prop']."'";
			                }
			                else{
			                    $where .= ($_SESSION['access'] != 1) ? " and a.provinsiid = '".$_SESSION['propinsi']."'" : "";
			                }

		            		$sql = "select kd_kategori, nmKategori, nilaiBatasBawah, nilaiBatasAtas 
		            				from p_range_penilaian_pelaksanaan where kd_sektor = '".$_GET['sektor']."'
		            				order by kd_kategori";
		            		$exe = mysqli_query($connDB, $sql);
							writeLog(__LINE__, __FILE__, mysqli_error($connDB));
							$x=0;
							$kdKategori = array();
							$nmKategori = array();
							$jumlah 	= array();
		            		while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
		            			$x++;
		            			switch($x){
		            				case 1 : $bgColor = "#5AD3D1"; $color = "#000000"; break;
                            		case 2 : $bgColor = "#FFC870"; $color = "#000000"; break;
                            		case 3 : $bgColor = "#FF5A5E"; $color = "#ffffff"; break;
		            			}

		            			$kdKategori[$x] = $row['kd_kategori'];
		            			$nmKategori[$x] = $row['nmKategori'];

		            			$qry = "select count(*) jml, a.kdKategoriPenilaian from monev_pelaksanaan_umum a
										where a.kd_sektor = '".$_GET['sektor']."' ".$where." and a.kdKategoriPenilaian = '".$kdKategori[$x]."'
										group by 2";
								$run = mysqli_query($connDB, $qry);
		            			$data = mysqli_fetch_array($run, MYSQLI_ASSOC);
								writeLog(__LINE__, __FILE__, mysqli_error($connDB));
								$jumlah[$x] = ($data['jml'] > 0) ? $data['jml'] : 0;

			            		echo '<tr style="background-color:'.$bgColor.'; color:'.$color.'">';
			            			echo '<td>'.$x.'. '.$row['nmKategori'].'</td>';
			            			echo '<td align="right">'.$jumlah[$x].'&nbsp;</td>';
			            		echo '</tr>';

			            		$totalDataPelaksanaan += $jumlah[$x];
		            		}
		            		echo '<tr valign="middle">'; 
			                    echo '<td align="right"><b>Total Kegiatan Pelaksanaan Konstruksi : </b></td>';
			                    echo '<td align="right"><b>'.number_format($totalDataPelaksanaan,0,',','.').'&nbsp;</b></td>';
			                echo '</tr>';	
		            		?>
		            	</tbody>
		            </table>
		            <hr>
					<?php
					if($jumlah[2] > 0){
						echo '<table cellspacing="0" cellpadding="0" width="100%">';
							echo '<tr>';
								echo '<td width="53%">';
									echo '<h5 class="txtBlue"><span class="glyphicon glyphicon-triangle-right small" aria-hidden="true"></span> <span class="txtOrange">'.$nmKategori[2].'</span>, karena :</h5>';

			            			// Load Data Tahap Pelaksanaan Konstruksi
						            $sql = "select kd_pelaksanaan, indikator, bobot, is_active
						                    from p_pelaksanaan_konstruksi where is_active = '1' and kd_sektor = '".$_GET['sektor']."'
						                    order by 1";   
						            $exe = mysqli_query($connDB, $sql);
						            writeLog(__LINE__, __FILE__, mysqli_error($connDB));
						            $total = array();
						            $x=0;
						            $totalKeseluruhan = 0;
						            while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
						                $x++;
						                $kdKriteria = $row['kd_pelaksanaan'];
						                $romawi     = romawi($x);
						                
										// Load Data Sub Tahap Pelaksanaan Konstruksi
										$sqc = "select a.kd_sub_pelaksanaan, a.indikator, a.keterangan_dokumen, a.keterangan, a.is_active
						                        from p_sub_pelaksanaan_konstruksi a, p_pelaksanaan_konstruksi b
						                        where a.kd_pelaksanaan = b.kd_pelaksanaan and a.kd_pelaksanaan = '".$kdKriteria."' and 
						                        a.is_active = '1' order by 1";  
						                $exc = mysqli_query($connDB, $sqc);
						                writeLog(__LINE__, __FILE__, mysqli_error($connDB));
						                $i=0;
						                while($roc = mysqli_fetch_array($exc, MYSQLI_ASSOC)){
						                    $i++;
						                    $kdSubKriteria  = $roc['kd_sub_pelaksanaan'];
								   
						                    $qry = "select b.skor
													from monev_pelaksanaan_umum a, monev_pelaksanaan_evaluasi b, p_param_sub_pelaksanaan c 
													where a.keyNumber = b.keyNumber and b.kd_parameter = c.kd_parameter 
													and b.kd_sub_tahap_pelaksanaan = ".$kdSubKriteria." ".$where." and 
													a.kdKategoriPenilaian = '".$kdKategori[2]."'";
						                    $run = mysqli_query($connDB, $qry);
						                    $data = mysqli_fetch_array($run, MYSQLI_ASSOC);
											
											$total[$x] += $data['skor'];
						                }

						                $totalKeseluruhan += $total[$x];
						           	}
						            
						            echo '<table id="example" class="table table-bordered table-striped" cellspacing="0" width="100%">';
			                			// Load Data Tahap Pelaksanaan Konstruksi
						                $sql = "select kd_pelaksanaan, indikator, bobot, is_active
							                    from p_pelaksanaan_konstruksi where is_active = '1' and kd_sektor = '".$_GET['sektor']."'
							                    order by 1";        
						                $exe = mysqli_query($connDB, $sql);
						                writeLog(__LINE__, __FILE__, mysqli_error($connDB));
						                $x=0;
						                $nilaiEvaluasi = array();
						                $totalEvaluasi = 0;
										$indikatorPelaksanaan = array();
										while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
						                    $x++;
						                    $romawi     = romawi($x);

						                    $nilaiEvaluasi[$x] = ($total[$x] / $totalKeseluruhan) * 100;
						                    $indikatorPelaksanaan[$x] = strtoupper($row['indikator']);
						                    echo '<tr valign="middle">'; 
						                        echo '<td align="center"><b class="txtBlue">'.$romawi.'</b></td>';
						                        echo '<td colspan="2"><b>'.strtoupper($row['indikator']).'</b></td>';
						                        echo '<td  align="right"><b>'.number_format($nilaiEvaluasi[$x],2,',','.').' %</b></td>';
						                    echo '</tr>';

						                    $totalEvaluasi += $nilaiEvaluasi[$x];
						                }
						                echo '<tr valign="middle">'; 
						                    echo '<td colspan="3" align="right"><b>Total : </b></td>';
						                    echo '<td align="right"><b>'.number_format($totalEvaluasi,2,',','.').' %</b></td>';
						                echo '</tr>';
						            echo '</table>';
						       	echo '</td>';
					        	echo '<td align="center">';
										echo '<script type="text/javascript">';
											echo 'var ctxP = document.getElementById("pieChart4").getContext("2d");';
										    echo 'var myPieChart = new Chart(ctxP, {';
										        echo 'type: "pie",';
										        echo 'data: {';
										            echo 'labels: [';
										            	for($i=1;$i<=$x;$i++){
										            		echo '"'.$indikatorPelaksanaan[$i].'",';
										            	}
														echo '],';
										            echo 'datasets: [{';
										                echo 'data: [';
										                	for($i=1;$i<=$x;$i++){
											            		echo '"'.number_format($nilaiEvaluasi[$i],2,'.',',').'",';
											            	}
										                echo '],';
										                echo 'backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360"],';
										                echo 'hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774"]';
										            echo '}]';
										        echo '},';
										        echo 'options: {';
										            echo 'responsive: true';
										        echo '}';
										    echo '});';
										echo '</script>';
									echo '<canvas id="pieChart4" height="150"></canvas>';
					        	echo '</td>';
					   		echo '</tr>';
					   	echo '</table>';
					}
					echo "<hr>";
					if($jumlah[3] > 0){
						echo '<table cellspacing="0" cellpadding="0" width="100%">';
							echo '<tr>';
								echo '<td width="53%">';
									echo '<h5 class="txtBlue"><span class="glyphicon glyphicon-triangle-right small" aria-hidden="true"></span> <span class="txtOrange">'.$nmKategori[3].'</span>, karena :</h5>';

			            			// Load Data Tahap Pelaksanaan Konstruksi
						            $sql = "select kd_pelaksanaan, indikator, bobot, is_active
						                    from p_pelaksanaan_konstruksi where is_active = '1' and kd_sektor = '".$_GET['sektor']."'
						                    order by 1";   
						            $exe = mysqli_query($connDB, $sql);
						            writeLog(__LINE__, __FILE__, mysqli_error($connDB));
						            $total = array();
						            $x=0;
						            $totalKeseluruhan = 0;
						            while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
						                $x++;
						                $kdKriteria = $row['kd_pelaksanaan'];
						                $romawi     = romawi($x);
						                $bobot[$x] 	= $row['bobot'];
						                
										// Load Data Sub Tahap Pelaksanaan Konstruksi
										$sqc = "select a.kd_sub_pelaksanaan, a.indikator, a.keterangan_dokumen, a.keterangan, a.is_active
						                        from p_sub_pelaksanaan_konstruksi a, p_pelaksanaan_konstruksi b
						                        where a.kd_pelaksanaan = b.kd_pelaksanaan and a.kd_pelaksanaan = '".$kdKriteria."' and 
						                        a.is_active = '1' order by 1";  
						                $exc = mysqli_query($connDB, $sqc);
						                writeLog(__LINE__, __FILE__, mysqli_error($connDB));
						                $i=0;
						                while($roc = mysqli_fetch_array($exc, MYSQLI_ASSOC)){
						                    $i++;
						                    $kdSubKriteria  = $roc['kd_sub_pelaksanaan'];
								   
						                    $qry = "select b.skor
													from monev_pelaksanaan_umum a, monev_pelaksanaan_evaluasi b, p_param_sub_pelaksanaan c 
													where a.keyNumber = b.keyNumber and b.kd_parameter = c.kd_parameter 
													and b.kd_sub_tahap_pelaksanaan = ".$kdSubKriteria." ".$where." and 
													a.kdKategoriPenilaian = '".$kdKategori[3]."'";
						                    $run = mysqli_query($connDB, $qry);
						                    $data = mysqli_fetch_array($run, MYSQLI_ASSOC);
											
											$total[$x] += $data['skor'];
						                }

						                $totalKeseluruhan += $total[$x];
						           	}
						            
						            echo '<table id="example" class="table table-bordered table-striped" cellspacing="0" width="100%">';
			                			// Load Data Tahap Pelaksanaan Konstruksi
						                $sql = "select kd_pelaksanaan, indikator, bobot, is_active
							                    from p_pelaksanaan_konstruksi where is_active = '1' and kd_sektor = '".$_GET['sektor']."'
							                    order by 1";        
						                $exe = mysqli_query($connDB, $sql);
						                writeLog(__LINE__, __FILE__, mysqli_error($connDB));
						                $x=0;
						                $nilaiEvaluasi = array();
						                $totalEvaluasi = 0;
						                $indikatorPelaksanaan = array();
						                while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
						                    $x++;
						                    $romawi     = romawi($x);

						                    $nilaiEvaluasi[$x] = ($total[$x] / $totalKeseluruhan) * 100;
											$indikatorPelaksanaan[$x] = strtoupper($row['indikator']);
											echo '<tr valign="middle">'; 
						                        echo '<td align="center"><b class="txtBlue">'.$romawi.'</b></td>';
						                        echo '<td colspan="2"><b>'.strtoupper($row['indikator']).'</b></td>';
						                        echo '<td  align="right"><b>'.number_format($nilaiEvaluasi[$x],2,',','.').' %</b></td>';
						                    echo '</tr>';

						                    $totalEvaluasi += $nilaiEvaluasi[$x];
						                }
						                echo '<tr valign="middle">'; 
						                    echo '<td colspan="3" align="right"><b>Total : </b></td>';
						                    echo '<td align="right"><b>'.number_format($totalEvaluasi,2,',','.').' %</b></td>';
						                echo '</tr>';
						            echo '</table>';
						        echo '</td>';
					        	echo '<td align="center">';
										echo '<script type="text/javascript">';
											echo 'var ctxP = document.getElementById("pieChart5").getContext("2d");';
										    echo 'var myPieChart = new Chart(ctxP, {';
										        echo 'type: "pie",';
										        echo 'data: {';
										            echo 'labels: [';
										            	for($i=1;$i<=$x;$i++){
										            		echo '"'.$indikatorPelaksanaan[$i].'",';
										            	}
														echo '],';
										            echo 'datasets: [{';
										                echo 'data: [';
										                	for($i=1;$i<=$x;$i++){
											            		echo '"'.number_format($nilaiEvaluasi[$i],2,'.',',').'",';
											            	}
										                echo '],';
										                echo 'backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360"],';
										                echo 'hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774"]';
										            echo '}]';
										        echo '},';
										        echo 'options: {';
										            echo 'responsive: true';
										        echo '}';
										    echo '});';
										echo '</script>';
									echo '<canvas id="pieChart5" height="150"></canvas>';
					        	echo '</td>';
					   		echo '</tr>';
					   	echo '</table>';
					}
					?>
					<div class="space"></div>
				</div>
			</div>

			<div class="tab-pane" id="pascaKonstruksi">
				<div class="space"></div>
				<div class="col-md-12">
					<h5 class="txtBlue"><span class="glyphicon glyphicon-triangle-right small" aria-hidden="true"></span> Hasil Evaluasi <span class="txtOrange">Pasca Konstruksi</span> Sektor <span class="txtOrange"><?=$nmSektor?></span> Tahun <?=$_GET['tahunData']?>:</h5>
		            <table id="example" class="table table-bordered table-striped" cellspacing="0" style="width: 70%">
		            	<thead>
		            		<tr class="isCenter bgGrey">
		            			<td><b>Kategori</b></td>
		            			<td><b>Jumlah</b></td>
		            		</tr>
		            	</thead>
		            	<tbody>
		            		<?php
		            		
			                $where = (!empty($_GET['tahunData'])) ? " and a.tahun_data = '".$_GET['tahunData']."'" : " and a.tahun_data = '".date('Y')."'";
			                if (isset($_GET['kota']) and $_GET['kota'] != ''){
			                    $where .= " and a.provinsiid = '".$_GET['prop']."' and a.kabupatenkotaid = '".$_GET['kota']."'";
			                }
			                else if (isset($_GET['prop']) and $_GET['prop'] != ''){
			                    $where .= " and a.provinsiid = '".$_GET['prop']."'";
			                }
			                else{
			                    $where .= ($_SESSION['access'] != 1) ? " and a.provinsiid = '".$_SESSION['propinsi']."'" : "";
			                }

		            		$sql = "select kd_kategori, nmKategori, nilaiBatasBawah, nilaiBatasAtas 
		            				from p_range_pasca_konstruksi where kd_sektor = '".$_GET['sektor']."'
		            				order by kd_kategori";
		            		$exe = mysqli_query($connDB, $sql);
							writeLog(__LINE__, __FILE__, mysqli_error($connDB));
							$x=0;
							$kdKategori = array();
							$nmKategori = array();
							$jumlah 	= array();
		            		while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
		            			$x++;
		            			switch($x){
		            				case 1 : $bgColor = "#5AD3D1"; $color = "#000000"; break;
                            		case 2 : $bgColor = "#FFC870"; $color = "#000000"; break;
                            		case 3 : $bgColor = "#FF5A5E"; $color = "#ffffff"; break;
		            			}

		            			$kdKategori[$x] = $row['kd_kategori'];
		            			$nmKategori[$x] = $row['nmKategori'];

		            			$qry = "select count(*) jml, a.kdKategoriPenilaian from monev_pascakonstruksi_umum a
										where a.kd_sektor = '".$_GET['sektor']."' ".$where." and a.kdKategoriPenilaian = '".$kdKategori[$x]."'
										group by 2";
								$run = mysqli_query($connDB, $qry);
		            			$data = mysqli_fetch_array($run, MYSQLI_ASSOC);
								writeLog(__LINE__, __FILE__, mysqli_error($connDB));
								$jumlah[$x] = ($data['jml'] > 0) ? $data['jml'] : 0;

			            		echo '<tr style="background-color:'.$bgColor.'; color:'.$color.'">';
			            			echo '<td>'.$x.'. '.$row['nmKategori'].'</td>';
			            			echo '<td align="right">'.$jumlah[$x].'&nbsp;</td>';
			            		echo '</tr>';

			            		$totalDataPascaKonstruksi += $jumlah[$x];
		            		}
		            		echo '<tr valign="middle">'; 
			                    echo '<td align="right"><b>Total Kegiatan Pasca Konstruksi : </b></td>';
			                    echo '<td align="right"><b>'.number_format($totalDataPascaKonstruksi,0,',','.').'&nbsp;</b></td>';
			                echo '</tr>';	
		            		?>
		            	</tbody>
		            </table>
					<hr>
					<?php
					if($jumlah[2] > 0){
						echo '<table cellspacing="0" cellpadding="0" width="100%">';
							echo '<tr>';
								echo '<td width="53%">';
									echo '<h5 class="txtBlue"><span class="glyphicon glyphicon-triangle-right small" aria-hidden="true"></span> <span class="txtOrange">'.$nmKategori[2].'</span>, karena :</h5>';

						            // Load Data Pasca Konstruksi
			        				$sql = "select kd_pasca_konstruksi, indikator, bobot, is_active
						                    from p_evaluasi_pasca_konstruksi where kd_kriteria = '5' and is_active = '1' and 
						                    kd_sektor = '".$_GET['sektor']."' order by 1";
						            $exe = mysqli_query($connDB, $sql);
						            writeLog(__LINE__, __FILE__, mysqli_error($connDB));
						            $total = array();
						            $x=0;
						            $totalKeseluruhan = 0;
						            while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
						                $x++;
						                $kdKriteria = $row['kd_pasca_konstruksi'];
						                $romawi     = romawi($x);

										// Load Data Sub Pasca Konstruksi
					           			$sqc = "select a.kd_sub_pasca_konstruksi, a.indikator, a.keterangan_dokumen, a.keterangan, a.is_active
					                    from p_sub_evaluasi_pasca_konstruksi a, p_evaluasi_pasca_konstruksi b
					                    where a.kd_pasca_konstruksi = b.kd_pasca_konstruksi and 
					                    a.kd_pasca_konstruksi = '".$kdKriteria."' and a.is_active = '1'
					                    order by 1";        
						                $exc = mysqli_query($connDB, $sqc);
						                writeLog(__LINE__, __FILE__, mysqli_error($connDB));
						                $i=0;
						                while($roc = mysqli_fetch_array($exc, MYSQLI_ASSOC)){
						                    $i++;
						                    $kdSubKriteria  = $roc['kd_sub_pasca_konstruksi'];

						                    $qry = "select b.skor
													from monev_pascakonstruksi_umum a, monev_pascakonstruksi_evaluasi b, 
													p_param_sub_evaluasi_pasca_konstruksi c 
													where a.keyNumber = b.keyNumber and b.kd_parameter = c.kd_parameter 
													and b.kd_sub_pasca_konstruksi = ".$kdSubKriteria." ".$where." and 
													a.kdKategoriPenilaian = '".$kdKategori[2]."'";
						                    $run = mysqli_query($connDB, $qry);
						                    $data = mysqli_fetch_array($run, MYSQLI_ASSOC);
											
											$total[$x] += $data['skor'];
						                }

						                $totalKeseluruhan += $total[$x];
						           	}
						            
						            echo '<table id="example" class="table table-bordered table-striped" cellspacing="0" width="100%">';
			                			// Load Data Pasca Konstruksi
			        					$sql = "select kd_pasca_konstruksi, indikator, bobot, is_active
						                    from p_evaluasi_pasca_konstruksi where kd_kriteria = '5' and is_active = '1' and 
						                    kd_sektor = '".$_GET['sektor']."' order by 1";            
						                $exe = mysqli_query($connDB, $sql);
						                writeLog(__LINE__, __FILE__, mysqli_error($connDB));
						                $x=0;
						                $nilaiEvaluasi = array();
						                $totalEvaluasi = 0;
						                $indikatorPascaKonstruksi = array();
						                while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
						                    $x++;
						                    $romawi     = romawi($x);

						                    $nilaiEvaluasi[$x] = ($total[$x] / $totalKeseluruhan) * 100;
						                    $indikatorPascaKonstruksi[$x] = strtoupper($row['indikator']);
						                    echo '<tr valign="middle">'; 
						                        echo '<td align="center"><b class="txtBlue">'.$romawi.'</b></td>';
						                        echo '<td colspan="2"><b>'.strtoupper($row['indikator']).'</b></td>';
						                        echo '<td  align="right"><b>'.number_format($nilaiEvaluasi[$x],2,',','.').' %</b></td>';
						                    echo '</tr>';

						                    $totalEvaluasi += $nilaiEvaluasi[$x];
						                }
						                echo '<tr valign="middle">'; 
						                    echo '<td colspan="3" align="right"><b>Total : </b></td>';
						                    echo '<td align="right"><b>'.number_format($totalEvaluasi,2,',','.').' %</b></td>';
						                echo '</tr>';
						            echo '</table>';
						        echo '</td>';
					        	echo '<td align="center">';
										echo '<script type="text/javascript">';
											echo 'var ctxP = document.getElementById("pieChart6").getContext("2d");';
										    echo 'var myPieChart = new Chart(ctxP, {';
										        echo 'type: "pie",';
										        echo 'data: {';
										            echo 'labels: [';
										            	for($i=1;$i<=$x;$i++){
										            		echo '"'.$indikatorPascaKonstruksi[$i].'",';
										            	}
														echo '],';
										            echo 'datasets: [{';
										                echo 'data: [';
										                	for($i=1;$i<=$x;$i++){
											            		echo '"'.number_format($nilaiEvaluasi[$i],2,'.',',').'",';
											            	}
										                echo '],';
										                echo 'backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360"],';
										                echo 'hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774"]';
										            echo '}]';
										        echo '},';
										        echo 'options: {';
										            echo 'responsive: true';
										        echo '}';
										    echo '});';
										echo '</script>';
									echo '<canvas id="pieChart6" height="150"></canvas>';
					        	echo '</td>';
					   		echo '</tr>';
					   	echo '</table>';
					}
					echo '<hr>';
					if($jumlah[3] > 0){
						echo '<table cellspacing="0" cellpadding="0" width="100%">';
							echo '<tr>';
								echo '<td width="53%">';
									echo '<h5 class="txtBlue"><span class="glyphicon glyphicon-triangle-right small" aria-hidden="true"></span> <span class="txtOrange">'.$nmKategori[3].'</span>, karena :</h5>';

						            // Load Data Pasca Konstruksi
			        				$sql = "select kd_pasca_konstruksi, indikator, bobot, is_active
						                    from p_evaluasi_pasca_konstruksi where kd_kriteria = '5' and is_active = '1' and 
						                    kd_sektor = '".$_GET['sektor']."' order by 1";      
						            $exe = mysqli_query($connDB, $sql);
						            writeLog(__LINE__, __FILE__, mysqli_error($connDB));
						            $total = array();
						            $x=0;
						            $totalKeseluruhan = 0;
						            while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
						                $x++;
						                $kdKriteria = $row['kd_pasca_konstruksi'];
						                $romawi     = romawi($x);
						                
										// Load Data Sub Pasca Konstruksi
					           			$sqc = "select a.kd_sub_pasca_konstruksi, a.indikator, a.keterangan_dokumen, a.keterangan, a.is_active
					                    from p_sub_evaluasi_pasca_konstruksi a, p_evaluasi_pasca_konstruksi b
					                    where a.kd_pasca_konstruksi = b.kd_pasca_konstruksi and 
					                    a.kd_pasca_konstruksi = '".$kdKriteria."' and a.is_active = '1'
					                    order by 1";        
						                $exc = mysqli_query($connDB, $sqc);
						                writeLog(__LINE__, __FILE__, mysqli_error($connDB));
						                $i=0;
						                while($roc = mysqli_fetch_array($exc, MYSQLI_ASSOC)){
						                    $i++;
						                    $kdSubKriteria  = $roc['kd_sub_pasca_konstruksi'];
								   
						                    $qry = "select b.skor
													from monev_pascakonstruksi_umum a, monev_pascakonstruksi_evaluasi b, 
													p_param_sub_evaluasi_pasca_konstruksi c 
													where a.keyNumber = b.keyNumber and b.kd_parameter = c.kd_parameter 
													and b.kd_sub_pasca_konstruksi = ".$kdSubKriteria." ".$where." and 
													a.kdKategoriPenilaian = '".$kdKategori[3]."'";
						                    $run = mysqli_query($connDB, $qry);
						                    $data = mysqli_fetch_array($run, MYSQLI_ASSOC);
											
											$total[$x] += $data['skor'];
						                }

						                $totalKeseluruhan += $total[$x];
						           	}
						            
						            echo '<table id="example" class="table table-bordered table-striped" cellspacing="0" width="100%">';
			                			// Load Data Pasca Konstruksi
			        					$sql = "select kd_pasca_konstruksi, indikator, bobot, is_active
						                    from p_evaluasi_pasca_konstruksi where kd_kriteria = '5' and is_active = '1' and 
						                    kd_sektor = '".$_GET['sektor']."' order by 1";            
						                $exe = mysqli_query($connDB, $sql);
						                writeLog(__LINE__, __FILE__, mysqli_error($connDB));
						                $x=0;
						                $nilaiEvaluasi = array();
						                $totalEvaluasi = 0;
						                $indikatorPascaKonstruksi = array();
						                while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
						                    $x++;
						                    $romawi     = romawi($x);

						                    $nilaiEvaluasi[$x] = ($total[$x] / $totalKeseluruhan) * 100;
						                    $indikatorPascaKonstruksi[$x] = strtoupper($row['indikator']);
						                    echo '<tr valign="middle">'; 
						                        echo '<td align="center"><b class="txtBlue">'.$romawi.'</b></td>';
						                        echo '<td colspan="2"><b>'.strtoupper($row['indikator']).'</b></td>';
						                        echo '<td  align="right"><b>'.number_format($nilaiEvaluasi[$x],2,',','.').' %</b></td>';
						                    echo '</tr>';

						                    $totalEvaluasi += $nilaiEvaluasi[$x];
						                }
						                echo '<tr valign="middle">'; 
						                    echo '<td colspan="3" align="right"><b>Total : </b></td>';
						                    echo '<td align="right"><b>'.number_format($totalEvaluasi,2,',','.').' %</b></td>';
						                echo '</tr>';
						            echo '</table>';
						        echo '</td>';
					        	echo '<td align="center">';
										echo '<script type="text/javascript">';
											echo 'var ctxP = document.getElementById("pieChart7").getContext("2d");';
										    echo 'var myPieChart = new Chart(ctxP, {';
										        echo 'type: "pie",';
										        echo 'data: {';
										            echo 'labels: [';
										            	for($i=1;$i<=$x;$i++){
										            		echo '"'.$indikatorPascaKonstruksi[$i].'",';
										            	}
														echo '],';
										            echo 'datasets: [{';
										                echo 'data: [';
										                	for($i=1;$i<=$x;$i++){
											            		echo '"'.number_format($nilaiEvaluasi[$i],2,'.',',').'",';
											            	}
										                echo '],';
										                echo 'backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360"],';
										                echo 'hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774"]';
										            echo '}]';
										        echo '},';
										        echo 'options: {';
										            echo 'responsive: true';
										        echo '}';
										    echo '});';
										echo '</script>';
									echo '<canvas id="pieChart7" height="150"></canvas>';
					        	echo '</td>';
					   		echo '</tr>';
					   	echo '</table>';
					}
					?>
					<div class="space"></div>
				</div>
			</div>
		</div>
	</div>
</div> 
