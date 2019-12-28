<?php
session_start();
include("../dbconnect.php");

$target_dir = "/usr/local/apache2/htdocs/board/uploads/";
$target_file = $target_dir . basename($_FILES["upload"]["name"]);
$db_target_dir = "/board/uploads/";
$db_target_file = $db_target_dir . basename($_FILES["upload"]["name"]);
echo $target_file."<br>";
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
echo $imageFileType."<br>";
// 파일이 이미지인지 체크
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["upload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// 파일이 이미 있는지 체크
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// 파일 사이즈 체크
if ($_FILES["upload"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// 파일 포맷 체크
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// $uploadOk 0 이라면 에러
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// 모든 조건을 만족시 업로드
} else {
    if (move_uploaded_file($_FILES["upload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["upload"]["name"]). " has been uploaded.";
        // 업로드 성공시 DB insert
        if(isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $user_permission = $_SESSION['user_permission'];
            $bTitle = $_POST['bTitle'];
            $bContent = $_POST['bContent'];
            $bRegTime = date("Y-m-d H:i:s");
            $bType = "I"; // 일반글 N 사진 I
            $bCategory = $_POST['bBoard_name'];
            $bPermission = "";
            if ($user_permission == 'N') {
                $bPermission = 'N';
            } else {
                $bPermission = 'Y';
            }
            $sql = "insert into board (board_userid, board_title, board_content, board_sfile_path, board_regtime, board_category, board_type, board_super)";
            $sql = $sql . "values('$user_id', '$bTitle', '$bContent', '$db_target_file', '$bRegTime','$bCategory','$bType','$bPermission')";
            $result = $db->query($sql);
        }

    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}




?>