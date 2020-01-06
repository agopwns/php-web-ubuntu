<?php
session_start();
include("../dbconnect.php");

// userid, username 세션에서 가져오기
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $roomid = $_GET['roomid'];

    $sql = "update streaming set stm_roomid='$roomid' where stm_adminid='$user_id'";
    $result = $db->query($sql);
} else {
    echo "세션 아이디 없음";
}
?>
