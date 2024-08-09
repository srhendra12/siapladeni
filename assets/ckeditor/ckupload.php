<?php
$urlUpload  = '../../upload/ckeditor/'.time()."_".$_FILES['upload']['name'];

#$urlImages  = 'common/lib/ckeditor/images/upload/'.time()."_".$_FILES['upload']['name'];
$urlImages  = 'http://localhost/bappeda/web/upload/ckeditor/'.time()."_".$_FILES['upload']['name'];

//extensive suitability check before doing anything with the file…
if (($_FILES['upload'] == "none") or (empty($_FILES['upload']['name'])) ){
  $message = "No file uploaded.";
}
else if ($_FILES['upload']["size"] == 0){
  $message = "The file is of zero length.";
}
else if (($_FILES['upload']["type"] != "image/pjpeg") and ($_FILES['upload']["type"] != "image/jpeg") and ($_FILES['upload']["type"] != "image/png")){
  $message = "The image must be in either JPG or PNG format. Please upload a JPG or PNG instead.";
}
else if (!is_uploaded_file($_FILES['upload']["tmp_name"])){
  $message = "You may be attempting to hack our server. We're on to you; expect a knock on the door sometime soon.";
}
else {
  $message  = "";
  $move     = move_uploaded_file($_FILES['upload']['tmp_name'], $urlUpload);
  if(!$move){
     $message = "Error moving uploaded file. Check the script is granted Read/Write/Modify permissions.";
  }
  $url = "" . $url;
}
$funcNum = $_GET['CKEditorFuncNum'] ;
echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction(".$funcNum.", '".$urlImages."', '".$message."');</script>";
?>