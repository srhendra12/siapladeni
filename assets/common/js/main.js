// JavaScript Document
var baseURL = window.location.protocol + "//" + window.location.host + '/';

function GetUrlValue(VarSearch){
    var SearchString = window.location.search.substring(1);
    var VariableArray = SearchString.split('&');
    for(var i = 0; i < VariableArray.length; i++){
        var KeyValuePair = VariableArray[i].split('=');
        if(KeyValuePair[0] == VarSearch){
            return KeyValuePair[1];
        }
    }
}

function ajaxloading(id)
{
	$("#"+id).empty().html('<table height="100%" width="100%"><tr><td align="center"><img src="'+ baseURL +'assets/common/img/loading.gif" /><br /><br /><font color="#666666">loading...please wait...</font></td></tr></table>');
}

Shadowbox.init({
	language	: 'en',
	modal		: true,
	continuous	: true,
	players		:  ['html','iframe','img'], 
	enableKeys	: false
});

function popup(judul,alamat,lebar,tinggi)
{
	Shadowbox.open({
       	content	: alamat,
       	player	: "iframe",
       	title	: judul,
       	height	: tinggi,
       	width	: lebar,
 	});
			
	Shadowbox.clearCache();
}

function tutup()
{
	self.parent.Shadowbox.close();
}

function hanyaangka(evt){ 
 	var code 		= evt.keyCode || evt.which;
	var charCode 	= (code) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode < 45 || charCode > 57) || charCode === 9)
	return false;	
}

function validateEmail(sEmail) {
	var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
	if (filter.test(sEmail)) {
		return true;
	}
	else {
		return false;
	}
}

function getEnableDisable(sourceInputID, destInputID){
	var isInput = $(sourceInputID).val();
	if(isInput != ''){
		$(destInputID).removeAttr("disabled");
	}
	else{
		$(destInputID).attr("disabled", "disabled");
	}
}

function isActive(id, value, table, param, filename, div, getFunction, jenisData){
	$.ajax({  
		type	: 'POST',
		url		:  baseURL + 'include/proses.php',
		dataType: "json",
		data	: {'action' : 'activeDeactive', 'jenis' : 'is_active', 'table' : table, 'param' : param, 'value' : value, 'id' : id},
		success : function(data) {
			if(data.error == false){
				getLoadData(div, getFunction, jenisData);
			}
		}
	});	
}

function enableDisable(id, value, table, param, jenis, fileName){
	var newParam = id.split('_');
	id = newParam[0];

	switch(fileName){
		case 'list_kriteria_kondisi' :
			var identifikasi = newParam[1];
			var aspek = newParam[2];
			break;
		case 'list_tahap_perencanaan' :
			var sektor = newParam[1];
			break;
		case 'list_pelaksanaan_konstruksi' :
			var sektor = newParam[1];
			break;
		case 'list_evaluasi_pasca_konstruksi' :
			var kdKriteria 	= newParam[1];
			var sektor 		= newParam[2];
			break;
		case 'list_output_perencanaan' :
			var prop 		= newParam[1];
			var kota 		= newParam[2];
			var tahunData 	= newParam[3];
			var sektor 		= newParam[4];
			break;
	}

	$.ajax({  
		type	: 'POST',
		url		:  baseURL + 'include/proses.php',
		dataType: "json",
		data	: {'action' : 'enableDisable', 'jenis' : jenis, 'table' : table, 'param' : param, 'value' : value, 'id' : id},
		success : function(data) {
			if(data.error == false){
				switch(fileName){
					case 'list_kriteria_kondisi' :
						ajaxloading('loadTable');
						$('#loadTable').load('include/proses.php?act=list_kriteria_kondisi&identifikasi='+ identifikasi +'&aspek='+ aspek);
						break;
					case 'list_tahap_perencanaan' :
						ajaxloading('loadTable');
						$('#divList').load('script/php/'+ fileName +'.php?sektor='+ sektor);
						break;
					case 'list_pelaksanaan_konstruksi' :
						ajaxloading('loadTable');
						$('#divList').load('script/php/'+ fileName +'.php?sektor='+ sektor);
						break;
					case 'list_evaluasi_pasca_konstruksi' :
						ajaxloading('loadTable');
						$('#divList').load('script/php/'+ fileName +'.php?kdKriteria='+ kdKriteria +'&sektor='+ sektor);
						break;
					case 'list_output_perencanaan' :
					case 'list_output_pelaksanaan' :
					case 'list_output_pascakonstruksi' :
						ajaxloading('divList');
						$('#divList').load('script/php/'+ fileName +'.php?prop='+ prop +'&kota='+ kota +'&tahunData='+ tahunData +'&sektor='+ sektor);
						break;
					default :
						ajaxloading('divList');
						$('#divList').load('script/php/'+ fileName +'.php');
						break;
				}
			}
		}
	});	
}

function deleteData(id, table, param, file, optParam = ''){
	bootbox.confirm("Anda Yakin akan menghapus data ini..?!", function(result) {
		if(result == true){
			$.ajax({  
				type	: 'POST',
				url		:  baseURL + 'include/proses.php?act=delete_data&id='+ id +'&table='+ table +'&param='+ param,
				dataType: "json",
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
									switch(file){
             						case 'list_tahap_perencanaan' :
											ajaxloading('loadTable');
											$('#divList').load('script/php/'+ file +'.php');
											break;
										case 'form_subtahap_perencanaan' :
											window.location.reload(true);
											break;
										case 'list_output_perencanaan' :
											var valParam = optParam.split("#");
											var prop 			= valParam[0];
											var kota 			= valParam[1];
											var tahunData 		= valParam[2];
											var isDashboard 	= valParam[3];
											ajaxloading('divList');
											$('#divList').load('script/php/list_output_perencanaan.php?prop='+ prop +'&kota='+ kota +'&tahunData='+ tahunData +'&isDashboard='+ isDashboard )
											break;
										default :
											ajaxloading('divList');
											$('#divList').load('script/php/'+ file +'.php');
											break;
									}
                           	
                        }, timeout);
					}
					else{
						bootbox.alert(data.message);
					}
				}
			});
		}
	});
}

function deleteDataForm(id, table, param, divContent, category){
	var valParam = param.split("#"); 
	bootbox.confirm("Anda Yakin akan menghapus data ini..?!", function(result) {
		if(result == true){
			$.ajax({  
				type	: 'POST',
				url		:  baseURL + 'include/proses.php?act=delete_data&cat='+ category +'&id='+ id +'&table='+ table +'&param='+ valParam[0],
				dataType: "json",
				success : function(data) {
					if(data.error == false){
						bootbox.alert(data.message, function() {
							ajaxloading('divList');
							getLoadData(valParam[1], valParam[2], divContent);
						});
					}
					else{
						bootbox.alert(data.message);
					}
				}
			});
		}
	});
}

$('a.page-scroll').click(function() {
	
	if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
	  var target = $(this.hash);
	  target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
	  if (target.length) {
		$('html,body').animate({
		  scrollTop: target.offset().top - 70
		}, 900);
		return false;
	  }
	}
});

function showLabelAlert(catAlert, alertMsg, scrollTop){
	$('#alert-'+ catAlert).remove();
	$('#labelAlert').show().focus();	
	$('#labelAlert').append('<div class="alert alert-danger alert-dismissible" role="alert" id="alert-'+ catAlert +'"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> '+ alertMsg +'</div>');
	if(scrollTop == true) $("html, body").animate({ scrollTop: 0 }, "slow");
  	return false;
}

/* ----------------- Get Kategori -------------------- */
function getKategori(table, value, txtResult, iDResult){
	$.ajax({  
		url		 : baseURL + 'include/proses.php?act=getKategori&table='+ table +'&value='+ value,
		dataType : "json",
		success  : function(data) {
			if(data.error == false){
				$('#' + txtResult).empty().text(data.nmKategori);
				$('#' + iDResult).empty().val(data.kdKategori);
			}
		}
	});
}

/* ----------------- Get Propinsi -------------------- */
function getPropinsi(selectID, idwilayah, idprop){
	$.ajax({  
		url		 : baseURL + 'include/proses.php?act=getPropinsi&idwilayah='+ idwilayah +'&idprop='+ idprop,
		dataType : "html",
		success  : function(data) {
			$('#' + selectID).empty();
			$('#' + selectID).append(data);
		}
	});
}

/* ----------------- Get Kota -------------------- */
function getKota(selectID, idprop, idkota){
	$.ajax({  
		url		 : baseURL + 'include/proses.php?act=getKota&idprop='+ idprop +'&idkota='+ idkota,
		dataType : "html",
		success  : function(data) {
			$('#' + selectID).empty();
			$('#' + selectID).append(data);
		}
	});
}

/* ----------------- Get Kecamatan -------------------- */
function getKecamatan(selectID, idprop, idkota, idkecamatan, idkecamatan){
	$.ajax({  
		url		 : baseURL + 'include/proses.php?act=getKecamatan&idprop='+ idprop +'&idkota='+ idkota +'&idkecamatan='+ idkecamatan,
		dataType : "html",
		success  : function(data) {
			$('#' + selectID).empty();
			$('#' + selectID).append(data);
		}
	});
}
/* ----------------- Get Kecamatan -------------------- */
function getKelurahan(selectID, idprop, idkota, idkecamatan, idkelurahan){
	$.ajax({  
		url		 : baseURL + 'include/proses.php?act=getKelurahan&idprop='+ idprop +'&idkota='+ idkota +'&idkecamatan='+ idkecamatan +'&idkelurahan='+ idkelurahan,
		dataType : "html",
		success  : function(data) {
			$('#' + selectID).empty();
			$('#' + selectID).append(data);
		}
	});
}

/* ----------------- Get Load Data -------------------- */
function getLoadData(getData, keyNumber, getDiv){
	$.ajax({  
		url		:  baseURL + 'include/proses.php?act='+ getData +'&keyNumber='+ keyNumber,
		dataType: "html",
		success : function(data) {
			$('#' + getDiv).empty();
			$('#' + getDiv).append(data);
		}
	});		
}

/* pagination plugin */
$.fn.pageMe = function(opts){
    var $this = this,
        defaults = {
            perPage: 7,
            showPrevNext: false,
            numbersPerPage: 1,
            hidePageNumbers: false
        },
        settings = $.extend(defaults, opts);
    
    var listElement = $this;
    var perPage = settings.perPage; 
    var children = listElement.children();
    var pager = $('.pagination');
    
    if (typeof settings.childSelector!="undefined") {
        children = listElement.find(settings.childSelector);
    }
    
    if (typeof settings.pagerSelector!="undefined") {
        pager = $(settings.pagerSelector);
    }
    
    var numItems = children.size();
    var numPages = Math.ceil(numItems/perPage);

    var curr = 0;
    pager.data("curr",curr);
    
    if (settings.showPrevNext){
        $('<li><a href="#" class="prev_link">«</a></li>').appendTo(pager);
    }
    
    while(numPages > curr && (settings.hidePageNumbers==false)){
        $('<li><a href="#" class="page_link">'+(curr+1)+'</a></li>').appendTo(pager);
        curr++;
    }
  
    if (settings.numbersPerPage>1) {
       $('.page_link').hide();
       $('.page_link').slice(pager.data("curr"), settings.numbersPerPage).show();
    }
    
    if (settings.showPrevNext){
        $('<li><a href="#" class="next_link">»</a></li>').appendTo(pager);
    }
    
    pager.find('.page_link:first').addClass('active');
    pager.find('.prev_link').hide();
    if (numPages<=1) {
        pager.find('.next_link').hide();
    }
  	pager.children().eq(0).addClass("active");
    
    children.hide();
    children.slice(0, perPage).show();
    
    pager.find('li .page_link').click(function(){
        var clickedPage = $(this).html().valueOf()-1;
        goTo(clickedPage,perPage);
        return false;
    });
    pager.find('li .prev_link').click(function(){
        previous();
        return false;
    });
    pager.find('li .next_link').click(function(){
        next();
        return false;
    });
    
    function previous(){
        var goToPage = parseInt(pager.data("curr")) - 1;
        goTo(goToPage);
    }
     
    function next(){
        goToPage = parseInt(pager.data("curr")) + 1;
        goTo(goToPage);
    }
    
    function goTo(page){
        var startAt = page * perPage,
            endOn = startAt + perPage;
        
        children.css('display','none').slice(startAt, endOn).show();
        
        if (page>=1) {
            pager.find('.prev_link').show();
        }
        else {
            pager.find('.prev_link').hide();
        }
        
        if (page<(numPages-1)) {
            pager.find('.next_link').show();
        }
        else {
            pager.find('.next_link').hide();
        }
        
        pager.data("curr",page);
       
        if (settings.numbersPerPage>1) {
       		$('.page_link').hide();
       		$('.page_link').slice(page, settings.numbersPerPage+page).show();
    	}
      
      	pager.children().removeClass("active");
        pager.children().eq(page+1).addClass("active");
    
    }
};
/* end plugin */

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("myBtn").style.display = "block";
    } else {
        document.getElementById("myBtn").style.display = "none";
    }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0; // For Chrome, Safari and Opera
    document.documentElement.scrollTop = 0; // For IE and Firefox
} 

function getOptionSelect(getAction, selectID, param){
	$.ajax({  
        url     : baseURL + 'include/proses.php?act='+ getAction + '&param='+ param,
        dataType: "html",
        success : function(data) {
            $('#' + selectID).empty().append(data);
        }
    }); 

}

function notification(){
	var lastInbox 	= $('#badgeInbox').text();
	var lastContact = $('#badgeContact').text();
	var lastNewUser = $('#badgeNewUser').text();
	var sesUserAccess = $('#sesUserAccess').val();

	$.ajax({  
		url     : baseURL + 'include/proses.php?act=notification',
		dataType: "json",
		success : function(data) {
			if(data.totalMsg == 0) $('.badge').hide(); else $('.badge').show();

			var checkNumInbox 	= parseInt(data.inbox) - parseInt(lastInbox);
			var checkNumContact = parseInt(data.contact_us) - parseInt(lastContact);
			var checkNumNewUser = parseInt(data.new_user) - parseInt(lastNewUser);

			if(parseInt(checkNumInbox) > 0){
				alertNotification('Anda mendapatkan '+ checkNumInbox +' pesan baru (Inbox)', 'info');
			}
			$('#badgeInbox').empty().text(data.inbox);

			if(parseInt(checkNumContact) > 0){
				alertNotification('Anda mendapatkan '+ checkNumContact +' pesan baru dari menu Contact Us (Inbox)', 'warning');
			}
			$('#badgeContact').empty().text(data.contact_us);

			if(parseInt(checkNumNewUser) > 0 && (sesUserAccess == 1 || sesUserAccess == 4)){
				alertNotification('Terdapat '+ checkNumNewUser +' baru untuk mendapatkan persetujuan akses i-CPRF !!', 'danger');
			}
			$('#badgeNewUser').empty().text(data.new_user);
		}
	});		
}

function alertNotification(message, panel){
	$('.top-right').notify({
		message   	: { text: message },
		type      	: panel,
		fadeOut   	: {
		delay 		: Math.floor(Math.random() * 500) + 5000
		}
    }).show();
}

function getLokasi(param){
	$.ajax({  
        url     : baseURL + 'include/proses.php?act=getLokasi&param='+ param,
       	dataType: "json",
        success : function(data) {
			$('#nmPropinsi').val(data.nmPropinsi);
        }
    }); 
}

function getParamData(inputID, param, actionLoad){
	$.ajax({  
        url     : baseURL + 'include/proses.php?act='+ actionLoad +'&param='+ param,
       	dataType: "json",
        success : function(result) {
            $('#'+ inputID).val(result.data);
        }
    }); 
}

function checkDataEntry(param, data, buttonID){
	if(data != ''){
		var catMsg, errorMsg;
		if(param == 'email'){
			if(!validateEmail(data)){
				bootbox.alert('Maaf, periksa kembali format penulisan email anda !');
				return;
			}	
		}
		
		$.ajax({ 
			type	: 'POST',
			url		: baseURL + 'include/proses.php?act=cekValidasiData&param='+ param +'&data='+ data,
			dataType: "json",
			success : function(data) { 
				if(data.error == true){
					bootbox.alert(data.errorMsg);
					$('#'+ buttonID).attr('disabled', true);
				}
				else{
					$('#'+ buttonID).attr('disabled', false);
				}
			}
		});
	}
}

function getIsExist(periode, buttonSimpan){
	$.ajax({  
		url     : baseURL + 'include/proses.php?act=getIsExist&periode='+ periode,
		dataType: "json",
		success : function(data) {
			if(data.error === true){
				$('#'+ buttonSimpan).attr('disabled', true);
			}
			else{
				$('#'+ buttonSimpan).removeAttr('disabled');

			}
		}
	}); 
}
