<?php
    session_start();
//    $db = include('../dbconnect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Welcome to the best Community">
    <meta name="keywords" content="Peanut Community">
    <link rel="stylesheet" href="../css/style.css">
    <title>Peanut Community</title>
</head>
<script src="//code.jquery.com/jquery.min.js"></script>

<script>

    // 숨기기 초기화
    $(document).ready(function() {
        // document.getElementById("isLogin").style.display="none";
        // document.getElementById("aLogout").style.display="none";
        $('html').scrollTop(0);
    });

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
                    document.location.href='../index.html';
                } else {
                    alert("아이디와 비밀번호를 다시 확인해주세요.");
                }
            }
        });
    }
</script>
<body>
<div class="parent_container">
    <nav id="navbar">
        <div style="position: fixed; bottom: 600px; right: 130px;">
            <a href="#">TOP</a>
        </div>
        <div class="container">
            <h1 class="logo"><a href="../index.html">Peanut Community</a></h1>
            <ul>
                <?php
                    if(isset($_SESSION['user_id'])) {
                        // 세션 존재시
                        $user_id = $_SESSION['user_id'];
                        echo "<li id='isLogin' style='margin-left: 40px; margin-top: 11px'><p>$user_id 님 환영합니다.</p></li>";
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
                <li><a href="../board/board_list.php?bName=패션">패션</a></li>
                <li><a href="../board/board_list.php?bName=드라마">드라마</a></li>
                <li><a href="../board/board_list.php?bName=영화">영화</a></li>
                <li><a href="../board/board_list.php?bName=다큐">다큐</a></li>
                <li><a href="../board/board_list.php?bName=애니">애니</a></li>
                <li><a href="../board/board_list.php?bName=만화">만화</a></li>
                <li><a href="../board/board_list.php?bName=연예">연예</a></li>
                <li><a href="../board/board_list.php?bName=여행">여행</a></li>
                <li><a href="../board/board_list.php?bName=사진">사진</a></li>
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