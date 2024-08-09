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
	$('#divForm').load('<?=BASE_URL?>script/php/form_penilaian_tahap_perencanaan.php?id='+ id);	
}
</script>
<div class="center-block">
    <div class="panel panel-info">
        <div class="panel-heading">
          <b class="panel-title">Daftar Range Penilaian Kecenderungan Pemahaman Petugas</b>
        </div>
        <div class="panel-body">
        	<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th width="5%">No</th>
						<th>Kategori</th>
						<th>Nilai Batas Bawah</th>
						<th>Nilai Batas Atas</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
                <?php
				$sql = "select a.kd_kategori, a.nmKategori, a.nilaiBatasBawah, a.nilaiBatasAtas 
						from p_range_tahap_perencanaan a";
				$exe = mysqli_query($connDB, $sql);
				writeLog(__LINE__, __FILE__, mysqli_error($connDB));
				$x=0;
				while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
					$x++;
					echo '<tr valign="middle">';	
						echo '<td align="center"><b>'.$x.'</b></td>';
						echo '<td>'.$row['nmKategori'].'</td>';
						echo '<td>'.$row['nilaiBatasBawah'].'</td>';
						echo '<td>'.$row['nilaiBatasAtas'].'</td>';
						echo '<td align="center"><a href="#" class="edit" id="'.$row['kd_kategori'].'" onclick="editData(\''.$row['kd_kategori'].'\');"><img src="'.BASE_URL.'assets/common/img/pencil.png" alt="edit" title="edit data"></a>&nbsp;<a href="#" class="delete" id="'.$row['kd_kategori'].'" onclick="deleteData(\''.$row['kd_kategori'].'\', \'p_range_tahap_perencanaan\', \'kd_kategori\', \'list_penilaian_tahap_perencanaan\');"><img src="'.BASE_URL.'assets/common/img/delete.png" alt="delete" title="hapus data" width="20px" ></a></td>';
					echo '</tr>';
				}
				?>
				</tbody>
			</table>
        </div>
	</div>
</div> 