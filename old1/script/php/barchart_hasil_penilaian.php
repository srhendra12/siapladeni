<?php session_start(); error_reporting(0); ?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<script src="<?=BASE_URL?>assets/bootstrap/extend/bootstrap-material-design/js/mdb.min.js"></script>
<?php
$sql = "select min(provinsiid) minID, max(provinsiid) maxID from provinsi";
$exe = mysqli_query($connDB, $sql);
writeLog(__LINE__, __FILE__, mysqli_error($connDB));
$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
$wilayah = $_SESSION['access'] == 1 ? rand($row['minID'], $row['maxID']) : $_SESSION['propinsi'];

$sql = "select min(kabupatenkotaid) minID, max(kabupatenkotaid) maxID from kabupatenkota where r_provinsiid = ".$wilayah;
$exe = mysqli_query($connDB, $sql);
writeLog(__LINE__, __FILE__, mysqli_error($connDB));
$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
$upt = $_SESSION['access'] == 1 ? rand($row['minID'], $row['maxID']) : $_SESSION['kota'];

$sql = 'select c.provinsiname, b.kabupatenkotaname 
        from kabupatenkota b, provinsi c 
        where c.provinsiid = b.r_provinsiid and b.r_provinsiid = '.$wilayah.' and b.kabupatenkotaid = '.$upt;
$exe = mysqli_query($connDB, $sql);
writeLog(__LINE__, __FILE__, mysqli_error($connDB));
$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
$nmPropinsi     = $row['provinsiname'];
$nmKota  		= $row['kabupatenkotaname'];
?>
<div class="container-fluid">
    <div class="row">
        <div class="center-block">
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
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(255, 99, 132, 0.2)'
                                 ],
                                 borderColor: [
                                    'rgba(255,99,132,1)',
                                    'rgba(255,99,132,1)',
                                    'rgba(255,99,132,1)',
                                    'rgba(255,99,132,1)',
                                    'rgba(255,99,132,1)',
                                    'rgba(255,99,132,1)',
                                    'rgba(255,99,132,1)',
                                    'rgba(255,99,132,1)',
                                    'rgba(255,99,132,1)',
                                    'rgba(255,99,132,1)',
                                    'rgba(255,99,132,1)',
                                    'rgba(255,99,132,1)'
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
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                 ],
                                 borderColor: [
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(54, 162, 235, 1)'
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
    </div>
</div> 