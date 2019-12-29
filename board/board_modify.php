<?php
session_start();
$db = include('../dbconnect.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Peanut Community</title>
    <link rel="stylesheet" href="../css/board_write.css">
</head>
<script src="//code.jquery.com/jquery.min.js"></script>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    function goHome(){
        location.href='../index.html';
    }
    function loadPage(){
        CKEDITOR.replace('bContent');
    }
    function FormSubmit(f) {
        CKEDITOR.instances.contents.updateElement();
        if(f.contents.value == "") {
            alert("내용을 입력해 주세요.");
            return false;
        }
        alert(f.contents.value); // 전송은 하지 않습니다.
        return false;
    }


</script>
<body onload="loadPage();">
<div class="container">
    <article class="boardArticle">
        <div id="boardWrite">
            <div id="boardHeader" >
                <h1 class="logo"><a href="../index.html">Peanut Community</a></h1>
                <?php
                $board_name = $_GET['bName'];
                $result = str_replace('%20' , '', $board_name);
                echo "<h3>$result 게시판 글 수정</h3>";
                ?>
            </div>
            <form action="./write_update.php" method="post">
                <table id="boardWrite">
                    <tbody>
                    <tr>
                        <th scope="row"><label for="bTitle">제목</label></th>
                        <td class="title">
                    <?php
                        $board_id = $_GET['bNO'];
                        $sql = "SELECT * FROM board WHERE board_id='$board_id'";
                        $result = $db->query($sql);

                        if($db) {
                            // 값이 있을 경우
                            if ($result->num_rows > 0) {

                                while ($row = mysqli_fetch_array($result)) {
                                    $session_userid = $_SESSION['user_id'];
                                    $userid = $row['board_userid'];
                                    $title = $row['board_title'];
                                    $content = $row['board_content'];

                                    echo "<input type='text' name='bTitle' id='bTitle' value='$title'>";
                                    echo "</td>";
                                    echo " </tr>";
                                    echo "<tr>";
                                    echo "<th scope='row'><label for='bContent'>내용</label></th>";
                                    echo "<td class='content'><textarea name='bContent' id='bContent'>$content</textarea></td>";
                                }
                            }
                        }
                    ?>
                    </tr>
                    <tr>
                        <td>
                            <?php
                            $board_id = $_GET['bNO'];
                            echo "<input type='hidden' id='bNO' name='bNO' value='$board_id'/>"
                            ?>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="btnSet">
                    <button type="submit" class="btnSubmit btn">수정</button>
                    <button onclick="goHome()" class="btnList btn">취소</button>
                </div>
            </form>
        </div>
    </article>
</div>

</body>

</html>