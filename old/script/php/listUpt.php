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
	$('#divForm').load('<?=BASE_URL?>script/php/formUpt.php?id='+ id);	
}
</script>
<div class="center-block">
    <div class="panel panel-info">
        <div class="panel-heading">
          <b class="panel-title">Daftar UPT Pemasyarakatan</b>
        </div>
        <div class="panel-body">
        	<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th width="5%">No</th>
                  <th>Nama Wilayah</th>
						<th>Nama UPT Pemasyarakatan</th>
						<th>Nama Kepala UPT</th>
						<th>Alamat</th>
						<th>No. HP/WA Kepala UPT</th>
						<th>Email</th>
						<th width="8%">Action</th>
					</tr>
				</thead>
				<tbody>
                <?php
				$sql = "select a.kabupatenkotaid, a.kabupatenkotaname, b.provinsiname, a.alamat, a.email, a.nama_kepala, 
							a.no_telp_kepala 
							from kabupatenkota a, provinsi b 
                     where a.r_provinsiid = b.provinsiid order by a.kabupatenkotaid";
				$exe = mysqli_query($connDB, $sql);
			    writeLog(__LINE__, __FILE__, mysqli_error($connDB));
				$x=0;
				while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
					$x++;
					echo '<tr valign="middle">';	
                  echo '<td align="center"><b>'.$x.'</b></td>';
                  echo '<td>'.$row['provinsiname'].'</td>';
						echo '<td>'.$row['kabupatenkotaname'].'</td>';
						echo '<td>'.$row['nama_kepala'].'</td>';
						echo '<td>'.$row['alamat'].'</td>';
						echo '<td>'.$row['no_telp_kepala'].'</td>';
						echo '<td>'.$row['email'].'</td>';
						echo '<td align="center"><a href="#" class="edit" id="'.$row['kabupatenkotaid'].'" onclick="editData(\''.$row['kabupatenkotaid'].'\');"><img src="'.BASE_URL.'assets/common/img/pencil.png" alt="edit" title="edit data"></a>&nbsp;<a href="#" class="delete" id="'.$row['kabupatenkotaid'].'" onclick="deleteData(\''.$row['kabupatenkotaid'].'\', \'kabupatenkota\', \'kabupatenkotaid\', \'listUpt\');"><img src="'.BASE_URL.'assets/common/img/delete.png" alt="delete" title="hapus data" width="20px" ></a></td>';
					echo '</tr>';
				}
				?>
				</tbody>
			</table>
        </div>
	</div>
</div> 