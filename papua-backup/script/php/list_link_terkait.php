<?php session_start(); error_reporting(0); ?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
    $('.image-link').click(function(){ 
		$('#blueimp-gallery').data('useBootstrapModal', false)
		$('#blueimp-gallery').toggleClass('blueimp-gallery-controls', true)
	})

    $("#example").DataTable({
        responsive      : true,
        ordering        : false,
        scrollCollapse  : true,
        paging          : false
    });
});

function editData(id){
    $('#divForm').load('<?=BASE_URL?>script/php/form_link_terkait.php?id='+ id);	
}
</script>
<div class="center-block">
    <div class="panel panel-info">
        <div class="panel-heading">
        <b class="panel-title">Link Informasi Terkait</b>
        </div>
        <div class="panel-body">
            <div class="space"></div>
            <div id="links">
                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="30%">Nama Link Informasi</th>
                            <th>URL Link Informasi</th>
                            <th width="10%">Is Active</th>
                            <th width="15%">Action</th>      
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "select kd_link, nm_link, url_link, isActive 
                                from lapas_link_terkait order by kd_link asc";
                        $exe = mysqli_query($connDB, $sql);
                        writeLog(__LINE__, __FILE__, mysqli_error($connDB));
                        $x=0;
                        while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
                            $x++;
                            $checked 	= ($row['isActive'] == 1) ? "checked" : "";
                            echo '<tr>';
                                echo '<td align="center"><b>'.$x.'</b></td>';
                                echo '<td>'.$row['nm_link'].'</td>';
                                echo '<td><a href="'.$row['url_link'].'" title="'.$row['nm_link'].'" target="_blank">'.$row['url_link'].'</a></td>';
                                echo '<td align="center"><input type="checkbox" value="'.$row['isActive'].'" name="isActive" '.$checked.' class="aktif" alt="isActive" onclick="enableDisable(\''.$row['kd_link'].'\', \''.$row['isActive'].'\', \'lapas_link_terkait\', \'link\', \'isActive\', \'list_link_terkait\');"></td>';
                                echo '<td align="center">';
                                   echo '<a href="#" class="edit" id="'.$row['kd_link'].'" onclick="editData(\''.$row['kd_link'].'\');"><img src="'.BASE_URL.'assets/common/img/pencil.png" alt="edit" title="edit data"></a>&nbsp;';
                                   echo '&nbsp;<a href="#" class="delete" id="'.$row['kd_link'].'" onclick="deleteData(\''.$row['kd_link'].'\', \'lapas_link_terkait\', \'kd_link\', \'list_link_terkait\');"><img src="assets/common/img/delete.png" alt="delete" title="hapus data" width="20px"></a>';
                                echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    <tbody>
                </table>
            </div>
        </div>
    </div>
</div> 