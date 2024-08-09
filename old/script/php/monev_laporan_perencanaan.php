<?php 
#error_reporting(0);
require_once '../../include/config.php'; 
require_once '../../include/phpfunction.php'; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7]> <html xmlns="http://www.w3.org/1999/xhtml" class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html xmlns="http://www.w3.org/1999/xhtml" class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html xmlns="http://www.w3.org/1999/xhtml" class="lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html xmlns="http://www.w3.org/1999/xhtml"> <!--<![endif]-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Expires" content="Mon, 26 Jul 1997 05:00:00 GMT">
    <meta http-equiv="Pragma" content="no-cache">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="description" content="<?=_TITLE?>">
    <meta name="author" content="@vincentGrerry">
    
    <title><?=_TITLE?></title>
    
    <link href="<?=BASE_URL?>assets/common/img/logoPU.png" rel="icon" type="image/x-icon" />

    <!-- Bootstrap core CSS -->
    <link href="<?=BASE_URL?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?=BASE_URL?>assets/bootstrap/fonts/font-awesome/css/font-awesome.css">    
    
    <!-- Bootstrap Extended -->
    <link href="<?=BASE_URL?>assets/bootstrap/extend/bootstrap-dataTable/css/dataTables.bootstrap.css" rel="stylesheet" >
    <link href="<?=BASE_URL?>assets/bootstrap/extend/bootstrap-dataTable/css/buttons.dataTables.min.css" rel="stylesheet" >
     
    <!-- Custom styles for this template -->
    <link href="<?=BASE_URL?>assets/shadowbox/shadowbox.css" rel="stylesheet" type="text/css" >
    <link href="<?=BASE_URL?>assets/bootstrap/extend/select2-master/css/select2.css" rel="stylesheet">
   
    <!-- Default styles for this template -->
    <link rel="stylesheet" href="<?=BASE_URL?>assets/common/css/main.css" />
    
    <!-- jQuery v1.11.3 -->
    <script src="<?=BASE_URL?>assets/common/js/jquery.min.js"></script>
    
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="<?=BASE_URL?>assets/common/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="<?=BASE_URL?>assets/common/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
</head>

<body>
    <div id="full-wrapper">
        <div class="container-fluid" style="margin-top: 20px;">
            <!-- Main Content Informasi-->
            <div id="content-informasi">
                <div class="center-block">
                    <div class="panel panel-info">
                        <div class="panel-heading" align="left">
                          <b class="panel-title">Daftar Pemantauan Dan Evaluasi Infrastruktur Bidang PLP Tahap Kesiapan Pelaksanaan</b>
                        </div>
                        <div class="panel-body">
                            <div id="divForm"></div>
                            <div id="divList" class="maintablecontainer"></div>
                        </div>
                    </div>
                </div>
            </div><!--/Informasi-->
        </div><!--/.col-xs-12.col-sm-9-->
    </div>
    
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    <!-- Bootstrap Script -->
    <script src="<?=BASE_URL?>assets/bootstrap/js/bootstrap.min.js"></script>
    
    <!-- Bootstrap Extended -->
    <script src="<?=BASE_URL?>assets/bootstrap/extend/bootstrap-dataTable/js/jquery.dataTables.js"></script>
    <script src="<?=BASE_URL?>assets/bootstrap/extend/bootstrap-dataTable/js/dataTables.bootstrap.js"></script>
    <script src="<?=BASE_URL?>assets/bootstrap/extend/bootstrap-dataTable/js/jszip.min.js"></script>
    <script src="<?=BASE_URL?>assets/bootstrap/extend/bootstrap-dataTable/js/pdfmake.min.js"></script>
    <script src="<?=BASE_URL?>assets/bootstrap/extend/bootstrap-dataTable/js/buttons.html5.min.js"></script>
    <script src="<?=BASE_URL?>assets/bootstrap/extend/bootstrap-dataTable/js/vfs_fonts.js"></script>
    <script src="<?=BASE_URL?>assets/bootstrap/extend/bootstrap-dataTable/js/dataTables.buttons.min.js"></script>
    <script src="<?=BASE_URL?>assets/bootstrap/extend/bootstrap-dataTable/js/buttons.print.min.js"></script>

    <script src="<?=BASE_URL?>assets/bootstrap/extend/select2-master/js/select2.full.js"></script>

    <!-- Custom Onload Script -->
    <script type="text/javascript" src="<?=BASE_URL?>assets/shadowbox/shadowbox.js"></script>
    <script type="text/javascript" src="<?=BASE_URL?>assets/common/js/main.js"></script>
      
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?=BASE_URL?>assets/common/js/ie10-viewport-bug-workaround.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function(){
            var window_width    = $('#content-informasi').width();

            $('#divList').width(window_width - 35);
            ajaxloading('divList');
            $('#divForm').load('<?=BASE_URL?>script/php/form_param_dataUmum.php?listFile=laporan_perencanaan');
            $('#divList').load('<?=BASE_URL?>script/php/laporan_perencanaan.php');
        });
    </script>
</body>
</html>
