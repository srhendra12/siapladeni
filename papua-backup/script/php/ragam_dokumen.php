<?php session_start(); error_reporting(0); ?>
<?php // if(empty($_SESSION['token']) || !isset($_SESSION['token'])) { echo "<script>window.location.href='http://'+ window.location.hostname +'/sig_drainase'</script>"; }?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<div class="center-block">
	<div class="col-md-8">
	    <div class="panel panel-info">
	    	<div class="panel-heading">
				<b class="panel-title">Ragam Dokumen</b>
	        </div>
	        <div class="panel-body">
               <table class="table table-striped">
                  <?php
                  $sql = "select nmFile, keterangan from p_dokumen_pendukung where jenis_dokumen = 2 and isActive = 1 
                        order by kd_dokumen";                  
                  $exe = mysqli_query($connDB, $sql);
                  writeLog(__LINE__, __FILE__, mysqli_error($connDB));
                  $x=0;
                  while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
                     $x++;
                     $keterangan = (!empty($row['keterangan'])) ? " / ".$row['keterangan'] : "";
                     echo '<tr>';
                        echo '<td><b>'.$x.'.</b> <a target="_blank" href="'.BASE_URL.'attachment/dokumen_pendukung/'.$row['nmFile'].'">'.$row['nmFile'].$keterangan.'</td>';
                     echo '</tr>';	
                  }
                  ?>
            </table>
	        </div>
		</div>
	</div>
</div> 