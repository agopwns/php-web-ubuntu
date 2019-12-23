<?php

$db = include ('dbconnect.php');

if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])) {
    // Verify data
    $email = $_GET['email']; // Set email variable
//    echo $email ."<br>";
    $hash = $_GET['hash']; // Set hash variable
//    echo $hash ."<br>";

    $sql = "SELECT * FROM member";
//    $sql = "SELECT email, hash, active FROM member WHERE mem_email='" . $email . "' AND mem_hash='" . $hash . "' AND mem_certified='N'";
    $result = $db->query($sql);

    if ($db) {
        echo "select 성공";
//        $search = mysql_query("SELECT email, hash, active FROM users WHERE email='".$email."' AND hash='".$hash."' AND active='0'") or die(mysql_error());

        // 검색 된 결과가 있다면
        if ($result->num_rows > 0) {
            // 한줄씩 데이터 검사
//            while($row = $result->fetch_assoc()){
//                echo "id: " .$row["mem_userid"]. " email: " .$row["mem_email"];
//            }
            $sql = "UPDATE member SET mem_certified='Y' WHERE mem_email='$email'";
            if($db->query($sql)){
                echo "계정 활성화 성공";
                echo "<script>document.location.href='index.html'</script>";
            } else {
                echo "계정 활성화 실패<br>";
                echo "관리자 메일로 문의바랍니다.";
            }

        }

    } else {
        echo "select 실패";
    }

}else{
    // Invalid approach
}



?>