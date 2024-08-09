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
    $('#divForm').load('<?=BASE_URL?>script/php/form_slide_show.php?id='+ id);	
}
</script>
<div class="center-block">
    <div class="panel panel-info">
        <div class="panel-heading">
        <b class="panel-title"><i>Slide Show</i> Halaman Utama</b>
        </div>
        <div class="panel-body">
            <div class="space"></div>
            <div id="links">
                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="30%">Nama File</th>
                            <th>Title</th>
                            <th width="10%">Is Active</th>
                            <th width="15%">Action</th>      
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "select kd_home, imageHome, firstTitle, lastTitle, shortDes, isActive 
                                from lapas_slidehome order by kd_home asc";
                        $exe = mysqli_query($connDB, $sql);
                        writeLog(__LINE__, __FILE__, mysqli_error($connDB));
                        $x=0;
                        while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
                            $x++;
                            $checked 	= ($row['isActive'] == 1) ? "checked" : "";
                            echo '<tr>';
                                echo '<td align="center"><b>'.$x.'</b></td>';
                                echo '<td><a href="attachment/slideshow/'.$row['imageHome'].'" title="'.$row['imageHome'].'" alt="ImageHome" target="_new" data-gallery class="image-link"><b>'.$row['imageHome'].'</b></a></td>';
                                echo '<td>'.$row['lastTitle'].'</td>';
                                echo '<td align="center"><input type="checkbox" value="'.$row['isActive'].'" name="isActive" '.$checked.' class="aktif" alt="isActive" onclick="enableDisable(\''.$row['kd_home'].'\', \''.$row['isActive'].'\', \'lapas_slidehome\', \'home\', \'isActive\', \'list_slide_show\');"></td>';
                                echo '<td align="center">';
                                   echo '<a href="#" class="edit" id="'.$row['kd_home'].'" onclick="editData(\''.$row['kd_home'].'\');"><img src="'.BASE_URL.'assets/common/img/pencil.png" alt="edit" title="edit data"></a>&nbsp;';
                                   echo '&nbsp;<a href="#" class="delete" id="'.$row['kd_home'].'" onclick="deleteData(\''.$row['kd_home'].'\', \'lapas_slidehome\', \'kd_home\', \'list_slide_show\');"><img src="assets/common/img/delete.png" alt="delete" title="hapus data" width="20px"></a>';
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

<!-- The Bootstrap Image Gallery lightbox, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery">
    <!-- The container for the modal slides -->
    <div class="slides"></div>
    <!-- Controls for the borderless lightbox -->
    <h3 class="title"></h3>
</div>