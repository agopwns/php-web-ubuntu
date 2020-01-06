<?php
session_start();
include("../dbconnect.php");

// userid, username 세션에서 가져오기
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $title = $_GET['title'];
    $roomid = $_GET['roomid'];

    $sql = "insert into streaming (stm_title, stm_roomid, stm_adminid)";
    $sql = $sql. "values('$title', '$roomid', '$user_id')";
    $result = $db->query($sql);
} else {
    echo "세션 아이디 없음";
}
?>
