<?php
session_start();
error_reporting(0);
clearstatcache();
header("Pragma: no-cache" );
header("Cache-Control: no-cache");

require "config.php";
require "phpfunction.php";

switch($_POST['action']){
	case 'add_tentang' :
		$fileName = "";
		if(file_exists($_FILES['userfile']['tmp_name'])){
			$allowedExt = array('jpg', 'jpeg', 'png');
			$fileExt    = end(explode(".",strtolower($_FILES['userfile']['name'])));
			$fileName	= array_shift(explode(".",strtolower($_FILES['userfile']['name'])));
	
			if(!in_array($fileExt, $allowedExt)){
				@unlink($_FILES['userfile']['tmp_name']);
				$result = array('error' => true, 'message' => 'Maaf, periksa kembali format file gambar terpilih !!');
				writeLog(__LINE__, __FILE__,'Format file gambar '.$fileExt.' tidak sesuai');
				echo json_encode($result);
				exit();
			}
			else{
				$targetDir	= realpath(dirname(dirname(__FILE__)))."/attachment/aboutUs/";
				$targetFile = $targetDir . basename($_FILES['userfile']['name']);
			
				if(move_uploaded_file($_FILES['userfile']['tmp_name'], $targetFile)){
					$fileName = basename($_FILES['userfile']['name']);
				}
				else{
					$result = array('error' => true, 'message' => 'File gambar '.$_FILES['userfile']['name'].' Gagal di upload !!');
					writeLog(__LINE__, __FILE__,'File gambar '.$_FILES['userfile']['name'].' Gagal di upload !!');
					echo json_encode($result);
					exit();
				}
			}
		}

		if($_POST['isEdit'] == '' || empty($_POST['isEdit'])){
			$sql = "insert into lapas_informasi_tentang (namaInformasi, jenisInput, imageInformasi, deskripsiInformasi, isActive)	values ('".$_POST['namaInformasi']."', '".$_POST['jenisInput']."', '".$fileName."', '".$_POST['deskripsiInformasi']."', '".$_POST['isActive']."')";
		}
		else{
			$sql = "select imageInformasi from lapas_informasi_tentang where kd_informasi = ".$_POST['isEdit'];
			$exe = mysqli_query($connDB, $sql);
			writeLog(__LINE__, __FILE__, mysqli_error($connDB));
			$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
			if(!unlink($targetDir.$row[0])){
				writeLog(__LINE__, __FILE__,'Lokasi directory salah : '.$targetDir.$row[0].'');
			}

			$updateImg = ($fileName != '' || !empty($fileName)) ? ", imageInformasi = '".$fileName."'" : "";

			$sql = "update lapas_informasi_tentang set namaInformasi = '".$_POST['namaInformasi']."', 
					jenisInput = '".$_POST['jenisInput']."', ".$updateImg."
					deskripsiInformasi = '".$_POST['deskripsiInformasi']."', isActive = '".$_POST['isActive']."'
					where kd_informasi = ".$_POST['isEdit'];	
		}

		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));

		if($exe == true){
			@unlink($_FILES['userfile']['tmp_name']);
			$result = array('error' => false, 'message' => 'Informasi berhasil disimpan !!');
		}
		else{
			$result = array('error' => true, 'message' => 'Proses penyimpanan data gagal !!');
			writeLog(__LINE__, __FILE__,'Proses penyimpanan data gagal !!');
		}
		
		echo json_encode($result);
		break;
	case 'add_link' :
		if($_POST['isEdit'] == ''){
			$sql = "insert into lapas_link_terkait (nm_link, url_link, isActive) 
					values ('".$_POST['nmLink']."', '".$_POST['urlLink']."', '".$_POST['isActive']."')";
		}
		else{
			$sql = "update lapas_link_terkait set nm_link = '".$_POST['nmLink']."', url_link = '".$_POST['urlLink']."', 
					isActive = '".$_POST['isActive']."' where kd_link = ".$_POST['isEdit'];	
		}
		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		$result = ($exe == true) ? array('message' => ' Data link informasi terkait berhasil disimpan !!', 'error' => false) : array('message' => 'Error: Data Gagal disimpan !!', 'error' => true);	
		echo json_encode($result);
		break;
		break;
	case 'update_passwd' :
		$message = '<b class="txtBlue">Kata sandi berhasil di perbaharui !!</b><br>System akan secara otomatis Keluar dari halaman ini dan silahkan anda Masuk kembali dengan menggunakan kata sandi baru anda.<br>Terima Kasih !';
		$sql = "update p_user_management set passwd = '".md5(md5("pa".$_POST['newpass']."ss"))."', resetcode = ''
				where kd_user = ".$_POST['userID'];
		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		$result = ($exe == true) ? array('message' => $message, 'error' => false) : array('message' => 'Error: Data Gagal disimpan !!', 'error' => true);
		echo json_encode($result);	
		break;
	case 'add_slideshow' :
		$fileName = "";
		if(file_exists($_FILES['userfile']['tmp_name'])){
			$allowedExt = array('jpg', 'jpeg', 'png');
			$fileExt    = end(explode(".",strtolower($_FILES['userfile']['name'])));
			$fileName	= array_shift(explode(".",strtolower($_FILES['userfile']['name'])));
	
			if(!in_array($fileExt, $allowedExt)){
				@unlink($_FILES['userfile']['tmp_name']);
				$result = array('error' => true, 'message' => 'Maaf, periksa kembali format file gambar terpilih !!');
				writeLog(__LINE__, __FILE__,'Format file gambar '.$fileExt.' tidak sesuai');
				echo json_encode($result);
				exit();
			}
			else{
				$targetDir	= realpath(dirname(dirname(__FILE__)))."/attachment/slideshow/";
				$targetFile = $targetDir . basename($_FILES['userfile']['name']);
			
				if(move_uploaded_file($_FILES['userfile']['tmp_name'], $targetFile)){
					$fileName = basename($_FILES['userfile']['name']);
				}
				else{
					$result = array('error' => true, 'message' => 'File gambar '.$_FILES['userfile']['name'].' Gagal di upload !!');
					writeLog(__LINE__, __FILE__,'File gambar '.$_FILES['userfile']['name'].' Gagal di upload !!');
					echo json_encode($result);
					exit();
				}
			}
		}

		if($_POST['isEdit'] == '' || empty($_POST['isEdit'])){
			$sql = "insert into lapas_slidehome (imageHome, lastTitle, isActive)	values ('".$fileName."', '".$_POST['lastTitle']."', '".$_POST['isActive']."')";
		}
		else{
			$sql = "select imageHome from lapas_slidehome where kd_home = ".$_POST['isEdit'];
			$exe = mysqli_query($connDB, $sql);
			writeLog(__LINE__, __FILE__, mysqli_error($connDB));
			$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
			if(!unlink($targetDir.$row[0])){
				writeLog(__LINE__, __FILE__,'Lokasi directory salah : '.$targetDir.$row[0].'');
			}

			$sql = "update lapas_slidehome set imageHome = '".$fileName."', lastTitle = '".$_POST['lastTitle']."', isActive = '".$_POST['isActive']."'
					where kd_home = ".$_POST['isEdit'];	
		}

		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));

		if($exe == true){
			@unlink($_FILES['userfile']['tmp_name']);
			$result = array('error' => false, 'message' => 'Gambar <i>Slideshow</i> berhasil disimpan !!');
		}
		else{
			$result = array('error' => true, 'message' => 'Proses penyimpanan data gagal !!');
			writeLog(__LINE__, __FILE__,'Proses penyimpanan data gagal !!');
		}
		
		echo json_encode($result);
		break;
	case 'add_account' :
		if(empty($_POST['isEdit']) || $_POST['isEdit'] == ''){
			if($_POST['userAccess'] < 4){
				$sql = "select userid from p_user_management where kd_propinsi = '".$_POST['kdPropinsi']."' and kd_kota = '".$_POST['kdKota']."' 
				and kd_access = '".$_POST['userAccess']."'";
				$exe = mysqli_query($connDB, $sql);
				writeLog(__LINE__, __FILE__, mysqli_error($connDB));
				$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
				$userid = $row['userid'];
				if(!empty($userid)){
					$result = array('message' => 'Maaf, Sudah terdapat User untuk Wilayah UPT Pemasyarakatan terpilih !', 'error' => true); 
					echo json_encode($result);	
					exit();
				}
			}
			
			$sql = "insert into p_user_management (userid, passwd, username, email, kd_access, kd_propinsi, kd_kota, kd_wilayah,
					is_active, online_status, datecreate, resetcode)
					values ('".$_POST['userid']."', '".md5(md5("pa".$_POST['password']."ss"))."', '".$_POST['username']."', '".$_POST['email']."', '".$_POST['userAccess']."', '".$_POST['kdPropinsi']."', '".$_POST['kdKota']."', '".$_POST['kdWilayah']."',
					'".$_POST['isActive']."', '0', NOW(), '')";
		}
		else{
			$password = (!empty($_POST['password']) || $_POST['password'] != '') ? "passwd = '".md5(md5("pa".$_POST['password']."ss"))."'," : "";
			$sql = "update p_user_management set ".$password." username = '".$_POST['username']."', 
					email = '".$_POST['email']."', kd_access = '".$_POST['userAccess']."', kd_propinsi = '".$_POST['kdPropinsi']."',
					kd_kota = '".$_POST['kdKota']."', kd_wilayah = '".$_POST['kdWilayah']."', is_active = '".$_POST['isActive']."' where kd_user = '".$_POST['isEdit']."'";	
		}
		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		$result = ($exe == true) ? array('message' => 'Data User berhasil disimpan !!', 'error' => false) : array('message' => 'Error: Data Gagal disimpan !!', 'error' => true); 
		echo json_encode($result);	
		break;
	case 'input_akses_user' :
		if($_POST['isEdit'] == ''){
			$sql = "insert into p_user_access (nm_access) values ('".$_POST['aksesUser']."')";
		}
		else{
			$sql = "update p_user_access set nm_access = '".$_POST['aksesUser']."' where kd_access = ".$_POST['isEdit'];	
		}
		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		$result = ($exe == true) ? array('message' => ' Data akses user berhasil disimpan !!', 'error' => false) : array('message' => 'Error: Data Gagal disimpan !!', 'error' => true);	
		echo json_encode($result);
		break;
	case 'addKeteranganTolak' :
		$splitData = explode("_", $_POST['kdDataMonev']);
		$sql = "insert into lapas_keterangan_ditolak(kd_dataMonev, keterangan, keyNumber, entry_date, entry_by, 
				status, jenisData)
				values('".$splitData[1]."', '".$_POST['keterangan']."', '".$splitData[0]."', NOW(), 
				'".$_SESSION['username']."', '1', '".$_POST['jenis']."')";
		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		logTransactionData("keteranganDitolak", "monev_".$_POST['jenis']."_ditolak", $sql, "insert", mysqli_error($connDB));

		$sqp = "update ".$_POST['table']." set is_confirm = '0', is_rejected = '1', rejected_date = NOW(), 
				rejected_by = '".$_SESSION['username']."'
				where kd_".$_POST['jenis']."_umum = '".$splitData[1]."'";	
		$exp = mysqli_query($connDB, $sqp);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		logTransactionData("rejectedData", $_POST['table'], $sqp, "update", mysqli_error($connDB));

		$result = ($exe && $exp  == true) ? array('error' => false, 'message' => 'Keterangan <b>'.ucwords($_POST['jenis']).'</b> ditolak berhasil disimpan !!') : array('message' => 'Error: Data Gagal disimpan !!', 'error' => true); 
		echo json_encode($result);	
		break;
	case 'upload_dokumen' :
		for($x=0;$x<$_POST['participants'];$x++){
			if(file_exists($_FILES['userfile']['tmp_name'][$x])){
				$targetdir 	= realpath(dirname(dirname(__FILE__)))."/attachment/dokumen_pendukung/";
				$allowedExt = array('jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx', 'pdf');

				$fileExt 	= end(explode(".",strtolower($_FILES['userfile']['name'][$x])));
				$fileName 	= array_shift(explode(".",$_FILES['userfile']['name'][$x]));
				if(!in_array($fileExt, $allowedExt))
				{
					@unlink($_FILES['userfile']['tmp_name'][$x]);
					$result = array('error' => true, 'message' =>  'Maaf, periksa kembali format lampiran dokumen Evaluasi yang anda unggah!!');
					writeLog(__LINE__, __FILE__,'Format file lampiran dokumen pendukung '.$fileExt.' tidak sesuai');
					echo json_encode($result);
					exit();
				}
				else{
					mkdir($targetdir, 0777, true);
					chmod($targetdir, 0777);
					
					$pathFile = $targetdir.$_FILES['userfile']['name'][$x];

					if(!move_uploaded_file($_FILES['userfile']['tmp_name'][$x], $pathFile)){
						@unlink($_FILES['userfile']['tmp_name'][$x]);
						$result = array('error' => true, 'message' => 'File '.basename($_FILES['userfile']['name'][$x]).' gagal di upload !!');
						writeLog(__LINE__, __FILE__,'File '.basename($_FILES['userfile']['name'][$x]).' gagal di upload !!');
						echo json_encode($result);
						exit();
					}
					$fileUpload[$x] = basename($_FILES['userfile']['name'][$x]);
				}
			}
			else{
				$fileUpload[$x] = "";
			}

			$sql = "insert into p_dokumen_pendukung (nmFile, keterangan, jenis_dokumen, isActive, kd_wilayah) 
					values ('".$fileUpload[$x]."', '".$_POST['keterangan'][$x]."', '".$_POST['jenis_dokumen'][$x]."' , 1, '".$_SESSION['wilayah']."')";
			$exe = mysqli_query($connDB, $sql);
			writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		}

		$result = ($exe == true) ? array('message' => ' Dokumen Pendukung berhasil di unggah !!', 'error' => false) : array('message' => 'Error: Dokumen Gagal diunggah !!', 'error' => true);	
		echo json_encode($result);
		break;
	case 'input_nilai' :
		if($_POST['isEdit'] == ''){
			$sql = "insert into p_nilai (nilai, keterangan) 
					values ('".$_POST['nilai']."', '".$_POST['keterangan']."')";
		}
		else{
			$sql = "update p_nilai set nilai = '".$_POST['nilai']."', keterangan = '".$_POST['keterangan']."'
					where kdNilai = ".$_POST['isEdit'];	
		}
		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		$result = ($exe == true) ? array('message' => ' Data parameter nilai berhasil disimpan !!', 'error' => false) : array('message' => 'Error: Data Gagal disimpan !!', 'error' => true);	
		echo json_encode($result);
		break;
	case 'input_upt' :
		if($_POST['isEdit'] == ''){
			$sql = "insert into kabupatenkota (kabupatenkotaname, r_provinsiid, alamat, email, nama_kepala, no_telp_kepala ) 
					values ('".$_POST['nmUpt']."', '".$_POST['wilayahID']."', '".$_POST['alamat']."', '".$_POST['email']."', 
					'".$_POST['nmKepala']."', '".$_POST['noTelp']."')";
		}
		else{
			$sql = "update kabupatenkota set kabupatenkotaname = '".$_POST['nmUpt']."', r_provinsiid = '".$_POST['wilayahID']."',
						alamat = '".$_POST['alamat']."', email = '".$_POST['email']."', nama_kepala = '".$_POST['nmKepala']."', 
						no_telp_kepala = '".$_POST['noTelp']."'
					where kabupatenkotaid = ".$_POST['isEdit'];	
		}
		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		$result = ($exe == true) ? array('message' => ' Data UPT Pemayarakatan berhasil disimpan !!', 'error' => false) : array('message' => 'Error: Data Gagal disimpan !!', 'error' => true);	
		echo json_encode($result);
		break;
		break;
	case 'input_wilayah' :
		if($_POST['isEdit'] == ''){
			$sql = "insert into provinsi (provinsiname) 
					values ('".$_POST['nm_wilayah']."')";
		}
		else{
			$sql = "update provinsi set provinsiname = '".$_POST['nm_wilayah']."'
					where provinsiid = ".$_POST['isEdit'];	
		}
		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		$result = ($exe == true) ? array('message' => ' Data Wilayah berhasil disimpan !!', 'error' => false) : array('message' => 'Error: Data Gagal disimpan !!', 'error' => true);	
		echo json_encode($result);
		break;
	case 'input_perilaku_napi' :
		if($_POST['isEdit'] == ''){
			$sql = "insert into p_range_perilaku_napi (nmKategori, nilaiBatasBawah, nilaiBatasAtas) 
					values ('".$_POST['nmKategori']."', '".$_POST['nilaiBatasBawah']."', 
					'".$_POST['nilaiBatasAtas']."')";
		}
		else{
			$sql = "update p_range_perilaku_napi set nmKategori = '".$_POST['nmKategori']."', 
					nilaiBatasBawah = '".$_POST['nilaiBatasBawah']."', 
					nilaiBatasAtas = '".$_POST['nilaiBatasAtas']."' 
					where kd_kategori = ".$_POST['isEdit'];	
		}
		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		$result = ($exe == true) ? array('message' => ' Penilaian Kecenderungan perilaku narapidana/tahanan !!', 'error' => false) : array('message' => 'Error: Data Gagal disimpan !!', 'error' => true);	
		echo json_encode($result);
		break;
	case 'verifyPenilaian' :
		if($_POST['keterangan'] != ''){
			$status = ($_POST['status'] == 'verify') ? 1 : 0;
			$sql = "insert into monev_catatan_penilaian(kd_penilaian, keterangan, kd_sektor, keyNumber, entry_date, entry_by, 
					status, jenisData)
					values('".$_POST['kdPenilaian']."', '".$_POST['keterangan']."', '".$_POST['sektor']."', '".$_POST['keynumber']."', NOW(), 
					'".$_SESSION['username']."', '".$status."', '".$_POST['output']."')";
			$exe = mysqli_query($connDB, $sql);
			writeLog(__LINE__, __FILE__, mysqli_error($connDB));
			logTransactionData("verifikasiPenilaian".ucwords($_POST['status']), "monev_".$_POST['jenis']."_evaluasi", $sql, "insert", mysqli_error($connDB));
		}
		
		switch($_POST['status']){
			case 'verify' : 
				$qryUpdate = "is_verify = '1', is_reject = '0', verify_date = NOW(), verify_by = '".$_SESSION['username']."'";
			break;
			case 'reject' : 
				$qryUpdate = "is_verify = '0', is_reject = '1', verify_date = '0000-00-00 00:00:00', verify_by = ''";
			break;
			case 'batal' : 
				$qryUpdate = "is_verify = '0', is_reject = '0', verify_date = '0000-00-00 00:00:00', verify_by = ''";
			break;
		}
		$sql = "update monev_".$_POST['output']."_evaluasi set ".$qryUpdate." 
				where kd_".$_POST['output']."_evaluasi = '".$_POST['kdPenilaian']."' and keyNumber = '".$_POST['keynumber']."'";
		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		$result = ($exe == true) ? array('error' => false) : array('message' => 'Error: Data Gagal disimpan !!', 'error' => true);	
		echo json_encode($result);
		break;
	case 'inputPerencanaan' :
		if(empty($_POST['isEdit']) || $_POST['isEdit'] == ''){
			$sql = "delete from lapas_perencanaan_umum where keyNumber = '".trim($_POST['keyNumber'])."'";
			$exe = mysqli_query($connDB, $sql);
			writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		}

		$sql = "delete from lapas_perencanaan_param_evaluasi where keyNumber = '".trim($_POST['keyNumber'])."'";
		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));

		$sql = "delete from lapas_perencanaan_penilaian_evaluasi where keyNumber = '".trim($_POST['keyNumber'])."'";
		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));

		// Insert Informasi Umum
		$kdPropinsi = (empty($_POST['kdPropTemp'])) ? $_POST['kdPropinsi'] : $_POST['kdPropTemp'];
		$kdKota = (empty($_POST['kdKotaTemp'])) ? $_POST['kdKota'] : $_POST['kdKotaTemp'];

		if(file_exists($_FILES['dokumen']['tmp_name'])){
			$targetdir 	= realpath(dirname(dirname(__FILE__)))."/attachment/dokumen_keabsahan/".$kdPropinsi."/".$kdKota."/".$_POST['keyNumber']."/";
			$allowedExt = array('pdf');

			$fileExt 	= end(explode(".",strtolower($_FILES['dokumen']['name'])));
			$fileName 	= array_shift(explode(".",$_FILES['dokumen']['name']));
			if(!in_array($fileExt, $allowedExt))
			{
				@unlink($_FILES['dokumen']['tmp_name']);
				$result = array('error' => true, 'message' =>  'Maaf, periksa kembali format lampiran dokumen pernyataan keabsahan data yang anda unggah!!');
				writeLog(__LINE__, __FILE__,'Format file lampiran dokumen '.$fileExt.' tidak sesuai');
				echo json_encode($result);
				exit();
			}
			else{
				mkdir($targetdir, 0777, true);
				chmod($targetdir, 0777);

				$pathFile = $targetdir.$_FILES['dokumen']['name'];
			
				if(!move_uploaded_file($_FILES['dokumen']['tmp_name'], $pathFile)){
					@unlink($_FILES['dokumen']['tmp_name']);
					$result = array('error' => true, 'message' => 'File '.basename($_FILES['dokumen']['name']).' gagal di upload !!');
					writeLog(__LINE__, __FILE__,'File '.basename($_FILES['dokumen']['name']).' gagal di upload !!');
					echo json_encode($result);
					exit();
				}

				$dokumen = basename($_FILES['dokumen']['name']);
			}
		}

		if(empty($_POST['isEdit']) || $_POST['isEdit'] == ''){
			$isTransaction = 'insert';
			$sql1 = "insert into lapas_perencanaan_umum (provinsiid, kabupatenkotaid, tahun_data, keterangan, 
						sumPotensi, sumSkor, valPersentasePetugas, valPerilakuNapi, dokumen, keyNumber, entry_date, entry_by) 
						values ('".$_POST['kdPropinsi']."', '".$kdKota."', '".$_POST['tahun_data']."".$_POST['bulan_data']."', 
						'".$_POST['keterangan']."', '".$_POST['sumPotensi']."', '".$_POST['sumSkor']."', 
						'".$_POST['valPersentasePetugas']."', '".$_POST['valPerilakuNapi']."', '".$dokumen."',
						'".$_POST['keyNumber']."', NOW(), '".$_SESSION['username']."')";
		}
		else{
			$isTransaction = 'update';
			$isDokumen = (!empty($dokumen) || $dokumen != "") ? "dokumen = '".$dokumen."', " : "";

			$sql1 = "update lapas_perencanaan_umum set keterangan = '".$_POST['keterangan']."', 
						sumPotensi = '".$_POST['sumPotensi']."', sumSkor = '".$_POST['sumSkor']."', 
						valPersentasePetugas = '".$_POST['valPersentasePetugas']."', 
						valPerilakuNapi = '".$_POST['valPerilakuNapi']."', ".$isDokumen." last_update_date = NOW(), 
						last_update_by = '".$_SESSION['username']."'
						where kd_perencanaan_umum = '".$_POST['isEdit']."'";	
		}
		#echo $sql1;
		$exe1 = mysqli_query($connDB, $sql1);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		logTransactionData('inputPerencanaan', 'lapas_perencanaan_umum', $sql1, $isTransaction, mysqli_error($connDB));

		$isSQL = ($isTransaction ==  'insert') ? ", entry_date, entry_by" : ", last_update_date, last_update_by";

		// Insert Evaluasi
		for($x=0;$x<count($_POST['nilai']);$x++){
			if($_POST['nilai'][$x] !== ''){
				// echo $_POST['kdKriteria'][$x]." - ".$_POST['kdSubKriteria'][$x]." - ".$_POST['kdParameter'][$x]." - ".$x." - ".$_POST['nilai'][$x]."<br>";

				$sql2 = "insert into lapas_perencanaan_param_evaluasi (kd_tahap_perencanaan, kd_sub_tahap_perencanaan, kd_parameter, 
							nilai, keyNumber ".$isSQL.") 
							values ('".trim($_POST['kdKriteria'][$x])."', '".trim($_POST['kdSubKriteria'][$x])."', 
							'".trim($_POST['kdParameter'][$x])."', '".$_POST['nilai'][$x]."', '".$_POST['keyNumber']."', NOW(), '".$_SESSION['username']."')";
				// echo $sql2."<br>";
				$exe2 = mysqli_query($connDB, $sql2);
				writeLog(__LINE__, __FILE__, mysqli_error($connDB));
				logTransactionData('inputPerencanaan', 'lapas_perencanaan_param_evaluasi', $sql2, $isTransaction, mysqli_error($connDB));
			}
			else{
				$exe2 = true;
			}
		}

		// Insert Nilai Bobot
		for($x=0;$x<count($_POST['totalSkor']);$x++){
			if($_POST['totalSkor'][$x] !== ''){
				// echo $_POST['kdKriteriaBobot'][$x]." - ".$_POST['kdSubKriteriaBobot'][$x]." - ".$_POST['totalSkor'][$x]." - ".$x." - ".$_POST['skorPotensi'][$x]." - ".$_POST['catatan'][$x]."<br>";

				$sql3 = "insert into lapas_perencanaan_penilaian_evaluasi (kd_tahap_perencanaan, kd_sub_tahap_perencanaan,  
							totalSkor, skorPotensi, catatan, keyNumber ".$isSQL.") 
							values ('".trim($_POST['kdKriteriaBobot'][$x])."', '".trim($_POST['kdSubKriteriaBobot'][$x])."', 
							'".$_POST['totalSkor'][$x]."', '".$_POST['skorPotensi'][$x]."', '".$_POST['catatan'][$x]."', '".$_POST['keyNumber']."', NOW(), '".$_SESSION['username']."')";
				// echo $sql3."<br>";
				$exe3 = mysqli_query($connDB, $sql3);
				writeLog(__LINE__, __FILE__, mysqli_error($connDB));
				logTransactionData('inputPerencanaan', 'lapas_perencanaan_penilaian_evaluasi', $sql3, $isTransaction, mysqli_error($connDB));
			}
			else{
				$exe3 = true;
			}
		}

		$result = ($exe1 == true && $exe2 == true && $exe3 == true) ? array('message' => ' Instrument Deteksi Dini Potensi Gangguan Keamanan dan Ketertiban Periode '.$_POST['tahun_data'].''.$_POST['bulan_data'].' <br>berhasil disimpan !!', 'error' => false) : array('message' => 'Error: Data Gagal disimpan !!', 'error' => true); 
		echo json_encode($result);
		break;
	case 'input_range_perencanaan' :
		if($_POST['isEdit'] == ''){
			$sql = "insert into p_range_tahap_perencanaan (nmKategori, nilaiBatasBawah, nilaiBatasAtas) 
					values ('".$_POST['nmKategori']."', '".$_POST['nilaiBatasBawah']."', 
					'".$_POST['nilaiBatasAtas']."')";
		}
		else{
			$sql = "update p_range_tahap_perencanaan set nmKategori = '".$_POST['nmKategori']."', 
					nilaiBatasBawah = '".$_POST['nilaiBatasBawah']."', 
					nilaiBatasAtas = '".$_POST['nilaiBatasAtas']."' 
					where kd_kategori = ".$_POST['isEdit'];	
		}
		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		$result = ($exe == true) ? array('message' => ' Penilaian Kecenderungan Pemahaman Petugas !!', 'error' => false) : array('message' => 'Error: Data Gagal disimpan !!', 'error' => true);	
		echo json_encode($result);
		break;
	
	case 'add_sub_tahap_perencanaan' :
		if(empty($_POST['isEdit']) || $_POST['isEdit'] == ''){
			$sql = "insert into p_sub_tahap_perencanaan (indikator, kd_tahap_perencanaan, bobot, is_active, keyNumber)
					values ('".$_POST['indikator']."', '".$_POST['kd_pelaksaaan']."', '".$_POST['bobot']."', 
					'".$_POST['isActive']."', '".$_POST['keyNumber']."')";
		}
		else{
			$sql = "update p_sub_tahap_perencanaan set indikator = '".$_POST['indikator']."', 
					bobot = '".$_POST['bobot']."', is_active = '".$_POST['isActive']."' 
					where kd_sub_tahap_perencanaan = '".$_POST['isEdit']."'";	
		}
		$exe1 = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));

		$sql = "select kd_sub_tahap_perencanaan from p_sub_tahap_perencanaan where keyNumber = '".$_POST['keyNumber']."'";
		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		$res = mysqli_fetch_array($exe, MYSQLI_ASSOC);
		$kd_subPelaksaaan = $res['kd_sub_tahap_perencanaan'];

		for($x=0;$x<$_POST['participants'];$x++){
			if($_POST['parameter'][$x] !=''){
				$isMandatory = (isset($_POST['isMandatory'][$x])) ? 1 : 0;
				$sql = "insert into p_param_sub_tahap_perencanaan (kd_sub_tahap_perencanaan, kd_tahap_perencanaan, nm_parameter, deskripsi, isMandatory, is_active) values ('".$kd_subPelaksaaan."', '".$_POST['kd_pelaksaaan']."', '".$_POST['parameter'][$x]."', '".$_POST['deskripsi'][$x]."', '".$isMandatory."', '".$_POST['isActive']."')";
				$exe2 = mysqli_query($connDB, $sql);
				writeLog(__LINE__, __FILE__, mysqli_error($connDB));
			}
			else{
				$exe2 = true;
			}
		}
		$result = ($exe1 == true && $exe2 == true) ? array('message' => 'Data Point Assessment Instrument Deteksi Dini<br>berhasil disimpan !!', 'error' => false) : array('message' => 'Error: Data Gagal disimpan !!', 'error' => true); 
		echo json_encode($result);	
		break;
	case 'add_tahap_perencanaan' :
		if(empty($_POST['isEdit']) || $_POST['isEdit'] == ''){
			$sql = "insert into p_tahap_perencanaan (indikator, is_active) values ('".$_POST['indikator']."', '".$_POST['isActive']."')";
		}
		else{
			$sql = "update p_tahap_perencanaan set indikator = '".$_POST['indikator']."', is_active = '".$_POST['isActive']."' 
					where kd_tahap_perencanaan = '".$_POST['isEdit']."'";	
		}
		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		$result = ($exe == true) ? array('message' => 'Data Elemen Assessment Instrument Deteksi Dini<br>berhasil disimpan !!', 'error' => false) : array('message' => 'Error: Data Gagal disimpan !!', 'error' => true); 
		echo json_encode($result);	
		break;
	case 'manage_setting' :
		for($x=1;$x<=$_POST['jumData'];$x++){
			$sql = "update p_setting set nilai = '".$_POST['input'.$x]."' where kd_setting = ".$x;
			$exe = mysqli_query($connDB, $sql);
			writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		}
		$result = ($exe == true) ? array('message' => 'Data berhasil diperbaharui !!', 'error' => false) : array('message' => 'Error: Data Gagal disimpan !!', 'error' => true);
		echo json_encode($result);
		break;
	case 'loginApp' :
		login($_POST['inputID'], md5(md5("pa".$_POST['inputPassword']."ss")));
		break;
	case 'enableDisable' :
		$nilai 		= ($_POST['value'] == 1) ? 0 : 1;
		$whereData	= ($_POST['param'] == 'kd_user') ? $_POST['param'] : "kd_".$_POST['param'];

		switch($_POST['jenis']){
			case 'is_verify' :
				$sqlUpdate = ", verify_by = '".$_SESSION['username']."', verify_date = NOW()";
				$logHistory = "verifyData";
				break;
			case 'is_confirm' :
				$sqlUpdate = ", is_rejected = '0', confirm_by = '".$_SESSION['username']."', confirm_date = NOW()";
				$logHistory = "confirmData";
				break;
			case 'is_close' :
				$sqlUpdate = ", close_by = '".$_SESSION['username']."', close_date = NOW()";
				$logHistory = "closingData";
				break;	
			default :
				$sqlUpdate = "";
				$logHistory = "enableDisableData";
				break;	
		}

		$sql = "update ".$_POST['table']." set ".$_POST['jenis']." = '".$nilai."' ".$sqlUpdate."
				where ".$whereData."= ".$_POST['id'];
		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		logTransactionData($logHistory, $_POST['table'], $sql, "update", mysqli_error($connDB));
		
		if($_POST['jenis'] == 'is_confirm'){
			switch($_POST['table']){
				case 'monev_pascakonstruksi_umum' : $jenisData = "pascakonstruksi"; break;
				case 'monev_pelaksanaan_umum' : $jenisData = "pelaksanaan"; break;
				case 'lapas_perencanaan_umum' : $jenisData = "perencanaan"; break;
			}

			$sqc = "update monev_keterangan_ditolak set status = '0' 
					where kd_dataMonev = ".$_POST['id']." and jenisData = '".$jenisData."'";
			$exc = mysqli_query($connDB, $sqc);
			writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		}
		
		$result = ($exe == true) ? array('error' => false) : array('error' => true);
		echo json_encode($result);
		break;
}

#:-------------------------------------------------------------------------------------------------------------------------------:#
#:-------------------------------------------------------------------------------------------------------------------------------:#
#:-------------------------------------------------------------------------------------------------------------------------------:#

switch($_GET['act']){
	case 'signout' :
		session_unset();
		session_destroy();
		$result = array('message' => 'Terima kasih !!', 'error' => false);
		echo json_encode($result);
		break;
	case 'cekValidasiData' :
		$sql 	= "select kd_user from p_user_management where ".$_GET['param']." = '".$_GET['data']."'";
		$exe 	= mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		$row 	= mysqli_fetch_array($exe, MYSQLI_ASSOC);
		$userID = $row['kd_user'];
		
		switch($_GET['param']){
			case 'email' 		: $errorMsg = 'Maaf, Alamat Email yang anda masukan sudah terdaftar !!'; break;
			case 'userid' 		: $errorMsg = 'Maaf, User ID sudah terdaftar !!'; break;
		}
		
		$result = (empty($userID) or $userID =='') ? array('error' => false, 'catMsg' => $_GET['param']) : array('error' => true, 'catMsg' => $_GET['param'], 'errorMsg' => $errorMsg);
		echo json_encode($result);
		break;
	case 'getPropinsi' :
		if(intval($_GET['param']) == 0 && $_GET['param'] != ''){
			$sql = "select provinsiid from provinsi where lower(provinsiname) like '%".str_replace("_"," ",$_GET['param'])."%'";
			$exe = mysqli_query($connDB, $sql);
			writeLog(__LINE__, __FILE__, mysqli_error($connDB));
			$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);	
			$result = array('idProp' => $row['provinsiid']);
			echo json_encode($result);
		}
		else{
			echo "<option></option>";
			$sql = "select provinsiid, provinsiname from provinsi where kd_wilayah = '".$_GET['idwilayah']."' order by provinsiid";
			$exe = mysqli_query($connDB, $sql);
			writeLog(__LINE__, __FILE__, mysqli_error($connDB));
			$i=0;
			while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
				$i++;
				$selected = ($row['provinsiid'] == $_GET['idprop']) ? "selected" : "";
				echo "<option value='".$row['provinsiid']."' ".$selected.">".$i.". ".$row['provinsiname']."</option>";
			}	
		}
		break;
	case 'getKota' :
		echo "<option></option>";
		$sql = "select kabupatenkotaname, kabupatenkotaid from kabupatenkota where r_provinsiid = '".$_GET['idprop']."' 
				order by kabupatenkotaid";
		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		$i=0;
		while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
			$i++;
			$selected = ($_GET['idkota'] == $row['kabupatenkotaid'] ) ? 'selected' : '';
			echo "<option value='".$row['kabupatenkotaid']."' ".$selected.">".$i.". ".$row['kabupatenkotaname']."</option>";
		}
		break;
	case 'delete_data' :
		switch($_GET['table']){
			case 'lapas_perencanaan_umum' :
				$sql = "delete from lapas_perencanaan_umum where keyNumber = '".$_GET['id']."'";
				$exe = mysqli_query($connDB, $sql);
				writeLog(__LINE__, __FILE__, mysqli_error($connDB));

				$sql = "delete from lapas_perencanaan_penilaian_evaluasi where keyNumber = '".$_GET['id']."'";
				$exe = mysqli_query($connDB, $sql);
				writeLog(__LINE__, __FILE__, mysqli_error($connDB));

				$sql = "delete from lapas_perencanaan_param_evaluasi where keyNumber = '".$_GET['id']."'";
				$exe = mysqli_query($connDB, $sql);
				writeLog(__LINE__, __FILE__, mysqli_error($connDB));

				logTransactionData('deleteDataPerencanaan', 'lapas_perencanaan_umum', "delete from lapas_perencanaan_umum where keyNumber = '".$_GET['id']."'", "delete", mysqli_error($connDB));
				break;
			default :
				$sql = "delete from ".$_GET['table']." where ".$_GET['param']." = '".$_GET['id']."'";
				$exe = mysqli_query($connDB, $sql);
				writeLog(__LINE__, __FILE__, mysqli_error($connDB));
				break;
		}
		$result = ($exe == true) ? array('error' => false, 'message' => 'Data BERHASIL di hapus !!') : array('error' => true, 'message' => 'Error: Data GAGAL di hapus !!');
		echo json_encode($result);
		break;
	case 'getKategori' :
		$sql = "select kd_kategori, nmKategori from ".$_GET['table']." 
				where '".$_GET['value']."' between nilaiBatasBawah and nilaiBatasAtas";
		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
		$nmKategori = ($row['nmKategori'] != null) ? $row['nmKategori'] : "";
		$kdKategori = $row['kd_kategori'];
		$isDateUpdate = ($kdKategori == 2 || $kdKategori == 5 || $kdKategori == 8) ? true : false;

		$result = ($exe == true) ? array('error' => false, 'kdKategori' => $kdKategori, 'nmKategori' => $nmKategori, 'isDateUpdate' => $isDateUpdate) : array('error' => true );
		echo json_encode($result);
		break;
	
	case 'getLokasi' :
		$sql = "select  provinsiname, provinsiid from provinsi where provinsiid = '".$_GET['param']."'";	
		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
		$result = ($exe == true) ? array('error' => false, 'nmPropinsi' => $row['provinsiname']) : array('error' => true);
		echo json_encode($result);
		break;
		case 'getShowReason' :
			$splitData = explode("_", $_GET['keyNumber']);
			echo '<table class="table table-hover table-condensed">';
				echo '<tr bgColor="#f0f0f0">';
					echo '<th>No</th>';
					echo '<th>Keterangan</th>';
					echo '<th>Tgl. Ditolak</th>';
					echo '<th>Oleh</th>';
					echo '<th>Status Data</th>';
					echo '<th>Jenis Data</th>';
				echo '</tr>';
			$sql = "select keterangan, entry_date, entry_by, status from lapas_keterangan_ditolak 
					where keyNumber = '".$splitData[1]."' and kd_dataMonev = '".$splitData[0]."' and jenisData =  '".$_GET['jenis']."' 
					order by status desc";
			$exe = mysqli_query($connDB, $sql);
			writeLog(__LINE__, __FILE__, mysqli_error($connDB));
			$x=0;
			while($row = mysqli_fetch_array($exe, MYSQLI_ASSOC)){
				$x++;
				$status = ($row['status'] == 1) ? 'glyphicon glyphicon-remove' : 'glyphicon glyphicon-ok';
				echo '<tr>';
					echo '<td align="center"><b>'.$x.'</b></td>';
					echo '<td>'.$row['keterangan'].'</td>';
					echo '<td align="center">'.$row['entry_date'].'</td>';
					echo '<td align="center">'.$row['entry_by'].'</td>';
					echo '<td align="center"><i class="'.$status.' fa-2x"></i></td>';
					echo '<td align="center">'.ucwords($_GET['jenis']).'</td>';
				echo '</tr>';
			}
			echo '</table>';
			break;
	case 'getIsExist' :
		$sql = "select tahun_data from lapas_perencanaan_umum where tahun_data = '".$_GET['periode']."' and kabupatenkotaid = ".$_SESSION['kota'];	
		$exe = mysqli_query($connDB, $sql);
		writeLog(__LINE__, __FILE__, mysqli_error($connDB));
		$row = mysqli_fetch_array($exe, MYSQLI_ASSOC);
		$periodeData = $row['tahun_data'];

		$result = (empty($periodeData) or $periodeData =='') ? array('error' => false) : array('error' => true);
		echo json_encode($result);
		break;
}
?>