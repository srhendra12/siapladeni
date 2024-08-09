<?php
error_reporting(1);
session_start();
clearstatcache();

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header("Cache-Control: no-cache, must-revalidate" );
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true ");
header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Depth, User-Agent, X-File-Size, X-Requested-With, If-Modified-Since, X-File-Name, Cache-Control");


function base_url(){
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$domainName = $_SERVER['HTTP_HOST'];
	return $protocol.$domainName.'/';
}

define('BASE_URL', base_url());
define('BASE_URL_MENU', base_url().$_SERVER['PHP_SELF']);
define('IS_ROOT', $_SERVER['DOCUMENT_ROOT']."/");
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

#-------------- KONEKSI DB MONEV -----------------------#
define('_HOST', 'localhost');
define('_USER', 'siapladeni');
define('_PASS', 'fM73x3StAMB0*');
define('_DBSE', 'siapladeni');

$connDB = mysqli_connect(_HOST, _USER, _PASS, _DBSE);	
if (mysqli_connect_errno())	{		
	echo "<b>Connection failed to DB - Error Message: [" . mysqli_connect_error($connDB) . "]</b>";
	exit;
}

global $connDB;

$_SESSION['BASE_URL']	= BASE_URL;
$_SESSION['IS_ROOT'] 	= IS_ROOT;
// $_SESSION['access']		= 1; //Sample Access

$wilayah = empty($_SESSION['wilayah']) ? 1 : $_SESSION['wilayah'];
$sql = "select nm_param, nilai from p_setting where kd_wilayah = ".$wilayah." order by kd_setting";$exe = mysqli_query($connDB, $sql);
while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
	$nilai = (trim($row['nm_param']) == 'default_log') ? IS_ROOT.trim($row['nilai']) : trim($row['nilai']);
	define ("_".trim(strtoupper($row['nm_param'])), $nilai);
	//echo "_".trim(strtoupper($row['nm_param']))." - ".$nilai."<br>";	
}
	
//Sengaja gk di tutup tag PHP nya
