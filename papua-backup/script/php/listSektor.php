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

function editData(id){
	$('#divForm').load('<?=BASE_URL?>script/php/formSektor.php?id='+ id);	
}
</script>
<div class="center-block">
    <div class="panel panel-info">
        <div class="panel-heading">
          <b class="panel-title">Daftar Sektor</b>
        </div>
        <div class="panel-body">
        	<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th width="5%">No</th>
						<th>Nama Sektor</th>
						<th width="15%">Action</th>
					</tr>
				</thead>
				<tbody>
                <?php
				$sql = "select kd_sektor, nm_sektor from p_sektor";
				$exe = mysqli_query($connDB, $sql);
			    writeLog(__LINE__, __FILE__, mysqli_error($connDB));
				$x=0;
				while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
					$x++;
					echo '<tr valign="middle">';	
						echo '<td align="center"><b>'.$x.'</b></td>';
						echo '<td>'.$row['nm_sektor'].'</td>';
						echo '<td align="center"><a href="#" class="edit" id="'.$row['kd_sektor'].'" onclick="editData(\''.$row['kd_sektor'].'\');"><img src="'.BASE_URL.'assets/common/img/pencil.png" alt="edit" title="edit data"></a>&nbsp;<a href="#" class="delete" id="'.$row['kd_sektor'].'" onclick="deleteData(\''.$row['kd_sektor'].'\', \'p_sektor\', \'kd_sektor\', \'listSektor\');"><img src="'.BASE_URL.'assets/common/img/delete.png" alt="delete" title="hapus data" width="20px" ></a></td>';
					echo '</tr>';
				}
				?>
				</tbody>
			</table>
        </div>
	</div>
</div> 