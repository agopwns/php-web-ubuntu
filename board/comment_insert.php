<script src="//code.jquery.com/jquery.min.js"></script>
<script src="../node/node_modules/socket.io-client/dist/socket.io.js"></script>
<?php
session_start();
//echo "db 연결 전<br>";
include("../dbconnect.php");
//echo "db 연결 후<br>";

$w = '';
$coNo = 'null';
$boId = '';

//2Depth & 수정 & 삭제
if(isset($_POST['w'])) {
    $w = $_POST['w'];
    $coNo = $_POST['co_no'];
}

$bNO = $_POST['bNO'];

if($w !== 'd') {//$w 변수가 d일 경우 $coContent와 $coId가 필요 없음.
    $coContent = $_POST['comContent'];
    if($w !== 'u') {//$w 변수가 u일 경우 $coId가 필요 없음.
        $coId = $_POST['coId'];
        if(!isset($coId))
            $coId = $_SESSION['user_id'];
    }
}

if(empty($w) || $w === 'w') { //$w 변수가 비어있거나 w인 경우
    $msg = '작성';
    $boId = $_POST['boId']; // 원 글 작성자
    echo $boId."<br>";
    echo $coId."<br>";
    $sql = 'insert into comment(com_board_id, com_order, com_content, com_userid) values( ' .$bNO . ', ' . $coNo . ', "' . $coContent . '", "' . $coId . '")';
    if(empty($w)) { //$w 변수가 비어있다면,
        $result = $db->query($sql);
        $coNo = $db->insert_id;
        $sql = 'update comment set com_order = com_id where com_id = ' . $coNo;
        ?>
        <script>
            history.back();
        </script>
        <?php
    }
} else if($w === 'u') { //작성
    $msg = '수정';
    $sql = 'select count(*) as cnt from comment where com_id = ' . $coNo;
    $result = $db->query($sql);
    $row = $result->fetch_assoc();
    if(empty($row['cnt'])) { //맞는 결과가 없을 경우 종료
        ?>
        <script>
            // alert('수정에 실패했습니다..');
            document.location.href='./board_view.php?bNO='<?php echo $bNO ?>;
        </script>
        <?php
        exit;
    }
    $sql = 'update comment set com_content = "' . $coContent . '" where com_id = ' . $coNo;
} else if($w === 'd') { //삭제
    $msg = '삭제';
    $sql = 'select count(*) as cnt from comment where com_id = ' . $coNo;
    $result = $db->query($sql);
    $row = $result->fetch_assoc();
    if(empty($row['cnt'])) { //맞는 결과가 없을 경우 종료
        ?>
        <script>
            // alert('삭제에 실패했습니다.');
            history.back();
        </script>
        <?php
        exit;
    }
    $sql = 'delete from comment where com_id = ' . $coNo;
} else {
    ?>
    <script>
        // alert('정상적인 경로를 이용해주세요.');
        history.back();
    </script>
    <?php
    exit;
}
$result = $db->query($sql);

// 코멘트 개수 증가 / 감소
if($result) {
    if($w == 'd'){
        $sql = 'update board set board_reply_count = board_reply_count - 1 where board_id = ' . $bNO;
        $result = $db->query($sql);
    } else {
        $sql = 'update board set board_reply_count = board_reply_count + 1 where board_id = ' . $bNO;
        $result = $db->query($sql);
        // 원 글 작성자와 댓글 쓴 사람이 다를 경우 noti 테이블에 전송
        if($coId != $boId){
            echo "noti insert 앞 <br>";
            $bRegTime = date("Y-m-d H:i:s");
            $sql = 'insert into notification(noti_receive_id, noti_send_id, noti_board_id, noti_regtime) values("' .$boId . '", "' . $coId . '", "' . $bNO . '", "' . $bRegTime . '")';
            echo $sql;
            $result = $db->query($sql);
            ?>
            <script>
                // 81번 포트 알림 전송 서버와 소켓 통신 연결
                console.log('연결 전');
                socket = io.connect(':81');
                console.log('포트 담은 후');

                socket.on('connect', function() {
                    console.log('socket connect 안');
                    socket.emit('login', { uid: '<?php echo $coId?>'});
                    console.log('서버에  login 해서 아이디 보내기 성공');
                });
                // 원 글 작성자에게 메시지 전송
                socket.emit('message special user', { uid:'<?php echo $boId?>', msg: '<?php echo $boId?>' + ',' + '<?php echo $coId?>' + ',' + '<?php echo $bNO?>'});
            </script>
            <?php
        }
    }
    ?>
    <script>
        // alert('수정에 실패했습니다..');
        document.location.href='./board_view.php?bNO='<?php echo $bNO ?>;
    </script>
    <?php
} else {
    ?>
    <script>
        //alert('댓글 <?php //echo $msg?>//에 실패했습니다.');
        history.back();
    </script>
    <?php
    exit;
}

//$sql = "insert into comment(com_board_id,com_content,com_userid) values($bNO,'$comContent','$bUserId')";
//echo $sql;
//$result = $db->query($sql);
//$comNO = $db->insert_id;
//$sql = 'update comment set com_order = com_id where com_id = ' . $comNO;
//$result = $db->query($sql);


?>