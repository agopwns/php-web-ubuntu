<?php

$db = include ('../dbconnect.php');

if(isset($_POST['id']) && !empty($_POST['id'])) {
    // Verify data
    $id = $_POST['id']; // Set email variable

    $sql = "SELECT * FROM member WHERE mem_userid = '$id'";
    $result = $db->query($sql);

    if ($result) {
//        echo "select 성공";
//        $search = mysql_query("SELECT email, hash, active FROM users WHERE email='".$email."' AND hash='".$hash."' AND active='0'") or die(mysql_error());

        // 검색 된 결과가 있다면
        if ($result->num_rows > 0) {
            // 한줄씩 데이터 검사
//            while($row = $result->fetch_assoc()){
//                echo "id: " .$row["mem_userid"]. " email: " .$row["mem_email"];
//            }
            echo "사용중인 아이디입니다.";
        } else {
            echo "사용 가능한 아이디입니다.";
        }

    } else {
        echo "시스템 오류(0080) : 관리자에게 문의하세요.";
    }

}else{
    // Invalid approach
}



?>