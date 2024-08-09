<?php
session_start();
error_reporting(0);
clearstatcache();
header("Pragma: no-cache" );
header("Cache-Control: no-cache");
include	"config.php";
include	"phpfunction.php";

/* ------------------------------ Upload Image ------------------------------ */
switch($_POST['action']){
	case 'input_home' 		: $paramID = 1; break;
	case 'input_info'		: $paramID = 1; break;
	case 'input_konselor'	: $paramID = 1; break;
	default 				: $paramID = 0; break;
}

if (!empty($_FILES['userfile']['tmp_name'])) {
	$allowedExt = array('jpg', 'jpeg', 'png');
	
	for($x=0;$x<count($_FILES['userfile']['tmp_name']);$x++){
		$x = ($paramID == 1) ? ($x + 1) : $x;
		
		$userFileName = ($paramID == 1) ? $_FILES['userfile']['name'] : $_FILES['userfile']['name'][$x];
		$userTempFile = ($paramID == 1) ? $_FILES['userfile']['tmp_name'] : $_FILES['userfile']['tmp_name'][$x];
		
		$fileExt    = end(explode(".",strtolower($userFileName)));
		$fileName	= array_shift(explode(".",strtolower($userFileName)));

		if(!in_array($fileExt, $allowedExt) && !file_exists($userTempFile))
		{
			@unlink($userTempFile);
			switch($_POST['action']){
				case 'input_home' : 
					$result = array('error' => true, 'message' => 'Maaf, periksa kembali format file gambar terpilih !!');
					writeLog(__LINE__, __FILE__,'Format file gambar '.$fileExt.' tidak sesuai');
					exit();
					break;
				case 'input_info' : 
					$result = array('error' => true, 'message' => 'Maaf, periksa kembali format file gambar terpilih !!');
					writeLog(__LINE__, __FILE__,'Format file gambar '.$fileExt.' tidak sesuai');
					exit();
					break;
				case 'input_konselor' : 
					$result = array('error' => true, 'message' => 'Maaf, periksa kembali format file gambar terpilih !!');
					writeLog(__LINE__, __FILE__,'Format file gambar '.$fileExt.' tidak sesuai');
					exit();
					break;
				default :
					$result = array('error' => 'Maaf, periksa kembali format file gambar terpilih !!');
					writeLog(__LINE__, __FILE__,'Format gambar '.$fileExt.' tidak sesuai');
					break;
			}
		}
		else
		{
			switch($_POST['action']){
				case 'input_home' : 
					$directory = "home";
					break;
				case 'input_info' : 
					$directory = "info";
					break;
				case 'input_konselor' : 
					$directory = "profile";
					break;
				default : 
					$directory = "gallery";
					break;
			}
			
			$targetDir 	= '../../new/images/'.$directory.'/';
			switch($_POST['action']){
				case 'input_info' : 
					list($width, $height) = getimagesize($_FILES['userfile']['tmp_name']);	
					if ($width >= 270 || $height >= 192) {
						$destination = $targetDir . basename($fileName) . "-thumb.". $fileExt;
						getThumb($_FILES['userfile']['tmp_name'], 270, 192, $destination);
					}
					break;
				case 'input_konselor' : 
					list($width, $height) = getimagesize($_FILES['userfile']['tmp_name']);	
					if ($width >= 230 || $height >= 130) {
						$destination = $targetDir . 'thumbs/' . $_FILES['userfile']['name'];
						getThumb($_FILES['userfile']['tmp_name'], 230, 130, $destination);
						$saveThumbsFile =  BASE_URL.substr($destination, 10);
					}
					break;
				case 'input_gallery' : 
					$destination = $targetDir . 'thumbs/' . $_FILES['userfile']['name'][$x];
					getThumb($userTempFile, 65, 65, $destination);
					$saveThumbsFile =  BASE_URL.substr($destination, 10);
					break;
			}
			
			$targetFile 	= $targetDir . basename($userFileName);
			$saveTargetFile =  BASE_URL.substr($targetFile,10);

			if(move_uploaded_file($userTempFile, $targetFile)){
				switch($_POST['action']){
					case 'input_home' : 
						if($_POST['isEdit'] == '' || empty($_POST['isEdit'])){
							$sql = "insert into icprf_home (imageHome, firstTitle, lastTitle, shortDes, isActive)
									values ('".basename($userFileName)."', '".$_POST['firstTitle']."', '".$_POST['lastTitle']."', 
									'".$_POST['shortDes']."', '".$_POST['isActive']."')";
						}
						else{
							$sql = "select imageHome from icprf_home where kd_home = ".$_POST['isEdit'];
							$exe = mysqli_query($connDB, $sql);
							writeLog(__LINE__, __FILE__,mysqli_error($connDB));
							$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
							if(!unlink($targetDir.$row[0])){
								writeLog(__LINE__, __FILE__,'Lokasi directory salah : '.$targetDir.$row[0].'');
							}
			
							$sql = "update icprf_home set imageHome = '".basename($userFileName)."', firstTitle = '".$_POST['firstTitle']."', 
									lastTitle = '".$_POST['lastTitle']."', shortDes ='".$_POST['shortDes']."', isActive = '".$_POST['isActive']."'
									where kd_home = ".$_POST['isEdit'];	
						}

						$exe = mysqli_query($connDB, $sql);
						writeLog(__LINE__, __FILE__,mysqli_error($connDB));
						break;
					case 'input_info' : 
						if($_POST['isEdit'] == '' || empty($_POST['isEdit'])){
							$sql = "insert into icprf_info (titleInfo, imageInfo, imageThumb, kd_kategori, shortDes, detailDes, userEntry, entryDate)
									values ('".$_POST['titleInfo']."', '".basename($userFileName)."', '".(basename($fileName) . "-thumb.". $fileExt)."', 
									'".$_POST['kdKategori']."', '".$_POST['shortDes']."', '".$_POST['detailDes']."', '".$_POST['userEntry']."', NOW())";
						}
						else{
							$sql = "select imageInfo, imageThumb from icprf_info where kd_info = ".$_POST['isEdit'];
							$exe = mysqli_query($connDB, $sql);
							writeLog(__LINE__, __FILE__,mysqli_error($connDB));
							$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
							if($row['imageInfo'] <> basename($userFileName)){
								if(!unlink($targetDir.$row[0])){
									writeLog(__LINE__, __FILE__,'Lokasi directory salah : '.$targetDir.$row[0].'');
								}
								
								if (!unlink($targetDir.$row[1])){
									writeLog(__LINE__, __FILE__,'Lokasi directory salah : '.$targetDir.$row[1].'');
								}
								$query = ", imageInfo = '".basename($userFileName)."', imageThumb = '".(basename($fileName) . "-thumb.". $fileExt)."' "; 
							}
							
							$sql = "update icprf_info set titleInfo = '".$_POST['titleInfo']."', kd_kategori = '".$_POST['kdKategori']."', 
									shortDes ='".$_POST['shortDes']."', detailDes = '".$_POST['detailDes']."' ".$query."
									where kd_info = ".$_POST['isEdit'];	
						}

						$exe = mysqli_query($connDB, $sql);
						writeLog(__LINE__, __FILE__,mysqli_error($connDB));
						break;
					case 'input_konselor' : 
						if($_POST['isEdit'] == '' || empty($_POST['isEdit'])){
							$sql = "insert into icprf_profile_konselor (nmKonselor, imageKonselor, imageThumb, detInfo, userEntry, entryDate)
									values ('".$_POST['kdKonselor']."', '".basename($userFileName)."', '".$saveThumbsFile."', 
									'".str_replace("</p><p>", "</p><br><p>", $_POST['detInfo'])."', '".$_POST['userEntry']."', NOW())";
						}
						else{
							$sql = "select imageKonselor, imageThumb from icprf_profile_konselor where kd_profile = ".$_POST['isEdit'];
							$exe = mysqli_query($connDB, $sql);
							writeLog(__LINE__, __FILE__,mysqli_error($connDB));
							$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
							
							if($row['imageKonselor'] <> basename($userFileName)){
								if(!unlink($targetDir.$row[0])){
									writeLog(__LINE__, __FILE__,'Lokasi directory salah : '.$targetDir . $row[0].'');
								}
								
								if (!unlink($targetDir.$row[1])){
									writeLog(__LINE__, __FILE__,'Lokasi directory salah : '.$targetDir . 'thumbs/' .$row[1].'');
								}
								$query = ", imageKonselor = '".basename($userFileName)."', imageThumb = '".$saveThumbsFile."' "; 
							}
							
							$sql = "update icprf_info set titleInfo = '".$_POST['titleInfo']."', 
									detInfo = '".str_replace("</p><p>", "</p><br><p>", $_POST['detInfo'])."' ".$query."
									where kd_info = ".$_POST['isEdit'];	
						}

						$exe = mysqli_query($connDB, $sql);
						writeLog(__LINE__, __FILE__,mysqli_error($connDB));
						break;
					default :
						$sql = "insert into icprf_image_gallery (keyNumber, imageName, pathThumbsFile, pathLargeFile, entryDate, postBy)
								values ('".$_POST['keyNumber']."', '".array_shift(explode(".",$userFileName))."', '".$saveThumbsFile."', 
								'".$saveTargetFile."', NOW(), '".$_POST['postBy']."')";
						$exe = mysqli_query($connDB, $sql);
						writeLog(__LINE__, __FILE__,mysqli_error($connDB));
						$result = array('uploaded' => true);
						break;
				}
			}
			else{
				switch($_POST['action']){
					case 'input_home' : 
						$result = array('error' => true, 'message' => 'File gambar '.$userFileName.' Gagal di upload !!');
						writeLog(__LINE__, __FILE__,'File gambar '.$userFileName.' Gagal di upload !!');
						exit();
						break;
					case 'input_info' : 
						$result = array('error' => true, 'message' => 'File gambar '.$userFileName.' Gagal di upload !!');
						writeLog(__LINE__, __FILE__,'File gambar '.$userFileName.' Gagal di upload !!');
						exit();
						break;
					case 'input_konselor' : 
						$result = array('error' => true, 'message' => 'File gambar '.$userFileName.' Gagal di upload !!');
						writeLog(__LINE__, __FILE__,'File gambar '.$userFileName.' Gagal di upload !!');
						exit();
						break;
					default :
						$result = array('error' => 'Maaf, gambar tidak dapat di simpan !!');
						writeLog(__LINE__, __FILE__,'File gambar '.$userFileName.' Gagal di upload !!');
						break;
				}
			}
		}
	}
	
	switch($_POST['action']){
		case 'input_home' : 
			if($exe == true){
				@unlink($_FILES['userfile']['tmp_name']);
				$result = array('error' => false, 'message' => 'Gambar <i>Slideshow</i> berhasil disimpan !!');
			}
			else{
				$result = array('error' => true, 'message' => 'Proses penyimpanan data gagal !!');
				writeLog(__LINE__, __FILE__,'Proses penyimpanan data gagal !!');
				exit();
			}
			break;
		case 'input_info' : 
			if($exe == true){
				@unlink($_FILES['userfile']['tmp_name']);
				$result = array('error' => false, 'message' => 'Informasi berhasil disimpan !!');
			}
			else{
				$result = array('error' => true, 'message' => 'Proses penyimpanan data gagal !!');
				writeLog(__LINE__, __FILE__,'Proses penyimpanan data gagal !!');
				exit();
			}
			break;
		case 'input_konselor' : 
			if($exe == true){
				@unlink($_FILES['userfile']['tmp_name']);
				$result = array('error' => false, 'message' => 'Informasi berhasil disimpan !!');
			}
			else{
				$result = array('error' => true, 'message' => 'Proses penyimpanan data gagal !!');
				writeLog(__LINE__, __FILE__,'Proses penyimpanan data gagal !!');
				exit();
			}
			break;
	}
}
else{
	switch($_POST['action']){
		case 'input_home' : 
			if($_POST['isEdit'] == '' || empty($_POST['isEdit'])){
				$sql = "insert into icprf_home (firstTitle, lastTitle, shortDes, isActive)
						values ('".$_POST['firstTitle']."', '".$_POST['lastTitle']."', '".$_POST['shortDes']."', '".$_POST['isActive']."')";
			}
			else{
				$sql = "update icprf_home set firstTitle = '".$_POST['firstTitle']."', lastTitle = '".$_POST['lastTitle']."', 
						shortDes ='".$_POST['shortDes']."', isActive = '".$_POST['isActive']."'
						where kd_home = ".$_POST['isEdit'];
			}
			$exe = mysqli_query($connDB, $sql);
			writeLog(__LINE__, __FILE__,mysqli_error($connDB));
			if($exe == true){
				$result = array('error' => false, 'message' => 'Home Info berhasil disimpan !!');
			}
			else{
				$result = array('error' => true, 'message' => 'Proses penyimpanan data tanpa file gagal !!');
				writeLog(__LINE__, __FILE__,'Proses penyimpanan data tanpa file gagal !!');
				exit();
			}
			break;
		case 'input_info' : 
			if($_POST['isEdit'] == '' || empty($_POST['isEdit'])){
				$sql = "insert into icprf_info (titleInfo, kd_kategori, userEntry, shortDes, detailDes, entryDate)
						values ('".$_POST['titleInfo']."', '".$_POST['kdKategori']."', '".$_POST['userEntry']."', '".$_POST['shortDes']."', 
						'".$_POST['detailDes']."',NOW())";
			}
			else{
				$sql = "update icprf_info set titleInfo = '".$_POST['titleInfo']."', kd_kategori = '".$_POST['kdKategori']."', 
						shortDes ='".$_POST['shortDes']."', detailDes = '".$_POST['detailDes']."'
						where kd_info = ".$_POST['isEdit'];
			}
			$exe = mysqli_query($connDB, $sql);
			writeLog(__LINE__, __FILE__,mysqli_error($connDB));
			if($exe == true){
				$result = array('error' => false, 'message' => 'Informasi berhasil disimpan !!');
			}
			else{
				$result = array('error' => true, 'message' => 'Proses penyimpanan data tanpa file gagal !!');
				writeLog(__LINE__, __FILE__,'Proses penyimpanan data tanpa file gagal !!');
				exit();
			}
			break;
		case 'input_konselor' : 
			if($_POST['isEdit'] == '' || empty($_POST['isEdit'])){
				$sql = "insert into icprf_profile_konselor (nmKonselor, detInfo, userEntry, entryDate)
						values ('".$_POST['kdKonselor']."', '".str_replace("</p><p>", "</p><br><p>", $_POST['detInfo'])."', '".$_POST['userEntry']."', NOW())";
			}
			else{
				$sql = "update icprf_profile_konselor set nmKonselor = '".$_POST['kdKonselor']."', 
						detInfo = '".str_replace("</p><p>", "</p><br><p>", $_POST['detInfo'])."'
						where kd_profile = ".$_POST['isEdit'];
			}
			$exe = mysqli_query($connDB, $sql);
			writeLog(__LINE__, __FILE__,mysqli_error($connDB));
			if($exe == true){
				$result = array('error' => false, 'message' => 'Profile Konselor berhasil disimpan !!');
			}
			else{
				$result = array('error' => true, 'message' => 'Proses penyimpanan data tanpa file gagal !!');
				writeLog(__LINE__, __FILE__,'Proses penyimpanan data tanpa file gagal !!');
				exit();
			}
			break;
		default :
			$result = array('error' => 'Maaf, tidak ada file yang dipilih !!');
			writeLog(__LINE__, __FILE__,'Gagal di upload !! - Tidak ada file yang di pilih !!');
			break;
	}
}
echo json_encode($result);
return;
?>