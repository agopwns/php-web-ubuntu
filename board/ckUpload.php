<?php
session_start();
include("../dbconnect.php");

if ($_FILES["upload"]["size"] > 0){
    $date_filedir = date ("YmdHis");
    $ext = substr(strrchr($_FILES["upload"]["name"], "."), 1);
    $ext = strtolower($ext);
    $savefilename = $date_filedir."_".str_replace(" ", "_", $_FILES["upload"]["name"]);

    $uploadpath = $_SERVER['DOCUMENT_ROOT']."/board/uploads/";
    $uploadsrc = $_SERVER['HTTP_HOST']."/board/uploads/";
    $http = 'http' . ((isset($_SERVER['HTTP']) && $_SERVER['HTTPS']='on') ? 's':'') . '://';

    if($ext=="jpg" or $ext=="gif" or $ext == "png"){
        if(move_uploaded_file($_FILES['upload']['tmp_name'], $uploadpath."/".iconv("UTF-8", "EUC-KR", $savefilename))){
            $uploadfile = $savefilename;
            echo ' {"filename" : "'. $uploadfile .'", "uploaded" : 1, "url":"' . $http.$uploadsrc.$uploadfile . '"}';
        }
    }
}



?>