<?php
session_start();
include("../dbconnect.php");

// userid, username 세션에서 가져오기
if(isset($_SESSION['user_id'])) {
    // 세션 존재할 때만 글 입력 처리
    $user_id = $_SESSION['user_id'];
    $bNO = $_GET['board_id'];

    // 추천 확인 테이블에 먼저 select 해서
    $sql = "SELECT * FROM recommend_board WHERE rcm_board_id='$bNO' and rcm_userid ='$user_id'";
    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        // 있으면 board_recommend_count --
        $sql = "update board set board_recommend_count = board_recommend_count - 1 WHERE board_id='$bNO'";
        $result = $db->query($sql);

        $sql = "DELETE FROM recommend_board WHERE rcm_board_id = '$bNO' AND rcm_userid = '$user_id'";
        $result = $db->query($sql);
        echo "decrease";
    } else {
        // 없으면 insert 하고
        $sql = "insert into recommend_board (rcm_board_id, rcm_userid)";
        $sql = $sql. "values('$bNO', '$user_id')";
        $result = $db->query($sql);

        if($result){
            // board_recommend_count ++
            $sql = "update board set board_recommend_count = board_recommend_count + 1 WHERE board_id='$bNO'";
            $result = $db->query($sql);
            echo "increase";
        } else {
            echo "insert fail";
        }
    }

} else {
    echo "session fail";
}

?>