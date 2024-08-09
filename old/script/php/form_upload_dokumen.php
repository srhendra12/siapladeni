<?php session_start(); error_reporting(0); ?>
<?php //if(empty($_SESSION['token']) || !isset($_SESSION['token'])) { echo "<script>window.location.href='http://'+ window.location.hostname +'/sig_drainase'</script>"; }?>
<?php include "../../include/config.php"; ?>
<?php include "../../include/phpfunction.php";?>
<script type="text/javascript">
$(document).ready(function(){
	/* -- Add / Remove Row Input --*/
	var p = $('#participants').val();
	var row = $('.participantRow');

	/* Functions */
	function getP(){
		p = $('#participants').val();
	}

	function addRow() {
		row.clone(true, true).appendTo('#participantTable').find('input').val('').end();
	}	

	function removeRow(button) {
		button.closest('tr').remove();
	}

	if($('#participantTable tr').length === 3) {
		$('.remove').hide();
	} 

	$('.add').on('click', function () {
		getP();
		addRow();
		var i = Number(p)+1;
		$('#participants').val(i);
		
		$(this).closest('tr').appendTo('#participantTable');
		if ($('#participantTable tr').length === 2) {
			$('.remove').hide();
		} 
		else {
			$('.remove').show();
		}
	});

	$('.remove').on('click', function () {
		getP();
		if($('#participantTable tr').length === 3) {
			//alert('Can't remove row.');
			$('.remove').hide();
		} 
		else if($('#participantTable tr').length - 1 == 3) {
			$('.remove').hide();
			removeRow($(this));
			var i = Number(p)-1;
			$('#participants').val(i);
		} 
		else {
			removeRow($(this));
			var i = Number(p)-1;
			$('#participants').val(i);
		}
	});

	$('.hapus').on('click', function () {
		getP();
		removeRow($(this));
		var i = Number(p)-1;
		$('#participants').val(i);
	});

	$('#reset').click(function(){ 
		$('#divForm').load('<?=BASE_URL?>script/php/form_upload_dokumen.php');
	});
	
	$("#form").submit(function() {
      var jumRow = $('#participants').val();
		var myForm = document.getElementById('form');
		var formData = new FormData(myForm); 
		for(var i=0;i<parseInt(jumRow);i++){
			formData.append("userfile[]", $("input[name='userfile[]']").prop('files')[i]);
		}

		$.ajax({  
			type	: 'POST',
			url		: $(this).attr('action'),
			data		: $(this).serialize(),
			data		: formData,
			contentType	: false,
			processData	: false, 
			dataType	: "json",
			success : function(data) {
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
						$('#divForm').load('<?=BASE_URL?>script/php/form_upload_dokumen.php');
						$('#divList').load('<?=BASE_URL?>script/php/list_dokumen.php');
						}, timeout);
				}
				else{
					bootbox.alert(data.message);
				}
			},  
			error : function() {  
				bootbox.alert("#error");  
			}  
		});

		return false;  
	});

});
</script>
<form id="form" name="form" method="post" action="<?=BASE_URL?>include/proses.php" autocomplete="off">
<input type="hidden" name="action" value="upload_dokumen">
<div class="center-block col-sm-6" style="padding-left:0px;">
    <div class="panel panel-info">
    	<div class="panel-heading" align="left">
          <b class="panel-title">Upload Dokumen Pendukung</b>
        </div>
        <div class="panel-body">
		  		<input type="hidden" id="participants" name="participants" value="1">
            <table class="table table-striped" id="participantTable">
               <tr>
                  <td colspan="3"><b>Upload File Pendukung :</b></td>
               </tr>
					<tr class="participantRow">
						<td width="35%"><input class="btn btn-primary" type="file" id="userfile[]" name="userfile[]"></td>
                  <td><input type="text" class="form-control input-sm" type="text" id="keterangan[]" name="keterangan[]" placeholder="Keterangan..">
						<td align="center"><button class="btn btn-danger remove" type="button">-</button></td>
					</tr>
               <tr>
                  <td colspan="3"  id="addButtonRow">
							<button class="btn btn-success add" type="button">+ Dokumen Pendukung</button>
							<div style="float: right;">
								<button type="reset" id="reset" class="btn btn-default">Batal</button>&nbsp;<button type="submit" class="btn btn-primary">Simpan</button>
							</div>
						</td>
               </tr>
            </table>
        </div>
	</div>
</div> 
</form>