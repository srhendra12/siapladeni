<?php session_start(); error_reporting(0); ?>
<?php //if(empty($_SESSION['token']) || !isset($_SESSION['token'])) { echo "<script>window.location.href='http://'+ window.location.hostname +'/sig_drainase'</script>"; }?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$('#example').DataTable({
	    responsive: true,
		fixedHeader: true
	});
});

</script>
<div class="center-block">
    <div class="panel panel-info">
        <div class="panel-heading">
          <b class="panel-title">Daftar Dokumen Pendukung</b>
        </div>
        <div class="panel-body">
        	<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th width="5%">No</th>
                  <th>Nama File</th>
						<th>Keterangan</th>
						<th>Is Active</th>
						<th width="15%">Action</th>
					</tr>
				</thead>
				<tbody>
                <?php
            $sql = "select kd_dokumen, nmFile, keterangan, isActive from p_dokumen_pendukung order by kd_dokumen";
				$exe = mysqli_query($connDB, $sql);
			    writeLog(__LINE__, __FILE__, mysqli_error($connDB));
				$x=0;
				while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
					$x++;
					$checked    = ($row['isActive'] == 1) ? "checked" : "";
					echo '<tr valign="middle">';	
                  echo '<td align="center"><b>'.$x.'</b></td>';
                  echo '<td><a href="'.BASE_URL.'attachment/dokumen_pendukung/'.$row['nmFile'].'">'.$row['nmFile'].'</td>';
						echo '<td>'.$row['keterangan'].'</td>';
						echo '<td align="center"><input type="checkbox" value="'.$row['isActive'].'" name="isActive" '.$checked.' class="aktif" alt="isActive" onclick="enableDisable(\''.$row['kd_dokumen'].'\', \''.$row['isActive'].'\', \'p_dokumen_pendukung\', \'dokumen\', \'isActive\', \'list_dokumen\');"></td>';
						echo '<td align="center"><a href="#" class="delete" id="'.$row['kd_dokumen'].'" onclick="deleteData(\''.$row['kd_dokumen'].'\', \'p_dokumen_pendukung\', \'kd_dokumen\', \'list_dokumen\');"><img src="'.BASE_URL.'assets/common/img/delete.png" alt="delete" title="hapus data" width="20px" ></a></td>';
					echo '</tr>';
				}
				?>
				</tbody>
			</table>
        </div>
	</div>
</div> 