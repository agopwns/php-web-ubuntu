<?php

$db = include ('dbconnect.php');

if(isset($_POST['id']) && !empty($_POST['id']) AND isset($_POST['pass']) && !empty($_POST['pass'])) {
    $id = $_POST['id']; // Set email variable
    $pass = md5($_POST['pass']); // Set hash variable

    $sql = "SELECT * FROM member WHERE mem_userid='" . $id . "' AND mem_password='" . $pass . "' AND mem_certified='Y'";
    $result = $db->query($sql);

    // 유저 권한 확인
    $row = $result->fetch_assoc();
    $user_permission = $row['mem_is_super'];

    if ($db) {
//        echo "select 성공";
        // 검색 된 결과가 있다면
        if ($result->num_rows > 0) {
//            session_destroy();
            session_start();
            $_SESSION['user_id'] = $id;
            $_SESSION['user_permission'] = $user_permission;
            echo "true";
        } else {
            session_destroy();
            echo "false";
        }

    } else {
        echo "select fail";
    }

}




?>