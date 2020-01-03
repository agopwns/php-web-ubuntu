<?php
    session_start();
    $host = '127.0.0.1'; // 고정 IP 바꾸기 전에 127.0.0.1
    $user = 'root';
    $pw = 'root';
    $dbName = 'web_db';
    $db = mysqli_connect($host, $user, $pw, $dbName);

    $user_id = $_SESSION['user_id'];
    // 알림 개수
//    $sql = "SELECT * FROM notification WHERE noti_receive_id='$user_id'";
//    $result = $db->query($sql);

    if($user_id != ''){
        $csql = "select count(*) as cnt from notification where noti_receive_id = '$user_id' and noti_is_check = 'N' order by noti_id desc";
        $notiCount = 0;
//    echo $sql;
        $countResult = $db->query($csql);
        if($countResult->num_rows > 0){
            $row = $countResult->fetch_assoc();

            if(!empty($row['cnt'])) { //맞는 결과가 없을 경우 종료
                $notiCount = $row['cnt'];
            }
        }

        // 알림 글 리스트
        $cSql = "SELECT * FROM notification WHERE noti_receive_id='$user_id' and noti_is_check = 'N' order by noti_id desc";
        $cResult = $db->query($cSql);
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Welcome to the best Community">
    <meta name="keywords" content="Peanut Community">
    <title>Peanut Community</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="//code.jquery.com/jquery.min.js"></script>
    <script src="../node/node_modules/socket.io-client/dist/socket.io.js"></script>
</head>
<style>
    #notiBar{

        display:none;  /* 평상시에는 서브메뉴가 안보이게 하기 */
        height:auto;
        padding:0px;
        margin:0px;
        margin-top: 60px;
        border:0px;
        font-size: 12px;
        position:absolute;
        width:170px;
        z-index:200;
    }
    #underNoti{
        display:none;  /* 평상시에는 서브메뉴가 안보이게 하기 */
        height:auto;
        padding:0px;
        margin:0px;
        margin-top: 60px;
        border:0px;
        font-size: 12px;
        position:fixed;
        top: 0px;
        right: 0px;
        width:300px;
        height: 100px;
        z-index:201;
    }

</style>
<script>
    // 숨기기 초기화
    $(document).ready(function() {
        // document.getElementById("isLogin").style.display="none";
        // document.getElementById("aLogout").style.display="none";
        $('html').scrollTop(0);
    });

    var isDrop = false;

    function login() {
        var idValue = $("#inputId").val();
        var passValue = $("#inputPassword").val();
        $.ajax({
            url:"../login.php",
            type:"POST",
            data:{id:idValue, pass:passValue},
            datatype:"html",
            success:function(data){
                // 성공시 html 영역 바꿔주기
                if(data == "true"){

                    // socket.on('connect', function() {
                    //     console.log('socket connect 안');
                    //     socket.emit('login', { uid: idValue });
                    //     console.log('서버에  login 해서 아이디 보내기 성공');
                    // });
                    document.location.href='../index.html';
                } else {
                    alert("아이디와 비밀번호를 다시 확인해주세요.");
                }
            }
        });
    }

    function notiDrop(){
        if(isDrop){
            document.getElementById('notiBar').style.display ='none';
            isDrop = false;
        } else {
            // 드롭다운 되는 순간에 ajax롤 서버와 통신하여
            // 알림 확인 업데이트
            document.getElementById('notiBar').style.display ='block';
            isDrop = true;
            $.ajax({
                url:"../board/alarm_update.php",
                type:"POST",
                datatype:"html",
                success:function(data){
                    // 성공시 html 영역 바꿔주기
                    if(data == "true"){
                        // notiCount 초기화
                        var notiCount = document.getElementById("notiCount").innerText;
                        console.log('현재 카운트 : ' + notiCount);

                        // 문서에 반영
                        notiCount = '';
                        $("#notiCount").text(notiCount);
                        console.log('초기화 후 카운트 : ' + notiCount);
                    }
                }
            });

        }

    }

    var id = '<?php echo  $_SESSION['user_id'] ?>';
    // 소켓 통신
    var socket = null;
    if(id !== '' && id !== null){
        // 81번 포트 알림 전송 서버와 소켓 통신 연결
        console.log('연결 전');
        socket = io.connect(':81');
        console.log('포트 담은 후');

        socket.on('connect', function() {
            console.log('socket connect 안');
            socket.emit('login', { uid: id});
            console.log('서버에  login 해서 아이디 보내기 성공');
        });
    }
    // 특정 유저에게 메세지 전송
    // 임시 테스트용이며 해당 부분은 board_view 로 옮길 것
    // if(id !== '' && id != null)
    //     socket.emit('message special user', { uid:id, msg: id + '에게만 보내는 메시지입니다.' });

    // 서버측에서 socket.send(msg); 한것을 받아 살행
    if(id !== '' && id != null){
        socket.on('message', function (msg) {
            console.log('서버로부터 수신한 메세지 : ' + msg);
            //alert('새 글 알림! ' +  msg);
            //document.write(msg);

            // 서버로부터 메세지를 받은 경우 notiCount 값 증가
            var notiCount = document.getElementById("notiCount").innerText;

            if(notiCount == ''){
                notiCount *= 1;
                notiCount = 1;
            } else {
                notiCount *= 1;
                notiCount++;
            }
            // 문서에 반영
            notiCount = '' + notiCount;
            $("#notiCount").text(notiCount);

            // 글 번호, 댓글 쓴 사람 id 값을 토대로 엘리먼트 추가
            var boId = msg.split(',')[0];
            console.log(boId);
            var coId = msg.split(',')[1];
            console.log(coId);
            var boNo = msg.split(',')[2];
            console.log(boNo);
            // <li><a style='background-color: #353535; padding:10px; width: 170px;'
            // href='board_view.php?bNO=$nBoardId'>$nSendId 님이 회원님의 게시글에 댓글을 달았습니다.</a></li>";

            // notiBar > li > a > 내용
            var addText = "<li><a style='background-color: #353535; padding:10px; width: 170px;' href='board_view.php?bNO=" + boNo + "'>" + coId + " 님이 회원님의 게시글에 댓글을 달았습니다.</a></li>";
            $("#notiBar").prepend(addText);

            addText = "<a style='background-color:#b7b7b7;color:#fff;padding:10px;width:200px;'href='board_view.php?bNO="
                + boNo + "'>" + coId + " 님이 회원님의 게시글에 댓글을 달았습니다.</a>";
            var test = $("#underNoti");
            test.fadeOut(1);
            test.prepend(addText);

            test.fadeIn(2000);
            var list = document.getElementById("underNoti");
            setTimeout(function(){ test.fadeOut(2000); },3000);
            setTimeout(function(){ list.removeChild(list.childNodes[0]); },5500);

        });
    }
    function page_move(s_name){
        var f=document.movepage; //폼 name
        f.bName.value = s_name; //POST방식으로 넘기고 싶은 값
        f.action="../board/board_list.php";//이동할 페이지
        f.method="post";//POST방식
        f.submit();
    }


</script>
<body>
<div class="parent_container">
    <div id="underNoti"></div>
    <nav id="navbar">
<!--        <div style="position: fixed; bottom: 600px; right: 130px;">-->
<!--            <a href="#">TOP</a>-->
<!--        </div>-->
        <div class="container">
            <h1 class="logo"><a href="../index.html">Peanut Community</a></h1>
            <ul>
                <?php
                    if(isset($_SESSION['user_id'])) {
                        // 세션 존재시
                        $user_id = $_SESSION['user_id'];
                        echo "<li id='isLogin' style='margin-left: 40px; margin-top: 11px'><p>$user_id 님 환영합니다.</p></li>";
                        echo "<li id='notiIcon'><a style='display: flex; float: left; padding: 20px 10px 20px 10px;  margin-left: 10px; cursor:pointer; ' onclick='notiDrop()'>";
                        echo "<img src='../img/noti.png' style='background-color: #292929; margin-top: 5px; margin-right: 3px; width: 18px; height: 18px;'>";
                        echo "<p id='notiCount' style='margin:0px; background-color: #292929;'>";
                        if($notiCount != 0) echo $notiCount;
                        echo "</p>";
                        echo "</a>";
                        echo "<ul id='notiBar'>";
                        if($cResult->num_rows > 0){
                            $index = 0;
                            while ($row = $cResult->fetch_assoc()) {
                                $nReceiveId = $row['noti_receive_id'];
                                $nSendId = $row['noti_send_id'];
                                $nBoardId = $row['noti_board_id'];
                                $nRegtime = $row['noti_regtime'];
                                echo "<li ><a style='background-color: #353535; padding:10px; width: 170px;' href='board_view.php?bNO=$nBoardId'>$nSendId 님이 회원님의 게시글에 댓글을 달았습니다.</a></li>";
                                $index++;
                                if($index > 4) break;
                            }
                            echo "<li ><a style='background-color: #353535; padding:10px; width: 170px;' href='board_view.php?bNO=$nBoardId'>모두 보기</a></li>";
                        }
                        echo "</ul>";
                        echo "</li>";
                        echo "<li ><a href='../logout.php' style='display: block' id='aLogout'>로그아웃</a></li>";
                    } else {
                        // 세션 없을시
//                        echo "세션 없음";
                        echo "<li><input type='text' style='color: #fff;' id='inputId'/></li>";
                        echo "<li><input type='password' style='color: #fff;' id='inputPassword'/></li>";
                        echo "<li><a style='cursor:pointer' onclick='login()' id='aLogin'>로그인</a></li>";
                        echo "<li><a href='../join/signUp.php' id='aRegister'>회원가입</a></li>";
                        echo "<li><a href='../index.html' id='aFindPass'>비밀번호 찾기</a></li>";
                    }
                ?>
            </ul>
        </div>

        <div class="menubar-container">
            <ul>
                <li><a href="../board/board_list.php?bName=최근글">최근 글</a></li>
                <li><a href="../board/board_list.php?bName=레전드 BEST">레전드 BEST</a></li>
                <li><a href="../board/board_list.php?bName=MONTH BEST">MONTH BEST</a></li>
                <li><a href="../board/board_list.php?bName=WEEK BEST">WEEK BEST</a></li>
                <li><a href="../board/board_list.php?bName=DAY BEST">DAY BEST</a></li>
                <li><a href="../board/board_list.php?bName=공지">공지</a></li>
                <li><a href="../board/board_list.php?bName=자유">자유</a></li>
                <li><a href="../board/board_list.php?bName=장터">장터</a></li>
                <li><a href="../board/board_list.php?bName=문의">문의</a></li>
            </ul>
        </div>

        <div class="menubar-container">
            <ul>
<!--                <li>-->
<!--                    <form name="form1" action="../board/board_list.php" method="POST">-->
<!--                        <input type="hidden" name="bName" id="bName" value="패션">-->
<!--                        <input type="submit" value="패션">-->
<!--                    </form>-->
<!--                </li>-->

                <li><a href="../board/board_list.php?bName=패션">패션</a></li>
                <li><a href="../board/board_list.php?bName=드라마">드라마</a></li>
                <li><a href="../board/board_list.php?bName=영화">영화</a></li>
                <li><a href="../board/board_list.php?bName=다큐">다큐</a></li>
                <li><a href="../board/board_list.php?bName=애니">애니</a></li>
                <li><a href="../board/board_list.php?bName=만화">만화</a></li>
                <li><a href="../board/board_list.php?bName=연예">연예</a></li>
                <li><a href="../board/board_list.php?bName=여행">여행</a></li>
                <li><a href="../board/img_board_list.php?bName=사진">사진</a></li>
                <li><a href="../board/board_list.php?bName=음식">음식</a></li>
                <li><a href="../board/board_list.php?bName=동물">동물</a></li>
                <li><a href="../board/board_list.php?bName=가수">가수</a></li>
                <li><a href="../board/board_list.php?bName=유튜브">유튜브</a></li>
                <li><a href="../board/board_list.php?bName=컴퓨터">컴퓨터</a></li>
                <li><a href="../board/board_list.php?bName=음악">음악</a></li>
                <li><a href="../board/board_list.php?bName=유머">유머</a></li>
                <li><a href="../board/board_list.php?bName=스포츠">스포츠</a></li>
                <li><a href="../board/board_list.php?bName=정치">정치</a></li>
                <li><a href="../board/board_list.php?bName=경제">경제</a></li>
                <li><a href="../board/board_list.php?bName=주식">주식</a></li>
                <li><a href="../board/board_list.php?bName=썰">썰</a></li>
                <li><a href="../board/board_list.php?bName=온라인게임">온라인게임</a></li>
                <li><a href="../board/board_list.php?bName=콘솔게임">콘솔게임</a></li>
                <li><a href="../board/board_list.php?bName=롤">롤</a></li>
                <li><a href="../board/board_list.php?bName=배그">배그</a></li>
                <li><a href="../board/board_list.php?bName=넷플릭스">넷플릭스</a></li>
                <li><a href="../board/board_list.php?bName=디즈니">디즈니</a></li>
            </ul>
        </div>
    </nav>
</div>
</body>
</html>