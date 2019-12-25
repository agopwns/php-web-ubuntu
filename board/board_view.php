<?php
    session_start();
    $db = include('../dbconnect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Welcome to the best Community">
    <meta name="keywords" content="Peanut Community">
    <link rel="stylesheet" href="../css/board_view.css">
    <title>Peanut Community</title>
    <script src="//code.jquery.com/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready( function() {
            $("#headers").load("../include/include_header.php");  // 원하는 파일 경로를 삽입하면 된다
        });

        function modifyBoard(board_id){
            location.href = './board_modify.php?bNO=' + board_id;
        }

        function deleteConfirm(board_id){
            if(confirm('정말로 삭제하시겠습니까?')){
                // yes 삭제
                location.href='./delete_update.php?bNO=' + board_id;
            }
        }

    </script>

</head>
<body>
<div class="parent_container">
    <!--  헤더  -->
    <div class='view-container'>
    <a href="../index.html" style="font-size:40px; font-weight: bold; color: #fff;">Peanut Community</a><br>
    <a href="../index.html" style="font-size:30px; font-weight: bold; color: #fff;">게시판</a>
    <!--  본문 영역  -->
    <?php
        $board_id = $_GET['bNO'];
        $sql = "SELECT * FROM board WHERE board_id='$board_id'";
        $result = $db->query($sql);

        if($db){
            // 값이 있을 경우
            if ($result->num_rows > 0) {

                while($row = mysqli_fetch_array($result)){
                    $session_userid = $_SESSION['user_id'];
                    $userid = $row['board_userid'];
                    $title = $row['board_title'];
                    $viewCount = $row['board_view_count'];
                    $content = $row['board_content'];
                    $regtime = $row['board_regtime'];

                    echo "<div class='title-container'>";
                    echo "<p>$title</p>";
                    echo "</div>";
                    echo "<div class='info-container'>";
                    echo "<div class='info-child'>작성자 $userid</div>";
                    echo "<div class='info-child'>조회수 $viewCount</div>";
                    echo "<div class='info-child'>등록 시간 $regtime</div>";
                    echo "</div>";
                    echo "<div class='content-container'>";
                    echo "<div>$content</div>";
                    echo "</div>";
                    echo "<div class='control-container'>";
                    if($session_id == $userId){
                        echo "<div class='control-child'><button style='color:#fff;' onclick='modifyBoard($board_id)'>수정</a></div>";
                        echo "<div class='control-child'><button style='color:#fff;' onclick='deleteConfirm($board_id)'>삭제</button></div>";
                    }
                    echo "</div>";
                    echo "<p style='font-size:30px; color: #fff;'>댓글</p>";
                    echo "<div class='comment-container'>";
                    echo "</div>";
                }
            }
            } else {
        }
    ?>
    </div>;
</div>


</body>
<script>


</script>
</html>