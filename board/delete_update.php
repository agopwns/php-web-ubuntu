<?php
session_start();
echo "db 연결 전<br>";
include("../dbconnect.php");
echo "db 연결 후<br>";

// userid, username 세션에서 가져오기
if(isset($_SESSION['user_id'])) {
    // 세션 존재할 때만 글 입력 처리
    $user_id = $_SESSION['user_id'];
    echo $user_id."<br>";
    $bNO = $_GET['bNO'];

    $sql = "update board set board_status='D' WHERE board_id='$bNO'";
    $result = $db->query($sql);

    if($result) { 
    // query가 정상실행 되었다면,
//        $msg = "정상적으로 글이 수되었습니다.";
          // TODO : 글 쓰기 DB 입력 성공시 board_view.php/?num=글번호로 이동!!
        $replaceURL = '../index.html';
        echo "<script>location.href='$replaceURL'</script>";
    } else {
//        $msg = "글을 수정하지 못했습니다.";
        ?>
        <script>
            alert("<?php echo $msg?>");
            // history.back();
        </script>
        <?php
    }
} else {
    echo "세션 아이디 없음";
}

?>
<script>
    //alert("<?php //echo $msg?>//");
    //location.replace("<?php //echo $replaceURL?>//");
</script>