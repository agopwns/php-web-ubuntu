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
    $bTitle = $_POST['bTitle'];
    echo $bTitle."<br>";
    $bContent = $_POST['bContent'];
    echo $bContent."<br>";
    // 입력 시간
    $bRegTime = date("Y-m-d H:i:s");
    echo $bRegTime."<br>";
    // 게시판 타입 // N : normal, I: image
    $bType = "N";
    // 게시판 카테고리
    $bCategory = $_POST['bBoard_name'];
    echo $bCategory."<br>";

    $sql = "insert into board (board_userid, board_title, board_content, board_regtime, board_category, board_type)";
    $sql = $sql. "values('$user_id', '$bTitle', '$bContent', '$bRegTime','$bCategory','$bType')";
    $result = $db->query($sql);

    if($result) { // query가 정상실행 되었다면,
//        $msg = "정상적으로 글이 등록되었습니다.";

          // TODO : 글 쓰기 DB 입력 성공시 board_view.php/?num=글번호로 이동!!

//        $bNo = $db->insert_id;
//        $replaceURL = './view.php?bno=' . $bNo;
    } else {
//        $msg = "글을 등록하지 못했습니다.";
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