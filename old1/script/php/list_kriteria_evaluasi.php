<?php session_start(); error_reporting(0); ?>
<?php // if(empty($_SESSION['token']) || !isset($_SESSION['token'])) { echo "<script>window.location.href='http://'+ window.location.hostname +'/sig_drainase'</script>"; }?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$('#example').DataTable({
	    responsive: true,
		fixedHeader: true
	});
});

function editData(id){
	$('#divForm').load('<?=BASE_URL?>script/php/form_kriteria_evaluasi.php?id='+ id);	
}
</script>
<div class="center-block">
    <div class="panel panel-info">
        <div class="panel-heading">
          <b class="panel-title">Daftar Kriteria Evaluasi</b>
        </div>
        <div class="panel-body">
        	<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th width="5%">No</th>
						<th>Nama Kriteria Evaluasi</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
                <?php
				$sql = "select kd_kriteria, nm_kriteria from p_kriteria_evaluasi";
				$exe = mysqli_query($connDB, $sql);
				writeLog(__LINE__, __FILE__, mysqli_error($connDB));
				$x=0;
				while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
					$x++;
					echo '<tr valign="middle">';	
						echo '<td align="center"><b>'.$x.'</b></td>';
						echo '<td>'.$row['nm_kriteria'].'</td>';
						echo '<td align="center"><a href="#" class="edit" id="'.$row['kd_kriteria'].'" onclick="editData(\''.$row['kd_kriteria'].'\');"><img src="'.BASE_URL.'assets/common/img/pencil.png" alt="edit" title="edit data"></a>&nbsp;<a href="#" class="delete" id="'.$row['kd_kriteria'].'" onclick="deleteData(\''.$row['kd_kriteria'].'\', \'p_kriteria_evaluasi\', \'kd_kriteria\', \'list_kriteria_evaluasi\');"><img src="'.BASE_URL.'assets/common/img/delete.png" alt="delete" title="hapus data" width="20px" ></a></td>';
					echo '</tr>';
				}
				?>
				</tbody>
			</table>
        </div>
	</div>
</div> 