<?php session_start(); error_reporting(0); ?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<div class="center-block">
	<div class="col-md-12">
	    <div class="panel panel-info">
	        <div class="panel-body">
               <table class="table">
                  <?php
                  $sql = "select jenisInput, imageInformasi, deskripsiInformasi from lapas_informasi_tentang where kd_informasi = ".$_GET['id'];                  
                  $exe = mysqli_query($connDB, $sql);
                  writeLog(__LINE__, __FILE__, mysqli_error($connDB));
                  $row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
                     $x++;
                     echo '<tr>';
                        echo '<td>';
                        if($row['jenisInput'] == 1){
                           echo '<img src="attachment/aboutUs/'.$row['imageInformasi'].'" title="'.$row['imageInformasi'].'" alt="imageInformasi" width="100%">';
                        }
                        else{
                           echo $row['deskripsiInformasi'];
                        }
                        echo '</td>';
                     echo '</tr>';	
                  ?>
            </table>
	        </div>
		</div>
	</div>
</div> 