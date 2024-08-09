<?php session_start(); error_reporting(0); ?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<script type="text/javascript">
$(document).ready(function(){
	$('#reset').click(function(){ 
		$('#divForm').load('<?=BASE_URL?>script/php/form_slide_show.php');
	});

    $("#form").submit(function() {
			
        var myForm = document.getElementById('form');
        var formData = new FormData(myForm); 
        formData.append("userfile", $("#userfile")[0].files[0]);
        
        var id 			= $('#isEdit').val();
        var action 		= $('#action').val();
        var isActive 	= ($('input:checkbox[name=isActive]').is(':checked')) ? 1 : 0;
        var firstTitle 	= $('#firstTitle').val();
        var lastTitle 	= $('#lastTitle').val();
        var shortDes 	= $('#shortDes').val();
                    
        $.ajax({
            type		: "POST",
            url			: $(this).attr('action'),
            data		: {'isActive' : isActive, 'firstTitle' : firstTitle, 'lastTitle' : lastTitle, 'shortDes' : shortDes, 'isEdit' : id, 'action' : action},
            data		: formData,
            contentType	: false,
            processData	: false, 
            dataType	: "json",
            success 	: function(data) {
                if(data.error == false){
                    var timeout = 2000; // 1 seconds
                    var dialog = bootbox.dialog({
                        message : '<p class="text-center">'+ data.message +'</p>',
                        size    : "small",
                        closeButton: false
                    });
                    setTimeout(function () {
                        dialog.modal('hide');
                        ajaxloading('divList');
                        $('#divForm').load('<?=BASE_URL?>script/php/form_slide_show.php');
                        $('#divList').load('<?=BASE_URL?>script/php/list_slide_show.php');
                    }, timeout);
                }
                else{
                    bootbox.alert(data.message);
                }
            },  
            error : function() {  
                bootbox.alert("#errorCode !!");  
            }  
        }); 
        return false;  
    }); 

});
</script>
<?php
if(!empty($_GET['id']) || $_GET['id'] != ''){
	$sql = "select imagehome, firstTitle, lastTitle, shortDes, isActive 
           	from lapas_slidehome
			where kd_home = ".$_GET['id'];
	$exe = mysqli_query($connDB, $sql);
    writeLog(__LINE__, __FILE__, mysqli_error($connDB));
	$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
	$checked = ($row['isActive'] == 1) ? "checked" : "";
}
else{
    $checked    = "checked";
}
?>
<form id="form" name="form" method="post" action="<?=BASE_URL?>include/proses.php" autocomplete="off">
<input type="hidden" name="action" value="add_slideshow">
<input type="hidden" name="isEdit" value="<?=$_GET['id'];?>">
<div class="center-block col-sm-5" style="padding-left:0px;">
    <div class="panel panel-info">
    	<div class="panel-heading" align="left">
          <b class="panel-title">Tambah Gambar <i>Slideshow</i></b>
        </div>
        <div class="panel-body">
            <table class="table table-striped">
                <tr>
                    <td width="30%"><label class="control-label" for="inputNama">Pilih Gambar</label></td>
                    <td><input class="btn btn-sm btn-primary" type="file" id="userfile" name="userfile"></td>
                </tr>
                <tr>
                    <td><label class="control-label" for="inputLastTitle">Informasi Tambahan</label></td>
                    <td><input class="form-control" type="text" id="lastTitle" name="lastTitle" value="<?=$row['lastTitle']?>"></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><label><input class="input-status" type="checkbox" name="isActive" id="isActive" value="1" <?=$checked?> >&nbsp;Is Active</label></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><button type="reset" id="reset" class="btn btn-default">Batal</button>&nbsp;<button type="submit" class="btn btn-primary">Simpan</button></td>
                </tr>
            </table>
        </div>
	</div>
</div> 
</form>
