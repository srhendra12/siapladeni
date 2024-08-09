<?php session_start(); error_reporting(0); ?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<script type="text/javascript">
$(document).ready(function(){
   $('.image, .deskripsi').hide();
   
   var isEdit = $('#isEdit').val();
   if(isEdit !=''){
      var selected = $("input:radio[name=jenisInput]:checked").val();
      switch(selected){
         case '1' : $('.image').show(); $('.deskripsi').hide(); break;
         case '2' : $('.image').hide(); $('.deskripsi').show(); break;
         default  : $('.image, .deskripsi').hide(); break;
      }
   }
   
   $('.jenisInput').click(function(){
      var selected = $("input:radio[name=jenisInput]:checked").val();
      switch(selected){
         case '1' : $('.image').show(); $('.deskripsi').hide(); break;
         case '2' : $('.image').hide(); $('.deskripsi').show(); break;
         default  : $('.image, .deskripsi').hide(); break;
      }
   });

	$('#reset').click(function(){ 
		$('#divForm').load('<?=BASE_URL?>script/php/form_about_us.php');
	});

   CKEDITOR.replace("deskripsiInformasi", {
		fullPage		: true,
		allowedContent	: true,
	});

   $("#form").submit(function() {
		var namaInformasi 	= $('#namaInformasi').val();

		if(namaInformasi === ''){
			alert('Maaf, Nama Informasi belum di isi !');
			return false;
		}
		else{
			var myForm = document.getElementById('form');
			var formData = new FormData(myForm); 
         formData.append("userfile", $("#userfile")[0].files[0]);
			formData.append("deskripsiInformasi", CKEDITOR.instances.deskripsiInformasi.getData());
						
			$.ajax({
				type		: "POST",
				url      : $(this).attr('action'),
				data		: $(this).serialize(),
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
                        $('#divForm').load('<?=BASE_URL?>script/php/form_about_us.php');
                        $('#divList').load('<?=BASE_URL?>script/php/list_about_us.php');
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
		}
	}); 

});
</script>
<?php
if(!empty($_GET['id']) || $_GET['id'] != ''){
	$sql = "select namaInformasi, jenisInput, imageInformasi, deskripsiInformasi, isActive from lapas_informasi_tentang
			   where kd_informasi = ".$_GET['id'];
	$exe = mysqli_query($connDB, $sql);
    writeLog(__LINE__, __FILE__, mysqli_error($connDB));
	$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
   $checked1 = ($row['jenisInput'] == 1) ? "checked" : "";
   $checked2 = ($row['jenisInput'] == 2) ? "checked" : "";
   $isActived  = ($row['isActive'] == 1) ? "checked" : "";
}
else{
   $isActived    = "checked";
}
?>
<form id="form" name="form" method="post" action="<?=BASE_URL?>include/proses.php" autocomplete="off">
<input type="hidden" name="action" value="add_tentang">
<input type="hidden" name="isEdit" id="isEdit" value="<?=$_GET['id'];?>">
<div class="center-block col-sm-7" style="padding-left:0px;">
    <div class="panel panel-info">
    	<div class="panel-heading" align="left">
          <b class="panel-title">Tambah About Us</i></b>
        </div>
        <div class="panel-body">
            <table class="table table-striped">
                <tr>
                    <td width="30%"><label class="control-label" for="namaInformasi">Nama Informasi</label></td>
                    <td><input class="form-control" type="text" id="namaInformasi" name="namaInformasi" value="<?=$row['namaInformasi']?>"></td>
                </tr>
                <tr>
                    <td><label class="control-label" for="jenisInformasi">Informasi Berupa</label></td>
                    <td>
                        <label><input class="input-status jenisInput" type="radio" name="jenisInput" value="1" <?=$checked1?> >&nbsp;Gambar</label>
                        <label><input class="input-status jenisInput" type="radio" name="jenisInput" value="2" <?=$checked2?> >&nbsp;Tulisan Deskripsi</label>
                  </td>
                </tr>
                <tr class="image">
                    <td><label class="control-label" for="inputNama">Pilih Gambar</label></td>
                    <td>
                        <input class="btn btn-sm btn-primary" type="file" id="userfile" name="userfile">
                        <?php
                           if(!empty($_GET['id']) || $_GET['id'] != ''){
                              echo '<br>';
                              echo 'Informasi gambar : <a href="attachment/aboutUs/'.$row['imageInformasi'].'" title="'.$row['imageInformasi'].'" alt="imageInformasi" target="_new" data-gallery class="image-link"><b>'.$row['imageInformasi'].'</b></a>';
                           }
                        ?>
                  </td>
                </tr>
                <tr class="deskripsi">
                    <td colspan="2"><label class="control-label" for="inputNama">Deskripsi Informasi</label></td>
                </tr>
                <tr class="deskripsi">
                    <td colspan="2"><textarea class="form-control input-sm" rows="5" id="deskripsiInformasi" name="deskripsiInformasi"><?=$row['deskripsiInformasi']?></textarea></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><label><input class="input-status" type="checkbox" name="isActive" id="isActive" value="1" <?=$isActived?> >&nbsp;Is Active</label></td>
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
