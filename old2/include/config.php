<?php
error_reporting(1);
session_start();
clearstatcache();

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header("Cache-Control: no-cache, must-revalidate" );
header("Pragma: no-cache");
header("Cache-Control: no-cache");


	
define('BASE_URL', "http://".$_SERVER['HTTP_HOST']."/");
define('BASE_URL_MENU', "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
define('IS_ROOT', $_SERVER['DOCUMENT_ROOT']."/");
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

#-------------- KONEKSI DB MONEV -----------------------#
define('_HOST', 'localhost');
define('_USER', 'u1083135_lapasok');
define('_PASS', 'maryo1387*#');
define('_DBSE', 'u1083135_lapasok');

$connDB = mysqli_connect(_HOST, _USER, _PASS, _DBSE);	
if (mysqli_connect_errno())	{		
	echo "<b>Connection failed to DB - Error Message: [" . mysqli_connect_error($connDB) . "]</b>";
	exit;
}

global $connDB;

$_SESSION['BASE_URL']	= BASE_URL;
$_SESSION['IS_ROOT'] 	= IS_ROOT;
// $_SESSION['access']		= 1; //Sample Access

$sql = "select nm_param, nilai from p_setting order by kd_setting";
$exe = mysqli_query($connDB, $sql);
while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
	$nilai = (trim($row['nm_param']) == 'default_log') ? IS_ROOT.trim($row['nilai']) : trim($row['nilai']);
	define ("_".trim(strtoupper($row['nm_param'])), $nilai);
	//echo "_".trim(strtoupper($row['nm_param']))." - ".$nilai."<br>";	
}
	
//Sengaja gk di tutup tag PHP nya
