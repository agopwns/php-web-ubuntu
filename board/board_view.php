<?php
    session_start();
    $db = include('../dbconnect.php');
    $bNO = $_GET['bNO'];

if(!empty($bNO) && empty($_COOKIE['board_view' . $bNO])) {
    $sql = 'update board set board_view_count = board_view_count + 1 where board_id = ' . $bNO;
    $result = $db->query($sql);
    if(empty($result)) {
        ?>
        <script>
            alert('오류가 발생했습니다.');
            history.back();
        </script>
        <?php
    } else {
        setcookie('board_view' . $bNO, TRUE, time() + (60 * 60 * 24), '/');
    }
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
<!--        <a href="../index.html" style="font-size:30px; font-weight: bold; color: #fff;">게시판</a>-->
    <!--  본문 영역  -->
    <?php
        $bName = $_GET['bName'];
        $bNO = $_GET['bNO'];
        $sql = "SELECT * FROM board WHERE board_id='$bNO'";
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
                    $category = $row['board_category'];
                    $recommend_count = $row['board_recommend_count'];
                    $report_count = $row['board_report_count'];

                    echo '<a href="./board_list.php?bName=' . $category . '" style="font-size:20px; font-weight: bold; color: #fff;">> '. $category . ' 게시판</a>';

                    echo "<div class='title-container'>";
                    echo "<p>제목 : $title</p>";
                    echo "</div>";
                    echo "<div class='info-container'>";
                    echo "<div class='info-child'>작성자 $userid</div>";
                    echo "<div class='info-child'>조회수 $viewCount</div>";
                    echo "<div class='info-child'>등록 시간 $regtime</div>";
                    echo "<div class='info-child'>추천 $recommend_count</div>";
                    echo "<div class='info-child'>신고 $report_count</div>";
                    echo "</div>";
                    echo "<div class='content-container' style='min-height: 400px; border-bottom: 1px solid white; border-top: 1px solid white'>";
                    echo "<div style='padding-top: 10px;'>$content</div>";
                    echo "</div>";
                    echo "<div class='control-container'>";

                    if($session_id == $userId){
                        echo "<div class='control-child'><button style='color:#fff;' onclick='modifyBoard($bNO)'>수정</a></div>";
                        echo "<div class='control-child'><button style='color:#fff;' onclick='deleteConfirm($bNO)'>삭제</button></div>";
                    }
                    echo "</div>";
                    echo "<p style='font-size:30px; color: #fff;'>댓글</p>";
                    echo "<div class='comment-container'>";
                    echo "</div>";
                    ?>
                    <!-- 댓글 내용 부분 -->
                    <?php
                    $sql = 'select * from comment where com_id = com_order and com_board_id=' . $bNO;
                    $result = $db->query($sql);
                    ?>
                    <div id="commentView">
                        <form action="comment_insert.php" method="post">
                            <input type="hidden" name="bNO" value="<?php echo $bNO?>">
                        <?php
                        while($row = $result->fetch_assoc()) {
                            ?>
                            <ul class="oneDepth" style="margin-top: 10px; margin-bottom: 10px;">
                                <li style="list-style: none;">
                                    <div id="co_<?php echo $row['com_id']?>" class="commentSet">
                                        <div class="commentInfo" style="display:flex; background: #323232;">
                                            <div class="commentId" style="background: #323232">작성자: <span class="coId" style="background: #323232;"><?php echo $row['com_userid']?></span></div>
                                            <div class="commentBtn" style="margin-left: 50px; background: #323232;">
                                                <a href="#" class="comt write" style="color:#fff; background: #323232;">댓글</a>
                                                <a href="#" class="comt modify" style="color:#fff; background: #323232;">수정</a>
                                                <a href="#" class="comt delete" style="color:#fff; background: #323232;">삭제</a>
                                            </div>
                                        </div>
                                        <div class="commentContent" style="background: #323232"><?php echo $row['com_content']?></div>
                                    </div>
                                    <?php
                                    $sql2 = 'select * from comment where com_id != com_order and com_order=' . $row['com_id'];
                                    $result2 = $db->query($sql2);
                                    while($row2 = $result2->fetch_assoc()) {
                                        ?>
                                        <ul class="twoDepth" style="margin-left: 40px; margin-top: 10px; margin-bottom: 10px;">
                                            <li style="list-style: none;">
                                                <div id="co_<?php echo $row2['com_id']?>" class="commentSet" style="background: #444444;">
                                                    <div class="commentInfo" style="display:flex; background: #444444;">
                                                        <div class="commentId" style="background: #444444;">
                                                            작성자:  <span class="coId" style="background: #444444;"><?php echo $row2['com_userid']?></span>
                                                        </div>
                                                        <div class="commentBtn" style="margin-left: 50px; background: #444444;">
                                                            <a href="#" class="comt modify" style="color:#fff; background: #444444;">수정</a>
                                                            <a href="#" class="comt delete" style="color:#fff; background: #444444;">삭제</a>
                                                        </div>
                                                    </div>
                                                    <div class="commentContent" style="background: #444444;"><?php echo $row2['com_content'] ?></div>
                                                </div>
                                            </li>
                                        </ul>
                                        <?php
                                    }
                                    ?>
                                </li>
                            </ul>
                        <?php } ?>
                        </form>
                    </div>
                    <form action="comment_insert.php" method="post">
                        <input type="hidden" name="bNO" value="<?php echo $bNO?>">
                        <input type="hidden" name="coId" value="<?php echo $userid?>">
                        <table style="margin-top: 20px;">
                            <tbody>
                            <tr>
                                <th scope="row"><label for="comContent">내용</label></th>
                                <td><textarea name="comContent" id="comContent" style="color:#fff; width:500px; height: 70px;"></textarea></td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="btnSet">
                            <input type="submit" value="작성" style="color:#fff;">
                        </div>
                    </form>
                    <?php
                }
            }
            } else {
        }
    ?>
    </div>;
</div>
</body>
<script>
    $(document).ready(function () {
        var action = '';
        $('#commentView').delegate('.comt', 'click', function () {
            //현재 위치에서 가장 가까운 commentSet 클래스를 변수에 넣는다.
            var thisParent = $(this).parents('.commentSet');
            //현재 작성 내용을 변수에 넣고, active 클래스 추가.
            var commentSet = thisParent.html();
            thisParent.addClass('active');
            //취소 버튼
            var commentBtn = '<a href="#" class="addComt cancel">취소</a>';
            //버튼 삭제 & 추가
            $('.comt').hide();
            $(this).parents('.commentBtn').append(commentBtn);
            //commentInfo의 ID를 가져온다.
            var co_no = thisParent.attr('id');
            //전체 길이에서 3("co_")를 뺀 나머지가 co_no
            co_no = co_no.substr(3, co_no.length);
            //변수 초기화
            var comment = '';
            var coId = '';
            var coContent = '';
            if($(this).hasClass('write')) {
                //댓글 쓰기
                action = 'w';
                //ID 영역 출력
                coId = '<input type="hidden" name="coId" id="coId" value="<?php echo $userid?>">';
            } else if($(this).hasClass('modify')) {
                //댓글 수정
                action = 'u';
                coId = thisParent.find('.coId').text();
                var coContent = thisParent.find('.commentContent').text();
            } else if($(this).hasClass('delete')) {
                //댓글 삭제
                action = 'd';
            }
            comment += '<div class="writeComment">';
            comment += '	<input type="hidden" name="w" value="' + action + '">';
            comment += '	<input type="hidden" name="co_no" value="' + co_no + '">';
            comment += '	<table>';
            comment += '		<tbody>';

            if(action !== 'd') {
                // comment += '			<tr>';
                // comment += '				<th scope="row"><label for="coId">아이디</label></th>';
                // comment += '				<td>' + coId + '</td>';
                // comment += '			</tr>';
            }

            if(action !== 'd') {
                comment += '			<tr>';
                comment += '				<th scope="row"><label for="coContent">내용</label></th>';
                comment += '				<td><textarea name="comContent" id="comContent" style="color:#fff">' + coContent + '</textarea></td>';
                comment += '			</tr>';
            }
            comment += '		</tbody>';
            comment += '	</table>';
            comment += '	<div class="btnSet">';
            comment += '		<input type="submit" value="확인" style="color:#fff">';
            comment += '	</div>';
            comment += '</div>';

            thisParent.after(comment);
            return false;
        });
        $('#commentView').delegate(".cancel", "click", function () {
            $('.writeComment').remove();
            $('.commentSet.active').removeClass('active');
            $('.addComt').remove();
            $('.comt').show();
            return false;
        });
    });

</script>
</html>