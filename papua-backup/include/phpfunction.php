<?php
error_reporting(1);
session_start();

function br2nl($text){
  $convert = preg_replace( "/\r|\n/", "", $text );
  return preg_replace('/<br(\s+)?\/?>/i', "\n", $convert);
}

function romawi($angka){
	$hasil 	 = "";
	$iromawi = array("", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", 20=>"XX", 30=>"XXX", 40=>"XL", 50=>"L", 60=>"LX", 70=>"LXX", 80=>"LXXX",  90=>"XC", 100=>"C", 200=>"CC", 300=>"CCC", 400=>"CD", 500=>"D", 600=>"DC", 700=>"DCC", 800=>"DCCC", 900=>"CM", 1000=>"M", 2000=>"MM", 3000=>"MMM");
	if(array_key_exists($angka,$iromawi)){
		$hasil = $iromawi[$angka];
	}
	elseif($angka >= 11 && $angka <= 99){
		$i = $angka % 10;
		$hasil = $iromawi[$angka-$i] . Romawi($angka % 10);
	}
	elseif($angka >= 101 && $angka <= 999){
		$i = $angka % 100;
		$hasil = $iromawi[$angka-$i] . Romawi($angka % 100);
	}
	else{
		$i = $angka % 1000;
		$hasil = $iromawi[$angka-$i] . Romawi($angka % 1000);
	}
	return $hasil;
}

function getBulan($bulan){
	switch ($bulan){
		case 1 : $nmBulan = "Januari"; break;
		case 2 : $nmBulan = "Februari"; break;
		case 3 : $nmBulan = "Maret"; break;
		case 4 : $nmBulan = "April"; break;
		case 5 : $nmBulan = "Mei"; break;
		case 6 : $nmBulan = "Juni"; break;
		case 7 : $nmBulan = "Juli"; break;
		case 8 : $nmBulan = "Agustus"; break;
		case 9 : $nmBulan = "September"; break;
		case 10 : $nmBulan = "Oktober"; break;
		case 11 : $nmBulan = "November"; break;
		case 12 : $nmBulan = "Desember"; break;	
	}
	return $nmBulan; 
}

function MakeDirectory($dir, $mode = 0755){
	if (is_dir($dir) || @mkdir($dir,$mode)) return TRUE;
	if (!MakeDirectory(dirname($dir),$mode)) return FALSE;
	return @mkdir($dir,$mode);
}

function writeLog($line, $filename, $message, $logfile = '') {
	global $connDB;

	if($logfile == '') {
		if (defined('_DEFAULT_LOG') == TRUE) {
			$logfile = _DEFAULT_LOG."/Log_".date('Ymd').".txt";
		}
		else {
			error_log('No log file defined!',0);
		}
	}

	if(!empty($message) || $message != ''){
		MakeDirectory(_DEFAULT_LOG);
		$fd 	= fopen($logfile, "a");
		$str 	= "[" . date("d-m-Y H:i:s", mktime()) . "] Error at line " . ($line-1) . " in file " . $filename ." : ". $message; 
		fwrite($fd, $str . "\n");
		fclose($fd);
	}
}

function login($form_ID, $form_password){
	global  $connDB;
	
	if($form_password == "" or $form_ID == ""){
		$result = array('message' => 'Maaf, periksa kembali input User ID atau password anda !!', 'error' => true);
	}
	else{
		$sql = "select kd_user, userid, username, kd_access, kd_propinsi, kd_kota, is_active, kd_wilayah
				from p_user_management where userid = '".$form_ID."' and passwd = '".$form_password."'";
		$exe = mysqli_query($connDB, $sql);
		$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		
		$kdUser 			= trim($row['kd_user']);
		$uid 				= trim($row['userid']);
		$username		= trim($row['username']);
		$userAccess 	= trim($row['kd_access']);
		$kdPropinsi 	= trim($row['kd_propinsi']);
		$kdKota 			= trim($row['kd_kota']);
		$kdWilayah 		= trim($row['kd_wilayah']);
		$encryptedUserID 	= base64_encode($username.'_'.$uid);
		
		if($exe == true){
			if($row['is_active'] == 1){
				$_SESSION['token'] 		= $encryptedUserID; 
				$_SESSION['userid']		= $uid; 
				$_SESSION['username'] 	= $username;
				$_SESSION['wilayah'] 	= $kdWilayah;
				$_SESSION['propinsi'] 	= $kdPropinsi;
				$_SESSION['kota'] 		= $kdKota;
				$_SESSION['access']		= $userAccess;
				$_SESSION['isLogin']  	= "loginPage";
				$_SESSION['kdUser']  	= $kdUser;

				$sql = "update p_user_management set online_status = '1' where userid = '".$uid."'";
				$exe = mysqli_query($connDB, $sql);
				writeLog(__LINE__, __FILE__, mysqli_error($connDB));

				$result = ($exe ==  true) ? array('error' => false) : array('message' => 'Maaf, Terjadi kesalahan system !!', 'error' => true);
			}
			else 
			{
				$result = array('message' => 'Maaf, anda belum terdaftar !! !!', 'error' => true);
			}
		}
		else{
			$result = array('message' => 'Maaf, User ID atau Password yang anda masukan tidak benar !!', 'error' => true);
		}
	}
	echo json_encode($result);
}

function getThumb($fileSrc, $width, $height, $destination){
	$fileExt    		= end(explode(".",strtolower($destination)));
	$image 				= ($fileExt == 'png') ? imagecreatefrompng($fileSrc) : imagecreatefromjpeg($fileSrc);
	$filename 			= $destination;
	$thumb_width 		= $width;
	$thumb_height 		= $height;
	$width 				= imagesx($image);
	$height 			= imagesy($image);
	$original_aspect 	= $width / $height;
	$thumb_aspect 		= $thumb_width / $thumb_height;
	
	if ( $original_aspect >= $thumb_aspect )
	{
	   // If image is wider than thumbnail (in aspect ratio sense)
	   $new_height = $thumb_height;
	   $new_width = $width / ($height / $thumb_height);
	}
	else
	{
	   // If the thumbnail is wider than the image
	   $new_width = $thumb_width;
	   $new_height = $height / ($width / $thumb_width);
	}
	$thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
	// Resize and crop
	imagecopyresampled($thumb,
					   $image,
					   0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
					   0 - ($new_height - $thumb_height) / 2, // Center the image vertically
					   0, 0,
					   $new_width, $new_height,
					   $width, $height);
	
	//if(imagejpeg($thumb, $filename, 80)) echo 1; else echo 0; 
	if(!imagejpeg($thumb, $filename, 80)){
		writeLog(__LINE__, __FILE__,'Gagal resize image !!');
	}
}

function token(){
	$chars = "abcdefghijkmnopqrstuvwxyz023456789";
	srand((double)time()*1000000);
	$i = 0;
	$token = '' ; //initialize $token
	while ($i <= 7) {
		$num 	= rand() % 33;
		$tmp 	= substr($chars, $num, 5);
		$token 	= $tmp .date('Ymd');
		$i++;
	}	
	return $token;
}

function deleteDir($path) {
    return is_file($path) ?
    @unlink($path) :
    array_map(__FUNCTION__, glob($path.'/*')) == @rmdir($path);
}

function time_stamp($time, &$waktu){ 
 	$time_difference 	= time() - $time ; 
	$seconds 			= $time_difference ; 
	$minutes 			= round($time_difference / 60 );
	$hours 				= round($time_difference / 3600 ); 
	$days 				= round($time_difference / 86400 ); 
	$weeks 				= round($time_difference / 604800 ); 
	$months 			= round($time_difference / 2419200 ); 
	$years 				= round($time_difference / 29030400 ); 
	
	if($seconds <= 60){
		$waktu = $seconds." seconds ago"; 
	}
	else if($minutes <= 60){
		if($minutes == 1){
		 	$waktu = "one minute ago"; 
		}
	   	else{
	   		$waktu = $minutes." minutes ago"; 
	   	}
	}
	else if($hours <= 24){
	   	if($hours == 1){
	   		$waktu = "one hour ago";
	   	}
	  	else{
	  		$waktu = $hours." hours ago";
	  	}
	}
	else if($days <= 7){
	  	if($days == 1){
	   		$waktu = "one day ago";
	   	}
	  	else{
	  		$waktu = $days." days ago";
	  	}
	}
	else if($weeks <= 4){
	  	if($weeks == 1){
	   		$waktu = "one week ago";
	   	}
	  	else{
	  		$waktu = $weeks." weeks ago";
	  	}
	}
	else if($months <= 12){
	   	if($months == 1){
	   		$waktu = "one month ago";
	   	}
	  	else{
	  		$waktu = $months." months ago";
	  	}
	}
	else{
		if($years == 1){
	   		$waktu = "one year ago";
	   	}
	  	else{
	  		$waktu = $years." years ago";
	  	}
	}
} 

function sendMail($EmailPenerima, $Penerima, $EmailCCPenerima, $CCPenerima, $SubjectEmail, $MailBody, $MessageSuccess){
	global  $connDB;

	$sql = "select nilai FROM p_setting limit 6,9";
	$exe = mysqli_query($connDB, $sql);
	writeLog(__LINE__, __FILE__, mysqli_error($connDB));
	$setting = array();
	while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
		$setting[] 	= $row['nilai'];
	}

	$mail = new PHPMailer;
	$mail->isSMTP(); 
	$mail->SMTPAuth 	= trim($setting[0]);	// Enable SMTP authentication                         
	$mail->Host 		= trim($setting[1]);	// Specify main and backup SMTP servers
	$mail->Port 		= trim($setting[2]);	// TCP port to connect to
	$mail->SMTPSecure 	= trim($setting[3]);	// Enable TLS encryption, ssl also accepted
	$mail->Username 	= trim($setting[4]);	// SMTP username
	$mail->Password 	= trim($setting[5]);	// SMTP password
	
	$mail->setFrom(trim($setting[6]), trim($setting[7]));

	if(count($EmailPenerima) > 1){
		for($x=0;$x<count($EmailPenerima);$x++){
			$mail->AddAddress($EmailPenerima[$x], $Penerima[$x]);
		}
	}
	else{
		$mail->AddAddress($EmailPenerima, $Penerima);
	}

	if($EmailCCPenerima != '' || count($EmailCCPenerima) != 0){
		if(count($EmailCCPenerima) > 1){
			for($x=0;$x<count($EmailCCPenerima);$x++){
				$mail->addCC($EmailCCPenerima[$x], $CCPenerima[$x]);
			}
		}
		else{
			$mail->addCC($EmailCCPenerima, $CCPenerima);
		}
	}
	
	$mail->isHTML(true);
	
	$mail->Subject 		= $SubjectEmail;
	$mail->Body    		= $MailBody;

	$result = ($mail->Send()) ? array('message' => $MessageSuccess , 'error' => false) : array('message' => '<b>Mailer Error</b><br>'.str_replace('\n',' # ',$mail->ErrorInfo).'', 'error' => true);	
	writeLog(__LINE__, __FILE__, $result['message']);
	
	return $result;
}

function logTransactionData($caseProcess, $isTable, $transactSQL, $isTransaction, $isError){
	global  $connDB;
	$sql = "insert into lapas_log_data (caseProcess, isTable, transactSQL, processDate, processBy, isTransaction, isError) 
			values ('".$caseProcess."', '".$isTable."', '".str_replace("'", "\'", $transactSQL)."', CURRENT_TIMESTAMP, 
			'".$_SESSION['username']."', '".$isTransaction."', '".$isError."')";
	$exe = mysqli_query($connDB, $sql);
	if(!$exe){
		echo 'Query Error : '.mysqli_errno($connDB).' - '.mysqli_error($connDB);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		exit();
	}

	return true;
}

function skemaPeringkatEvaluasi($keyNumber){
	global  $connDB;

	$textEvaluasi1 	= "tidak memuaskan";
	$textEvaluasi2 	= "kurang memuaskan";
	$textEvaluasi3 	= "memuaskan";
	$textEvaluasi4 	= "sangat memuaskan";

	$sql = "select kd_penilaian, nilai from monev_pascakonstruksi_penilaian 
			where keyNumber = '".$keyNumber."'
			order by 1";
	$exe = mysqli_query($connDB, $sql);
	writeLog(__LINE__, __FILE__, mysqli_error($connDB));
	$nilai 			= array();
	$kdPenilaian 	= array();
	$x=0;
	while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
		$nilai[$x] 			= $row['nilai'];
		$kdPenilaian[$x] 	= $row['kdPenilaian'];
		$x++;
	}

	$nilaiRelevansi 		= $nilai[0];
	$nilaiEfisiensi 		= $nilai[1];
	$nilaiEfektifitas 		= $nilai[2];
	$nilaiDampak 			= $nilai[3];
	$nilaiBerkelanjutan 	= $nilai[4];

	$sql = "select kategori_penilaian from p_skema_penilaian_pascakonstruksi 
			where relevansi = '".$nilaiRelevansi."' and efisiensi = '".$nilaiEfisiensi."' and efektifitas = '".$nilaiEfisiensi."' and 
			dampak = '".$nilaiDampak."' and keberlanjutan = '".$nilaiBerkelanjutan."'";
	$exe = mysqli_query($connDB, $sql);
	writeLog(__LINE__, __FILE__, mysqli_error($connDB));
	$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
	$hasilEvaluasi = $row['kategori_penilaian'];

	$sql = "update monev_pascakonstruksi_umum set peringkat_evaluasi = '".strtoupper($hasilEvaluasi)."'
			where keyNumber = '".$keyNumber."'";	
	$exe = mysqli_query($connDB, $sql);
	writeLog(__LINE__, __FILE__, mysqli_error($connDB));
	logTransactionData('inputPascaKonstruksi', 'monev_pascakonstruksi_umum', $sql, 'update', mysqli_error($connDB));

	return $exe;
}

function getColorPenilaian($kdKategori){
	if($kdKategori <= 3){
		$bgColor = $kdKategori;
	}
	else if($kdKategori > 3 && $kdKategori < 7){
		$bgColor = $kdKategori - 3;
	}
	else if($kdKategori > 6 && $kdKategori < 10){
		$bgColor = $kdKategori - 6;
	}
	
	return $bgColor;
}

?>