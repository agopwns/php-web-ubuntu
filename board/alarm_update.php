<?php
session_start();
//echo "db 연결 전<br>";
include("../dbconnect.php");
//echo "db 연결 후<br>";

// userid, username 세션에서 가져오기
if(isset($_SESSION['user_id'])) {
    // 세션 존재할 때만 글 입력 처리
    $user_id = $_SESSION['user_id'];

    $sql = "update notification set noti_is_check='Y' WHERE noti_receive_id='$user_id'";
    $result = $db->query($sql);
    if($result)
        echo "true";
    else
        echo "false";

} else {
//    echo "세션 아이디 없음";
}

?>
