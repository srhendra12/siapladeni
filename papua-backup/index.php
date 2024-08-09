<?php 
error_reporting(0);
session_start(); 

require_once 'include/config.php'; 
require_once 'include/phpfunction.php'; 
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
    
    <link href="assets/common/img/logoPU.png" rel="icon" type="image/x-icon" />

    <!-- Bootstrap core CSS -->
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/bootstrap/fonts/font-awesome/css/font-awesome.css">    
    
    <!-- Bootstrap Extended -->
    <link href="assets/bootstrap/extend/navbar-fixed-top/navbar-fixed-top.css" rel="stylesheet">
    <link href="assets/bootstrap/extend/bootstrap-submenu/css/bootstrap-submenu.css" rel="stylesheet" >
    <link href="assets/bootstrap/extend/bootstrap-dataTable/css/dataTables.bootstrap.css" rel="stylesheet" >
    <link href="assets/bootstrap/extend/bootstrap-dataTable/css/buttons.dataTables.min.css" rel="stylesheet" >
    <link href="assets/bootstrap/extend/bootstrap-notify/css/bootstrap-notify.css" rel="stylesheet">
    <link href="assets/bootstrap/extend/bootstrap-notify/css/styles/alert-bangtidy.css" rel="stylesheet">
    <link href="assets/bootstrap/extend/bootstrap-image-gallery/css/blueimp-gallery.min.css" rel="stylesheet" >
    <link href="assets/bootstrap/extend/bootstrap-image-gallery/css/bootstrap-image-gallery.min.css" rel="stylesheet">
    <link href="assets/bootstrap/extend/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" >

    <!-- Owl Stylesheets -->
    <link rel="stylesheet" href="assets/owlcarousel/assets/owl.carousel.css">
    <link rel="stylesheet" href="assets/owlcarousel/assets/owl.theme.default.min.css">
     
    <!-- Custom styles for this template -->
    <link href="assets/shadowbox/shadowbox.css" rel="stylesheet" type="text/css" >
    <link href="assets/bootstrap/extend/select2-master/css/select2.css" rel="stylesheet">
   
    <!-- Default styles for this template -->
    <link rel="stylesheet" href="assets/common/css/main.css" />
    
    <!-- jQuery v1.11.3 -->
    <script src="assets/common/js/jquery.min.js"></script>

    <!-- Owl Javascript -->
    <script src="assets/owlcarousel/owl.carousel.js"></script>
   
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="assets/common/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="assets/common/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
</head>

<body>
    <div id="full-wrapper">
        <div id="top-nav">
            <div id="navigator" style="z-index:9999 !important;">
                <!-- Fixed navbar -->
                <nav class="navbar navbar-inverse navbar-fixed-top">
                    <div class="container">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                        <div id="navbar" class="navbar-collapse collapse dropdown">
                            <ul class="nav navbar-nav">
                                <li class="active"><a href="<?=$_SERVER['PHP_SELF']?>"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                                <?php
                                if(isset($_SESSION['token']) || $_SESSION['token'] != ''){
                                    switch($_SESSION['access']){
                                        case 1 :
                                        case 4 : include 'include/menu/menu_admin.php'; break;
                                        case 2 : include 'include/menu/menu_user.php'; break;
                                        case 3 : include 'include/menu/menu_verifikator.php'; break;
                                    }
                                }
                                // else{
                                //     include 'include/menu/menu_guest.php';
                                // }
                                ?>
                            </ul>
                            <ul class="nav navbar-nav navbar-right">
                                <?php
                                if(empty($_SESSION['userid']) || !isset($_SESSION['userid'])){
                                    echo '<li><a data-toggle="modal" href="#myModal" title="Sign In">Masuk</a></li>';
                                    echo '<li><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Informasi <span class="caret"></span></a>';
                                        echo '<ul class="dropdown-menu">';
                                        $sql = "select nm_link, url_link from lapas_link_terkait where isActive = 1 
                                                order by kd_link asc";
                                        $exe = mysqli_query($connDB, $sql);
                                        writeLog(__LINE__, __FILE__, mysqli_error($connDB));
                                        $x=0;
                                        while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
                                            echo '<li><a href="'.$row['url_link'].'" target="_blank" title="'.$row['nm_link'].'">'.$row['nm_link'].'</a></li>';
                                        }
                                        echo '</ul>';
                                    echo '</li>'; 
                                    echo '<li><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Tentang <span class="caret"></span></a>';
                                        echo '<ul class="dropdown-menu">';
                                        $sql = "select kd_informasi, namaInformasi from lapas_informasi_tentang where isActive = 1 
                                                order by kd_informasi asc";
                                        $exe = mysqli_query($connDB, $sql);
                                        writeLog(__LINE__, __FILE__, mysqli_error($connDB));
                                        $x=0;
                                        while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
                                            echo '<li><a href="'.BASE_URL_MENU.'?fl=about_us&id='.$row['kd_informasi'].'" title="'.$row['namaInformasi'].'">'.$row['namaInformasi'].'</a></li>';
                                        }
                                        echo '</ul>';
                                    echo '</li>'; 
                                }
                                else{
                                    echo '<li><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Hi, <b>'.strtoupper($_SESSION['username']).'</b> <span class="caret"></span></a>';
                                        echo '<ul class="dropdown-menu">';
                                            if($_SESSION['access'] == 2){
                                                echo '<li><a href="'.BASE_URL_MENU.'?fl=profile" title="profile">Profile</a></li>';
                                            }
                                            echo '<li><a tabindex="0" href="#ChangePassword" title="ChangePassword" onclick="popup(\'\',\''.BASE_URL.'script/php/form_change_passwd.php\',\'490\',\'270\');">Ubah Password</a></li>';
                                            echo '<li><a href="#signout" id="signout"><b>Sign Out</b></a></li>';
                                        echo '</ul>';
                                    echo '</li>';   
                                }
                                ?>
                            </ul>
                        </div><!--/.nav-collapse -->
                    </div>
                </nav>
            </div>
        </div>
       
       <div class="container-fluid">
            <!-- Show Notification -->
            <div class='notifications top-right'></div>
            <!-- Main Content Informasi-->
            <div id="content-informasi">
            <?php 
            echo '<input type="hidden" id="sesUserAccess" value="'.$_SESSION['userid'].'">';
            if($_GET['fl'] && file_exists("script/html/".$_GET['fl'].".html")){
                include("script/html/".$_GET['fl'].".html");    
            }
            else{
                /*------- Banner -------*/
                // echo '<div class="col-xs-10 left-banner"></div>';
                // echo '<div class="col-xs-2 right-banner"></div>'; 
                // echo '<div class="space"></div>';
                if(isset($_SESSION['access'])){
                    echo '<div class="panel" id="content-home">';
                        echo '<div class="panel-body">';
                            echo '<div class="col-md-12">';
                                echo '<div id="divTableSummary"></div>';
                            echo '</div>';
                         echo '</div>';
                    echo '</div>';  
                }
                else{

                    /* owlcarousel
                    ================================================== */
                    echo '<div class="center-block col-md-12" style="margin-top:12px;">';
                        echo '<div class="panel panel-default">';
                            echo '<div class="panel-body">';
                                echo '<div class="owl-carousel  owl-theme">';
                                    /*---- SLideshow Image  ----*/
                                    $sql = "select kd_home, imageHome, lastTitle from lapas_slidehome 
                                            where isActive = 1 order by kd_home desc limit 5";
                                    $exe = mysqli_query($connDB, $sql);
                                    writeLog(__LINE__, __FILE__, mysqli_error($connDB));
                                    $x=0;
                                    while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
                                        $x++;
                                        echo '<div class="item">';
                                            echo '<img src="'.BASE_URL.'attachment/slideshow/'.str_replace(" ","%20",$row['imageHome']).'" style="width: 100%;height: 100%;max-height: 72vh;">';
                                            echo '<h4>'.$row['lastTitle'].'</h4>';
                                        echo '</div>';
                                    }
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
            }
            ?>
            </div><!--/Informasi-->
        </div><!--/.col-xs-12.col-sm-9-->

        
        <footer>
            <div class="navbar navbar-inverse navbar-fixed-bottom" role="navigation" style="min-height:0px !important;">
                <div class="container">
                    <div id="footer-warp">
                        <p style="color:#fff;" class="text-center"><?=_FOOTER?> </p>    
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <!-- Login Modals Item -->
    <div style="display: none;margin-top:150px !important;" id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel">Form Masuk Sistem</h4>
                </div>

                <div class="modal-body">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <input type="hidden" id="action" name="action" value="loginApp" /> 
                            <div class="form-group">
                                <label for="inputID" class="sr-only">User ID</label>
                                <input type="text" id="inputID" name="inputID" class="form-control" placeholder="User ID" required autofocus autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="inputPassword" class="sr-only">Password</label>
                                <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" require autocomplete="off"d>
                            </div>
                            <div class="form-group"> 
                                <button class="btn btn-sm btn-primary" type="button" id="signin">Masuk <span class="glyphicon glyphicon-log-in"></span></button>&nbsp;<button class="btn btn-sm btn-default" type="reset" data-dismiss="modal">Batal <span class="glyphicon glyphicon-remove"></span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    <!-- Bootstrap Script -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    
    <!-- Bootstrap Extended -->
    <script src="assets/bootstrap/extend/bootstrap-submenu/js/bootstrap-submenu.min.js" defer></script>
    <script src="assets/bootstrap/extend/bootstrap-tab/bootstrap-tab.js" defer></script>
    <script src="assets/bootstrap/extend/bootstrap-modal/bootstrap-modal.js"></script>
    
    <script src="assets/bootstrap/extend/bootstrap-dataTable/js/jquery.dataTables.js"></script>
    <script src="assets/bootstrap/extend/bootstrap-dataTable/js/dataTables.bootstrap.js"></script>
    <script src="assets/bootstrap/extend/bootstrap-dataTable/js/jszip.min.js"></script>
    <script src="assets/bootstrap/extend/bootstrap-dataTable/js/pdfmake.min.js"></script>
    <script src="assets/bootstrap/extend/bootstrap-dataTable/js/buttons.html5.min.js"></script>
    <script src="assets/bootstrap/extend/bootstrap-dataTable/js/vfs_fonts.js"></script>
    <script src="assets/bootstrap/extend/bootstrap-dataTable/js/dataTables.buttons.min.js"></script>
    <script src="assets/bootstrap/extend/bootstrap-dataTable/js/buttons.print.min.js"></script>
   
    <script src="assets/bootstrap/extend/bootbox/bootbox.min.js"></script>
    <script src="assets/bootstrap/extend/bootstrap-collapse/bootstrap-collapse.js"></script>
    <script src="assets/bootstrap/extend/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

    <script src="assets/bootstrap/extend/bootstrap-image-gallery/js/jquery.blueimp-gallery.min.js"></script>
    <script src="assets/bootstrap/extend/bootstrap-image-gallery/js/bootstrap-image-gallery.min.js"></script>

    <script src="assets/bootstrap/extend/bootstrap-notify/js/bootstrap-notify.js"></script>

    <script src="assets/bootstrap/extend/select2-master/js/select2.full.js"></script>

    <script src="assets/bootstrap/extend/bootstrap-material-design/js/mdb.min.js"></script>

    <!-- Custom Onload Script -->
    <script type="text/javascript" src="assets/shadowbox/shadowbox.js"></script>
    <script type="text/javascript" src="assets/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="assets/common/js/main.js"></script>
      
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/common/js/ie10-viewport-bug-workaround.js"></script>
    
    <script type="text/javascript">
    $(document).ready(function(){
        $.ajaxPrefilter(function( options, original_Options, jqXHR ) {
            options.async = true;
        });

        var $navClick = $('.nav > li > a');
        var $navItems = $('.nav > li');

        $navClick.click(function(){
            $(".nav > li").removeClass("active");
            $(this).parent().addClass('active');
        });

        $("#signin").click(function() {
            login();
        }); 
        
        $('#inputPassword').keydown(function (e){
            if(e.keyCode == 13){
                login();
            }
        });

        ajaxloading('divTableSummary');
        $('#divTableSummary').load('<?=BASE_URL?>script/php/barchart_hasil_penilaian.php');
        
        $("#signout").click(function() {
            $.ajax({  
                type    : 'POST',
                url     : '<?=BASE_URL?>include/proses.php?act=signout',
                dataType: "json",
                success : function(data) {
                    if(data.error == false){
                        var timeout = 2000; // 3 seconds
                        var dialog = bootbox.dialog({
                            message: '<p class="text-center">'+ data.message +'</p>',
                            closeButton: false
                        });

                        setTimeout(function () {
                            dialog.modal('hide');
                            window.location = '<?=BASE_URL?>';
                        }, timeout);
                    }
                    else{
                        bootbox.alert(data.message);
                    }
                }
            });
        }); 

        $('.owl-carousel').owlCarousel({
            items: 1,
            margin: 10,
            loop: true,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            autoHeight:true
        });
    });

    function login(){
        var inputID         = $("#inputID").val();
        var inputPassword   = $("#inputPassword").val();
        var action          = $("#action").val();
        
        if(inputID == '' && inputPassword == ''){
            $("#myModal").hide();
            bootbox.alert("Maaf, Silahkan periksa kembali User ID yang anda isikan !!", function() {
                $("#myModal").show();
            });
        }
        else if(inputID == ''){
            $("#myModal").hide();
            bootbox.alert("Maaf, Form User ID harus diisi !!", function() {
                $("#myModal").show();
            });
        }
        else if(inputPassword == ''){
            $("#myModal").hide();
            bootbox.alert('Maaf, Form Password harus diisi !!', function() {
                $("#myModal").show();
            });
        }
        else{
            $.ajax({  
                type    : 'POST',
                url     : '<?=BASE_URL?>include/proses.php',
                data    : {'inputID' :  inputID,'inputPassword' : inputPassword, 'action' : action},
                dataType: "json",
                success : function(data) {
                    if(data.error == false){
                        $("#myModal").hide();
                        location.reload(true);
                    }
                    else{
                        $("#myModal").hide();
                        bootbox.alert(data.message, function() {
                            $("#myModal").show();
                        });
                    }
                },  
                error : function() {  
                    bootbox.alert(data.message, function() {
                        $("#myModal").show();
                    });  
                }  
            });
        }
        return false;   
    }
    </script>
</body>
</html>