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
	$('#divForm').load('<?=BASE_URL?>script/php/form_jenis_infrastruktur.php?id='+ id);	
}
</script>
<div class="center-block">
    <div class="panel panel-info">
        <div class="panel-heading">
          <b class="panel-title">Daftar Parameter Jenis Infrastruktur</b>
        </div>
        <div class="panel-body">
        	<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th width="5%">No</th>
						<th width="30%">Sektor</th>
						<th>Jenis Infrastruktur</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
                <?php
				$sql = "select a.kd_jenisInfra, a.nmJenisInfra, b.nm_sektor 
						from p_jenis_infrastruktur a, p_sektor b
						where a.kd_sektor = b.kd_sektor";
				$exe = mysqli_query($connDB, $sql);
				writeLog(__LINE__, __FILE__, mysqli_error($connDB));
				$x=0;
				while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
					$x++;
					echo '<tr valign="middle">';	
						echo '<td align="center"><b>'.$x.'</b></td>';
						echo '<td>'.$row['nm_sektor'].'</td>';
						echo '<td>'.$row['nmJenisInfra'].'</td>';
						echo '<td align="center"><a href="#" class="edit" id="'.$row['kd_jenisInfra'].'" onclick="editData(\''.$row['kd_jenisInfra'].'\');"><img src="'.BASE_URL.'assets/common/img/pencil.png" alt="edit" title="edit data"></a>&nbsp;<a href="#" class="delete" id="'.$row['kd_jenisInfra'].'" onclick="deleteData(\''.$row['kd_jenisInfra'].'\', \'p_jenis_infrastruktur\', \'kd_jenisInfra\', \'list_jenis_infrastruktur\');"><img src="'.BASE_URL.'assets/common/img/delete.png" alt="delete" title="hapus data" width="20px" ></a></td>';
					echo '</tr>';
				}
				?>
				</tbody>
			</table>
        </div>
	</div>
</div> 