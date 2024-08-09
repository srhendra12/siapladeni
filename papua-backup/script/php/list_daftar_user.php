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
	$('#divForm').load('<?=BASE_URL?>script/php/form_daftar_user.php?id='+ id);	
}
</script>
<div class="center-block">
    <div class="panel panel-info">
        <div class="panel-heading">
          <b class="panel-title">Daftar User</b>
        </div>
        <div class="panel-body">
        	<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th width="5%">No</th>
                  <th>User ID</th>
                  <th>User Name</th>
                  <th>Hak akses</th>
                  <th>Wilayah</th>
                  <th>UPT Pemasyarakatan</th>
                  <th>Is active</th>
						<th width="8%">Action</th>
					</tr>
				</thead>
				<tbody>
               <?php
					$where = ($_SESSION['access'] == 1) ? "where pum.kd_wilayah = ".$_SESSION['wilayah'] : "";
					$sql = "select  provinsiname, provinsiid from provinsi ".$where." order by provinsiid";
               $sql = "select pum.kd_user, pum.userid, pum.username, pum.email, pua.nm_access hakAkses, p.provinsiname wilayah, pum.is_active,
					pw.nama_wilayah propinsi, k.kabupatenkotaname upt
					from p_user_management pum 
					join p_user_access pua on pum.kd_access = pua.kd_access 
					left join p_wilayah pw on pum.kd_wilayah = pw.kd_wilayah 
					left join provinsi p on pum.kd_propinsi = p.provinsiid 
					left join kabupatenkota k on pum.kd_kota = k.kabupatenkotaid 
					".$where." order by pum.kd_user";
               $exe = mysqli_query($connDB, $sql);
               writeLog(__LINE__, __FILE__, mysqli_error($connDB));
               $x=0;
               while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
                  $x++;
                  $checked    = ($row['is_active'] == 1) ? "checked" : "";
                  echo '<tr valign="middle">';	
                     echo '<td align="center"><b>'.$x.'</b></td>';
                     echo '<td>'.$row['userid'].'</td>';
                     echo '<td>'.$row['username'].'</td>';
                     echo '<td>'.$row['hakAkses'].'</td>';
                     echo '<td>'.$row['wilayah'].'</td>';
                     echo '<td>'.(!empty($row['upt']) ? $row['upt'] : "-").'</td>';
                     echo '<td align="center"><input type="checkbox" value="'.$row['is_active'].'" name="is_active" '.$checked.' class="aktif" alt="is_active" onclick="enableDisable(\''.$row['kd_user'].'\', \''.$row['is_active'].'\', \'p_user_management\', \'user\', \'is_active\', \'list_daftar_user\');"></td>';
							echo '<td align="center">';
								echo '<a href="#" class="edit" id="'.$row['kd_user'].'" onclick="editData(\''.$row['kd_user'].'\');"><img src="'.BASE_URL.'assets/common/img/pencil.png" alt="edit" title="edit data"></a>&nbsp;';
								if($row['kd_user'] > 1){
									echo '<a href="#" class="delete" id="'.$row['kd_user'].'" onclick="deleteData(\''.$row['kd_user'].'\', \'p_user_management\', \'kd_user\', \'list_daftar_user\');"><img src="'.BASE_URL.'assets/common/img/delete.png" alt="delete" title="hapus data" width="20px" ></a></td>';
								}
                  echo '</tr>';
               }
               ?>
				</tbody>
			</table>
        </div>
	</div>
</div> 